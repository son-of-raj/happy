
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Pages</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table custom-table mb-0 datatable" id="pages_status">
								<thead>
									<tr>
										<th>Page </th>
										<th>Slug</th>
										<th >Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										foreach ($pages as $key => $page) {
										if($page->status==1) {
										$val='checked';
									}
									else {
										$val=''; 
									}
											
									 ?>
									<tr>
										<td><?php echo $page->page_title; ?></td>
										<td><?php echo $page->page_slug; ?></td>
										<td class="text-center">
	                                		<?php if($page->page_route == 'home-page') { 
	                                			$attr = 'disabled';
	                                		} else {
	                                			$attr = '';
	                                		} ?>
	                                		<div class="status-toggle">
											<input <?php echo $attr; ?> id="pages_status<?php echo $page->id ?>" class="check pages_status" data-id="<?php echo $page->id ?>" type="checkbox" <?php echo $val ?>>
											<label for="pages_status<?php echo $page->id ?>" class="checktoggle">checkbox</label>
										</div>
										</td>
										<td >
											<a href="<?php echo $base_url; ?>settings/<?php echo $page->page_route; ?>/<?php echo $page->id; ?>" class="btn btn-sm bg-success-light me-2">
												<i class="far fa-edit me-1"></i>Edit
											</a>
										</td>
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