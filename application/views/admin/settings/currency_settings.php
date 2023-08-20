<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Currency Settings</h3>
				</div>
				<div class="col-auto text-right">
					<div class="col-sm-4 text-right m-b-20">
						<a href="<?php echo base_url().'admin/settings/create_currency/'; ?>" class="btn btn-primary add-button"><i class="fas fa-plus"></i></a>
					</div>
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<?php
			if ($this->session->userdata('message')) {
				echo $this->session->userdata('message');
			}
			?>
		<div class="panel">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover table-center categories_table">
						<thead>
							<tr>
								<th>#</th>
								<th>Currency Name</th>
								<th>Currency Code</th>
								<th>Currency Symbol</th>
								<th>Rate</th>
								<th>Status</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (!empty($lists)) {
								$i=1;
								foreach ($lists as $row) {
							?>
								<tr>
									<td> <?php echo $i++; ?></td>
									<td> <?php echo $row['currency_name']; ?></td>
									<td> <?php echo $row['currency_code']; ?></td>
									<td> <?php echo $row['currency_symbol']; ?></td>
									<td> <?php echo $row['rate']; ?></td>
									<td><?php if ($row['status'] == 1) { ?>
											<span class="badge bg-success">Active</span>
										<?php } else { ?>
											<span class="badge bg-danger">Inactive</span>
										<?php } ?></td>
									<td class="text-right">
										<a href="<?php echo base_url().'admin/settings/currency_edit/' . $row['id']; ?>" class="btn btn-sm bg-success-light mr-2"><i class="far fa-edit mr-1"></i>Edit</a>&nbsp;
										<a class='btn btn-sm bg-danger-light' id="cur_del" data-id="<?php  echo $row['id']; ?>"><i class="far fa-trash-alt"></i> <?php echo(!empty($admin_settings['lg_admin_delete']))?($admin_settings['lg_admin_delete']) : 'Delete';  ?></a>
									</td>
								</tr>
								<?php
								}
							} else {
								?>
								<tr>
									<td colspan="5">
										<p class="text-danger text-center m-b-0">No Records Found</p>
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

<div class="modal" id="cur_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confiramtion</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you confirm to delete this currency?</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_delete_cur" data-id="" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>