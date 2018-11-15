<?php
class CartModel extends Model{
	private $userId=0;
	private $userKey;//支持未登录用户购物
	public function __construct(){
		parent::__construct(); 
		$uId=getUserC('SE_USER_ID');
		$this->userId=$uId ? $uId :-1;
		$key=return_key(); 
		$this->userKey=$key ? $key : '';
	}
	
    /*购物车列表**/
    public function cartList($goodsIds='',$cityId=0){
		 if($this->userKey) $cw=" (user_id='{$this->userId}' OR user_key='{$this->userKey}') "; 
		 else $cw=" user_id='{$this->userId}' "; 
		 if($goodsIds){
			 $goodsIds=filterRequst($goodsIds);
			 $cw.=" AND (goods_id IN({$goodsIds}) OR zhuhe_goods_id IN({$goodsIds})) ";
		 }
		 $result=$this->where($cw)->order("logistics_tpl_id ASC")->order(array('goods_id'=>'ASC'))->select();
		 $data=array();
		 $tplId=$result[0]['logistics_tpl_id'];
		 $tplArr=array();	 
		 $total=0;//商品总计
		 $availableIntegral=0;//商品可使用积分
		 $sendingPay=0; //增送消费积分
		 $sndingRank=0; //增送等级积分
		 $availbalelntRed=0; //可使用现金额
		 $goods_nums=0; //商品个数
		 //商品名称多语言
		 $langField=langAllField('goods_name');
		 //优惠信息多语言
		 $typeRec=langAllField(C('CART_REC_INFO'));
		 $overdue=0;
		 $times=time();
		 $cartArr=array();
		 $attrids='';
		 foreach($result as $key=>$val){
		 	//过滤超时购物车
		 	if(($times - $val['times']) >= C('CARTRETAIN')){
			    $overdue++;
		 	}else{
				 //商品ID
				 $goods_id.=$val['goods_id'].',';
				 //属性ID集合转换成数组
				 $attrids.=$val['goods_attr_ids'];
	             //商品属性
				 $result[$key]['goods_attr']=$val['goods_attr'] ? json_decode($val['goods_attr'],true) : array();
				 //商品小计
				 $result[$key]['subtotal']=$val['goods_number'] * $val['goods_price'];
				 //商品总计
				 $total+=$result[$key]['subtotal'];
				 $goods_nums+=$val['goods_number'];
				 //可使用现金额
				 if($val['is_discount'] > 0) $availbalelntRed+=$result[$key]['subtotal'];
				 //商品可使用积分
				 $availableIntegral+=$val['goods_number'] * $val['integral_amount'];
				 //增送消费积分
				 $sendingPay+=$val['goods_number'] * $val['pay_points'];
				 //增送等级积分 
				 $sndingRank+=$val['goods_number'] * $val['rank_points'];
				 //物流数组
				 if($tplId==$val['logistics_tpl_id']){
				     $tplArr[$tplId]['logistics_tpl_id']=$val['logistics_tpl_id'];
					 $tplArr[$tplId]['num']+=$val['goods_number'];
					 $tplArr[$tplId]['goods_weight']+=$val['goods_weight'];
					  $tplArr[$tplId]['volume_m3']+=$val['volume_m3'];
				 }else{
					 $tplId=$val['logistics_tpl_id'];
				     $tplArr[$tplId]['logistics_tpl_id']=$val['logistics_tpl_id'];
					 $tplArr[$tplId]['num']+=$val['goods_number'];
					 $tplArr[$tplId]['goods_weight']+=$val['goods_weight'];
					 $tplArr[$tplId]['volume_m3']+=$val['volume_m3'];
					 $i++;
				 }	
				 //已过滤的购物车商品
				 $cartArr[$key]=	$result[$key];				 		
		 	 }
		 }	
		// p($cartArr);die;
        unset($result);
		$data['goods_num']=$goods_nums;
        $data['total']=$total;
		$data['availableIntegral']=$availableIntegral;
		$data['sendingPay']=$sendingPay;
		$data['sndingRank']=$sndingRank;
        //送现金卷
		if(C('IS_BONUS'))  $data['availablelntRed']=$this->giveRed($data['total']);
		//满即送活动
		if(C('IS_dISCOUNT')) $data['full']=$this->fullFree($data['total']);

		//获取用户可使用现金卷
		if(C('IS_BONUS')) if($availbalelntRed > 0) $data['useRed']=$this->getUseRed($data['total']);

	   //获得物流信息(只对中国语言有效)
	   if(($cityId > 0) && C('IS_LOGISTICS_TPL')){
			if($tplArr) $data['logisticsFreight']=$this->logistics($tplArr,$cityId);
	   }
	   $data['goods_list']=$cartArr;
	   //重算购物车个数
	   if($overdue > 0) $this->getCartCount($overdue,false);
	   
	   //获得商品属性
	   $goods_id=rtrim($goods_id,',');
	   $am=new  GoodsBasicAction();
	   $goods_attr=$am->getGoodsAttr($this->db,$goods_id);
	   $data['goods_attrs']=$goods_attr;
	   
	   //获得已先选择属性数组
	   $attrids=rtrim($attrids,',');
	   $sql="SELECT av.id,av.".pfix('attr_value')." AS attr_value,ga.id AS ga_id FROM ".PREFIX."goods_attribute_value AS av JOIN ";
	   $sql.=PREFIX."goods_attr AS ga ";
	   $sql.="ON (av.id=ga.attribute_value_id) WHERE ga.id IN ({$attrids})";
	   $av=$this->db->query($sql);
	   //p($data['goods_attrs']);
	   //p($av);die;
	   $data['select_attr']=$av;
       return $data;
    }

