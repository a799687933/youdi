<?php
// 商品内容页公共控制器
class GoodsBasicAction extends BasicAction {
    
	protected function baiscIndex($pageLimit){
			$id=getNum($_GET['html']);
			$db=M('goods');
            $goods=$this->getGoods($db,$id);
			
			//币种
			$goods['CUR_REP']=C('CUR');	
			
			//是否促销商品(优先级一)
			if($goods['is_promotion']){
			   $promotion=$this->goodsIsPromotion($goods['id']);
			   $goods['promotion']=$promotion;
		   } 

		    //商品数量优惠(优先级二)
		   if(empty($promotion)){
		       if(C('IS_GOODS_VOLUME_PRICE'))
			   $numPrice=$this->numPreferential($goods['id']);	
			   $goods['numPrice']=$numPrice;
		   }

		   //会员等级价格(优先级三)
		   if(empty($numPrice) && empty($promotion)){
			   if(C('IS_GOODS_MEMBER_PRICE'))
			   $gradePrice=$this->getGoodsMemberPrice($db,$goods['id']);	
			   $goods['gradePrice']=$gradePrice;
		    }		

			//记录分类ID猜你喜欢
			$this->guessYouLike($goods['goods_category_id'],0,'');		
			
			//记录最近浏商品ID
			if($goods) $visit=$this->recentVisit($id,11);		
			
			//增加点击量		
			$this->goodsClick($id);	
			
		    //你的位置
			$goods['poistion']=$this->positions($goods['goods_category_id']);
		
			//商品属性
			if(C('IS_GOODS_ATTRIBUTE')){					
				$goods['attrs']=$this->getGoodsAttr($db,$goods['id']);//商品属性	
		    }	
		
		//物流处理
		if(C('IS_GOODS_LOGISTICS')){
			if($goods['logistics_tpl_id'] > 0){
			    $logisGoods['id']=$goods['id'];
				$logisGoods['goods_weight']=$goods['goods_weight'];
				$logisGoods['volume_m3']=$goods['volume_m3'];
				$logisGoods['logistics_tpl_id']=$goods['logistics_tpl_id'];
	            $goods['logistics']=$this->basicGetFreight($logisGoods,'',''); //自动定位用户位置
			}			
			//物流省份
			$goods['province'] =M('region')->field('region_id,region_name')->where(array('region_type'=>1,'is_show'=>1))
			->order(array('orders'=>'ASC'))->select();
		}
		 
           //组合购买
		   if(C('IS_GOODS_ZHUHE')) $goods['zhuhe']=$this->goodsZhuhe($goods['id'],$db);
		
		//用户咨询	
		if(C('IS_CONSUlTATION')){
			$sql="SELECT gc.*,if(m.nickname <>'',m.nickname,m.user_name) AS user_name,m.head_pic FROM ".PREFIX."goods_consultant AS gc ";
			$sql.=" LEFT JOIN ".PREFIX."member AS m ON(gc.user_id=m.id) WHERE gc.goods_id='{$goods['id']}' AND gc.is_show=1 ";
			$goods['consultant']=$db->query($sql);
		}
		//p($goods);die;
		return $goods;
   }

	//组合购买祥情页
	protected function basicCombination(){
	       $ids=filterRequst(rtrim($_GET['ids'],','));
		   if(empty($ids)) return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'));
		   $idArr=explode(',',$ids);
		   $goodsId=$idArr[0];
		   unset($idArr[0]);
		   if(empty($idArr)) return array('success'=>false,'msg'=>isL(L('IllegalOperation'),'非法操作'));
		   $ids=implode(',',$idArr);
		   $db=M('goods');
		   //获取组合数据
		   $clle="(SELECT count(c.rec_id) AS rec_id FROM ".PREFIX."goods_collect AS c WHERE c.goods_id=g.id) AS gc_counts";
		   $sql="SELECT {$clle},z.zhuhe_price,z.id AS z_id,g.id,g.".pfix('goods_name')." AS goods_name,g.goods_market_price,g.goods_retail_price,";
		   $sql.="g.goods_thumb,g.volume,g.review_score,g.good_per,g.review_score,g.review_count,g.goods_photo FROM ";
		   $sql.=PREFIX."goods_zhuhe AS z ";
		   $sql.="JOIN ".PREFIX."goods AS g ON (g.id=z.zhuhe_goods_id) ";
		   $sql.="WHERE g.shelves > 0 AND g.recycle_bin=0 AND z.main_goods_id='{$goodsId}' AND z.zhuhe_goods_id IN ({$ids}) ";
		   $z=$db->query($sql);
		   if(empty($z)) return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'));
		   $data=$this->getGoods($db,$goodsId);		  
		   if(empty($data)) return array('success'=>false,'msg'=>isL(L('NoSuchData'),'不存在此数据'));
		   $goods['list'][0]=$data;
		   //相册
		   foreach($z as $k=>$v){
			   $z[$k]['goods_photo']=$v['goods_photo'] ? json_decode($v['goods_photo'],true) : array();
			   $goods['list'][$k+1]=$z[$k];
		   }
		  $goods['attrs']=$this->getGoodsAttr($db,$goodsId.','.$ids);
		  return array('success'=>true,'msg'=>'','data'=>$goods);
	}
	
