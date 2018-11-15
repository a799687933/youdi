<?php
// 本类由系统自动生成，仅供测试用途

class GoodsAction extends CommonAction {

	//全部商品列表
    public function goodsAllList(){
       $this->_goodsList();
       $this->display('Goods/goodsList');	//重定义模板路径		   
    }
	
	//未审核商品
	public function notAudit(){
       $this->_goodsList();
       $this->display('Goods/goodsList');	//重定义模板路径		
	}
	
	//已上架商品列表
    public function goodsShelvesList(){
       $this->_goodsList();
       $this->display('Goods/goodsList');	//重定义模板路径		   
    }
    
	//未上架商品列表
	public function goodsNotShelvesList(){
		$this->_goodsList();
		$this->display('Goods/goodsList');//重定义模板路径
	}
	
	//商品回收站列表
	public function recycleBin(){
		$this->_goodsList();
		$this->display('Goods/goodsList');//重定义模板路径		
	}

	//添加新商品界面
	public function goodsAdd(){	
        $this->_publicAddSave();
		//指定会等级的价格，获取会员等级
		$member=M('member_grade')->field('id,'.pfix('grade_name').' AS grade_name,discount')->order(array('id'=>'ASC'))->select();  
		$this->member=$member;
		$this->json_date=json_encode($member); //js动态付值会员价格*/	
		//商品属性类型
		if(C('IS_GOODS_ATTRIBUTE')){	
		     $this->attrType=M('goods_attr_type')->select(); 
		}
		$this->display();
	}

	//修改商品界面
	public function goodsEditing(){
		$db=M();
		$this->_publicAddSave();
		$goods=M('goods')->where(array('id'=>$_GET['id']))->find();

		//规格参数
		$parametersEdit=$this->langIsMore('goods','parameters');
		$pars=array();
		foreach($parametersEdit as $pk=>$pv){
			$goods[$pv['field']]=json_decode($goods[$pv['field']],true);
		}
		
		$this->goods=$goods;
		//获取原有相关相册
		$this->photo=$goods['goods_photo'] ? json_decode($goods['goods_photo'],true) : array();		
		//会员等级表
		$grade=M('goods_member_price')->field('member_price')->where(array('goods_id'=>$_GET['id']))->select();
		$this->grade=$grade;
		$member=M('member_grade')->field('id,'.pfix('grade_name').' AS grade_name,discount')->order(array('id'=>'ASC'))->select();  //指定会等级的价格，获取会员等级
		$this->member=$member;
		$this->json_date=json_encode($member); //js动态付值会员价格*/				
		
		 //商品数量优惠价格
		 if(C('IS_GOODS_VOLUME_PRICE')){
		    $this->volume=M('goods_volume_price')->field('discount_sum,preferential')->where(array('goods_id'=>$_GET['id']))->select();
		 }
		 
		//是否促销商品
		if(C('IS_PROMOTION')){
			$promotion=M('goods_promotion')->where(array('goods_id'=>$_GET['id']))->find();
			$this->promote=$promotion;
		}		
		
		//获取原有关联商品
		if(C('IS_GOODS_LINK')){
			$sql="SELECT l.link_goods_id,l.is_double,l.goods_id,g.".pfix('goods_name')." AS goods_name FROM ";
			$sql.=PREFIX."goods_link AS l  JOIN ";
			$sql.=PREFIX."goods AS g ON (l.link_goods_id=g.id) WHERE l.goods_id ='".$_GET['id']."' ";
			$this->goodsRelation=$db->query($sql);
		}
		
		//获取原有关联配件
		if(C('IS_GOODS_ACCESSORIES')){
			$sql='';
			$sql.="SELECT a.goods_acc_id,a.goods_id,g.".pfix('goods_name')." AS goods_name FROM ";
			$sql.=PREFIX."goods_accessories AS a JOIN ";
			$sql.=PREFIX."goods AS g ON(a.goods_acc_id=g.id) WHERE a.goods_id='".$_GET['id']."' ";
			$this->accessories=$db->query($sql);
		}
		
		//获取原有关联文章
		if(C('IS_GOODS_LINK_ARTICLE')){
			$sql='';
			$sql.="SELECT g.article_id,a.".pfix('titletext')."titletext FROM ";
			$sql.=PREFIX."goods_link_article AS g JOIN ";
			$sql.=PREFIX."article AS a ON(a.id=g.article_id) WHERE g.goods_id = '".$_GET['id']."' ";
			$this->article=$db->query($sql);
		}

	   //获取原有组合购买
	   if(C('IS_GOODS_ZHUHE')){
		   import('Class.ZuHeGoods',APP_PATH);
		  $result=ZuHeGoods::getZuGoods($_GET['id'],false);
		  $this->ZuHeGoods=$result;				   
	   }
        
		//获取原有商品属性	
		if(C('IS_GOODS_ATTRIBUTE')){
			$this->attrList=$this->getGoodsAttr($goods['goods_attr_type_id'],$_GET['id']);
			$this->type=$_REQUEST['type'];					
			//商品属性类型
			$this->attrType=M('goods_attr_type')->select(); 
        }
		
		//获取已选择分类
		$category=M('goods_category');
		$path=$category->field('path')->where(array('id'=>$goods['goods_category_id']))->find();
		$path=explode('-',$path['path']);
		unset($path[0]);
		$cate=array();
		$cateIdAll='';
		
		foreach($path as $k=>$v){
			$field="c1.id,c1.".pfix('name')." AS name,c1.pid,(IF(c1.id={$v},1,0)) AS selected ";
			$sql="SELECT {$field} FROM ".PREFIX."goods_category AS c1 WHERE c1.pid=(SELECT c2.pid FROM ".PREFIX."goods_category AS c2 WHERE c2.id='{$v}' AND c2.is_show=1)  ORDER BY c1.reorder ASC";
			 $lists=$category->query($sql);
		     $cate[$k]['lists']=$lists;
			 $cateIdAll.=in_id($lists,'id').',';
		}
		$this->countCate=count($cate);
        $this->selectCate=$cate;
		
		//获取一级分类的所有品牌列表
		$cateIdAll=rtrim($cateIdAll,',');
		$cateBand=$this->getCateBand($cateIdAll,true);
		$bands='';
		$count6=0;
		foreach($cateBand as $k=>$v){
			if($v['id']==$goods['goods_brand_id']) $selected="checked='checked'";
			$bands.='<label><input type="radio" '.$selected.' name="goods_brand_id" value="'.$v['id'].'"  style="position:relative;top:3px;"/>'.$v['name'].'</label>';
			$count6++;
			$selected='';
			if($count6==7){
				$bands.='<br/>';
				$count6=0;
		    }			
		}
		$this->bands=$bands;
		
        $this->display('Goods/goodsAdd');// 重定义模板路径与仓库共用
	}