    /**
	 * 计算物流费
	 * $arr      物流模板ID商品个数和重量和体积
	 * $cityId  城市ID
	 * */
    public function logistics($arr,$cityId){
    	$allArray=array();
		$totals=0;
    	foreach($arr as $k=>$v){
		 //必须有模板ID才算运费	
		 if($v['logistics_tpl_id'] > 0){	
    		$field1="s.shipping_name,s.shipping_id,t.free_shipping,t.pricing,t.default_number,t.default_price,t.add_number,t.add_price";
			$field2="p.shipping_id,p.city_id,p.pricing AS p_pricing,p.first_number,p.first_price,p.add_numeber_moer,p.add_price_moer";
    		$sql="SELECT {$field1},{$field2} FROM ".PREFIX."logistics_tpl AS t LEFT JOIN ".PREFIX."logistics_tpl_price AS p ON(p.tpl_id=t.id) LEFT JOIN ";
			$sql.=PREFIX."shipping AS s ON(s.shipping_id=p.shipping_id) WHERE t.id={$v['logistics_tpl_id']} ORDER BY p.shipping_id ASC";
			$result=$this->db->query($sql);
			$sql='';
			$shippingId=$result[0]['shipping_id'];
			$temps=array();
			$i=0;
			if($shippingId){
				foreach($result as $k1=>$v1){
					if($shippingId==$v1['shipping_id']){
						$city=explode(',', $v1['city_id']);
						if(in_array($cityId,$city)){
						    $tempTplPrice=0;
							$addNum=0;
							$dafNum=0;	
							 if($v1['p_pricing']==0){ //按件数计费
								$dafNum=$v['num'];
							 }else if($v1['p_pricing']==1){ //按体重
								 $dafNum=$v['goods_weight'];
							 }else if($v1['p_pricing']==2){ //按体积
								 $dafNum=$v['volume_m3'];
							 }	
							 $tempTplPrice=$v1['first_price'];			
							 $addNum=$dafNum - $v1['first_number'];			
							 if($addNum > $v1['add_numeber_moer']){
								  $addNum=ceil($addNum / $v1['add_numeber_moer']);
								  $tempTplPrice+=$addNum * $v1['add_price_moer'];
							 }else if($addNum > 0){
								  $tempTplPrice+=$v1['add_price_moer'];
							 }		
							 $temps[$v1['shipping_id']]['shipping_id']=$v1['shipping_id'];		
							 $temps[$v1['shipping_id']]['shipping_name']=$v1['shipping_name'];		
							 $temps[$v1['shipping_id']]['logistics_price']=$tempTplPrice;				
						}
					}else{
						    $i++;
						    $shippingId=$v1['shipping_id'];
							$city=explode(',', $v1['city_id']);
							if(in_array($cityId,$city)){
							    $tempTplPrice=0;
								$addNum=0;
								$dafNum=0;	
								 if($v1['p_pricing']==0){ //按件数计费
									$dafNum=$v['num'];
								 }else if($v1['p_pricing']==1){ //按体重
									 $dafNum=$v['goods_weight'];
								 }else if($v1['p_pricing']==2){ //按体积
									 $dafNum=$v['volume_m3'];
								 }	
								 $tempTplPrice=$v1['first_price'];			
								 $addNum=$dafNum - $v1['first_number'];			
								 if($addNum > $v1['add_numeber_moer']){
									  $addNum=ceil($addNum / $v1['add_numeber_moer']);
									  $tempTplPrice+=$addNum * $v1['add_price_moer'];
								 }else if($addNum > 0){
									  $tempTplPrice+=$v1['add_price_moer'];
								 }		
								 $temps[$v1['shipping_id']]['shipping_id']=$v1['shipping_id'];		
								 $temps[$v1['shipping_id']]['shipping_name']=$v1['shipping_name'];		
								 $temps[$v1['shipping_id']]['logistics_price']=$tempTplPrice;			 					
							}				
					 }
				 }
			 }else{ //默认运费
				    $tempTplPrice=0;
					$addNum=0;
					$dafNum=0;				 
					 if($result[0]['pricing']==0){ //按件数计费
						$dafNum=$v['num'];
					 }else if($result[0]['pricing']==1){ //按体重
						 $dafNum=$v['goods_weight'];
					 }else if($result[0]['pricing']==2){ //按体积
						 $dafNum=$v['volume_m3'];
					 }
					 $tempTplPrice=$result[0]['default_price'];
					 $addNum=$dafNum - $result[0]['default_number'];
					 if($addNum > $result[0]['add_number']){
						 $addNum=ceil($addNum / $result[0]['add_number']);
						 $tempTplPrice+=$addNum * $result[0]['add_price'];
					 }else if($addNum > 0){
						 $tempTplPrice+=$result[0]['add_price'];
					 }
					 $temps[0]['shipping_id']=0;		
					 $temps[0]['shipping_name']='默认运费';		
					 $temps[0]['logistics_price']=$tempTplPrice;			
			 }
             $allArray[$k]=$temps;
		  }	 
    	}
		//p($allArray);
        if(count($allArray) == 1){
       	     return $allArray[$k];
        }else{
        	$returnArr=array();
       	    foreach($allArray as $k2 =>$v2){
       	    	$returnArr[0]['shipping_id']=0;
				$returnArr[0]['shipping_name']='默认运费';
				foreach($v2 as $k3=>$v3) {$tempPrices=$v2[$k3]['logistics_price'];break;}
				$returnArr[0]['logistics_price']+=$tempPrices;	
       	    }
			return $returnArr;
        }
    }
	
