<?php
/*邮件订阅控制器*/
class EmailSubscribeAction extends EmailSubscribeBasicAction {
	
    //订阅界面
    public function saveEmail(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		if(is_email($_REQUEST['email'])){
			$email=$_REQUEST['email'];
	    }else{
			$this->myInfos(false,'',isL(L('EmailFormatError'),'请输入正确的电邮地址'),'');
			die;
	    }
		//是否已订阅过
		$email_subscribe=M('email_subscribe')->where(array('email'=>$email))->getField('email');
		if($email_subscribe) $this->myInfos(false,'',isL(L('YouHaveRegistered'),'你已在YOUDI WU中有注册用户'),'');
		//是否已注册过
		$member=M('member')->where(array('user_name'=>$email))->getField('id');
		if($member) $this->myInfos(false,'',isL(L('YouHaveRegistered'),'你已在YOUDI WU中有注册用户'),'');
		if(IS_AJAX) $this->myInfos(true,'','',get_url());
		
	    //最近浏览
	    $this->recentVisit= $this->recentVisit(0,24,0);	 	
		//获得省份
		$r=new RegionModel();
		$this->region=$r->getRegion(array('province'=>'1'));
		$this->email=$email;
        $this->display();
    }
	
	//订阅界面表单处理
	public function saveEmailForm(){
		if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','error','');
		//年龄
		$age['20']='20岁以下';
		$age['20-25']='20岁-25岁';
		$age['25-55']='25岁-35岁';
		$age['35']='35岁以上';
		//省/市
		$province=$_REQUEST['province'] ? $_REQUEST['province'] : 0;
		$city=$_REQUEST['city'] ? $_REQUEST['city'] : 0;
		$ids=$province.','.$city;
		$region=M('region')->field('region_id,region_name')->where("region_id IN ({$ids})")->order("region_type ASC")->select();
		$data=array();
		$data['email']=$_REQUEST['email'];
		$data['age']=$_REQUEST['age'] ? $age[$_REQUEST['age']] : '';
		$data['demand']=$_REQUEST['demand'] ? implode(',',$_REQUEST['demand']) : '';
		$data['user_name']=$_REQUEST['user_name'] ? $_REQUEST['user_name'] : '';
		$data['citys']=$region ? $region[0]['region_name'].$region[1]['region_name'] : '';
		$data['contents']='';
		$data['add_time']=time();
		M('email_subscribe')->add($data);
		header("location:".U('Index/index'));
	}
	
	//免费在家试穿表单处理
	public function wantTest(){
		$user_id=getUserC('SE_USER_ID');
		$pfix=pfix();
	    $sub_email=is_email($_REQUEST['sub_email']) ? $_REQUEST['sub_email'] : $this->myInfos(false,'','email is error');
		$goods_id=intval($_REQUEST['goods_id']) ? $_REQUEST['goods_id'] :  $this->myInfos(false,'','goods id is error');
		$attr_ids=$_REQUEST['attr_ids'] ? rtrim($_REQUEST['attr_ids'],',') : '';
		$is_subs=intval($_REQUEST['is_subs']);
		$m=M('goods');
		$goods_name=$m->where(array('id'=>$goods_id))->getField($pfix.'goods_name');
		if(!$goods_name) $this->myInfos(false,'','goods is error');
		$sql="SELECT ga.{$pfix}name AS name,av.{$pfix}attr_value AS attr_value FROM ".PREFIX."goods_attribute AS ga ";
		$sql.="JOIN ".PREFIX."goods_attribute_value AS av ON (ga.id=av.attribute_id) JOIN ".PREFIX."goods_attr AS attr ";
		$sql.="ON(attr.attribute_value_id=av.id) WHERE attr.id IN ({$attr_ids}) ORDER BY ga.id ASC";
		$attr=$m->query($sql);
		$data['sub_email']=$sub_email;
		$data['user_id']=$user_id ? $user_id : 0;
		$data['goods_name']=$goods_name;
		$data['goods_attr']=$attr ? json_encode($attr) : '';
		$data['add_time']=time();
		if(M('want_test')->add($data)){
			$str="<img src='".__ROOT__."/Public/Common/Images/icon102.png' style='position:relative;top:3px;'> ";
			$str.="<span style='color:green;'>".isL(L('SubmitSuccessfully'),'提交成功')."</span>";
			$this->myInfos(true,'',$str);
		}else{
			$str="<img src='".__ROOT__."/Public/Common/Images/icon103.png' style='position:relative;top:3px;'> ";
			$str.="<span style='color:green;'>".isL(L('Failure'),'操作失败')."</span>";
			$this->myInfos(false,'',$str);			
	    }
	}

}