<?php
/**
 *支付宝WAP支付类
 */
class alipay_wap{
	
	var $alipay_config;
	/**
	 *支付宝网关地址
	 */
	var $alipay_gateway_new = 'http://wappaygw.alipay.com/service/rest.htm?';
	/**
     * HTTPS形式消息验证地址
     */
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
     * HTTP形式消息验证地址
     */
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	
	/**
	*当前类文件路径
	*/
	var $alipay_wap_path;
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */

    function __construct(){
        $this->alipay_wap_path=APP_PATH.'Plugins/Payment/alipay_wap/';
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment){

        //字符集
		$charset = 'utf-8';
		//合作身份者id，以2088开头的16位纯数字
		$this->alipay_config['partner']		= $payment['alipay_partner'];

		//收款支付宝帐户
		$this->alipay_config['seller_email']	= $payment['alipay_account'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$this->alipay_config['key']			= $payment['alipay_key'];
				
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$this->alipay_config['sign_type']    = 'MD5';
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$this->alipay_config['input_charset'] = $charset;
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中(MD5验证忽略此项)
		$alipay_config['cacert']    = $this->alipay_wap_path.'cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$this->alipay_config['transport']    = $payment['is_http'];
		
		
		//返回格式
		$format = "xml";
		//必填，不需要修改

		//返回格式
		$v = "2.0";
		//必填，不需要修改

		//请求号
		$req_id = date('Ymdhis');
		//必填，须保证每次请求都是唯一

		//**req_data详细信息**

		//服务器异步通知页面路径
		$notify_url =$payment['notify_url']; //str_replace('mobile/', '', return_url(basename(__FILE__, '.php')));
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//页面跳转同步通知页面路径
		$call_back_url =$payment['call_back_url']; //str_replace('mobile/', '', return_url(basename(__FILE__, '.php')));
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//操作中断返回地址
		$merchant_url = $payment['exit_url']; //$GLOBALS['ecs']->url();
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

		//卖家支付宝帐户
		$seller_email = $payment['alipay_account'];
		//必填

		//商户订单号
		$out_trade_no = $order['order_sn'];
		//商户网站订单系统中唯一订单号，必填

		//订单名称
		$subject = $order['order_name'] ? $order['order_name'] : $order['order_sn'];
		//必填

		//付款金额
		$total_fee = $order['order_amount'];
		//必填

		//请求业务参数详细
		$req_token_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => $payment['alipay_partner'],
				"sec_id" => 'MD5',
				"format"	=> 'xml',
				"v"	=> '2.0',
				"req_id"	=> date('Ymdhis'),
				"req_data"	=> $req_token_data,
				"_input_charset"	=> $charset
		);
		
		//建立请求
		$html_text = $this->buildRequestHttp($para_token);
		$html_text = urldecode($html_text);
		
		//解析远程模拟提交后返回的信息
		$para_html_text = $this->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];
		
        /**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($this->alipay_config['partner']),
				"sec_id" => trim($this->alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
		);

		//建立请求
		
		$html_text = $this->buildRequestForm($parameter, 'get', '请点击此按钮继续');
		return $html_text;
    }
	
	/**
     * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
     * @param $para_temp 请求参数数组
     * @return 支付宝处理结果
     */
	function buildRequestHttp($para_temp) {
		$sResult = '';
		
		//待请求参数数组字符串
		$request_data = $this->buildRequestPara($para_temp);

		//远程获取数据
		$sResult = $this->getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'],$request_data,trim(strtolower($this->alipay_config['input_charset'])));

		return $sResult;
	}
	
	/**
	 * 远程获取数据，POST模式
	 * 注意：
	 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
	 * @param $url 指定URL完整路径地址
	 * @param $cacert_url 指定当前工作目录绝对路径
	 * @param $para 请求的数据
	 * @param $input_charset 编码格式。默认值：空值
	 * return 远程输出的数据
	 */
	function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {
	
		if (trim($input_charset) != '') {
			$url = $url."_input_charset=".$input_charset;
		}
		
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl,CURLOPT_POST,true); // post传输数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}
	
