<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Staffs'])) ? $user_language[$user_selected]['lg_My_Staffs'] : $default_language['en']['lg_My_Staffs']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_My_Staffs'])) ? $user_language[$user_selected]['lg_My_Staffs'] : $default_language['en']['lg_My_Staffs']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$all_staffs = $this->employee->get_staffs($this->session->userdata('id'));
$n_count = 0;

?>
<style>
	.txtcenter{text-align:center}
</style>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			
			<div class="col-xl-9 col-md-8">
				<div class="row align-items-center mb-4">
					<div class="col">
						<h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_My_Staffs'])) ? $user_language[$user_selected]['lg_My_Staffs'] : $default_language['en']['lg_My_Staffs']; ?></h4>
					</div>
					<div class="col-auto">
						<div class="addnewdiv text-end">
							<h6><a href="<?php echo base_url()?>add-staff" class="btn btn-primary text-white"><i class="fas fa-plus me-2"></i><?php echo (!empty($user_language[$user_selected]['lg_Add_Staff'])) ? $user_language[$user_selected]['lg_Add_Staff'] : $default_language['en']['lg_Add_Staff']; ?></a></h6>
						</div>
					</div>
				</div>

						
							<div class="card transaction-table mb-0">
					<div class="card-body">
						<div class="table-responsive">
							
							<?php if(count($all_staffs)>0){  ?>
								<table class="table mb-0" id="order-summary">
							<?php } else { ?>
								<table class="table mb-0" >
							<?php } ?>
							
								<thead>
									<tr>	
										<th>S.No</th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_gender'])) ? $user_language[$user_selected]['lg_gender'] : $default_language['en']['lg_gender']; ?></th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
										<th class="text-center"><?php echo (!empty($user_language[$user_selected]['lg_Action'])) ? $user_language[$user_selected]['lg_Action'] : $default_language['en']['lg_Action']; ?></th>
									</tr>
								</thead>
								<tbody>
								<?php $sno = 1; 
									if(count($all_staffs)>0){
										foreach($all_staffs as $staff) { 
											if($staff['status'] == 1) {
												 $bg ='success'; 
												 $textbg = 'Active';
											} else {
												$bg='danger';
												$textbg = 'Deactive';
											}	
										$staff_id = $staff['id']; 
										$provider_id = $staff['provider_id'];
										$shop_assign = $this->db->where('staff_id', $staff_id)->where_not_in('status',[5,6,7])->from('book_service')->count_all_results();
										$sscArr = explode(",", $staff['sub_subcategory']);
										$ssc_name = $this->db->select('GROUP_CONCAT(sub_subcategory_name," ") as name')->where_in('id',$sscArr)->get('sub_subcategories')->row_array();
										
								?>
								
									<tr>
									<td><?php echo $sno++; ?></td>
									<td><?php echo $staff['first_name']; ?></td>
									<td><?php echo $staff['gender']; ?></td>
									<td><span class="badge bg-<?php echo $bg; ?>-light"><?php echo $textbg; ?></span></td>
									<td>
									<div class="col text-right">
									<a href="<?php echo base_url();?>edit-staff/<?php echo $staff['id'];?>" class="btn btn-sm bg-success-light me-2 "><i class="far fa-edit"></i></a>
									<a href="<?php echo base_url();?>staff-details/<?php echo $staff['id'];?>" class="btn btn-sm bg-info-light me-2"><i class="far fa-eye"></i></a>
									<?php if($shop_assign > 0) { ?>
										<a href="javascript:" class="btn btn-sm bg-danger-light me-2"  data-bs-toggle="modal" data-bs-target="#staffNotDeleteConfirmModal"><i class="far fa-trash-alt"></i></a>
									<?php } else { ?>
										<a href="javascript:" class="btn btn-sm bg-danger-light me-2 staff-delete"  data-bs-toggle="modal" data-bs-target="#staffdeleteConfirmModal" data-id="<?php echo $staff['id']?>"><i class="far fa-trash-alt me-1"></i></a>
									<?php } ?>
									</div>
									
									</td>
									
									</tr>
									<?php } 
									 } else { ?>
										<tr> <td colspan="6"> <div class="text-center text-muted"><?php echo (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found']; ?></div></td> </tr>
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
<div class="modal fade" id="staffdeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title" id="acc_title"></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="acc_msg"></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_accept_confirm">Yes</a>
				<button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="staffNotDeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Delete Staff?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Staff is assigned to the service provided by the shop and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>