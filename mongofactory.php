<?php
final class mongofactory {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance($url, $db_name) {
		if (self::$instance) {
			return self::$instance;
		}
		$mongo = new MongoClient($url);
		self::$instance = $mongo->selectDB($db_name);
		return self::$instance;
	}
}