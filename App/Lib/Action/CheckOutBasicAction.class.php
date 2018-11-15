<?php
//公共 结算收银台 
class CheckOutBasicAction extends BasicAction {
    protected $userKey;
	protected $userId=0;
	protected $model;

	//结算页
    protected function basicIndex(){
		 $ids=rtrim(filterRequst($_GET['ids']),',');
		 if(empty($ids)) return array('success'=>false,'msg'=>isL(L('IllegalOperation'),'非法操作'));
		 $result=array();
		 $result['ids']=$ids;
		 //获取会员收货地址
		 if($this->userId > 0){
			 $address=$this->model->getAdderss();	
			 $result['addressList']=$address;			 	
		 }
		 
		 //获取默认物流城市ID
		 if(C('IS_LOGISTICS_TPL')){
			 $cityId=$address[0]['city'];
			 foreach($address as $k=>$v){
				 if($v['default_address']==1){
					$cityId=$v['city'];
				 }
			 }
		 }
		 $cityId=$cityId > 0 ? $cityId : 0;
		 //默认区域检索
		 $result['region']=$this->model->getRegion('','','','');
		 
		 //增值税发票
		 if($this->userId > 0){
			 $vat=M('member_zeng_zhi_inv')->where(array('member_id'=>$this->userId))->find(); 
			 $result['vatRegion']=$this->model->getRegion($vat['country'],$vat['province'],$vat['city'],$vat['district']); 
			 $result['vat']=$vat;			 
			 //普通发票
			 $result['ordinary']=M('member_pu_tong_inv')->where(array('member_id'=>$this->userId))->find(); 
		 }
		 //购物车商品列表
		 $cart=new CartModel();
		 $cartData=$cart->cartList($ids,$cityId);
		 //p($cartData);die;
		 //购物车为空时
		 if($cartData['total'] <= 0) return array('success'=>false,'msg'=>isL(L('NoCommodity'),'购物车中没有商品'));  
		 $result['cartData']=$cartData;

		 //获取会员消费积分
		 if($this->userId > 0){
			 $result['userIntergal']=M('member')->where(array('id'=>$this->userId))->getField('pay_points'); 
			 //积分换算公式(1000积分可对换1元)
			 $result['integralFormula']= (C('INTEGRAL_CONVERSION_RATIO') / 1000); 
		 }
		 return array('success'=>true,'msg'=>'','data'=>$result);
    }
	
	//跟据地址显示物流信息
	public function basicShowLogistics(){
	     $cityId=$_GET['city_id'];
		 $ids=rtrim($_GET['ids'],',');
		 //购物车商品列表
		 $cart=new CartModel();
		 $cartData=$cart->cartList($ids,$cityId);		 
		 if($cartData){
			   $logisitcsId=0;
			   $select='';
			   $i=0;
			   foreach($cartGoods['logisticsFreight'] as $k=>$v){
				   if($i==0) $logisitcsId=getPrice($v['logistics_price'],false);
				   $i++;
				   $select.='<option value="'.$v['shipping_id'].'" price="'.getPrice($v['logistics_price'],false).'">'.$v['shipping_name'].'</option>';
			   }
			   die(json_encode(array('select_str'=>$select,'total_logistics'=>$logisitcsId)));
		 }
	}
	
