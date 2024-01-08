<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| ----------------------------------------------------------------  ---------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['admin'] = 'admin/login';
$route['forgot-password'] = 'admin/login/forgot_password';
$route['dashboard'] = 'admin/dashboard';
$route['map'] = 'admin/dashboard/map_list';
$route['map-lists'] = 'admin/dashboard/service_map_list';
$route['admin-profile'] = 'admin/profile';
$route['admin/logout'] = 'admin/login/logout';
$route['admin/wallet'] = 'admin/wallet';
$route['admin/wallet-history'] = 'admin/wallet/wallet_history';
/*booking report*/
$route['admin/total-report'] = 'admin/Booking/total_bookings';
$route['admin/pending-report'] = 'admin/Booking/pending_bookings';
$route['admin/inprogress-report'] = 'admin/Booking/inprogress_bookings';
$route['admin/complete-report'] = 'admin/Booking/completed_bookings';
$route['admin/reject-report'] = 'admin/Booking/rejected_bookings';
$route['admin/cancel-report'] = 'admin/Booking/cancel_bookings';
$route['reject-payment/(:num)'] = 'admin/Booking/reject_booking_payment';
$route['pay-reject'] = 'admin/Booking/update_reject_payment';

$route['admin-notification'] = 'admin/Dashboard/admin_notification';

$route['admin/SendPushNotification'] = 'admin/dashboard/SendPushNotification';
$route['admin/SendPushNotificationList'] = 'admin/dashboard/SendPushNotificationList';
$route['admin/send-push-notification'] = 'admin/dashboard/send_push_notification';

//admin users
$route['adminusers'] = 'admin/dashboard/adminusers';
$route['adminusers/edit'] = 'admin/dashboard/edit_adminusers';
$route['adminusers/edit/(:num)'] = 'admin/dashboard/edit_adminusers/$1';
$route['adminuser-details/(:num)'] = 'admin/dashboard/adminuser_details/$1';
$route['adminusers-list'] = 'admin/dashboard/adminusers_list';

//Products Admin
$route['product-categories'] = 'admin/products/product_categories';
$route['manage-product-category/(:num)'] = 'admin/products/manage_product_category/$1';
$route['product-subcategories'] = 'admin/products/product_subcategories';
$route['manage-product-subcategory/(:num)'] = 'admin/products/manage_product_subcategory/$1';
$route['product_units'] = 'admin/products/product_units';

$route['manage-product-unit/(:num)'] = 'admin/products/manage_product_unit/$1';
$route['admin-product-list'] = 'admin/products/admin_product_list';
$route['admin/product-orders'] = 'admin/products/product_orders';
$route['admin/order-refund/(:num)'] = 'admin/products/order_refund/$1';
//


//email template 
$route['emailtemplate'] = 'admin/emailtemplate';
$route['edit-emailtemplate/(:num)'] = 'admin/emailtemplate/edit/$1';

//

/* Settings*/
$route['admin/homeservice-settings'] = 'admin/settings/homeservice_settings';
$route['admin/moyaser-payment-gateway'] = 'admin/settings/moyaser_payment_gateway';
$route['admin/fb-social-media'] = 'admin/settings/fb_social_media';
$route['admin/googleplus-social-media'] = 'admin/settings/googleplus_social_media';
$route['admin/twit-social-media'] = 'admin/settings/twit_social_media';
$route['admin/emailsettings'] = 'admin/settings/emailsettings';
$route['admin/sms-settings'] = 'admin/settings/smssettings';
$route['admin/stripe-payment-gateway'] = 'admin/settings/stripe_payment_gateway';
$route['admin/cod-payment-gateway'] = 'admin/settings/cod_payment_gateway';
$route['admin/razorpay-payment-gateway'] = 'admin/settings/razorpay_payment_gateway';
$route['admin/paypal-payment-gateway'] = 'admin/settings/paypal_payment_gateway';
$route['admin/paytabs-payment-gateway'] = 'admin/settings/paytabs_payment_gateway';
$route['admin/aboutus'] = 'admin/settings/aboutus';
$route['admin/termconditions'] = 'admin/settings/termconditions';
$route['admin/privacypolicy'] = 'admin/settings/privacypolicy';
$route['admin/banner-image'] = 'admin/settings/banner_image';
$route['admin/edit-banner/(:num)'] = 'admin/settings/edit_banner/$1';