	//添加与修改公共模板付值
	private function _publicAddSave(){
		$curd=D('Curd');		
		//获取供应商
		$this->supplier=M('goods_supplier')->field('id,name')->select();
		
		//商品品牌
		//$this->brandList=M('goods_brand')->field(pfix('name').' AS name,id')->where('display=1')->order("orders ASC")->select();
		
		//商品分类
	    $goodCat=M('goods_category')->where(array('is_show'=>1))->order("reorder ASC")->select();
		
	    import('Class.Category',APP_PATH);
		$goodCat=Category::unlimitedForLevel($goodCat,'id','pid');
		
		//分类检索
		$this->goodCat=$goodCat;
		
		//一级商品分类
		$pidArr=array();
		foreach($goodCat as $k=>$v) if($v['pid'] > 0) $pidArr[]=$v['pid'];
		$oenCate=array();
		foreach($goodCat as $k=>$v){
			if($v['pid'] == 0){
				$oenCate[$k]['id']=$v['id'];
				$oenCate[$k]['name']=$v[pfix('name')];
				$oenCate[$k]['is_child']=in_array($v['id'], $pidArr) ? true : false;
			}
		}
		$this->oenCate=$oenCate;
		
	    //文章分类
	    if(C('IS_GOODS_LINK_ARTICLE')){
	    	$this->cage=$curd->cage_select('files_sort','files_id,'.pfix('files_name').' AS files_name,files_pid','','files_sort asc','files_id','files_pid');
	    }	
	     //城市表	
		if(C('IS_GOODS_CITY_SELECT')){
			$citys=M('region')->where(array('region_type'=>array('lt',3),'is_show'=>1))->order(array('orders'=>'ASC'))->select();
			import('Class.Category',APP_PATH);
			$citys=Category::unlimitedForLevel($citys,'region_id','parent_id');	
			unset($citys[0]);
			$this->citys=$citys;
		}
		
		//物流模板
		if(C('IS_GOODS_LOGISTICS')){
		    $this->logisticsTpl=M('logistics_tpl')->field('id,tpl_name')->order()->select();
		}
		
		//多语言
		$this->goodsNames=$this->langIsMore('goods','goods_name'); 
		$this->commodityKeys=$this->langIsMore('goods','commodity_key'); 
		$this->commodityDescription=$this->langIsMore('goods','commodity_description'); 
		$this->contents=$this->langIsMore('goods','content');
		if(C('IS_GOODS_PARAMETERS')) $this->parameterss=$this->langIsMore('goods','parameters');
		if(C('IS_GOODS_AFTER_SALES')) $this->afterSales=$this->langIsMore('goods','after_sales');
		$this->warehouses=$this->langIsMore('goods','warehouse');
		$this->expresss=$this->langIsMore('goods','express');
	}		
	