    /**修改购物车数量**/
	public function updateCart($id,$num){
		 $goodsId=getNum($_REQUEST['goods_id']);
		 $num=getNum($_REQUEST['num']);
		 if(empty($num)) return array('success'=>false,'msg'=>isL(L('IllegalOperation'),'非法操作'));	
		 $states=true;
		 $this->startTrans();
		 if($this->userKey) $where="(user_id='{$this->userId}' OR user_key='{$this->userKey}') AND (goods_id = '{$goodsId}' OR zhuhe_goods_id='{$goodsId}')";
		 else $where="user_id='{$this->userId}' AND (goods_id = '{$goodsId}' OR zhuhe_goods_id='{$goodsId}')";
		 $result=$this->field('id,goods_id,goods_number,goods_attr_ids')->where($where)->order(array('goods_id'=>'ASC'))->select();
		 if($result){
			 $cartId='';
			 $ids=in_id($result,'goods_id');
			 $goods=M('goods')->field('id,stock')->where(array('id'=>array('in',$ids)))->order(array('id'=>'ASC'))->select();
			 foreach($goods as $k=>$v){
			 	 //检查库存量
				 if($num > $v['stock'])
				 if(C('CHECK_STOCK')) return array('success'=>false,'msg'=>isL(L('InsufficientInventory'),'库存量不足'));
				 $cartId.=$result[$k]['id'].',';
			 }
			 if($this->where(array('id'=>array('in',rtrim($cartId,','))))->save(array('goods_number'=>$num))){
				  $this->commit();
				 $this->getCartCount(0,true);
				  return array('success'=>true,'msg'=>isL(L('Success'),'操作成功'));
			 }else{
				 $this->rollback();
				 return array('success'=>false,'msg'=>isL(L('Failure'),'操作失败'));
			 }
		 }else{
			  return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'));
		 }
	}
	  
   //删除购物车
   public function delCart(){
		 $ids=rtrim(filterRequst($_REQUEST['ids']),','); 
		 if($_GET['cart_id'] > 0) $and="  AND (id='{$_GET['cart_id']}')";
		 else $and='';
		 if($this->userKey) $where="(user_id ='{$this->userId}' OR user_key='{$this->userKey}') AND (goods_id IN({$ids}) OR zhuhe_goods_id IN ({$ids}))".$and; 
		 else $where="(user_id ='{$this->userId}') AND (goods_id IN({$ids}) OR zhuhe_goods_id IN ({$ids}))".$and; 
		 $states=true;
		 $this->startTrans();		 
		 $result=$this->field('id,goods_id,goods_number')->where($where)->select();
		 $goodsArr=array();
		 $cartIds='';
		 $goodsNum=0;
		 foreach($result as $k=>$v){
			 $goodsArr[$v['goods_id']]=array('id'=>$v['goods_id'],'stock'=>$v['goods_number'],'volume'=>$v['goods_number']);
			 $goodsAttrsArr[$v['goods_id']]=array('num'=>$v['goods_number'],'ids'=>$v['goods_attr_ids']);
			 $cartIds.=$v['id'].',';
			 $goodsNum+=$v['goods_number'];
		 }		 
		 $cartIds=rtrim($cartIds,',');		
		 if(!$this->where(array('id'=>array('in',$cartIds)))->delete()) $states=false;
		 if($states){
             $this->commit();
			 $this->getCartCount($goodsNum,false);	
			 return array('success'=>true,'msg'=>isL(L('Success'),'操作成功'));
		 }else{
			 $this->rollback();
			 return array('success'=>false,'msg'=>isL(L('Failure'),'操作失败')); 
		 }
	}	
	