	//获取商品信息
	private function getGoods($db,$id){
			$clle="(SELECT count(c.rec_id) AS rec_id FROM ".PREFIX."goods_collect AS c WHERE c.goods_id=g.id) AS gc_counts,";//收藏个数
			$brand="(SELECT b.".pfix('name')." FROM ".PREFIX."goods_brand AS b WHERE b.id=g.goods_brand_id LIMIT 1) AS brand_name ";//品牌
			$field="g.*,{$clle}{$brand} ";
			$gsql="SELECT {$field} FROM ".PREFIX."goods AS g  ";
			$gsql.="WHERE g.id='{$id}' AND g.shelves=1 AND g.recycle_bin =0 LIMIT 1  ";
			$goods=$db->query($gsql);
			$goods[0]['goods_photo']=$goods[0]['goods_photo'] ? json_decode($goods[0]['goods_photo'],true) : array();
			$goods[0][pfix('parameters')]=$goods[0][pfix('parameters')] ? json_decode($goods[0][pfix('parameters')],true) : array();
            return $goods[0];	
	}
   
   //组合购买
   protected function goodsZhuhe($gooodsId){
	    $db=$db ? $db : M();
        $sql="SELECT g.id AS goods_id,g.".pfix('goods_name')." AS goods_name,g.goods_market_price,g.goods_retail_price,g.goods_thumb,";
        $sql.="z.id AS z_id,z.zhuhe_price FROM ".PREFIX."goods AS g ";
		$sql.="JOIN ".PREFIX."goods_zhuhe AS z  ON (z.zhuhe_goods_id=g.id) WHERE z.main_goods_id=".$gooodsId;
		return $db->query($sql);
   }   
	
	//选择属性动太显示价格
	protected function basicAttrGoodsPrice(){
	   $this->hckToken();
		if(!IS_AJAX) json_code(0,'Error!');
		$goodsId=$_REQUEST['goods'];  //商品ID
		$attrs=$_REQUEST['attrIds'] ? rtrim($_REQUEST['attrIds'],',') : 0; //属性ID集合
		//$freight=$_REQUEST['freight'] ? intval($_REQUEST['freight']) :  json_code(0,'非法操作!'); //物流表ID
		$number=$_REQUEST['number'] ? intval($_REQUEST['number']) : $this->myInfos(false,'',isL(L('IllegalOperation'),'非法操作'),'','',''); //商品数量
		$where['shelves']=1;
		$where['recycle_bin']=0;
		$where['stock']=array('gt',0);
		$where['id']=$goodsId;		
		$res=M('goods')->field('goods_retail_price,stock,is_promotion,trade_integral')->where($where)->find();
		if($res){
			    if(C('CHECK_STOCK')){
			    	if($number > $res['stock']) $this->myInfos(false,'',isL(L('InsufficientInventory'),'库存量不足'),'','','');
			    }				
				$attr=M('goods_attr')->where(array('id'=>array('in',$attrs),'goods_id'=>$goodsId))->sum('attr_price');
				$price=0;
				if($res['is_promotion']==1){
					$times=time();
					$proWhere=array('goods_id'=>$goodsId,'no_ffo'=>1,'promotion_start_date'=>array('elt',$times),'promotion_end_date'=>array('egt',$times));
					$promotion=M('goods_promotion')->field('promotion_price')->where($proWhere)->find();
					if($promotion){
						$res['goods_retail_price']=$promotion['promotion_price'];
					}
				}
                $price=($res['goods_retail_price'] + intval($attr)) * $number;
				$tradeIntegral=$res['trade_integral'] * $number;
				$price=number_format($price,2, '.', '');
				$this->myInfos(true,'','','','',array('price'=>$price,'logistic_price'=>'','trade_integral'=>$tradeIntegral));
				//json_code(1,'','',$price);		
		}else{
			   $this->myInfos(false,'',isL(L('IllegalOperation'),'非法操作'),'','','');
		       //json_code(0,'非法操作3!'); 
		}
	}
	
