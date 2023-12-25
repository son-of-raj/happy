<div class="page-wrapper">
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title"><?php echo(!empty($booking['lg_comments_list']))?($booking['lg_comments_list']) : 'Comments List';  ?></h3>
                </div>
                <div class="col-auto text-right">
                    <a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive total-booking-report">
                            <table class="table table-hover table-center mb-0 blogcomments_table" >
                                <thead>
                                    <tr>
                                        <th><?php echo(!empty($booking['lg_admin_#']))?($booking['lg_admin_#']) : '#';  ?></th>
                                        <th><?php echo(!empty($booking['lg_name']))?($booking['lg_name']) : 'Name';  ?></th>
                                        <th><?php echo(!empty($booking['lg_email']))?($booking['lg_email']) : 'Email';  ?></th>
                                        <th><?php echo(!empty($booking['lg_comments']))?($booking['lg_comments']) : 'Comments';  ?></th>
                                        <th><?php echo(!empty($booking['lg_created_at']))?($booking['lg_created_at']) : 'Created At';  ?></th>
                                        <th><?php echo(!empty($booking['lg_admin_action']))?($booking['lg_admin_action']) : 'Action';  ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($comments)) {

                                    $i=1;
                                    foreach ($comments as $rows) {
                                    $badge='';
                                    if ($rows['status']==1) {
                                        $badge='Approved';
                                        $color='green';
                                        $status = $badge;
                                    }
                                    if ($rows['status']==2) {
                                        $badge='Deleted';
                                        $color='danger';
                                        $status = $badge;
                                    } 
                                    if ($rows['status']==3) {
                                        $badge='Rejected';
                                        $color='danger';
                                        $status = $badge;
                                    }
                                    ?>
                                    
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $rows['name']; ?></td>
                                        <td><?php echo $rows['email']; ?></td>
                                        <td><?php echo wordwrap($rows['comment'], 60, '<br />', true);?></td>
                                        <td><?php echo $rows['created_at']; ?></td>
                                        <td> 
                                            <?php if($rows['status'] == 0) { ?>
                                                <select class="form-control commentstatus" name="comment_status" data-id="<?php echo $rows['id']; ?>">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Approved</option>
                                                    <option value="3">Rejected</option>
                                                </select>
                                            <?php } else {  
                                                echo $status; 
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php } 
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="9">
                                            <div class="text-center text-muted"><?php echo(!empty($booking['lg_admin_no_records_found']))?($booking['lg_admin_no_records_found']) : 'No Records Found';  ?></div>
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