$route['users'] = 'admin/dashboard/users';
$route['user-details/(:num)'] = 'admin/dashboard/user_details/$1';
$route['users-list'] = 'admin/dashboard/users_list';


$route['categories'] = 'admin/categories/categories';
$route['add-category'] = 'admin/categories/add_categories';
$route['categories/check-category-name'] = 'admin/categories/check_category_name';
$route['edit-category/(:num)'] = 'admin/categories/edit_categories/$1';

$route['subcategories'] = 'admin/categories/subcategories';
$route['add-subcategory'] = 'admin/categories/add_subcategories';
$route['categories/check-subcategory-name'] = 'admin/categories/check_subcategory_name';
$route['edit-subcategory/(:num)'] = 'admin/categories/edit_subcategories/$1';

$route['sub_subcategories'] = 'admin/categories/sub_subcategories';
$route['add-sub-subcategory'] = 'admin/categories/add_sub_subcategories';
$route['categories/check-subsubcategory-name'] = 'admin/categories/check_subsubcategory_name';
$route['edit-sub-subcategory/(:num)'] = 'admin/categories/edit_sub_subcategories/$1';

$route['dashboard/check-usr-mobile'] = 'admin/dashboard/check_usr_mobile';
$route['dashboard/check-usr-emailid'] = 'admin/dashboard/check_usr_emailid';
$route['edit-user/(:num)'] = 'admin/dashboard/edit_user/$1';

$route['service/check-pro-mobile'] = 'admin/service/check_pro_mobile';
$route['service/check-pro-emailid'] = 'admin/service/check_pro_emailid';
$route['edit-provider/(:num)/(:num)'] = 'admin/service/edit_provider/$1/$2';
$route['edit-service/(:num)'] = 'admin/service/edit_service/$1';

$route['update-subscriptions/(:num)'] = 'admin/service/update_subscriptions';
$route['subscriptions-lists'] = 'admin/service/subscriptions_lists';
$route['freelancer-subscriptions'] = 'admin/service/freelancer_subscriptions';
$route['subscriptions'] = 'admin/service/subscriptions';

$route['add-subscription'] = 'admin/service/add_subscription';

$route['service/check-subscription-name'] = 'admin/service/check_subscription_name';

$route['service/save-subscription'] = 'admin/service/save_subscription';

$route['edit-subscription/(:num)'] = 'admin/service/edit_subscription/$1';

$route['service/update-subscription'] = 'admin/service/update_subscription';

$route['subscription-list'] = 'user/subscription/subscription_list';

$route['ratingstype'] = 'admin/ratingstype/ratingstype';
$route['review-reports'] = 'admin/ratingstype/review_report';

$route['add-ratingstype'] = 'admin/ratingstype/add_ratingstype';

$route['ratingstype/check-ratingstype-name'] = 'admin/ratingstype/check_ratingstype_name';

$route['edit-ratingstype/(:num)'] = 'admin/ratingstype/edit_ratingstype/$1';
$route['delete-ratingstype/(:num)'] = 'admin/ratingstype/delete_ratingtype/$1';

$route['reward-system-details/(:num)'] = 'admin/rewards/reward_system_details';
$route['reward-system'] = 'admin/rewards/reward_system';

$route['deposit-provider-list'] = 'admin/deposit/deposit_list';

$route['coupons-details/(:num)'] = 'admin/service/service_coupons_details';
$route['service-coupons'] = 'admin/service/service_coupons';

$route['offers-details/(:num)'] = 'admin/service/service_offers_details';
$route['service-offers'] = 'admin/service/service_offers';

$route['service/check-additional-servicename'] = 'admin/service/check_additional_servicename';
$route['edit-additional-services/(:num)'] = 'admin/service/edit_additional_services/$1';
$route['add-additional-services'] = 'admin/service/add_additional_services';
$route['additional-services'] = 'admin/service/additional_services';
$route['freelancer-details/(:num)'] = 'admin/service/freelancer_details/$1';
$route['freelances-providers'] = 'admin/service/freelance_providers';
$route['service-providers'] = 'admin/service/service_providers';