	/**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
	function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		
		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);
		
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		if($para_sort['service'] != 'alipay.wap.trade.create.direct' && $para_sort['service'] != 'alipay.wap.auth.authAndExecute') {
			$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
		}
		
		return $para_sort;
	}
	
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function buildRequestMysign($para_sort) {
		//把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		
		return $this->md5Sign($prestr, $this->alipay_config['key']);
	}
	
	/**
	 * 签名字符串
	 * @param $prestr 需要签名的字符串
	 * @param $key 私钥
	 * return 签名结果
	 */
	function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5($prestr);
	}
	
	/**
	 * 把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		//file_put_contents('00AA00.txt', $arg."\n\r", FILE_APPEND);
		
		return $arg;
	}
	
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $key == "code" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	
	/**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
	function buildRequestForm($para_temp, $method, $button_name) {
		//待请求参数数组
		
		
		$para = $this->buildRequestPara($para_temp);
		
		$sHtml = '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;"><form id="alipaysubmit" name="alipaysubmit" action="'.$this->alipay_gateway_new.'_input_charset='.trim(strtolower($this->alipay_config['input_charset'])).'" method="'.$method.'">';
		while (list ($key, $val) = each ($para)) {
            $sHtml.= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
        }

		//submit按钮控件请不要含有name属性
        $sHtml = $sHtml.'<input type="submit" value="'.$button_name.'" style="padding:10px 20px"></form>';
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		return $sHtml;
	}
	
	/**
     * 解析远程模拟提交后返回的信息
	 * @param $str_text 要解析的字符串
     * @return 解析结果
     */
	function parseResponse($str_text) {
		//以"&"字符切割字符串
		
		$para_split = explode('&',$str_text);
		//把切割后的字符串数组变成变量与数值组合的数组
		foreach ($para_split as $item) {
			//获得第一个=字符的位置
			$nPos = strpos($item,'=');
			//获得字符串长度
			$nLen = strlen($item);
			//获得变量名
			$key = substr($item,0,$nPos);
			//获得数值
			$value = substr($item,$nPos+1,$nLen-$nPos-1);
			//放入数组中
			$para_text[$key] = $value;
		}
		
		if( ! empty ($para_text['res_data'])) {
			
			//token从res_data中解析出来（也就是说res_data中已经包含token的内容）
			$doc = new DOMDocument();
			$doc->loadXML($para_text['res_data']);
			$para_text['request_token'] = $doc->getElementsByTagName( "request_token" )->item(0)->nodeValue;
		}
		
		return $para_text;
	}
	
	//服务器异步通知处理，成功返回数组信息否则返回false
	function aliNotify($payment){
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		//合作身份者id，以2088开头的16位纯数字
		$this->alipay_config['partner']		= $payment['alipay_partner'];
		//收款支付宝帐户
		$this->alipay_config['seller_email']	= $payment['alipay_account'];
		$this->alipay_config['key']			= $payment['alipay_key'];
		$this->alipay_config['sign_type']			= 'MD5';	
		$verify_result = $this->verifyNotify();		
		if($verify_result){//签名验证成功
			$doc = new DOMDocument();
			$doc->loadXML($_POST['notify_data']);
			//商户网站唯一订单号
			$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
			//支付宝交易号
			$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
			//交易金额
			$total_fee = $doc->getElementsByTagName( "total_fee" )->item(0)->nodeValue;
			//交易状态
			$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;		
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
				return array('out_trade_no'=>$out_trade_no,'trade_no'=>$trade_no,'total_fee'=>$total_fee,'trade_status'=>$trade_status);
			}else{
				return false;
			}
		}else{
		    return false;
		}
	}

	//页面跳转同步通知页面
    function aliReturn($payment){
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		//合作身份者id，以2088开头的16位纯数字
		$this->alipay_config['partner']		= $payment['alipay_partner'];
		//收款支付宝帐户
		$this->alipay_config['seller_email']	= $payment['alipay_account'];
		$this->alipay_config['key']			= $payment['alipay_key'];
		$this->alipay_config['sign_type']			= 'MD5';			
		$verify_result = $this->verifyReturn();
		if($verify_result){ //签名验证成功
		    if(($_GET['trade_status']=='TRADE_SUCCESS') || ($_GET['trade_status']=='TRADE_SUCCESS')){
				return array('out_trade_no'=>$_GET['out_trade_no'],'trade_no'=>$_GET['trade_no'],'total_fee'=>$_GET['total_fee'],'trade_status'=>$_GET['trade_status']);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
	function verifyReturn(){
		if(empty($_GET)) {//判断GET来的数组是否为空
			return false;
		}
		else {
			//生成签名结果
			unset($_GET['_URL_']);
			$isSign = $this->getSignVeryfy($_GET, $_GET["sign"],true);
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if ($isSign) {
				return true;
			} else {
				//签名错误写日志
				$errorLog="$_GET数据：".json_encode($_GET);
				file_put_contents($this->alipay_wap_path.'get_sign_error'.date('Y-m-d H-i-s').'.txt', $errorLog);				
				return false;
			}
		}
	}
	
	function verifyNotify(){
		if(empty($_POST)) {//判断POST来的数组是否为空
			return false;
		}
		else {
			
			//对notify_data解密
			$decrypt_post_para = $_POST;
			
			//notify_id从decrypt_post_para中解析出来（也就是说decrypt_post_para中已经包含notify_id的内容）
			$doc = new DOMDocument();
			$doc->loadXML($decrypt_post_para['notify_data']);
			$notify_id = $doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;
			
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (! empty($notify_id)) {$responseTxt = $this->getResponse($notify_id);}
			
			//生成签名结果
			
			$isSign = $this->getSignVeryfy($decrypt_post_para, $_POST["sign"],false);
			
			//写日志记录
			if ($isSign) {
				$isSignStr = 'true';
			}else {
				$isSignStr = 'false';
			}
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关			
			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				//签名错误写日志
				$errorLog="responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr."\r\n";	
				$errorLog.="$_POST数据：".json_encode($_POST);
				file_put_contents($this->alipay_wap_path.'post_sign_error'.date('Y-m-d H-i-s').'.txt', $errorLog);
				return false;
			}
		}
	}
	
	/**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
	function getResponse($notify_id) {
		$transport = strtolower(trim($this->alipay_config['transport']));
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}
		
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);
		
		return $responseTxt;
	}
	
	/**
	 * 远程获取数据，GET模式
	 * 注意：
	 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
	 * @param $url 指定URL完整路径地址
	 * @param $cacert_url 指定当前工作目录绝对路径
	 * return 远程输出的数据
	 */
	function getHttpResponseGET($url,$cacert_url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}
	
	/**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @param $isSort 是否对待签名数组排序
     * @return 签名验证结果
     */
	function getSignVeryfy($para_temp, $sign, $isSort) {
		
		//除去待签名参数数组中的空值和签名参数
		$para = $this->paraFilter($para_temp);
		
		//对待签名参数数组排序
		if($isSort) {
			$para = $this->argSort($para);
		} else {
			$para = $this->sortNotifyPara($para);
		}
		
		//把数组所有元素，按照"参数=参数值"的模式用"&"字符拼接成字符串
		$prestr = $this->createLinkstring($para);
		
		return $this->md5Verify($prestr, $sign, $this->alipay_config['key']);
	}
	
	/**
     * 异步通知时，对参数做固定排序
     * @param $para 排序前的参数组
     * @return 排序后的参数组
     */
	function sortNotifyPara($para) {
		$para_sort['service'] = $para['service'];
		$para_sort['v'] = $para['v'];
		$para_sort['sec_id'] = $para['sec_id'];
		$para_sort['notify_data'] = $para['notify_data'];
		return $para_sort;
	}
	
	/**
	 * 验证签名
	 * @param $prestr 需要签名的字符串
	 * @param $sign 签名结果
	 * @param $key 私钥
	 * return 签名结果
	 */
	function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);
	
		if($mysgin == $sign) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * RSA验签
	 * @param $data 待签名数据
	 * @param $ali_public_key_path 支付宝的公钥文件路径
	 * @param $sign 要校对的的签名结果
	 * return 验证结果
	 */
	function rsaVerify($data, $ali_public_key_path, $sign)  {
		$pubKey = file_get_contents($ali_public_key_path);
		$res = openssl_get_publickey($pubKey);
		$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		openssl_free_key($res);    
		return $result;
	}
}

?>
