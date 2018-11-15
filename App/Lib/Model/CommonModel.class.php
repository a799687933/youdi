<?php
class CommonModel extends Model{  

	/**
	*获得会员等级信息修改时间 2015-10-7
	*$points  等级积分
	*return array();
	*/
   public function getGrade($points){
 		 $gr=M('member_grade');
		 $grandField="id,".pfix('grade_name')." AS grade_name,discount";
		 $minPoints=$gr->field('id,min_points')->order(array('min_points'=>'ASC'))->find(); //会员等级最低分点
		 $maxPoints=$gr->field('id,min_points')->order(array('min_points'=>'DESC'))->find(); //会员等级最高分点
		 if($points <= $minPoints['min_points']){//最低组级别
			$grade=$gr->field($grandField)->where(array('id'=>$minPoints['id']))->find();
		 }else if($points > $maxPoints['min_points']){ //最高组级别
			 $grade=$gr->field($grandField)->where(array('id'=>$maxPoints['id']))->find();
		 }else{ //其它组级别
			 $gradeWhere="min_points < '{$points}' AND max_points >= '{$points}' ";
			 $grade=$gr->field($grandField)->where($gradeWhere)->find();
		 }
		//用户等级信息
		if($grade){ 
		    return array('id'=>$grade['id'],'grade_name'=>$grade['grade_name'],'points'=>$points);	
		}	
   }	
   	
	//AJAX检索区域
	public function searchRegion(){
		if(empty($_GET['region_id'])){
			$where['parent_id']=0;			
		}else{
			$where['parent_id']=$_GET['region_id'];
		}
		$res= M('region')->field('region_id,region_name')->where($where)->select();
		if(empty($_GET['region_id'])){
			return $res;			
		}else{
			die(json_encode($res));
		}
	}	
	
	//获取已选择的地区列表
	//返回下拉框
	//$country 国家ID
	//$province 省份ID
	//$city 城市ID
	//$district  区县ID
	public function getRegion($country,$province,$city,$district){
		if($country > 0 && $province > 0 && $city > 0){
		    $arr=array(0=>0,1=>$country,2=>$province,3=>$city,4=>$district);
		}else{
		    $arr=array(0=>0);
		}
		$region=M('region');
		$regAll=array();
		foreach($arr as $k=>$v){
			if($k==4){
				    if($v > 0){
						$str='';
						$regionArr=$region->field('region_id,region_name')->where(array('parent_id'=>$v,'is_show'=>1))->select();
						foreach($regionArr as $kk=>$vv){
							 if($vv['region_id']==$country || $vv['region_id']==$province || $vv['region_id']==$city || $vv['region_id']==$district) 
							 $select='selected="selected"';
							 $str.='<option value="'.$vv['region_id'].'" '.$select.'>'.$vv['region_name'].'</option>';
							 $select='';
						}							
					}else{
						$str='';
						$str.='<option value="">请选择上一级</option>';						
					}
			}else{
				    $str='';
					$regionArr=$region->field('region_id,region_name')->where(array('parent_id'=>$v,'is_show'=>1))->select();
					foreach($regionArr as $kk=>$vv){
						 if($vv['region_id']==$country || $vv['region_id']==$province || $vv['region_id']==$city || $vv['region_id']==$district) $select='selected="selected"';
						 $str.='<option value="'.$vv['region_id'].'" '.$select.'>'.$vv['region_name'].'</option>';
						 $select='';
					}			
			}
            if($str) $regAll[$k]=$str;				
		}  
		return $regAll;   		
	}
	
