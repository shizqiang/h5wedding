<?php
require 'mongofactory.php';

$url = 'mongodb://114.113.155.22:27017';
$db_name = 'shizqdb';
$db = mongofactory::getInstance($url, $db_name);

$request_type = $_GET['type'];

$appid = 'wx40c3a549a1f9df06';
$secret = '2b844fcf66c0e6bdfd608381f8f4c779';

if ($request_type === 'token') {
	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
	$req = 'token';
} else {
	$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $_GET['token'] . '&type=jsapi';
	$req = 'ticket';
}

if (check_timeout($req)) {
	echo get_from_server($url, $req);
} else {
	echo get_from_cache($req);
}


function check_timeout($req) {
	global $db;
	$time = time();
	$res = $db->wechat->find(array('tag'=>$req));
	if ($res->hasNext()) {
		$res_info = $res->next();
		$spend = $time - $res_info['save_time'];
		if ($spend > 7000) {
			// 过期
			return true;
		} else {
			return false;
		}
	} else {
		// 第一次
		return true;
	}
}

function save_item($data, $req) {
	global $db;
	// 更新一个文档，如果指定的文档不存在，则插入新的
	$db->wechat->update(array('tag'=>$req), array('$set'=>array('save_time'=>time(), 'val'=>$data)), array('upsert'=>true));
}

function get_from_cache($req) {
	global $db;
	$res = $db->wechat->find(array('tag'=>$req));
	$res_info = $res->next();
	return $res_info['val'];
}

function get_from_server($url, $req) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$output = curl_exec($ch);
	curl_close($ch);
	save_item($output, $req);
	return $output;
}