    //收藏处理
	protected function basicCollection(){
		nonlicet_url();
	     if(!is_login()){
			 $this->myInfos(false,'',isL(L('PleaseLogIn'),'请先登录'),U(is_m().'/Login/index','','').'?url='.base64_encode($_SERVER["HTTP_REFERER"]),'','');
		 }
		 $userId=getUserC('SE_USER_ID');
		 //检查是否已收藏过
		 $where['user_id']=$userId;
		 $where['goods_id']=$_REQUEST['id'];
		 $times=time();
		 if(!$goods=M('goods')->field('id,is_promotion')->where(array('id'=>$_REQUEST['id']))->find())
		 $this->myInfos(false,'',isL(L('IllegalOperation'),'非法操作'),'','','');
		 $tableName='goods_collect';
		 $errorInfo=isL(L('HasBeenCollected'),"你已收藏过此商品，无需重复收藏！");
		 $successInfo=isL(L('Success'),'操作成功');
		 $data['goods_id']=$_REQUEST['id'];
		 $data['user_id']=$userId;		 
		 $data['add_time']=$times;
		 $data['rec_type']=$goods['is_promotion'] ? 3 : 0;
		 $data['is_attention']=0;
		 $DB=M($tableName);
		 if($DB->where($where)->find()) $this->myInfos(false,'',$errorInfo,'','','');		
		 if($DB->add($data)){
			 $this->myInfos(true,'',$successInfo,$_SERVER["HTTP_REFERER"],'','');		
		 }else{
			 $this->myInfos(false,'',$errorInfo,'','','');		
		 }
	}

	//是否是促销商品,并提取相关数据
	private function goodsIsPromotion($goodsId){
		$times=time();
		$where['goods_id']=$goodsId;
		$where['no_ffo']=1;
		$where['promotion_start_date']=array('ELT',$times);
		$where['promotion_end_date']=array('EGT',$times);
	   $res=M('goods_promotion')->field('id,promotion_price,promotion_start_date,promotion_end_date,numbers')->where($where)->find();
	   return $res;
	}

 
 	//是否增送红包
	private function getGoodsRed($bonusTypeId){
		if(!$bonusTypeId) return 0;
		$times=time();
		$where=array(
		    'id'=>$bonusTypeId,
		    'send_start_date'=>array('ELT',$times),
		    'send_end_date'=>array('EGT',$times),
		);
		$res=M('bonus_type')->field('bonus_money')->where($where)->find();
		return $res['bonus_money'] ? $res['bonus_money'] : 0;
	}
	
	//会员等级价格
	private function getGoodsMemberPrice($db,$goodsId){
		$sql="SELECT mg.id,mg.".pfix('grade_name')." AS grade_name,mp.member_price FROM ".PREFIX."member_grade AS mg,";
		$sql.=PREFIX."goods_member_price AS mp ";
		$sql.="WHERE mp.member_grade=mg.id AND mp.goods_id=".$goodsId;
		$res=$db->query($sql);
		return $res ? $res : '';
	}
	
	//商品数量优惠
	private function numPreferential($goodsId){
		$res=M('goods_volume_price')->where(array('goods_id'=>$goodsId))->order(array('discount_sum'=>'ASC'))->select();
		return $res ? $res : '';
	}

