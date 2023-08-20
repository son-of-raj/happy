<?php 
$categories = $language_content['language'];

$query = $this->db->query("select * from language WHERE status = '1'");
$lang_test = $query->result();

$mainmodule_details = $this->db->select('parent')->group_by('parent')->where('status',1)->order_by('module_order')->get('admin_modules')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title"><?php echo(!empty($categories['lg_roles_permissions']))?($categories['lg_roles_permissions']) : 'Add Roles & Permissions';  ?></h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				<div class="card">
					<div class="card-body">
						<form id="add_roles" action="<?php echo $base_url; ?>admin/add-roles-permissions" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
							<div class="row">
								<?php foreach ($lang_test as $langval) { ?>
									<div class="col-md-6">
										<div class="form-group">
											<label><?php echo(!empty($categories['lg_role_name']))?($categories['lg_role_name']) : 'Role Name';  ?>(<?php echo $langval->language; ?>)</label>
											<input class="form-control" type="text"  name="role_name_<?php echo $langval->id; ?>" id="role_name">
										</div>
									</div>
								<?php }  ?>
							</div>
							<div class="form-group">
								<label class="set-access"><?php echo(!empty($adminuser['lg_admin_set_access']))?($adminuser['lg_admin_set_access']) : 'Set Access';  ?></label>
								<div class="example1">
									<div class="checkbox-select-all">
										<label class="custom_check">
                                            <input type="checkbox" name="selectall1" id="selectall1" class="all" value="1">
                                            <span for="selectall1" class="checkmark"></span> <?php echo(!empty($adminuser['lg_admin_select_all']))?($adminuser['lg_admin_select_all']) : 'Select all';  ?>
                                        </label>
									</div>
									<ul class="nav checkbox-list">
									<?php 
									$dups = $new_arr = array();
									foreach ($mainmodule_details as $mainmodule) {
										$module_details = $this->db->where('status',1)->where('parent',$mainmodule['parent'])->get('admin_modules')->result_array();
										foreach ($module_details as $module) {
										$checkcondition  = "";
										if(!empty($user['user_id'])){
											$access_result = $this->db->where('admin_id',$user['user_id'])->where('module_id',$module['id'])->where('access',1)->select('id')->get('admin_access')->result_array();
											if(!empty($access_result)){
												$checkcondition  = "checked='checked'";
											}
										}
									?>
										<li> 
											<label class="custom_check">
                                                <input type="checkbox" <?php echo $checkcondition; ?> name="accesscheck[]" id="check<?php echo $module['id'];?>" value="<?php echo $module['id'];?>">
                                                <span for="check1" class="checkmark"></span>  <?php echo $module['module_name'];?>
                                            </label>
										</li>

									<?php } 
									echo "</ol>";
									} ?>
									</ul>									
								</div>
							</div>
							<div class="service-fields-btns mt-0">
								<?php if($user_role==1){ ?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit"><?php echo(!empty($categories['lg_submit']))?($categories['lg_submit']) : 'Submit';  ?></button>
							<?php }?>

								<a href="<?php echo $base_url; ?>admin/roles"  class="btn btn-cancel"><?php echo(!empty($categories['lg_admin_cancel']))?($categories['lg_admin_cancel']) : 'Cancel';  ?></a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

					