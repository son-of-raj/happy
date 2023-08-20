<?php
	$providers = $this->db->get('providers')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Seller Balance</h3>
				</div>
				<!-- <div class="col-auto text-right">
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div> -->
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
                        <th>Provider Name</th>
                        <th>Wallet Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($list)) {
										$i=1;											
										foreach ($list as $rows) { ?>
		                    <tr>
													<td><?php echo $i++ ?></td> 
													<td><?php echo $rows['provider_name']; ?></td>
													<td><?php echo settingValue('currency_symbol').$rows['wallet_amt']; ?></td>
													
												</tr>
                    <?php 
                  		} 
                  	} else { ?>
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