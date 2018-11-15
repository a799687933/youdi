<?php
//公共配置文件
require_once("config.php");	
/***WAP板回调URL配置***************/

// 前台通知地址 (商户自行配置通知地址)
define('WAP_FRONT_RECEIVE','http://'.$_SERVER['HTTP_HOST'].'/Mobile/MobilePay/frontReceive.html');
const SDK_FRONT_NOTIFY_URL=WAP_FRONT_RECEIVE;

// 后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
define('WAP_BACK_RECEIVE','http://'.$_SERVER['HTTP_HOST'].'/Mobile/MobilePay/backReceive.html');
const SDK_BACK_NOTIFY_URL=WAP_BACK_RECEIVE;

?>