   /**
   *把my_region城市表数据放入内存
   */	
	public function regionToArray(){
			$region=M('region');
			//国家
			$cou=$region->field('region_id,region_name')->where(array('region_type'=>0))->select();
			foreach($cou as $k=>$v){
				$country[$v['region_id']]=$v['region_name'];
			}
			unset($cou);
			//省份
			$pro=$region->field('region_id,region_name')->where(array('region_type'=>1))->select();
			foreach($pro as $k=>$v){
				$province[$v['region_id']]=$v['region_name'];
			}
			unset($pro);	
			//城市
			$cit=$region->field('region_id,region_name')->where(array('region_type'=>2))->select();
			foreach($cit as $k=>$v){
				$city[$v['region_id']]=$v['region_name'];
			}
			unset($cit);		
			
			//区县
			$dis=$region->field('region_id,region_name')->where(array('region_type'=>3))->select();
			foreach($dis as $k=>$v){
				$district[$v['region_id']]=$v['region_name'];
			}
			unset($dis);			
			return array('country'=>$country,'province'=>$province,'city'=>$city,'district'=>$district);
	}
	

	
	/**
	*平台总收入表
	*$type       记录类型;-1支出;1收入;
	*$changeType       变动类型；0订单支付收入；1会员充值收入；2商户提现支出；3会员提现支出；
	*$money   金额
	*$changeData      变动原因
	*$userName         操作管理员
	*$date    时间差
	*/	
	public function affairsTotal($type,$changeType,$money,$changeData,$userName,$date=''){
	      $atData=false;
		  $times=$date ? $date : time();
		  $sameDay=strtotime(date('Y-m-d',$times)); //今天时间差
		  $at=M('affairs_total');
		  $queryWhere="times = '{$sameDay}'";
		  if($queryId=$at->field('id')->where($queryWhere)->find()){
			  if($type==-1){
				  $atData=$at->where(array('id'=>$queryId['id']))->setInc('subtract_money',$money);
			  }else{
				  $atData=$at->where(array('id'=>$queryId['id']))->setInc('add_money',$money);
			  }
		  }else{
			  if($type==-1){
		          $atData=$at->add(array('subtract_money'=>$money,'times'=>$sameDay));  
			  }else{
				  $atData=$at->add(array('add_money'=>$money,'times'=>$sameDay));  
			  }
		  }	
		  //写入清单表
		  if($atData){
			    $data['affairs_total_id']=$queryId['id'] ? $queryId['id'] : $atData;
				$data['change_type']=$changeType;
				$data['type']=$type;	
				$data['money']=$money;	
				$data['change_data']=$changeData;
				$data['user_name']=$userName;
				$data['change_time']=$times;	
				$atData=M('affairs_change')->add($data);
		  }
		  return $atData;
	}	
	
    /**
	 * 指定时间自动删除过期订单
	 * $userId  用户ID
	* */
    public function expiredOrder($userId=''){
			$orderDb=M('order_info');
			$delTime=time() - (C('ORDERRETAIN') * 86400);	
			$where=array();
			$where['order_status']=0;
			$where['pay_status']=0;
			$where['shipping_status']=0;
			if(!empty($userId)) $where['user_id']=$userId;
			$where['add_time']=array('LT',$delTime);		
			$orderId=$orderDb->field('order_id')->where($where)->select();
			if($orderId){
				$orderIds='';
				foreach($orderId as $k=>$v) $orderIds.=$v['order_id'].',';
				$orderIds=rtrim($orderIds,',');
				//回滚商品库存与促销数量与退回用户积分和现金卷
				$result=$this->returnUser($orderIds);
                //删除过期订单
				$orderDb->where(array('order_id'=>array('in',$orderIds)))->delete(); 
				//删除订单商品
				M('order_goods')->where(array('order_id'=>array('in',$orderIds)))->delete(); 
			}    	
    }

	/**
	 * 指定的时间自动清理过期购物车商品
	**/
	public function cleanCart(){
		 $times=getExpiredTime();
		 if($times['expiredDbTime'] && $times['userKey']){
			 	$where['times']=array('LT',$times['expiredDbTime']);
			    M('cart')->where($where)->delete(); 	
		 }
	}	
	
