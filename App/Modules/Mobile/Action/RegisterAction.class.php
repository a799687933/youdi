<?php
// 本类由系统自动生成，仅供测试用途 
class RegisterAction extends RegisterBasicAction {

	public function _initialize(){
		parent::_initialize();
        if(is_login()) header('location:'.__ROOT__.'/'); 
	}
		
    //会员注册
    public function index(){
		   $this->title='会员注册';
           $this->display(); 
    }
	
	//会员/商家注册表单处理
	public function regDo(){
	    $this->basicRegDo();
	}

	//检查用户名、手机、电子邮件是否被占用
	//只需要在input 属性ID值是数据库字段名
	public function chkData(){
	    $this->basicChkData();
	}	
	
	//发送手机短信息
	public function sendMobile(){
	     $this->basicSendMobile();
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