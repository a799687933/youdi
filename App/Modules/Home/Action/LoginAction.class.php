<?php
// 本类由系统自动生成，仅供测试用途
class LoginAction extends LoginBasicAction {
	
    //用户登录
    public function index(){ 
	     yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
         //  if(is_login()) header('location:'.__ROOT__.'/');
		   $this->returnUrl=$_GET['url'];
           $this->display(); 
    }
	
	/*提交登录*/
	public function loginDo(){
		   $this->basicLoginDo();		
	}
	
	//未登录用户强制注册
	public function notLogin(){
		 yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		 if(!verify_req_sign($_GET,array(),-1)) $this->error('error!');
		 $this->url=sign_url(array('ids'=>$_GET['ids'],'total'=>$_GET['total']),U('CheckOut/selectAddress','',''),'');
	     $this->display();
	}
	
	//退出
	public function exitLogin(){
         $this->basicExitLogin();
	}
	
	//AJAX检测用户是否登录
	public function ajaxIsLogin(){
	    if(is_login()){
		    echo 1;
		}else{
		    echo 0;
		}
	}
	
	//检测用户是否存在
	public function checks(){
         $this->basicChecks();
	}
	
	//显示验证码
	public function showCode(){
		$this->basicVerify(40,26);
	}
	
	//验证验证码是否正确
	public function checkCode(){
	    $this->basicAjaxYzm();
	}
    
}