	//添加购物车
	public function cartAction(){
		   $states=true;
		   $this->startTrans();			
		   $result=$this->getGoods();
		   if($result['success']) return $result;
		   $data=$result['data'];
		   if(empty($data)) return $result;
		   //修改商品销量和库存数组
		   $goods=array();
		   $goodsAttr=array();
		   $cart=array();
		   //商品名称多语言
		   $langs=langAllField('goods_name');	 
		   //购物车优惠信息字段名
		   $langRec=langAllField(C('CART_REC_INFO'));	
		   //当前添加个数
		   $goodsNum=0;
		   $times=time();
		   foreach($data as $k=>$v){
		   	   $goodsNum+=$v['goods_number'];
			   $cart[$k]['zhuhe_goods_id']=$v['zhuhe_goods_id'];
			   $cart[$k]['user_id']=$this->userId > 0 ? $this->userId : 0;
			   $cart[$k]['user_key']=$this->userKey ? $this->userKey : '';
			   $cart[$k]['goods_id']=$v['id'];
			   $cart[$k]['cate_id']=$v['goods_category_id'];
			   $cart[$k]['goods_brand_id']=$v['goods_brand_id'];
			   $cart[$k]['goods_sn']=$v['goods_num'];
			   $cart[$k]['goods_thumb']=$v['goods_thumb'];
			   foreach($langs['lang_arr'] as $langv)$cart[$k][$langv]=$v[$langv]; 
			   $cart[$k]['market_price']=$v['goods_market_price'];
			   $cart[$k]['goods_price']=$v['goods_retail_price'];
			   $cart[$k]['goods_weight']=$v['goods_weight'];
			   $cart[$k]['volume_m3']=$v['volume_m3'];
			   $cart[$k]['goods_number']=$v['goods_number'];
			   $cart[$k]['pay_points']=$v['pay_points'];
			   $cart[$k]['rank_points']=$v['rank_points'];
			   $cart[$k]['integral_amount']=$v['integral_amount'];
			   $cart[$k]['goods_attr']=$v['attrs'] ? json_encode($v['attrs']) : '';
			   $cart[$k]['goods_attr_ids']=$v['goods_attr_ids'] ? $v['goods_attr_ids'] : '';
			   $cart[$k]['rec_type']=$v['rec_type'] ? $v['rec_type'] : 0;
			   foreach($langRec['lang_arr'] as $langr)$cart[$k][$langr]=$v[$langr] ? $v[$langr] : ''; 
			   $cart[$k]['is_discount']=$v['is_discount'] ? $v['is_discount'] : 0;
			   $cart[$k]['times']=$times;
			   $cart[$k]['logistics_tpl_id']=$v['logistics_tpl_id'];
			   $cart[$k]['goods_supplier']=$v['goods_supplier'];	   
		   }   
		   //添加购物车
	       if(!$this->addAll($cart)) $states=false;		
		   if($states){
		   	   $this->commit();
			   $this->getCartCount($goodsNum,true);
			   return array('success'=>true,'msg'=>isL(L('AddCartSuccess'),'添加购物车成功'),'data'=>$cart);			   	
		   }else{
		   	   $this->rollback();
		   	   return array('success'=>false,'msg'=>isL(L('Failure'),'操作失败'));	
		   }	   
	}
	
	//检查购物车是否有此商品(有则删除当修改)
	private function chkCart($goodsId,$attrId=''){
		if($this->userKey) $where="goods_id='{$goodsId}' AND (user_id='{$this->userId}' OR user_key='{$this->userKey}')";
		else $where="goods_id='{$goodsId}' AND user_id='{$this->userId}'";
		$goods=$this->field('id,goods_attr')->where($where)->select();
		$cart_id=$_REQUEST['cart_id'];
		if($goods){
			$attrId=$attrId ? explode(',',$attrId) : array();
				foreach($goods as $k=>$v){
					$attr=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
					$ids=array();
					foreach($attr as $k1=>$v1) $ids[]=$v1['value']['attr_id'];
					if($attrId){
						if($_REQUEST['cart_id'] > 0) $this->where($where." AND id='{$cart_id}'")->delete();
						if(!array_diff($ids,$attrId)) $this->where($where." AND id='{$v['id']}'")->delete();
					}else{
						$this->where($where." AND id='{$v['id']}'")->delete();
					}
				}				
		 }
		 return '';
	}