$route['provider_list'] = 'admin/service/provider_list';
$route['service-list'] = 'admin/service/service_list';
$route['provider-details/(:num)'] = 'admin/service/provider_details/$1';
$route['admin/provider-list'] = 'admin/service/provider_list';
$route['payment_list'] = 'admin/payments/payment_list';
$route['admin-payment/(:any)'] = 'admin/payments/admin_payment/$1';
$route['service-details/(:num)'] = 'admin/service/service_details/$1';
$route['contact-details/(:num)'] = 'admin/contact/contact_details/$1';

$route['branch-details/(:num)'] = 'admin/branch/branch_details/$1';
$route['branch-lists'] = 'admin/branch/branch_lists';

$route['shop/check-shop-mobile'] = 'admin/shop/check_shop_mobile';
$route['shop/check-shop-emailid'] = 'admin/shop/check_shop_emailid';
$route['shop-edit/(:num)'] = 'admin/shop/shop_edit/$1';
$route['shop-details/(:num)'] = 'admin/shop/shop_details/$1';
$route['shop-lists'] = 'admin/shop/shop_lists';

$route['staffs/check-staff-mobile'] = 'admin/staffs/check_staff_mobile';
$route['staffs/check-staff-emailid'] = 'admin/staffs/check_staff_emailid';
$route['staff-edit/(:num)'] = 'admin/staffs/staff_edit/$1';
$route['view-staff-details/(:num)'] = 'admin/staffs/staff_details/$1';
$route['staff-lists'] = 'admin/staffs/staff_lists';

/*web*/

$route['all-categories'] = 'categories';
$route['maincategories/(:any)'] = 'categories/subcategories/$1';
$route['featured-category'] = 'user/categories/featured_categories';
$route['service-preview/(:any)'] = 'home/service_preview/$1';
$route['all-services'] = 'home/services';
$route['offered-services'] = 'home/offered_services';
$route['featured-services'] = 'user/service/featured_services';
$route['popular-services'] = 'user/service/popular_services';
$route['search'] = 'home/services';
$route['about-us'] = 'user/about/about_us';
$route['terms-conditions'] = 'user/terms/terms';
$route['contact'] = 'user/contact/contact';
$route['pages/(:any)'] = 'home/pages/$1';
$route['search/(:any)'] = 'home/services/$1';
$route['search/(:any)/(:any)'] = 'home/services/$1/$2';
$route['search/(:any)/(:any)/(:any)'] = 'home/services/$1/$2/$3';
$route['privacy'] = 'user/privacy/privacy';
$route['faq'] = 'user/privacy/faq';
$route['help'] = 'user/privacy/help';
$route['terms-conditions-app'] = 'user/terms/terms_conditions_app';
$route['privacy-app'] = 'user/privacy/privacy_app';
$route['contact-app'] = 'user/contact/contact_app';

//my_service_pagination
$route['my-services'] = 'user/myservice/index';
$route['my-services-inactive'] = 'user/myservice/inactive_services';
$route['service-offer-history'] = 'user/myservice/service_offer_history';

$route['coupons'] = 'user/myservice/service_coupons';
$route['coupon-details'] = 'user/myservice/service_coupons_details';

$route['rewards'] = 'user/myservice/service_rewards';
$route['reward-details'] = 'user/myservice/service_reward_details';

//end

//freelancer-shop
$route['freelances/my-shop-inactive'] = 'user/shopfreelancer/inactive_shop';
$route['freelances/shop-preview/(:any)'] = 'user/shopfreelancer/shop_preview/$1';
$route['freelances/edit-shop/(:num)'] = 'user/shopfreelancer/edit_shop/$1';
$route['freelances/add-shop'] = 'user/shopfreelancer/add_shop';
$route['freelances/shop'] = 'user/shopfreelancer/index';
//Shop, branch, Staff and Appointment
$route['service-checkout/(:any)'] = 'user/appointment/checkout/$1';
$route['edit-appointment/(:any)'] = 'user/appointment/edit_appointment/$1';
$route['book-appointment/(:any)'] = 'user/appointment/book_appointment/$1';
$route['my-branch-inactive'] = 'user/branch/inactive_branch';
$route['branch-preview/(:any)'] = 'user/branch/branch_preview/$1';
$route['edit-branch/(:num)'] = 'user/branch/edit_branch/$1';
$route['add-branch'] = 'user/branch/add_branch';
$route['branch'] = 'user/branch/index';
$route['my-shop-inactive'] = 'user/shop/inactive_shop';
$route['shop-preview/(:any)'] = 'user/shop/shop_preview/$1';
$route['edit-shop/(:num)'] = 'user/shop/edit_shop/$1';
$route['add-shop'] = 'user/shop/add_shop';
$route['shop'] = 'user/shop/index';
$route['staff-details/(:num)'] = 'user/service/staff_details/$1';
$route['edit-staff/(:num)'] = 'user/service/edit_staff/$1';
$route['add-staff'] = 'user/service/add_staff';
$route['staff-settings'] = 'user/service/staff_settings';

