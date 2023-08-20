<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Payment_History'])) ? $user_language[$user_selected]['lg_Payment_History'] : $default_language['en']['lg_Payment_History']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Payment_History'])) ? $user_language[$user_selected]['lg_Payment_History'] : $default_language['en']['lg_Payment_History']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			 <?php $this->load->view('user/home/user_sidemenu');?>
		 
			<div class="col-xl-9 col-md-8">
				<ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url() ?>user-payment"><?php echo (!empty($user_language[$user_selected]['lg_services'])) ? $user_language[$user_selected]['lg_services'] : $default_language['en']['lg_services']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url() ?>user-order-payment"><?php echo (!empty($user_language[$user_selected]['lg_products'])) ? $user_language[$user_selected]['lg_products'] : $default_language['en']['lg_products']; ?></a>
                    </li>
                </ul>
						
				<div class="card transaction-table mb-0">
					<div class="card-body">
						<div class="table-responsive">
							<?php
									if(count($services)>0){?>
							<table class="table mb-0" id="order-summary">
								<?php } else{?>
									<table class="table mb-0" >
								<?php }?>
								<thead>
									<tr>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></th>
										
										<th><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
									</tr>
								</thead>
								<tbody>

									<?php
									if(count($services)>0){
										$user_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();

										$service_images='';
									 foreach($services as $row){ 
									    $userdetails = $this->db->where('id', $row['provider_id'])->get('providers')->row_array();
									    $amount_refund=''; $by_whom = '';
									 	if(!empty($row['reject_paid_token'])){
									 	if($row['admin_reject_comment']=="This service amount favour for User"){
									 		$amount_refund="Amount refund to User";
									 	}else{
                                          $amount_refund="Amount refund to Provider";
									 	}
										$by_whom = ' by Admin';
									 }
									 $user_currency = get_user_currency();
                                     $user_currency_code = $user_currency['user_currency_code'];
									$service_amount = get_gigs_currency($row["amount"], $row["currency_code1"], $user_details['currency_code']);

									 	if(!empty($row['service_image'])){									 		
										 $service_image=$this->db->where('service_id',$row['service_id'])->get('services_image')->row_array();
										 if(!empty($service_image['service_image']) && file_exists($service_image['service_image'])){
											 $service_images=$service_image['service_image'];
											}else{
												$service_images="";
											}
									 	}
										if (!empty($userdetails['profile_img']) && file_exists($userdetails['profile_img'])) {
											$proimage = base_url() . $userdetails['profile_img'];
										} else {
											$proimage = base_url() . 'assets/img/user.jpg';
										}


									 	?>
									
									<tr>
										
										<td>
											<img class="avatar-xs rounded-circle" src="<?php echo $proimage;?>" alt=""> <?php echo $userdetails['name'];?>
										</td>
										<td>
											<a href="javascript:void(0);">
												<img src="<?php echo base_url().$service_images;?>" class="pro-avatar" alt=""> <?php echo $row['service_title'];?>
											</a>
										</td>
										<td>
											<?php 
											  if(!empty($row["service_date"])){
                                             $date=date(settingValue('date_format'), strtotime($row["service_date"]));
                                            }else{
                                                $date='-';                                
                                            } 
										 ?>
										 <?php echo  $date; ?></td>
										<td><strong><?php echo currency_conversion($user_currency_code).$service_amount;?></strong></td>
										<td>
											<?php if(!empty($row['reject_paid_token'])){ ?>
												<span class="badge bg-success-light"><?php echo $amount_refund.$by_whom;?></span>

										<?php } if($row['booking_status']==6){?>
											<span class="badge bg-success-light"><?php echo (!empty($user_language[$user_selected]['lg_Payment_Completed'])) ? $user_language[$user_selected]['lg_Payment_Completed'] : $default_language['en']['lg_Payment_Completed']; ?></span>
										<?php }else if($row['booking_status']==5&&empty($row['reject_paid_token'])){?>
											<span class="badge bg-danger-light"><?php echo (!empty($user_language[$user_selected]['lg_Use_Rejected'])) ? $user_language[$user_selected]['lg_Use_Rejected'] : $default_language['en']['lg_Use_Rejected']; ?></span>
										<?php }else if($row['booking_status']==7&&empty($row['reject_paid_token'])){?>
                                            <span class="badge bg-danger-light"><?php echo (!empty($user_language[$user_selected]['lg_Provider_Rejected'])) ? $user_language[$user_selected]['lg_Provider_Rejected'] : $default_language['en']['lg_Provider_Rejected']; ?></span>
										<?php }?>
										</td>
									</tr>
								<?php } }else{?>
									<tr> <td colspan="5"> <div class="text-center text-muted"><?php echo (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou']; ?></div></td> </tr>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>