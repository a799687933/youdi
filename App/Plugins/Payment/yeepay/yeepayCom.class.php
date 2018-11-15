<?php
// 易宝支付接口PC加WAP版
class yeepayCom{
	
	/*提交易宝支付*/
	function yeepay($arr){
        $p0_Cmd='Buy';//业务类型，固定值是'Buy'
        $p1_MerId=$arr['account'];//易宝支付商户编号
        $p2_Order=$arr['order_sn'];//订单号
        $p3_Amt=$arr['order_amount'];//支付金额
        $p4_Cur='CNY';//交易币种，固定值是'CNY' 人民币
        $p5_Pid=$arr['order_name'];//订单名称
        $p6_Pcat='';//商品种类
        $p7_Pdesc='';//商品描述
        $p8_Url=$arr['yee_retrun'];//回调地址
        $p9_SAF='0';//送货地址
        $pa_MP='';//商品扩展信息
        $pd_FrpId=$arr['pd_FrpId'];//各种银行的支付通道
        $pr_NeedResponse='1';//应答机制
		$data='';
        $data=$data.$p0_Cmd;
        $data=$data.$p1_MerId;
        $data=$data.$p2_Order;
        $data=$data.$p3_Amt;
        $data=$data.$p4_Cur;
        $data=$data.$p5_Pid;
        $data=$data.$p6_Pcat;
        $data=$data.$p7_Pdesc;
        $data=$data.$p8_Url;
        $data=$data.$p9_SAF;
        $data=$data.$pa_MP;
        $data=$data.$pd_FrpId;
        $data=$data.$pr_NeedResponse;
        $key=$arr['key'];//商户密钥
        $hmac=$this->HmacMd5($data,$key);//mac签名用于验证	
		$get='';
		$get='p0_Cmd='.$p0_Cmd.'&';
		$get.='p1_MerId='.$p1_MerId.'&';
		$get.='p2_Order='.$p2_Order.'&';
		$get.='p3_Amt='.$p3_Amt.'&';
		$get.='p4_Cur='.$p4_Cur.'&';
		$get.='p5_Pid='.$p5_Pid.'&';
		$get.='p6_Pcat='.$p6_Pcat.'&';
		$get.='p7_Pdesc='.$p7_Pdesc.'&';
		$get.='p8_Url='.$p8_Url.'&';
		$get.='p9_SAF='.$p9_SAF.'&';
		$get.='pa_MP='.$pa_MP.'&';
		$get.='pd_FrpId='.$pd_FrpId.'&';
		$get.='pr_NeedResponse='.$pr_NeedResponse.'&';
		$get.='hmac='.$hmac;		
		$urlGet=$arr['yeepay_do_url'].$get;
		header('location:'.$urlGet);//GET提交		
	}
	
	//易宝支付成功返回操作(只有支付成功才会返回，否则不予返回)
	function yeeRetrunInfo($paysArr){	
		$key=$paysArr['key'];//易宝商户密钥	
		$p1_MerId=$paysArr['account']; //易宝商户编号		
		//获取从易宝支付网关返回的信息		 
		$r0_Cmd=$_REQUEST['r0_Cmd']; //业务类型,固定值 ”Buy”.
		$r1_Code=$_REQUEST['r1_Code']; //支付结果,固定值 “1”, 代表支付成功.
		$r2_TrxId=$_REQUEST['r2_TrxId']; //易宝支付交易流水号
		$r3_Amt=$_REQUEST['r3_Amt']; //支付金额
		$r4_Cur=$_REQUEST['r4_Cur']; //交易币种
		$r5_Pid=$_REQUEST['r5_Pid']; //商品名称
		$r6_Order=$_REQUEST['r6_Order']; //商户订单号
		$r7_Uid=$_REQUEST['r7_Uid']; //易宝支付会员ID
		$r8_MP=$_REQUEST['r8_MP']; //商户扩展信息此参数如用到中文，请注意转码.
		$r9_BType=$_REQUEST['r9_BType']; //交易结果返回类型为“1”: 浏览器重定向;为“2”: 服务器点对点通讯.
		$hmac=$_REQUEST['hmac']; //签名串
		//拼接
		$data='';
		$data.=$p1_MerId;
		$data.=$r0_Cmd;
		$data.=$r1_Code;
		$data.=$r2_TrxId;
		$data.=$r3_Amt;
		$data.=$r4_Cur;
		$data.=$r5_Pid;
		$data.=$r6_Order;
		$data.=$r7_Uid;
		$data.=$r8_MP;
		$data.=$r9_BType;	
		//判断签名串
		if($this->HmacMd5($data, $key)==$hmac){
			if($r1_Code==1){//如果支付成功
				$info=array();		
				$info['success']=true;
				$info['order_sn']=$r6_Order;
				$info['pay_num']=$r2_TrxId;
				$info['amount']=$r3_Amt;	
				$info['r9_BType']=$r9_BType;	
				return $info;
			}else{
			    return false;
			}
		}else{
			return false;
		}			
	}
	
	//易宝支付生成加密串
    private function HmacMd5($data,$key){
        $key=iconv('gb2312','utf-8',$key);
        $data=iconv('gb2312','utf-8',$data);
        $b=64;
        if(strlen($key)>$b){
            $key=pack('H*',md5($key));
        }
        $key=str_pad($key,$b,chr(0x00));
        $ipad=str_pad('',$b,chr(0x36));
        $opad=str_pad('',$b,chr(0x5c));
        $k_ipad=$key^$ipad;
        $k_opad=$key^$opad;
        return md5($k_opad.pack('H*',md5($k_ipad.$data)));
    }	
	
}