    //获取商品信息
    private function getGoods(){
        $goodsId=filterRequst($_REQUEST['goods_id']);
		$nums=filterRequst($_REQUEST['num']);
		$attrIds=rtrim(filterRequst($_REQUEST['attr_id']),',');
		$data=array();
		//组合购买
		if(strpos($goodsId,'.') !==false){
			$data['goods_id']=explode('.',$goodsId);
			$data['num']=explode('.',$nums);
			$data['attrIds']=$attrIds ? explode('.',$attrIds) : array();
			if((count($data['goods_id']) != count($data['num'])) || (count($data['goods_id']) !=count($data['attrIds'])) || (count($data['num']) !=count($data['attrIds'])))
		    return array('success'=>false,'msg'=>'非法操作');		
			$mainGoodsId=$data['goods_id'][0];
			$tempIds=$data['goods_id'];
			unset($tempIds[0]);
			$zhuheGoodsId=implode(',',$tempIds);
			$zw="main_goods_id='{$mainGoodsId}' AND zhuhe_goods_id IN({$zhuheGoodsId})";
			$z=M('goods_zhuhe')->where($zw)->select();
			$zd=array();
			foreach($z as $k=>$v) {
				$zd[$v['zhuhe_goods_id']]['id']=$v['id'];
				$zd[$v['zhuhe_goods_id']]['main_goods_id']=$v['main_goods_id'];
				$zd[$v['zhuhe_goods_id']]['zhuhe_goods_id']=$v['zhuhe_goods_id'];
				$zd[$v['zhuhe_goods_id']]['zhuhe_price']=$v['zhuhe_price'];
			}
			//删除这些增经在购物车的数据
			if($this->userKey) $dwhere="goods_id IN (".implode(',',$data['goods_id']).") AND (user_id='{$this->userId}' OR user_key='{$this->userKey}')";
			else $dwhere=array('goods_id'=>array('in',implode(',',$data['goods_id'])),'user_id'=>$this->userId);
		    $this->where($dwhere)->delete();
		}else{//单个购买
		    //检查购物车是否已有商品
		    $cart=$this->chkCart($goodsId,$attrIds);
			if($cart['success']) return $cart;
			$data['goods_id']=array($goodsId);
			$data['num']=array($nums);
			$data['attrIds']=$attrIds ? array($attrIds) : array();			
		}
		//获取属性集合
		if($data['attrIds']){
			$attrIds=implode(',',$data['attrIds']);
			$attrAll=$this->getAttr($attrIds);			
		}
		//p($cart['success']);die;
		//商品名称多语言
        $langs=langAllField('goods_name');
		//获取商品信息
		$field="id,goods_category_id,goods_brand_id,goods_num,{$langs['lang_str']},goods_market_price,goods_retail_price,pay_points,rank_points";
		$field.=",goods_thumb,integral_amount,goods_weight,volume_m3,is_discount,is_promotion,goods_supplier,logistics_tpl_id,stock,shelves";
		$result=M('goods')->field($field)->where(array('id'=>array('in',implode(',',$data['goods_id']),'')))->select();
		if(!$result) return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'),'data'=>'');
		foreach($result as $k=>$v){
		   //是否已上架
			if($v['shelves'] < 1) return array('success'=>false,'msg'=>isL(L('UnderGoods'),'此商品已下架'));
			$i=0;
			foreach($data['goods_id'] as $k1=>$v1){
				if($v1==$v['id']){
					$i=$k1;
					break;
				}
			} 
			//库存
			if(C('CHECK_STOCK')){
				if($data['num'][$i] > $v['stock']) return array('success'=>false,'msg'=>isL(L('InsufficientInventory'),'库存量不足'));
			}					
			$attrPrice=$attrAll[$v['id']]['total'] > 0 ? $attrAll[$v['id']]['total'] : 0;
			$result[$k]['attrs']=$attrAll[$v['id']]['list'];
			$result[$k]['goods_attr_ids']=$attrAll[$v['id']]['goods_attr_ids'];//所选择的属性ID集合
			if($v['id'] != $data['goods_id'][0]){
				$result[$k]['goods_number']=$data['num'][$i]; 
				$result[$k]['goods_retail_price']=$v['goods_retail_price'] + $attrPrice;
				$result[$k]['zhuhe_goods_id']=$data['goods_id'][0];
				if($zd[$v['id']]){
					$result[$k]['goods_retail_price']=$zd[$v['id']]['zhuhe_price'] + $attrPrice;
					foreach($langs['lang_pfix'] as $vn) $result[$k][$vn.C('CART_REC_INFO')]=C(strtoupper($vn).'COMBINED_PRICE');
					$result[$k]['rec_type']=4;
				}					
			}else{
				$result[$k]['goods_number']=$data['num'][0];
				$result[$k]['zhuhe_goods_id']=0;
				$getOuterPrice=$this->getOuterPrice($v['id'],$data['num'][0]);
				$prices=$getOuterPrice['price'] > 0 ? $getOuterPrice['price'] : $v['goods_retail_price'];
				$result[$k]['goods_retail_price']=$prices + $attrPrice;
				$result[$k]['rec_type']=$getOuterPrice['rec_type'];
				foreach($langs['lang_pfix'] as $v2) $result[$k][$v2.C('CART_REC_INFO')]=$getOuterPrice[$v2.C('CART_REC_INFO')];
			}					
		}
        $result=multi_array_sort($result,'zhuhe_goods_id',SORT_ASC);
		if($result) return array('success'=>false,'msg'=>'','data'=>$result);
		else return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'),'data'=>'');
    }
	
	/**
	 * 获得商品常规以外的价格
	  * 商品限时促销价格的优先级第一；
	 * 商品数量优惠价格的优先级第二；
	 * 商品会员等级价格优先级三；
	 */
    private function getOuterPrice($goodsId,$num=0){
		if($data=$this->promotion($goodsId)){
		}else if($data=$this->volumePrice($goodsId,$num)){
		}else if($data=$this->cartMember($goodsId)){
		}
		return $data;
    }  
	
