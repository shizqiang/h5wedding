<?php
define("TOKEN", "zhubyu");
$wechatObj = new WeChatTools();
$wechatObj->valid();

class WeChatTools {

	public function valid() {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
        	echo $echoStr;
            $this->responseMsg();
        	exit;
        } else {
            header("HTTP/1.1 404 Not Found");  
            header("Status: 404 Not Found");  
            exit;  
        }
    }

    public function responseMsg() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		if (!empty($postStr)) {
            libxml_disable_entity_loader(true);
          	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $time = time();
            $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>"; 
            $keyword = trim($postObj->Content);           
			if (!empty($keyword)) {
          		$msgType = "text";
            	$contentStr = "http://shizqiang.com/";
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	echo $resultStr;
            } else {
            	echo "Input something...";
            }

        } else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature() {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        if (!isset($_GET["signature"]) || !isset($_GET["timestamp"]) || !isset($_GET["nonce"])) {
            include '404.html';
            exit;
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		
		if ($tmpStr === $signature){
			return true;
		} else {
			return false;
		}
	}
}