	//添加/修改商品表单处理
	public function goodsSendForm(){
		//p($_POST);die;
        $action=$_POST['action']; 
		$type=$_POST['type'] != '' ? '/type/'.$_POST['type'] : '';
        $rel= 'Goods/'.$action.'/action/'.$action.$type;
        //获取商品属性
		if($_GET['get_attr']=='ok'){
			$this->getGoodsAttr();
			die;
		}
        //AJAX修改一个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}	
		
		//检索下级商品分类
		if($_GET['cate']=='ajax'){
			$this->ajaxClass();
			die();
		}	
        
		//检索下级城市
		if($_GET['city']=='ajax'){
			$model=new CommonModel();
			return $model->searchRegion();			
		    die();
		}      
		
		//BAC检索商品品牌
		if($_GET['band_bac']=='ajax'){
			$this->getBandBAC($_GET['bac']);
			die();
		}

		//组合购买检索
		if($_GET['zhu']=='ok'){
			import('Class.ZuHeGoods',APP_PATH);
			die(ZuHeGoods::retrievalGoods());
		}
		
		if(!$_POST['goods_category_id']) return_json(300,'分类选择错误，还有下级分类');
		
		if($_POST['id']){
			$goods_id=$_POST['id'];
		}else{
			//处理商品货号	
			if(empty($_POST['goods_num'])){
			     $_POST['goods_num']=goods_num(PREFIX.'goods',C('GOODS_ITEM_PREFIX'));	//商品货号	
			}	
			$goods_id=getNextId(PREFIX.'goods');//获得商品表新增时的ID			
		}

	   /* 判断优惠价格 */
	   if(isset($_POST['discount_sum']) && isset($_POST['preferential'])){
	   	     $temp_num = array_count_values($_POST['discount_sum']);
		     foreach($temp_num as $v){
		     	 if($v >1) return_json(300,'优惠数量或优惠价格重复！');
				 break;
		     }
	   }
		 
		//处理推荐
		if(isset($_POST['goods_promoted_id'])){
			$moted='-';
			foreach($_POST['goods_promoted_id'] as $k=>$v){
				$moted.=$v.'-';
			}
			$_POST['goods_promoted_id']=$moted;
		}		

        //处理消费积分
        if($_POST['pay_points'] < 0) $_POST['pay_points']=intval($_POST['goods_retail_price']);
		
		//处理等级积分
	    if($_POST['rank_points'] < 0) $_POST['rank_points']=intval($_POST['goods_retail_price']);

		//规格参数列表
	   $parameterss=$this->langIsMore('goods','parameters');
	   foreach($parameterss as $k=>$v){
           if($_POST[$v['field']]){
				$par=array();
				$c=0;
				foreach($_POST[$v['field']] as $k1=>$v1){
					$parName=emptyHtml($v1);
					$parValue=$_POST[$v['field'].'_val'][$k1];
					if($parName && $parValue){
						$par[$c]['par_name']=$parName;
						$par[$c]['par_value']=$parValue;
						$c++;					
					}
				}
				unset($_POST[$v['field']]);
				unset($_POST[$v['field'].'_val']);
				$_POST[$v['field']]=json_encode($par);
				
			}		   
	       /* if($_POST[$v['field']][0]){
				$par=array();
				$c=0;
				foreach($_POST[$v['field']] as $k1=>$v1){
					$parName=emptyHtml($v1);
					$parValue=$_POST[$v['field'].'_val'][$k1];
					if($parName && $parValue){
						$par[$c]['par_name']=$parName;
						$par[$c]['par_value']=$parValue;
						$c++;					
					}
				}
				unset($_POST[$v['field']]);
				unset($_POST[$v['field'].'_val']);
				$_POST[$v['field']]=json_encode($par);
				
			}	  */ 	
	   }
	   //p($_POST);die;
		//统一调用私有方法
		$this->__actionAll($goods_id);
		$_POST['create_time']=time();
		
		//是否应用了分类推荐
		if(!$_POST['recommend']){
		    $_POST['reco_pic']='';
			$_POST['recommend']='';
		}		
		//处理商品相册
		$i=0;
		$imgArr=array();
		foreach($_POST['imgAll'] as $k=>$v){
			if($v){
				$imgArr[$i]['img']=$v;
				$imgArr[$i]['title']=$_POST['title'][$k] ? $_POST['title'][$k] : '';
				$imgArr[$i]['url']=$_POST['url'][$k] ? $_POST['url'][$k] : '';
				$i++;
		    }
		}
		$_POST['goods_photo']=$imgArr ? json_encode($imgArr) : '';
		if($_POST['id']){ //修改商品
		    M('goods')->where(array('id'=>$_POST['id']))->save($_POST);
		}else{ //添加商品	
		    $this->requestArray('goods'); //重组POST数据
			M('goods')->add($_POST);
		}	
		return_json(200,'操作成功！',$rel,'','closeCurrent');
	}

