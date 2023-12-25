<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_service_coupon_history'])) ? $user_language[$user_selected]['lg_service_coupon_history'] : $default_language['en']['lg_service_coupon_history']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                        	<?php echo (!empty($user_language[$user_selected]['lg_service_coupon_history'])) ? $user_language[$user_selected]['lg_service_coupon_history'] : $default_language['en']['lg_service_coupon_history']; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$user_id = $this->session->userdata('id');
?>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">
				<div class="card transaction-table mb-0">
					<div class="card-body" id="dataList">
						<div class="table-responsive">
							<?php 
									if(count($list)>0){?>
							<table class="table mb-0" id="order-summary">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_coupon_name'])) ? $user_language[$user_selected]['lg_coupon_name'] : $default_language['en']['lg_coupon_name']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_coupon_offers'])) ? $user_language[$user_selected]['lg_coupon_offers'] : $default_language['en']['lg_coupon_offers']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_start_date'])) ? $user_language[$user_selected]['lg_start_date'] : $default_language['en']['lg_start_date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_end_date'])) ? $user_language[$user_selected]['lg_end_date'] : $default_language['en']['lg_end_date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_valid_for'])) ? $user_language[$user_selected]['lg_valid_for'] : $default_language['en']['lg_valid_for']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_user_limits'])) ? $user_language[$user_selected]['lg_user_limits'] : $default_language['en']['lg_user_limits']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_coupon_used'])) ? $user_language[$user_selected]['lg_coupon_used'] : $default_language['en']['lg_coupon_used']; ?></th>										
										<th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Action'])) ? $user_language[$user_selected]['lg_Action'] : $default_language['en']['lg_Action']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($list)){ 										
										foreach ($list as $val) {
											$sdate = date('d-m-Y', strtotime($val['start_date']));
											$edate = date('d-m-Y', strtotime($val['end_date']));
											if($val['coupon_type'] == 1){
												$offer = $val['coupon_percentage']."%";;
											} else {
												$offer = currency_conversion($val['currency_code']).$val['coupon_amount'];
											}
											
											if($val['user_limit'] == 0){
												$lmt_txt = 'No Limits';
											} else {
												$lmt_txt = $val['user_limit'];												
											}
									?>									
										
											<tr>
												<td><?php echo $val['service_title'];?></td>
												<td><i class="text-info"><?php echo $val['coupon_name']?></i></td>
												<td class="text-end"><?php echo $offer?></td>
												<td><?php echo date("d-m-Y", strtotime($val['start_date']))?></td>
												<td><?php echo date("d-m-Y", strtotime($val['end_date']))?></td>
												<td><?php echo $val['valid_days']?> days</td>
												<td><?php echo $lmt_txt?></td>
												<td><span data-bs-toggle="tooltip" title="No of Times Coupon Used By the User"><?php echo $val['user_limit_count']?></span></td>
												<td>
												<?php if($val['status'] == 3) { ?>
													<a href="javascript:" class="btn btn-sm bg-danger-light me-2" title="Coupon Expired" data-id="<?php echo $val['id']; ?>"><i class="fas fa-info-circle me-1 ms-1"></i>Expired</a>
												<?php } else if($val['status'] == 1) { ?>
													<a href="javascript:" class="btn btn-sm bg-success-light me-2 coupon-service" title="Click here to Deactive the Coupon" data-id="<?php echo $val['id']; ?>" data-text="Inactive" data-bs-toggle="modal" data-bs-target="#statusConfirmModal"><i class="fas fa-info-circle me-1 ms-1"></i>Active</a>
												<?php } else { ?>
													<a href="javascript:" class="btn btn-sm bg-warning-light me-2 coupon-service" title="Click here to Active the Coupon" data-id="<?php echo $val['id']; ?>" data-text="Activate" data-bs-toggle="modal" data-bs-target="#statusConfirmModal"><i class="fas fa-info-circle me-1 ms-1"></i>Inactive</a>
												<?php } ?>
												</td>
												<td><?php if($val['status'] != 3) { ?>
												<a class="btn btn-sm bg-primary-light editServiceCoupon" data-id="<?php echo $val['id']; ?>" data-service_title="<?php echo $val['service_title'];?>" data-service_amount="<?php echo currency_conversion($val['currency_code']).$val['service_amount'];?>" ><i class="far fa-edit"></i></a>
												<?php } ?>
												<a href="javascript:void(0)" class="btn btn-sm bg-success-light viewServiceCoupon"  data-id="<?php echo $val['id']; ?>" data-service_title="<?php echo $val['service_title'];?>" data-service_amount="<?php echo currency_conversion($val['currency_code']).$val['service_amount'];?>"><i class="far fa-eye"></i></a>
												<a href="javascript:void(0)" class="btn btn-sm bg-danger-light coupons-delete" data-bs-toggle="modal" data-bs-target="#coupondeleteConfirmModal" data-id="<?php echo $val['id']; ?>"><i class="far fa-trash-alt"></i></a></td>
											</tr>
									<?php }
										} else {
											$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
											echo "<tr><td colspan='7' class='text-center'>".$norecord."</td></tr>";
										}
									?>
									</tbody>
								</table>
							</div>
							
						</div>
						
					</div>			
				</div>
			</div>

		</div>
	</div>
