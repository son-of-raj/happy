<?php
    $page = $this->uri->segment(1);
    $active =$this->uri->segment(2);
	$arg = $this->uri->segment(3);
	$access_result_data_array = $this->session->userdata('access_module');	
	$admin_id=$this->session->userdata('admin_id');

 ?>
 <div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>
				<li class="<?php echo ($page == 'dashboard')?'active':'';?>">
					<a href="<?php echo $base_url; ?>dashboard"><i class="fas fa-columns"></i> <span>Dashboard</span></a>
				</li>
				<?php if(in_array(2,$access_result_data_array) || in_array(3,$access_result_data_array) || in_array(4,$access_result_data_array) || in_array(31,$access_result_data_array) || in_array(33,$access_result_data_array) || in_array(34,$access_result_data_array) || in_array(35,$access_result_data_array) || in_array(55,$access_result_data_array) || in_array(56,$access_result_data_array) || in_array(57,$access_result_data_array)) { ?>
				<li class="submenu <?php echo ($page == 'service settings' ||$page == 'categories' || $page == 'add-category' || $page == 'edit-category' || $page == 'subcategories' || $page == 'add-subcategory' || $page == 'edit-subcategory' || $page == 'service-list' || $page == 'service-details' || $page == 'sub_subcategories' || $page == 'additional-services' || $page == 'add-additional-services' || $page == 'edit-additional-services' || $page == 'edit-service' || $page == 'service-offers' || $page == 'offers-details' || $page == 'service-coupons' || $page == 'coupons-details' || $page == 'inactive-service-list' || $active == 'pending-service-list' || $page == 'deleted-service-list') ? 'active':'';?>">
					<a href="#">
						
						 <i class="fas fa-bullhorn"></i> <span>Shop & Services </span>
						 <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
					<ul>
						<?php
						if(in_array(2,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'categories' || $page == 'add-category' || $page == 'edit-category') ? 'active':'';?>" href="<?php echo $base_url; ?>categories"> <span>Categories</span></a>
						</li>
						<?php
						} if(in_array(3,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'subcategories' || $page == 'add-subcategory' || $page == 'edit-subcategory') ? 'active':'';?>" href="<?php echo $base_url; ?>subcategories"> <span>Sub Categories</span></a>
						</li>
						<?php
						}  if(in_array(4,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'service-list' || $page == 'service-details' || $page == 'edit-service') ? 'active':'';?>" href="<?php echo $base_url; ?>service-list">
								<span> Services</span>
							</a>
						</li>
						<?php
						} if(in_array(33,$access_result_data_array)) {
 						} if(in_array(34,$access_result_data_array)) { ?>
						<li>
							<a class="<?php echo ($page == 'service-offers' || $page == 'offers-details') ? 'active':'';?>" href="<?php echo $base_url; ?>service-offers">
								<span> Offers</span>
							</a>
						</li>
						<?php } if(in_array(35,$access_result_data_array)) { ?>
						<li>
							<a class="<?php echo ($page == 'service-coupons' || $page == 'coupons-details') ? 'active':'';?>" href="<?php echo $base_url; ?>service-coupons">
								<span> Coupons</span>
							</a>
						</li>
						<?php
						} if(in_array(27,$access_result_data_array) || in_array(28,$access_result_data_array)) { ?>
					    <?php
						if(in_array(27,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'staff-lists')?'active':''; echo ($page == 'staff-edit')?'active':''; echo ($page == 'staff-details')?'active':'';?>" href="<?php echo $base_url; ?>staff-lists"><span>Staffs</span></a>
						</li>
						<?php
						} if(in_array(28,$access_result_data_array)) { ?>
							<li>
								<a class="<?php echo ($page == 'shop-lists')?'active':''; echo ($page == 'shop-edit')?'active':'';  echo ($page == 'shop_details')?'active':''; ?>" href="<?php echo $base_url; ?>shop-lists"> <span>Shops</span></a>
							</li>	
						<?php } if(in_array(55,$access_result_data_array)) { ?>
							<li class="d-none">
								<a class="<?php echo ($active == 'pending-service-list')?'active':''; ?>" href="<?php echo $base_url; ?>admin/pending-service-list"> <span>Pending Services</span></a>
							</li>
						<?php } if(in_array(56,$access_result_data_array)) { ?>
							<li>
								<a class="<?php echo ($page == 'inactive-service-list')?'active':'';?>" href="<?php echo $base_url; ?>inactive-service-list"> <span>Inactive Services</span></a>
							</li>
						<?php } if(in_array(57,$access_result_data_array)) { ?>
							<li>
								<a class="<?php echo ($page == 'deleted-service-list')?'active':'';?>" href="<?php echo $base_url; ?>deleted-service-list"> <span>Deleted Services</span></a>
							</li>
						<li>
							<a href="<?php echo $base_url; ?>admin/add-service" class="<?php echo ($active == 'add-service') ? 'active':'';?>"> <span><?php echo(!empty($sidebar['lg_admin_add_services']))?($sidebar['lg_admin_add_services']) : 'Add Services';  ?></span></a>
						</li>
						<?php } 
					} 
						?>
					</ul>
				</li>
				<?php } ?>
				<?php if(in_array(1,$access_result_data_array) || in_array(13,$access_result_data_array) || in_array(12,$access_result_data_array) || in_array(30,$access_result_data_array)) { ?>
				<li class="submenu <?php echo ($page == 'adminuser-details')?'active':''; echo ($page == 'adminusers')?'active':''; echo ($page == 'edit_adminuser')?'active':''; echo ($page == 'adminuser_details')?'active':''; echo ($page == 'users')?'active':''; echo ($page == 'service-providers')?'active':''; echo ($page == 'freelances-providers')?'active':''; echo ($page == 'user-details')?'active':''; echo ($page == 'provider-details')?'active':''; echo ($page == 'edit-user')?'active':''; echo ($page == 'edit-provider')?'active':'';echo ($page == 'add-provider')?'active':'';?>">
					<a href="#">
						
						 <i class="fas fa-users"></i> <span>Users </span>
						 <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
					<ul>
					    <?php
						if(in_array(1,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'adminuser-details')?'active':''; echo ($page == 'adminusers')?'active':''; echo ($page == 'edit_adminuser')?'active':''; echo ($page == 'adminuser_details')?'active':'';?>" href="<?php echo $base_url; ?>adminusers"><span>Admin</span></a>
						</li>
						<?php
						} if(in_array(13,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'users')?'active':'';echo ($page == 'user-details')?'active':''; echo ($page == 'edit-user')?'active':'';?>" href="<?php echo $base_url; ?>users"> <span>Users</span></a>
						</li>
						<?php
						} if(in_array(12,$access_result_data_array)) {
						?>
						<li>
							<a class="<?php echo ($page == 'service-providers')?'active':''; echo ($page == 'add-provider')?'active':''; echo ($page == 'provider-details')?'active':''; echo ($page == 'edit-provider' && $arg == 1)?'active':'';?>" href="<?php echo $base_url; ?>service-providers"> <span> Vendors</span></a>
						</li>
						<?php
						}
						?>

						<li>
							<a class="<?php echo ($active == 'roles' || $active == 'edit-roles-permissions' || $active == 'add-roles-permissions')?'active':'';?>" href="<?php echo $base_url; ?>admin/roles"><span><?php echo(!empty($sidebar['lg_roles_permissions']))?($sidebar['lg_roles_permissions']) : 'Roles & Permissions';  ?></span></a>
						</li> 
					</ul>
				</li>
				
				<?php }  if(in_array(5,$access_result_data_array)) { ?>
				<li class="<?php echo ($active =='total-report' || $active =='pending-report' || $active == 'inprogress-report' || $active == 'complete-report' || $active == 'reject-report' || $active == 'cancel-report' ||$page == 'reject-payment')? 'active':''; ?>">
					<a href="<?php echo $base_url; ?>admin/total-report"><i class="far fa-calendar-check"></i> <span> Booking List</span></a>
				</li>
				<?php }?>

				<?php if(in_array(58,$access_result_data_array) || in_array(59,$access_result_data_array) || in_array(60,$access_result_data_array)) { ?>
				<li class="submenu <?php echo ($active =='add-payouts' || $active =='payout-requests' || $active =='completed-payouts')?'active':''; ?> d-none">
						<a href="#"><i class="fas fa-hashtag"></i> <span> Payout</span> <span class="sub-arrow"><i class="fas fa-angle-right"></i></span></a>
						<ul>
							<?php if(in_array(58,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($active == 'add-payouts')?'active':'';?>"  href="<?php echo $base_url; ?>admin/add-payouts" > <span> Add Payout</span></a>
								</li>
							<?php } if(in_array(59,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($active == 'payout-requests')?'active':'';?>"  href="<?php echo $base_url; ?>admin/payout-requests" > <span> Payout Requests</span></a>
								</li>
							<?php } if(in_array(60,$access_result_data_array)) {?>
								<li>
									<a class="<?php echo ($active == 'completed-payouts')?'active':'';?>"  href="<?php echo $base_url; ?>admin/completed-payouts" > <span> Completed Payout</span></a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
				<?php if(in_array(41,$access_result_data_array) || in_array(42,$access_result_data_array) || in_array(43,$access_result_data_array) || in_array(44,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($page == 'product-categories')?'active':''; echo ($page == 'product-subcategories')?'active':''; echo ($page == 'product_units')?'active':''; echo ($page == 'admin-product-list')?'active':'';?>">
						<a href="#">	
							<i class="fas fa-shopping-basket"></i>				
							<span>Products </span>
							<span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
						<ul>
							<?php if(in_array(41,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($page == 'product-categories')?'active':'';?>" href="<?php echo $base_url; ?>product-categories"> <span>Product Catgories</span></a>
								</li>
							<?php } if(in_array(42,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($page == 'product-subcategories')?'active':'';?>" href="<?php echo $base_url; ?>product-subcategories"> <span>Product Sub Catgories</span></a>
								</li>
							<?php } if(in_array(43,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($page == 'product_units')?'active':'';?>" href="<?php echo $base_url; ?>product_units"> <span>Product Units</span></a>
								</li>
							<?php } if(in_array(44,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($page == 'admin-product-list')?'active':'';?>" href="<?php echo $base_url; ?>admin-product-list"> <span>Products</span></a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
				<?php if(in_array(46,$access_result_data_array)) { ?>
					<li class="<?php echo ($active == 'product_orders')?'active':'';?>">
						<a href="<?php echo $base_url; ?>admin/product-orders"><i class="far fa-calendar-check"></i> <span> Product Orders</span></a>
					</li>
				<?php } ?>
				<?php if(in_array(9,$access_result_data_array) || in_array(6,$access_result_data_array) || in_array(10,$access_result_data_array) || in_array(11,$access_result_data_array)|| in_array(18,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($page == 'deposit-provider-list' ||$page == 'payment_list' || $page == 'payment_details' || $page == 'admin-payment' || $page == 'withdraw_list' || $page == 'view_withdraw' || $page == 'subscriptions' || $page == 'add-subscription' || $page == 'edit-subscription' || $active =='wallet' || $active =='wallet-history')? 'active':''; echo ($page == 'Revenue')?'active':''; echo ($active == 'cod')?'active':''; echo ($page == 'freelancer-subscriptions')?'active':''; echo ($page == 'subscriptions-lists')?'active':'';?>">
					<a href="#">
						
						 <i class="far fa-money-bill-alt"></i> <span>Accounting </span>
						 <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
					<ul>
						<?php if(in_array(9,$access_result_data_array)) { ?>				
				<li>
					<a href="<?php echo $base_url; ?>subscriptions" class="<?php echo ($page == 'subscriptions' || $page == 'freelancer-subscriptions' || $page == 'subscriptions-lists')?'active':''; echo ($page == 'add-subscription')?'active':''; echo ($page == 'edit-subscription')?'active':'';?>"> <span>Subscriptions</span></a>
				</li>
				<?php } if(in_array(6,$access_result_data_array)) {?>
					<li>
						<a href="<?php echo $base_url; ?>payment_list" class="<?php echo ($page == 'payment_list')?'active':''; echo ($page == 'admin-payment')?'active':'';?>"><span>Payments</span></a>
					</li>
				<?php
				} if(in_array(10,$access_result_data_array)) { 
				?>
					
				<?php
				} if(in_array(11,$access_result_data_array)) {
				?>
					<li>
						<a class="<?php echo ($page == 'Revenue') ? 'active':'';?>"  href="<?php echo $base_url; ?>Revenue"> <span>Revenue</span></a>
					</li>
				<?php
				} if(in_array(40,$access_result_data_array)) {
				?>
					<li>
						<a class="<?php echo ($page == 'deposit-provider-list') ? 'active':'';?>"  href="<?php echo $base_url; ?>deposit-provider-list"> <span>Deposit</span></a>
					</li> 
				<?php
				}
                ?>				
					</ul>
				</li>
				<?php } ?>
				<?php if(in_array(46,$access_result_data_array) || in_array(47,$access_result_data_array) || in_array(48,$access_result_data_array) || in_array(49,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($page == 'blogs' || $page == 'add-blog' || $page == 'blog-categories' || $active == 'blog-comments')?"active":''; ?> ">
						<a href="#">
						 <i class="far fa-money-bill-alt"></i> <span>Blogs </span>
						 <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
						<ul>
							<?php if(in_array(46,$access_result_data_array)) { ?>
								<li><a class="<?php echo ($page == 'blogs')? 'active':''; ?>" href="<?php echo $base_url; ?>blogs">All Blogs</a></li>
							<?php } if(in_array(47,$access_result_data_array)) { ?>
								<li><a class="<?php echo ($page == 'add-blog' || $page == 'edit-blog')? 'active':''; ?>" href="<?php echo $base_url; ?>add-blog">Add Blogs</a></li>
							<?php } if(in_array(48,$access_result_data_array)) { ?>
								<li><a class="<?php echo ($page == 'blog-categories' || $page == 'edit-blog-category')? 'active':''; ?>" href="<?php echo $base_url; ?>blog-categories">Categories</a></li>
							<?php } if(in_array(49,$access_result_data_array)) { ?>
								<li>
								<a class="<?php echo ($active == 'blog-comments')? 'active':''; ?>" href="<?php echo $base_url; ?>admin/blog-comments"><span> <?php echo(!empty($sidebar['lg_blog_comments']))?($sidebar['lg_blog_comments']) : 'Blog Comments';  ?></span></a>
								</li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
				<?php if(in_array(43,$access_result_data_array) || in_array(44,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($active == 'earnings' || $active == 'seller-balance') ? 'active' : ''; ?> d-none">
						 <a href="#">
						 <i class="fas fa-wallet"></i> <span>Earnings </span>
						 <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
						</a>
							<ul>
								<?php if(in_array(43,$access_result_data_array)) { ?>
									<li>
										<a class="<?php echo ($page == 'earnings')?'active':''; ?>" href="<?php echo $base_url; ?>earnings"><span> Earnings</span></a>
									</li>
								<?php } if(in_array(44,$access_result_data_array)) { ?>
									<li>
										<a class="<?php echo ($active == 'seller-balance')?'active':''; ?>" href="<?php echo $base_url; ?>admin/seller-balance"><span> Seller Balance</span></a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
				<?php if(in_array(14,$access_result_data_array) || in_array(15,$access_result_data_array) || in_array(16,$access_result_data_array) || in_array(32,$access_result_data_array) || in_array(38,$access_result_data_array) || in_array(39,$access_result_data_array)) 
				{
					$setting_arr = []; 
					?>
					<li class="submenu <?php if(in_array($active,$setting_arr)) { echo "actives"; } else { echo ""; } ?>">
					<ul>
						<?php if(in_array(14,$access_result_data_array)) { ?>
							<li >
								<a href="<?php echo $base_url; ?>admin/settings" class="<?php echo ($active == 'settings' ||   $active =='stripe_payment_gateway' || $active =='sms-settings' || $active == 'theme-color' || $active =='moyaser-payment-gateway' || $active=='cod_payment_gateway')? 'active':''; ?>"> <span> Settings</span></a>
							</li> 
						
						<?php } if(in_array(15,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>language" class="<?php echo ($page == 'language' || $page == 'wep-language' || $page == 'app-page-list' || $page == 'add-app-keyword' || $page == 'add-language')?'active':'';?>"> <span>Language</span></a>
						</li>
						<?php } if(in_array(16,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/country_code_config" class="<?php echo ($active == 'country_code_config')?'active':'';?>"> <span>Country Code</span></a>
						</li>	
						<?php } if(in_array(38,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/state_code_config" class="<?php echo ($active == 'state_code_config')?'active':'';?>"> <span>State Code</span></a>
						</li> 
						<?php } if(in_array(39,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/city_code_config" class="<?php echo ($active == 'city_code_config')?'active':'';?>"> <span>City Code</span></a>
						</li>
						<?php } if(in_array(32,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/homeservice-settings" class="<?php echo ($active == 'homeservice-settings')?'active':'';?>"> <span>Service Charges</span></a>
						</li>
						<?php } ?>					
						<li>
							<a class="<?php echo ($page == 'cod')? 'active':''; ?>" href="<?php echo $base_url; ?>admin/cod"> <span> COD</span></a>
						</li>
					</ul>
				</li>
				<?php }?>
				<?php if(in_array(50,$access_result_data_array)) { ?>
					<!-- <li class="<?php echo ($page == 'reward-system' || $page == 'reward-system-details')?'active':'';?>">
						<a href="<?php echo $base_url; ?>reward-system"> <i class="fas fa-coins"></i> <span>Rewards</span></a>
					</li> -->
				<?php } ?>
				<?php if(in_array(36,$access_result_data_array)) { ?>
				<li class="<?php echo ($active == 'chat')?'active':'';?>">
					<a href="<?php echo $base_url; ?>admin/chat"> <i class="far fa-comments"></i> <span>Chat</span></a>
				</li>
				<?php } ?>
				<?php if(in_array(51,$access_result_data_array)) { ?>
					<li class="<?php echo ($active == 'abuse-reports' || $page == 'abuse-details')? 'active':''; ?>">
						<a href="<?php echo $base_url; ?>admin/abuse-reports"><i class="fas fa-file"></i> <span>Abuse Report</span></a>
					</li>
				<?php } ?>
					<li class="<?php echo ($page == 'admin-profile')? 'active':''; ?>">
						<a href="<?php echo $base_url; ?>admin-profile"><i class="fas fa-user-cog"></i> <span>Manage Profile</span></a>
					</li>
				<?php if(in_array(17,$access_result_data_array) || in_array(7,$access_result_data_array) || in_array(8,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($active == 'contact' || $page == 'contact-details' || $page == 'ratingstype' || $page == 'add-ratingstype' || $page == 'edit-ratingstype' || $page == 'review-reports' || $page == 'add-review-reports' || $page == 'edit-review-reports' || $page == 'view_review') ? 'active':'';?>">
					<a href="#">
						<i class="far fa-address-book"></i> <span>Contact </span>
						<span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span>
					</a>
					<ul>						
				<?php if(in_array(17,$access_result_data_array)) { ?>
				<li >
					<a href="<?php echo $base_url; ?>admin/contact" class="<?php echo ($active == 'contact' ||				
					 $page == 'contact-details')?'active':''; ?>"><span>Contact Details</span></a>
				</li>
				<?php } ?>
				<?php if(in_array(7,$access_result_data_array)) { ?>
				<li>
					 <a href="<?php echo $base_url; ?>ratingstype" class="<?php echo ($page == 'ratingstype')?'active':''; echo ($page == 'add-ratingstype')?'active':''; echo ($page == 'edit-ratingstype')?'active':'';?>"><span>Rating Type</span></a>
				</li> 
				<?php }if(in_array(8,$access_result_data_array)) { ?>
				<li>
					 <a href="<?php echo $base_url; ?>review-reports" class="<?php echo ($page == 'review-reports')?'active':''; echo ($page == 'add-review-reports')?'active':''; echo ($page == 'edit-review-reports')?'active':'';?>"><span>Ratings</span></a>
				</li>
				<?php } ?>	
					</ul>
				</li>
				<?php } if(in_array(20,$access_result_data_array)) { ?>
				<li class="<?php echo ($active == 'emailtemplate' || $active =='edit-emailtemplate')? 'active':''; ?>">
					<a href="<?php echo $base_url; ?>admin/emailtemplate"><i class="fas fa-envelope"></i> <span> Email Templates</span></a>
				</li>
				<?php } ?>
				<?php if(in_array(14,$access_result_data_array) || in_array(15,$access_result_data_array) || in_array(16,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ($active == 'stripe_payment_gateway' ||$active == 'paypal_payment_gateway' ||$active == 'cod_payment_gateway' ||$active == 'moyaser-payment-gateway' ||$active == 'city_code_config' ||$active == 'state_code_config' ||$active == 'homeservice-settings' || $active == 'chat-settings' || $active == 'other-settings' || $active == 'seo-settings' || $active == 'social-settings' || $active == 'system-settings' || $active == 'localization' ||$active == 'emailsettings' || $active == 'settings'  || $active =='sms-settings' || $active =='theme-color' || $active == 'cancellation-amount-settings' || $page == 'language' || $page == 'add-language' || $page == 'wep-language' || $page == 'add-app-keyword' || $page == 'app-page-list' || $active == 'country_code_config' || $page == 'district' || $page == 'taluk' || $page == 'area' || $page == 'cod') ? 'active':''; ?>">
					<a href="#">
						<i class="fas fa-cog"></i> <span>Settings <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span></span>
					</a>
					<ul>
						<?php if(in_array(14,$access_result_data_array)) { ?>
							<li>
								<a href="<?php echo $base_url; ?>admin/general-settings" class="<?php echo ($active == 'general-settings')? 'active':''; ?>"> <span> General Settings</span></a>
							</li> 
						<?php } ?>
					<li>
						<a class="<?php echo ($active == 'system-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/system-settings"> <span>System Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'localization')?'active':'';?>" href="<?php echo $base_url; ?>admin/localization"> <span>Localization</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'social-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/social-settings"> <span>Login Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'emailsettings')?'active':'';?>" href="<?php echo $base_url; ?>admin/emailsettings"> <span>Email Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'stripe_payment_gateway' ||$active == 'moyaser-payment-gateway' || $active == 'razorpay_payment_gateway' || $active == 'paypal_payment_gateway' || $active == 'paystack_payment_gateway' || $active == 'paysolution_payment_gateway' || $active == 'cod_payment_gateway')?'active':'';?>" href="<?php echo $base_url; ?>admin/moyaser-payment-gateway"> <span>Payment Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'seo-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/seo-settings"> <span>SEO Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'sms-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/sms-settings"> <span>SMS Settings</span></a>
					</li>
					<li>
						<a class="<?php echo ($active == 'theme-color')?'active':'';?>" href="<?php echo $base_url; ?>admin/theme-color"> <span>Theme Settings</span></a>
					</li>

						<?php if(in_array(15,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>languages" class="<?php echo ($page == 'languages' || $page == 'wep-language' || $page == 'app-page-list' || $page == 'add-app-keyword' || $page == 'add-language')?'active':'';?>"> <span>Language</span></a>
						</li>
						<?php } ?> 
						<?php if(in_array(16,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/country_code_config" class="<?php echo ($active == 'country_code_config')?'active':'';?>"> <span>Country Code</span></a>
						</li>	
						<li>
							<a href="<?php echo $base_url; ?>admin/state_code_config" class="<?php echo ($active == 'state_code_config')?'active':'';?>"> <span>State Code</span></a>
						</li> 
						<?php } if(in_array(39,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/city_code_config" class="<?php echo ($active == 'city_code_config')?'active':'';?>"> <span>City Code</span></a>
						</li>
						<?php } ?>						
						<li class="d-none">
							<a class="<?php echo ($page == 'cod')? 'active':''; ?>" href="<?php echo $base_url; ?>admin/cod"> <span> COD</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'other-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/other-settings"> <span>Other Settings</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'chat-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/chat-settings"> <span>Chat Settings</span></a>
						</li>
						<?php } if(in_array(32,$access_result_data_array)) { ?>
						<li>
							<a href="<?php echo $base_url; ?>admin/homeservice-settings" class="<?php echo ($active == 'homeservice-settings')?'active':'';?>"> <span>Service Charges</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'currency-settings' || $arg == 'create_currency' || $arg == 'currency_edit')?'active':'';?>" href="<?php echo $base_url; ?>admin/currency-settings"> <span>Currency Settings</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'service-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/service-settings"> <span>Service Settings</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'cache-settings')?'active':'';?>" href="<?php echo $base_url; ?>admin/cache-settings"> <span>Cache System</span></a>
						</li>
						<li>
							<a class="<?php echo ($active == 'sitemap')?'active':'';?>" href="<?php echo $base_url; ?>admin/sitemap"> <span>Sitemap</span></a>
						</li>	
					</ul>
				</li>
				<?php } ?>
				
				<?php if(in_array(52,$access_result_data_array) || in_array(53,$access_result_data_array) || in_array(54,$access_result_data_array)) { ?>
					<li class="submenu <?php echo ( $active == 'footer-settings' || $active == 'frontend-settings' || $active == 'home-page' || $active == 'terms-service' || $active == 'privacy-policy'||$active == 'help'||$active == 'faq'||$active == 'cookie-policy'||$active == 'about-us'||$active == 'pages')?'active':'';?>">
						<a href="#"><i class="fas fa-cog"></i> <span> Frontend Settings<span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span></span></a>
						<ul>
							<?php if(in_array(52,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($active == 'frontend-settings')?'active':'';?>"  href="<?php echo $base_url; ?>admin/frontend-settings" > <span> Header Settings</span></a>
								</li> 
							<?php } if(in_array(52,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($active == 'footer-settings')?'active':'';?>"  href="<?php echo $base_url; ?>admin/footer-settings" > <span>Footer Settings</span></a>
								</li>
							<?php } if(in_array(52,$access_result_data_array)) { ?>
								<li>
									<a class="<?php echo ($active == 'pages' || $active == 'home-page'|| $active == 'about-us'|| $active == 'cookie-policy'|| $active == 'faq'|| $active == 'help'|| $active == 'privacy-policy'|| $active == 'terms-service')?'active':'';?>"  href="<?php echo $base_url; ?>admin/pages"> <span>Pages </span></a>
								</li>
							<?php } ?>		
						</ul>
				</li>
				<li class="submenu">
					<a href="#"><i class="fas fa-book"></i> <span> Pages</span> <span class="sub-arrow"><img src="<?php echo base_url();?>assets/img/right-arrow.png" alt=""></span></span></a>
					<ul>
						<li>
							<a  class="<?php echo ($active == 'add-pages')?'active':'';?>" href="<?php echo $base_url; ?>admin/add-pages" ><span> Add Pages</span></a>
						</li>
            			<li>
							<a  class="<?php echo ($active == 'pages-list' || $active == 'edit-pages')?'active':'';?>" href="<?php echo $base_url; ?>admin/pages-list" ><span> Pages List</span></a>
						</li>	
					</ul>
				</li>
				<li class="<?php echo ($active == 'offline-payment-details')?'active':''; ?>">
					<a href="<?php echo $base_url;?>admin/offline-payment-details"><i class="fas fa-credit-card"></i> <span> Offline Payment</span></a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>