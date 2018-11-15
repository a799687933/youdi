<?php
class PaymentAction extends CommonAction {
    
	//支付首页
	public function index(){
		$this->res=M('payment')->where(array('is_show'=>1))->order(array('pay_order'=>'ASC'))->select();
        $this->display();
	}
	
    //修改安装支付
    public function exePay(){
    	$res=M('payment')->where(array('pay_id'=>$_GET['pay_id']))->find();
		$this->save=$res;
        $this->display();
    }
	
	//修改安装支表单处理
	public function exePayForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}		
		$wx=array();	
		foreach($_POST as $k=>$v){
			if($k=='account'){
				if($v=='') return_json(300,'你的商户帐号不能为空！');
			}else if($k=='coll_id'){
				if($v=='') return_json(300,'你的合作者身份ID不能为空！');			
			}else if($k=='key'){
				if($v=='') return_json(300,'你的商户密钥不能为空！');			
			}else if($k=='appid'){
				if($v=='') return_json(300,'微信公众号标识(APPID)不能空！');
				$wx['APPID']=trim($v);
			}else if($k=='mchid'){
				if($v=='') return_json(300,'受理商ID(MCHID)不能空！');
				$wx['MCHID']=trim($v);
			}else if($k=='wx_key'){
				if($v=='') return_json(300,'微信支付密钥(KEY)不能空！');
				$wx['KEY']=trim($v);
			}else if($k=='appsecret'){
				if($v=='') return_json(300,'JSAPI接口中获取(APPSECRET)不能空！');
				$wx['APPSECRET']=trim($v);
			}
			$_POST[$k]=trim($v);
		}
        //生成微信支付配置
        if($wx) $this->setWxpayConfig($wx);
		$res=M('payment')->where(array('pay_id'=>$_POST['pay_id']))->save($_POST);
		if($res){
			return_json(200,'操作成功！','index','','closeCurrent');
		}else{
			return_json(300,'修改失败！');
		}
	}
	
	//生成微信支付WxPay.pub.config.php文件
	private function setWxpayConfig($data){
		$filePath=APP_PATH.'Plugins/Payment/WxPayPubHelper/WxPay.pub.config.php';
		$file=file_get_contents($filePath);
		//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
		$APPID=$data['APPID'];
		//受理商ID，身份标识
		$MCHID=$data['MCHID'];
		//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
		$KEY=$data['KEY'];
		//JSAPI接口中获取openid，
		$APPSECRET=$data['APPSECRET'];
		$replaces=array(
						0=>array('rep'=>"/const(\s*)APPID(\s*)\=(\s*)(\'|\")(.*)(\'|\")\;/iU",'value'=>"const APPID = '{$APPID}';"),
						1=>array('rep'=>"/const(\s*)MCHID(\s*)\=(\s*)(\'|\")(.*)(\'|\")\;/iU",'value'=>"const MCHID =  '{$MCHID}';"),
						2=>array('rep'=>"/const(\s*)KEY(\s*)\=(\s*)(\'|\")(.*)(\'|\")\;/iU",'value'=>"const KEY = '{$KEY}';"),
						3=>array('rep'=>"/const(\s*)APPSECRET(\s*)\=(\s*)(\'|\")(.*)(\'|\")\;/iU",'value'=>"const APPSECRET = '{$APPSECRET}';"),
		);
		foreach($replaces as $v){
		  $file=preg_replace($v['rep'],$v['value'],$file);
		}
		file_put_contents($filePath,$file);		
	}

}