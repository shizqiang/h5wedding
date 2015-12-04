<?php
error_reporting(E_ALL);

set_time_limit(0);
$port = 9696;
$address = '127.0.0.1';
$address = '121.40.33.135';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
	die(socket_strerror($socket));
} else {
	echo 'Socket create successful.';
	echo "\n\r";
}

$connection = socket_connect($socket, $address, $port);
if ($connection < 0) {
	die(socket_strerror($socket));
} else {
	echo 'Connect to 9696 successful.';
	echo "\n\r";
}

$data = isset($_REQUEST['code']) ? $_REQUEST['code'] : 'no data';
// $data = file_get_contents('data.xml');
if (!socket_write($socket, $data."\r\n")) {
	die('Write messge faild.');
} else {
	echo 'Send message successful!';
	echo "\n\r";
}

$receive = socket_read($socket, 1024 * 4, PHP_NORMAL_READ);
echo 'Message from server is: '.$receive;
echo "\n\r";


socket_close($socket);