	//提交订单页
	protected function basicAddOrder(){

	    //购车ID以逗号隔开	
		if($_REQUEST['ids']) $cartId=filterRequst(rtrim($_REQUEST['ids'],','));
        else return array('success'=>false,'msg'=>isL(L('IllegalOperation'),'非法操作')); 
		//现金卷ID
		if($_REQUEST['redId'] > 0) $redId=$_REQUEST['redId'];
		else $redId=0;
		//消费积分
		if($_REQUEST['integral'] > 0) $getPaypoints=$_REQUEST['integral'];
		else $getPaypoints=0;
		//用户所选择的物流公司ID
		$logisticsId=$_REQUEST['logistics'] > 0 ? $_REQUEST['logistics'] : 0; 
		//支付方式;0在线支付;1快递代收;2线下支付;3余额支付;		
		$payType=$_REQUEST['payType'] > 0 ? $_REQUEST['payType'] : 0; 
		//支付评台代码，允许支付方式
		$pay=$_REQUEST['pay'];
		$bids=array('alipay');
		if(!in_array($pay,$bids)) $this->error('error!');		
		
		//发票;0不需要发票;1普通发票;2增值税发票;
		$isInv=$_REQUEST['isInv'] > 0 ? $_REQUEST['isInv'] : 0; 		
		//订单留言
		$orderMessage=$_REQUEST['orderMessage'] ? emptyHtml($_REQUEST['orderMessage']) : ''; 	
	   //会员收货地址ID
	   $addresss=$this->model->getUserAddress();
	   //p($addresss);die;
	   if(empty($addresss['consignee'])) return array('success'=>false,'msg'=>isL(L("NameCanNotBeEmpty"),"收货人姓名不能为空"));
	  // if(empty($addresss['mobile'])) return array('success'=>false,'msg'=>isL(L("HandNumberError"),"手机格式错误"));
	  // if(empty($addresss['country'])) return array('success'=>false,'msg'=>isL(L("SelectCountry"),"请选择国家"));
	  // if(empty($addresss['province'])) return array('success'=>false,'msg'=>isL(L("SelectProvince"),"请选择省份"));
	  // if(empty($addresss['city'])) return array('success'=>false,'msg'=>isL(L("SelectCity"),"请选择城市"));
	 //  if(empty($addresss['address'])) return array('success'=>false,'msg'=>isL(L("AddressCanNotBeEmpty"),"地址不能为空"));
	   /*$selectAdderss=$_REQUEST['selectAdderss'] > 0 ? $_REQUEST['selectAdderss'] : 0;		
	   $addresss=M('member_address')->where(array('address_id'=>$selectAdderss,'user_id'=>$this->userId))->find();
	   if(empty($addresss)) return array('success'=>false,'msg'=>isL(L('Pctrgapca'),'请选择收货地址或创建一个新地址'));*/
		//获取税率
		if($isInv==1) $tax=C('INVOICEORDINARY') > 0 ? C('INVOICEORDINARY') : 0;
		else if($isInv==2) $tax=C('INVOICEVAT') > 0 ? C('INVOICEVAT') : 0;
		else $tax=0;
		//统一时间
		$times=time();
        //购物车数据
        $c=new CartModel();
		$cart=$c->cartList($cartId,0);
		//购物车是空的
		if(!$cart['total']) {
			header('location:'.U('Index/index'));
			die();
		}
		//实际应付金额
		$actualAmount=$cart['total'];
		//满即送
		if($cart['full']['reduction'] > 0){
		    if($cart['full']['dis_type']==1){//折扣优惠
		    	$actualAmount=$actualAmount * $cart['full']['reduction'];
		    }else if($cart['full']['dis_type']==2){//价格减免
		    	$actualAmount=$actualAmount - $cart['full']['reduction'];
		    }
			$full=$cart['full'];
			unset($full['id']);
			unset($full['goods_list']);
			unset($full['list']);
			$cart['discount_data']=json_encode($full);
		}
		//使用消费积分
		if($getPaypoints > 0){
			$getPaypoints=($getPaypoints - $cart['availableIntegral']) > 0 ? $cart['availableIntegral'] : $getPaypoints;
			if($getPaypoints < 1000) return array('success'=>false,'msg'=>isL(L('IntegraLLeastInput'),'积分最少输入('.C('INTEGRAL_LOWEST_POINT').')'));
            //积分换算公式(1000积分可对换1元)
		    $integralFormula= (C('INTEGRAL_CONVERSION_RATIO') / 1000); 	
			$integralAmount=$getPaypoints * $integralFormula;		
			$actualAmount=$actualAmount - $integralAmount;
			$cart['integral_amount']=$cart['availableIntegral'];
			$cart['use_integral']=$getPaypoints;
			$cart['integral_money']=$integralAmount;
		}
		//使用现金卷
		if($redId > 0){
			$resArr=$cart['useRed'][$redId];
			if($resArr){
				if($cart['total'] >= $resArr['min_amount']){
					$actualAmount=$actualAmount - $resArr['bonus_money'];
					$cart['use_red_id']=$resArr['mb_id']; //已使用的现金卷my_member_bonus表ID
					unset($resArr['mb_id']);
					unset($resArr['bonus_type_id']);
					unset($resArr['min_amount']);
					$cart['bonus_data']=json_encode($resArr);
				}
			}
		}
		//物流费用
		if($logisticsId > 0){
			$logist=$cart['logisticsFreight'][$logisticsId];
			if($logist){
				$actualAmount=$actualAmount + $logist['logistics_price'];
				$cart['logistics_id']=$logist['shipping_id']; //物流公司ID
				$cart['freight']=$logist['logistics_price'];
			}
		}
        //总税金
        $taxAmount=$tax * $actualAmount;
		//订单
		$this->model->startTrans(); 
		$orderSn=order_num();
		$order['order_sn']=$orderSn;
		$order['user_id']=$this->userId > 0 ? $this->userId : 0;
		$order['goods_supplier']=0;
		$order['order_status']=1;
		$order['pay_status']=0;
		$order['shipping_status']=0;
		$order['is_inv']=$isInv;
		$order['goods_number']=$cart['goods_num'];
		$order['goods_amount']=$cart['total'];
		$order['freight']=$cart['freight'] ? $cart['freight'] : 0;
		$order['order_amount']=$actualAmount + $taxAmount;
		$order['add_time']=$times;
		$order['confirm_time']=0;
		$order['pay_time']=0;
		$order['shipping_time']=0;
		$order['send_inv_time']=0;
		$order['tax']=$tax;
		$order['taxes']=$taxAmount;
		$order['surname']=$addresss['surname'] ? $addresss['surname'] : '';
		$order['consignee']=$addresss['consignee'] ? $addresss['consignee'] : '';
		$order['coun_id']=$addresss['coun_id'] ? $addresss['coun_id'] : 0;
		$order['country']=$addresss['country'] ? $addresss['country'] : '';
		$order['province']=$addresss['province'] ? $addresss['province'] : '';
		$order['city']=$addresss['city'] ? $addresss['city'] : 0;
		$order['district']=$addresss['district'] ? $addresss['district'] : '';
		$order['address']=$addresss['address'] ? $addresss['address'] : '';
		$order['zipcode']=$addresss['zipcode'] ? $addresss['zipcode'] : '';
		$order['tel']=$addresss['tel'] ? $addresss['tel'] : '';
		$order['mobile']=$addresss['mobile'] ? $addresss['mobile'] : '';
		$order['email']=$addresss['email'] ? $addresss['email'] : '';
		$order['pay_type']=$payType;
		$order['pay_code']=$pay;
		$order['integral_amount']=$cart['availableIntegral'] ? $cart['availableIntegral'] : 0;
		$order['pay_points']=$cart['sendingPay'] ? $cart['sendingPay'] : 0;
		$order['rank_points']=$cart['sndingRank'] ? $cart['sndingRank'] : 0;
		$order['use_integral']=$cart['use_integral'] ? $cart['use_integral'] : 0;
		$order['integral_money']=$cart['integral_money'] ? $cart['integral_money'] : 0;
		$order['bonus_sn']=$cart['use_red_id'] ? $cart['use_red_id'] : 0;
		$order['bonus_type_id']=$cart['availablelntRed']['id'] > 0 ? $cart['availablelntRed']['id'] : 0;
		$order['order_message']=$orderMessage ? $orderMessage : '';
		$order['refund_reason']='';
		$order['to_buyer']='';
		$order['logistics_sn']='';
		$order['logistics_id']=$cart['logistics_id'] ? $cart['logistics_id'] : 0;
		$order['discount_data']=$cart['discount_data'] ? $cart['discount_data'] : '';
		$order['bonus_data']=$cart['bonus_data'] ? $cart['bonus_data'] : '';
		$orderId=M('order_info')->add($order);
		if(empty($orderId)){
			$this->model->rollback();
			return array('success'=>false,'msg'=>'写入订单失败');
		}
		//订单商品
		$og=array();
		$langs=langAllField('goods_name');	 
		$langPfix=langAllField(C('CART_REC_INFO'));	 
		$orderGoodsName='';
		foreach($cart['goods_list'] as $k=>$v){
			$orderGoodsName.=$v[pfix('goods_name')].'|';
			$recInfo=array();
			foreach($langPfix['lang_pfix'] as $px) $recInfo[$px.C('CART_REC_INFO')] =$v[$px.C('CART_REC_INFO')];
			$og[$k]['order_id']=$orderId;
			$og[$k]['goods_brand_id']=$v['goods_brand_id'];
			$og[$k]['goods_id']=$v['goods_id'];
			$og[$k]['goods_sn']=$v['goods_sn'];
			foreach($langs['lang_arr'] as $lang) $og[$k][$lang]=$v[$lang];
			$og[$k]['volume_m3']=$v['volume_m3'];
			$og[$k]['goods_weight']=$v['goods_weight'];
			$og[$k]['goods_thumb']=$v['goods_thumb'];
			$og[$k]['goods_number']=$v['goods_number'];
			$og[$k]['market_price']=$v['market_price'];
			$og[$k]['goods_price']=$v['goods_price'];
			$og[$k]['goods_attr']=$v['goods_attr'] ? json_encode($v['goods_attr']) : '';
			$og[$k]['goods_attr_ids']=$v['goods_attr_ids'] ? $v['goods_attr_ids'] : '';
			$og[$k]['is_real']=0;
			$og[$k]['is_shipping']=0;
			$og[$k]['is_gift']=0;
			$og[$k]['rec_type']=$v['rec_type'];
			$og[$k]['rec_info']=$recInfo ? json_encode($recInfo) : '';
			$og[$k]['times']=$times;
			$og[$k]['is_reviews']=0;			
		    //修改商品销量和库存数组
		    $goods[$v['goods_id']]=array('id'=>$v['goods_id'],'stock'=>$v['goods_number'],'volume'=>$v['goods_number']);
		    //修改商品属性库存数组
		    if($v['goods_attr_ids']) $goodsAttr[$v['goods_id']]=array('num'=>$v['goods_number'],'ids'=>$v['goods_attr_ids']);		
			
			//邮件商品信息
			if($og[$k]['goods_attr']) $tempAttrs=json_decode($og[$k]['goods_attr'],true);
			else $tempAttrs=array();
			$tr.='<tr><td align="left" style="line-height:40px;">'.$v[pfix('goods_name')].'</d><td align="left" style="line-height:40px;">'.$v['goods_sn'].'</d>';
			foreach($tempAttrs as $attrv) {
				if($attrv[pfix('name')]=='尺码' || strtoupper($attrv[pfix('name')]=='SIZE')) {
					$sizeval=$attrv['value'][pfix('attr_value')]; 
					break;
				}
			}
			$tr.='<td align="left" style="line-height:40px;">'.$sizeval.'</td>';
			$tr.='<td align="left" style="line-height:40px;">'.$v['goods_number'].'</td>';
			$tr.='<td align="right" style="line-height:40px;">'.C('KTEC_H').$v['goods_price'].'</td>';
			$tr.='</tr>';
			$sizeval='';
		}
        //加销量减库存
        if(($goods || $goodsAttr) && C('CHECK_STOCK')){
        	$this->model->updateMoerGoodsAttrs(true,$goodsAttr); //处理商品属性库存
        	$this->model->updateMoerGoods(true,$goods);	//处理商品库存与销量
        }
		
		//写入订单商品表
		if(!M('order_goods')->addAll($og)){
			$this->model->rollback();
			//重新计算购物车数量
			$c->getCartCount();
			return array('success'=>false,'msg'=>'写入订单商品失败');			
		}
        //已使用消费积分
        if($cart['use_integral'] > 0 && $this->userId > 0){
        	if(!M('member')->where(array('id'=>$this->userId))->setDec('pay_points',$cart['use_integral'])){
        		$this->model->rollback();
				return array('success'=>false,'msg'=>'更新会员积分失败');		
        	}			
        }
		//已使用现金卷
		if($cart['use_red_id'] > 0 && $this->userId > 0){
			if(!M('member_bonus')->where(array('id'=>$cart['use_red_id']))->save(array('used_time'=>$times,'order_id'=>$orderId))){
        		$this->model->rollback();
				return array('success'=>false,'msg'=>'处理已使用现金卷失败');					
			}
		}
		//删除购物车商品
		if($this->userKey) $cw="(goods_id IN ({$cartId}) OR zhuhe_goods_id IN ({$cartId})) AND (user_id='{$this->userId}' OR user_key='{$this->userKey}')";
		else $cw="(goods_id IN ({$cartId}) OR zhuhe_goods_id IN ({$cartId})) AND user_id='{$this->userId}'";
		if(!M('cart')->where($cw)->delete()){
			$this->model->rollback();
			return array('success'=>false,'msg'=>'删除购物车商品失败');
		}
		//能到这里就成功提交事务
        $this->model->commit();
		$parr['order_sn']=$order['order_sn'];
		$parr['order_time']=$order['add_time'];
		$parr['order_goods']=rtrim($orderGoodsName,'|');
		$parr['pay']=$pay;
		$url=get_up_url($parr,U('Payment/index','',''),true);		
						
		
		//发邮件
		$member=M('member')->field('user_name,full_name')->where("id = '{$this->userId}'")->find();
		$tpl='./Public/EmailTpl/addOrder/'.pfix('ddqr').'.html';
		$contents=file_get_contents($tpl);
		$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
		$contents=str_replace('{$str1}',date('Y/m/d',time()),$contents);
		$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name']),$contents);
		//订单号
		$contents=str_replace('{$order_sn}',$order['order_sn'],$contents);
		//订单日期(只显示英文日期)
		$contents=str_replace('{$date}',date('Y/m/d',time()),$contents);
		//姓名
		$contents=str_replace('{$names}',$addresss['consignee'].' '.$addresss['surname'],$contents);
		//国、省、市
		if($addresss['country']) $contents=str_replace('{$country}',$addresss['country'],$contents);
	    //省份	
		if($addresss['province']) $contents=str_replace('{$province}',$addresss['province'],$contents);
	    //城市	
		if($addresss['city']) $contents=str_replace('{$city}',$addresss['city'],$contents);		
	    //区县	
		$contents=str_replace('{$district}',$addresss['district'] ? $addresss['district'] : '',$contents);				
		//地址
		$contents=str_replace('{$address}',$addresss['address'],$contents);
		/*//国、省、市
		$ids=$addresss['country'] .','.$addresss['province'].','.$addresss['city'].','.$addresss['district'];
		$region=M('region')->field("region_name")->where("id IN ({$ids})")->order("parent_id ASC")->select();		
		//国家
		if($region[0]) $contents=str_replace('{$country}',$region[0].'<br/>',$contents);
		else $contents=str_replace('{$country}','',$contents);	
		//省份
		if($region[1]) $contents=str_replace('{$province}',$region[1].'<br/>',$contents);
		else $contents=str_replace('{$province}','',$contents);	
		//城市
		if($region[2]) $contents=str_replace('{$city}',$region[2],$contents);
		else $contents=str_replace('{$city}','',$contents);		
		//区县
		if($region[3]) $contents=str_replace('{$district}',$region[3].'<br/>',$contents);		
		else $contents=str_replace('{$district}','',$contents);	*/	
		
		
		//电话
		$contents=str_replace('{$tle}',$addresss['tel'],$contents);
		//邮编
		$contents=str_replace('{$zip}',$addresss['zipcode'],$contents);
		//商品信息
		$contents=str_replace('{$goodsList}',$tr,$contents);
		//商品总金额
		$contents=str_replace('{$goods_amount}',$order['goods_amount'],$contents);		
		//总金额
		$contents=str_replace('{$orderAmount}',$order['order_amount'],$contents);
		
		//货币符号
		$contents=str_replace('{$KTEC_H}',C('KTEC_H'),$contents);
        
		send_mail2($member['user_name'],isL(L('OrderCongs'),'订单确定'),$contents);
		
		//$result=send_mail($member['user_name'],isL(L('OrderCongs'),'订单确定'),$contents,false);				
		
		
		//跳转支付
		header("location:{$url}");
		//提交支付数组
	   /* $payArr['order_sn']=$orderSn;
		$payArr['goods_amount']=$order['goods_amount'];
		$payArr['order_amount']=$order['order_amount'];
	    $payArr['order_time']=$times;
	    $payArr['order_goods']=rtrim($orderGoodsName,'|');
		$payArr['consignee']=$addresss['consignee'] ? $addresss['consignee'] : '';
		$payArr['country']=$addresss['country'] ? $addresss['country'] : 0;
		$payArr['province']=$addresss['province'] ? $addresss['province'] : 0;
		$payArr['city']=$addresss['city'] ? $addresss['city'] : 0;
		$payArr['district']=$addresss['district'] ? $addresss['district'] : 0;
		$payArr['address']=$addresss['address'] ? $addresss['address'] : '';
		$payArr['zipcode']=$addresss['zipcode'] ? $addresss['zipcode'] : '';
		$payArr['tel']=$addresss['tel'] ? $addresss['tel'] : '';
		$payArr['mobile']=$addresss['mobile'] ? $addresss['mobile'] : '';
		$payArr['email']=$addresss['email'] ? $addresss['email'] : '';
        return array('success'=>true,'msg'=>'','pay_type'=>$payType,'data'=>$payArr);*/
	}
	