    //相同属性关联商品
    private function attrSimilarGoods($db,$attrArr,$goodsId){
        $arr=array();
    	foreach($attrArr as $k=>$v){
	    	$sql="SELECT g.id,g.goods_name,g.goods_retail_price,g.goods_thumb FROM ".PREFIX."goods AS g ".
	        "JOIN ".PREFIX."goods_attr AS a ON (g.id=a.goods_id) AND a.attr_value='{$v['value']}' AND g.id <>'{$goodsId}' ORDER BY g.id DESC LIMIT 10";   
			$res=$db->query($sql);
			$arr[$k]['title']='相同'.$v['name'].'的商品推荐';		 
			$arr[$k]['goodsList']=$res;  		
    	}
       return $arr ? $arr : '';
    }
	
	/**
	*获取物流运费,使用新良IP接口查询写入$_COOKIE['position_city']
	*$goodsData  如果是数组是商品数据数组,否则是商品ID
	*$cityName 城市名称，如果指定城市就不使用自动获取城市
	*$district  区县级ID
	*商品ID
	*/
    public function basicGetFreight($goodsData,$cityName='',$district=''){
	       if(is_array($goodsData)){
			   $goods=$goodsData;
		   }else{
			   $goods=M('goods')->field("id,goods_weight,volume_m3,logistics_tpl_id")->where("id='{$goodsData}' AND shelves=1 AND recycle_bin=0 AND stock > 0")->find();
		   }
		   //是否存在此商品
		   if(!$goods){
			   $loginstics['success']=false;
			   $loginstics['info']='不存在此数据111111';
			   if(IS_AJAX){
				   die(json_encode($loginstics));
			   }else{
				   return $loginstics;
			   }			   
		   }
		   //获取城市
		   if($cityName){
			    $city=$cityName;
		   }else{
				if($_COOKIE['position_city']){
					$city=$_COOKIE['position_city'];
				}else{
					$ip=get_ip();
					if($ip=='127.0.0.1') $ip='125.95.26.15';
					$getIpUrl='http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
					$ipString=file_get_contents($getIpUrl);
					$str=preg_match_all('/\{(.*)\}/',$ipString,$iparr);
					$ipar=json_decode($iparr[0][0],true);
					$city=$ipar['city'];	
					$cityDistrict=$ipar['district'];		
					if($city) cookie('position_city',$city); //把所在城市写入COOKIE
				}				
			}

			//获取运费模板
			$logisticsTpl=M('logistics_tpl')->where(array('id'=>$goods['logistics_tpl_id']))->find();
			$loginstics=array();
			if(!$logisticsTpl) {
			   $loginstics['success']=false;
			   $loginstics['info']='不存在此数据22222';
			   if(IS_AJAX){
				   die(json_encode($loginstics));
			   }else{
				   return $loginstics;
			   }
			} 
			
			//城市区域
			if($district){
				 $db=M('region');
				 $names=$db->field('region_id,parent_id,region_name')->where(array('region_id'=>$district,'is_show'=>1))->find();		
				 $i=0;
				 $infoCity=array();
				 while(true){
					 $res=$db->field('region_id,parent_id,region_name')->where(array('is_show'=>1,'region_id'=>$names['parent_id']))->find();
					 $names['parent_id']=$res['parent_id'];
					 $infoCity[$i]['city_name']=$res['region_name'];
					 $infoCity[$i]['city_id']=$res['region_id'];
					 if($names['parent_id']==1) break;
					 $i++;
				 }	
				 $provinceCityStr=$infoCity[1]['city_name'].$infoCity[0]['city_name'].$names['region_name'];
				 $cityId=$infoCity[0]['city_name'];
			}else{
				 $sql="SELECT r2.region_name FROM ".PREFIX."region AS r1 JOIN ".PREFIX."region AS r2 ";
				 $sql.="ON(r1.parent_id=r2.region_id) WHERE r1.region_name='{$city}' LIMIT 1";
				 $citys=M()->query($sql);		
				if(!$citys){
				   $loginstics['success']=false;
				   $loginstics['info']='不存在此数据33333';					
				   if(IS_AJAX){			   
					   die(json_encode($loginstics));
				   }else{
					   return $loginstics;
				   }				
				}					 
				 $provinceCityStr=$citys[0]['region_name'].$city.$cityDistrict;
			}

			//非免运费商品
			if($logisticsTpl['free_shipping']==0){
			    $tplPriceList=M('logistics_tpl_price')->where(array('tpl_id'=>$logisticsTpl['id']))->order('id ASC')->select();
				foreach($tplPriceList as $k=>$v){
					$temp=explode('、',rtrim($v['city_name'],'、'));
					if(in_array($city,$temp)){
						$loginstics['success']=true;
						$loginstics['is_free']=false;//是否免运费
						$loginstics['id']=$v['id'];
						$loginstics['tpl_id']=$v['tpl_id'];
						$loginstics['shipping_id']=$v['shipping_id'];
						$loginstics['pricing']=$v['pricing'];
						$loginstics['province_id']=$v['province_id'];
						$loginstics['first_number']=$v['first_number'];
						$loginstics['first_price']=$v['first_price'];
						$loginstics['add_numeber_moer']=$v['add_numeber_moer'];
						$loginstics['add_price_moer']=$v['add_price_moer'];
						break;
					}
				}
				if(!$loginstics){//没有指定区域的运费使用默认的运费
					$loginstics['success']=true;
					$loginstics['is_free']=false;//是否免运费
					$loginstics['id']='';
					$loginstics['tpl_id']=$logisticsTpl['id'];
					$loginstics['pricing']=$logisticsTpl['pricing'];
					$loginstics['province_id']=$logisticsTpl['province'];
					$loginstics['first_number']=$logisticsTpl['default_number'];
					$loginstics['first_price']=$logisticsTpl['default_price'];
					$loginstics['add_numeber_moer']=$logisticsTpl['add_number'];
					$loginstics['add_price_moer']=$logisticsTpl['add_price'];
				}
			}else{ //免运费商品
					$loginstics['success']=true;
					$loginstics['is_free']=true;//是否免运费
					$loginstics['id']='';
					$loginstics['tpl_id']=$logisticsTpl['id'];
					$loginstics['province_id']=$logisticsTpl['province'];
					$loginstics['first_number']=$logisticsTpl['default_number'];
					$loginstics['first_price']=$logisticsTpl['default_price'];
					$loginstics['add_numeber_moer']=$logisticsTpl['add_number'];
					$loginstics['add_price_moer']=$logisticsTpl['add_price'];
			}	 
			$loginstics['province_city_str']=$provinceCityStr;
			//处理计价方式
			if($loginstics['pricing']==1){//按体重单位克
			     if($goods['goods_weight'] > $loginstics['first_number']){
					 $firstNumber=$goods['goods_weight'] - $loginstics['first_number'];
					 $firstNumber=ceil($firstNumber / $loginstics['add_numeber_moer']);
					 if($firstNumber > 0){
						 $loginstics['first_price']=$loginstics['first_price'] + ($firstNumber * $loginstics['add_price_moer']);
					 }
				 }
			}else if($loginstics['pricing']==2){ //按体积单位平方厘米(cm³)
			     if($goods['volume_m3'] > $loginstics['first_number']){
					 $firstNumber=$goods['volume_m3'] - $loginstics['first_number'];
					 $firstNumber=ceil($firstNumber / $loginstics['add_numeber_moer']);
					 if($firstNumber > 0){
						 $loginstics['first_price']=$loginstics['first_price']+($firstNumber * $loginstics['add_price_moer']);
					 }
				 }			    
			}
			//是否有检索到的物流公司
			if($loginstics['shipping_id']){
				$shipping=M('shipping')->field("shipping_name")->where("shipping_id='{$loginstics['shipping_id']}'")->find();
				$loginstics['first_price']=$shipping['shipping_name'].'('.getPrice($loginstics['first_price'],true).')';
			}else{
				$loginstics['first_price']=isL(L('DefaultShipping'),'默认运费').'('.getPrice($loginstics['first_price'],true).')';
			}
			
			//当前自动定位的城市
			$loginstics['city_name']=$city;
		    if(IS_AJAX){
			   die(json_encode($loginstics));
		    }else{
			   return $loginstics;
		    }			
	}
	
