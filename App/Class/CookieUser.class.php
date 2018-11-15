<?php
header('Content-Type:text/html;charset=utf-8');
/**
*用户登录信息写cookie
*/
class CookieUser{
	 //客户端签名加密串KEY
	 private   $appKey='e0eb5d5eBf09cb70cSd3e03f9Tab83c1Pb';
	 //登录信息是否邦定IP
	 public     $isBiotinge=true;
	 //COOKIE保存时间单秒，默认浏览器关闭时退出
	 public    $expires = 0;
	 
	 /*构造*/
     public function __construct(){
		// ini_set( 'error_reporting', E_ALL ^ E_NOTICE );
	 }
	 
	 //登录写COOKIE
	 public function login($data){
		 if(!is_array($data)) return false;		
		 if($this->expires > 0) $times=time() + $this->expires;
		 else $times=0;		   
	     $data=base64_encode(json_encode($data));
		 $sign=md5($data.$this->_getIp().$this->appKey);
		 setcookie('app_key',$sign,$times,'/'); 
		 setcookie('user_data',$data,$times,'/'); 
		 return true;
	 }
	 
	 //获取COOKIE
	 public function getUser($field=''){
		if(empty($_COOKIE['user_data']) || empty($_COOKIE['app_key'])) return false; 
	    if(md5($_COOKIE['user_data'].$this->_getIp().$this->appKey) !=$_COOKIE['app_key']) return false;
		$data =json_decode(base64_decode($_COOKIE['user_data']),true);
		if($field){
			return $data[$field];
		}else{
			return $data;
	    }
	 }
	 
	 //退出
	 public function signOut(){
		setcookie('app_key',null,time() - 1,'/'); 
		setcookie('user_data',null,time() - 1,'/'); 	 
	 }

	/**
	 * 获得客户端的真实IP地址
	 * @access  public
	 * @return  string
	 */
	private function _getIp(){
		 return '0.0.0.0';//不邦定IP
		//登录不邦定IP
		if(!$this->isBiotinge) return '0.0.0.0';
		static $realip = NULL;
		if ($realip !== NULL) {
			return $realip;
		}
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				foreach ($arr AS $ip) {
					$ip = trim($ip);
					if ($ip != 'unknown') {
						$realip = $ip;
						break;
					}
				}
			}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			}else {
				if (isset($_SERVER['REMOTE_ADDR'])){
					$realip = $_SERVER['REMOTE_ADDR'];
				} else {
					$realip = '0.0.0.0';
				}
			}
		}else{
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			}elseif (getenv('HTTP_CLIENT_IP')){
				$realip = getenv('HTTP_CLIENT_IP');
			}else{
				$realip = getenv('REMOTE_ADDR');
			}
		}
		preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
		$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
		return $realip;
	}
}

/*$user=new CookieUser();
if($_GET['action']=='login'){ //登录
    $data['user_id']=13;
	$data['user_name']='黎剑光';
	$data['user_pic']='Uploads/Images/12354562.png';
	$data['user_ip']='136.2.3.8';
	$data['last_time']='2016:1:12 12:00:00'; //上次登录时间
	$data['login_tiem']='2016:2:14 10:00:00'; //当次登录时间
	$data['user_grade_id']=20; //会员等级ID
	$data['user_grade_name']='高级会员'; //会员等级名称
    $user->login($data);
}else if($_GET['action']=='getUser'){ //获取Cookie数据
   print_r($user->getUser(''));
}else if($_GET['action']=='signOut'){ //退出
   $user->signOut();
}*/

?>
