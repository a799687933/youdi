<?php
// 公共优惠劵控制器
class CouponBasicAction extends BasicAction {
	protected $userId;
	//优惠劵列表
    protected function basicIndex($pageLimt){
		  $times=time();
		  $bt=M('bonus_type')->field('id,bonus_name,picture,bonus_money,min_amount')
		  ->where("send_start_date <='{$times}' AND send_end_date >='{$times}' AND send_type=3")
		  ->find();
          $M=M('member_bonus');
		  $where="bonus_type_id='{$bt['id']}' AND member_id=0";
		  $counts=$M->where($where)->count();
		  $this->doPage($counts,$pageLimt);
		  $result=$M->field('id,bonus_sn')->where($where)->order("id DESC")->limit($GLOBALS['limit'])->select();
          $this->bt=$bt;
		  //导航下单张广告
		  $this->navAdcou=$this->getAd(63,$ad,false);		  
		  $this->result=$result;
		  $this->display();
    }
	
	//领取现金卷
	protected function basicReceive(){
		 $id=getNum($_REQUEST['id']);
		 $id=$id ? $id : 0;
	      if(!is_login()) $this->myInfos(false,'submits','请登录后再领取优惠劵！',U(is_m().'/Login/index','','')."?url=".base64_encode($_SERVER["HTTP_REFERER"]),'','',3);
		  $times=time();
		  //是否已过期
		  $bt=M('bonus_type')->field('id,'.pfix('bonus_name').' AS bonus_name,picture,bonus_money,min_amount,use_end_date')
		  ->where("send_start_date <='{$times}' AND send_end_date >='{$times}' AND send_type=6 AND id='{$id}' ")
		  ->find();
		  if(!$bt) $this->myInfos(false,'submits','不存在此优惠劵或已过期！','','','',3);
		  //是否已领取过此现金卷
		  if(M('member_bonus')->where("member_id='{$this->userId}' AND used_time=0 AND send_type=6 AND bonus_type_id='{$bt['id']}' ")->find()){
			  $this->myInfos(false,'submits','你已领取过此优惠劵，请使用后再领取！','','','',3);
		  }
		  //写入领取
		  $insets['bonus_type_id']=$bt['id'];
		  $insets['send_type']=6;
		  $insets['bonus_sn']=0;
		  $insets['member_id']=$this->userId;
		  $insets['used_time']=0;
		  $insets['order_id']=0;
		  if(M('member_bonus')->add($insets)){
		  	 $info='<p>优惠券类型：'.$bt['bonus_name'].'</p>';
			 $info.='<p>优惠券额：<span class=hong3>'.getPrice($bt['bonus_money'],true).'</span></p>';
			 $info.='<p>使用门槛：<span class=hong3>订单满'.getPrice($bt['min_amount'],true).'</span></p>';
			 $info.='<p>使用时间：<span class=hong3>'.date('Y-m-d H:i:s',$bt['use_end_date']).'</span></p>';
		  	 $this->myInfos(true,'submits',$info,$_SERVER["HTTP_REFERER"],'','',3);
		  }else{
		  	 $this->myInfos(false,'submits','优惠劵领取失败','','','',3);
		  }
	}
	
}