  /**
  *处理商品属性和属性总价格;修改时间:2016-4-7
  *return array(); total 属性总金额;list属性列表;不支持多选属性
  */
  private function getAttr($attIds){
  	  if(empty($attIds)) return array();
	  //获取多语言
	  $aname='';
	  $attrValule='';
	  if(!C('LANG_SWITCH_ON')){
		  $langFpx=explode('-',C('DEFAULT_LANG'));
		  $langFpx=$langFpx[1];
		  $aname.='ga.'.$langFpx.'_name';
		  $attrValule.='gav.'.$langFpx.'_attr_value';		  
	  }else{
		  foreach(C('LANG_LIST') as $k=>$v){
			  $langFpx=explode('-',$v);
			  $langFpx=$langFpx[1];
			  $aname.='ga.'.$langFpx.'_name,';
			  $attrValule.='gav.'.$langFpx.'_attr_value,';
		  }
	  }
	  $aname=rtrim($aname,',');
	  $attrValule=rtrim($attrValule,',');
	  $anameArr=explode(',',str_replace('ga.','',$aname));
	  $attrValuleArr=explode(',',str_replace('gav.','',$attrValule));
	  $sql="SELECT ga.id,{$aname},ga.attr_type,{$attrValule},attr.id AS attr_id,attr.goods_id,attr.attr_price,attr.attr_stock,gav.orders FROM ";
	  $sql.=PREFIX."goods_attribute AS ga ";
	  $sql.="JOIN ".PREFIX."goods_attribute_value AS gav ON (ga.id=gav.attribute_id) JOIN ".PREFIX."goods_attr AS attr ";
	  $sql.="ON (attr.attribute_value_id=gav.id) WHERE ga.is_show=1 AND gav.is_show=1 AND attr.id IN ($attIds) ORDER BY attr.goods_id ASC";
	  $result=$this->db->query($sql);
	  $goodsId=$result[0]['goods_id'];
	  $attr=array();
	  $i=0;
	  foreach($result as $k=>$v){  	  
		  if($goodsId==$v['goods_id']){		  	 
			  $attr[$goodsId]['total']+=$v['attr_price'];
			  foreach($anameArr as $av) $attr[$goodsId]['list'][$i][$av]=$v[$av];
			  foreach($attrValuleArr as $ava) $attr[$goodsId]['list'][$i]['value'][$ava]=$v[$ava];
			  $attr[$goodsId]['list'][$i]['orders']=$v['orders'];
			  $attr[$goodsId]['list'][$i]['value']['attr_id']=$v['attr_id'];
			  $attr[$goodsId]['list'][$i]['value']['price']=$v['attr_price'];
			  $attr[$goodsId]['list'][$i]['value']['attr_stock']=$v['attr_stock'];
			  $attr[$goodsId]['goods_attr_ids'].=$v['attr_id'].',';//商品属性ID集合
			  $i++;
		  }else{
			  $i=0;
			  $goodsId=$v['goods_id'];
			  $attr[$goodsId]['total']+=$v['attr_price'];
			  foreach($anameArr as $av) $attr[$goodsId]['list'][$i][$av]=$v[$av];
			  foreach($attrValuleArr as $ava) $attr[$goodsId]['list'][$i]['value'][$ava]=$v[$ava];
			  $attr[$goodsId]['list'][$i]['orders']=$v['orders'];
			  $attr[$goodsId]['list'][$i]['value']['attr_id']=$v['attr_id'];
			  $attr[$goodsId]['list'][$i]['value']['price']=$v['attr_price'];
			  $attr[$goodsId]['list'][$i]['value']['attr_stock']=$v['attr_stock'];		
			  $attr[$goodsId]['goods_attr_ids'].=$v['attr_id'].',';//商品属性ID集合	  
			  $i++;
		  }
	  }
	 // p($attr);die;
	  $attr1=multi_array_sort($attr[$goodsId]['list'],'orders',SORT_ASC);
	  $attr[$goodsId]['list']=$attr1;
      return $attr ? $attr : array();
  }
  
  /**处理会员等级价格
  *这里的等级ID取于SESSION保存的用户等级ID my_member_grade表ID
  */
  private function cartMember($goodsId){
	 if(!C('IS_GOODS_MEMBER_PRICE')) return ''; //不启用会员价格等级
	 if($this->userId <= 0) return array();
	 $userGrade=getUserC('SE_USER_GRADR_ID');
  	 $grade=$userGrade ? $userGrade : 0;
	 //商品名称多语言
     $langs=langAllField('mg.grade_name');	 
	 $sql="SELECT mp.member_price,{$langs['lang_str']} FROM ".PREFIX."goods_member_price AS mp JOIN ".PREFIX."member_grade AS mg ";
	 $sql.="ON (mp.member_grade=mg.id) WHERE mp.goods_id='{$goodsId}' AND mp.member_grade='{$grade}' LIMIT 1";
	 $res=$this->db->query($sql);
	 $res=$res[0];
	 $data=array();
	 $data['price']=$res['member_price'];
	 $data['rec_type']=3;
	 foreach($langs['lang_pfix'] as $k=>$v) $data[$v.C('CART_REC_INFO')]=$res[$langs['lang_arr'][$k]];
	 return $data;
  }
  
