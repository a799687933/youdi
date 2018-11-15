<?php
//商品分类列表控制器
class CategoryAction extends BasicAction {
    //商品列表页
    public function index(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		//属性检索
	   $getAttrs=rtrim(filterRequst($_GET['attrs']),',');
	   $getAttrsArr=$getAttrs ? explode(',',$getAttrs) : array();
	   //分页条数
	   $page_sze=in_array($_REQUEST['page_size'],array(30,60)) ? $_REQUEST['page_size'] : 30;
	  $this->page_sze=$page_sze;		
	  $goodsIds=	cookie('cookie_collect');//已收藏的商品ID
	  $this->goodsIds=$goodsIds ? explode(',',$goodsIds) : array();//显示未收藏的商品
	  $ids=rtrim(filterRequst($_GET['html']),',');
	  $cate=M('goods_category')->field("id,{$this->langfx}name AS name")->where(array('is_show'=>1))->select();
	  $this->cateCount=count($cate);
	  $this->cateList=$cate;
	  if($ids){
		  $ch=explode(',',$ids);
		  $scs=array();
		  foreach($cate as $k=>$v){
		  	if(in_array($v['id'],$ch)) $scs[$k]=$v;
		  } 
		  //显示选中可关闭导般条
		  $this->scs=$scs;
		  //显示checkbox选择状态
		  $this->ch=$ch;
	   }
	  //基础条件
	  $where="1=1 AND recycle_bin=0 AND shelves=1 ";
	  //只显示收藏的愿望单
	   if($_GET['wishlist']=='condition'){
			if($goodsIds) $where.="AND id IN ({$goodsIds}) ";
			else $where.="AND id=0 ";
	   }
	   //关键字查询
	   if($_GET['keywords']){
	   	   $keywords=filterRequst($_GET['keywords']);
		   $where.="AND (".pfix('goods_name')." LIKE '%{$keywords}%' OR ".pfix('commodity_key')." LIKE '%{$keywords}%') ";
	   }
	   //分类查询
	   if($ids){
	   	  $where.="AND (goods_category_id IN({$ids})) ";
	   }
	   //价格筛选
	   if(is_numeric($_GET['min']) && is_numeric($_GET['max'])){
		   $this->selectPrice=isL(L('Price'),'价格').'：'.$_GET['min'].'-'.$_GET['max'];
		   $where.="AND (goods_retail_price >= '{$_GET['min']}' AND goods_retail_price <= '{$_GET['max']}') ";
		   if($_GET['max'] > $_GET['min']) $this->prcieError='很抱歉，我们暂时无法搜索到符合你要求的商品，请重新尝试。';
		   else $this->prcieError='找不到符合此价格的商品，请修改范围重新尝试';
	   }
	   //显示属性列表
	   $M=M();
	  $sql="SELECT ga.id AS ga_id,ga.".pfix('name')." AS ga_name,ga.sort_order,gav.id AS gav_id,gav.".pfix('attr_value').' AS attr_value,gav.orders ';
	  $sql.="FROM ".PREFIX."goods_attribute AS ga JOIN ".PREFIX."goods_attribute_value AS gav ON (ga.id=gav.attribute_id) ";
	  $sql.="WHERE ga.attr_index=1 AND ga.is_show=1 AND gav.is_show=1 ORDER BY ga.id ASC ";
	  $attList=$M->query($sql);
		$i=0;$j=0;
		$id=$result[0]['ga_id'];
		$attrlist=array();
		foreach($attList as $k=>$v){
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
		//重组列表并得出用户已选择的属性
		$showSelectAttrs=array();
		$si=0;
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
					$checkIds.=$vv['attr_id'].',';
					$attrlist[$k]['attr_ids'][]=$vv['attr_id'];
					if(in_array($vv['attr_id'],$getAttrsArr)) {
						$showSelectAttrs[$si]['attr_id']=$vv['attr_id'];
						$showSelectAttrs[$si]['attribute_name']=$v['attribute_name'];
						$showSelectAttrs[$si]['attr_value']=$vv['attr_value'];
						$si++;
					}
			}
		}	
		//p($showSelectAttrs);
		//显示用户已选择的属性
		$this->showSelectAttrs=$showSelectAttrs;
		
		//检查属性是否有商品
		$checkIds=rtrim($checkIds,',');
        $sql="SELECT attribute_value_id,count(id) AS counts FROM ".PREFIX."goods_attr WHERE attribute_value_id IN ({$checkIds}) GROUP BY attribute_value_id WITH ROLLUP";
		$check=$M->query($sql);
		$cheArrId=array();
		foreach($check as $v) if($v['attribute_value_id']) $cheArrId[]=$v['attribute_value_id'];
	    //把有数据的属性设为true
		foreach($attrlist as $k=>$v){
			foreach($v['attr_list'] as $k1=>$v1){
			   if(in_array($v1['attr_id'],$cheArrId)){
				   $attrlist[$k]['attr_list'][$k1]['is_true']=true;
			   }
			}
		}
		$this->attrs=$attrlist;
		//接授属性检索
	   if($getAttrs){
		   $this->selectAttr=explode(',',$getAttrs);
		   //获得商品ID
		   $goodsIds=M('goods_attr')->field('goods_id')->where("attribute_value_id IN ({$getAttrs})")->group('goods_id')->limit(1000)->select();
		   if($goodsIds){
			   $gid=in_id($goodsIds,'goods_id');
			   $where.="AND id IN ({$gid}) ";
		   }else{
			   $where.="AND id =-1 ";
		   }
	   }
	   //排序
	   $getOrder=orderList('order_type','order','review_score','');
	   $this->orders=$getOrder['tpl_show_order'];
	   $orderField=$getOrder['db_order'];      
       $this->_URL_=$_GET['_URL_'];
	   $g=M('goods');
	   $counts=$g->where($where)->count();
	   $this->doPage($counts,$page_sze,6);
	   $field="id,{$this->langfx}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,goods_thumb,goods_thumb2,stock,volume";
	   $goods=$g->field($field)->where($where)->order($orderField)->limit($GLOBALS['limit'])->select();
	   if($keywords && !$goods){
		   header("location:".U('Modular/serchnone','','').'?keywords='.$keywords);
		   die;
	   }
	   $this->goods=$goods;
	   if(IS_AJAX){
	   	   $this->display('index_ajax');
		   die();
	   }
	  //你可能还喜欢
	  $this->youLike=$this->guessYouLike(0,24,'');
	   //最近浏览
	  $this->recentVisit= $this->recentVisit(0,24,0);	  
      $this->display();
    }
    
	//AJAX检索区域
	public function searchRegion(){
		$model=new CommonModel();
		return $model->searchRegion();
	}			  
  
}