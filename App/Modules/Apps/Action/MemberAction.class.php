<?php
// 本类由系统自动生成，仅供测试用途

class MemberAction extends CommonAction {
	
	 //会员列表
    public function memberList(){
       $where=D('Curd')->search('user_name','','city'); //询查条件返回数组
       $counts=D('MemberRelation')->where($where['searchArr'])->relation(true)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->Member=D('MemberRelation')->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order(array('reg_time'=>'DESC'))->select();
	   $this->url=$where['url']; //查询条件分页URL
	   $this->searchVal=$where['keyword']; //保存输入框的关键字 
	   
	   //会员等级表
	   $this->level=M('member_grade')->order('max_points DESC')->select();
	   //会员等级最高分组
	   $this->max=M('member_grade')->field(pfix('grade_name').' AS grade_name,max_points,special_rank')->order(array('max_points'=>'DESC'))->find();

       $this->display();
    }
	
	//添加会员界面
	public function memberAdd(){
		$this->City=D('Curd')->citySelect(1); //城市列表
		$this->display();
	}
	
	//添加会员表单处理
	public function memberAddForm(){
		  $userArr=pwd_sha1($_POST['password']);//处理密码和加密随机码
		  $_POST['password']=$userArr['SHA1'];
		  $_POST['sha1_random']=$userArr['RANDOM'];
		  $_POST['activation_num']=sha1(uniqid());
		  $_POST['reg_time']=time();
		  $me=M('member');
		  
		  //用户名是否重复
		 /* $member=array();
		  $member['user_name']=$_POST['user_name'];
		  $member['reg_email']=$_POST['user_name'];
		  $member['mobile_phone']=$_POST['user_name'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');*/

		  //电子邮件是否重复
		/*  $member=array();
		  $member['user_name']=$_POST['mobile_phone'];
		  $member['reg_email']=$_POST['mobile_phone'];
		  $member['mobile_phone']=$_POST['mobile_phone'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');*/

		  //手机号是否重复
		  $member=array();
		  $member['user_name']=$_POST['reg_email'];
		  $member['reg_email']=$_POST['reg_email'];
		  $member['mobile_phone']=$_POST['reg_email'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'手机号重复请检查后再提交？');
		  		  
		  if($id=$me->add($_POST)){
			   import('Class.DisposeImg',APP_PATH);
			   DisposeImg::addPictures('member',$id,array($_POST['head_pic']),'','','');		  
		       M('member_account')->add(array('member_id'=>$id,'rank_points'=>1,'change_time'=>time(),'change_desc'=>'登录积分','change_type'=>4)); //默认等级
			   return_json(200,'添加成功！','memberList','','closeCurrent');
		  }else{
		      return_json(300,'操作失败！');
		  }
	}
	
	//修改会员界面
	public function memberSave(){
		$save=M('member')->where(array('id'=>$_GET['id']))->find();
		$this->save=$save;
		//默认区域
		$model=new CommonModel();
		$this->region=$model->getRegion($save['country'],$save['province'],$save['city'],$save['district']);	
		
		//获取会员相册专辑
		$this->photo=M('album')->where(array('tableId'=>$_GET['id'],'table_name'=>'member'))->select();
		$this->display();
	}
	
	//修改会员表单处理
	public function memberSaveForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
		if($_POST['password']){
			$pwd=pwd_sha1($_POST['password']);
			$_POST['password']=$pwd['SHA1'];
			$_POST['sha1_random']=$pwd['RANDOM'];
		}else{
			unset($_POST['password']);
		}
	   $me=M('member');
	   //p($_POST);die;
		  //用户名是否重复
		  $member='';
		  $member="user_name='{$_POST['user_name']}' AND id <> '{$_POST['id']}'";  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');

		  //手机号是否重复
		//  $member='';
		//  $member="(reg_email='{$_POST['mobile_phone']}' OR mobile_phone='{$_POST['mobile_phone']}') AND (id <> '{$_POST['id']}') ";  
		//  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');

		  //电子邮件是否重复  
		 // $member='';
		 // $member="(reg_email='{$_POST['mobile_phone']}' OR mobile_phone='{$_POST['reg_email']}') AND (id <> '{$_POST['id']}') ";  
		 // if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');		  
		 D('Curd')->modify('member','memberList','','id',array($_POST['head_pic'])); //修改数据
	}
	
	//取消会员登录限制
	public function cancelLimit(){
	   $id=$_GET['id'];
	   if(M('member')->where(array('id'=>$id))->save(array('temp_login_count'=>0,'temp_login_time'=>1))){
	       return_json(200,'操作成功！','memberList','','closeCurrent');
	   }else{
	       return_json(300,'操作失败！');
	   }
	}
	
	//删除会员
	public function memberDelete(){
		   import('Class.Category',APP_PATH);
		   $curd=D('Curd');
		   $url=U(APP_APPS.'/Member/memberList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
           $ids['id']=array('IN',$_REQUEST['ids']);
		  if(M('member')->where($ids)->delete()){
		  	 $curd->del_img($_REQUEST['ids'],'member'); //删除会员照片
		  	 M('member_account')->where(array('member_id'=>array('in',$_REQUEST['ids'])))->delete();  //删除会员明细帐
		  	 //M('comment_reply')->where(array('user_id'=>array('in',$_REQUEST['ids'])))->delete();  //删除会员论坛贴子回复
		  	 //$commentId=Category::unlimitedForInId(M('comment')->field("id")->where(array('user_id'=>$_REQUEST['ids']))->select());//获取贴子评述表ID
		  	 //$curd->del_img($commentId,'comment'); //删除贴子评述时上传的图片
		  	 //M('comment')->where(array('user_id'=>$_REQUEST['ids']))->delete(); //删除贴子评述表
		  	 //$articleId=Category::unlimitedForInId(M('article')->field("id")->where(array('author_id'=>$_REQUEST['ids']))->select());//获取贴子表ID
		  	 //$curd->del_img($articleId,'article'); //删除贴子上传的图片
		  	 //M('article')->where(array('author_id'=>$_REQUEST['ids']))->delete();//删除贴子表
			 return_json(200,'删除成功！','memberList','','forward',$url);
		  }	
	}
	
	//会员导出EXCEL
	function exportExcel(){
		set_time_limit(0);
		$fieldArray=array(
			    0=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'用户ID','field'=>'id'),
				1=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'用户名称','field'=>'user_name'),
				2=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'用户昵称','field'=>'nickname'),
				3=>array('type'=>'img','width'=>'13','height'=>'50','info'=>'用户头像','field'=>'head_pic'),
				4=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'电子邮件','field'=>'reg_email'),
				5=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'手机','field'=>'mobile_phone'),
				6=>array('type'=>'address','width'=>'30','height'=>'50','info'=>'详细地址','field'=>'country,province,city,district,address'),
				7=>array('type'=>'txt','width'=>'15','height'=>'50','info'=>'邮政编码','field'=>'zip'),
				8=>array('type'=>'txt','width'=>'15','height'=>'50','info'=>'固定电话','field'=>'telephone'),
			   9=>array('type'=>'txt','width'=>'15','height'=>'50','info'=>'QQ号','field'=>'qq'),
			   10=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'微信号','field'=>'wechat'),
			   11=>array('type'=>'date','width'=>'20','height'=>'50','info'=>'注册时间','field'=>'reg_time')
		);
		$dataArray=M('member')->order('id DESC')->limit($limit)->select();
		exportExcel($dataArray,$fieldArray,'./'.C('USER_IMG'));
	}	

}