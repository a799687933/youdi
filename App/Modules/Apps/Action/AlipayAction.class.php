<?php
class AlipayAction extends CommonAction {
    
	//支付首页
	public function index(){
        nonlicet_url();
		$tradeSn=$_REQUEST['s'];
		$alipay=$_REQUEST['alipay'];
		$goodsName=$_REQUEST['goods_name'];
		$toAmount=$_REQUEST['amount'];
		$tos="s/".$tradeSn.'/alipay/'.$alipay.'/goods_name/'.$goodsName.'/amount/'.$toAmount;
		$code=md5($tos.C('REQUEST_LOGIN'));		
		if($code !=$_REQUEST['code']) die('Error!');  //判断签名	
		$stm=M('order_shop_trade');
		$st=$stm->field('order_id,pay_status')->where(array('trade_sn'=>$tradeSn))->find();
		if(!$st) die('不存在此数据1！');
		if($st['pay_status'] ==1) die('交易号：('.$tradeSn.') 已支付成功，请不要重复支付！');
		//统计已付金额
		$nums="(SELECT sum(str.amount) AS st_amount FROM ".PREFIX."order_shop_trade AS str WHERE str.order_id='{$st['order_id']}') AS st_amount";
		$sql="SELECT {$nums},o.order_amount,o.shops_id FROM ".PREFIX."order_info AS o ";
		$sql.="WHERE o.order_id='{$st['order_id']}' LIMIT 1 ";
		$order=$stm->query($sql);
		$order=$order[0];
		if(!$order) die('不存在此数据2！');
		if(($order['st_amount']) >($order['order_amount'] * 100)) die('转帐金额错误！');
		//获取用户支付信息
		$shopPay=M('shops')->field('alipay,alipay_id,alipay_key')->where(array('id'=>$order['shops_id'],'alipay'=>$alipay,'display'=>1,'shop_close'=>1))->find();
		if(!$shopPay) die('不存在此商户！');
        $result['account']=$shopPay['alipay'];
		$result['coll_id']=$shopPay['alipay_id'];
		$result['key']=$shopPay['alipay_key'];
		$result['order_sn']=$tradeSn;
		$result['goods_name']=$goodsName;
		$result['order_amount']=$toAmount;
		$this->doDlipay($result);
	}
	
	/****支付宝*****************************************/      
    //提交
    private function doDlipay($arr){
		$path = APP_PATH.'Payment/alipay/';
		require_once($path."alipay_submit.class.php");		
		$alipay_config=$this->alipayConfig($path,$arr);
		        //支付类型
		        $payment_type = "1";
		        //服务器异步通知页面路径(需http://格式的完整路径，不能加?id=123这类自定义参数)
		        $notify_url = "http://".$_SERVER["HTTP_HOST"].__ROOT__."/".APP_APPS."/Alipay/aliNotify.html";		
		        //页面跳转同步通知页面路径(需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/)
		        $return_url =  "http://".$_SERVER["HTTP_HOST"].__ROOT__."/".APP_APPS."/Alipay/aliReturn.html";		        
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

    //服务器异步通知页面路径
    public function aliNotify(){

		    $path = APP_PATH.'Payment/alipay/';
		    require_once($path."alipay_notify.class.php");	
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];			
			//支付宝交易号
			$trade_no = $_POST['trade_no'];			
			//交易状态
			$trade_status = $_POST['trade_status'];				
            //获取商户支付宝资料
			$order=M('order_shop_trade')->field('order_id')->where(array('trade_sn'=>$out_trade_no))->find();
			$sql="SELECT s.alipay,s.alipay_id,s.alipay_key FROM ".PREFIX."shops AS s JOIN ".PREFIX."order_info AS o ON(s.id=o.shops_id) ";
			$sql.="WHERE o.order_id='{$order['order_id']}' LIMIT 1";
			$pay=M()->query($sql);
			$pay=$pay[0];
			$paysArr['account']=$pay['alipay'];
			$paysArr['coll_id']=$pay['alipay_id'];
			$paysArr['key']=$pay['alipay_key'];

		    $alipay_config=$this->alipayConfig($path,$paysArr);
		    //计算得出通知验证结果
		    $alipayNotify = new AlipayNotify($alipay_config);
		    $verify_result = $alipayNotify->verifyNotify();	
			if($verify_result) {//验证成功			
				
				//这里执行对订单表的操作
				 if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
						//这里执行对订单表的操作
						 $where['trade_sn']=$out_trade_no;
						 $save['alipay_sn']=$trade_no;
						 $save['pay_status']=1;
						 M('order_shop_trade')->where($where)->save($save);							
				  }			        
				  echo "success"; //请不要修改或删除,返回给支付宝说明业务已处理
			}else {			   
			      echo "fail";  //验证失败
			}			  	
    }
	
	//页面跳转同步通知页面路径
	public function aliReturn(){
		    $path = APP_PATH.'Payment/alipay/';
		    require_once($path."alipay_notify.class.php");	  
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];			
			//支付宝交易号
			$trade_no = $_GET['trade_no'];			
			//交易状态
			$trade_status = $_GET['trade_status'];
            //获取商户支付宝资料
			$order=M('order_shop_trade')->field('order_id')->where(array('trade_sn'=>$out_trade_no))->find();
			$sql="SELECT s.alipay,s.alipay_id,s.alipay_key FROM ".PREFIX."shops AS s JOIN ".PREFIX."order_info AS o ON(s.id=o.shops_id) ";
			$sql.="WHERE o.order_id='{$order['order_id']}' LIMIT 1";
			$pay=M()->query($sql);
			$pay=$pay[0];
			$paysArr['account']=$pay['alipay'];
			$paysArr['coll_id']=$pay['alipay_id'];
			$paysArr['key']=$pay['alipay_key'];

		    $alipay_config=$this->alipayConfig($path,$paysArr);		
			//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($alipay_config);
			$verify_result = $alipayNotify->verifyReturn();
			if($verify_result) {//验证成功			
				//这里执行对订单的操作		
			    if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
						//这里执行对订单表的操作
						 $where['trade_sn']=$out_trade_no;
						 $save['alipay_sn']=$trade_no;
						 $save['pay_status']=1;
						 $db=M('order_shop_trade');
						 $db->where($where)->save($save);
						 $json='{"statusCode":"200", "message":"支付成功！", "navTabId":"orderShow", "forwardUrl":"", "callbackType":"closeCurrent"}';
			    }else {
			      echo "trade_status=".$trade_status;
			    }						
			}else {
			    //验证失败
			    //如要调试，请看alipay_notify.php页面的verifyReturn函数
			   $json='{"statusCode":"300", "message":"支付失败！", "navTabId":"orderShow", "forwardUrl":"", "callbackType":"closeCurrent"}';
			}		
		   echo '<script>parent.iframeReturn('.$json.');</script>';
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
	 
}