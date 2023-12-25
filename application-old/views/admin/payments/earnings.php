<?php
	$providers = $this->db->get('providers')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Earnings</h3>
				</div>
				<div class="col-auto text-right">
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>service</th>
                                        <th>Provider</th>
																				<th>Payment Type</th>
                                        <th>Amount</th>
                                        <th>Commission Rate</th>
                                        <th>Status</th>
                                        <th>Earned Amount</th>
                                        <th>Date</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  if(!empty($list)) {
																	$i=1;
																	foreach ($list as $rows) {
																		$com = settingValue('commission')/100 ;
																		$com_amnt = $rows['amount']*$com;
																		$ded_amnt = $rows['amount']-$rows['amount']*$com;
							                                        $amount_refund=''; 
																 	if(!empty($rows['reject_paid_token'])){
																 	if($rows['admin_reject_comment']=="This service amount favour for User"){
																 		$status="Amount refund to User";
																 	}else{
							                      $status="Amount refund to Provider";
																 	}
																 }
																	$admin_payment = $this->db->where('booking_id',$rows['id'])->get('admin_payment')->row_array();
																	
																	if($rows['status'] == 1) {
							                                            $status = 'Pending';
																	}
																	elseif($rows['status'] == 2) {
							                                            $status = 'Inprogress';
																	}
																	elseif($rows['status'] == 3) {
							                                            $status = 'Complete Request to User';
																	}
																	elseif($rows['status'] == 5&&empty($rows['reject_paid_token'])) {
							                                            $status = 'Rejected by User';
																	}
																	elseif($rows['status'] == 6) {
							                                            $status = 'Payment Completed';
																	}
																	elseif($rows['status'] == 7&&empty($rows['reject_paid_token'])) {
							                                            $status = 'Cancelled by Provider';
																	}
																	elseif($rows['status'] == 8) {
							                                            $status = 'Completed';
																	} elseif($rows['status'] == 4) {
																		$status = 'User Accepted';
																	} else {
																		$status = '-';
																	}

																	$datef = explode(' ', $rows['updated_on']);
	                                if(settingValue('time_format') == '12 Hours') {
	                                    $time = date('h:ia', strtotime($datef[1]));
	                                } elseif(settingValue('time_format') == '24 Hours') {
	                                   $time = date('H:i:s', strtotime($datef[1]));
	                                } else {
	                                    $time = date('G:ia', strtotime($datef[1]));
	                                }
	                                $date = date(settingValue('date_format'), strtotime($datef[0]));
	                                $timeBase = $date.' '.$time;
																?>
                               <tr>
											<td><?php echo $i++ ?></td> 
											<td><?php
												echo ($rows)?$rows['service_title']:'';?></td>
											<td><?php
												echo $rows['name'];?></td>
											<td><?php 
													if ($rows['cod'] == 1) {
														echo "COD";
													} else {
														echo "Wallet";
													}
												?>		
											</td>
											<td><?php echo currency_conversion($rows['currency_code']).$rows['amount']?></td>
											<td><?php 
												if ($rows['cod'] == 2) {
													echo currency_conversion($rows['currency_code']).$com_amnt;
												} else {
													echo "$0";
												}
												?>		
											</td>
											<td><?php echo $status; ?></td>
											<td><?php 
													if ($rows['cod'] == 1) {
														echo "$0";
													} else {
														echo currency_conversion($rows['currency_code']).$ded_amnt;
													} 
												?>
											</td>
											<td><?php echo $timeBase; ?></td>
											<td>
												<a href="#" id="ear_del" class="btn btn-sm bg-danger-light  delete_menu" data-id="<?php  echo $rows['id']; ?>">
                          <i class="far fa-trash-alt "></i>
                           Delete
                        </a>
                      </td>
										</tr>
                                    <?php } } else {
                                    ?>
									<tr>
										<td colspan="7">
											<div class="text-center text-muted">No Records Found</div>
										</td>
									</tr>
									<?php } ?>
                                </tbody>
                            </table>
						</div> 
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="ear_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confiramtion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are You Confirm To Delete This Earning.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_delete_ear" data-id="" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>