  //商品数量优惠价格
  //返回优惠价格
  private function volumePrice($goodsId,$num){
	 if(!C('IS_GOODS_VOLUME_PRICE')) return ''; //不启用商品数量优惠价格
  	 $retPrice='';
  	 $res=M('goods_volume_price')->where(array('goods_id'=>$goodsId))->order(array('discount_sum'=>'ASC'))->select();
	 if(!$res) return false; //不存在数量优惠
	 $count=count($res);
	 $cou=$res[$count -1]['discount_sum']; //取得最大的优惠个数
	 $price=$res[$count - 1]['preferential']; //取得最大的优惠个数的单价
	 if($num>=$cou){ //购买的数量等于大于最大的优惠个数
	 	$retPrice=$price; 
	 }else{
	 	 foreach($res as $k=>$v){
			if($count==1){
				if($num >= $v['discount_sum']) $retPrice=$v['preferential'];
			}else{
				if(($count-1)==$k){
					if($num >= $res[$k]['discount_sum']) $retPrice=$res[$k]['discount_sum'];
				}else{
					if(($num >= $v['discount_sum']) && ($num <=$res[$k+1]['discount_sum'])){
						$retPrice=$v['preferential'];
						break;
					}						
				}
			} 
	 	 }
	 }
	 if($retPrice){
		  $langs=langAllField(C('CART_REC_INFO'));	  
		  $data=array();
		  $data['price']=$retPrice;
		  $data['rec_type']=2;	
		  foreach($langs['lang_pfix'] as $k=>$v) $data[$v.C('CART_REC_INFO')]=C(strtoupper($v).'DISCOUNTS');
		  return $data;	 
	 }else{
	      return '';	 
	 }
	 
  }
  
  //限时抢购价格
  private function promotion($goodsId){
	  if(!C('IS_PROMOTION')) return ''; //不启用限时抢购价格
  	  $times=time();
  	  $where['goods_id']=$goodsId;
	  $where['no_ffo']=1;
	  $where['promotion_start_date']=array('ELT',$times);
	  $where['promotion_end_date']=array('EGT',$times);
	  $res=M('goods_promotion')->field('promotion_price,id')->where($where)->find();
	  if($res){		  
		  $langs=langAllField(C('CART_REC_INFO'));	  
		  $data=array();
		  $data['price']=$res['promotion_price'];
		  $data['rec_type']=1;	
		  foreach($langs['lang_pfix'] as $k=>$v) $data[$v.C('CART_REC_INFO')]=C(strtoupper($v).'PROMOTION');
		  return $data;	 		  
	  }else{
	      return '';
	  }
  }  
  
  //优惠活动满即送活动
  private function fullFree($amount){
  	 if(empty($amount)) return false;
     if(!C('IS_dISCOUNT')) return false; 
	 $userGrade=getUserC('SE_USER_GRADR_ID');
  	 $grade=$userGrade ? $userGrade : 0;	
	 $times=time();
	 $langField=langAllField('gd.dis_name');
	 $dw="start_date <='{$times}' AND end_date >='{$times}' ";
	 $sql="SELECT gd.id,{$langField['lang_str']},gd.member_grade_id,gd.dis_type,gd.dis_goods_id,";
	 $sql.="gdx.amount,gdx.dis_type_val FROM ".PREFIX."goods_discount AS gd JOIN ".PREFIX."goods_discount_expand AS gdx ";
	 $sql.="ON (gd.id=gdx.goods_discount_id) WHERE {$dw}  ORDER BY gdx.amount ASC";
	 $result=$this->db->query($sql);
     if($result){
		//是否在当前会员内
		if(C('IS_GOODS_MEMBER_PRICE')){
			if(!in_array($grade,explode(',',$result[0]['member_grade_id']))){
				return false;
			}
		}
		//优惠方式
		$data=array();
		$temp=multi_array_sort($result,'amount',SORT_DESC);
		if($result[0]['dis_type']==0){ //送赠品
            
		}else{//享受价格折扣或现金减免
		   $reduction=0;
		   $full=0;
		   if($amount >=$temp[0]['amount']) {
		   	   $reduction=$temp[0]['dis_type_val'];
			   $full=$temp[0]['amount'];
		   }
		   //是否达到满即减
		   if($reduction==0){
			 foreach($temp as $ks=>$vs){
				$tempNum=$temp[$ks +1]['amount'];
				if($tempNum){
					if($amount >=$tempNum && ($amount < $vs['amount'])){
						$reduction=$temp[$ks+1]['dis_type_val'];
						$full=$temp[$ks+1]['amount'];
						break;
					}				
				}							 
			 }			   
		   }
		   $data['id']=$result[0]['id'];
		   $data['dis_type']=$result[0]['dis_type'];
		   foreach($langField['lang_arr'] as $lang) $data[$lang]=$result[0][$lang];
		   //计算得出当前订单已达到金额(满)
		   $data['dis_full']=$full;
		   //得出当前订单的优惠金额；$data['dis_type']==1按折扣优惠除100，$data['dis_type']==2直接现金减免
		   $data['reduction']=$data['dis_type']==1 ? $reduction / 100 : $reduction; 
		   $data['goods_list']='';
		   foreach($result as $k=>$v){
			   $data['list'][$k]['amount']=$v['amount'];
			   $data['list'][$k]['dis_type_val']=$v['dis_type_val'];
		   }
		}
		return $data;	 
	 }
  }
  