	//指定时间自动确认订单交易完成
	public function autoConfirmOrder(){
		$das=C('AUTO_CONFIRMC_ORDER');
		if(empty($das)) return;
		$conTime=time() - ($das * 86400);	
		$where=array();
		$where['order_status']=1;
		$where['pay_status']=2;
		$where['shipping_status']=array('gt',1);	
		$where['pay_type']=array('in','0,3');
		$where['shipping_time']=array('LT',$conTime);	
		$order=M('order_info')->field('order_id,order_sn,user_id,goods_supplier,order_amount,pay_points,rank_points')->where($where)->select();
		if($order){
			$ids='';
			$fee=C('SUPPLIERBUYBACKS');
			$fee= $fee ? $fee : 0;
			$gs=M('goods_supplier');
			$gsa=M('goods_supplier_account');
			//启动事务
			$this->startTrans(); 
			$transTrue=true;
			foreach($order as $k=>$v){
				$ids.=$v['order_id'].',';
				//订单金额过帐给供应商		
				if($v['goods_supplier'] > 0){
					$procedures=round($v['order_amount'] * ($fee / 100)); //手续费
					$actualPrice=$v['order_amount'] - $procedures;
					if(!$gs->where(array('id'=>$v['goods_supplier']))->setInc('bankroll',$actualPrice)) $transTrue=false;	
					//记录操作清单
					$data['goods_supplier_id']=$v['goods_supplier'];
					$data['trade_type']=1;
					$data['total_price']=$v['order_amount'];
					$data['centum']=$fee;
					$data['fee']=$procedures;
					$data['actual_price']=$actualPrice;
					$data['change_desc']='用户未确认完成订单已超('.$das.')天，系统自动确认订单交易资金到帐<br/>单号：'.$v['order_sn'];
					$data['change_time']=time();
					if(!$gsa->add($data)) $transTrue=false;						
				}
			}
			$ids=rtrim($ids,',');
			//增送会员的各种活动
			if(!$this->deliveryUser($ids)) $transTrue=false;
			//修改订单已确认完成状态
			if(!M('order_info')->where(array('order_id'=>array('in',$ids)))->save(array('order_status'=>2))) $transTrue=false;
			if($transTrue){
				 $this->commit();//成功则提交
			}else{
				 $this->rollback();//不成功，则回滚
			}			
		}
	}	
	
