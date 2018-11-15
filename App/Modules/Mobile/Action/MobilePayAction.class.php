<?php
class MobilePayAction extends Action {

	//支付首页 
	public function index(){
        if(!verify_req_sign($_GET,array(),86400)) jsAlert('操作超时', '');
		$orderSn=$_REQUEST['order_sn'];
		$id=$_REQUEST['order_id'];
		$goodsName=base64_to_ch($_REQUEST['goods_name']);
		$goodsName=$goodsName ? $goodsName : $_REQUEST['goods_name'];
		$pay=$_REQUEST['pay'];
		//如果微信支付
		if($_POST['pay']=='weixin'){
			Header('location:'.U(APP_MOBILE.'/Weixin/index','','').'?order='.$orderSn);
			die();
		}			
	    $banking=array(
					  'UnionPay',//银联在线
					  'payPal',//PayPal 国际
                      'alipay', //支付宝
                      'ICBC-WAP', //工商银行WAP
                      'CMBCHINA-WAP', //招商银行WAP
                      'CCB-WAP', //建设银行WAP
		);	
		if(in_array($pay, $banking) && $orderSn){
			if(strpos($orderSn,'CZ') !==false){//在线充值
				$field='transaction,member_money';
				$ma=M('member_account')->where(array('transaction'=>$orderSn,'return_mark'=>0))->find();
				$order['order_sn']=$ma['transaction'];
				$order['order_amount']=$ma['member_money'];
				$order['order_name']=$order['order_sn'];
			}else{ //普通订单支付
				$field='order_sn,order_amount';
				$order=M('order_info')->field($field)->where(array('order_sn'=>$orderSn,'pay_status'=>0))->find();	
				$order['order_name']=$order['order_sn'];
			}	
			if($order){
				$order['order_name']=$goodsName ? $goodsName : $order['order_name'];//商品名称
				$payment=M('payment')->where(array('enabled'=>1,'pay_code'=>$pa))->find();
				 if($pa=='alipay'){ //支付宝
					  $this->alipay($order,$payment);
				 }else if($pa=='yeepay'){//易宝支付
					  $result=array_merge($order,$payment);
					  $result['pd_FrpId']=$pay;
					  $this->yeepay($result);
				 }			
			}else{
			    die('不存在此数据');
			}
		}else{
		    die('支付方式选择错误');
		}
	}
	
