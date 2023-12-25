<?php 
$shop_id = $this->uri->segment('2');
$this->db->select('*');
$this->db->select('s.status as check_status');
$this->db->from('shops s');
$this->db->where('s.id',$shop_id);
$shop_details = $this->db->get()->row_array();

$this->db->select("shop_image");
$this->db->from('shops_images');
$this->db->where("shop_id", $shop_details['id']);
$shop_image = $this->db->get()->row_array(); 
$avg_rating = 0;

if(!empty($shop_details['provider_id'])){
	$provider_online=$this->db->where('id',$shop_details['provider_id'])->from('providers')->get()->row_array();
	$datetime1 = new DateTime();
	$datetime2 = new DateTime($provider_online['last_logout']);
	$interval = $datetime1->diff($datetime2);
	$days = $interval->format('%a');
	$hours = $interval->format('%h');
	$minutes = $interval->format('%i');
	$seconds = $interval->format('%s');
}else{
	$days=$hours=$minutes=$seconds=0;
}
	
$availability_details = json_decode($shop_details['availability'],true);

$category = $this->db->select('category_name')->where('id',$shop_details['category'])->get('categories')->row()->category_name;
$subcategory = $this->db->select('subcategory_name')->where('id',$shop_details['subcategory'])->get('subcategories')->row()->subcategory_name;
?>
<div class="page-wrapper">	
	<div class="content container-fluid">
	<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Shop Details</h3>
				</div>
				<div class="text-right mb-3">
				<a href="<?php echo base_url()?>shop-lists" class="btn btn-primary float-end">Back</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-body">
						<div class="service-header">
							<div class="service-inner">
								<h2><?php echo $shop_details['shop_name']?></h2>
								<address class="service-location"><i class="fas fa-location-arrow"></i> <?php echo $shop_details['shop_location']?></address>
								<div class="rating">
									<?php 
							for($x=1;$x<=$avg_rating;$x++) {
								echo '<i class="fas fa-star filled"></i>';
							}
							if (strpos($avg_rating,'.')) {
								echo '<i class="fas fa-star"></i>';
								$x++;
							}
							while ($x<=5) {
								echo '<i class="fas fa-star"></i>';
								$x++;
							}
							?>	
							<span class="d-inline-block average-rating">(<?php echo $avg_rating;?>)</span>
								</div>
								
							</div>
							
						</div>
						<div class="service-description">
							<div class="service-images service-carousel">
								<div class="images-carousel owl-carousel owl-theme">
								<?php 
								if(!empty($shop_image))
								{
									for ($i=0; $i < count($shop_image) ; $i++) { 
										if(!empty($shop_image['shop_image']) && file_exists($shop_image['shop_image'])){
											echo'<div class="item"><img src="'.base_url().$shop_image['shop_image'].'" alt="" class="img-fluid"></div>';
										} else {
											echo'<div class="item"><img src="'.base_url().'assets/img/placeholder_shop.png" alt="" class="img-fluid"></div>';
										}
									}
								
								} else {
									echo'<div class="item"><img src="'.base_url().'assets/img/placeholder_shop.png" alt="" class="img-fluid"></div>';
								}
								?>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card provider-widget clearfix">
					<div class="card-body">
						<h5 class="card-title">Service Provider</h5>
						<?php
										if(!empty($shop_details['provider_id'])){
											$provider=$this->db->select('*')->
											from('providers')->
											where('id',$shop_details['provider_id'])->
											get()->row_array();
										?>
						<div class="about-author">
							<div class="about-provider-img">
								<div class="provider-img-wrap">
									<?php
													if(!empty($provider['profile_img']) && file_exists($provider['profile_img'])){
														$image=base_url().$provider['profile_img'];
													}else{
														$image=base_url().'assets/img/user.jpg';
													}
													?>
													<a href="javascript:void(0);"><img class="img-fluid rounded-circle" alt="" src="<?php echo $image;?>"></a>
								</div>
							</div>
							<div class="provider-details">
									<a href="javascript:void(0);" class="ser-provider-name"><?php echo !empty($provider['name'])?$provider['name']:'-';?></a>
												<p class="last-seen"> 
												<?php if($provider_online['is_online']==2){ ?>
												<i class="fas fa-circle"></i> Last seen: &nbsp;
												<?php echo  (!empty($days))?$days.' days':'';?> 
												<?php if($days==0){?>
												<?php echo  (!empty($hours))?$hours.' hours':''; ?>
												<?php }?>
												 <?php if($days==0&&$hours==0){?>
												<?php echo  (!empty($minutes))?$minutes.' min':'';?>
											     <?php }?>
												 ago
												</p>
												<?php }elseif($provider_online['is_online']==1){?>
													<i class="fas fa-circle online"></i> Online</p>
												<?php }?>
												<p class="text-muted mb-1">Member Since <?php echo  date('M Y',strtotime($provider['created_at']));?></p>
							</div>
						</div>
						<hr>
						<div class="provider-info">
							<p class="mb-1"><i class="far fa-envelope"></i> <?php echo $provider['email']?></p>
											<p class="mb-0"><i class="fas fa-phone-alt"></i> xxxxxxxx<?php echo rand(00,99)?></p>
						</div>
							<?php } ?>
					</div>
				</div>
				<div class="card available-widget">
					<div class="card-body">
						<h5 class="card-title">Shop Availability</h5>
						<ul>
							<?php
					if(!empty($availability_details))
					{
					foreach ($availability_details as $availability) {

					$day = $availability['day'];
					$from_time = $availability['from_time'];
					$to_time = $availability['to_time'];

					  if($day == '1')
					  {
						$weekday = 'Monday';
					  }
					  elseif($day == '2')
					  {
						$weekday = 'Tuesday';
					  }
					  elseif($day == '3')
					  {
						$weekday = 'Wednesday';
					  }
					  elseif($day == '4')
					  {
						$weekday = 'Thursday';
					  }
					  elseif($day == '5')
					  {
						$weekday = 'Friday';
					  }
					  elseif($day == '6')
					  {
						$weekday = 'Saturday';
					  }
					  elseif($day == '7')
					  {
						$weekday = 'Sunday';
					  }
					  elseif($day == '0')
					  {
						$weekday = 'Sunday';
					  }
					 
					echo '<li><span>'.$weekday.'</span>'.$from_time.' - '.$to_time.'</li>'; 
					}
					}
					else
					{
						echo '<li class="text-center">No Details found</li>';
					}
					
					?>
						</ul>
					</div>				
				</div>
			</div>
		</div>
		
		
		<div class="row">			
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						
						<h5 class="card-title">Service Details</h5>
						<div class="row col-md-12">
							<p class="text-muted me-2" title="category">Category: <span class="text-info"><?php echo $category; ?></span></p>
							<p class="text-muted me-2"  title="sub category">Sub Category: <span class="text-info"><?php echo $subcategory; ?></span></p>
						</div>
						<div class="row col-md-12"><p><?php echo $shop_details['description']?></p></div>
						
						<table class="table table-bordered">
							<thead>
								<tr>
								    <th>Service Name</th>									
									<th class="text-center">Duration</th>
									<th class="text-center">Amount</th>										
								</tr>
							</thead>
							<tbody>
							
							<?php $uid = $shop_details['provider_id'];
							$WHERE = array('ser.user_id' => $uid, 'ser.status' => 1,'list.shop_id' => $shop_details['id'] );
							$this->db->select('ser.id, ser.user_id, ser.service_title, ser.currency_code, ser.service_amount, ser.category, ser.subcategory, ser.sub_subcategory, ser.duration, ser.duration_in, ser.status, ser.about, list.id as listid,  list.service_offer_id, list.staff_id'); 
							$this->db->from('services AS ser');
							$this->db->join('shop_services_list AS list', 'list.service_offer_id = ser.id AND list.delete_status = 0', 'left');
							$this->db->where($WHERE);
							$serv_lists = $this->db->get()->result_array();
							if (count($serv_lists) > 0) {
								foreach ($serv_lists as $key => $value) {
								?>
						
									<tr>
										<td class="data_service" data-serviceoffer="<?php echo $value['service_title'];?>">
											<?php echo($value['service_title']);?><br/>
											<i class="text-info small"><?php echo $value['about'];?></i>
										</td>
										<td class="text-center data_duration">
											<?php echo $value['duration']."<span class='small'>".$value['duration_in']."</span>"; ?>
										</td>
										<td align="center">
										<?php echo $value['service_amount']; ?>	
										</td>
									
									</tr>
								<?php } }  else { ?>	
									<tr><td colspan="3" class="text-center">No Services Found</td></tr>
								<?php } ?>
												
							</tbody>
						</table>
						
												
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>