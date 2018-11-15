<?php
/**
* 	配置账号信息
*/
define('WX_URI','http://'.$_SERVER['HTTP_HOST'].'/Weixin/index');
define('WX_ST_PAHT', 'http://'.$_SERVER['HTTP_HOST'].'/App/Plugins/Payment/WxPayPubHelper/cacert/apiclient_cert.pem');
define('WX_SY_PAHT', 'http://'.$_SERVER['HTTP_HOST'].'/App/Plugins/Payment/WxPayPubHelper/cacert/apiclient_key.pem');
define('WX_NOTIFY', 'http://'.$_SERVER['HTTP_HOST'].'/Weixin/notifyUrl');
class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = '4444444444';
	//受理商ID，身份标识
	const MCHID =  '220154854644444';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'kacs4kls5lj0sdlf35aldk4';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = 'p4a3kc5jf2la7kdj9lh0gjdl785';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = WX_URI;

	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = WX_ST_PAHT;
	const SSLKEY_PATH = WX_SY_PAHT;
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = WX_NOTIFY;

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>