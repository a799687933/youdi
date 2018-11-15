<?php
 Class PaymentModel extends Model{
 	private $orderNum;
	private $dealName;
	private $dealNum;
	private $type;
	private $times;
	private $userId;
	private $amount;
	/**
	 * 支付成功对订单表的操作公共接口
	 * $orderNum  订单号
	 * $dealName  支付平台名称
	 * $dealNum    支付平台流水号
	 * $type            支付类型;0,在线支付；1，余额支付；
	**/
	public function paySuccessOrder($orderNum,$dealName,$dealNum,$type){
		$this->orderNum=$orderNum;
		$this->dealName=$dealName;
		$this->dealNum=$dealNum;
		$this->type=$type;
		$this->times=time();		
		if(strpos($this->orderNum,'CZ') !==false){//在线充值
		   return $this->czSuccess();
		}else{ //普通商品订单
			return $this->goSuccess();
		}
    }
    
	//普通订单支付成功操作
	private function goSuccess(){
		$order=M('order_info');
		$filed='order_id,user_id,order_status,pay_status,goods_supplier,goods_amount,order_amount,consignee,surname,address,coun_id,country,province,city,district,tel,zipcode';
		$where=array('order_sn'=>$this->orderNum);
		$res=$order->field($filed)->where($where)->find();
		if($res){//更改订单为已支付状态
			$this->userId=$res['user_id'];
			$this->amount=$res['order_amount'];
			if($res['pay_status'] >=2) return false;
			if(!in_array($res['order_status'],array(0,1))) return false;
			$this->writeLog();
			$this->startTrans(); //启动事务
			$operationTrue=true; //事务状态			
			//修改订单状态	
			if(!$order->where($where)->save(array('pay_status'=>2,'pay_time'=>$this->times))) $operationTrue=false;	
			//写入平台总帐户
			$userName=M('member')->where(array('id'=>$res['user_id']))->getField('user_name');
			$changeData='会员订单在线支付<br/>支付会员：'.$userName.'<br/>单号：'.$this->orderNum.'<br/>支付金额：￥'.$res['order_amount'];
			$changeData.='<br/>支付平台：'.$this->dealName.'<br/>平台交易号：'.$this->dealNum;
			$com=new CommonModel();
			if(!$com->affairsTotal(1,0,$res['order_amount'],$changeData,'在线支付')) $operationTrue=false;	
			//支付成功发邮件
			$this->sendEmailSuccess($res);
			if($operationTrue){
				$this->commit();//成功则提交
			}else{
				$this->rollback();//不成功，则回滚
			}	
			return $operationTrue;
	    }		
	}
   
    //在线充值成功作操
	private function czSuccess(){
	    $ma=M('member_account');
		$where=array('transaction'=>$this->orderNum,'return_mark'=>0);
		$result=$ma->field('id,member_id,transaction,member_money')->where($where)->find();
		if($result){
			$this->userId=$result['member_id'];
			$this->amount=$result['member_money'];
			$this->writeLog();
			$operationTrue=true; //事务状态			
			$this->startTrans(); //启动事务
			//修改支付成功状态
			if(!$ma->where($where)->save(array('return_mark'=>1))) $operationTrue=false;
			//增加会员帐户
			if(!M('member')->where(array('id'=>$result['member_id']))->setInc('member_funds',$result['member_money'])) $operationTrue=false;
			//写入平台总帐户
			$userName=M('member')->where(array('id'=>$result['member_id']))->getField('user_name');
			$changeData='会员在线充值<br/>充值会员：'.$userName.'<br/>单号：'.$result['transaction'].'<br/>充值金额：￥'.$result['member_money'];
			$changeData.='<br/>支付平台：'.$this->dealName.'<br/>平台交易号：'.$this->dealNum;
			$com=new CommonModel();
			if(!$com->affairsTotal(1,1,$result['member_money'],$changeData,'在线支付')) $operationTrue=false;
			if($operationTrue){
				$this->commit();//成功则提交
			}else{
				$this->rollback();//不成功，则回滚
			}
			return $operationTrue;
		}
	}

   //写日志
   private function writeLog(){
			//写入支付日志
			$insetField=array(
				 'order_sn'=>$this->orderNum,
				 'user_id'=>$this->userId,
				 'deal_name'=>$this->dealName,
				 'deal_num'=>$this->dealNum,
				 'order_amount'=>$this->amount,
				 'order_type'=>$this->type,
				 'add_time'=>$this->times
			);
			M('pay_log')->add($insetField);	  	
   }
   
   //支付成功发邮件
   private function sendEmailSuccess($order){
	   $pfix=pfix();
	    //订单商品
		$f="rec_id,{$pfix}goods_name AS goods_name,goods_sn,goods_number,goods_price,goods_attr";
	    $og=M('order_goods')->field($f)->where(array('order_id'=>$order['order_id']))->select();
	    $tr='';
		foreach($og as $k=>$v){
			if($v['goods_attr']) $tempAttrs=json_decode($v['goods_attr'],true);
			else $tempAttrs=array();
			$tr.='<tr><td align="left" style="line-height:40px;">'.show_str($v['goods_name'],10).'</d><td align="left" style="line-height:40px;">'.$v['goods_sn'].'</d>';
			foreach($tempAttrs as $attrv) {
				if((strpos($attrv[$pfix.'name'],'尺码') !==false) || (strpos(strtoupper($attrv[$pfix.'name']),'SIZE') !==false)) {
					$sizeval=$attrv['value'][$pfix.'attr_value']; 
					break;
				}
			}
			$tr.='<td align="left" style="line-height:40px;">'.$sizeval.'</td>';
			$tr.='<td align="left" style="line-height:40px;">'.$v['goods_number'].'</td>';
			$tr.='<td align="left" style="line-height:40px;">'.C('KTEC_H').$v['goods_price'].'</td>';
			$tr.='</tr>';
			$sizeval='';			
		}
		$member=M('member')->field('user_name,surname,full_name')->where("id = '{$order['user_id']}'")->find();
		$tpl='./Public/EmailTpl/addOrder/'.pfix('ddqr').'.html';
		$contents=file_get_contents($tpl);
		$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
		//$contents=str_replace('{$str1}',gmstrftime(" %d %b, %X "),$contents);
		$contents=str_replace('{$str1}',date('Y.m.d H:i',time()),$contents);
		$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name'].' '.$member['surname']),$contents);
		//订单号
		$contents=str_replace('{$order_sn}',$order['order_sn'],$contents);
		//订单日期(只显示英文日期)
		//$contents=str_replace('{$date}',format_date('Y-m-d',time(),'en-us'),$contents);
		$contents=str_replace('{$date}',date('Y.m.d H:i',time()),$contents);
		//姓名
		$contents=str_replace('{$names}',$order['consignee'].' '.$order['surname'],$contents);
		//地址
		$contents=str_replace('{$address}',$order['address'],$contents);
		//国、省、市
		/*$ids=$order['coun_id'].','.$order['province'].','.$order['city'].','.$order['district'];
		$ids=rtrim($ids,',');
		$region=M('region')->field("region_name")->where("region_id IN ({$ids})")->order("parent_id ASC")->select();	
		//国家
		if($region[0]['region_name']) $contents=str_replace('{$country}',$region[0]['region_name'].'<br/>',$contents);
		else $contents=str_replace('{$country}','',$contents);	
		//省份
		if($region[1]['region_name']) $contents=str_replace('{$province}',$region[1]['region_name'].'<br/>',$contents);
		else $contents=str_replace('{$province}','',$contents);	
		//城市
		if($region[2]['region_name']) $contents=str_replace('{$city}',$region[2]['region_name'],$contents);
		else $contents=str_replace('{$city}','',$contents);	
		//区县
		if($region[3]['region_name']) $contents=str_replace('{$district}',$region[3]['region_name'].'<br/>',$contents);		
		else $contents=str_replace('{$district}','',$contents);		*/	
		//国家
		if($order['country']) $contents=str_replace('{$country}',$order['country'].'<br/>',$contents);
		else $contents=str_replace('{$country}','',$contents);	
		//省份
		if($order['province']) $contents=str_replace('{$province}',$order['province'].'<br/>',$contents);
		else $contents=str_replace('{$province}','',$contents);	
		//城市
		if($order['city']) $contents=str_replace('{$city}',$order['city'],$contents);
		else $contents=str_replace('{$city}','',$contents);		
		//区县
		if($order['district']) $contents=str_replace('{$district}',$order['district'].'<br/>',$contents);		
		else $contents=str_replace('{$district}','',$contents);		
		
		//电话
		$contents=str_replace('{$tle}',$order['tel'],$contents);
		//邮编
		$contents=str_replace('{$zip}',$order['zipcode'],$contents);
		//商品信息
		$contents=str_replace('{$goodsList}',$tr,$contents);
		//商品总金额
		$contents=str_replace('{$goods_amount}',$order['goods_amount'],$contents);		
		//总金额
		$contents=str_replace('{$orderAmount}',$order['order_amount'],$contents);
		
		//货币符号
		$contents=str_replace('{$KTEC_H}',C('KTEC_H'),$contents);

		return send_mail($member['user_name'],isL(L('OrderCongs'),'订单确定'),$contents,false);			  
   }
 }
?>