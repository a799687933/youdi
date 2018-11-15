<?php
// 本类由系统自动生成，仅供测试用途

class MemberAccountAction extends CommonAction{
	
	 //会员明细帐列表
    public function accountList(){
  
       $where=D('Curd')->search('','','change_type'); //询查条件返回数组
       $where['searchArr']['member_id']=$_REQUEST['id'];
       $counts=M('member_account')->where($where['searchArr'])->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   
	   //统计会明细帐
	   $field="user_name,nickname,mobile_phone,member_funds,frozen_funds,rank_points,pay_points";
	   $member=M('member')->field($field)->where(array('id'=>$_REQUEST['id']))->find();
       $this->member=$member;
	   
	   $this->account=M('member_account')->where($where['searchArr'])->limit($GLOBALS['limit'])->order(array('change_time'=>'DESC'))->select();
	   $this->url=$where['url'].'/id/'.$_REQUEST['id'].'/name/'.$_REQUEST['name']; //查询条件分页URL
	   $this->searchVal=$where['keyword']; //保存输入框的关键字 
       $this->display();
    }
	
	//调节会员帐户界面
	public function accountAdjust(){
        $member=M('member')->where(array('id'=>$_GET['id']))->find();
		$this->member=$member;
		$this->display();
	}
	
	//调节会员帐户表单处理
	public function accountAdjustForm(){
		  $this->chkTokenAjax(); //验证令牌是否正确，是否是AJAX提交
          $member=M('member')->field('member_funds,frozen_funds,rank_points,pay_points')->where("id='{$_POST['member_id']}'")->find();
		  
		  if($_POST['select_il_m']<1){ //可用资金
		  	if($_POST['member_money']>0) {
			    $member['member_funds']-=$_POST['member_money']; //用户资金减少
				$_POST['member_money']=$_POST['member_money'] * -1;	
			}
		  }else{
			  if($_POST['member_money']>0) $member['member_funds']+=$_POST['member_money']; //用户资金增加
		  }
		  
		  if($_POST['select_il_f']<1){ //冻结资金
		        if($_POST['frozen_money']>0) {
					$member['frozen_funds']-=$_POST['frozen_funds']; //冻结资金减少
				    $_POST['frozen_money']=$_POST['frozen_money'] * -1;	
				}
		  }else{
		   	   if($_POST['frozen_money']>0) $member['frozen_funds']+=$_POST['frozen_money']; //冻结资金增加
		  } 
		  
		  if($_POST['select_il_r']<1){ //等级积分
		      if($_POST['rank_points']>0) {
				  $member['rank_points']-=$_POST['rank_points']; //等级积分减少
				  $_POST['rank_points']=$_POST['rank_points'] * -1;	
			  }	  	 
		  }else{
		      if($_POST['rank_points']>0) $member['rank_points'] +=  $_POST['rank_points']; //等级积分增加
		  } 
		  
		  if($_POST['select_il_p']<1){ //消费积分
				if($_POST['pay_points']>0) {
					$member['pay_points'] -=  $_POST['pay_points']; //消费积分增加
					$_POST['pay_points']=$_POST['pay_points'] * -1;	
				} 
		  }else{
		       if($_POST['pay_points']>0) $member['pay_points'] +=  $_POST['pay_points']; //消费积分咸少
		  } 
	     
		 if(M('member')->where("id='{$_POST['member_id']}'")->save($member)){
				$_POST['change_time']=time();
				$_POST['change_type']=3;  //后台调节标记
				D('Curd')->insert('member_account');//插入
		 }
	}
	
}