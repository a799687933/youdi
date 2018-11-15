<?php
// 找回密码公共控制器
class ForgetBasicAction extends BasicAction {
    
	//手机找回忘记密码
    protected function basicIndex(){

    }
	
	//手机找回设置密码
	public function basicSetPwd(){
		nonlicet_url();
		$res=M('member')->field('id')->where(array('mobile_phone'=>$_POST['mobile']))->find();
        import('Class.MobileMessage',APP_PATH);
        $moblie=new MobileMessage($_POST['mobile'],$_POST['mobile_code'],false,true,'');
        if(!$moblie->success || !$res['id']) $this->error(isL(L('IllegalOperation'),'非法操作'));
        $this->mobileCode=$_POST['mobile_code'];//验证码
		$this->mobile=$_POST['mobile'];//手机号
        $this->display();
	}
	
	//手机找回设置密码表单处理
	protected function basicSetPwdForm(){
		nonlicet_url();
		$submit=$_REQUEST['submits'] ? emptyHtml($_REQUEST['submits']) : 'submits';
		$userName=$_REQUEST['mobile'] ? emptyHtml($_REQUEST['mobile']) : $this->myInfos(false,$submit,isL(L('IllegalOperation'),'非法操作'),'','','');
		$code=$_REQUEST['mobile_code'] ? filterRequst($_REQUEST['mobile_code']) : $this->myInfos(false,$submit,isL(L('IllegalOperation'),'非法操作'),'','','');
		$ctype=$_REQUEST['ctype'] > 0 ? $_REQUEST['ctype'] : 0;
		$res=M('member')->field('id')->where(array('user_name'=>$userName))->find();
		if($ctype==0){ //手机找回密码
			import('Class.MobileMessage',APP_PATH);
			$moblie=new MobileMessage($_REQUEST['mobile'],$code,false,true,'');
			if(!$moblie->success) $this->myInfos(false,'mobile_code',isL(L('VerificationCodeError'),'验证码错误'),'','','');
			if(!$res['id']) $this->myInfos(false,$submit,isL(L('WithoutThisUser'),'不存在此用户'),'','','');			
		}else{ //邮件找回密码
		    if($_SESSION['save_email_accounts'] !=$userName) $this->myInfos(false,$submit,isL(L('IllegalOperation'),'非法操作'),'','','');
		    if($_SESSION['save_email_code'] !=$code) $this->myInfos(false,'email_code',isL(L('VerificationCodeError'),'验证码错误'),'','','');
			if(!$res['id']) $this->myInfos(false,$submit,isL(L('WithoutThisUser'),'不存在此用户'),'','','');				
		}
		$data['password']=$_REQUEST['password'] ? 
		trim($_REQUEST['password']) : $this->myInfos(false,$submit,isL(L('ResetPasswordIsEmpty'),'重置密码不可空'),'','','');
		$data['not_password']=$_REQUEST['not_password'];
		if($data['password'] !=$data['not_password']) $this->myInfos(false,$submit,isL(L('TwoInputConsistent'),'两次密码输入不一致'),'','','');
		$arr=pwd_sha1($data['password']);
		$save['password']=$arr['SHA1'];
		$save['sha1_random']=$arr['RANDOM'];	
		if(M('member')->where(array('user_name'=>$userName))->save($save)){
			if($ctype==0) {
				$moblie->unsetDbCode(); //删除手机验证码
			}else{
				unset($_SESSION['save_email_code']);//删除验证
		    }
			val_json($submit,true,isL(L('SuccessRedirection'),'操作成功正在转向...'),U('/Login/index'),'');
		}else{
			val_json($submit,false,isL(L('PasswordSetupFailed'),'密码设置失败'),'','');
		}
	}	
	
	//问题找回密码
	protected function basicQuestion(){
		$this->questions=C('LANG_SWITCH_ON') ? L('Question') : C('QUESTION'); //显示问题列表
		$this->questionsValue=C('QUESTION');//问题值
	}
	
	//问题找回密码表单处理
	protected function basicQuestionForm(){
		nonlicet_url();
		if(md5($_REQUEST['code']) != $_SESSION['verify']) $this->myInfos(false,'code',isL(L('VerificationCodeError'),'验证码错误'),'','','');
		
	   $userName=$_REQUEST['user_name'] ? 
	   trim(strip_tags($_REQUEST['user_name'])) : $this->myInfos(false,'user_name',isL(L('UserNameIsEmpty'),'用户名称不可为空'),'','','');
	   
	   $question=$_REQUEST['question'] ? 
	   trim(strip_tags($_REQUEST['question'])) : $this->myInfos(false,'question',isL(L('PleaseProblem'),'请选择问题'),'','','');
	   
	   $answer=$_REQUEST['answer'] ? 
	   trim(strip_tags($_REQUEST['answer'])) : $this->myInfos(false,'answer',isL(L('QuestionAnswerEmpty'),'请填写问题答案'),'','','');
	   
	   $password=$_REQUEST['password'] ? 
	   trim(strip_tags($_REQUEST['password'])) : $this->myInfos(false,'password',isL(L('ResetPasswordIsEmpty'),'重置密码不可空'),'','','');
	   
	   if($password !=trim(strip_tags($_REQUEST['not_password']))) 
	   $this->myInfos(false,'not_password',isL(L('TwoInputConsistent'),'两次密码输入不一致'),'','','');
	   
	   $arr=pwd_sha1($password);
	   $save['password']=$arr['SHA1'];
	   $save['sha1_random']=$arr['RANDOM'];
	   $where['user_name']=$userName;
	   $where['question']=$question;
	   $where['answer']=md5($answer);
	   if(M('member')->where($where)->save($save)){
		   $this->myInfos(true,'submits',isL(L('SuccessRedirection'),'操作成功正在转向...'),U(is_m().'/Login/index'),'','');
	   }else{
		   $this->myInfos(false,'submits',isL(L('QuestionAnswerError'),'问题答案错误'),'','','');
	   }
	}
	
	//检测用户是否存在
	protected function basicChecks(){
	     nonlicet_url();
	     $field=$_GET['fieldId'];
		 if($field=='mobile') $field='mobile_phone';
		 $where[$field]=$_GET['fieldValue'];
		 $res=M('member')->field('id,question')->where($where)->find();
		 if($res){
		      die(json_encode(array($_GET['fieldId'],true,'',$res['question'])));
		  }else{
		     die(json_encode(array($_GET['fieldId'],false)));
		 }
	}
	
}