<?php
class PaymentAction extends PaymentBasicAction {
	//支付首页 
	public function index(){
		 if(!verify_req_sign($_GET,array(),86400)) die('Error!');
		 if($_GET['order_time'] > 0)
		 if((time() - intval($_GET['order_time'])) > C('ORDERRETAIN')) $this->error(isL(L('ThisTransactionExpired'),'此交易已过期'),__ROOT__.'/');		
         $result=$this->basicIndex();
		 if(!$result['success']) die($result['msg']);
	}
	
	/****银联在线支付***********************************/
	//银联同步通知页面
	public function frontReceive(){
	      $result=$this->basicFrontReceive();
		  $this->info=$result;
		  $this->display('info');			 
	}
	
	//银联移步通知页面
	public function backReceive(){
	    $this->basicBackReceive();
	}	
	/****银联在线支付结束***********************************/	
	
	/***WAP支付宝*******************************/
    //WAP服务器异步通知页面路径
    public function wapAliNotify(){
          $this->BasicWapAliNotify();	  	
    }	

	//页面跳转同步通知页面路径
	public function wapAliReturn(){
          $result=$this->BasicWapAliReturn();
		  $this->info=$result;
		  $this->display('info');
	}	
	/***WAP支付宝结束*******************************/
    
	/***PC支付宝*******************************/
    //服务器异步通知页面路径
    public function aliNotify(){
          $this->basicAliNotify();	  	
    }
	
	//页面跳转同步通知页面路径
	public function aliReturn(){
          $result=$this->basicAliReturn();
		  $this->info=$result;
		  $this->display('info');
	}
	/****PC支付宝结*****************************************/     
	
	/****易宝支付接口************************************/	
	public function yeeRetrun(){
		 $result=$this->basicYeeRetrun();
		  $this->info=$result;
		  $this->display('info');		 
    }	
	/****易宝支付接口结束*******************************/
	
	/****paypal(贝宝支付)******************************/
	/*贝宝支付异步通知*/
	public function paypalNotify(){
		$this->basicPaypalNotify();
	}
	/*贝宝支付同步跳转通知页*/
	public function paypalReturn(){
		  $result=$this->basicPaypalReturn();
		  $this->info=$result;
		  $this->display('info');		
	}	
	/****paypal(贝宝支付)结束******************************/
	
	/******扫码微信支付*************************/
	public function weixinPay(){
		if(!verify_req_sign($_GET,array(),86400)) die('Error!');
		if($_GET['order_time'] > 0)
		if((time() - intval($_GET['order_time'])) > C('ORDERRETAIN')) $this->error(isL(L('ThisTransactionExpired'),'此交易已过期'),__ROOT__.'/'.is_M());			
		$orderSn=$_REQUEST['order'];
		$payUrl="http://".$_SERVER["HTTP_HOST"]."/Weixin/index".'?order='.$orderSn;					
		$this->payUrl=$payUrl;
		$this->assign('orderSn',$orderSn);
	    $this->display('Payment/weixinPay');		
	}	
	 
}