	//获取物流城市和区县
	protected function BasicgetContets(){
		$goodsId=$_GET['goods_id'];
		$regionId=$_GET['id'];
		$child="(SELECT r1.region_id FROM ".PREFIX."region AS r1 WHERE r1.parent_id=r.region_id AND r1.is_show=1 LIMIT 1) AS child,";
		$sql="SELECT {$child}r.region_id,r.parent_id,r.region_name FROM ".PREFIX."region AS r WHERE r.is_show=1 AND (r.parent_id='{$regionId}' OR region_id='{$regionId}') ORDER BY r.region_id ASC";
		$result=M()->query($sql);
		if($result){
			$str='';
			foreach($result as $k=>$v){
				//$str.='<li><a href="javascript:void(0);" onClick="ajaxGo(\''.$v['region_name'].'\','.$tplId.')">'.$v['region_name'].'</a></li>'; //只获取城市
				if($k > 0){
					if(!$v['child']){
						$str.='<li><a href="javascript:void(0);" onClick="ajaxGo(\''.$result[0]['region_name'].'\','.$goodsId.','.$v['region_id'].')">'.$v['region_name'].'</a></li>';
					}else{
						$str.='<li><a href="javascript:void(0);" onClick="getCitys(2,'.$v['region_id'].')">'.$v['region_name'].'</a></li>';	
					}
				}
			}
			die($str);
		}else{
			die(' ');
		}
	}

