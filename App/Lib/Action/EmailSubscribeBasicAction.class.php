<?php
// 邮件订阅公共控制器
class EmailSubscribeBasicAction extends BasicAction {
    
	//提交邮件帐户处理
    protected function basicSaveEmail(){
    	 nonlicet_url();
    	 $this->hckToken($_REQUEST);
         $email=quotes(strip_tags($_REQUEST['email_subscite']));
		 if(!$email) $email=quotes(strip_tags($_REQUEST['email']));
		 $submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submit';
		 //if(!is_email($email)) val_json($submits, false, '邮件格式错误');
		 $userName=getUserC('SE_USER_NAME');
		 $data['email']=$email;
		 $data['user_name']=$userName ? $userName : '匿名用户';
		 $data['contents']=$_REQUEST['contents'] ? emptyHtml($_REQUEST['contents']) : '';
		 $data['add_time']=time();
		 if(M('email_subscribe')->where(array('email'=>$email))->find()){
			 if(!$data['contents']) unset($data['contents']);
			 $satrs=M('email_subscribe')->where(array('email'=>$data['email']))->save($data);
			 //p($data);die;
		 }else{
			 $satrs=M('email_subscribe')->add($data);
			 cookie('app_box',1,(86400 * 365));
			 // echo -1;die;
		 }
		 //echo $satrs;die;
		 val_json($submits, true, isL(L('NewInformationWe'),'有新消息我们会第一时间以邮件的方式通知你'),$_SERVER["HTTP_REFERER"]);
		/* if(M('email_subscribe')->where(array('email'=>$email))->find()) val_json($submits, false, isL(L('SubmittedYourMail'),'你已提交过邮件，请不要重复提交'));		 
		 if(M('email_subscribe')->add($data)){
		 	cookie('app_box',1,(86400 * 365));
		 	val_json($submits, true, isL(L('NewInformationWe'),'有新消息我们会第一时间以邮件的方式通知你'),$_SERVER["HTTP_REFERER"]);
		 }else{
		 	val_json($submits, false, '提交失败');
		 }*/
	}

}