<div class="modal fade" id="edit_coupon_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_edit_coupon'])) ? $user_language[$user_selected]['lg_edit_coupon'] : $default_language['en']['lg_edit_coupon']; ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           <div class="modal-body">
                <form accept-charset="UTF-8" id="coupon_form" method="POST">
					<div class="row">               
						<div class="form-group col-lg-8 d-none">
							<h6><?php echo (!empty($user_language[$user_selected]['lg_Service_Name'])) ? $user_language[$user_selected]['lg_Service_Name'] : $default_language['en']['lg_Service_Name']; ?></h6>
							<label id="sname"></label>
							
						</div>

						<div class="form-group col-lg-4 d-none">
							<h6><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?></h6>
							<label id="samount"></label>
							
						</div>
					</div>
                    <div class="row">
						 <div class="col-md-6">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_coupon_name'])) ? $user_language[$user_selected]['lg_coupon_name'] : $default_language['en']['lg_coupon_name']; ?></label>
                            <div class="input-group mb-3">							  
							  <div class="input-group-append">
								<span class="input-group-text" id="basic-addon2">PRO</span>
							  </div>
							 <input type="text" class="form-control inputcls" id="coupon_name" value="" >
							 <input type="hidden" id="service_id" value="">		
							 <input type="hidden" id="coupon_id" value="">		
							</div>
                        </div>
						<div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_how_many_user_can_use_this'])) ? $user_language[$user_selected]['lg_how_many_user_can_use_this'] : $default_language['en']['lg_how_many_user_can_use_this']; ?></label>
                            <input type="number" id="user_limit" class="form-control inputcls" value="" min="0" max="99999" >
                        </div>
						<div class="col-md-6 mb-1">
							<label><?php echo (!empty($user_language[$user_selected]['lg_coupon_type'])) ? $user_language[$user_selected]['lg_coupon_type'] : $default_language['en']['lg_coupon_type']; ?></label><br>
                            <label class="radio-inline">
							  <input type="radio" name="coupontype" id="percentage" value="1" class="cpntype"/>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_percentage'])) ? $user_language[$user_selected]['lg_percentage'] : $default_language['en']['lg_percentage']; ?>
							</label>
							<label class="radio-inline">
							  <input type="radio" name="coupontype" id="amount" value="2" class="cpntype" />&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_fixed_amount'])) ? $user_language[$user_selected]['lg_fixed_amount'] : $default_language['en']['lg_fixed_amount']; ?>
							</label>
                        </div>
                        <div class="col-md-6 mb-3" id="coupon_per">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_percentage'])) ? $user_language[$user_selected]['lg_percentage'] : $default_language['en']['lg_percentage']; ?> %</label>
                            <input type="text" id="coupon_percent" class="form-control number inputcls" value="" >
						</div>
						<div class="col-md-6 mb-3" id="coupon_amt">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></label>
                            <input type="text" id="coupon_price" class="form-control number" value="" >                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_from'])) ? $user_language[$user_selected]['lg_from'] : $default_language['en']['lg_from']; ?></label>
                            <input type="text" id="start_date" class="form-control datetimepicker inputcls" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_valid_in_days'])) ? $user_language[$user_selected]['lg_valid_in_days'] : $default_language['en']['lg_valid_in_days']; ?><</label>
                            <input type="number" id="valid_days" class="form-control inputcls" value="" min="1" max="365" >
                        </div>	
						<div class="col-md-12 mb-1">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?></label>
                            <textarea class="form-control inputcls" name="description" id="cdescription"></textarea>
                        </div>
                    </div>
					<b id="error_service" class="text-danger"></b>
                </form>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="edit_submit_coupon"><?php echo (!empty($user_language[$user_selected]['lg_save'])) ? $user_language[$user_selected]['lg_save'] : $default_language['en']['lg_save']; ?></button>
                <button type="button" class="btn btn-danger coupon_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="coupondeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_delete_service_coupon'])) ? $user_language[$user_selected]['lg_delete_service_coupon'] : $default_language['en']['lg_delete_service_coupon']; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="acc_msg"><?php echo (!empty($user_language[$user_selected]['lg_sure_to_delete_coupon_service'])) ? $user_language[$user_selected]['lg_sure_to_delete_coupon_service'] : $default_language['en']['lg_sure_to_delete_coupon_service']; ?></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_coupon_confirm"><?php echo (!empty($user_language[$user_selected]['lg_YES'])) ? $user_language[$user_selected]['lg_YES'] : $default_language['en']['lg_YES']; ?></a>
				<button type="button" class="btn btn-danger si_coupon_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="statusConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title" id="status_acc_title"></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="status_acc_msg"></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_status_confirm"><?php echo (!empty($user_language[$user_selected]['lg_YES'])) ? $user_language[$user_selected]['lg_YES'] : $default_language['en']['lg_YES']; ?></a>
				<button type="button" class="btn btn-danger si_status_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="couponViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_name"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           <div class="modal-body">
                <form accept-charset="UTF-8" id="coupon_form" method="POST"  >
					<div class="row">               
						<div class="form-group col-lg-6 d-none">
							<h6><?php echo (!empty($user_language[$user_selected]['lg_Service_Name'])) ? $user_language[$user_selected]['lg_Service_Name'] : $default_language['en']['lg_Service_Name']; ?></h6>
							<label id="view_sname" class="text-info"></label>							
						</div>
						<div class="form-group col-lg-3 d-none">
							<h6><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?></h6>
							<label id="view_samount" class="text-info"></label>							
						</div>
					</div>
                    <div class="row">						
                        						
						<div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_how_many_user_can_use_this'])) ? $user_language[$user_selected]['lg_how_many_user_can_use_this'] : $default_language['en']['lg_how_many_user_can_use_this']; ?> :-</label>
                           <span id="view_user" class="text-info"></span>
                        </div>
						<div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_count_of_coupon_used'])) ? $user_language[$user_selected]['lg_count_of_coupon_used'] : $default_language['en']['lg_count_of_coupon_used']; ?> :- </label>
                             <span id="view_num" class="text-info"></span>
                        </div>	
						
						<div class="col-md-4 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></label>
                             <div id="view_statu" class="text-info"></div>
                        </div>
						<div class="col-md-4 mb-3">
							<label><?php echo (!empty($user_language[$user_selected]['lg_coupon_type'])) ? $user_language[$user_selected]['lg_coupon_type'] : $default_language['en']['lg_coupon_type']; ?></label><br>
                            <div id="view_type" class="text-info"></div>
                        </div>
                        <div class="col-md-4 mb-3" id="coupon_vper">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_percentage'])) ? $user_language[$user_selected]['lg_percentage'] : $default_language['en']['lg_percentage']; ?> %</label>
                            <div class="text-info">
								<span id="view_per"></span>
								<span>%</span>
							</div>
						</div>
						<div class="col-md-4 mb-3" id="coupon_vamt">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></label>
                            <div class="text-info">
								<span><?php echo currency_conversion($val['currency_code']); ?></span>
								<span id="view_amt"></span>
							</div>                           
                        </div>
						
						<div class="col-md-4 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_from_date'])) ? $user_language[$user_selected]['lg_from_date'] : $default_language['en']['lg_from_date']; ?></label>
                             <div id="view_from" class="text-info"></div>
                        </div>
						<div class="col-md-4 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_end_date'])) ? $user_language[$user_selected]['lg_end_date'] : $default_language['en']['lg_end_date']; ?></label>
                             <div id="view_end" class="text-info"></div>
                        </div>       
						<div class="col-md-4 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_valid_in_days'])) ? $user_language[$user_selected]['lg_valid_in_days'] : $default_language['en']['lg_valid_in_days']; ?></label>
                             <div id="view_until" class="text-info"></div>
                        </div>	
						 				
						<div class="col-md-12 mb-1">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?></label>
                            <div id="view_desc" class="text-info"></div>
                        </div>
                    </div>
					
                </form>                
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-danger coupon_close" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_close'])) ? $user_language[$user_selected]['lg_close'] : $default_language['en']['lg_close']; ?></button>
            </div>
        </div>
    </div>
</div>