$route['add-service'] = 'user/service/add_service';
$route['edit_service'] = 'user/service/edit_service';
$route['notification-list'] = 'user/service/notification_view';
$route['booking'] = 'user/service/booking';
$route['update-bookingstatus'] = 'user/service/update_bookingstatus';
$route['update-status-user'] = 'user/service/update_status_user';
$route['update_booking/(:any)'] = 'user/service/update_booking/$1';
$route['user_bookingstatus/(:any)'] = 'user/service/user_bookingstatus/$1';
$route['book-service/(:any)'] = 'user/service/book_service/$1';
$route['user-dashboard'] = 'user/service/user_dashboard';
$route['provider-dashboard'] = 'user/service/provider_dashboard';
$route['user-settings'] = 'user/dashboard/user_settings';
$route['change-password'] = 'user/dashboard/userchangepassword';
$route['provider-change-password'] = 'user/dashboard/prochangepassword';
$route['user-wallet'] = 'user/dashboard/user_wallet';
$route['paytab-payment'] = 'user/dashboard/paytab_payment'; //user/dashboard/paytab_payment
$route['user-payment'] = 'user/dashboard/user_payment';
$route['user-accountdetails'] = 'user/dashboard/user_accountdetails';
$route['user-reviews'] = 'user/dashboard/user_reviews';
$route['provider-reviews'] = 'user/dashboard/provider_reviews';
$route['booking-details/(:any)'] = 'user/service/booking_details/$1';
$route['booking-details-user/(:any)'] = 'user/service/booking_details_user/$1';

$route['user-order-payment'] = 'user/dashboard/user_order_payment';
$route['product-payment'] = 'user/dashboard/product_payment';
$route['provider-deposit-history'] = 'user/dashboard/provider_deposit_history';
$route['provider-bookings'] = 'user/dashboard/provider_bookings';
$route['offline-bookings'] = 'user/appointment/offline_bookings';
$route['provider-settings'] = 'user/dashboard/provider_settings';
$route['provider-wallet'] = 'user/dashboard/provider_wallet';
$route['provider-payment'] = 'user/dashboard/provider_payment';
$route['provider-subscription'] = 'user/dashboard/provider_subscription';
$route['provider-availability'] = 'user/dashboard/provider_availability';
$route['provider-accountdetails'] = 'user/dashboard/provider_accountdetails';
$route['create-availability'] = 'user/dashboard/create_availability';
$route['user-bookings'] = 'user/dashboard/user_bookings';
$route['logout'] = 'user/login/logout';

/*
 * Multiple Languages
 */
$route['language'] = 'admin/language';
$route['languages'] = 'admin/language/languages';
$route['edit-languages/(:any)'] = 'admin/language/editLanguages/$1';
$route['add-languages'] = 'admin/language/addLanguage';
$route['web-languages/(:any)'] = 'admin/language/webLanguages/$1';

/*api*/

/*chat api*/

$route['user-chat'] = 'user/Chat_ctrl';
$route['user-chat/booking-new-chat'] = 'user/Chat_ctrl/booking_new_chat';
$route['user-chat/insert_chat'] = 'user/Chat_ctrl/insert_message';
$route['user-chat/get_user_chat_lists'] = 'user/Chat_ctrl/get_user_chat_lists';

