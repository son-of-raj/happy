<?php 

$provider_currency_code = $this->db->select('currency_code as provider_currency')
						 ->where('provider_id',$this->session->userdata('id'))
						->get('book_service')->row_array();
												
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);

?>	

<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Provider_Deposit_History'])) ? $user_language[$user_selected]['lg_Provider_Deposit_History'] : $default_language['en']['lg_Provider_Deposit_History']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Provider_Deposit_History'])) ? $user_language[$user_selected]['lg_Provider_Deposit_History'] : $default_language['en']['lg_Provider_Deposit_History']; ?></li>
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
					<div class="card-body">
						<div class="table-responsive">
							<?php 
									if(!empty($services) && is_countable($services) && count($services)>0){?>
							<table class="table mb-0" id="order-summary">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Deposited_By'])) ? $user_language[$user_selected]['lg_Deposited_By'] : $default_language['en']['lg_Deposited_By']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Deposited_At'])) ? $user_language[$user_selected]['lg_Deposited_At'] : $default_language['en']['lg_Deposited_At']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(count($deposit)>0){
										$user_details = $this->db->where('id', $this->session->userdata('id'))->get('providers')->row_array();
										foreach($deposit as $row){ 											
											$user_currency_code = '';
											$userId = $this->session->userdata('id');
											$type = $this->session->userdata('usertype');
											if ($type == 'user') {
												$user_currency = get_user_currency();
											} else if ($type == 'provider') {
												$user_currency = get_provider_currency();
											} else if ($type == 'freelancer') {
												$user_currency = get_provider_currency();
											} else {
												$user_currency['user_currency_code'] = settingValue('currency_option');
											}
											$user_currency_code = $user_currency['user_currency_code'];
											
											if (!empty($user_details['profile_img']) && file_exists($user_details['profile_img'])) {
                                                $proimage = base_url() . $user_details['profile_img'];
                                            } else {
                                                $proimage = base_url() . 'assets/img/user.jpg';
                                            }
											
											if(!empty($row["deposit_completed_at"])){
												$depat=date(settingValue('date_format'), strtotime($row["deposit_completed_at"]));
											}else{
												$depat='-';                                
											} 
											if($row['deposit_status'] == 1){
												$status = 'Inprogress';
											} else {
												$status = 'Completed';
											}	
	
											?>
											<tr>
												
												<td>
													<img class="avatar-xs rounded-circle" src="<?php echo $proimage;?>" alt=""> <?php echo $user_details['name'];?>
												</td>
												<td><?php 
													  if(!empty($row["deposit_date"])){
														 $date=date(settingValue('date_format'), strtotime($row["deposit_date"]));
														}else{
															$date='-';                                
														}  echo $date;
													?>
												</td>
												<td><?php
												
												$row['amount'] = get_gigs_currency($row['amount'], $provider_currency_code['provider_currency'], $user_currency_code);
												
												echo currency_conversion($user_currency_code).$row['amount'];?></td>
												<td>ADMIN</td>
												<td><?php echo $status?></td>
												<td><?php echo $depat?></td>
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