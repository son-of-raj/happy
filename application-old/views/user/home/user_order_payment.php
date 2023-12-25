<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Payment_History'])) ? $user_language[$user_selected]['lg_Payment_History'] : $default_language['en']['lg_Payment_History']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Payment_History'])) ? $user_language[$user_selected]['lg_Payment_History'] : $default_language['en']['lg_Payment_History']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/user_sidemenu');?>
			<div class="col-xl-9 col-md-8">
				<ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url() ?>user-payment"><?php echo (!empty($user_language[$user_selected]['lg_services'])) ? $user_language[$user_selected]['lg_services'] : $default_language['en']['lg_services']; ?></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url() ?>user-order-payment"><?php echo (!empty($user_language[$user_selected]['lg_products'])) ? $user_language[$user_selected]['lg_products'] : $default_language['en']['lg_products']; ?></a>
                    </li>
                </ul>
								
				<div class="card transaction-table mb-0">
					<div class="card-body">
						<div class="table-responsive">
							<?php 
								if(count($product)>0){?>
							<table class="table mb-0" id="order-summary">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>										
										<th><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_products'])) ? $user_language[$user_selected]['lg_products'] : $default_language['en']['lg_products']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(count($product)>0){	
										$user_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();
										foreach($product as $row){ 	
											$userdetails = $this->db->where('id', $row['providerid'])->get('providers')->row_array();
											if(!empty($row['product_image']) && file_exists($row['product_image'])){
												 $productimage=base_url().$row['product_image'];
											}else{
												$productimage="https://via.placeholder.com/360x220.png?text=Product%20Image";
											}
											$user_currency = get_user_currency();
											$user_currency_code = $user_currency['user_currency_code'];
											$product_amount = get_gigs_currency($row["product_total"], $row["product_currency"], $user_details['currency_code']);
							
											if (!empty($userdetails['profile_img']) && file_exists($userdetails['profile_img'])) {
                                                $proimage = base_url() . $userdetails['profile_img'];
                                            } else {
                                                $proimage = base_url() . 'assets/img/user.jpg';
                                            }

											?>
											<tr>
												<td>
													<img class="avatar-xs rounded-circle" src="<?php echo $proimage;?>" alt=""> <?php echo $userdetails['name'];?>
												</td>
												<td>
													<a href="javascript:void(0);">
														<img src="<?php echo $productimage;?>" class="pro-avatar" alt=""> <?php echo $row['product_name'];?>
													</a>
												</td>
												
												<td><?php echo date('d M Y',strtotime($row['created_at']));?></td>
												<td><strong><?php echo currency_conversion($user_currency_code).$product_amount;?></strong></td>
												<td>
													<?php if($row['delivery_status']==5){?>
													<span class="badge bg-success-light">Delivered</span>
													<?php } if($row['delivery_status']==6 || $row['delivery_status']==7){?>
													<span class="badge bg-danger-light">Cancelled</span>
													<?php } ?>
												</td>
											</tr>
										<?php } }else{?>
											<tr> <td colspan="5"> <div class="text-center text-muted"><?php echo (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found']; ?></div></td> </tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>			
				</div>
			</div>

		</div>
	</div>