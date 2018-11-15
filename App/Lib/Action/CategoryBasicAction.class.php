<?php
// 本类由系统自动生成，仅供测试用途
class CategoryBasicAction extends BasicAction {

    protected function basicIndex($limitSize){

        $c_id=getNum($_GET['html']); //分类ID
        $attr  =$_GET['attr'];  //属性字符串 
        $min =conversio($_GET['min']);  //最小价(外币强制转换成人民币)
		$max=conversio($_GET['max']); //最大价(外币强制转换成人民币)
		$db=M('goods');
		//基础查询条件
		$where=" WHERE shelves=1 AND recycle_bin=0 AND is_promotion=0 ";         
		 if($c_id){
			$cateData=$this->getCateData($c_id); //(价格分级和分类属性ID集合和面包导航和左边分类数组)
			$this->breadNav=$cateData['bread_nav']; //显示面包导航分类也就是你的分类位置
			$this->leftCates=$cateData['left_cate_list'][0]; //左边分类由大分类自动到小分类
			$where.=" AND goods_category_id IN ({$cateData['in_id']}) ";
			$extendedWhere=" AND goods_category_id IN ({$cateData['in_id']}) "; 
		 }else{
		    $extendedWhere="";
		 }
		 
		//商品属性检索
		if($cateData['goods_attribute_id_all']){
			$this->attrs=$this->showAttr($attr,$cateData['goods_attribute_id_all'],$db);
			//扩展条件(属性)
			if($attr){
				$attrAtt=$this->getGoodsId($attr);
				foreach($attrAtt as $k=>$v){
					$where.=' AND id IN ('.$v.') ';
				}
			}					
		}

		//显示价格分级
		if(!$_GET['keywrods']) {
			if(C('IS_GOODS_CLASS')){
			   $gradeNum=$cateData['goods_attribute_id_all'] ? $cateData['goods_attribute_id_all'] : 6; //价格分级默认6级
			   $grade=$this->showGrade($gradeNum,'goods','goods_retail_price',$where,'min','max');
			   $this->grade=$grade['priceList']; //分级列表
			   $this->selectPrice=$grade['price_select']; //已选价格分级
		   }
		}

		//分类ID存在
       if($c_id){
			if($_GET['keywrods']){
				$keyword=trim(strip_tags($_GET['keywrods']));
				$where.=" AND (".pfix('goods_name')." LIKE '%{$keywrods}%' OR ".pfix('goods_name')." LIKE '%{$keywrods}%') ";			
			}
		}else{
			if($_GET['keywrods']){
				$keywrods=trim(strip_tags($_GET['keywrods']));
				$where.=" AND (".pfix('goods_name')." LIKE '%{$keywrods}%' OR ".pfix('goods_name')." LIKE '%{$keywrods}%') ";			
			}		
		}
		
		if($_GET['city'] > 0){
		    $this->cityName=M('region')->field('region_name')->where(array('region_id'=>$_GET['city']))->find();
		    $where.=" AND city='{$_GET['city']}' ";
		}
		
		//品牌
		if(C('IS_GOODS_BRAND')){
			//显示品牌检索
			$brands=$this->brands($inId);
			$this->brands=$brands['brands_list']; //显示品牌列表
			$this->selectBran=$brands['select_brands']; //已选择品牌
			if($_GET['b']){
				$where.=" AND goods_brand_id='{$_GET['b']}' ";
			}
		}
    
		//大表单查询
		if($_GET['all']){
		    $keyword=$_GET['keywrods2'] ? trim(strip_tags($_GET['keywrods2'])) : '';
			if($keyword) $form.=" AND ".pfix('goods_name')." LIKE '%{$keyword}%' ";
			if($min >= 0 && $max > $min) $form.=" AND (goods_retail_price>='{$min}' AND goods_retail_price<='{$max}') ";
		}else{
		    if($min >= 0 && $max > $min) $form.=" AND (goods_retail_price>='{$min}' AND goods_retail_price<='{$max}') ";
		}
		
		//合拼条件
		if($form){
			$where.=$form;
		}

	   //排序
	   $getOrder=orderList('order_type','order','create_time','');
	   $this->orders=$getOrder['tpl_show_order'];
	   $orderField=$getOrder['db_order'];

      //商品列表
	  $countSql="";
	  $countSql="SELECT count(id) AS counts FROM ".PREFIX."goods ".$where;
	  $counts=$this->DB->query($countSql);
	  $this->_URL_=$_GET['_URL_'];//获取GET参数
	  $this->doPage($counts[0]['counts'],$limitSize,'');	 	
	  $sql="";
	  $field="id,".pfix('goods_name')." AS goods_name,goods_market_price,goods_retail_price,pay_points,";
	  $field.="rank_points,goods_thumb,volume,click_count,stock,hot,is_new,buy_limit";
	  $sql.="SELECT {$field} FROM ".PREFIX."goods ";
	  $sql.=$where." ORDER BY ".$orderField." LIMIT ".$GLOBALS['limit'];
	  $goodsList=$this->DB->query($sql);
	  $this->goodsList=$goodsList;
	  $this->counts=$counts[0]['counts'];
		//热销商品
		$this->hots=$this->getGoodsTb('recycle_bin=0 AND stock > 0 AND shelves=1 AND is_promotion=0 AND hot=1'.$extendedWhere, 'volume DESC', 10);
		
	    //你可能还喜欢
        $this->categoryLove=$this->loves(in_id($goodsList, 'id'),10);
		
		//导航下多图切换广告
		$this->categoryAd=$this->getAd(45,true);	  
		
		if(IS_AJAX){
			$this->display('Piece/Category_index');
		}else{
			$this->display();
		}
	}
    