	//提交支付宝支付
	private function  alipay($order,$payment){
		//支付宝合作者身份ID
		$pay['alipay_partner']=$payment['coll_id'];
		//支付宝商户帐号
		$pay['alipay_account']=$payment['account'];
		//支付宝商户密钥
		$pay['alipay_key']=$payment['key'];
		//服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$pay['is_http']='http';
		//服务器异步通知页面路径
		$pay['notify_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/'.APP_MOBILE."/Payment/aliNotify.html";
		//页面跳转同步通知页面路径
		$pay['call_back_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/'.APP_MOBILE."/Payment/aliReturn.html";	
		//终止支付返回页地址
		$pay['exit_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/'.APP_MOBILE;	
	    $path = APP_PATH.'Plugins/Payment/alipay_wap/';
		require_once($path."alipay_wap.class.php");	
		$wappay=new alipay_wap();
		echo $wappay->get_code($order,$pay);
		
	}		
	
    //服务器异步通知页面路径
    public function aliNotify(){
		 $payment=M('payment')->where(array('enabled'=>1,'pay_code'=>'alipay'))->find();
		 //支付宝合作者身份ID
		 $pay['alipay_partner']=$payment['coll_id'];
		 //支付宝商户帐号
		 $pay['alipay_account']=$payment['account'];
		 //支付宝商户密钥
		 $pay['alipay_key']=$payment['key'];
	     $path = APP_PATH.'Plugins/Payment/alipay_wap/';
		 require_once($path."alipay_wap.class.php");	
		 $wappay=new alipay_wap();		
		 $result=$wappay->aliNotify($pay);
		 if(!empty($result)){
			 //这里执行对订单的操作
			 $model=new CommonModel();
			 $model->paySuccessOrder($result['out_trade_no'],'支付宝',$result['trade_no'],0);
			 //输出成功信息
			 echo "success";
		 }
    }
	
	//页面跳转同步通知页面路径
	public function aliReturn(){
		 $payment=M('payment')->where(array('enabled'=>1,'pay_code'=>'alipay'))->find();
		 //支付宝合作者身份ID
		 $pay['alipay_partner']=$payment['coll_id'];
		 //支付宝商户帐号
		 $pay['alipay_account']=$payment['account'];
		 //支付宝商户密钥
		 $pay['alipay_key']=$payment['key'];
	     $path = APP_PATH.'Plugins/Payment/alipay_wap/';
		 require_once($path."alipay_wap.class.php");	
		 $wappay=new alipay_wap();		
		 $result=$wappay->aliReturn($pay);
		 if(!empty($result)){
			//这里执行对订单的操作
			$model=new CommonModel();
			$model->paySuccessOrder($result['out_trade_no'],'支付宝',$result['trade_no'],0);
			$info=array();
			$info['success']=true;
			$info['order_sn']=$result['out_trade_no'];
			$info['pay_num']=$result['trade_no'];
			$info['amount']=$result['total_fee'];
			$info['pay_name']='支付宝';						
		 }else{
			$info=array();
			$info['success']=false;
			$info['order_sn']=$result['out_trade_no'];
			$info['pay_num']=$result['trade_no'];
			$info['amount']=$result['total_fee'];
			$info['pay_name']='支付宝';					 
		 }
		 $this->info=$info;
		 if(strpos($info['order_sn'],'CZ') !==false){
			$this->display('Payment/userRecharge');
		 }else{
			$this->display('Payment/info');
		 }		 
	}
	/****支付宝结*****************************************/     
	
	/****易宝支付接口************************************/
	//提交易宝支付
	private function yeepay($data){
	     $path = APP_PATH.'Plugins/Payment/yeepay/';
		 require_once($path."yeepayCom.class.php");	
		 $yee=new yeepayCom();
		 $pay['account']=$data['account']; //易宝支付商户编号
		 $pay['key']=$data['key']; //商户密钥
		 $pay['order_sn']=$data['order_sn']; //订单号
		 $pay['order_amount']=$data['order_amount']; //支付金额
		 $pay['order_name']=$data['order_name'] ? $data['order_name'] : $data['order_amount']; //订单名称
		 $pay['pd_FrpId']=$data['pd_FrpId']; //各种银行的支付通道
		 $pay['yeepay_do_url']='http://www.yeepay.com/app-merchant-proxy/wap/controller.action?'; //请求地址
		 $pay['yee_retrun']="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/'.APP_MOBILE."/Payment/yeeRetrun.html"; //回调地址
		 $yee->yeepay($pay);
	}
	
	//易宝支付成功返回操作(只有支付成功才会返回，否则不予返回)
	public function yeeRetrun(){
	     $path = APP_PATH.'Plugins/Payment/yeepay/';
		 require_once($path."yeepayCom.class.php");	
		 $yee=new yeepayCom(); 
		 $payment=M('payment')->where(array('enabled'=>1,'pay_code'=>'yeepay'))->find();
		 $paysArr['account']=$payment['account']; //易宝支付商户编号
		 $paysArr['key']=$payment['key']; //商户密钥			
		 $result=$yee->yeeRetrunInfo($paysArr);		
		 if(!empty($result)){
			 //操作数据库
			$model=new CommonModel();
			$model->paySuccessOrder($result['order_sn'],'易宝支付',$result['pay_num'],0);	
			$result['pay_name']='易宝支付';
			$this->info=$result;	
		 }else{
			$this->info=array('success'=>false,'order_sn'=>'','pay_num'=>'','amount'=>'','pay_name'=>'易宝支付'); 
		 }
		if(strpos($result['order_sn'],'CZ') !==false){
			$this->display('Payment/userRecharge');
		}else{
			$this->display('Payment/info');
		}	
	}
	 
}