	//统一调用私有方法
	private function __actionAll($goods_id){

		   $this->_limitPromotion($goods_id);  //限时促销
           $this->_linkGoods($goods_id);         //关联商品
           $this->_linkAcce($goods_id);            //关联配件
           $this->_linkArticle($goods_id);           //关联文章
           $this->_memberPrice($goods_id); //处理会员价格					                  
		   $this->_preferential($goods_id);   //处理优惠价格									  					                  					                   
           $this->_attrPesForm($goods_id);//处理商品属性和促销活动和重量运算	
		   $this->_goodsZhuhe($goods_id); //处理组合购买
	}
	
	//处理限时促销
	private function _limitPromotion($goods_id){
		   if(!C('IS_PROMOTION')) return true; //不使用限时促销
			//处理促销活动	
			$db=M('goods_promotion');
			if($_POST['is_promotion']){
					$start=sqlTime($_POST['promote_start_date']);
					$_insert['promotion_start_date']=$start['startTime']; //促销活动开始时间
					$end=sqlTime($_POST['promote_end_date']);
					$_insert['promotion_end_date']=$end['endTime']; //促销活动结束时间
					$_insert['promotion_price']=$_POST['promotion_price']; //限时促销价格
					$_insert['goods_category_id']=$_POST['goods_category_id'] ? $_POST['goods_category_id'] : 0; //商品分类ID
					$_insert['goods_id']=$goods_id; //促销商品ID
					$_insert['numbers']=$_POST['numbers']; //促销数量
					$_insert['no_ffo']=$_POST['is_promotion'];  //启动促销开关。0，强制停止促销活动；1,促销进行中；
					if($db->where(array('goods_id'=>$goods_id))->find()){ //修改
						$db->where(array('goods_id'=>$goods_id))->save($_insert);
					}else{//新增
						$db->add($_insert);
					}
			}else{
			       $db->where(array('goods_id'=>$goods_id))->delete();
			       $_POST['is_promotion']=0;
			}			  
	}    
	
	//处理会员价格
	private function _memberPrice($goods_id){
		 if(!C('IS_GOODS_MEMBER_PRICE')) return true;//不使用会员等级价格
		  $db=M('goods_member_price');
		  $db->where(array('goods_id'=>$goods_id))->delete();//删除原有的会员价格		
          if(isset($_POST['member_grade_id'])){
          	  $grade=array();
          	  foreach($_POST['member_grade_id'] as $k=>$v){
          	  	     if($_POST['grade_price'][$k]){
						 $grade[$k]['goods_id']=$goods_id;
						 $grade[$k]['member_grade']=$v;
						 $grade[$k]['member_price']=$_POST['grade_price'][$k];
          	  	     }					                  	       
              }
              $db->addAll($grade);
          }		
	}	

    //处理优惠价格	
	private function _preferential($goods_id){
	  if(!C('IS_GOODS_VOLUME_PRICE')) return true; //不使用商品数量优惠价格	
	   $db=M('goods_volume_price');	
	   $db->where(array('goods_id'=>$goods_id))->delete();		//删除原有的优惠价格 								  
	   if(isset($_POST['discount_sum']) && isset($_POST['preferential'])){
	   	     $volume=array();
		     foreach($_POST['discount_sum'] as $k=>$v){
	  	  	     if($_POST['preferential'][$k]){
					 $volume[$k]['goods_id']=$goods_id;
					 $volume[$k]['discount_sum']=$v;
					 $volume[$k]['preferential']=$_POST['preferential'][$k];
	  	  	     }			
		     }
			 $db->addAll($volume);
	   }
	}   		

