<?php
$user_details = $this->db->where('status',1)->get('providers')->result_array();

$datearr = $this->deposit->x_week_range('25-08-2021');

$currentWeekNumber = date('W');
$currentYear = date('Y');

$result = $this->deposit->Start_End_Date_of_a_week($currentWeekNumber,$currentYear);

?>
<div class="page-wrapper">
	<div class="content container-fluid">
		
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Deposit</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<ul class="nav nav-tabs menu-tabs d-none">
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url().'deposit-provider-list'; ?>">Deposit - Provider List</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="javascript:void(0)">Reports</a>
			</li>			
		</ul>
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin/deposit/deposit_list" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
					
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider Name</label>
								<select class="form-control select" name="username">
									<option value="">Select Vendor name</option>
									<?php foreach ($user_details as $user) { ?>
									<option value="<?php echo $user['name']?>"><?php echo $user['name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Email</label>
								<select class="form-control select" name="email">
									<option value="">Select email</option>
									<?php foreach ($user_details as $user) { ?>
									<option value="<?php echo $user['email']?>"><?php echo $user['email']?></option>
									<?php } ?>
								</select>
							</div>
						</div>						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="from">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="to">
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
                        <div class="table-responsive deposit-lists">
                            <table class="table custom-table mb-0 w-100 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Provider Name</th>                                        
                                        <th class="text-center">Total Service Bookings</th>
                                        <th class="text-center">Total Product Orders</th>
										<th class="text-center">Total Amount</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
										
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									if(!empty($lists)) {
									$i=1;
									foreach ($lists as $rows) {										
										
										$profile_img ='assets/img/user.jpg';
										if(!empty($rows->profile_img) && file_exists($rows->profile_img)){
											$profile_img = $rows->profile_img;
										}
										
										/* Bookings */
										
										$totbookamnt = 0;$totbook = 0; $a=0; $totser = 0; 
										
										$arr = array("provider_id" => $rows->id, "parent_bookid" => 0, "guest_parent_bookid" => 0, "cod" => 2, "deposit_flag" => 0);
										
										$services = $this->db->select('count(DISTINCT(service_id)) as total_services')->where($arr)->where_in('status',[5,6,7])->get('book_service')->row_array();
																				
										$bookings = $this->db->select('*')->where($arr)->where_in('status',[5,6,7])->get('book_service')->result_array();
										
										
										$book_ids = '';
										
										foreach($bookings as $bk) {											
										
											if($bk['status'] == 5 || $bk['status'] == 7) {
												if(!empty($bk['reject_paid_token'])){
													if($bk['admin_reject_comment']=="This service amount favour for User"){
														$badge="Amount refund to User";
														if($bk['status'] == 5) {
															$totbookamnt+=0.5;
														}
														$book_ids .= $bk['id'].",";
														$totser++;
														$totbook++;														
													}else{									 		
														$badge="Amount refund to Provider";
														$totbookamnt+=$bk['final_amount'];
														$book_ids .= $bk['id'].",";
														$totser++;
														$totbook++;
													}
												} 
											}
											if($bk['status'] == 6) {
												$totbookamnt+=$bk['final_amount'];
												$book_ids .= $bk['id'].",";
												$totser++;
												$totbook++;
											}
											$a+=$bk['final_amount'];
										}
										$book_ids = rtrim($book_ids, ",");
									
										
										$deposit_id = 0; $deposit_status = 0;
										$deposit = $this->db->select('*')->where("provider_id", $rows->id)->order_by('id','DESC')->get('deposit_details')->row_array();									
										if(!empty($deposit)){
											$deposit_id = $deposit['id'];
											$deposit_status = $deposit['deposit_status'];
										}
										if($deposit_status == 1) {$insel = 'selected';} else $insel = '';
										if($deposit_status == 2) {$cosel = 'selected'; $deposit_status= '';} else $cosel = '';
										
										$stat_disable = ''; $statcls = ''; $final_status = "";
										if($deposit_status == ''){
											$stat_disable = 'disabled';
											$statcls = 'd-none';
											$final_status = "Completed";
										}
										/* Bookings End */
										
										/* Orders */
										
										$totorder = 0; $cart_ids=''; $totorderamt = 0;
										$order_where = array('product_cart.status'=>1, 'shops.provider_id'=>$rows->id, 'product_order.payment_type' => 'card', 'product_cart.cart_flag'=>0);
										$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno');
										$this->db->join('product_order','product_cart.order_id=product_order.id','left');
										$this->db->join('products','product_cart.product_id=products.id','left');
										$this->db->join('shops','product_cart.shop_id=shops.id','left');
										$this->db->join('product_units','products.unit=product_units.id','left');
										$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
										$this->db->join('users','product_cart.user_id=users.id','left');
										$this->db->where($order_where);
										$this->db->where_in('product_cart.delivery_status',[5,6]);						
										$order_query =  $this->db->get('product_cart')->result_array();
										foreach($order_query as $ord) {	
											$cart_ids .= $ord['id'].",";
											$totorder++;
											$totorderamt += $ord['product_total'];
										}
										$cart_ids = rtrim($cart_ids, ",");
										$totamnt = 0;
										$totamnt = $totbookamnt + $totorderamt;
										
										/* Orders End */
										
										
										echo'<tr>
											<td>'.$i++.'</td>
											<td><h2 class="table-avatar"><a href="javascript:void(0)" class="avatar avatar-sm me-2"><img class="avatar-img rounded-circle" src="'.base_url().$profile_img.'"> '.str_replace('-', ' ', $rows->name).'</a></h2></td>
											<td align="center">'.$totbook.'</td>
											<td align="center">'.$totorder.'</td>
											<td align="center">'.settingValue('currency_symbol').''.$totamnt.'</td>';											
											echo '
											<td align="center">
											<select class="form-control change_depstatus '.$statcls.'" name="depstatus"  data-deposit_id="'.$deposit_id.'" data-providerid="'.$rows->id.'" '.$stat_disable.' >
												<option value="1" '.$insel.'>Inprogress</option>
												<option value="2" '.$cosel.'>Completed</option>';
												
											echo '</select>
											'.$final_status.'
											</td>';
											echo '<td align="center">';
												if($totamnt > 0 && $deposit_status == '') {
												echo '<a href="javascript:void(0)" class="btn btn-sm bg-success-light me-3 deposit_show" data-id="'.$rows->id.'" data-book_ids="'.$book_ids.'" data-cart_ids="'.$cart_ids.'" data-amount="'.$totamnt.'"><i class="far fa-edit me-1"></i> Deposit</a>';
												} else {
													echo "-";
												}
											echo '</td>
											
											
										</tr>';
									}
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


<div class="modal" id="deposit_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Deposit Process</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form id="deposit_form" name="deposit_form">
			<div id="deposit_content">
				
			</div>
			<input type="hidden" id="pid" name="pid" value="" />
			<input type="hidden" id="bid" name="bid" value="" />
			<input type="hidden" id="cid" name="cid" value="" />
			<input type="hidden" id="amt" name="amt" value="" />
	   </form>
      </div>
      <div class="modal-footer">
      	<?php if($this->session->userdata('role') == 1) { ?>
        	<button type="button" id="confirm_btn_deposit" data-id="" class="btn btn-primary">Confirm</button>
    	<?php } ?>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