	/**
	*退回用户的积分和现金额卷并回滚销量与库存(在开启库存情况下)
	*$orders  订单数据多维数组 array(0=>array('user_id'=>12,'order_id'=>30,'use_integral'=>2000,'bonus_sn'=>25));
	*$isDelete 是否删除数据库数据
	*/
	public function returnUser($orders,$isDelete=false){
		  if(empty($orders)) return array('success'=>false,'msg'=>'数组是空的');
		  $returnUserTrue=true;
		  $changeIntegral=array();
		  $bonusIds='';
		  $orderSnS='';
		  $pointsSxplain=array();
		  $times=time();
		  $langma=langAllField('change_desc');//积分说明多语言
		  foreach($orders as $k=>$v){
			  //订单号集合
			  $orderSnS.=$v['order_id'].',';
			  //退积分数组
			  if($v['use_integral'] > 0){
				  $changeIntegral[$k]['id']=$v['user_id'];
				  $changeIntegral[$k]['pay_points']=$v['use_integral'];
				  $changeIntegral[$k]['rank_points']=0;
				   //退回积分清单说明
				   $pointsSxplain[$k]['member_id']=$v['user_id'];
				   $pointsSxplain[$k]['pay_points']=$v['use_integral'];
				   $pointsSxplain[$k]['change_time']=$times;
				   $pointsSxplain[$k]['change_type']=99;
				   //使用说明多语言
				   foreach($langma['lang_arr'] as $mak=>$mav)
				   $pointsSxplain[$k][$mav]= C(strtoupper($langma['lang_pfix'][$mak]).'RETURN_POINTS');
			  }
			  //退回现金卷数组
			  if($v['bonus_sn'] > 0){
				  $bonusIds.=$v['bonus_sn'].',';
			  }
		  }
		 
		  //退积分
		  if($changeIntegral){
			   if(!$this->changeIntegral(true,$changeIntegral)) return array('success'=>false,'msg'=>'退回用户积分失败');	
			   if(!$this->memberAccount($pointsSxplain)) return array('success'=>false,'msg'=>'写入用户积分清单失败');		
		  }
		 
		  //退回现金卷
		  if($bonusIds){
			  $bonusIds=rtrim($bonusIds,',');
			  if(!M('member_bonus')->where(array('id'=>array('in',$bonusIds)))->save(array('used_time'=>0,'order_id'=>0))) return array('success'=>false,'msg'=>'退回现金卷失败');
		  }
		  
          //订单商品(回滚库存与销售量)，但回滚属性库存尚未解决
		  $orderSnS=rtrim($orderSnS,',');
		  $orderGoods=M('order_goods')
		  ->field('rec_id,goods_id,goods_number,goods_attr_ids')->where("order_id IN ({$orderSnS})")
		  ->order("goods_id ASC")->select();
		 $goodsArr=array();
		 $goodsId=$orderGoods[0]['goods_id'];
		 $ogIds='';
		 foreach($orderGoods as $ok=>$ov){
			 //这里处理商品属性库存数组，但现在未找到好的解决方法
			 $ogIds.=$ov['rec_id'].',';
			if($goodsId==$ov['goods_id']){
				$goodsArr[$goodsId]['id']=$ov['goods_id'];
				$goodsArr[$goodsId]['stock']+=$ov['goods_number'];
				$goodsArr[$goodsId]['volume']+=$ov['goods_number'];			
			}else{
				$goodsId=$ov['goods_id'];
				$goodsArr[$goodsId]['id']=$ov['goods_id'];
				$goodsArr[$goodsId]['stock']+=$ov['goods_number'];
				$goodsArr[$goodsId]['volume']+=$ov['goods_number'];			
			}
		  }		
		  if(C('CHECK_STOCK')) if(!$this->updateMoerGoods(false,$goodsArr)) return array('success'=>false,'msg'=>'处理库存与销量失败');
		   
		  if($isDelete){
			  if(!M('order_info')->where(array('order_sn'=>array('in',$orderSnS)))->delete()) $returnUserTrue=false;
			  if(!M('order_goods')->where(array('rec_id'=>array('in',rtrim($ogIds,','))))->delete()) $returnUserTrue=false;
		  }
         return array('success'=>true,'msg'=>'处理成功');
	}
	