	//关联商品
	private function _linkGoods($goods_id){
       if(!C('IS_GOODS_LINK')) return true; //不使用关联商品  
	   //删除原有的关联商品
         $amp['goods_id']=$goods_id;
         $amp['_logic']='OR';
         $amp['link_goods_id']=$goods_id;
	  	 M('goods_link')->where($amp)->delete();
	   		
        if(isset($_POST['link_goods_id'])){
           $link_goods=array();	
           foreach($_POST['link_goods_id'] as $k=>$v){
           	  $v_arr=explode('-', $v);
           	   $sql1.="('".$goods_id."','".$v_arr[0]."','".$v_arr[1]."'),";
			   if($v_arr[1]==1){
			   	  $sql1.="('".$v_arr[0]."','".$goods_id."','".$v_arr[1]."'),";			  
			   }
           }
           if($sql1){
           	  $sql1=rtrim($sql1,',');
			   $link_sql="INSERT INTO ".PREFIX."goods_link (goods_id,link_goods_id,is_double) VALUES ".$sql1;
			   M()->query($link_sql);
           }
		}		
	}

	//关联配件
	public function _linkAcce($goods_id){
		if(!C('IS_GOODS_ACCESSORIES')) return true;//不使用商品配件关联
	    $db=M('goods_accessories');	
	   	$db->where(array('goods_id'=>$goods_id))->delete();	
		if(isset($_POST['acc_relevance_id'])){
			$accessories=array();
			foreach($_POST['acc_relevance_id'] as $k=>$v){
				$accessories[$k]['goods_id']=$goods_id;
				$accessories[$k]['goods_acc_id']=$v;
			}
			$db->addAll($accessories);
		}
	}

	//关联文章
	public function _linkArticle($goods_id){
	  if(!C('IS_GOODS_LINK_ARTICLE')) return true;//不使用商品关联文章	
	   $db=M('goods_link_article');	
	    //删除原有的关联文章
	   	$db->where(array('goods_id'=>$goods_id))->delete();	
		if(isset($_POST['selectLinkArticle'])){
			$link=array();
			foreach ($_POST['selectLinkArticle'] as $key => $value) {
				$link[$key]['goods_id']=$goods_id;
				$link[$key]['article_id']=$value;
			}

            $db->addAll($link);
		}
	}

	//处理组合购买
	private function _goodsZhuhe($goodsId){
		if(!C('IS_GOODS_ZHUHE')) return true;//不使用组合购买
	   $success=true;
	   $z=M('goods_zhuhe');
	   $z->where("main_goods_id='{$goodsId}'")->delete();
	   if($_REQUEST['zhu_he_goods_id'][0]){
		   $insetArr=array();
		   $i=0;
		   foreach($_REQUEST['zhu_he_goods_id'] as $k=>$v){
			    if($_REQUEST['zhu_he_price'][$k] > 0){
					$insetArr[$i]['main_goods_id']=$goodsId;
					$insetArr[$i]['zhuhe_goods_id']=$v;
					$insetArr[$i]['zhuhe_price']=$_REQUEST['zhu_he_price'][$k];
					$i++;
				}
		   }
		   if($insetArr) 
		   if($z->addAll($insetArr)) $success=false;
	   }
	   return $success;
	}
	
	//处理表单属性
	private function _attrPesForm($goodsId){
		   if(!C('IS_GOODS_ATTRIBUTE')) return true;//不启用商品属性
           $success=true;
		   $db=M('goods_attr');
		   //删除原有商品属性
           $db->where(array('goods_id'=>$goodsId))->delete();
		   //商品属性	
		   $i=0;
		   $attrs=array();
		   $attrPic=array();   
		   foreach($_REQUEST['attr_id_list'] as $k=>$v){
				if(intval($v) > 0){				
					$attrs[$i]['goods_id']=$goodsId;
					$attrs[$i]['attribute_value_id']=$v;
					$attrs[$i]['attr_price']=$_REQUEST['attr_price_list'][$k] > 0 ? $_REQUEST['attr_price_list'][$k] : 0;
					$attrs[$i]['attr_stock']=$_POST['attr_stock'][$k] ? $_REQUEST['attr_stock'][$k] : 0;
					$attrs[$i]['attr_pic']=$_REQUEST['attr_pic'][$k] ? $_REQUEST['attr_pic'][$k] : '';
					$i++;	
				}				
		   }
		   if($attrs) {
			   if(!$db->addAll($attrs)) $success=false;
		   } 
		   return $success;
	}
	
