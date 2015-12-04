<?php
require 'mongofactory.php';

$url = 'mongodb://127.0.0.1:27017';
$db_name = 'test';
$db = mongofactory::getInstance($url, $db_name);

$activities_corsor = $db->activities->find()->skip(0);

while ($activities_corsor->hasNext()) {
	$acts[] = $activities_corsor->next();
}

header("Access-Control-Allow-Origin: *");
echo json_encode($acts);