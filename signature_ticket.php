<?php
$jsapiTicket = $_GET['jsapi_ticket'];
$timestamp = time();
$nonceStr = 'zhubyu';
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
	$_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = 'http://shizqiang.com/we_scan.html'; // "$protocol$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]";
$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
$signature = sha1($string);
$signPackage = array(
	"nonceStr"  => $nonceStr,
	"timestamp" => $timestamp,
	"url"       => $url,
	"signature" => $signature,
	"rawString" => $string
);
echo json_encode($signPackage);
