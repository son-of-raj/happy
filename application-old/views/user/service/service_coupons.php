<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_service_coupons'])) ? $user_language[$user_selected]['lg_service_coupons'] : $default_language['en']['lg_service_coupons']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_service_coupons'])) ? $user_language[$user_selected]['lg_service_coupons'] : $default_language['en']['lg_service_coupons']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$user_id = $this->session->userdata('id');
$count = $this->db->where('provider_id',$user_id)->where('status != ',0)->get('service_coupons')->num_rows(); // Coupon Count
?>
<style>
label:not(.radio-inline){font-weight:600}
</style>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">

				<h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_service_coupons'])) ? $user_language[$user_selected]['lg_service_coupons'] : $default_language['en']['lg_service_coupons']; ?></h4>
				<div class="col-lg-12 float-end mb-3">
					<button class="btn btn-sm bg-success-light addnewcoupon float-end" disabled title="Select Service To Add Coupon"><i class="fas fa-plus"></i> Add Coupon</button>
					<?php if($count > 0) { ?>
						<a href="<?php echo base_url()?>coupon-details" target="_blank" class="btn btn-sm bg-warning-light viewcoupon me-2 ml-2 float-end"><i class="fas fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_view_coupon_history'])) ? $user_language[$user_selected]['lg_view_coupon_history'] : $default_language['en']['lg_view_coupon_history']; ?></a>
					<?php } ?>
				</div>
				
				<div class="card transaction-table mb-0">
					<div class="card-body" id="dataList">
						<div class="table-responsive">
							<?php if(!empty($lists)){ ?>
							<table class="table mb-0">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th>S.No</th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Coupons'])) ? $user_language[$user_selected]['lg_Coupons'] : $default_language['en']['lg_Coupons']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Select'])) ? $user_language[$user_selected]['lg_Select'] : $default_language['en']['lg_Select']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($lists)){ 
										$sno = 1;
										foreach ($lists as $val) {
											$cname = $this->db->select('coupon_name')->where('provider_id',$user_id)->where('service_id',$val['id'])->where('status', 1)->get('service_coupons')->row()->coupon_name;
											
											if($cname != ''){
												 $bg ='success'; 
												 $textbg = $cname;
												 $disabled = 'disabled';
											} else {
												$bg='';
												$textbg = '-';
												$disabled = '';
											}
											
									?>
										<tr>
											<td><?php echo $sno++;?></td>
											<td><?php echo $val['service_title']?></td>
											<td><?php echo currency_conversion($val['currency_code']).$val['service_amount'];?></td>
											<td><span class="badge bg-<?php echo $bg; ?>-light"><?php echo $textbg; ?></span></td>
											<td class="text-center">
												<input type="checkbox" name="coupons[]" id="couponsid" class="couponschkbox" value="<?php echo $val['id']; ?>" <?php echo $disabled;?> />
											</td>
										</tr>
									<?php 
										}
									} else {
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo "<tr><td colspan='5' class='text-center'>".$norecord."</td></tr>";
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
<div class="modal fade" id="coupons-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_add_coupon'])) ? $user_language[$user_selected]['lg_add_coupon'] : $default_language['en']['lg_add_coupon']; ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" title="To Select any one service">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form accept-charset="UTF-8" id="coupon_form" method="POST"  >
                    <div class="row">
						 <div class="col-md-6">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_coupon_name'])) ? $user_language[$user_selected]['lg_coupon_name'] : $default_language['en']['lg_coupon_name']; ?></label>
                            <div class="input-group mb-3">							  
							  <div class="input-group-append">
								<span class="input-group-text" id="basic-addon2">PRO</span>
							  </div>
							 <input type="text" class="form-control inputcls"  name="coupon_name" id="coupon_name" value="" >
							 <input type="hidden" id="service_id" value="">	
							</div>
                        </div>
						<div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_how_many_user_can_use_this'])) ? $user_language[$user_selected]['lg_how_many_user_can_use_this'] : $default_language['en']['lg_how_many_user_can_use_this']; ?></label>
                            <input type="number" id="user_limit" class="form-control inputcls" value="" min="0" max="99999" >
                        </div>
						<div class="col-md-6 mb-1">
							<label><?php echo (!empty($user_language[$user_selected]['lg_coupon_type'])) ? $user_language[$user_selected]['lg_coupon_type'] : $default_language['en']['lg_coupon_type']; ?></label><br>
                            <label class="radio-inline">
							  <input type="radio" name="coupontype" id="percentage" value="1" class="cpntype" checked/>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_percentage'])) ? $user_language[$user_selected]['lg_percentage'] : $default_language['en']['lg_percentage']; ?>
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
                            <label><?php echo (!empty($user_language[$user_selected]['lg_price'])) ? $user_language[$user_selected]['lg_price'] : $default_language['en']['lg_price']; ?></label>
                            <input type="text" id="coupon_price" class="form-control number" value="" >                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_from'])) ? $user_language[$user_selected]['lg_from'] : $default_language['en']['lg_from']; ?></label>
                            <input type="text" id="start_date" class="form-control datetimepicker inputcls" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_valid_in_days'])) ? $user_language[$user_selected]['lg_valid_in_days'] : $default_language['en']['lg_valid_in_days']; ?></label>
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
                <button type="button" class="btn btn-success" id="submit_coupon"><?php echo (!empty($user_language[$user_selected]['lg_save'])) ? $user_language[$user_selected]['lg_save'] : $default_language['en']['lg_save']; ?></button>
                <button type="button" class="btn btn-danger coupon_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
            </div>
        </div>
    </div>
</div>