	//选择线下支付提示页
	protected function basicBelowLine(){
		 $this->data=$this->publicData();	
		 //银行列表
		 $this->belowLine=M('below_line')->where(array('is_show'=>1))->order(array('bank_orders'=>'ASC'))->select();
		 $this->display();
	}
	
	//选择在线支付提示页
	protected function basicPaySelect(){
		 $data=$this->publicData();
		 if(empty($data['success'])) return $data;
		 $orders['orders']=$data['data'];
		 //获取支付方式
		 $orders['payment']=M('payment')->where(array('enabled'=>1,'is_show'=>1))->order('pay_id ASC')->select();
		 return array('success'=>true,'data'=>$orders);
	}

    //选择余额支付提示页
    protected function basicBalance(){
    	$this->data=$this->publicData();
		$this->display(); 
    }
	
	//选择积分对换
	protected function basicIntegralTrade(){
    	$this->data=$this->publicData();
		$this->display(); 		
	}	
	
	//支付提示公用数据
	private function publicData(){
         $data=$_REQUEST;
		 unset($data['sign']);
		 unset($data['_URL_']);
		 unset($data['random']);
		 $times=time();
		 if(($times - $data['order_time']) > C('ORDERRETAIN')) return array('success'=>false,'msg'=>isL(L('OrderHasAxpired'),'订单已过期'));
		 $cityId.=$data['country'] ? $data['country'].',' : '';
		 $cityId.=$data['province'] ? $data['province'].',' : '';
		 $cityId.=$data['city'] ? $data['city'].',' : '';
		 $cityId.=$data['district'] ? $data['district'].',' : '';
		 $cityId=rtrim($cityId,',');
		 $region=M('region')->field('region_name')->where("region_id IN({$cityId})")->order('region_id ASC')->select();
		 $data['country']=$region[0]['region_name'] ? $region[0]['region_name'] : '';
		 $data['province']=$region[1]['region_name'] ? $region[1]['region_name'] : '';
		 $data['city']=$region[2]['region_name'] ? $region[2]['region_name'] : '';
		 $data['district']=$region[3]['region_name'] ? $region[3]['region_name'] : '';		
		 return array('success'=>true,'msg'=>'','data'=>$data);
	}

}