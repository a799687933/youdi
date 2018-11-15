<?php
// 本类由系统自动生成，仅供测试用途
class QQBasicAction extends BasicAction {
	private $app_id = '101295754'; // 你的APP ID
	private $app_secret = 'b7a12a98e86fd8902ac95263280091cf'; //你的APP KEY
	private $redirect;//你的回调地址
	private $QQ;
	public function __construct(){
	   parent::__construct();
	  // $this->redirect='http://'.$_SERVER["HTTP_HOST"].__ROOT__.'/'.is_m().'/QQ/callback';
	   $this->redirect='http://'.$_SERVER["HTTP_HOST"].'/QQ/callback';
	  require(APP_PATH.'Plugins/QQ/QQSDK.class.php');
	   $QQ= new QQSDK();
	   $QQ->app_id=$this->app_id;
	   $QQ->app_secret=$this->app_secret;
	   $QQ->redirect=$this->redirect;
	   $this->QQ=$QQ;
	}
	
	//自动跳到QQ登录
    protected function basiclogin(){
	   header('location:https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$this->app_id.'&redirect_uri='.$this->redirect);
    }
	
	//登录成功与失败回调方法
	protected function basicCallback(){
		   $this->display('ThreeLogin/index');	die;
	      $token = $this->QQ->get_access_token($_GET['code']); 
		  $arrKey=array_keys($token,0);
		  if(!$token['access_token']) die($arrKey[0]); 
		  $open_id = $this->QQ->get_open_id($token['access_token']); 
		  if($open_id['error']) die($open_id['error']); 
		  $user = $this->QQ->get_user_info($token['access_token'], $open_id['openid']); 
		  if($user['ret']==-1) die('获取用户信息失败？'); 
		  $userKey=$open_id['openid']; //邦定数据库的KEY值
		  $qq['type']='QQ登录帐号'; //类型
		  $qq['name']=$user['nickname']; //用户昵称
		  $qq['pic_m']=$user['figureurl_qq_1']; //用户小头像
		  $qq['pic_a']=$user['figureurl_qq_2']; //用户大头像
		 
		  //是否已邦定用户
		  $field=array('id','user_name','mobile_phone','nickname','head_pic','rank_points','login_count','is_lock','temp_login_count','temp_login_time','last_time');
		  if($dbUser=M('member')->field($field)->where(array('qq_key'=>$userKey))->find()){
			   if($dbUser['is_lock']) die('用户被锁定');
			   $times=time();
			   $ip=get_ip();
			   $ge=new CommonModel();
			   $grade=$ge->getGrade($dbUser['rank_points']);
			   $logins[C('SE_USER_GRADR_ID')]=$grade['id'];//会员等级ID
			   $logins[C('SE_USER_GRADR_NAME')]=$grade['grade_name'];//会员等级名称
			   $logins[C('SE_USER_ID')]=$dbUser['id'];//会员名称
			   $logins[C('SE_USER_NAME')]=$qq['name'] ? $qq['name'] : $dbUser['user_name'];//用户帐号
			   $logins[C('SE_USER_HEAD_PIC')]=$qq['pic_a'] ? $qq['pic_a'] : $dbUser['head_pic']; //用户默认头像
			   $logins[C('SE_USER_IP')]=$ip; //用户IP 
			   $logins[C('SE_USER_LAST_TIME')]=$dbUser['last_time']; //用户上次登录时间
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
		        $_SESSION['QQ_KEY']=$userKey; //QQKEY值
			    $this->biotinge=$qq;
			    $this->display('ThreeLogin/index');		  
		  }
	}

}