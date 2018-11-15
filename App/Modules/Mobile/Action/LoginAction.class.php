<?php
// 本类由系统自动生成，仅供测试用途
class LoginAction extends LoginBasicAction {
	
    //用户登录
    public function index(){ 
         //  if(is_login()) header('location:'.__ROOT__.'/');
		   $this->returnUrl=$_GET['url'];
		   $this->title='会员登录';
           $this->display(); 
    }
	
	/*提交登录*/
	public function loginDo(){
		   $this->basicLoginDo();		
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
    
}