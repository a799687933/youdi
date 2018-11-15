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
	
	//邮件找回密码(发送链接界面)
	public function emailPassword(){
	     $this->display();
	}

    //邮件找回密码(发送链接表单处理)
	public function sendEmailUrl(){
		if(!verify_req_sign($_GET,array('email','user_name','_',TOKEN_NAME),0)) val_json('submits',false,'操作超时',$_SERVER["HTTP_REFERER"],'','');
		 $email=is_email($_REQUEST['user_name']) ? $_REQUEST['user_name'] : val_json('submits',false,'非法操作','','','');
		//检查用户是否存在
		$result=M('member')->field('id,full_name')->where(array('user_name'=>$email))->find();
		if(!$result) val_json('submits',true,'error!',U('Index/index'),'','');
		$url=sign_url(array('email'=>$email), 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/Forget/emailSetPwd.html');
		$urlArr=explode('&',$url);
		$times=explode('=',$urlArr[1]);
		$times=$times[1];
		if(!M('member')->where(array('user_name'=>$email))->save(array('answer'=>$times))) val_json('submits',true,'error!',U('Index/index'),'','');
		
		//发邮件
		$tpl='./Public/EmailTpl/setPwd/'.pfix('setpwd').'.html';
		$contents=file_get_contents($tpl);
		$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
		//$contents=str_replace('{$str1}',gmstrftime(" %d %b, %X "),$contents);
		$contents=str_replace('{$str1}',date('Y.m.d H:i',time()),$contents);
		$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$result['full_name']),$contents);
		$contents=str_replace('{$link_url}',$url,$contents);
		//$result=send_mail($email,isL(L('SetPasswordLink'),'设置密码链接'),$contents,false);
		send_mail2($email,isL(L('SetPasswordLink'),'设置密码链接'),$contents);
		if(!$result) val_json('submits',false,isL(L('FailedSendEmail'),'电子邮件发送失败'),'','','');
		else val_json('submits',true,'',U('Forget/sendSuccess','','').'?email='.$email,'','');
	}
	
	//发关成功提示页
	public function sendSuccess(){
	    $this->email=$_REQUEST['$email'];   
		$this->display();
	}
	
	//邮件链接重置密码界面
	public function emailSetPwd(){
		$this->links=true;
		if(!verify_req_sign($_GET,array(),86400)) $this->links=false;
		$times=emptyHtml($_GET['random']);
		$userName=emptyHtml($_GET['email']);
		$user=M('member')->field('answer')->where(array('user_name'=>$userName,'answer'=>$times))->find();
		if(!$user['answer']) $this->links=false;
		if((time() - $user['answer']) > 86400) $this->links=false;
		$this->display();
	}
	
	//邮件链接重置密码表单处理
	public function emailSetPwdForm(){
		if(!verify_req_sign($_GET,array(),86400)) val_json('submits',false,isL(L('OperationTimeout'),''),'操作超时','','');
		$times=emptyHtml($_GET['random']);
		$userName=emptyHtml($_GET['email']);		
		$arr=$_REQUEST['password'] ? 
		pwd_sha1(quotes(trim(strip_tags($_REQUEST['password'])))) :$this->myInfos(false,'submits',isL(L('PleaseEnterApassword'),'请输入密码'),'','','');			
		if($_REQUEST['password'] !=$_REQUEST['not_password']) 
		$this->myInfos(false,'submits',isL(L('TwoInputConsistent'),'两次密码输入不一致'),'','','');	
		$save['password']=$arr['SHA1'];		
		$save['sha1_random']=$arr['RANDOM'];		
		$save['answer']='';			
		$where=array('user_name'=>$userName,'answer'=>$times);
		$user=M('member')->field('answer')->where(array('user_name'=>$userName,'answer'=>$times))->find();		
		if(!$user['answer']) val_json('submits',false,isL(L('OperationTimeout'),''),'操作超时','','');
		if((time() - $user['answer']) > 86400) val_json('submits',false,isL(L('OperationTimeout'),''),'操作超时','','');
		//修改
		if(M('member')->where($where)->save($save)){ 
			val_json('submits',true,'',sign_url(array('email'=>$userName),U('Forget/emailSetPwdSucess')),'','');
		}else{
			val_json('submits',false,isL(L('Failure'),'操作失败'),U('Index/index'),'','');
		}
	}
	
	//设置密码成功提示
	public function emailSetPwdSucess(){
		if(!verify_req_sign($_GET,array(),1200)) die('error');
	    $this->display();
	}
}