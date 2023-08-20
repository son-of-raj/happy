<?php
require 'vendor/autoload.php';
require 'includes/config.php';
require 'includes/classes/Database.php';
require 'includes/classes/Chat.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

if (ENABLE_DATABASE == true) {
    $db = new Database(
        DATABASE_USERNAME,
        DATABASE_PASSWORD,
        DATABASE_HOST,
        DATABASE_DB
    );
} else {
    $db = null;
}

if (DBENVIRONMENT1 == 'local') {    
    $server = IoServer::factory(new HttpServer(new WsServer(new Chat($db))),WEBSOCKET_SERVER_PORT,WEBSOCKET_SERVER_IP);
    echo "Server running at ".WEBSOCKET_SERVER_IP.":".WEBSOCKET_SERVER_PORT."\n";
    $server->run();
}
else{
    $ssl = [
        'local_cert' => CRT_PATH, 
        'local_pk'=> KEY_PATH, 
        'local_ca'=> CA_PATH,
        'allow_self_signed' => true, 
        'verify_peer' => false
    ];
    $app = new \Ratchet\Http\HttpServer(
    new \Ratchet\WebSocket\WsServer(
        new Chat($db)
        )
    );
    $loop = \React\EventLoop\Factory::create();
    $webSock = new \React\Socket\Server('0.0.0.0'.':'.'8443', $loop);
    $webSock = new \React\Socket\SecureServer($webSock, $loop, $ssl);
    $webSock = new \Ratchet\Server\IoServer($app, $webSock, $loop);
    echo "Server running at "."0.0.0.0".":"."8443"."\n";
    $webSock->run();
}

