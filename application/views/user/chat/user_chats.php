<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_chats'])) ? $user_language[$user_selected]['lg_chats'] : $default_language['en']['lg_chats']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<?php if($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer'){ ?>
						<li class="breadcrumb-item"><a href="<?php echo base_url()."provider-bookings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Booking'])) ? $user_language[$user_selected]['lg_Booking'] : $default_language['en']['lg_Booking']; ?></a></li>
						<?php } else { ?>
						<li class="breadcrumb-item"><a href="<?php echo base_url()."user-bookings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Booking'])) ? $user_language[$user_selected]['lg_Booking'] : $default_language['en']['lg_Booking']; ?></a></li>
						<?php } ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_chats'])) ? $user_language[$user_selected]['lg_chats'] : $default_language['en']['lg_chats']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
	<div class="content chat-history-blk">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-12">
        <div class="pbox">
            <div class="row justify-content-center">
                <div class="col-md-4 col-xl-4 chat d-flex">
				<div class="card mb-sm-3 mb-md-0 contacts_card flex-fill chat-scroll">
                    <div class="card-header chat-search">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="search_btn"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" placeholder="Search" name="search_chat_list" id="search_chat_list" class="form-control search-chat ">
                        </div>
                    </div>
                    <div class="card-body contacts_body">
                            <div class="">
                            <ul role="tablist" class="left_message contacts">
                        <?php 
                        foreach ($chat_list as $key => $value) {
                            if(!empty($value['profile_img'])){
                                $path=base_url().$value['profile_img'];
                            }else{
                                $path=base_url().'assets/img/user.jpg';
                            }
                            $class_names='badge_count'.$value['token'];
                            if($value['badge']!=0){
                                $badge="<span  class='position-absolute badge badge-primary '>".$value['badge']."</span>";
                            }else{
                                $badge="<span  class='position-absolute badge badge-primary '></span>";

                            }
                         ?>

                                    <li class="active history_append_fun" data-token="<?php echo $value['token'];?>" > <a href="javascript:void(0);">
                                    <div class="d-flex bd-highlight">
                                    <div class="img_cont"><?php echo $badge;?>

                                    <img src="<?php echo $path;?>" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                    <span class="user-name"><?php echo $value['name'];?></span><span class="float-right text-muted"></span>
                                    </div>
                                    </div></a>
                                    </li>

                        <?php } ?>
                     
                  
                        </ul>
                        </div>
                 


                    </div>
                    <div class="card-footer"></div>
                </div></div>
                       
                <div class="col-md-8 col-xl-8 chat d-flex chat-scroll">

                    <div class="card flex-fill mb-0 justify-content-center align-items-center" id="home_page">
					
						<div class="no-messages">
							<i class="far fa-comments"></i>
						</div>
                    </div>
                
<!-- chat history -->
                    <div class="card w-100 mb-0" id="history_page">
                        <div class="card-header msg_head">
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



                        <div class="card-footer">
                          
                           
                                <input type="hidden" name="chat-seft" id="fromToken" placeholder="" value="" class=""  />
                                <input type="hidden" name="toToken" value="" id="toToken" placeholder="" class=""  />
                                <input type="hidden" name="from_name" value="" id="from_name">
                                <input type="hidden" name="to_name" value="" id="to_name">


                            <div class="input-group">
                                <input name="" class="form-control type_msg mh-auto empty_check" id="chat-message" placeholder="Type your message..." maxlength="1000"></input>
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
         </div>
      </div>
   </div>
<input type="hidden" name="user_address">
<input type="hidden" id="self_token" value="<?php echo $this->session->userdata('chat_token');?>">
<input type="hidden" id="server_name" value="<?php echo $server_name.':'.$port_no;?>">
<input type="hidden" id="img" value="<?php echo  base_url('assets/img/loader.gif');?>">

