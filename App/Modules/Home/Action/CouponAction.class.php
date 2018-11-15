<?php
//优惠劵控制器
class CouponAction extends CouponBasicAction {
	
	///优惠劵列表
    public function index(){
    	die('error');
		$this->basicIndex(20);
 	     //热门推荐
	    $this->hots=$this->getGoodsTb(array('recycle_bin'=>0,'shelves'=>1,'hot'=>1,'stock'=>array('gt',0),'is_promotion'=>0),"volume DESC ",20); 
    }
	
	//领取现金卷
	public function receive(){
		$this->userId=getUserC('SE_USER_ID');
		$this->basicReceive();	
	}

}