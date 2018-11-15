<?php
//忘记密码
class ForgetAction extends ForgetBasicAction {
	
	//手机找回忘记密码
    public function index(){
	    $this->display();
    }
	
	//手机找回设置密码
	public function setPwd(){
		$this->basicSetPwd();
        $this->display();
	}
	
	//手机找回设置密码表单处理
	public function setPwdForm(){
		$this->basicSetPwdForm();
	}	
	
	//问题找回密码
	public function question(){
        $this->basicQuestion();
		$this->title='找回密码';
	    $this->display();
	}
	
	//问题找回密码表单处理
	public function questionForm(){
		$this->basicQuestionForm();
	}
	
	//检测用户是否存在
	public function checks(){
		$this->basicChecks();
	}
	
	//显示验证码
	public function verify(){
		$this->basicVerify();
	}
	
	//验证验证码是否正确
	public function ajaxYzm(){
		$this->basicAjaxYzm();
	}
	

}