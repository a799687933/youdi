<?php
// 本类由系统自动生成，仅供测试用途
class SinaBasicAction extends BasicAction {
	private $sina;
	private $webKey='2838185523'; //  App Key
	private $webSkey='110cc28372fdef1383f6a5c27d83633d'; //  App Secret:
	private $callbackUrl;  //回调地址
	public function __construct(){
	   parent::__construct();
	   $this->callbackUrl="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/'.is_m()."/Sina/callback.html";	//回调地址
	   require(APP_PATH.'Plugins/Sina/saetv2.ex.class.php');
	   $this->sina = new SaeTOAuthV2( $this->webKey , $this->webSkey );
	}
	
	//自动跳到新浪授权页面
    protected function basiclogin(){	    
        $codeUrl = $this->sina->getAuthorizeURL( $this->callbackUrl );
		header('location:'.$codeUrl);
    }
	
	//登录成功与失败回调方法
	protected function basicCallback(){
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->callbackUrl;
			try {
				$token = $this->sina->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}	 
		if($token){ //验证成功
		   $sae = new SaeTClientV2( $this->webKey , $this->webSkey,$token['access_token']);//获取用户信息
		   $sinaUser=$sae->show_user_by_id($token['uid']); //用户总信息p($sinaUser);
		   $sinaArr['type']='新浪微博登录帐号';
		   $sinaArr['name']=$sinaUser['screen_name'] ? $sinaUser['screen_name'] :  $sinaUser['name']; //新浪用户名
		   $sinaArr['pic_a']=$sinaUser['profile_image_url']; //新浪头像
		   $field=array('id','user_name','mobile_phone','nickname','head_pic','rank_points','login_count','is_lock','temp_login_count','temp_login_time','last_time');
		    if($dbs=M('member')->field($field)->where(array('sina_key'=>$token['access_token']))->find()){
			   $times=time();
			   $ip=get_ip();
			   $ge=new CommonModel();
			   $grade=$ge->getGrade($dbs['rank_points']);
			   $logins[C('SE_USER_GRADR_ID')]=$grade['id'];//会员等级ID
			   $logins[C('SE_USER_GRADR_NAME')]=$grade['grade_name'];//会员等级名称
			   $logins[C('SE_USER_ID')]=$dbs['id'];//会员名称
			   $logins[C('SE_USER_NAME')]=$sinaArr['name'] ? $sinaArr['name'] : $dbs['user_name'];//用户帐号
			   $logins[C('SE_USER_HEAD_PIC')]=$sinaArr['pic_a'] ? $sinaArr['pic_a'] : $dbs['head_pic']; //用户默认头像
			   $logins[C('SE_USER_IP')]=$ip; //用户IP 
			   $logins[C('SE_USER_LAST_TIME')]=$dbs['last_time']; //用户上次登录时间
			   $logins[C('SE_USER_LIGON_TIEM')]=$times; //用户登录时间		
			   set_login($logins,true,0);//写SESSION
			  //更新用户信息
			  $data['login_count']=$dbUser['login_count']+1;
			  $data['last_time']=$times;
			  $data['last_ip']=$ip;
			  M('member')->where(array('id'=>$dbUser['id']))->save($data);			
			  if(is_mobile('')) header('location:'.__ROOT__.'/'.APP_MOBILE);  	    
			  else header('location:'.__ROOT__.'/');  
			}else{
				 $_SESSION['SINA_KEY']=$token['access_token']; //QQKEY值
				 $this->biotinge=$sinaArr;
				 $this->display('ThreeLogin/index');
			}	    
		}else{		
		    die('Error!');
		}
	}

}