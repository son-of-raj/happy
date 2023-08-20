<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Cash On Delivery</h3>
				</div>
				<div class="col-auto text-end d-none">
					<div class="col-sm-4 text-end m-b-20">
						<a href="<?php echo base_url().$theme . '/' . $model . '/create'; ?>" class="btn btn-white add-button"><i class="fas fa-plus"></i></a>
					</div>
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		
		<ul class="nav nav-tabs menu-tabs">
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/cod'; ?>">Service Bookings</a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url().'admin/cod/product'; ?>">Product Orders</a>
			</li>			
		</ul>
		
		<div class="clearfix"></div>
		
		<?php
			if ($this->session->userdata('message')) {
				echo $this->session->userdata('message');
			}
			?>
		<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-actions-bar m-b-0 categories_table">
							<thead>
								<tr>
									<th>#</th>
									<th>Shop</th>
									<th>Product</th>
									<th>Username</th>
									<th>Provider</th>
									<th>Amount</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if (!empty($lists)) 
								{
									$ds = array(1=>['text'=>'Order Placed', 'color'=>'dark'], 2=>['text'=>'Order Confirmed', 'color'=>'info'], 3=>['text'=>'Shipped', 'color'=>'primary'], 4=>['text'=>'Out of Delivery', 'color'=>'muted'], 5=>['text'=>'Delivered', 'color'=>'success'], 6=>['text'=>'Cancelled', 'color'=>'danger']);
									$sno = 0;
									foreach ($lists as $row) 
									{										
										?>
										<tr>
											<td> <?php echo ++$sno; ?></td>
											<td><?php echo $row['shop_name']?></td>
											<td><?php echo $row['product_name']?> <?php echo $row['qty']?><?php echo $row['unit_name']?></td>
											<td> <?php echo $row['name']?></td>
											<td> <?php echo $row['provider_name']?></td>
											<td><?php echo currency_conversion($row['product_currency']).$row['product_total']?></td>
											<td> <label class="badge badge-<?php echo $ds[$row['delivery_status']]['color']?>"><?php echo $ds[$row['delivery_status']]['text']?></td>
										</tr>
									<?php
									}
								} else {
									?>
									<tr>
										<td colspan="6">
											<p class="text-danger text-center m-b-0">No Records Found</p>
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
</div>