$route['api/country_details'] = 'api/api/country_details';
$route['api/chat_details'] = 'api/api/chat_details';
$route['api/chat'] = 'api/api/chat';
$route['api/chat_storage'] = 'api/api/insert_message';
$route['api/get-chat-list'] = 'api/api/get_chat_list';
$route['api/get-chat-history'] = 'api/api/get_chat_history';
$route['api/flash-device-token'] = 'api/api/flash_device_token';
$route['api/get-notification-list'] = 'api/api/get_notification_list';
$route['api/home'] = 'api/api/home';
$route['api/demo-home'] = 'api/api/demo_home';
$route['api/service-details'] = 'api/api/service_details';
$route['api/all-services'] = 'api/api/all_services';
$route['api/category'] = 'api/api/category';
$route['api/subcategory'] = 'api/api/subcategory';
$route['api/generate_otp'] = 'api/api/generate_otp';
$route['api/provider_signin'] = 'api/api/provider_signin';
$route['api/subcategory_services'] = 'api/api/subcategory_services';
$route['api/profile'] = 'api/api/profile';
$route['api/subscription'] = 'api/api/subscription';
$route['api/subscription_success'] = 'api/api/subscription_success';
$route['api/add_service'] = 'api/api/add_service';
$route['api/update_service'] = 'api/api/update_service';
$route['api/delete_service'] = 'api/api/delete_service';
$route['api/update_provider'] = 'api/api/update_provider';
$route['api/my_service'] = 'api/api/my_service';
$route['api/edit_service'] = 'api/api/edit_service';
$route['api/existing_user'] = 'api/api/existing_user';
$route['api/delete_serviceimage'] = 'api/api/delete_serviceimage';
$route['api/add_availability'] = 'api/api/add_availability';
$route['api/update_availability'] = 'api/api/update_availability';
$route['api/availability'] = 'api/api/availability';
$route['api/user_signin'] = 'api/api/user_signin';
$route['api/generate_userotp'] = 'api/api/generate_userotp';
$route['api/logout'] = 'api/api/logout';
$route['api/logout_provider'] = 'api/api/logout_provider';
$route['api/update_user'] = 'api/api/update_user';
$route['api/user_profile'] = 'api/api/user_profile';
$route['api/service_availability'] = 'api/api/service_availability';
$route['api/book_service'] = 'api/api/book_service';
$route['api/search_services'] = 'api/api/search_services';
$route['api/bookingdetail'] = 'api/api/bookingdetail';
$route['api/bookinglist_provider'] = 'api/api/bookinglist_provider';
$route['api/requestlist_provider'] = 'api/api/requestlist_provider';
$route['api/bookinglist_users'] = 'api/api/bookinglist_users';
$route['api/bookingdetail_user'] = 'api/api/bookingdetail_user';
$route['api/views'] = 'api/api/views';
$route['api/update_bookingstatus'] = 'api/api/update_bookingstatus';
$route['api/service_statususer'] = 'api/api/service_statususer';
$route['api/bookinglist'] = 'api/api/bookinglist';
$route['api/get_services_from_subid'] = 'api/api/get_services_from_subid'; #get services belongs to sub category id
$route['api/get_provider_dashboard_infos'] = 'api/api/get_provider_dashboard_infos'; #get provider dashboar infos
$route['api/delete_account'] = 'api/api/delete_account';
$route['api/rate_review'] = 'api/api/rate_review';
$route['api/review_type'] = 'api/api/review_type';
$route['api/update_booking'] = 'api/api/update_booking';
$route['api/generate_otp_provider'] = 'api/api/generate_otp_provider';
$route['api/check_provider_email'] = 'api/api/check_provider_email';
$route['api/check_user_emailid'] = 'api/api/check_user_emailid';
$route['api/forget_password'] = 'api/api/forget_password';
$route['api/userchangepassword'] = 'api/api/userchangepassword';
$route['api/generate_otp_user'] = 'api/api/generate_otp_user';
$route['api/stripe_account_details'] = 'api/api/stripe_account_details';
$route['api/details'] = 'api/api/details';
$route['api/account_details'] = 'api/api/account_details';
$route['api/update-myservice-status'] = 'api/api/update_myservice_status';


