<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Revenue</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white filter-btn me-2" href="javascript: void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin/Revenue" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
					
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider Name</label>
								<select class="form-control select" name="provider_name" >
									<option value="">Select Vendor name</option>
									<?php foreach ($provider_list as $provider) { ?>
									<option value="<?php echo $provider['id']?>"><?php echo $provider['name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="date" id="date">
								</div>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->
		
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class=" table table-hover table-center mb-0 payment_table">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<th>Provider Name</th>
										<th>User Name</th>
										<th>Amount</th>										
										<th>Offer</th>
										<th>Coupon Discount</th>
										<th>Rewards</th>
										<th>Commission Amount</th>
										<th>VAT</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
								if(!empty($list)) {
									$i=1;
								
								foreach ($list as $rows) { 
                                    $amount=$rows['amount'];
                                    $comi=$rows['commission'];
									$vat=($rows['vat']=='' ? 0 : $rows['vat']);
									$comAount=$amount*$comi/100;
									$vatAmount=$amount*$vat/100;
									
									
									if($rows['offersid'] > 0) {
										$off=$this->db->where('id', $rows['offersid'])->get('service_offers')->row_array();
										$offPer = $off['offer_percentage']."%";
										$offAmount=($amount) * ($off['offer_percentage']/100);
										$off_Amount=currency_conversion($rows['currency_code']).$offAmount . "<br>(".$offPer.")";
									} else{
										$offAmount = 0;$offPer='';
										$off_Amount = currency_conversion($rows['currency_code']).$offAmount;
									}
									
									if($rows['couponid'] > 0) {
										$cpn=$this->db->where('id', $rows['couponid'])->get('service_coupons')->row_array();
										if($cpn['coupon_type'] == 1){
											$cpnPer = $cpn['coupon_percentage']."%";
											$cpnAmount=($amount) * ($cpn['coupon_percentage']/100);
											$cpn_Amount=currency_conversion($rows['currency_code']).$cpnAmount . "<br>(".$cpnPer.")";
										} else {
											$cpnPer = '';
											$cpnAmount = $cpn['coupon_amount'];
											$cpn_Amount = currency_conversion($rows['currency_code']).$cpn['coupon_amount'];
										}
									} else{
										$cpnAmount = 0;$cpnPer='';
										$cpn_Amount = currency_conversion($rows['currency_code']).$cpnAmount;
									}
									
									
									$rewardid = $rows['rewardid'];
									$reward = $this->db->where("id",$rewardid)->get("service_rewards")->row_array(); 
									if($rewardid > 0) {
										if ($reward['reward_type'] == 1) {
											$rwdPer = $reward['reward_discount']."%";
											$rwdAmt = ($amount) * ($reward['reward_discount'] / 100) ; 	
											if(is_nan($rwdAmt)) $rwdAmt = 0;	
											$rwd_Amount = currency_conversion($rows['currency_code']).$rwdAmt. "<br>(".$rwdPer.")";		
										} else if ($reward['reward_type'] == 0) {
											$rwdAmt  = 0;	$rwdPer = ''; $rwd_Amount ='Free Service';
										}
									} else {
										$rwdAmt  = 0;	$rwdPer = ''; 
										$rwd_Amount = currency_conversion($rows['currency_code']).$rwdAmt;
									}
									
									$grandTotal = ($amount - $offAmount) - $cpnAmount;
									$grandTotal = $grandTotal - $rwdAmt;
									
									$vatAmount = number_format($vatAmount,2);
									$grandTotal = number_format($grandTotal,2);
									
										
									if($rows['booking_id'] > 0) { 
										$shopid = $this->db->select('shop_id')->where('id',$rows['booking_id'])->get('book_service')->row();
										$taxqry = $this->db->where('id',$shopid->shop_id)->get('shops')->row_array(); 
										
										if($taxqry['tax_allow'] == 0){ $vatAmount = 0; }
										
									}									
									// print_r($vatAmount);
									
                                ?>
								<tr>
									 
									<td><?php echo $i++; ?></td> 
									<td><?php echo date(settingValue('date_format'), strtotime($rows['date']));?></td> 
									<td><?php echo ($rows['provider']); ?></td> 
									<td><?php echo ($rows['user']); ?></td> 
									<td><?php echo currency_conversion($rows['currency_code']).($rows['amount']); ?></td> 
									<td><?php echo $off_Amount; ?></td> 
									<td><?php echo $cpn_Amount; ?></td> 
									<td><?php echo $rwd_Amount; ?></td> 
									<td><?php echo currency_conversion($rows['currency_code']).($comAount); ?></td> 
									<td><?php echo currency_conversion($rows['currency_code']).($vatAmount); ?></td> 
									
									<td><label class="badge badge-success">Completed</label></td> 
									<td><a target="_blank" href="<?php echo base_url()?>invoice-revenue/<?php echo $rows['id']?>" class="btn btn-info btn-sm text-white">View</a></td>
									<!--Compete Request Accept update_status_user-->

								</tr>
								<?php } } ?>
								</tbody>
							</table>
						</div> 
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>