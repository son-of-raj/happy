<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Service_Offer_History'])) ? $user_language[$user_selected]['lg_Service_Offer_History'] : $default_language['en']['lg_Service_Offer_History']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Service_Offer_History'])) ? $user_language[$user_selected]['lg_Service_Offer_History'] : $default_language['en']['lg_Service_Offer_History']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">
				<div class="card transaction-table mb-0">
					<div class="card-body" id="dataList">
						<div class="table-responsive">
							<?php 
									if(!empty($offers) && is_countable($offers) && count($offers)>0){?>
							<table class="table mb-0">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_offer'])) ? $user_language[$user_selected]['lg_offer'] : $default_language['en']['lg_offer']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_start_date'])) ? $user_language[$user_selected]['lg_start_date'] : $default_language['en']['lg_start_date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_end_date'])) ? $user_language[$user_selected]['lg_end_date'] : $default_language['en']['lg_end_date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_time_range'])) ? $user_language[$user_selected]['lg_time_range'] : $default_language['en']['lg_time_range']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_created_date'])) ? $user_language[$user_selected]['lg_created_date'] : $default_language['en']['lg_created_date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Action'])) ? $user_language[$user_selected]['lg_Action'] : $default_language['en']['lg_Action']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($offers)){ 
										
										foreach ($offers as $val) {
												$sdate = date('d-m-Y', strtotime($val['start_date']));
												$edate = date('d-m-Y', strtotime($val['end_date']));
												$start_time = date('h:i A', strtotime($val['start_time']));
												$end_time = date('h:i A', strtotime($val['end_time']));
											?>
											<tr>
												<td><?php echo $val['service_title']?></td>
												<td><?php echo currency_conversion($val['currency_code']).$val['service_amount'];?></td>
												<td><?php echo $val['offer_percentage']?>%</td>
												<td><?php echo date("d-m-Y", strtotime($val['start_date']))?></td>
												<td><?php echo date("d-m-Y", strtotime($val['end_date']))?></td>
												
												<td><?php echo $start_time?> - <?php echo $end_time?></td>
												<td><?php echo date("d-m-Y h:i A", strtotime($val['created_at']))?></td>
												<td>
													<a class="btn btn-sm bg-success-light editServiceOffers" data-id="<?php echo $val['id']; ?>" data-offers_per="<?php  echo $val['offer_percentage']; ?>" data-start_date="<?php echo $sdate; ?>" data-end_date="<?php echo $edate; ?>" data-start-time="<?php echo $start_time?>" data-end-time="<?php echo $end_time?>" data-service_id="<?php echo $val['service_id']; ?>" ><i class="far fa-edit"></i></a>

												<a href="javascript:void(0)" class="btn btn-sm bg-danger-light ml-2 me-2 offers-delete" data-bs-toggle="modal" data-bs-target="#offersdeleteConfirmModal" data-id="<?php echo $val['id']; ?>"><i class="far fa-trash-alt"></i></a></td>
											</tr>
											<?php 
										}
									} else {
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo "<tr><td colspan='7' class='text-center'>".$norecord."</td></tr>";
									}
									?>
									</tbody>
								</table>
							</div>
							
						</div>
						<!-- Pagination Links -->
	                    <?php
	                    if (!empty($offers)) {
	                        echo $this->ajax_pagination->create_links();
	                    }
	                    ?>
					</div>			
				</div>
			</div>

		</div>
	</div>
<div class="modal fade" id="edit_offer_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_offer'])) ? $user_language[$user_selected]['lg_offer'] : $default_language['en']['lg_offer']; ?></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_offer'])) ? $user_language[$user_selected]['lg_offer'] : $default_language['en']['lg_offer']; ?> %</label>
                            <input type="text" id="offer_percentage" class="form-control number eoffercls" value="">
                            <input type="hidden" id="hservice_id" value="">
							<input type="hidden" id="offerid" value="">
                        </div>
                        <div class="col-md-4">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_from'])) ? $user_language[$user_selected]['lg_from'] : $default_language['en']['lg_from']; ?></label>
                            <input type="text" id="start_date" class="form-control datetimepicker-start eoffercls" value="">
                        </div>
                        <div class="col-md-4">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_to'])) ? $user_language[$user_selected]['lg_to'] : $default_language['en']['lg_to']; ?></label>
                            <input type="text" id="end_date" class="form-control datetimepicker-end eoffercls" value="">
                        </div>
                        <div class="col-md-4">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_start_time'])) ? $user_language[$user_selected]['lg_start_time'] : $default_language['en']['lg_start_time']; ?></label>
                            <select id="start_time" class="form-control">
                                <option value="">Select</option>
                                <?php
                                $start_time = strtotime('9:00');
                                while($start_time <= strtotime('21:00'))
                                {
                                    $stime = date ("h:i A", $start_time);
                                    $add_mins  = 30 * 60;
                                    $start_time += $add_mins; // to check endtie=me
                                    ?>
                                    <option value="<?php echo $stime?>"><?php echo $stime?></option>
                                    <?php 
                                } 
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label><?php echo (!empty($user_language[$user_selected]['lg_end_time'])) ? $user_language[$user_selected]['lg_end_time'] : $default_language['en']['lg_end_time']; ?></label>
                            <select id="end_time" class="form-control">
                                <option value="">Select</option>
                                <?php
                                $end_time = strtotime('9:00');
                                while($end_time <= strtotime('21:00'))
                                {
                                    $etime = date ("h:i A", $end_time);
                                    $add_mins  = 30 * 60;
                                    $end_time += $add_mins; // to check endtie=me
                                    ?>
                                    <option value="<?php echo $etime?>"><?php echo $etime?></option>
                                    <?php 
                                } 
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
                <b id="error_service" class="text-danger"></b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="edit_submit_offer"><?php echo (!empty($user_language[$user_selected]['lg_save'])) ? $user_language[$user_selected]['lg_save'] : $default_language['en']['lg_save']; ?></button>
                <button type="button" class="btn btn-danger offer_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="offersdeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_delete_offer'])) ? $user_language[$user_selected]['lg_delete_offer'] : $default_language['en']['lg_delete_offer']; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="acc_msg"><?php echo (!empty($user_language[$user_selected]['lg_sure_to_delete_offer'])) ? $user_language[$user_selected]['lg_sure_to_delete_offer'] : $default_language['en']['lg_sure_to_delete_offer']; ?></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_accept_confirm"><?php echo (!empty($user_language[$user_selected]['lg_YES'])) ? $user_language[$user_selected]['lg_YES'] : $default_language['en']['lg_YES']; ?></a>
				<button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
			</div>
		</div>
	</div>
</div>