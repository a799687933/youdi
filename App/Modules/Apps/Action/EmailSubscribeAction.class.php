<?php
// 本类由系统自动生成，仅供测试用途

class EmailSubscribeAction extends CommonAction {
	
	 //会员邮件订阅列表
    public function emailList(){
	   if($_REQUEST['user_name']){
		   $where['user_name']=array('like',"%{$_REQUEST['user_name']}%");
		   $this->userName=$_REQUEST['user_name'];
	   }
   	   $M=M('email_subscribe');
	   $counts=$M->where($where)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->reuslt=$M->where($where)->order(array('id'=>'DESC'))->limit($GLOBALS['limit'])->select();
       $this->display();
    }
	
	//发邮件
	public function sndEmail(){
		$this->id=$_REQUEST['id'];
		$this->action=$_REQUEST['action'];
		$this->display();		
	}
	
	//发邮件表单处理
	public function sndEmailForm(){
		set_time_limit(0);
		$id=$_REQUEST['id'] ? $_REQUEST['id'] : '';
		$action=$_REQUEST['action'] ? $_REQUEST['action'] : return_json(300,'非法操作');
		if(!in_array($action,array('all','oen'))) return_json(300,'非法操作');
		$title=$_REQUEST['title'] ? $_REQUEST['title'] : return_json(300,'邮件标题不可空');
		$content=$_REQUEST['content'] ? stripcslashes($_REQUEST['content']) : return_json(300,'邮件标题不可空');
		if($action=='all'){ //批量发送
			$result=M('email_subscribe')->field('email')->select();
		    if($result){
		        require(APP_PATH.'Plugins/PHPMailer/PHPMailerAutoload.php');
		        require(APP_PATH.'Plugins/PHPMailer/class.phpmailer.php');
		    	foreach($result as $k=>$v){
		    		send_mail($v['email'],$title,$content,true);
		    	}
				$data['title']=$title;
				$data['content']=$content;
				$data['user_name']='全部发送';
				$data['add_time']=time();
				M('email_subscribe_content')->add($data);
				return_json(200,'批量发送成功！','emailList','','closeCurrent');
		    }else{
		    	return_json(300,'没有订阅用户');
		    }
		}else if($action=='oen'){ //向指定会员发送
			$result=M('email_subscribe')->field('email,user_name')->where(array('id'=>$id))->find();
			if($result){
				send_mail($result['email'],$title,$content,false);
				$data['title']=$title;
				$data['content']=$content;
				$data['user_name']=$result['user_name'];
				$data['add_time']=time();
				M('email_subscribe_content')->add($data);				
				return_json(200,'发送成功！','emailList','','closeCurrent');
			}else{
				return_json(300,'不存在此订阅用户');
			}
		}
	}
	
	//删除邮件
	public function deleteEmail(){
        if(M('email_subscribe')->where(array('id'=>$_REQUEST['ids']))->delete()){
        	$url=U('EmailSubscribe/emailList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
        	return_json(200,'操作成功！','','','forward',$url);
        }else{
        	return_json(300,'操作失败');
        }
	}
	
	//批量导出电子邮件
	public function exportExcel(){
		$fieldArray=array(
			    0=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'用户ID','field'=>'id'),
				1=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'用户名称','field'=>'user_name'),
				2=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'电子邮件','field'=>'email'),
			   3=>array('type'=>'date','width'=>'20','height'=>'50','info'=>'时间','field'=>'add_time')
		);
		$dataArray=M('email_subscribe')->order('id DESC')->select();
		exportExcel($dataArray,$fieldArray,'./'.C('USER_IMG'));		
	}
	
	//邮件订阅发送内容列表
	public function emailContentList(){
   	    $M=M('email_subscribe_content');
	    $counts=$M->count();
	    $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	    $this->reuslt=$M->order(array('id'=>'DESC'))->limit($GLOBALS['limit'])->select();		
		$this->display();
	}
	
	//查看发送内容
	public function emailContentShow(){
	    $this->result=M('email_subscribe_content')->where(array('id'=>$_REQUEST['id']))->find();	
		$this->display();
	}
	
	//删除发送邮件内容
	public function smailContentEelete(){
		if(M('email_subscribe_content')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
        	$url=U('EmailSubscribe/emailContentList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
        	return_json(200,'操作成功！','','','forward',$url);			
		}else{
			return_json(300,'操作失败！');	
		}
	}

}