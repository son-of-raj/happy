<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
					
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Order Refund</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form method="post" method="post" action="<?php echo base_url()?>admin/products/update_refund" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input type="hidden" name="cart_id" value="<?php echo $product['id']?>">
                            <div class="form-group">
                                <label>Product</label>
                               <b><?php echo $product['product_name']?></b>
                            </div>
							<div class="form-group">
                                <label>Total Amount</label>
                                <b><?php echo $product['product_total']?></b>
                            </div>
                            <div class="form-group">
                                <label>Cancelled By</label>
                                <b><?php echo ($product['delivery_status']==6 ? 'User':'Provider')?></b>
                            </div>
                            <div class="form-group">
                                <label>Rejection Comments.</label>
                                <b><?php echo $product['cancel_reason']?></b>
                            </div>
                            <div class="form-group">
                                <label>Refund Amount Favour for</label>
								<div>									
									<label class="radio-inline"><input class="pay_for"  type="radio" name="pay_for" value="2" checked="checked" > User </label>
									<label class="radio-inline"><input class="pay_for"  type="radio" name="pay_for" value="1"> Provider </label>
								</div>
                            </div>
							<div class="form-group">
                                <label id="reasonfor">Comments</label>
                                <textarea name="pay_comment" id="pay_com" class="form-control" required></textarea>
                            </div>
							
                            <div class="mt-4">
                            	<?php if($this->session->userdata('role') == 1) { ?>
                                	<button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
                                <?php } ?>
								<a href="<?php echo $base_url; ?>admin/product-orders"  class="btn btn-danger">Cancel</a>
								
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
