<?php
// 提交支付公共控制器
class PaymentBasicAction extends BasicAction {
	private $isMobile;
	//支付首页 
	protected function basicIndex(){
     	 //if(!verify_req_sign($_GET,array(),3600)) exit('Error!');
		 if(is_m()) $this->isMobile='/'.is_m();
		 $orderSn=$_REQUEST['order_sn'];
		 $orderTime=$_REQUEST['order_time'];
		 $goodsName=$_REQUEST['order_goods'];
		 $pay=$_REQUEST['pay'] ? $_REQUEST['pay'] : $data['pay'];
		 
		 if(strpos($orderSn,'CZ') ===false){//普通订单有时间限制
		 	if((time()-$orderTime) > C('ORDERRETAIN')) exit('Error!');
		 }
		 //微信支付
		 if($pay=='weixin'){
			$order=$orderSn;			
			//如果是手机访问不需生成二维码扫描支付链接，直接跳转微信支付
	       $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
           $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
           if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
           	   $url=sign_url(array('order'=>$orderSn,'order_time'=>$_REQUEST['order_time']),U('/Weixin/index','',''),'');
	           Header('location:'.$url);
	           die();
           }	
		   //跳到扫描页
		   Header('location:'.$url=sign_url(array('order'=>$orderSn,'order_time'=>$_REQUEST['order_time']),U('/Payment/weixinPay','',''),''));
		   die();
		 }			 
	     $banking=array(
					  'UnionPay',//银联在线
					  'payPal',//PayPal 国际						
                      'alipay', //支付宝
                      'ICBC-WAP', //工商银行WAP
                      'CMBCHINA-WAP', //招商银行WAP
                      'CCB-WAP', //建设银行WAP
					  //以下易宝PC端
                      'ICBC-NET',
                      'CMBCHINA-NET',
                      'ABC-NET',
                      'CCB-NET',
                      'BCCB-NET',
                      'BOCO-NET',
                      'CIB-NET',
                      'NJCB-NET',
                      'CMBC-NET',
                      'CEB-NET',
                      'BOC-NET',
                      'PINGANBANK-NET',
                      'CBHB-NET',
                      'HKBEA-NET',
                      'NBCB-NET',
                      'ECITIC-NET',
                      'SDB-NET',
                      'GDB-NET',
                      'SHB-NET',
                      'SPDB-NET',
                      'POST-NET',
                      'BJRCB-NET',
                      'HXB-NET',
                      'CZ-NET'
		);
		if(in_array($pay, $banking) && $orderSn){
			if(strpos($orderSn,'CZ') !==false){//在线充值
				$field='transaction,member_money';
				$ma=M('member_account')->where(array('transaction'=>$orderSn,'return_mark'=>0))->find();
				$order['order_sn']=$ma['transaction'];
				$order['order_amount']=$ma['member_money'];
			}else{ //普通订单支付
				$field='order_sn,order_amount';
				$order=M('order_info')->field($field)->where(array('order_sn'=>$orderSn,'pay_status'=>array('lt',2)))->find();		    
			}
			if($order){
				 if($pay=='alipay'){ //支付宝
					 $pa='alipay';
				 }else if($pay=='payPal'){ //payPal 国际
					 $pa='payPal';
				 }else if($pay=='UnionPay'){ //银联
					 $pa='UnionPay';
				 }else{ //易宝支付
					 $pa='yeepay';
				 }
				 $res=M('payment')->where(array('enabled'=>1,'is_show'=>1,'pay_code'=>$pa))->find();
				 $result=array_merge($order,$res);
				 if(!$result) return array('success'=>false,'msg'=>'Error!');
				 if($pa=='alipay'){ //支付宝
				      if(is_mobile()){
				      	$this->wapAlipay($order, $res);
				      }else{
				      	 $this->alipay($result);
				      }				 	  
				 }else if($pa=='UnionPay'){//银联在线支付		      
				 	  $this->unionPay($result);
				 }else if($pa=='yeepay'){//易宝支付
				      $result['pd_FrpId']=$pay;
				 	  $this->yeepay($result);
				 }else if($pa=='payPal'){//贝宝支付
				 	  $this->paypal($result);
				 }					
			}else{
				$this->error(isL(L('ThereIsNoSuchData'),'不存在此数据'),U(is_m().'/Index/index'));
			}
		}else{
			$this->error(isL(L('IllegalOperation'),'非法操作'),U(is_m().'/Index/index'));
		}
		
	}

	/**银联支付*******************************************/
    //产品：跳转网关支付产品
    public function unionPay($result){
        $this->_imoprts();//引入文件
		//参数
        $params = array(
		
		//以下信息非特殊情况不需要改动
		'version' => '5.0.0',                 //版本号
		'encoding' => 'utf-8',				  //编码方式
		'txnType' => '02',				      //交易类型
		'txnSubType' => '01',				  //交易子类
		'bizType' => '000201',				  //业务类型
		'frontUrl' =>  SDK_FRONT_NOTIFY_URL,  //前台通知地址
		'backUrl' => SDK_BACK_NOTIFY_URL,	  //后台通知地址
		'signMethod' => '01',	              //签名方法
		'channelType' => $GLOBALS['channelType'],	              //渠道类型，07-PC，08-手机
		'accessType' => '0',		          //接入类型
		'currencyCode' => '156',	          //交易币种，境内商户固定156
		
		//TODO 以下信息需要填写
		'merId' => $result['account'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
		'orderId' => $result['order_sn'],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
		'txnTime' => date('YmdHis',$result['add_time']),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
		'txnAmt' => $result['order_amount'] * 100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

		//TODO 其他特殊用法请查看 special_use_preauth.php
	    );
		AcpService::sign ( $params );
		$uri = SDK_FRONT_TRANS_URL;
		$html_form = AcpService::createAutoFormHtml( $params, $uri );
		echo $html_form;
    }
	
	//银联同步通知页面
	public function basicFrontReceive(){
		$this->_imoprts();//引入文件	
		if(isset ( $_POST ['signature'] )){
			//验证成功
			if(AcpService::validate ( $_POST )){
				$orderId = $_POST ['orderId']; //订单号
				$queryId=$_POST['queryId']; //交易流水号
				$txnAmt=$_POST['txnAmt']; //交易金额
				$respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功
				//如果卡号我们业务配了会返回且配了需要加密的话，请按此方法解密......
				if($respCode=='00' || $respCode=='A6'){//银联操作成功
						//这里执行对订单的操作
						$model=new PaymentModel();
						$model->paySuccessOrder($orderId,'银联在线',$queryId,0);	
						return $this->returnData(true,$orderId,$queryId,'银联在线',$txnAmt,'CNY');					  
				}else{
				   die('支付失败');
				}
			}else{
				die('支付验证失败');
			}
		}else{
			die('签名为空');
		}	
	}
	
	//银联移步通知页面
	public function basicBackReceive(){
		$this->_imoprts();//引入文件
		if(isset ( $_POST ['signature'] )){
			//验证成功
			if(AcpService::validate ( $_POST )){
				$orderId = $_POST ['orderId']; //订单号
				$queryId=$_POST['queryId']; //交易流水号
				$txnAmt=$_POST['txnAmt']; //交易金额
				$respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功
				//如果卡号我们业务配了会返回且配了需要加密的话，请按此方法解密......
				if($respCode=='00' || $respCode=='A6'){//银联操作成功
					//这里执行对订单的操作
					$model=new PaymentModel();
					$model->paySuccessOrder($orderId,'银联在线',$queryId,0);
					die('支付成功');					  
				}else{
				   die('支付失败');
				}
			}else{
				die('支付验证失败');
			}
		}else{
		    die('签名为空');
		}
	}
	
	//银联引入文件
	private function _imoprts(){
		$path = APP_PATH.'Plugins/Payment/UnionPay/';
		//日志类
		require_once($path."Sdk/log.class.php");			
		//配置文件，WAP与PC各有不同的回调地址
		if($this->isMobile){
			require_once($path."Sdk/sdk_wap_config.php");	
			$GLOBALS['channelType']='08';   //手机支付
		}else{
			require_once($path."Sdk/sdk_pc_config.php");	
			$GLOBALS['channelType']='07';   //PC支付
		}
		//公共使用
		require_once($path."Sdk/common.php");		
		//其它
		require_once($path."Sdk/secureUtil.php");	
		require_once($path."Sdk/acp_service.php");			
	}
	
	/****WAP支付宝***************************************/
	//提交WAP支付宝支付
	private function  wapAlipay($order,$payment){
		//支付宝合作者身份ID
		$pay['alipay_partner']=$payment['coll_id'];
		//支付宝商户帐号
		$pay['alipay_account']=$payment['account'];
		//支付宝商户密钥
		$pay['alipay_key']=$payment['key'];
		//服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$pay['is_http']='http';
		//服务器异步通知页面路径
		$pay['notify_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__."/Payment/wapAliNotify.html";
		//页面跳转同步通知页面路径
		$pay['call_back_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__."/Payment/wapAliReturn.html";	
		//终止支付返回页地址
		$pay['exit_url']="http://".$_SERVER["HTTP_HOST"].__ROOT__.'/';	
	    $path = APP_PATH.'Plugins/Payment/alipay_wap/';
		require_once($path."alipay_wap.class.php");	
		$wappay=new alipay_wap();
		echo $wappay->get_code($order,$pay);
		
	}		
	
    //WAP服务器异步通知页面路径
    public function BasicWapAliNotify(){
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
			 $model=new PaymentModel();
			 $model->paySuccessOrder($result['out_trade_no'],'支付宝',$result['trade_no'],0);
			 //输出成功信息
			 echo "success";
		 }
    }	
	
	//WAP页面跳转同步通知页面路径
	public function BasicWapAliReturn(){
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
			$model=new PaymentModel();
			$model->paySuccessOrder($result['out_trade_no'],'支付宝',$result['trade_no'],0);
		    return $this->returnData(true,$result['out_trade_no'],$result['trade_no'],'支付宝',$total_fee,'CNY');
		 }else{
			die("fail");			 
		 } 
	}	
	/****WAP支付宝结束***************************************/
	
	/****PC支付宝*****************************************/      
    //提交
    private function alipay($arr){
		$path = APP_PATH.'Plugins/Payment/alipay/';
		require_once($path."alipay_submit.class.php");		
		$alipay_config=$this->alipayConfig($path,$arr);
		        //支付类型
		        $payment_type = "1";
		        //服务器异步通知页面路径(需http://格式的完整路径，不能加?id=123这类自定义参数)
		        $notify_url = "http://".$_SERVER["HTTP_HOST"].__ROOT__.$this->isMobile."/Payment/aliNotify.html";		
		        //页面跳转同步通知页面路径(需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/)
		        $return_url =  "http://".$_SERVER["HTTP_HOST"].__ROOT__.$this->isMobile."/Payment/aliReturn.html";		        
		        //卖家支付宝帐户(必填)
				 $seller_email =$alipay_config['account']; 
		        //商户唯一订单号(必填)
		        $out_trade_no = $arr['order_sn'];		
		        //订单名称(必填)
		        $subject = $arr['goods_name'] ? $arr['goods_name'] : $arr['order_sn'];		
		        //付款金额，必须是数字(必填)
		        $total_fee = $arr['order_amount'];
		        //订单描述		
		        $body = '';				
		        //商品展示地址(需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html)
		        $show_url = '';		
		        //防钓鱼时间戳(若要使用请调用类文件submit中的query_timestamp函数)
		        $anti_phishing_key = "";		
		        //客户端的IP地址(非局域网的外网IP地址，如：221.0.0.1)
		        $exter_invoke_ip = "";	
				//构造要请求的参数数组，无需改动
				$parameter = array(
						"service" => "create_direct_pay_by_user",
						"partner" => trim($alipay_config['partner']),
						"payment_type"	=> $payment_type,
						"notify_url"	=> $notify_url,
						"return_url"	=> $return_url,
						"seller_email"	=> $seller_email,
						"out_trade_no"	=> $out_trade_no,
						"subject"	=> $subject,
						"total_fee"	=> $total_fee,
						"body"	=> $body,
						"show_url"	=> $show_url,
						"anti_phishing_key"	=> $anti_phishing_key,
						"exter_invoke_ip"	=> $exter_invoke_ip,
						"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
				 );		
				
				//建立请求
				$alipaySubmit = new AlipaySubmit($alipay_config);
				$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
				echo $html_text;		
    }

    //PC支付宝服务器异步通知页面路径
    protected function basicAliNotify(){
		    $path = APP_PATH.'Plugins/Payment/alipay/';
		    require_once($path."alipay_notify.class.php");	  
		    $paysArr=M('payment')->where(array('enabled'=>1,'pay_code'=>'alipay'))->find();		
		    $alipay_config=$this->alipayConfig($path,$paysArr);
		    //计算得出通知验证结果
		    $alipayNotify = new AlipayNotify($alipay_config);
		    $verify_result = $alipayNotify->verifyNotify();	
			if($verify_result) {//验证成功			
				//商户订单号
				$out_trade_no = $_POST['out_trade_no'];			
				//支付宝交易号
				$trade_no = $_POST['trade_no'];			
				//交易状态
				$trade_status = $_POST['trade_status'];					
				//这里执行对订单表的操作
				 if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
						//这里执行对订单表的操作
						$model=new PaymentModel();
						$model->paySuccessOrder($out_trade_no,'支付宝',$trade_no,0);								
				  }			        
				  echo "success"; //请不要修改或删除,返回给支付宝说明业务已处理
			}else {			   
			      echo "fail";  //验证失败
			}			  	
    }
	
	//PC支付宝页面跳转同步通知页面路径
	protected function basicAliReturn(){
			//测试对订单的操作
			//$model=new PaymentModel();
			//$model->paySuccessOrder('2016120210002286529','支付宝','201601015245879564281',0);		
		    //die();
			
		    $path = APP_PATH.'Plugins/Payment/alipay/';
		    require_once($path."alipay_notify.class.php");	  
		    $paysArr=M('payment')->where(array('enabled'=>1,'pay_code'=>'alipay'))->find();		    
		    $alipay_config=$this->alipayConfig($path,$paysArr);		
			//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($alipay_config);
			$verify_result = $alipayNotify->verifyReturn();
			if($verify_result) {//验证成功			
				//商户订单号
				$out_trade_no = $_GET['out_trade_no'];			
				//支付宝交易号
				$trade_no = $_GET['trade_no'];			
				//交易状态
				$trade_status = $_GET['trade_status'];
				//订单金额
				$total_fee = $_GET['total_fee']; 
				//这里执行对订单的操作		
			    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
			    	    //这里执行对订单的操作
						$model=new PaymentModel();
						$model->paySuccessOrder($out_trade_no,'支付宝',$trade_no,0);		
						return $this->returnData(true,$out_trade_no,$trade_no,'支付宝',$total_fee,'CNY');
			    }else {
			      echo "trade_status=".$_GET['trade_status'];
			    }					
			}else {
			    //验证失败
			    //如要调试，请看alipay_notify.php页面的verifyReturn函数
			}		
	}
	
	//支付宝配置
	private function alipayConfig($path,$arr){
		 //卖家支付宝帐户(必填)
		$alipay_config['account']=$arr['account'];		
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $arr['coll_id'];		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= $arr['key'];
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');		
		//ca证书路径地址，用于curl中ssl校验(请保证cacert.pem文件在当前文件夹目录中)
		$alipay_config['cacert']    = $path.'cacert.pem';	
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
			
		return $alipay_config;
	}
	/****支付宝结*****************************************/     
	
	/****易宝支付接口************************************/
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
		 $pay['yeepay_do_url']='https://www.yeepay.com/app-merchant-proxy/node?'; //请求地址
		 $pay['yee_retrun']="http://".$_SERVER["HTTP_HOST"].__ROOT__."/Payment/yeeRetrun.html"; //回调地址
		 $yee->yeepay($pay);		
	}
	
	//易宝支付成功返回操作(只有支付成功才会返回，否则不予返回)
	protected function basicYeeRetrun(){
	     $path = APP_PATH.'Plugins/Payment/yeepay/';
		 require_once($path."yeepayCom.class.php");	
		 $yee=new yeepayCom();
		 $payment=M('payment')->where(array('enabled'=>1,'pay_code'=>'yeepay'))->find();
		 $paysArr['account']=$payment['account']; //易宝支付商户编号
		 $paysArr['key']=$payment['key']; //商户密钥	
		 $result=$yee->yeeRetrunInfo($paysArr);
		 if(!empty($result)){
			 //操作数据库
			$model=new PaymentModel();
			$model->paySuccessOrder($result['order_sn'],'易宝支付',$result['pay_num'],0);	
			$result['pay_name']='易宝支付';
			return $this->returnData(true,$result['order_sn'],$result['pay_num'],'易宝支付',$result['amount'],'CNY');
			if(intval($result['r9_BType'])==2) die('success'); //处理移步通知
		 }else{
			$this->info=array('success'=>false,'order_sn'=>'','pay_num'=>'','amount'=>'','pay_name'=>'易宝支付'); 
		 }
	}	
	/****易宝支付接口结束*******************************/
	/*提交贝宝支付*/
	public function paypal($reslut){
		$code=C('APP_KEY'); //签名串
		$goodsName='';
		if($reslut['order_id']){
			$orderGoods=M('order_goods')->field(pfix('goods_name').' AS goods_name')->where(array('order_sn'=>$reslut['order_sn']))->select();
			foreach($orderGoods as $v) if($v) $goodsName.='['.$v['goods_name'].'] ';
			if(!$goodsName) $goodsName=$reslut['order_sn'];
		}else{
			$goodsName='Online recharge'; //在线充值
		}
		$orderSn=$reslut['order_sn'];//订单号
		$orderAmount=RMBtoForeign($reslut['order_amount'],'USD');//订单金额转换成美元
		$cmd='_cart';//告诉paypal该表单是立即购买
		$business=$reslut['account'];//卖家帐号  也就是收钱的帐号
		$currencyCode='USD';//交易币种，支持(值可以为 "USD"、"EUR"、"GBP"、"CAD"、"JPY")
		$lc='US';//支付页面语言设置
		//测试环境https://www.sandbox.paypal.com/cgi-bin/webscr
		$paypalUlr='https://www.paypal.com/cgi-bin/webscr';//支付URL
		$return="http://".$_SERVER["HTTP_HOST"].__ROOT__."/Payment/paypalReturn.html"; //支付成功后网页跳转地址
		$notifyUrl="http://".$_SERVER["HTTP_HOST"].__ROOT__."/Payment/paypalNotify.html";//支付成功后paypal后台发送订单通知地址
		$cancelUrl="http://".$_SERVER["HTTP_HOST"]; //如果用户跳转到paypal支付接口后不想继续购买返回按钮
		//参家签名   (paypal账号、订单号、订单总金额、交易币种)
		$sing=md5($code.$business.$orderSn.$orderAmount);
		$str.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		//$str.='<p style="padding:0;margin:0;">正在重定向...</p>';
		$str.='<form method="post" action="'.$paypalUlr.'" id="paypal" name="paypal">';
		$str.='<input type="hidden" name="upload" value="1">';//第三方购物车或购物车的内容上传
		$str.='<input type="hidden" name="cmd" value="'.$cmd.'" >'; //告诉paypal该表单是立即购买
		$str.='<input type="hidden" name="business" value="'.$business.'" >';//卖家帐号  也就是收钱的帐号
		$str.='<input type="hidden" name="invoice" value="'.$orderSn.'">';//订单号 paypal原样返回
		$str.='<input type="hidden" name="item_name_1" value="'.$goodsName.'">'; //商品名称
		$str.='<input type="hidden" name="amount_1" value="'.$orderAmount.'">';//订单总金额		
		$str.='<input type="hidden" name="currency_code" value="'.$currencyCode.'">';//交易币种		
		$str.='<input type="hidden" name="lc" value="'.$lc.'">'; //支付页面语言设置
		$str.='<input type="hidden" name="return" value="'.$return.'">';//支付成功后网页跳转地址
		$str.='<input type="hidden" name="notify_url" value="'.$notifyUrl.'">';//支付成功后paypal后台发送订单通知地址
		$str.='<input type="hidden" name="cancel_return" value="'.$cancelUrl.'">';// 如果用户跳转到paypal支付接口后不想继续购买返回按钮
		$str.='<input type="hidden" name="custom" value="'.$sing.'">';//自定义签名  paypal原样返回
		$str.='<input type="submit"  name="" value="To paypal" style="">'; //提交按钮
		$str.='</form>';
		$str.='<script type="text/javascript">document.forms["paypal"].submit();</script>';		
		echo ($str);
	}
	
	/*贝宝支付移步通知*/
	protected function basicPaypalNotify($isTpl){
	        //读入POST流
			$raw_post_data = file_get_contents('php://input');
			$raw_post_array = explode('&', $raw_post_data);
			$myPost = array();
			foreach ($raw_post_array as $keyval) {
				$keyval = explode ('=', $keyval);
				if (count($keyval) == 2)
					$myPost[$keyval[0]] = urldecode($keyval[1]);
			}		
			//读取贝宝系统后添加“CMD” 
			$req = 'cmd=_notify-validate';
			if(function_exists('get_magic_quotes_gpc')) {
				$get_magic_quotes_exists = true;
			}
			foreach ($myPost as $key => $value) {
				if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
					$value = urlencode(stripslashes($value));
				} else {
					$value = urlencode($value);
				}
				$req .= "&$key=$value";
			}	
			//IPN数据后回到贝宝验证IPN数据是否真实		
			//测试环境
			//$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			//生产环境
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
			$ch = curl_init($paypal_url);
			if ($ch == FALSE) return FALSE;
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);		
			//测试环境打开这两个选择
			//curl_setopt($ch, CURLOPT_HEADER, 1);
			//curl_setopt($ch, CURLINFO_HEADER_OUT, 1);		
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));	
			$res = curl_exec($ch);
			$logTime=time();
			//CURL错误
			if (curl_errno($ch) != 0){
				//CURL错误日志
				file_put_contents('./curl_error '.date('Y-m-d H-i-s',$logTime).'.txt', 'CURL请求错误：'.curl_error($ch));
				curl_close($ch);
				exit;
			}
			//处理CURL返回值
			$tokens = explode("\r\n\r\n", trim($res));
            $res = trim(end($tokens));	
			
			//获取$_POST
			$code=C('APP_KEY'); //签名串
			$custom=$_POST['custom'];
			$business=$_POST['business'];//帐号
			$orderSn=$_POST['invoice']; //订单号
			$itemName=$_POST['item_name1'];//商品名称
			$mcCurrency=$_POST['mc_currency']; //交易币种
			$amount=number_format($_POST['mc_gross'],2, '.', '');//总金额
			$payStatus=$_POST['payment_status']; //付款状态 Completed 成功
			$payId=$_POST['txn_id']; // payPal 交易号

			//成功处理	
			if (strcmp ($res, "VERIFIED") == 0) {
				//未交易成功的
				if(strtoupper($payStatus) !='COMPLETED') {
					file_put_contents('./payment_status_error '.date('Y-m-d H-i-s',$logTime).'.txt', '未交易完成，JSON的POST数据：'.json_encode($_POST));
					return false; 
				}
				//签名错误
				if($custom != md5($code.$business.$orderSn.$amount)){
					file_put_contents('./sign_error '.date('Y-m-d H-i-s',$logTime).'.txt', '签名错误，JSON的POST数据：'.json_encode($_POST));
					return false;
				}
				//成功写日志
				//file_put_contents('./strcmp_success '.date('Y-m-d H-i-s',$logTime).'.txt', "操作成功： IPN: $req");				
				//这里执行对订单表的操作
				$model=new PaymentModel();
				$model->paySuccessOrder($orderSn,'Paypal',$payId,0,$mcCurrency);	
				echo('Success');							
			}else if (strcmp ($res, "INVALID") == 0){//失败处理	
					//失败写日志
					file_put_contents('./strcmp_error '.date('Y-m-d H-i-s',$logTime).'.txt', "操作失败,JSON的POST数据".json_encode($_POST));
					echo('fail');						
			}		      	   	   
	 }	

	 //贝宝支付同步跳转通知
	 protected function basicPaypalReturn(){
			//获取 PayPal 交易流水号 tx
			$tx_token = $_GET['tx'];
			if(empty($tx_token)) {
				file_put_contents('./tx_error '.date('Y-m-d H-i-s',time()).'.txt', '交易号是空的');	
				return array();
			}
			$pw=array('pay_code'=>'payPal','enabled'=>1,'is_show'=>1);
			$paypal=M('payment')->field('pay_name,account,key')->where($pw)->find();
			if(empty($paypal)){
				file_put_contents('./paypal_error '.date('Y-m-d H-i-s',time()).'.txt', '数据库找不到paypal帐号信息');	
				return array();
			}
			
			//定义您的身份标记(KEY)
			$auth_token = $paypal['key'];
			//形成验证字符串
			$req = " cmd=_notify-synch&tx=$tx_token&at=$auth_token";
			//将交易流水号及身份标记返回 PayPal 验证
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
			if (!$fp) {
			    // HTTP ERROR
				file_put_contents('./return_http_error '.date('Y-m-d H-i-s',time()).'.txt', 'fsockopen http 错误');		
			} else {
				fputs ($fp, $header . $req);
				//获取返回数据
				$res = '';
				$headerdone = false;
				while (!feof($fp)) {
					$line = fgets ($fp, 1024);
					if (strcmp($line, "\r\n") == 0) {
					      //获取头
					      $headerdone = true;
					}else if ($headerdone){
					      //获取主体内容
					      $res .= $line;
					}
				}
				//解析获取内容
				$lines = explode("\n", $res);
				$keyarray = array();
				if (strcmp ($lines[0], "SUCCESS") == 0) {
					for ($i=1; $i<count($lines);$i++){
						list($key,$val) = explode("=", $lines[$i]);
						$keyarray[urldecode($key)] = urldecode($val);
					}
					//检查交易付款状态 payment_status 是否为 „Completed‟
					if(strtoupper($keyarray['payment_status']) !='COMPLETED') {
						file_put_contents('./return_status_error '.date('Y-m-d H-i-s',time()).'.txt', '未交易完成，fsockopen返回数据：'.json_encode($keyarray));
						return array(); 
					}
					//签名
					$code=C('APP_KEY'); //签名串
					$custom=$keyarray['custom'];
					$business=$keyarray['business']; //商家paypal帐号
					$invoice=$keyarray['invoice']; //订单号
					$item_name=$keyarray['item_name1'];//商品名称
					$mc_gross=number_format($keyarray['mc_gross'],2, '.', '');//总金额
					$mc_currency=$keyarray['mc_currency']; //交易币种
					if($custom != md5($code.$business.$invoice.$mc_gross)){
						file_put_contents('./return_sign_error '.date('Y-m-d H-i-s',time()).'.txt', '签名错误，fsockopen返回数据：'.json_encode($keyarray));
						return array();
					}					
					//这里执行对订单表的操作
					$model=new PaymentModel();
					$model->paySuccessOrder($invoice,'Paypal',$keyarray['txn_id'],0,$mc_currency);	
					fclose ($fp);
					//成功写日志
					//file_put_contents('./return_success '.date('Y-m-d H-i-s',time()).'.txt', json_encode($keyarray));		
					return $this->returnData(true,$invoice,$keyarray['txn_id'],'Paypal',conversio($mc_gross,'USD'),$mc_currency);
				}else if (strcmp ($lines[0], "FAIL") == 0) {
					//失败写日志
					file_put_contents('./return_error '.date('Y-m-d H-i-s',time()).'.txt', $res);		
					fclose ($fp);
					return array();		    
				}
			}
			fclose ($fp);
	 }

     /**公共返回信息
	  * $success 状态
	  * $orderSn  订单号
	  * $trx           支付交易号
	  * $payName  支付平台名称
	  * $amount   订单总金额 
	  * $cur 交易币种
	  * */
     private function returnData($success,$orderSn,$trx,$payName,$amount,$cur){
		if(strpos($orderSn,'CZ') !==false){//在线充值
			$info=array();
			$info['success']=$success;
			$info['cul']=$cur;
			$info['type']='CZ';
			$info['order_sn']=$orderSn;
			$info['order_amount']=$amount;
			$info['pay_num']=$trx;
			$info['pay_name']=$payName;
		}else if(strpos($orderSn,'LE') !==false){ //商家购买等级
			$info=array();
			$info['success']=$success;
			$info['cul']=$cur;
			$info['type']='LE';
			$info['order_sn']=$orderSn;
			$info['order_amount']=$amount;
			$info['pay_num']=$trx;
			$info['pay_name']=$payName;		
		}else{ //普通订单支付
			  $info=array();		
			  $info['type']='ORDER';
			  $info['success']=$success;
			  $info['cul']=$cur;
			  $info['order_sn']=$orderSn;
			  $info['order_amount']=$amount;
			  $info['pay_num']=$trx;
			  $info['pay_name']=$payName;		
		}  
	    return $info;   	
     }	
}