<?php
$currency_option = $this->db->get_where('system_settings',array('key' => 'currency_option'))->row()->value;

$user_currency_code = $currency_option;
if (!empty($this->session->userdata('provider_id'))) {
$get_details = $this->db->select('token, category, subcategory')->where('id',$this->session->userdata('provider_id'))->get('providers')->row_array();
$protoken = $get_details['token'];
$subcategory = $this->service->adminreadSubcategory();
$subsubcategory = $this->service->adminreadSubSubcategory();

$shop_details = $this->db->select('id, shop_name, shop_code, shop_location, shop_latitude, shop_longitude')->where('status',1)->where('provider_id',$this->session->userdata('provider_id'))->order_by('id','DESC')->get('shops')->result_array();
}
$category = $this->service->readCategory();

$btntxt = 'Submit';
$usrbtntxt = 'Send to user';
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
			  <!-- Page Header -->
			  <div class="page-header">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-title">Admin Add Service</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                    <form method="post" enctype="multipart/form-data" autocomplete="off" id="add_service" action="<?php echo base_url() ?>admin/service/add_service_ajax">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
                   
                    <div class="service-fields mb-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Select Provider<span class="text-danger">*</span></label>
                                <select class="form-control select pro_change" name="username">
                                <option value="">Select Vendor name</option>
                                <?php foreach ($provider_list as $providers) { ?>
                                <?php if (!empty($providers->name)) { ?>
                                <option value="<?=$providers->id?>" id='test' data-id="<?=$providers->id?>"><?php echo (!empty($providers->name))?$providers->name:''?></option>

                                <?php } }?>
                            </select>
                            </div>
                        </div>
                        <h3 class="heading-2">Service Category</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Category Name <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Category" name="category" id="service_category"   required>
                                    <option>Select Category</option>
                                    <?php foreach ($category as $cat) { ?>
                                        <option value="<?php echo $cat['id']?>"  ><?php echo $cat['category_name']?>
                                    </option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>SubCategory</label>
                                    <select class="form-control select" title="Sub Category" name="subcategory" id="service_subcategory">
                                    
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="service-fields mb-3">
                        <h3 class="heading-2">Service Information</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Service Name <span class="text-danger">*</span></label>
                                    <input type="hidden" class="form-control" id="map_key" value="<?php echo $map_key?>" >
                                    <input class="form-control" type="text" name="service_title" id="service_title" required>
                                </div>
                            </div>       
                            
                            <div class="col-lg-12 d-none">
                                <div class="form-group">
                                    <label>Service Location <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_location" id="service_location" required>
                                    <input type="hidden" name="service_latitude" id="service_latitude">
                                    <input type="hidden" name="service_longitude" id="service_longitude">
                                </div>
                            </div>
                            <?php //if (!empty($this->session->userdata('provider_id'))) { ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Shops  <span class="text-danger">*</span></label>
                                    <select class="form-control select services_shop_id" title="Shop" name="shop_id" id="shop_id">
                                        <!-- <option value="">Select Shop</option>
                                        <?php foreach ($shop_details as $shop) { ?>
                                        <option value="<?php echo $shop['id']?>" data-subtext="<?php echo (!empty($shop['shop_code']))?$shop['shop_code']:''; ?>" data-location="<?php echo $shop['shop_location']?>" data-latitude="<?php echo $shop['shop_latitude']?>" data-longitude="<?php echo $shop['shop_longitude']?>"  ><?php echo $shop['shop_name']?>
                                        </option>
                                    <?php } ?> -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                   
                                    <label>
                                        Staffs <span class="text-danger">*</span> 
                                    </label>
                                    <select class="form-control select" title="Staffs" name="staff_id[]" id="staff_id" multiple>
                                    
                                    </select>
                                </div>
                            </div>
                            <?php //} ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Amount (included with VAT) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_amount" id="service_amount" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Service Duration <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" name="duration" id="duration" required>
                                      <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">min(s)</span>
                                      </div>
                                      <input type="hidden" class="form-control" name="duration_in" id="duration_in" value="min(s)">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (settingValue('service_offered_showhide') == 1) { ?>
                    <!-- Additional Service Content -->
                    <div class="service-fields mb-3" id="addiservice_div">
                        <h3 class="heading-2">Additional Services</h3>

                        <div class="additional-info">
                            <div class="row form-row additional-cont-label d-none">
                                <div class="col-lg-4">
                                    <div class="form-group">    
                                         <label>Name </label>
                                    </div> 
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">  
                                         <label>Amount </label>
                                    </div> 
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-group">     
                                        <label>Duration </label>
                                        <input type="hidden" id="durintxt" value="min(s)" />
                                    </div> 
                                </div>
                                
                            </div>
                        </div>
                        <div class="add-more-additional form-group">
                            <a href="javascript:void(0);" class="add-additional"><i class="fas fa-plus-circle"></i> Add More Additional Services</a>
                        </div>
                    </div>
                    <!-- Additional Service Content Ends -->
                    <?php  }  ?>
                    
                   <div class="service-fields mb-3">
                        <h3 class="heading-2">Details Information</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Description  <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="about" id="ck_editor_textarea_id4"></textarea>
                                    <?php echo display_ckeditor($ckeditor_editor4);  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2">Service Gallery</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="service-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Upload Image *</span>
                                    <input type="file" name="images[]" id="images" multiple accept="image/jpeg, image/png, image/gif,">
                                </div>
                                <div id="uploadPreview"></div>
                            </div>
                        </div>
                    </div>
                    
                       <div class="service-fields mb-3">
                        <h3 class="heading-2">Service For</h3>
                        <div class="row">
                            <div class="col-lg-3">
                               <label> Service For</label>
                               <select class="form-control select" id="service_for" name="service_for" >
                                    <option value="1">Shop</option>
                                    <option value="2">User</option>
                               </select>
                            </div>
                            <?php if (!empty($this->session->userdata('provider_id'))) { ?>
                            <div class="col-lg-4 d-none" id="divChatUserId">
                                <input type="hidden" class="txtBtn" name="txt_btn" value="<?php  echo $btntxt."__".$usrbtntxt; ?>" />
                                <?php $sql = "SELECT id,name FROM users WHERE token IN (SELECT DISTINCT(sender_token) FROM chat_table WHERE status = 1 and receiver_token = '".$protoken."' UNION SELECT DISTINCT(receiver_token) FROM chat_table WHERE status = 1  and sender_token = '".$protoken."')"; 
                                $usr=$this->db->query($sql)->result_array();                                
                                ?>
                               <label> User Details</label>
                               <select class="form-control select" title="User List" id="chat_userid" name="chatuserid" disabled required>
                                    <?php foreach($usr as $uval){ ?>                                        <option value="<?php echo $uval['id']?>"><?php echo $uval['name']; ?></option>
                                    <?php } ?>
                               </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit"><?php echo $btntxt; ?></button>
                    </div>
                </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>