    //用户咨询表单处理请求处理
    protected function basicConsultant(){
    	if(!is_login()){
			$url=U('Login/index',array('url'=>base64_encode(U('Goods/index',array('html'=>'g_'.$_REQUEST['goods_id'])))));
			$this->myInfos(false,'',isL(L('PleaseLogIn'),'请先登录'),$url,'','');
    	} 
		$goods=M('goods')->field('id')->where("id='{$_REQUEST['goods_id']}'")->find();
		if(!$goods) die('Error');
		$data['goods_id']=$goods['id'];
		$data['user_id']=getUserC('SE_USER_ID');
		$data['title']=$_REQUEST['title'] ? emptyHtml($_REQUEST['title']) : die('Error');
		$data['content']=$_REQUEST['content'] ? emptyHtml($_REQUEST['content']) : die('Error');
		$data['add_time']=time();
		$data['reply']='';
		$data['reply_time']=0;
		if(M('goods_consultant')->add($data)){
			$this->myInfos(true,'submits',isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
		}else{
			$this->myInfos(false,'submits',isL(L('Failure'),'操作失败'),'','','');
		}
    }

	/**
	*商品内容页显示商品属性
	*$db    obj 模型对像
	*$goodsIds   商品ID，支持多个商品 IN(1,2,3,4)
	*return array();
	*获取商品属性时 p($attrAll[1]);获取商品ID为1的商品属性
	*/
	public function getGoodsAttr($db,$goodsIds){
		$db= $db ? $db : M();
		$sql="SELECT distinct ga.id AS ga_id,ga.".pfix('name')." AS ga_name,ga.sort_order,gav.id AS gav_id,gav.".pfix('attr_value').' AS attr_value,gav.orders,';
		$sql.="attr.id AS attr_id,attr.goods_id,attr.attr_price,attr.attr_stock,attr.attr_pic ";
		$sql.="FROM ".PREFIX."goods_attribute AS ga JOIN ".PREFIX."goods_attribute_value AS gav ON (ga.id=gav.attribute_id) ";
		$sql.="JOIN ".PREFIX."goods_attr AS attr ON(attr.attribute_value_id=gav.id) ";
		$sql.="WHERE attr.goods_id IN ({$goodsIds}) AND ga.is_show=1 AND gav.is_show=1 ORDER BY attr.goods_id ASC";	
		$result=$db->query($sql);
		
		$tempId=$result[0]['goods_id'];
		$attr=array();
		$i=0;
		foreach($result as $k=>$v){
			if($tempId==$v['goods_id']){
				$attr[$tempId][$i]['ga_id']=$v['ga_id'];
				$attr[$tempId][$i]['ga_name']=$v['ga_name'];
				$attr[$tempId][$i]['sort_order']=$v['sort_order'];
				$attr[$tempId][$i]['attribute_id']=$v['gav_id'];
				$attr[$tempId][$i]['attr_id']=$v['attr_id'];
				$attr[$tempId][$i]['attr_value']=$v['attr_value'];
				$attr[$tempId][$i]['attr_price']=$v['attr_price'];
				$attr[$tempId][$i]['attr_stock']=$v['attr_stock'];
				$attr[$tempId][$i]['attr_pic']=$v['attr_pic'];
				$attr[$tempId][$i]['orders']=$v['orders'];
				$attr[$tempId][$i]['goods_id']=$tempId;
				$i++;
			}else{
				$i=0;
				$tempId=$v['goods_id'];
				$attr[$tempId][$i]['ga_id']=$v['ga_id'];
				$attr[$tempId][$i]['ga_name']=$v['ga_name'];
				$attr[$tempId][$i]['sort_order']=$v['sort_order'];
				$attr[$tempId][$i]['attribute_id']=$v['gav_id'];
				$attr[$tempId][$i]['attr_id']=$v['attr_id'];
				$attr[$tempId][$i]['attr_value']=$v['attr_value'];
				$attr[$tempId][$i]['attr_price']=$v['attr_price'];
				$attr[$tempId][$i]['attr_stock']=$v['attr_stock'];
				$attr[$tempId][$i]['attr_pic']=$v['attr_pic'];
				$attr[$tempId][$i]['orders']=$v['orders'];
				$attr[$tempId][$i]['goods_id']=$tempId;				
				$i++;
			}
		}
		unset($result);
		$attrAll=array();
		foreach($attr as $key=>$val){
			$i=0;$j=0;
			$id=$attr[$key][0]['ga_id'];	
			$attrlist=array();
			$val=multi_array_sort($val,'ga_id',SORT_ASC);		
			foreach($val as $k=>$v){
				if($id==$v['ga_id']){
					$attrlist[$i]['attribute_id']=$v['ga_id'];
					$attrlist[$i]['attribute_name']=$v['ga_name'];
					$attrlist[$i]['orders']=$v['sort_order'];
					$attrlist[$i]['attr_list'][$j]['attribute_id']=$v['attribute_id'];
					$attrlist[$i]['attr_list'][$j]['attr_id']=$v['attr_id'];
					$attrlist[$i]['attr_list'][$j]['attr_value']=$v['attr_value'];
					$attrlist[$i]['attr_list'][$j]['attr_price']=$v['attr_price'];
					$attrlist[$i]['attr_list'][$j]['attr_stock']=$v['attr_stock'];
					$attrlist[$i]['attr_list'][$j]['attr_pic']=$v['attr_pic'];
					$attrlist[$i]['attr_list'][$j]['orders']=$v['orders'];		
					$j++;
				}else{
					$j=0;$i++;
					$id=$v['ga_id'];
					$attrlist[$i]['attribute_id']=$v['ga_id'];
					$attrlist[$i]['attribute_name']=$v['ga_name'];
					$attrlist[$i]['orders']=$v['sort_order'];
					$attrlist[$i]['attr_list'][$j]['attribute_id']=$v['attribute_id'];
					$attrlist[$i]['attr_list'][$j]['attr_id']=$v['attr_id'];
					$attrlist[$i]['attr_list'][$j]['attr_value']=$v['attr_value'];
					$attrlist[$i]['attr_list'][$j]['attr_price']=$v['attr_price'];
					$attrlist[$i]['attr_list'][$j]['attr_stock']=$v['attr_stock'];
					$attrlist[$i]['attr_list'][$j]['attr_pic']=$v['attr_pic'];
					$attrlist[$i]['attr_list'][$j]['orders']=$v['orders'];				
					$j++;
				}
			}		
			$attrlist=multi_array_sort($attrlist,'orders',SORT_ASC);		
			foreach($attrlist as $k1=>$v1){
				$attrlist[$k1]['attr_list']=multi_array_sort($v1['attr_list'],'orders',SORT_ASC);	
			}	
			$attrAll[$key]=$attrlist;
		}
		unset($attr);
		//p($attrAll);die;
		return $attrAll;
	}			
	
	/**
	*增加商品点击率，每个商品同一个用户一天内只增加一个击率
	*$goodsId  商品ID
	*/
	public function goodsClick($goodsId=0){
		if($goodsId==0) $goodsId=$_REQUEST['goods_id'];
	    if($goodsId){
			if(strpos($_COOKIE['goods_click_count'],$goodsId) ===false){
					 M('goods')->where("id={$goodsId}")->setInc('click_count'); 
					 $srtatTime=strtotime(date('Y-m-d'));
					 $endTime=$srtatTime + 86400;
					 $saveTime=$endTime - time();	
					 cookie('goods_click_count',$_COOKIE['goods_click_count'].','.$goodsId,$saveTime);
			}		
		}
	}	
		
}