	/**
	*增送积分和现金卷
	*$orders  订单数据多维数组 
	*array(
	*	 0=>array('order_id'=>33,'user_id'=>332,'pay_points'=>100,'rank_points'=>100,'bonus_type_id'=>3),
	*    2=>array('order_id'=>34,'user_id'=>300,'pay_points'=>100,'rank_points'=>100,'bonus_type_id'=>5)
	*)
	*/	
	public function deliveryUser($orders){
	     $times=time();
		 $deliPoints=array();
		 $pointsSxplain=array();
		 $redArr=array();
		 $langma=langAllField('change_desc');//积分说明多语言
		 $redIds='';
		 foreach($orders as $key=>$val){
			 //是否送现金卷
			 if($val['bonus_type_id'] > 0){
				 $redIds.=$val['bonus_type_id'].',';
				 $redArr[$val['bonus_type_id']]['bonus_type_id']=$val['bonus_type_id'];
				 $redArr[$val['bonus_type_id']]['send_type']=2;
				 $redArr[$val['bonus_type_id']]['bonus_sn']=0;
				 $redArr[$val['bonus_type_id']]['member_id']=$val['user_id'];
				 $redArr[$val['bonus_type_id']]['used_time']=0;
				 $redArr[$val['bonus_type_id']]['order_sn']=0;
			 }
			 //是否有积分送
			 if($val['pay_points'] > 0 || $val['rank_points']){
				 //增送积分数组
				 $deliPoints[$key]['id']=$val['user_id'];
				 $deliPoints[$key]['pay_points']=$val['pay_points'];
				 $deliPoints[$key]['rank_points']=$val['rank_points'];	
				 //增送积分清单数组
				 $pointsSxplain[$key]['member_id']=$val['user_id'];
				 $pointsSxplain[$key]['pay_points']=$val['pay_points'];
				 $pointsSxplain[$key]['rank_points']=$val['rank_points'];
				 $pointsSxplain[$key]['change_time']=$times;
				 $pointsSxplain[$key]['change_type']=99;
				 $langma=langAllField('change_desc');//积分说明多语言
				 foreach($langma['lang_arr'] as $mak=>$mav)
				 $pointsSxplain[$key][$mav]= C(strtoupper($langma['lang_pfix'][$mak]).'ORD_COM_POINTS');				 
			 }
	 
		 }
		 //增送积分
		 if($deliPoints){
			 if(!$this->changeIntegral(true,$deliPoints)) return array('success'=>false,'msg'=>'增送积分失败');
			 if(!$this->memberAccount($pointsSxplain)) return array('success'=>false,'msg'=>'增送积分清单写入失败');	 
		 }
		
         //送现金卷
		 if($redArr){
			 //筛选未过期的可送现金卷
			 $redIds=rtrim($redIds,',');
			 $bw="id IN ({$redIds}) AND is_show=1 AND send_type=2 AND send_start_date <='{$times}' AND send_end_date >='{$times}'";
			 $bt=M('bonus_type')->field('id')->where($bw)->select();
			 $addRedArr=array();
			 foreach($bt as $v) if($redArr[$v['id']]) $addRedArr[]=$redArr[$v['id']];
			 if($addRedArr){
				 if(!M('member_bonus')->addAll($addRedArr)) return array('success'=>false,'msg'=>'增送现金卷入失败');	 
			 }
		 }
		 return array('success'=>true,'msg'=>'操作成功');	 
	}

	/**
	*批量增加/减少商品库存和销量
	*$type 处理成功true,处理失败false
	*$arr  数组
	*$arr=array(
				商品ID1=>array('id'=>1,'stock'=>1,'volume'=>1),
				商品ID2=>array('id'=>2,'stock'=>2,'volume'=>2),
				商品ID3=>array('id'=>3,'stock'=>3,'volume'=>3));
	*/
	public function updateMoerGoods($type,$arr){
		if(!C('CHECK_STOCK')) return true;
		if($type){
			$suc1=' - ';
			$suc2=' + ';
		}else{
			$suc1=' + ';
			$suc2=' - ';		
		}
		$ids=implode(',', array_keys($arr)); 
		$field1="UPDATE ".PREFIX."goods SET stock = CASE id ";
		$field1End="END, ";
		$field2=" volume = CASE id ";
		$field2End=" END ";
		$str1='';
		$str2='';
		foreach($arr as $v){
			$str1.=' WHEN '.$v['id'].' THEN stock'.$suc1.$v['stock'].' ';
			$str2.=' WHEN '.$v['id'].' THEN volume'.$suc2.$v['volume'].' ';
		}
		$where="WHERE id IN ($ids)";
		$sql=$field1.$str1.$field1End.$field2.$str2.$field2End.$where;
		return $this->db->execute($sql);
	}

