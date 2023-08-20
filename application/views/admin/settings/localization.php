<?php  
$query = $this->db->where('status', 1)->get('currency_rate')->result_array();

$cuurency_sym = $this->db->get_where('system_settings',array('key' => 'currency_symbol'))->row()->value;

if ($cuurency_sym) {
    $symbolData = $cuurency_sym;
}else{
    $symbolData = '';
}

$country_list=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
$country_code = $this->db->get_where('system_settings',array('key' => 'countryCode'))->row()->value;

if ($country_code) {
    $codeData = $country_code;
}else{
    $codeData = 91;
}

$currencies_code = $this->db->get_where('system_settings',array('key' => 'currency_option'))->row()->value;
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title">Localization Settings</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<form class="form-horizontal" id="how_it_works" action="<?php echo base_url('admin/settings/localization'); ?>"  method="POST" enctype="multipart/form-data" >
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="row">
					<div class=" col-lg-6 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">Localization Details</h4>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label>Time Zone</label>
									<select class="form-control select" name="time_zone" id="time_zone">
										<option value="India" <?php if(settingValue('time_zone')=='India'){echo 'selected';
                                                }?>>(UTC+05:30) India</option>
										<option value="USA"<?php if(settingValue('time_zone')=='USA'){echo 'selected';
                                                }?>>(GMT-5) USA</option>
										<option value="Australia"<?php if(settingValue('time_zone')=='Australia'){echo 'selected';
                                                }?>>(UTC +10:30) Australia</option>
										<option value="South America"<?php if(settingValue('time_zone')=='South America'){echo 'selected';
                                                }?>>(UTC -5) South America</option>
										<option value="Asia"<?php if(settingValue('time_zone')=='Asia'){echo 'selected';
                                                }?>>(UTC +4) Asia</option>
										<option value="North America Atlantic"<?php if(settingValue('time_zone')=='North America Atlantic'){echo 'selected';
                                                }?>>(UTC -3) North America Atlantic</option>
										<option value="South America"<?php if(settingValue('time_zone')=='South America'){echo 'selected';
                                                }?>>(UTC -4) South America</option>
										<option value="Pacific"<?php if(settingValue('time_zone')=='Pacific'){echo 'selected';
                                                }?>>(UTC -12) Pacific</option>
										<option value="Military"<?php if(settingValue('time_zone')=='Military'){echo 'selected';
                                                }?>>(UTC +2) Military</option>
										<option value="Europe"<?php if(settingValue('time_zone')=='Europe'){echo 'selected';
                                                }?>>(UTC +1) Europe</option>
									</select>
								</div>
								<div class="form-group">
									<label>Date Format</label>
									<select class="form-control select" name="date_format" id="date_format">
										<option value="Y/m/d" <?php if(settingValue('date_format')=='Y/m/d'){echo 'selected';
                                                }?>><?php echo date('Y/m/d'); ?></option>
										<option value="d.m.Y" <?php if(settingValue('date_format')=='d.m.Y'){echo 'selected';
                                                }?>><?php echo date('d.m.Y'); ?></option>
										<option value="d-m-Y" <?php if(settingValue('date_format')=='d-m-Y'){echo 'selected';
                                                }?>><?php echo date('d-m-Y'); ?></option>
                                        <option value="m/d/Y" <?php if(settingValue('date_format')=='m/d/Y'){echo 'selected';
                                                }?>><?php echo date('m/d/Y'); ?></option>        
										<option value="M-Y-D" <?php if(settingValue('date_format')=='M-Y-D'){echo 'selected';
                                                }?>><?php echo date('M-Y-D'); ?></option>
										<option value="Y-m-d" <?php if(settingValue('date_format')=='Y-m-d'){echo 'selected';
                                                }?>><?php echo date('Y-m-d'); ?></option>
										<option value="M-d-Y" <?php if(settingValue('date_format')=='M-d-Y'){echo 'selected';
                                                }?>><?php echo date('M-d-Y'); ?></option>
										<option value="d-M-Y" <?php if(settingValue('date_format')=='d-M-Y'){echo 'selected';
                                                }?>><?php echo date('d-M-Y'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label>Time Format</label>
									<select class="form-control select"  name="time_format" id="time_format">
										<option <?php if(settingValue('time_format')=='12 Hours'){echo 'selected';
                                                }?>>12 Hours</option>
										<option <?php if(settingValue('time_format')=='24 Hours'){echo 'selected';
                                                }?>>24 Hours</option>
									</select>
								</div>
								<div class="form-group">
										<label>Default Country</label>
                                                <select name="countryCode" id="countryCode" class="form-control countryCode final_provider_c_code">
                                                    <?php foreach ($country_list as $key => $country) { 

                                                    	$list = explode('(', $country['country_name']);
                                                    	$country_code_key = $list[0].'-'.strtolower($country['country_code']).'('.$list[1]; 
                                                        if($country['country_id']== $codeData){ $select='selected';}else{ $select='';} ?>
                                                        <option <?php echo $select;?> data-countryCode="<?php echo $country['country_code'];?>" value="<?php echo $country['country_id'];?>"><?php echo $country_code_key;?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="country_code_key" id="country_code_key" value="">
								</div>
								<div class="form-group">
									<label>Currency Code</label>
									<select class="form-control currency_code" name="currency_option" id="currency_option" >
						                <?php 
						                foreach($query as $currency) {
						                	if($currency['currency_code'] == $currencies_code){
						                	 	$select='selected';
						                	 }else{ 
						                	 	$select='';
						                	 }
						                	 ?>
						                 <option <?php echo $select;?> value="<?php echo $currency['currency_code']; ?>"><?php echo $currency['currency_code']; ?></option>';
						                <?php }
						                ?>
						            </select>
								</div>
								<div class="form-group">
									<label>Currency Symbol</label>
									<input type="text" class="form-control" name="currency_symbol" id="currency_symbol" readonly="" value="<?php echo settingValue('currency_symbol')?settingValue('currency_symbol'):'';?>">
								</div>
								<?php if($this->session->userdata('role') == 1) { ?>
									<div class="form-groupbtn">
										<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>
</div>