	//获取分类向上级数的总信息(价格分级和分类属性ID集合和左边分类列表)
    private function getCateData($cId){
		$goodsGategory=M('goods_category');
		$arr=$goodsGategory
		->field(pfix('name')." AS name,id,pid,grade,grade,goods_attribute_id_all,path")
		->order("pid DESC")->select();		
	   $inId=''; 
	   $nav=array();
	   $cate_oen=array();
	   foreach($arr as $k1=>$v1){
			if($v1['id']==$cId){
				$inId.=$v1['id'].',';
				$preatId=$v1['pid'];
			    $cate_oen['grade']=trim($v1['grade']);
			    $cate_oen['goods_attribute_id_all']=trim($v1['goods_attribute_id_all']);	
			    $nav[$v1['id']]['id']=$v1['id'];
			    $nav[$v1['id']]['name']=$v1['name'];
				$path=$v1['path'];
			}else if(strpos($v1['path'],'-'.$cId.'-') !==false){
				$inId.=$v1['id'].',';					
			}					
			if($v1['id']==$preatId){
				$preatId=$v1['pid'];
				if(!$cate_oen['grade']) $cate_oen['grade']=trim($v1['grade']);
				if(!$cate_oen['goods_attribute_id_all']) $cate_oen['goods_attribute_id_all']=trim($v1['goods_attribute_id_all']);	
			    $nav[$v1['id']]['id']=$v1['id'];
			    $nav[$v1['id']]['name']=$v1['name'];				
			}
		}        
	   $cate_oen['in_id']=rtrim($inId,',');
	   ksort($nav);
	   $cate_oen['bread_nav']=$nav;
	   $likes=explode('-',$path);
	   //左边分类导航
	   foreach($arr as $k2=>$v2){
		   if((strpos($v2['path'],'-'.$likes[1].'-') !==false) || ($v2['id']==$likes[1])){
			   $leftArr[$k2]['id']=$v2['id'];
			   $leftArr[$k2]['name']=$v2['name'];
			   $leftArr[$k2]['pid']=$v2['pid'];		   
		   }
	   }
	   $leftArr=unlimitedForLayer($leftArr,'pid','id','child',0);
	   $cate_oen['left_cate_list']=$leftArr;
       return $cate_oen;			
	}

     //显示品牌检索
     private function brands($inIds,$branName='b'){
		if($inIds) $where['goods_category_id'] =array('in',$inIds);
		$where['display'] =1;
     	$db=M('goods_brand');
	    $result= $db->field("id,".pfix('name')." AS name,logo")->where($where)->order(array('orders'=>'ASC'))->select();
        $temp[0]['id']=0;
		$temp[0]['name']=C('LANG_SWITCH_ON') ? L('All'): '全部';
		$temp[0]['logo']='';
		$temp[0]['select']=!$_GET[$branName] ? true : false;
        foreach($result as $k=>$v){
	         $temp[$k+1]['id']=$v['id'];
			 $temp[$k+1]['name']=$v['name'];
			 $temp[$k+1]['logo']=$v['logo'];   
			 $temp[$k+1]['select']=$_GET[$branName] == $v['id'] ? true : false; 	
			 if($_GET[$branName] == $v['id']) $selectBran=$v['name'];//显示已选择的品牌
        }
		$arrays=array('brands_list'=>$temp,'select_brands'=>$selectBran);
        return $arrays;
     }