	/**
	*批量增加/减少商品属性库存
	*$type 处理成功true,处理失败false
	*$arr  数组
	*$arr=array(1=>array('num'=>10,'ids'=>'1,2,3,4'),2=>array('num'=>20,'ids'=>'5,6,7'));
	*/
	public function updateMoerGoodsAttrs($type,$arr){
		 if(!C('IS_GOODS_ATTRIBUTE_STOCK')) return true;
		 if($type){
			 $suc1=' - ';
			 $suc2=' + ';
		 }else{
			 $suc1=' + ';
			 $suc2=' - ';		
		 }	
		$field1="UPDATE ".PREFIX."goods_attr SET attr_stock = CASE id ";
		$field1End="END ";
		$str1='';
		$inIds='';
		 foreach($arr as $k=>$v){
			 $num=$v['num'];
			 $ids=rtrim($v['ids'],',');
			 $inIds.=$v['ids'].',';
			 $idsArr=explode(',',$ids);
			 foreach($idsArr as $av){
				 $str1.=' WHEN '.$av.' THEN attr_stock'.$suc1.$num.' ';
			 }
		 }
		 $where="WHERE id IN (".rtrim($inIds,',').")";
		 $sql=$field1.$str1.$field1End.$where;
		 return $this->db->execute($sql);
     } 
	
	/**
	*批量变动会员积分
	*$type 送积分true,退积分false
	*$arr  多维数组
	*$arr=array(0=>array('id'=>1,'pay_points'=>1000,'rank_points'=>100),1=>array('id'=>2,'pay_points'=>2000,'rank_points'=>100));
	*/
	private function changeIntegral($type,$arr){
		 if($type){
			 $suc1='  + ';
		 }else{
			 $suc1=' - ';
		 }	
		$field1="UPDATE ".PREFIX."member SET pay_points = CASE id ";
		$field1End="END ";
		$str1='';
	    $field2=" ,rank_points = CASE id ";
		$field2End=" END ";
		$str2='';
		$inIds='';
		 foreach($arr as $k=>$v){
			 $inIds.=$v['id'].',';
			 $str1.=' WHEN '.$v['id'].' THEN pay_points'.$suc1.$v['pay_points'].' ';
			 $str2.=' WHEN '.$v['id'].' THEN rank_points'.$suc1.$v['rank_points'].' ';
		 }
		 $where="WHERE id IN (".rtrim($inIds,',').")";
		 if($str2) $rank=$field2.$str2.$field2End;
		 $sql=$field1.$str1.$field1End.$rank.$where;
		 return $this->db->execute($sql);
     } 	
	 
	 /**
	 *会员帐户变动清单，支持多条数据
	 *$changeArray  
	 *	$changeArray=array(
	 *	0=>array('member_id'=>$this->userId,'rank_points'=>-100,'change_time'=>time(),'change_type'=>2),
	 *	1=>array('member_id'=>$this->userId,'rank_points'=>-200,'change_time'=>time(),'change_type'=>2),
	 *  );
	 */
	 public function memberAccount($changeArray){
	      $field=array();
		  $langs=langAllField('change_desc');
		  $field[]='member_id';//会员ID
		  $field[]='transaction'; //0为其它操作，流水单号为在线充值号
		  $field[]='return_mark';//在线充值移步返回标记号，在线充值成功后返回操作改为1
		  $field[]='member_money';//用户该笔记录的金额
		  $field[]='frozen_money';//被冻结的资金
		  $field[]='rank_points';//等级积分，不能作为消费积分
		  $field[]='pay_points';//消费积分，不能作为等级积分
		  $field[]='change_time';//该笔操作发生的时间
		  foreach($langs['lang_arr'] as $v) $field[]=$v;//该笔操作变动原因 
		  $field[]='change_type';//1为在线充值，2为提现，3为管理员调节，4为登录积分(每登录一次加一分),5下订单时操作，6冻结资金；99为其他类型
		  $addData=array();
		  $i=0;
		  foreach($changeArray as $k=>$v){
			  foreach($field as $f){
				  if(array_key_exists($f,$v)){
					  $addData[$i][$f]=$v[$f];
				  }else{
					  $addData[$i][$f]=0;
				  }
			  }
			  $i++;
		  }
		  if($addData)
		  if(!M('member_account')->addAll($addData)) return false;
		  return true;
	 }
		
}
?>