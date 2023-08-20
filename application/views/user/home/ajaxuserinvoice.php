<?php
if (!empty($bookings)) 
{ 
    foreach ($bookings as $val) 
    {
		if(!empty($val['service_image']) && file_exists($val['service_image'])){
			$serviceimage = base_url().$val['service_image'];
		}else{
			$serviceimage = "https://via.placeholder.com/360x220.png?text=Service%20Image";
		} 
        
		$servicetitle = preg_replace('/[^\w\s]+/u',' ',$val['service_title']);
		$servicetitle = str_replace(' ', '-', $servicetitle);
		$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
		$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($val['service_id']);
        ?>

        <div class="bookings">
            <div class="booking-list">
                <div class="booking-widget">
                    <a href="<?php echo $service_url; ?>" class="booking-img">
                        <img src="<?php echo $serviceimage; ?>" alt="Service Image">
                    </a>
                    <div class="booking-det-info">
                        <h3 class="mb-2">
                            <a href="<?php echo $service_url; ?>">
                                <?php echo $val['service_title']; ?>
                            </a>
                        </h3>
                        <?php
                        if (!empty($val['profile_img']) && file_exists($val['profile_img'])) {
                            $image = base_url() . $val['profile_img'];
                        } else {
                            $image = base_url() . 'assets/img/user.jpg';
                        }
                        $user_currency_code = '';
                        $userId = $this->session->userdata('id');
                        
                        $service_amount1 = $val['service_amount'];

                        $user_currency = get_user_currency();
                        $user_currency_code = $user_currency['user_currency_code'];
                        $service_amount1 = get_gigs_currency($val['service_amount'], $val['currency_code'], $user_currency_code);
                        ?>
                        <ul class="booking-details">
                            <li>
                                <span><?php echo (!empty($user_language[$user_selected]['lg_Booking_Date'])) ? $user_language[$user_selected]['lg_Booking_Date'] : $default_language['en']['lg_Booking_Date']; ?></span><?php echo  date('d M Y', strtotime($val['service_date'])); ?> 
                            </li>
                            <li><span><?php echo (!empty($user_language[$user_selected]['lg_Booking_time'])) ? $user_language[$user_selected]['lg_Booking_time'] : $default_language['en']['lg_Booking_time']; ?></span> <?php echo  $val['from_time'] ?> - <?php echo  $val['to_time'] ?></li>
                            <li><span><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></span> <?php echo currency_conversion($user_currency_code) . $service_amount1; ?></li>
                            <li>
                                 <span><?php echo (!empty($user_language[$user_selected]['lg_Provider'])) ? $user_language[$user_selected]['lg_Provider'] : $default_language['en']['lg_Provider']; ?></span>
                                <div class="avatar avatar-xs mr-1">
                                    <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $image; ?>">
                                </div> <?php echo  !empty($val['provider_name']) ? $val['provider_name'] : '-'; ?>
                            </li>
							
                        </ul>
                    </div>
                </div>

                <div class="booking-action">
					<a target="_blank" href="<?php echo base_url(); ?>user/dashboard/export_invoice/<?php echo $val['id']; ?>" class="btn btn-sm bg-success-light">Export</a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
   <p><?php echo (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou']; ?></p>
<?php } ?>
<?php
echo $this->ajax_pagination->create_links();
?>