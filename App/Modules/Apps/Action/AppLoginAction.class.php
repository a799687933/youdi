<?php
 Class AppLoginAction extends Action{
 	
	public function _initialize(){
		//p(pwd_sha1(123456));
 		if(C('TOKEN_ON')){
 			if($_POST){
				$CheckToken=M();
		        if(!$CheckToken->autoCheckToken($_POST)) {
                    return_json(300,'Error！');   
		        }
			}
		}   			
	}
     
     //登录界面
     public function index(){
       //p(pwd_sha1(123456));
         $this->display();
     }
     
     //验证码
	 public function verify(){
		 //验证码Session值$_SESSION['verify'];
		 import('ORG.Util.Image');
		 Image::buildImageVerify(4,1,'png');
	 }
	 
	 //比较验证码
	 public function usVerify(){
	     if($_SESSION['verify']==md5($_GET['code'])){
		    die('1');
		 }else{
		    die('0');
		 }
	 }
     
     //普通登录Ajax登录
     public function loginDo(){
         if(empty($_POST['account'])) return_json(300,'请输入登录用户名！');
         if(empty($_POST['password'])) return_json(300,'请输入登录密码！');         
         $userName= I('account');
         $pwd=I('password');
         $user=M('user')->where(array('username'=>$userName))->find();
         if(!$user || !compare_sha1($pwd,$user['sha1_random'],$user['password'])){
                return_json(300,'用户名或密码错误！');
          }else{
              if($user['lock']) {
                     return_json(300,'用户被锁定！');                    
               }
         }

           //更新登录时间和IP
          $data=array(
                'id'=>$user['id'],    
                 'logintime'=>time(),
                 'loginip'=>get_client_ip()//获取IP
            );
             M('user')->save($data);//更新
             session(C('USER_AUTH_KEY'),$user['id']); //用户ID
             session(C('SESSION_NAME_VAL'),$user['username']);
             session(C('SESSION_TIME_VAL'),$user['logintime']);
             session(C('SESSION_LOGINIP_VAL'),$user['loginip']);
             
             //如果是超级程序员
             if($user['username']==C('RBAC_SUPERADMIN')){
                 session(C('ADMIN_AUTH_KEY'),true);
             }else{
				 //获取管理员等级信息
				 $sql="SELECT r.name,r.remark FROM ".PREFIX."role AS r JOIN ".PREFIX."role_user AS r_u ON(r_u.role_id=r.id) WHERE r_u.user_id='".$user['id']."' ";
				 $grade=M()->query($sql);
				 $_SESSION['grade']=$grade;	
				 //写入频道权限
				// if($user['username'] != C('ADMIN_KEY')){
						$filesJurisdiction=M('files_jurisdiction')->field('files_id,groups')->where(array('user_id'=>$user['id']))->select();
						$temp=array();
						foreach($filesJurisdiction as $k=>$v){
							$temp[$v['files_id']]=explode(',',$v['groups']);
						}
						unset($filesJurisdiction);
						$_SESSION[C('CHANNEL_GROUPS_STRING')]=$temp;					 
				// }
			 }
			 //引入thinkPHP RBAC类
			  import('ORG.Util.RBAC');	 
			  RBAC::saveAccessList();//调用RBAC类
             //p($_SESSION);die;  
              if($_POST['deLogin']){
              	  return_json(200,'登录成功！','','','closeCurrent');   
              }else{
              	  return_json(200,'登录成功！','index','index','forward',U(APP_APPS.'/Index/index'));              
              }
                     
      }

     //登录超时界面
     public function loginDialog(){
         $this->display();
     }
     
    //退出
    public function logout(){
        session_unset();
        session_destroy();
        echo "<script type='text/javascript'>window.parent.location.href='".U(APP_APPS.'/AppLogin/index','','')."';</script>";
        exit();
    }    
     
 }
?>