$route['api/chat_storage'] = 'api/api/insert_message';
$route['api/get-chat-list'] = 'api/api/get_chat_list';
$route['api/get-chat-history'] = 'api/api/get_chat_history';
$route['api/get-wallet'] = 'api/api/get_wallet_amt';
$route['api/add-user-wallet'] = 'api/api/add_user_wallet';
$route['api/withdraw-provider'] = 'api/api/provider_wallet_withdrawal';
$route['api/customer-card-list'] = 'api/api/get_customer_saved_card';
$route['api/wallet-history'] = 'api/api/wallet_history';
$route['api/stripe_details'] = 'api/api/stripe_details';
$route['api/provider-card-info'] = 'api/api/provider_card_info';
$route['api/countries'] = 'api/api/countries';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Add Multiple Language
$route['language'] = 'admin/language';
$route['add-language'] = 'admin/language/AddLanguages';
$route['insert-language'] = 'admin/language/InsertLanguage';
$route['update_language'] = 'admin/language/update_language_status';
//Add Wep Keywords
$route['wep-language'] = 'admin/language/wep_language';
//$route['add-wep-keyword'] = 'admin/language/AddWepKeyword';
$route['add-wep-keyword/(:any)'] = 'admin/language/AddWepKeyword/$1';
$route['insert_web_keyword'] = 'admin/language/InsertWepKeyword';
$route['update-multi-web-language'] = 'admin/language/update_multi_web_language/';
$route['language-web-list'] = 'admin/language/language_web_list';
//App Keyword
$route['app-page-list/(:any)'] = 'admin/language/AppPageList/$1';
$route['app-page-list/(:any)/(:any)'] = 'admin/language/pages_language/$1/$1';
$route['add-app-keyword/(:any)'] = 'admin/language/AddAPPKeyword/$1';
$route['insert-app-keyword'] = 'admin/language/InsertAppKeyword';
$route['language_list'] = 'admin/language/language_list';
//$route['app-keyword-add'] = 'admin/language/AllAPPKeyword';
$route['insertApp'] = 'admin/language/AppKeyword';
$route['app-keyword-add/(:any)/(:any)'] = 'admin/language/AllAPPKeyword';
$route['exportlang'] = 'admin/language/exportlang';
$route['exportapplang'] = 'admin/language/exporapptlang';

$route['Revenue'] = 'admin/Revenue';
$route['invoice-revenue/(:any)'] = 'admin/Revenue/invoice_revenue/$1';

$route['paypal-braintree'] = 'user/paypal/braintree';


$route['admin/theme-color'] = 'admin/Settings/ThemeColorChange';
$route['Change-color'] = 'admin/Settings/ChangeColor';

//Payouts
$route['admin/add-payouts'] = 'admin/payouts/addPayouts';
$route['admin/payout-requests'] = 'admin/payouts/payoutRequest';
$route['admin/completed-payouts'] = 'admin/payouts/completedPayouts';

//Products User
$route['my-products/(:any)'] = 'user/products/my_products/$1';
$route['add-product/(:any)'] = 'user/products/add_product/$1';
$route['save-my-product'] = 'user/products/save_my_product';
$route['edit-product/(:any)/(:any)'] = 'user/products/edit_product/$1/$2';
$route['products'] = 'user/products/productlist';
$route['cart-list'] = 'user/products/my_cart_list';
$route['delete-cart'] = 'user/products/delete_cart';
$route['checkout/(:any)'] = 'user/products/my_checkout/$1';
$route['order-payment/(:any)'] = 'user/products/order_payment/$1';
$route['order-confirmation/(:any)'] = 'user/products/order_confirmation/$1';
$route['product-details/(:any)'] = 'user/products/view_product_details/$1';
$route['user-orders'] = 'user/products/user_orders';
$route['provider-orders'] = 'user/products/provider_orders';
//
$route['user-invoices'] = 'user/dashboard/user_invoices';
$route['provider-invoices'] = 'user/dashboard/provider_invoices';

$route['admin/other-settings'] = 'admin/dashboard/otherSettings';
$route['admin/chat-settings'] = 'admin/dashboard/chatSettings';
$route['admin/general-settings'] = 'admin/settings/generalSetting';

