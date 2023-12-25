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
							<h3 class="page-title"><?php echo(!empty($categories['lg_edit_roles_permissions']))?($categories['lg_edit_roles_permissions']) : 'Edit Roles & Permissions';  ?></h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				<div class="card">
					<div class="card-body">
						<form id="add_roles" action="<?php echo $base_url; ?>admin/add-roles-permissions" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
							<input type="hidden" name="role_id" value="<?php echo $roles[0]['id']; ?>"/>
							<?php foreach ($lang_test as $langval) { 
								//echo '<pre>'; print_r(); exit;
								$role_name = $this->db->get_where('roles_permissions_lang', array('role_id'=>$roles[0]['id'], 'lang_type'=>$langval->language_value))->row()->role_name;
								?>
							<div class="form-group">
								<label><?php echo(!empty($categories['lg_role_name']))?($categories['lg_role_name']) : 'Role Name';  ?>(<?php echo $langval->language; ?>)</label>
								<input class="form-control" type="text"  name="role_name_<?php echo $langval->id; ?>" id="role_name" value="<?php echo ($role_name)?$role_name:''; ?>">
							</div>
							<?php }  ?>
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
										$permission_details = explode(',', $roles[0]['permission_modules']);
										foreach ($module_details as $module) {
										$checkcondition  = "";
										if(in_array($module['id'], $permission_details)){
											$checkcondition  = "checked='checked'";
										}
									?>
									<li><input type="checkbox" <?php echo $checkcondition; ?> name="accesscheck[]" id="check<?php echo $module['id'];?>" value="<?php echo $module['id'];?>"> <label for="check1"><?php echo $module['module_name'];?></label></li>
									<?php } 
									echo "</ol>";
									} ?>
									</ul>									
								</div>
							</div>
							<div class="mt-4">
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

					