    //获取商品属性
    private function getGoodsAttr($attrTypeId='',$goodsId=''){
		  if(empty($attrTypeId)) $attrTypeId=getNum($_REQUEST['goods_attr_type_id']);
		  import('Class.Attrs',APP_PATH);
		  $data=Attrs::goodsSaveAttr($attrTypeId,$goodsId);
		  if($goodsId){
			  return $data;
		  }else{
			  die($data);
		  }
		  
    }	
	
	//查看仓库通知消息
/*	public function  goodsInveInfo(){
		if($_REQUEST['confirm']=='ok'){
			$url=U(MODULE_NAME.'/Goods/'.$_POST['action'].'/',array(C('VAR_PAGE')=>$_POST[C('VAR_PAGE')],'type'=>$_POST['type'],'action'=>$_POST['action']),'');
			D('Curd')->save('goods',$_POST['action'],$url);
		}else{
			$this->info=M('goods')->field('id,business_notes')->where(array('id'=>$_GET['ids']))->find();
			$this->display();			
		}

	}*/
	
	//转移/还原商品到回收站
	public function moveRecycleBin(){
		
        $where['id']=array('in',$_REQUEST['ids']);
		
		if(intval($_REQUEST['type']) > 1){ //还源回收站商品
			$where['recycle_bin']=0;
		}else{//转移商品到回收站
			$where['recycle_bin']=1;
		}		
		if(intval($_REQUEST['type'])==0){
			$cation= 'goodsNotShelvesList';  //未上架
		}else if(intval(intval($_REQUEST['type'])==1)){
			$cation= 'goodsShelvesList';  //已上架上架
		}else if(intval(intval($_REQUEST['type'])==2)){
			$cation= 'recycleBin';  //回收站
		}	
		$url=U(APP_APPS.'/Goods/'.$cation,array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')],'type'=>$_REQUEST['type']),'');	
		if(M('goods')->save($where)){
			return_json(200,'操作成功！',$cation,'','forward',$url);
		}

	}
	
	//切底删除商品
	public function goodsDelete(){
		M('goods_attr')->where(array('goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除商品属性表
		M('goods_member_price')->where(array('goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除会员价格表	
		M('goods_volume_price')->where(array('goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除优惠价格表	
		M('goods_link')->where(array('goods_id'=>array('IN',$_REQUEST['ids']),'_logic'=>'OR','link_goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除关联商品表	
		M('goods_accessories')->where(array('goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除关联配件表	
		M('goods_link_article')->where(array('goods_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除关联文章表
		$url=U(APP_APPS.'/Goods/recycleBin',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')],'type'=>$_REQUEST['type']),'');
		D('Curd')->del('goods','recycleBin',$url); //删除商品表
	}
	
	//商品列表公共使用(上架、未上架、回收站)
	private function _goodsList(){
	       $amp=D('Curd')->search(pfix('goods_name'),'');//询查条件,返回数组已有按商品分类询查
	       if(intval($_REQUEST['type'])==2){ //类型2，回收站；
	       	   $amp['searchArr']['recycle_bin']=1;   //0为正常商品
	       }else{
	       	  if($_REQUEST['type'] =='0'  || $_REQUEST['type'] ==1) {
				  $amp['searchArr']['shelves']= $_REQUEST['type']; //类型0，已下架；类型1，上架商品；
			  }
	       	  $amp['searchArr']['recycle_bin']=0;   //0为正常商品
	       }
		   
		   //商品分类查询
		   if($_REQUEST['goods_category_id']){
			    $cateId=$_REQUEST['goods_category_id'];
		        $category=M('goods_category')->field("id")->where("path LIKE '%-{$cateId}-%' OR id='{$cateId}' ")->select();
				$cateIds=in_id($category,'id');
				$amp['searchArr']['goods_category_id']=array('in',$cateIds);
				$seaUrl0='/goods_category_id/'.$cateId;
		   }

		   //供货询查条件 		   
		   if($_REQUEST['inventory_supplier_id']){
		   	   $amp['searchArr']['goods_supplier']=$_REQUEST['inventory_supplier_id']; 
		   	   $seaUrl1='/inventory_supplier_id/'.$_REQUEST['inventory_supplier_id'];
		   } 
		   
		   //品牌询查条件
		   if($_REQUEST['goods_brand_id']){
		   	   $amp['searchArr']['goods_brand_id']=$_REQUEST['goods_brand_id']; 
		   	   $seaUrl3='/goods_brand_id/'.$_REQUEST['goods_brand_id'];
		   } 		   
           $seaUrl=$seaUrl0.$seaUrl1.$seaUrl2.$seaUrl3.'/action/'.$_REQUEST['action']; //组合搜索URL
		   $order=$this->orderBy()?$this->orderBy():array('create_time'=>'DESC');//排序
	       // $counts=M('goods')->field('id')->where($amp['searchArr'])->count();
		   $goodsRelation=D('GoodsRelation');
		   $counts=$goodsRelation->where($amp['searchArr'])->relation(true)->count();
	       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
		   $goods=$goodsRelation->where($amp['searchArr'])->relation(true)->order($order)->limit($GLOBALS['limit'])->select();
		   foreach($goods as $k=>$v){
			      if(C('IS_STOCK')){//如果开启库存功能
			      	$sql="select sum(i_o) AS i_o FROM ".PREFIX.INVENTOR_TABLE.$v['inventory_id']." WHERE pro_num='".$v['goods_num']."' ";
					$sum=  $db->query($sql);
					$goods[$k]['sum']=$sum[0]['i_o'] ? intval($sum[0]['i_o']) : 0;
			      }
		   }
		   $this->goods=$goods; //商品数组	 
		   $this->searchVal=$amp['keyword']; //保存输入的搜索关键字
		   $this->url=$amp['url'].'/type/'.$_REQUEST['type'].$seaUrl; //保存查询条件*/	   	
		   $this->types=	'type/'.$_REQUEST['type'];  //搜索类型,上架或未上架，回收站
		   $this->goodCat=D('CURD')->cage_select('goods_category','','','reorder ASC'); //按商品分类搜索
		   $this->supplier=M('goods_supplier')->field('id,name')->select(); //按供货商询查
		   $this->brand=M('goods_brand')->field('id,'.pfix('name').' AS name')->select();  //按品牌询查
	}

	//商品咨询列表
	public function consultation(){
		  $order=$this->orderBy('gc')?$this->orderBy('gc'):" gc.add_time DESC";//排序
		  $db=M();
		  $countsSql="SELECT count(gc.id) as counts FROM ".PREFIX."goods_consultant AS gc JOIN ".PREFIX."member AS m ";
		  $countsSql.="ON (gc.user_id=m.id) JOIN ".PREFIX."goods AS g ON(g.id=gc.goods_id) ";
		  $counts=$db->query($countsSql);
		  $this->callAjaxPage(C('BGPAGENUM'),$counts[0]['counts']);//分页
	      $sql="SELECT gc.*,m.user_name,g.goods_name FROM ".PREFIX."goods_consultant AS gc JOIN ".PREFIX."member AS m "; 
		  $sql.="ON (gc.user_id=m.id) JOIN ".PREFIX."goods AS g ON(g.id=gc.goods_id) ORDER BY {$order} LIMIT ".$GLOBALS['limit'];
		  $this->result=$db->query($sql);
		  $this->display();
	}
	
	//查看商品咨询
	public function consultationShow(){
	      $sql="SELECT gc.*,m.user_name,g.goods_name FROM ".PREFIX."goods_consultant AS gc JOIN ".PREFIX."member AS m "; 
		  $sql.="ON (gc.user_id=m.id) JOIN ".PREFIX."goods AS g ON(g.id=gc.goods_id) WHERE gc.id='{$_GET['id']}' LIMIT 1";
		  $result=M()->query($sql);
		  $this->assign('result',$result[0]);
		  $this->display();
	}
	
	//回复咨询
	public function consultationReply(){
        //AJAX修改一个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}	
		if(M('goods_consultant')->where(array('id'=>$_POST['id']))->save($_POST)){
			return_json(200,'操作成功！','consultation','','closeCurrent');
		}else{
			return_json(200,'操作失败！');
		}
	}
	
	//删除商品咨询
	public function consultationDelete(){
		if(M('goods_consultant')->where("id IN({$_REQUEST['ids']})")->delete()){
			$url=U(APP_APPS."/Goods/consultation");
			return_json(200,'操作成功！',$action,'','forward',$url);
		}else{
			return_json(200,'操作失败！');
		}
	
	}
	
 	//AJAX检索下级分类(支持无限级)
	private function ajaxClass(){
	   $key=$_GET['key'];	
	   $id=$_GET['id'];
	   $res=M('goods_category')->where(array('pid'=>$id))->order(array('reorder'=>'ASC'))->select();
	   if($res){
		   
		   //是否达到最后一级分类
		   $lastPid=M('goods_category')->field('id')->where(array('pid'=>$res[0]['id']))->find();
		   if(!$lastPid) $name="goods_category_id"; //已到达最后一级
		   
		   $str.='<tr class="show-trs">';
		   $str.='<td align="right" style="text-align:right;">'.($key+1).'级分类：</td>';
		   $str.='<td align="left" id="radio-content'.($key+1).'">';		   
		   foreach($res as $k=>$v){
				if($name){
					$str.='<label>';
					$str.='<input type="radio" value="'.$v['id'].'" name="'.$name.'" onclick="whereClass('.($key+1).','.$v['id'].')"/>';
					$str.=$v[pfix('name')];
					$str.='</label>';				
				}else{			
					$str.='<label>';
					$str.='<input type="radio" value="'.$v['id'].'" name="category_'.($key+1).'"  onclick="whereClass('.($key+1).','.$v['id'].')"/>';
					$str.=$v[pfix('name')];
					$str.='</label>';  	
				}
	       }
		   $str.='</td>';
		   $str.='</tr>';	   
	   }else{
	       $str=0;
	   }	
	   //获取一级分类的所有品牌
	   if($key==1){
		   $band=$this->getCateBand($id);
		   $arrarys['b']=true;
		   $arrarys['bands']=$band;
	   }else{
	       $arrarys['b']=false;
	   }
	   $arrarys['cates']=$str;
	  die(json_encode($arrarys));
	}	 
	
	//是否已有分类推荐位，每个二级分类只有一个推荐
	private function isCateTce(){
		   $res=M('goods_category')->field('id')->where(array('pid'=>$_GET['id']))->select();
		   $idId=in_id($res,'id');
		   if(M('Goods')->field('id')->where(array('goods_category_id'=>array('in',$idId),'recommend'=>1))->find()){
		       echo 1;
		   }else{
		       echo 0;
		   }
	}
	
	//获取一级分类下的所有品牌
	public function getCateBand($cateId,$update=false){
		$cate=M('goods_category_brand_bc_id')->field('brand_id')->where("category_id IN ({$cateId})")->select();
		$cateIdAll=in_id($cate,'brand_id');
	    $band=M('goods_brand')->field('id,'.pfix('name').' AS name')->where("id IN({$cateIdAll})")->select();
		if($update) return $band;
		$str='';
		$count6=0;
		foreach($band as $k=>$v){
			$str.='<label><input type="radio" name="goods_brand_id" value="'.$v['id'].'"  style="position:relative;top:3px;"/>'.$v['name'].'</label>';
			$count6++;
			if($count6==7){
				$str.='<br/>';
				$count6=0;
		    }
		}
		return $str;
	}
	
	//BAC检索商品品牌
	private function getBandBAC($abc){
		$result=M('goods_brand')->field('id,'.pfix('name').' AS name')->where(array('abc'=>$abc,'display'=>1))->order("orders ASC")->select();
		$str='';
		foreach($result as $k=>$v){
			$str.='<label><input type="radio" name="goods_brand_id" value="'.$v['id'].'"  style="position:relative;top:3px;"/>'.$v['name'].'</label>';
		}
		die($str);
	}

	//组合购买商品检索
	/*public function goodsZhuhe(){
	   $id=$_GET['goods_id'];
	   $goods_name=$_GET['goods_name'];
	   $where=pfix('goods_name')." LIKE '%{$goods_name}%'";
	   if($id) $where.=" AND id <> '{$id}'";
	   $result=M('goods')->field('id,'.pfix('goods_name').' AS goods_name,goods_market_price,goods_retail_price')->where($where)->select();
	   $str='';
	   foreach($result as $k=>$v){
			 $str.='<tr class="goods_zhuhe_tr">';
			 $str.='<td width="40%" >'.$v['goods_name'].'</td>';
			 $str.='<td width="15%" >市场价：￥'.$v['goods_market_price'].'</td>';
			 $str.='<td width="15%">本店价：￥'.$v['goods_retail_price'].'</td>';
			 $str.='<td width="18%">组合价(减)：';
			 $str.='<input type="text" name="goods_zhuhe_price[]" value="" class="required number" style="position:relative;top:0px;" size="6" />';
			 $str.='<input type="hidden" value="'.$v['id'].'" name="goods_zhuhe_ids[]" />';
			 $str.='</td>';
			 $str.='<td width="5%"><a href="javascript:void(0);" onclick="delZhuhe(this)">删除</a></td>';
			 $str.='</tr> ';  	   
	   }
	   die($str);
	}*/
	
}