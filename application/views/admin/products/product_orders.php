<?php
$ds = array(1=>['text'=>'Order Placed', 'color'=>'success'], 2=>['text'=>'Order Confirmed', 'color'=>'info'], 3=>['text'=>'Shipped', 'color'=>'primary'], 4=>['text'=>'Out of Delivery', 'color'=>'muted'], 5=>['text'=>'Delivered', 'color'=>'success'], 6=>['text'=>'Cancelled By User', 'color'=>'danger'], 7=>['text'=>'Cancelled By Provider', 'color'=>'danger']);
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Product Orders</h3>
				</div>
				<div class="col-auto text-end">
					<a href="<?php echo $base_url; ?>admin/product-orders" class="btn btn-white add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="#" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Shop</label>
								<input type="text" class="form-control nv" id="shop_name">
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Product</label>
								<input type="text" class="form-control nv" id="product_name">
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">User</label>
								<input type="text" class="form-control nv" id="user_name">
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">Provider</label>
								<input type="text" class="form-control nv" id="provider_name">
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker nv" type="text" id="from_date" value="">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker nv" type="text" id="to_date" value="">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<button class="btn btn-primary" name="form_submit" value="submit" type="button" onclick="filter_order('s');">Search</button>
								<button class="btn btn-danger" type="button" onclick="filter_order('c');">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->

		<ul class="nav nav-tabs menu-tabs">
			<li class="nav-item ni active" id="all_ni">
				<a class="nav-link product_orders" data-id="all" href="#">All <span class="badge badge-primary"><span id="total"><?php echo $status_count['total']?></span></span></a>
			</li>
			<li class="nav-item ni" id="1_ni">
				<a class="nav-link product_orders" data-id="1" href="#" >Placed <span class="badge badge-primary"><span id="placed"><?php echo $status_count['placed']?></span></a>
			</li>
			<li class="nav-item ni" id="2_ni">
				<a class="nav-link product_orders" data-id="2" href="#" >Confirmed <span class="badge badge-primary"><span id="confirmed"><?php echo $status_count['confirmed']?></span></span></a>
			</li>
			<li class="nav-item ni" id="3_ni">
				<a class="nav-link product_orders" data-id="3" href="#" >Shipped <span class="badge badge-primary"><span id="shipped"><?php echo $status_count['shipped']?></span></span></a>
			</li>
			<li class="nav-item ni" id="4_ni">
				<a class="nav-link product_orders" data-id="4" href="#" >Out For Delivery <span class="badge badge-primary"><span id="out_delivery"><?php echo $status_count['out_delivery']?></span></span></a>
			</li>
			<li class="nav-item ni" id="5_ni">
				<a class="nav-link product_orders" data-id="5" href="#" >Delivered <span class="badge badge-primary"><span id="delivered"><?php echo $status_count['delivered']?></span></span></a>
			</li>
			<li class="nav-item ni" id="6_ni">
				<a class="nav-link product_orders" data-id="6" href="#" >Cancelled <span class="badge badge-primary"><span id="cancelled"><?php echo $status_count['cancelled']?></span></span></a>
			</li>
		</ul>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div id="mplist">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 order_table" id="order-list-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order Code</th>
                                        <th>User</th>
                                        <th>Provider</th>
                                        <th>Shop</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($list)) 
                                    {
                                    	$i=1;
	                                    foreach ($list as $rows) 
	                                    {
											$user_image = $rows['profile_img'];
											if(empty($user_image)){
												$user_image ='assets/img/user.jpg';
											}
											$provider_image = $rows['provider_profile_img'];
											if(empty($provider_image)){
												$provider_image ='assets/img/user.jpg';
											}
										/* time */
										$full_date=date(settingValue('date_format'), strtotime($rows['created_at']));
											
											?>
									<tr>
										<td><?php echo $i?></td>
										<td><?php echo $full_date?></td>
										<td><?php echo $rows['order_code']?></td>
										<td>
											<h2 class="table-avatar">
												<a href="#" class="avatar avatar-sm me-2">
													<img class="avatar-img rounded-circle" alt="" src="<?php echo base_url().$user_image?>">
												</a>
												<a href="javascript:void(0);"><?php echo $rows['name']?></a>
											</h2>
										</td>
										<td>
											<h2 class="table-avatar">
												<a href="#" class="avatar avatar-sm me-2">
													<img class="avatar-img rounded-circle" alt="" src="<?php echo base_url().$provider_image?>">
												</a>
												<a href="javascript:void(0);"><?php echo $rows['provider_name']?></a>
											</h2>
										</td>
										<td><?php echo $rows['shop_name']?></td>

										<td><?php echo wordwrap($rows['product_name'], 60, '<br />', true); ?> <?php echo $rows['qty']?><?php echo $rows['unit_name']?></td>
										<td><?php echo currency_conversion($rows['product_currency']).$rows['product_total']?></td>
										<td>
											<label class="badge badge-<?php echo $ds[$rows['delivery_status']]['color']?>"><?php echo $ds[$rows['delivery_status']]['text']?></label>
										</td>
									</tr>
									<?php 
										$i++;
										} 
									} 
									
									?>
                                </tbody>
                            </table>
						</div>
						<?php
		                echo $this->ajax_pagination_new->create_links();
		                ?>
						</div>
					</div> 
				</div> 
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="selected_status" value="all">
<style type="text/css">
.pagination {
    float: left;
    width: 100%;
    text-align: center;
    margin-top: 40px;
}
.pagination > ul {
    float: left;
    width: 100%;
    text-align: center;
    margin: 0;
}
.pagination > ul li {
    float: none;
    display: inline-block;
    margin: 0 1px;
}
.pagination > ul li a {
    float: left;
    width: 35px;
    height: 35px;
    background: #fff;
    border: 1px solid #e8ecec;
    line-height: 34px;
    font-size: 13px;
    color: #8d8d8d;
}
.pagination > ul li span {
    float: left;
    width: 35px;
    height: 35px;
    background: #fff;
    border: 1px solid #e8ecec;
    line-height: 34px;
    font-size: 13px;
    color: #8d8d8d;
}
.pagination > ul li.arrow a{
    background: #f1f1f1;
    font-size: 17px;
    margin: 0;
}

.pagination > ul li.active a {
    background: #393CC6;
    border-color: #393CC6;
    color: #fff;
}
</style>
<script type="text/javascript">
/*function getlist(status)
{
	$(".ni").removeClass('active');
	$("#"+status+"_ni").addClass('active');
	$("#selected_status").val(status);
	orders_list(0);
}*/



</script>