    //显示属性检索
    private function showAttr($getAttrStr,$attrId,$obj=''){
		$data=array();
    	$attrFilter=$getAttrStr ? explode('-', $getAttrStr) : array();
		$attrId= trim($attrId,',');
		$temp_arrt_url_arr=explode(',',$attrId);
		if(!$attrId) return false;
		$obj=$obj ?  $obj : $obj=M();
		$sql="SELECT ga.id AS ga_id,ga.".pfix('name')." AS ga_name,ga.sort_order,gav.id AS gav_id,gav.".pfix('attr_value').' AS attr_value,gav.orders ';
		$sql.="FROM ".PREFIX."goods_attribute AS ga JOIN ".PREFIX."goods_attribute_value AS gav ON (ga.id=gav.attribute_id) ";
		$sql.="WHERE ga.id IN ({$attrId}) AND ga.attr_index=1 AND ga.is_show=1 AND gav.is_show=1 ORDER BY ga.id ASC ";
		$result=$obj->query($sql);
		//已选择的属性
		if($attrFilter){
			foreach($attrFilter as $k=>$v){
			   foreach($result as $k1=>$v2){
					if($v==$v2['gav_id']){
							$strval=$v2['attr_value'];
							$newStr='';
							foreach($attrFilter as $v3){
								if($v3==$v2['gav_id']) $newStr.='0-';
								else $newStr.=$v3.'-';
							}
						   $attrUrlString[$k]['attr_url_str'].=rtrim($newStr,'-');
						   $attrUrlString[$k]['attr_value']=$strval;													
					 }
				} 			
			}
		}
		$data['select_attr']=$attrUrlString;
		//显示属性列表
		$i=0;$j=0;
		$id=$result[0]['ga_id'];
		$attrlist=array();
		foreach($result as $k=>$v){
			if($id==$v['ga_id']){
				$attrlist[$i]['attribute_id']=$v['ga_id'];
				$attrlist[$i]['attribute_name']=$v['ga_name'];
				$attrlist[$i]['orders']=$v['sort_order'];
				$attrlist[$i]['attr_list'][$j]['attr_id']=$v['gav_id'];
				$attrlist[$i]['attr_list'][$j]['attr_value']=$v['attr_value'];
				$attrlist[$i]['attr_list'][$j]['orders']=$v['orders'];
				$j++;
			}else{
				$j=0;$i++;
				$id=$v['ga_id'];
				$attrlist[$i]['attribute_id']=$v['ga_id'];
				$attrlist[$i]['attribute_name']=$v['ga_name'];
				$attrlist[$i]['orders']=$v['sort_order'];
				$attrlist[$i]['attr_list'][$j]['attr_id']=$v['gav_id'];
				$attrlist[$i]['attr_list'][$j]['attr_value']=$v['attr_value'];
				$attrlist[$i]['attr_list'][$j]['orders']=$v['orders'];				
				$j++;
			}
		}		
		//排序
		$attrlist=multi_array_sort($attrlist,'orders',SORT_ASC);		
		foreach($attrlist as $k=>$v){
			$attrlist[$k]['attr_list']=multi_array_sort($v['attr_list'],'orders',SORT_ASC);	
		}	
		//重组列表
		foreach($attrlist as $k=>$v){
			for($i=0;$i<count($temp_arrt_url_arr);$i++){
				$temp_arrt_url_arr[$i]=$attrFilter[$i] ? $attrFilter[$i] :0;
			}	
			$temp_arrt_url_arr[$k] = 0;   
			$temp_arrt_url = implode('-', $temp_arrt_url_arr);
			$attrlist[$k]['attr_list'][0]['attr_id']=0;
			$attrlist[$k]['attr_list'][0]['attr_value']=C('LANG_SWITCH_ON') ? L('All') : '全部';;
			$attrlist[$k]['attr_list'][0]['select']=empty($attrFilter[$k]) ? true : false;
			$attrlist[$k]['attr_list'][0]['url_str']=$temp_arrt_url;
			
			foreach($v['attr_list'] as $kk=>$vv){
					if($attrFilter[$k]==$vv['attr_id']) $select=true;
					else	$select=false;
                    $temp_arrt_url_arr[$k]=$vv['attr_id']; 
					$attrlist[$k]['attr_list'][$kk+1]['attr_id']=$vv['attr_id'];
                    $attrlist[$k]['attr_list'][$kk+1]['attr_value']=$vv['attr_value'];
				    $attrlist[$k]['attr_list'][$kk+1]['select']=$select;
				    $attrlist[$k]['attr_list'][$kk+1]['url_str']= implode('-', $temp_arrt_url_arr);
			}
		}
		$data['attrs']=$attrlist;
        return $data;
    }
	
	//商品属性筛选的IN(10,20,30,50) 的ID,返回数组 id IN(1,2,3,4)
	private function getGoodsId($attrId){
		$ids='';
		$attrId=explode('-',trim($attrId,'-'));
		$t=0;
		foreach($attrId as $v) {
			if($v > 0) {
				$ids.=$v.',';
				$t++;
			}
		}
		$ids=rtrim($ids,',');
		if(!$ids) return false;
		$result=M('goods_attr')->field("goods_id,attribute_value_id")
		->where(array('attribute_value_id'=>array('in',$ids)))
		->order(array('attribute_value_id'=>'DESC'))->select();
		$attributeIds=$result[0]['attribute_value_id'];
		$i=0;
		$arrs=array();
		foreach($result as $k=>$v){
			if($attributeIds==$v['attribute_value_id']){
				$arrs[$i]['id'].=$v['goods_id'].',';
			}else{
				$i++;
				$attributeIds=$v['attribute_value_id'];
				$arrs[$i]['id'].=$v['goods_id'].',';
			}
		}
		foreach($arrs as $k=>$v) $arrs[$k]=rtrim($v['id'],',');
		$ac=count($arrs);
		if($ac < $t){
			foreach($arrs as $k=>$v){
				$arrs[$ac]=0;
				$ac++;
				if($ac==$t) break;
			}
		}
		return $arrs;
	}	  
     	
}