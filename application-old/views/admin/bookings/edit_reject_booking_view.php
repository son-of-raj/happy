<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
					
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<?php if($list['payment_status']==5){?>
							<h3 class="page-title">Reject Payment</h3>
							<?php }else{ ?>
								<h3 class="page-title">Cancel Payment</h3>
							<?php }?>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form method="post" method="post" action="<?php echo base_url('pay-reject')?>" id="reject_payment_submit" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

							<input type="hidden" name="booking_id" value="<?php echo $list['id'];?>">
                            <div class="form-group">
                                <label>Service Title</label>
                                <input class="form-control" type="text" name="service_name" id="service_name" value="<?php echo !empty($list['service_title'])?$list['service_title']:'';?>" readonly>
                            </div>
							<div class="form-group">
                                <label>Service Amount</label>
                                <input class="form-control" type="text" name="service_amount" id="service_amount" value="<?php echo !empty($list['amount'])?$list['amount']:'';?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Rejection Comments.</label>
                                <textarea class="form-control" readonly=""><?php echo !empty($list['reason'])?$list['reason']:'not mentioned...';?></textarea>
                            </div>
							 <div class="form-group">
                                <label>Cancellation Charges</label>
								<?php if($list['payment_status']==5){ ?>
									<textarea class="form-control" readonly="" id="refundval"><?php echo "0.5 SR";?></textarea>
								<?php } else { ?>
									<textarea class="form-control" readonly=""><?php echo "0.0 SR";?></textarea>
								<?php } ?>
                            </div>
				
							
							
                            <div class="form-group">
                                <label>Refund Service Amount Favour for</label>
								<div>									
									<label class="radio-inline"><input class="pay_for"  type="radio" name="pay_for" value="2" checked="checked" > User </label>
								</div>
                            </div>
                            <input type="hidden" name="token" id="token" value="<?php echo $list['provider_token'];?>">
						
							<div class="form-group">
                                <label>Favour comments</label>
                                <textarea name="favour_comment" id="fav_com" class="form-control" readonly="">This service amount favour for User</textarea>
                            </div>
							
							<div class="form-group">
                                <label id="reasonfor">Comments</label>
                                <textarea name="pay_comment" id="pay_com" class="form-control" required></textarea>
                            </div>
							
                            <div class="mt-4">
                            	<?php if($user_role==1){?>
                                <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
                                <?php }?>
								<?php if($list['payment_status']==5){?>
								<a href="<?php echo $base_url; ?>admin/reject-report"  class="btn btn-danger">Cancel</a>
								<?php } else { ?>
								<a href="<?php echo $base_url; ?>admin/cancel-report"  class="btn btn-danger">Cancel</a>
								<?php } ?>
                            </div>
                            <input type="hidden" name="utoken" id="user_token" value="<?php echo $list['user_token'];?>">
                            <input type="hidden" name="ptoken" id="provider_token" value="<?php echo $list['provider_token'];?>">
                        </form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