  /**
  *订单是否送现金卷
  */
  private function giveRed($amount){
	    if(!C('IS_BONUS')) return array();
	    $times=time();
	    $field=pfix('bonus_name').' AS bonus_name,id,bonus_money,min_amount,send_start_date,send_end_date,is_show';
		$where="send_type=2";
		$result=M('bonus_type')->field($field)->where($where)->find();
		if(empty($result)) return array();
		if(($result['is_show'] > 0) && ($result['send_start_date'] <=$times && $result['send_end_date'] > $times) && ($amount >=$result['min_amount'])){
			return $result;
		}
		return array();
  }  
  
  /**计算购物车个数
   * $num    变化数量
   * $type true 增加;false 减少
   * */
  public function getCartCount($num=0,$type=false){	
  	 if($num > 0){
  	 	$cartCount=$_COOKIE[C('CART_COUNT')] > 0 ? $_COOKIE[C('CART_COUNT')] : 0;
		if($type) $counts=$cartCount + $num;
		else $counts=$cartCount - $num;
		$counts=$counts >= 0 ? $counts : 0;
		setcookie(C('CART_COUNT'),$counts,0,'/');   
		return $counts; 
  	 }
	 if($this->userKey) $where="user_id='{$this->userId}' OR user_key='{$this->userKey}'";
	 else array('user_id'=>$this->userId);
	 $counts=$this->where($where)->sum('goods_number');
	 $counts=$counts ? $counts : 0;
	 setcookie(C('CART_COUNT'),$counts,0,'/');    	 	
	 return $counts;
  }
  
  //合拼物流模板相同的ID
  private function mergeLlogistics($arr){
     $arr=multi_array_sort($arr,'logistics_tpl_id',SORT_ASC);
	 $tplId=$arr[0]['logistics_tpl_id'];
	 $returnArr=array();
	 $i=0;
	 foreach($arr as $k=>$v){
		 if($v['logistics_tpl_id'] > 0){
			 if($tplId==$v['logistics_tpl_id']){
				 $returnArr[$i]['logistics_tpl_id']=$v['logistics_tpl_id'];
				 $returnArr[$i]['num']+=$v['num'];
				 $returnArr[$i]['volume_m3']+=$v['volume_m3'];
				 $returnArr[$i]['goods_weight']+=$v['goods_weight'];
			 }else{
				 $i++;
				 $tplId=$v['logistics_tpl_id'];
				 $returnArr[$i]['logistics_tpl_id']=$v['logistics_tpl_id'];
				 $returnArr[$i]['num']+=$v['num'];
				 $returnArr[$i]['volume_m3']+=$v['volume_m3'];
				 $returnArr[$i]['goods_weight']+=$v['goods_weight'];		 
			 }			 
		 }

	 }
	// p($returnArr);
	 return $returnArr;
  }
  
	//获取会员可用现金卷
	private function getUseRed($amount){
		if(!C('IS_BONUS')) return array();
		if($this->userId <= 0) return array();
		$times=time();
		$langField=langAllField('bt.bonus_name');
	    $sql="SELECT mb.id,bt.id AS bonus_type_id,{$langField['lang_str']},bt.bonus_money,bt.min_amount FROM ";
		$sql.=PREFIX."member_bonus AS mb JOIN ".PREFIX."bonus_type AS bt ON (mb.bonus_type_id=bt.id) WHERE mb.member_id='{$this->userId}'";
		$sql.="AND mb.used_time=0 AND mb.order_id=0 ";
		$sql.="AND (bt.use_start_date <= '{$times}' AND bt.use_end_date > '{$times}') AND bt.min_amount <= '{$amount}' ";
		$result=$this->db->query($sql); 
		$redList=array();
		foreach($result as $k=>$v){
			$redList[$v['id']]['mb_id']=$v['id'];
			$redList[$v['id']]['bonus_type_id']=$v['bonus_type_id'];
			foreach($langField['lang_arr'] as $lang) $redList[$v['id']][$lang]=$v[$lang];
			$redList[$v['id']]['bonus_money']=$v['bonus_money'];
			$redList[$v['id']]['min_amount']=$v['min_amount'];
		}
		return $redList;
	}  

}
?>