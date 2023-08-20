<?php
require '../vendor/autoload.php';
require '../includes/config.php';
session_start();
if(!isset($_SESSION['user'])){
    $_SESSION['user'] = '42';
}
$user['username'] = $_SESSION['user'];
$user['id'] = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Websocket chat example using jquery + php + mysql">
    <meta name="author" content="Johnny Mast">
    <title>Websocket Chat</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Our litle custom theme -->
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">
        /**
         * You might want to configure this to your
         * settings. The settings can be found in includes/config.php
         *
         * @type {string}
         */
        let socketHost = '<?php print WEBSOCKET_SERVER_IP ?>';
        let socketPort = '<?php print WEBSOCKET_SERVER_PORT ?>';
        let WS = '<?php print WS ?>';

        /**
         * Also when your script is live make sure this user object
         * doest not show to much information. Like for example passwords
         * should be excluded. Add only the information you need on the server
         * for this user.
         */
        let chat_user  = JSON.parse('<?php print addslashes(json_encode($user)); ?>');

    </script>
</head>
<body>
<div class="container">

    <div class="starter-template">
        <div class="chat_dialog1">
            <div class="chat_dialog"></div>
            <ul class="typing_indicator"></ul>
        </div>
        <select class="user_list" multiple></select>
        <div class="clear">&nbsp;</div>
        <div class="alert alert-danger connection_alert" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Currently there is no connection to the server. <br />
            <span class="error_type"></span>
            <span class="error_reconnect_msg">reconnecting in</span>
            <span class="error_reconnect_countdown">10</span>
        </div>

        <div class="input-group client">
            <span class="input-group-addon name_bit" id="basic-addon3">
                <span class="client_user_you"><?php print $user['username']; ?></span>
                &nbsp;&gt;&gt;&nbsp;<span class="name_bit chat_target"></span>
            </span>

            <input type="text" class="form-control client_chat"  placeholder="Type your message...">
            <span class="input-group-btn">
                <button class="btn btn-default btn-send chat_btn" type="button">Go!</button>
            </span>
        </div><!--/input-group -->
    </div>
    
</div><!-- /.container -->

<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="js/dom.js"></script>
<script type="text/javascript" src="js/websockets.js"></script>
<script type="text/javascript" src="js/interface.js"></script>
<script type="text/javascript">
    setCookie('from_user',chat_user['id'], 3);
</script>
</body>
</html>

