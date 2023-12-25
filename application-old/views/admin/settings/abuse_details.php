<?php
	$provider_name=$this->db->where('id',$list[0]['pro_id'])->from('providers')->get()->row_array();
	$user_name=$this->db->where('id',$list[0]['report_user_id'])->from('users')->get()->row_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-sm-6">
							<h3 class="page-title">Abuse Details</h3>
						</div>
						<div class="col-sm-6">
							<div class="text-right mb-3">
								<a href="<?php echo $base_url; ?>admin/abuse-reports" class="btn btn-primary">Back</a>
							</div>
						</div>

					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                        <div class="form-group">
							<h5>Name</h5>
							<label><?php echo $provider_name['name']; ?></label>
							
						</div>
						
						<div class="form-group">
							<h5>User name</h5>
							<label><?php echo $user_name['name']; ?></label>
							
						</div>
						
						<div class="form-group">
							<h5>descriptions</h5>
							<label><?php echo $list[0]['description']; ?></label>
							
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	