<div class="page-wrapper">
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title"><?php echo(!empty($booking['lg_payout_requests']))?($booking['lg_payout_requests']) : 'Payout Requests';  ?></h3>
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
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0 categories_table" id="categories_table" >
                                <thead>
                                    <tr>
                                        <th><?php echo(!empty($booking['lg_admin_#']))?($booking['lg_admin_#']) : '#';  ?></th>
                                        <th><?php echo(!empty($booking['lg_name']))?($booking['lg_name']) : 'Name';  ?></th>
                                        <th><?php echo(!empty($booking['lg_payout_method']))?($booking['lg_payout_method']) : 'Payout Method';  ?></th>
                                        <th><?php echo(!empty($booking['lg_payout_amount']))?($booking['lg_payout_amount']) : 'Amount';  ?></th>
                                        <th><?php echo(!empty($booking['lg_status']))?($booking['lg_status']) : 'Status';  ?></th>
                                        <th><?php echo(!empty($booking['lg_created_at']))?($booking['lg_created_at']) : 'Created At';  ?></th>
                                        <th><?php echo(!empty($booking['lg_admin_action']))?($booking['lg_admin_action']) : 'Action';  ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($requests_data)) {

                                    $i=1;
                                    foreach ($requests_data as $rows) { 
                                        $pro_name = $this->db->get_where('providers', array('id'=>$rows['user_id']))->row()->name;
                                        $amount = get_gigs_currency($rows['amount'], $rows['currency'], settingValue('currency_option'));
                                        ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $pro_name; ?></td>
                                        <td><?php echo $rows['payout_method']; ?></td>
                                        <td><?php echo settingValue('currency_symbol').$rows['amount'];?></td> <!-- settingValue('currency_symbol').$amount -->
                                        <td><?php echo 'Pending';?></td>
                                        <td><?php echo $rows['created_datetime']; ?></td>
                                        <td> 
                                            <select class="form-control payoutstatus" name="payout_status" data-id="<?php echo $rows['id']; ?>" data-userid="<?php echo $rows['user_id']; ?>" data-amount="<?php echo $rows['amount'];?>" >
                                                <option value="">Select Status</option>
                                                <option value="1">Approved</option>
                                            </select>
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