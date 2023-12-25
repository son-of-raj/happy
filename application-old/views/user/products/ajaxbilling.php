<div class="my-checkout-col">
    <div class="row">
        <?php
        if (!empty($billing)) 
        {
        $n=1;
        foreach ($billing as $b) 
        {
            ?>
        <div class="col-12 col-md-4 d-flex">
            <div class="inner-checkout-col d-flex w-100">
                <div class="card w-100 cart-container">
                    <div class="card-body">
                        <input type="radio" name="select_billing" class="select_billing" value="<?php echo $b['id']?>" <?php echo ($b['id']==$cout['billing_details_id'] ? 'checked':'')?>>
                        <input type="hidden" name="address_type" class="form-control" id="address_type" value="<?php echo ($b['address_type'])?$b['address_type']:'home'; ?>">
                        <p><strong><?php echo $b['full_name']?></strong></p>
                        <p>Address Type: <?php echo($b['address_type'])?$b['address_type']:'home'; ?></p>
                        <p><?php echo $b['phone_no']?></p>
                        <p><?php echo $b['email_id']?></p>
                        <p><?php echo $b['address']?></p>
                        <p><?php echo $b['city_name']?>, <?php echo $b['state_name']?></p>
                        <p><?php echo $b['country_name']?> - <?php echo $b['zipcode']?></p>
                        <div class="mt-3">
                            <button type="button" bid="<?php echo $b['id']?>" class="edit_billing">Edit</button>
                            <button type="button" bid="<?php echo $b['id']?>" class="delete_billing">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
                $n++;
            }
        } 
        else
        {
            ?>
        <div class="col-12 col-md-6 d-flex">
            <div class="inner-checkout-col d-flex w-100">
                <div class="card w-100">
                    <div class="card-body">
                       <p> No Records Found</p>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        }
        ?>
    </div>
</div>