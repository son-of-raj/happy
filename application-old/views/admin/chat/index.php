<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Chat List</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-sm-3 mb-md-0 contacts_card flex-fill">

                    <div class="card-header">
                        <form class="chat-search">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="fa fa-search"></i>
                                </div>
                                <input type="text" placeholder="Search Provider" name="search_chat_list" id="search_chat_list" class="form-control search-chat ">
                            </div>
                        </form>
                       
                    </div>

                    <div class="card-body contacts_body  chat-scroll">
                        <div class="">
                            <ul role="tablist" class="left_message contacts">
                                <?php
                                // var_dump(sizeOf($chat_list));
                                foreach ($chat_list as $key => $value) {
                                    if (file_exists($value['profile_img'])) {
                                        $path = base_url() . $value['profile_img'];
                                    } else {
                                        $path = base_url() . 'assets/img/user.jpg';
                                    }
                                    $class_names = 'badge_count' . $value['token'];
                                    if ($value['badge'] != 0) {
                                        $badge = "<span  class='position-absolute badge badge-primary '>" . $value['badge'] . "</span>";
                                    } else {
                                        $badge = "<span  class='position-absolute badge badge-primary '></span>";
                                    }
									$act = '';
									if($key == 0) $act = 'active';
                                    ?>

                                    <li class="<?php echo $act; ?> history_append_fun1 history_append" data-token="<?php echo  $value['token']; ?>" >
                                        <a href="javascript:void(0);">
                                            <div class="d-flex bd-highlight">
                                                <div class="img_cont"><?php echo  $badge; ?>
                                                    <img src="<?php echo  $path; ?>" class="rounded-circle user_img">
                                                </div>
                                                <div class="user_info">
                                                    <span class="user-name"><?php echo  $value['name']; ?></span><span class="float-end text-muted"></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
							
							
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="col-md-3">
                <div class="card mb-sm-3 mb-md-0 contacts_card flex-fill">

                    <div class="card-header">
                        <form class="chat-search">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="fa fa-search"></i>
                                </div>
                                <input type="text" placeholder="Search User" name="search_userchat_list" id="search_userchat_list" class="form-control search-userchat ">
                            </div>
                        </form>                        
                    </div>

                    <div class="card-body contacts_body  chat-scroll">
                        <div class="">
                            <ul role="tablist" class="user_left_message contacts">
                            <?php
                                // var_dump(sizeOf($chat_list)); user_chat _list
                                foreach ($chat_list_user as $key => $value) {
                                    if (file_exists($value['profile_img'])) {
                                        $path = base_url() . $value['profile_img'];
                                    } else {
                                        $path = base_url() . 'assets/img/user.jpg';
                                    }
                                    $class_names = 'badge_count' . $value['token'];
                                    if ($value['badge'] != 0) {
                                        $badge = "<span  class='position-absolute badge badge-primary '>" . $value['badge'] . "</span>";
                                    } else {
                                        $badge = "<span  class='position-absolute badge badge-primary '></span>";
                                    }
									$act = '';
									if($key == 0) $act = 'active';
                                    ?>

                                    <li class="<?php echo $act; ?> history_append_fun1 history_append" data-token="<?php echo  $value['token']; ?>" >
                                        <a href="javascript:void(0);">
                                            <div class="d-flex bd-highlight">
                                                <div class="img_cont"><?php echo  $badge; ?>
                                                    <img src="<?php echo  $path; ?>" class="rounded-circle user_img">
                                                </div>
                                                <div class="user_info">
                                                    <span class="user-name"><?php echo  $value['name']; ?></span><span class="float-end text-muted"></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            
                            </ul>							
							
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 chat d-flex chat-scroll">
                <div class="card flex-fill mb-0 justify-content-center align-items-center" id="home_page">
                    <div class="no-messages">
                        <i class="far fa-comments"></i>
                    </div>
                </div>

                <div class="card w-100 mb-0" id="history_page">
                    <div class="card-header msg_head d-none">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont">
                                <img id="receiver_image" src="" class="rounded-circle user_img">
                            </div>
                            <div class="user_info">
                                <span><strong id="receiver_name"></strong></span>
                                <p class="mb-0"><?php echo (!empty($user_language[$user_selected]['lg_messages'])) ? $user_language[$user_selected]['lg_messages'] : $default_language['en']['lg_messages']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body msg_card_body" id="chat_box">
                        <div id="load_div" class="text-center"></div>
                    </div>
                    
                    <!-- <div class="card-footer d-none"> -->
                    <div class="card-footer">
                        <input type="hidden" name="chat-seft" id="fromToken" placeholder="" value="" class=""  />
                        <input type="hidden" name="toToken" value="" id="toToken" placeholder="" class=""  />
                        <input type="hidden" name="from_name" value="" id="from_name">
                        <input type="hidden" name="to_name" value="" id="to_name">
                        <div class="input-group">
                            <input name="" class="form-control type_msg mh-auto empty_check" id="chat-message" placeholder="Type your message..." maxlength="1000">
                            <div class="input-group-append">
                                <button id="submit"  class="btn btn-primary btn_send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<input type="hidden" name="user_address">
<input type="hidden" id="self_token" value="0dreamsadmin">
<input type="hidden" id="server_name" value="<?php echo $server_name . ':' . $port_no; ?>">
<input type="hidden" id="img" value="<?php echo  base_url('assets/img/loader.gif'); ?>">
<!--Dont know-->
</div>
</div>
</div>
<style type="text/css">
    .chat-scroll {
        max-height: calc(100vh - 255px);
        overflow-y: auto;
    }
    .contacts_body {
        padding-top:0;
    }
    .msg_card_body{
        overflow-y: auto;
    }
    .chat-window .chat-cont-left .chat-search {
        background-color: #f5f5f6;
        border-bottom: 1px solid #e5e5e5;
        padding: 10px 15px;
        width: 100%;
    }
    .chat-search .input-group {
        width: 100%;
    }
    .chat-search .input-group .form-control {
        background-color: #fff;
        border-radius: 50px;
        padding-left: 36px;
    }
    .chat-search .input-group .form-control:focus {
        border-color: #ccc;
        box-shadow: none;
    }
    .chat-search .input-group .input-group-prepend {
        align-items: center;
        bottom: 0;
        color: #666;
        display: flex;
        left: 15px;
        pointer-events: none;
        position: absolute;
        top: 0;
        z-index: 4;
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border-bottom: 0 !important;
    }
    .card-footer {
        background-color:#fff;
        border-radius: 0 0 15px 15px !important;
        border-top: 0 !important;
    }
    .search {
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0,0,0,0.1) !important;
        border:0 !important;
        color:fff !important;
    }
    .search:focus {
        box-shadow:none !important;
        outline:0px !important;
    }
    .type_msg {
        background-color: rgba(0,0,0,0.1) !important;
        border:0 !important;
        overflow-y: auto;
    }
    .type_msg:focus {
        box-shadow:none !important;
        outline:0px !important;
    }
    .attach_btn {
        border-radius: 15px 0 0 15px !important;
        background-color: rgba(0,0,0,0.1) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;
    }
    .send_btn {
        border-radius: 0 15px 15px 0 !important;
        background-color: rgba(0,0,0,0.1) !important;
        border:0 !important;
        color: white !important;
        cursor: pointer;
    }
    .search_btn {
        background-color: transparent;
        border:0 !important;
    }
    .contacts {
        list-style: none;
        padding: 0;
    }
    .contacts li {
        margin-bottom: 15px;
    }
    .user_img {
        height: 45px;
        width: 45px;
        border:1.5px solid #f5f6fa;

    }
    .user_img_msg {
        height: 40px;
        width: 40px;
        border:1.5px solid #f5f6fa;

    }
    .img_cont {
        position: relative;
        height: 45px;
        width: 45px;
    }
    .img_cont_msg {
        height: 40px;
        width: 40px;
    }
    .online_icon {
        position: absolute;
        height: 15px;
        width:15px;
        background-color: #4cd137;
        border-radius: 50%;
        bottom: 0.2em;
        right: 0.4em;
        border:1.5px solid white;
    }
    .offline{
        background-color: #c23616 !important;
    }
    .user_info {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 15px;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .user_info .user-name {
        color: #2c3038;
    }
    .user_info span {
        font-size: 16px;
    }
    .user_info p {
        font-size: 14px;
        color: #8d8d8d;
    }
    .msg_cotainer {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 5px;
        background-color: #e4e4e4;
        padding: 10px;
        position: relative;
    }
    .msg_cotainer_send {
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 5px;
        background-color: #0400ff;
        color: #fff;
        padding: 10px;
        position: relative;
    }
    .msg_time {
		color:#1e90ff;
        font-size: 10px;
        position: relative;
        display:block;
    }
    .msg_time_send {
        position: relative;
        color:rgba(230, 230, 230, 0.8);
        font-size: 10px;
        display: block;
    }
    .msg_head {
        position: relative;
    }
	.left_message li.active, .user_left_message  li.active {
		background-color: #e19278;
		border-radius: 5px;
		padding: 5px;
	}
</style>