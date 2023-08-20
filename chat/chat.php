<script type="text/javascript">
    let socketHost = 'localhost';
    let socketPort = '8080';
    let WS = 'wss';
    <?php 
        $user['id'] = '1';
        $user['name'] = 'Admin';
    ?>
    let chat_user  = JSON.parse('<?php print addslashes(json_encode($user)); ?>');
</script>
<script type="text/javascript" src="assets/js/websocket.js"></script>
