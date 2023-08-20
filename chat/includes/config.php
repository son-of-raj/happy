<?php
define('BASEPATH', '');
include_once '../application/config/database.php';
require 'includes/classes/Databasechat.php';
date_default_timezone_set('Asia/Kolkata');

define('ENABLE_DATABASE', true);
define('DATABASE_HOST', $db['default']['hostname']);
define('DATABASE_USERNAME', $db['default']['username']);
define('DATABASE_PASSWORD', $db['default']['password']);
define('DATABASE_DB', $db['default']['database']);

define('DBENVIRONMENT1', $db['default']['DBENVIRONMENT']);
define('WS', $db['default']['WS']);
define('CRT_PATH', $db['default']['CRT_PATH']);
define('KEY_PATH', $db['default']['KEY_PATH']);
define('CA_PATH', $db['default']['CA_PATH']);
define('IF_SSL', $db['default']['IF_SSL']);

if (ENABLE_DATABASE == true) {
    $db = new Databasechat(
        DATABASE_USERNAME,
        DATABASE_PASSWORD,
        DATABASE_HOST,
        DATABASE_DB
    );
} else {
    $db = null;
}
$port = $db->fetchsettings();
$ip = $db->getsocketip();
$socket_data = $db->getsocketdetails();
$server_port = $port['value'];
$server_ip = $ip['value'];
if($socket_data['value'] == 1) {
    $server_port = $port['value'];
    $server_ip = $ip['value'];
} else {
    $server_ip = '127.0.0.1';
    $server_port = '8443';
}

define('WEBSOCKET_SERVER_IP', $server_ip);
define('WEBSOCKET_SERVER_PORT', $server_port);