$route['admin/system-settings'] = 'admin/settings/systemSetting';
$route['admin/social-settings'] = 'admin/settings/socialSetting';
$route['admin/seo-settings'] = 'admin/settings/seoSetting';
$route['admin/theme-color'] = 'admin/Settings/ThemeColorChange';
$route['admin/emailsettings'] = 'admin/settings/emailsettings';
$route['users/add'] = 'admin/dashboard/add_user';
$route['admin/localization'] = 'admin/settings/localization';
$route['admin/pages'] = 'admin/settings/pages';
$route['settings/about-us/(:num)'] = 'admin/settings/aboutus/$1';
$route['settings/cookie-policy/(:num)'] = 'admin/settings/cookie_policy/$1';
$route['settings/faq/(:num)'] = 'admin/settings/faq/$1';
$route['settings/help/(:num)'] = 'admin/settings/help/$1';
$route['settings/privacy-policy/(:num)'] = 'admin/settings/privacy_policy/$1';
$route['settings/terms-service/(:num)'] = 'admin/settings/terms_of_services/$1';
$route['settings/home-page/(:num)'] = 'admin/settings/home_page';
$route['cookie-policy'] = 'user/privacy/cookiesPolicy';
$route['add-provider'] = 'admin/service/add_provider';
$route['admin/frontend-settings'] = 'admin/footer_menu/frontendSettings';
$route['admin/footer-settings'] = 'admin/settings/footerSetting';
$route['admin/currency-settings'] = 'admin/settings/currencySettings';

$route['admin-login'] = 'home/admin_login';

$route['admin/pending-service-list'] = 'admin/service/pendingServiceList';
$route['inactive-service-list'] = 'admin/service/inactive_service_list';
$route['inactive-service-details/(:num)'] = 'admin/service/inactive_service_details/$1';
$route['deleted-service-list'] = 'admin/service/deleted_service_list';
$route['deleted-service-details/(:num)'] = 'admin/service/deleted_service_details/$1';
$route['admin/cache-settings'] = 'admin/settings/cache_settings';
//Service Settings
$route['admin/service-settings'] = 'admin/settings/serviceSettings';
//Sitemap
$route['sitemap\.xml'] = "admin/sitemap/view_map";
$route['earnings'] = 'admin/payments/earnings';
$route['admin/seller-balance'] = 'admin/payments/sellerBalance';
$route['admin/abuse-reports'] = 'admin/settings/abuse_reports';
$route['abuse-details/(:num)'] = 'admin/settings/abuse_details/$1';

$route['admin/pages-list'] = 'admin/settings/pageslist';
$route['admin/add-pages'] = 'admin/settings/add_pages';
$route['admin/edit-pages/(:num)'] = 'admin/settings/edit_pages/$1';
$route['pages-details/(:any)'] = 'user/privacy/pages_details/$1';

$route['admin/offlinepayment'] = 'admin/settings/offline_payment';
$route['admin/offline-payment-details'] = 'admin/settings/offlinepaymentdetails';

$route['admin/add-service'] = 'admin/service/add_service';
$route['service-edit/(:num)'] = 'admin/service/service_edit/$1';
$route['admin/add-provider-session'] = 'admin/settings/add_provider_session';


//Cod Payment
$route['cod-payment'] = 'user/appointment/codPayment';
$route['delete-account'] = 'user/dashboard/delete_account';


//Roles & Permissions
$route['admin/add-roles-permissions'] = 'admin/roles/add_roles_permissions';
$route['admin/edit-roles-permissions/(:any)'] = 'admin/roles/edit_roles_permissions/$1';
$route['admin/delete-role'] = 'admin/roles/deleteRoles';


/* Admin Blogs */
$route['blogs'] = 'admin/blogs';
$route['blogs-pending'] = 'admin/blogs/pending';
$route['add-blog'] = 'admin/blogs/add_blog';
$route['edit-blog/(:num)'] = 'admin/blogs/edit_blog/$1';
$route['blog-details/(:num)'] = 'admin/blogs/blog_details/$1';
$route['blogs/get_blog_categories_by_lang'] = 'admin/blogs/get_blog_categories_by_lang';
$route['admin/blog-comments'] = 'admin/blogs/comments';

/* Blog Categories */
$route['blog-categories'] = 'admin/blog_categories/blog_categories';
$route['add-blog-category'] = 'admin/blog_categories/add_blog_categories';
$route['edit-blog-category/(:num)'] = 'admin/blog_categories/edit_blog_categories/$1';
$route['blog_categories/check_category_name'] = 'admin/blog_categories/check_category_name';
/* User Blogs */
$route['all-blogs'] = 'user/blogs';
$route['user-blog-details/(:any)'] = 'user/blogs/blog_details/$1';
$route['blog-comments'] = 'user/blogs/blogComments';
$route['delete-comments'] = 'user/blogs/deleteComments';
