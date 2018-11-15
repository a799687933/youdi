<?php
class PaymentAction extends PaymentBasicAction {
    
	//支付首页 
	public function index(){
		 $this->basicIndex();
	}

    //服务器异步通知页面路径
    public function aliNotify(){
		 $this->basicAliNotify();
    }
	
	//页面跳转同步通知页面路径
	public function aliReturn(){
		 $this->basicAliReturn();
	}
	/****支付宝结*****************************************/     
	
	/****易宝支付接口************************************/
	//易宝支付成功返回操作(只有支付成功才会返回，否则不予返回)
	public function yeeRetrun(){
		 $this->basicYeeRetrun();		
	}
	 
}