<?php 
$categories = $language_content['language'];
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title"><?php echo(!empty($categories['lg_roles_permissions']))?($categories['lg_roles_permissions']) : 'Roles & Permissions';  ?></h3>
				</div>
				<div class="col-auto text-right">
					<a href="<?php echo $base_url; ?>admin/roles" class="btn btn-primary add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn mr-3 d-none" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
					
					<a href="<?php echo $base_url; ?>admin/add-roles-permissions" class="btn btn-primary add-button"><i class="fas fa-plus"></i></a>
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin/categories/categories" method="post" id="filter_inputs" class="d-none">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label><?php echo(!empty($categories['lg_admin_category']))?($categories['lg_admin_category']) : 'Category';  ?></label>
								<select class="form-control" name="category">
									<option value=""><?php echo(!empty($categories['lg_admin_select_category']))?($categories['lg_admin_select_category']) : 'Select category';  ?></option>
									<?php foreach ($list_filter as $cat) { 

										$cat_lang = ($this->session->userdata('lang'))?$this->session->userdata('lang'):'en';
										$this->db->where('category_id', $cat['id']);
							            $this->db->where('lang_type', $cat_lang);
							            $cat_name = $this->db->get('categories_lang')->row_array();

									?>
									<option value="<?=$cat['id']?>"><?php echo $cat_name['category_name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label><?php echo(!empty($categories['lg_admin_from_date']))?($categories['lg_admin_from_date']) : 'From Date';  ?></label>
								<div class="cal-icon">
									<input class="form-control start_date" type="text" name="from">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label><?php echo(!empty($categories['lg_admin_to_date']))?($categories['lg_admin_to_date']) : 'To Date';  ?></label>
								<div class="cal-icon">
									<input class="form-control end_date" type="text" name="to">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit"><?php echo(!empty($categories['lg_admin_submit']))?($categories['lg_admin_submit']) : 'Submit';  ?></button>
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
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 categories_table" id="categories_table">
								<thead>
									<tr>
										<th><?php echo(!empty($categories['lg_admin_#']))?($categories['lg_admin_#']) : '#';  ?></th>
										<th><?php echo(!empty($categories['lg_role_name']))?($categories['lg_role_name']) : 'Role Name';  ?></th>
										<!-- <th><?php //echo(!empty($categories['lg_permissions']))?($categories['lg_permissions']) : 'Permissions';  ?></th> -->
										<th><?php echo(!empty($categories['lg_admin_action']))?($categories['lg_admin_action']) : 'Action';  ?></th>		  
									</tr>
								</thead>
								<tbody>
									<?php
									$i=1;
									if(!empty($roles)){
										foreach ($roles as $rows) { 
											$role_name = $this->db->get_where('roles_permissions_lang', array('role_id'=>$rows['id'], 'lang_type'=>settingValue('language')))->row()->role_name;
											?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo $role_name; ?></td>
											<!-- <td><?php //echo wordwrap($rows['permission_modules'], 60, '<br />', true); ?></td> -->
											<td>
												<a href="<?php echo $base_url; ?>admin/edit-roles-permissions/<?php echo $rows['id']; ?>" class="btn btn-sm bg-success-light mr-2">
													<i class="far fa-edit mr-1"></i>Edit
												</a>
												<a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_roles" id="Onremove_'.$rows['id'].'" data-id="<?php echo $rows['id']; ?>"><i class="far fa-trash-alt mr-1"></i>Delete</a>
											</td>
										</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="4"><div class="text-center text-muted">No records found</div></td>
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