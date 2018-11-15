<?php
// 本类由系统自动生成，仅供测试用途
class BasicAction extends Action {
	protected $DB;
	protected $cityId; //城市ID
	protected $langfx;
	protected $wishlistList;
    protected function _initialize(){
		if( is_mobile() > 0) exit();//禁手机访问
         //语言前缀(cn_) 
		$this->langfx=pfix();		
		
		//模板C获取配置语言前缀(cn_)
		$this->assign('langfx',$this->langfx);

	   //在客户端生成一个唯一的购物车标识
	   setKey();	
	   //p($_COOKIE);	die;	   
	   
       //左边公共产品分类
	   $GLOBALS['cateList']=$this->navLeve();
	   $this->cateList=$GLOBALS['cateList'];
	   $this->cateCount=count($cateList);
        //p($_SESSION);die;
		//调起城市检索功能
        //$this->runCity();
		
	    //控制器执行SQL模型对像
	    $this->DB=M();		
		//获取全部单页
		$files=$this->getFiles('3,7');
		$this->assign('files',$files);
		//获得Cookie收藏商品列表
		$pbulcCollect=$this->cookieCollect(0,false);
		//p($pbulcCollect);die;
		$this->pbulcCollect=$pbulcCollect;
		//购物车列表，收藏商品列表需特殊处理
		if(MODULE_NAME.'/'.ACTION_NAME=='Cart/index') {
			$this->wishlistList=$pbulcCollect;
			$this->is_cart=true;
		}

    }
	
	/**
	 * 获取综合检索数据
	 * $typeWhere  可指定类型 hensive('0,1,2');不指定类型则是全部
	 * */
	public function hensive($typeWhere=''){
		$tool=new ToolModel();
		return $tool->hensive($typeWhere);
	}	
	
	//调起城市检索功能
	private function runCity(){
		if(!C('IS_GOODS_CITY_SELECT')) return;
        //IP定位
		$this->showIpToCity();
		//模板显示COOKIE城市名称
		if(cookie(C('COOKIE_CITY_NAME'))) $this->cityName=cookie(C('COOKIE_CITY_NAME'));
		
		//显示热门城市
		$reg=M('region');
		$this->hotCity=$reg->field('region_id,region_name')->where(array('is_show'=>1,'is_hot'=>1))->order(array('orders'=>'DESC'))->select();
        $this->district=$reg->field('region_id,region_name')->where(array('parent_id'=>cookie(C('COOKIE_CITY_ID'))))->select();//区县列表		
	}
	
	//跟据IP定位城市
	private function showIpToCity(){
		if(cookie(C('COOKIE_CITY_ID'))) return;
		$sinaCity=get_sina_ip_to_city();
		$reg=M('region');
		$region=$reg->field('region_id,region_name')->where(array('region_name'=>$sinaCity['city']))->find();	
		if($region){
			$this->cityName=$region['region_name']; //模板显示所在城市
			cookie(C('COOKIE_CITY_ID'),$region['region_id'],86400);
			cookie(C('COOKIE_CITY_NAME'),$region['region_name'],86400);
			cookie(C('COOKIE_DISTRICT_ID'),'');
			cookie(C('COOKIE_DISTRICT_NAME'),'');			
		}
	}

		 //分页
	public function doPage($counts,$pageSize=20,$pageType=4){  
			import('Class.Page',APP_PATH);		
			$page=new Page($counts,$pageSize);					
			$GLOBALS['limit']=$page->firstRow . ',' . $page->listRows;//组合limti条件
			$this->pageNumShown=$page->totalPages;//总页数
			$this->currentPage=$page->nowPage;//当前页
			$this->psize=$pageSize; //每页显示多少条
			$this->dataCount=$counts; //数据库总条数
			$this->page=$page->show($pageType);
	}	
		
	   //检查用户名、手机、电子邮件是否被占用
	  //只需要在input 属性ID值是数据库字段名
	  protected function basicChkData(){
			 nonlicet_url();
			 $field=$_GET['fieldId'];
			 $where[$field]=$_GET['fieldValue'];
			 //验证商户表
			 if($field =='catalog'){
				$res=M('shops')->field('id')->where($where)->find();
			 }else{//验证会员表
				$member['user_name']=$_GET['fieldValue'];
				$member['reg_email']=$_GET['fieldValue'];
				$member['mobile_phone']=$_GET['fieldValue'];
				$member['_logic']='OR';
				$res=M('member')->field('id')->where($member)->find();	
			 }		 
			 if($res){
				  die(json_encode(array($_GET['fieldId'],false)));
			  }else{
				 die(json_encode(array($_GET['fieldId'],true)));
			 }  
	  }		

	  /**成功或错误提示
	  *$success   状态 true 或 false
	  *$inputId  提示节点ID
	  *$title            消息标题
	  *$info            消息内容
	  *$finctions    回调函数
	  *$url               跳转地址
	  *$data           数组数据
	  **/
	  protected function myInfos($success,$inputId,$info,$url='',$finctions='',$data=''){
			if(IS_AJAX){
				val_json($inputId,$success,$info,$url,$finctions,$data);
			}else{
				if($success){
					$this->success($info,$url);
				}else{
					$this->error($info,$url); 
				}
			}	     
	  }

   //显示验证码图片
   protected function basicVerify($width=40,$height=26){
   		 //验证码Session值$_SESSION['verify'];
		 ob_end_clean();  
		 import('ORG.Util.Image');
		 Image::buildImageVerify(4,1,'png',$width,$height);
   }	
   
    //AJAX验证码是否正确
   protected function basicAjaxYzm(){
	   $userName=$_GET['fieldValue'];
	   $arr[0]=$_GET['fieldId'];
	   if(md5($userName)==$_SESSION['verify']){
		   $arr[1]=true; 
	   }else{
		   $arr[1]=false;
	   }
	   echo  json_encode($arr);	
	}	

	//验证短信息号码是否正确
	protected function basicMobileCode(){
		nonlicet_url();
		$val=$_GET['fieldValue'];
		$returnId=$_GET['fieldId'];
		$mobile=explode('__',$returnId);
		$token[$mobile[2]]=$mobile[3];
		$this->hckToken($token);
		//die(json_encode(array($returnId,true)));	 //使用时去掉
        import('Class.MobileMessage',APP_PATH);
        $moblie=new MobileMessage($mobile[1],$val,false,true,'');
        if($moblie->success){
        	echo json_encode(array($returnId,true));	
        }else{
        	echo json_encode(array($returnId,false));	
        }
	}  	
	
	/**
	*显示商品评分与列表
	*$goodsId  商品ID
	*$db      Model对像
	*$pageLimit  控制分页数
	*return array();   
	*/
	protected function goodsScore($goodsId='',$db='',$pageLimit){
        $where='';
	    if($_GET['feel'] !='') $where.=" AND ga.feel LIKE '%-{$_GET['feel']}-%' ";
		if($_GET['sva'] !='' && is_numeric($_GET['sva'])) $where.=" AND ga.sva ='{$_GET['sva']}' ";
		//按有图检索
		$isPic=getNum($_GET['is_pic']);
		if($isPic > 0) $where.=" AND ga.is_pic ='{$isPic}' ";
		if(!$goodsId) $goodsId=$_REQUEST['id'];
		if(!$db) $db=M();
    	$sql="SELECT sva,count(sva) as s_c FROM ".PREFIX."goods_appraise WHERE goods_id='{$goodsId}' AND is_show=1 group by sva with rollup";
		$sva=$db->query($sql);		
		$svaArr['subpar_count']=0;//差评个数
		$svaArr['medium_count']=0;//中评个数
		$svaArr['good_count']=0;//好评个数
		$svaArr['all_count']=0; //总条数
		$svaArr['img_count']=M('goods_appraise')->where("goods_id='{$goodsId}' AND is_show=1 AND is_pic=1")->count();//有图评论
		foreach($sva as $k=>$v){
			if($v['sva']=='0'){ //差评个数量
				$svaArr['subpar_count']=$v['s_c'];
			}else if($v['sva']=='1'){
				$svaArr['medium_count']=$v['s_c'];
			}else if($v['sva']=='2'){
				$svaArr['good_count']=$v['s_c'];
			}else if($v['sva']==''){
				$svaArr['all_count']=$v['s_c'];
			}
		 }				 
		 //评价列表
		 if($where){
		     $countSql1="SELECT count(ga.id) AS counts FROM ".PREFIX."goods_appraise AS ga ";
			 $countSql1.="WHERE ga.goods_id='{$goodsId}' AND ga.is_show=1 {$where} ";
			 $counts=$db->query($countSql1);		 	
			 $counts=$counts[0]['counts'];	 		 	
		 }else{
		 	$counts=$svaArr['all_count'];
		 }
		 $this->_URL_=$_GET['_URL_'];
		 $this->doPage($counts,$pageLimit,'');	 
		 $sql="SELECT ga.*,m.user_name,m.head_pic,m.surname,m.full_name,og.goods_attr FROM ";
		 $sql.=PREFIX."goods_appraise AS ga JOIN ".PREFIX."member AS m ON(m.id=ga.member_id) ";
		 $sql.="LEFT JOIN ".PREFIX."order_goods AS og ON (ga.goods_id=og.goods_id AND ga.order_id=og.order_id) ";
		 $sql.="WHERE ga.goods_id='{$goodsId}' {$where} ";
		 $sql.="AND ga.is_show=1 LIMIT ".$GLOBALS['limit'];
		 $result=$db->query($sql); 
		 if($result){
			 $goods_appraise_id=in_id($result,'id');
			 $reply=M('goods_appraise_reply')->where("goods_appraise_id IN ({$goods_appraise_id})")->order("times ASC")->select();
			 $app_reply=[];
			 $gaid=$reply[0]['goods_appraise_id'];
			 foreach($reply as $v){
				 if($gaid == $v['goods_appraise_id']){
					 $app_reply[$gaid][]=$v;
				 }else{
					 $gaid = $v['goods_appraise_id'];
					 $app_reply[$gaid][]=$v;
				 }
			 }
		 }
		 //p($sql);die;
		 foreach($result as $k=>$v){
		     $result[$k]['slide_show']=$v['slide_show'] ? json_decode($v['slide_show'],true) : array();	
			 $result[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
			 $result[$k]['reply_list']=$app_reply[$v['id']];
		 }
		 //p($result);die;
		 return array(
		      'app_list'=>$result, //评价列表
		      'subpar_count'=>$svaArr['subpar_count'],//差评个数
		      'medium_count'=>$svaArr['medium_count'],//中评个数
		      'good_count'=>$svaArr['good_count'],//好评个数
			  'img_count'=>$svaArr['img_count'],//有图评论个数
		      'app_count'=>$svaArr['all_count'] //总条数
	     );
	}   
	
	/**
	*获取购物车数量
	*/
	protected function getCartData(){
	   $cartUserId=getUserC('SE_USER_ID');
	   if($cartUserId > 0) {
	       $cartUart="user_id = '{$cartUserId}' OR user_key='{$_COOKIE['user_key']}' ";
	   }else{
	       $cartUart="user_key='{$_COOKIE['user_key']}'";
	   }
	   $cart=M('cart')->where($cartUart)->select();
       $data['counts']=count($cart);
	   $data['goods_list']=$cart;
	   return $data;
	} 
	
	/*
	*获取广告
	*$adId  广告ID
	*$db  对像
	*$lists  true获取列表;false获取一条数据
	*return array
	*/
	protected function getAd($adId,$lists=true){
	     $field=array('ad_img',$this->langfx.'ad_name','ad_url','ad_class_bewrite');	
		 $field="ad_id,{$this->langfx}ad_name AS ad_name,ad_img,ad_url,{$this->langfx}ad_title AS ad_title,{$this->langfx}ad_class_bewrite As ad_class_bewrite,price";
		 $where=array('display'=>1,'ad_pid'=>$adId);
		 if(!$lists){
		     $res=M('ad')->field($field)->where($where)->order(array('ad_order'=>'ASC'))->find();
		 }else{
		     $res=M('ad')->field($field)->where($where)->order(array('ad_order'=>'ASC'))->select();
		 }
		 return $res;
	}

     //你的位置
     protected function positions($c_id){
     		import('Class.Category',APP_PATH);
			$res=M('goods_category')->field('id,'.$this->langfx.'name AS name,pid')->select();
			$position=Category::unlimitedForGetParents($res,$c_id);
			return $position;
     }	

	//导航
	protected function getNav(){
		return M('nav')->field('nav_name,nav_url,nav_on,nav_blank')->where(array('nav_show'=>1))->order(array('nav_sort'=>'ASC'))->select();
	}

    //无限级
    protected function navLeve(){
         $field='id,'.$this->langfx.'name AS name,pid,ad_array,goods_attribute_id_all,ad_alone,ad_alone_link,ad_price,'.$this->langfx.'ad_title AS ad_title,';
         $field.='ad_alone1,ad_alone_link1,ad_array';
         $cate=M('goods_category')->field($field)->where(array('is_show'=>1))->order(array('reorder'=>'ASC'))->select();
         $cate = unlimitedForLayer($cate,'pid','id');   	
		 foreach($cate as $k=>$v){
		    $cate[$k]['ad_array']=json_decode($v['ad_array'],true);
		 }
		 return $cate;
    }

    //验证令牌是否正确
    protected function hckToken($token=''){
 		if(C('TOKEN_ON')){
			$CheckToken=M();
			if(!$token) $token =$_REQUEST;
	        if(!$CheckToken->autoCheckToken($token)) {
	        	if($this->isAjax()){
	        		die('{"msg":"Error！","url":"","success":0}');
	        	}else{
	        		$this->error('Error！'); 
                }
	        }
		}   	
    }
		
	/**
	*价格分级
	*$grade  int 价格分级数
	*$tabeName  string 表名称
	*$fieldName  string  需要处理的表字段名称普常价格字段
	*$where         string   查找条件; " WHERE 1=1 "
	*$least           string   $_GET[$least]  最大值字符串
	*$most          string   $_GET[$most]  最小值字符串
	*/
	protected function showGrade($grade,$tabeName,$fieldName,$where='',$least ='min',$most='max'){
			$db=$this->DB;
			$minMaxSql="SELECT min({$fieldName}) AS min, max({$fieldName}) as max FROM ".PREFIX.$tabeName.$where;
		    $minMaxRes=$db->query($minMaxSql);
			$min=$minMaxRes[0]['min'];
			$max=$minMaxRes[0]['max'];
			
	        // 取得价格分级最小单位级数，比如，千元商品最小以100为级数$price_grade = 0.0001;
			//$price_grade = 0.0001;
			
			// 取得价格分级最小单位级数，比如，百元商品最小以10为级数$price_grade = 0.001;
			$price_grade = 0.001;
			
	        
	        for($i=-2; $i<= log10($min); $i++){
	            $price_grade *= 10;
	        }

	        //跨度
	        $dx = ceil(($max - $min) / ($grade) / $price_grade) * $price_grade;
	        if($dx == 0){
	            $dx = $price_grade;
	        }	
	        for($i = 1; $min > $dx * $i; $i ++);	
	        for($j = 1; $min > $dx * ($i-1) + $price_grade * $j; $j++);
	        $min= $dx * ($i-1) + $price_grade * ($j - 1);	
	        for(; $max >= $dx * $i; $i ++);
	        $max  = $dx * ($i) + $price_grade * ($j - 1);	
			$sql="SELECT (FLOOR(({$fieldName} - $min) / $dx)) AS sn, COUNT(*) AS num FROM ".PREFIX.$tabeName.$where;
			$sql.=" GROUP BY sn ";
			$price_grade = $db->query($sql);
			foreach($price_grade as $priceKey=>$priceValue){
	            $priceKey = $priceKey + 1;
	            $price_grade[$priceKey]['num'] = $priceValue['num'];
	            $price_grade[$priceKey]['start'] = getPrice($min + round($dx * $priceValue['sn']),false);
	            $price_grade[$priceKey]['end'] = getPrice($min + round($dx * ($priceValue['sn'] + 1)),false);
	            $price_grade[$priceKey]['price_range'] = CUR().$price_grade[$priceKey]['start'] . '&nbsp;-&nbsp;' . CUR().$price_grade[$priceKey]['end'];	
	            if($_REQUEST[$least] ==$price_grade[$priceKey]['start'] && $_REQUEST[$most] ==$price_grade[$priceKey]['end']){
	            	$price_grade[$priceKey]['select']=true;
					$selectPrice=$price_grade[$priceKey]['price_range']; //已选中价格级
	            }else{
	            	$price_grade[$priceKey]['select']=false;
	            }		
			}
            $price_grade[0]['num'] = 0;
			$price_grade[0]['sn'] = 0;
            $price_grade[0]['start'] = 0;
            $price_grade[0]['end'] = 0;
            $price_grade[0]['price_range'] =C('LANG_SWITCH_ON') ? L('All') : '全部';
			$price_grade[0]['select']= empty($_REQUEST[$most]) ? true : false;
			if(count($price_grade) > 1){
			    $arrays=array('priceList'=>$price_grade,'price_select'=>$selectPrice);
				return $arrays;
			}	
	}	
	
	/**
	*促销商品
	*$pageSize  是否以列表显示，大于0以列表显示；第页显示多少数据；
	*$limit    如果不是以列表显示，直接获取条数
	*$conOther 其它查询条件
	*$orders   排序
	*/
	protected function isPromotion($pageSize=0,$limit=4,$conOther='',$orders=''){
		 $times=time();
		 $wheres="g.shelves=1 AND g.stock > 0 AND g.recycle_bin=0 AND p.no_ffo=1 AND p.promotion_start_date <='{$times}' AND p.promotion_end_date >='{$times}'";
		 if($pageSize){
			 $csql="SELECT count(g.id) AS counts FROM ".PREFIX."goods AS g JOIN ".PREFIX."goods_promotion AS p ON(p.goods_id=g.id) ";
			 $csql.="WHERE ".$wheres;	
			 $counts=$this->DB->query($csql);
			 $this->doPage($counts[0]['counts'],$pageSize);
			 $limit=$GLOBALS['limit'];   
		 }
		 if(!$orders) $orders=" p.promotion_start_date ASC ";
		 $sql="SELECT g.id,g.{$this->langfx}goods_name AS goods_name,g.goods_market_price,g.goods_retail_price,";
		 $sql.="g.pay_points,g.rank_points,g.integral_amount,g.promotion_img,g.goods_thumb,g.stock,g.volume,p.promotion_price,";
		 $sql.="p.promotion_start_date,p.promotion_end_date,p.numbers,p.sales,p.{$this->langfx}explain AS explain1,b.{$this->langfx}name as brand_name ";
		 $sql.="FROM ".PREFIX."goods AS g JOIN ".PREFIX."goods_promotion AS p ON(p.goods_id=g.id) ";
		 $sql.="LEFT JOIN ".PREFIX."goods_brand AS b ON(g.goods_brand_id=b.id) ";
		 $sql.="WHERE ".$wheres." {$conOther} ORDER BY  {$orders}  LIMIT ".$limit;
		 $prom=$this->DB->query($sql);
		 return  $prom;  
	}	
	
	//热门点击和最新上市
	protected function getGoodsTb($where,$order,$limit){   
	    if(!$where)   $where= array('recycle_bin'=>0,'shelves'=>1);
		$res = M('goods')->field('id,'.$this->langfx.'goods_name AS goods_name,goods_market_price,goods_retail_price,goods_thumb,click_count,volume')
		->where($where)
		->order($order)
		->limit($limit)
		->select();
		return $res;	
	}  
	

	/**
	 * 商品关联数据
	 * loves($ids)
	 * $ids   商品ID集合(1,2,3,4,5),也可以是单个商的关联
	 * */
	protected function loves($ids,$limit=0){
		if(strpos($ids,',')!==false){
			$group="GROUP BY gl.goods_id";
		}else{
			$group="ORDER BY g.volume DESC";
		}
		if($limit ==0) $limit=10;
		$sql="SELECT g.id,".pfix('goods_name')." AS goods_name,g.goods_market_price,g.goods_retail_price,g.goods_thumb,g.click_count,g.volume ";
		$sql.="FROM ".PREFIX."goods AS g JOIN ".PREFIX."goods_link AS gl ON(g.id=gl.link_goods_id) ";
		$sql.="WHERE gl.goods_id IN ($ids) AND g.recycle_bin=0 AND shelves=1 {$group} LIMIT ".$limit;
		return M()->query($sql);		
	} 	
	
	/**最近浏览
	 *$id  如果为空取数据库最近浏览数据,否则记录的商品ID集合
	 *$num  保存商品ID个数，为0时不限制
	 *$pageSize 分页控制
	 *$idDelete  true 删除
	*/
	protected function recentVisit($id,$num=24,$pageSize=0,$idDelete=false){
		 if(!C('IS_RECENT_VISIT')) return '';
		 //删除
		 if($idDelete){
			 $recent_visit=cookie('recent_visit');
			 $arrs=explode(',',$recent_visit);
			 
			 foreach($arrs as $k => $v){
				 if($v==$id){
					 unset($arrs[$k]);
					 break;
				 }
			 }
			if($arrs){
				 $recent_visit=implode(',',$arrs); 
				 if($recent_visit) cookie('recent_visit',rtrim($recent_visit,','),86400 * 360);//保存一年 
			}else{
				cookie('recent_visit',null,time()-3600);//保存一年 
			}
		     return true;
		 }
		 
		 if($id){
			  $recent_visit=cookie('recent_visit');
			  if($num > 0){
				  $arrs=explode(',',$recent_visit);
				  $counts=count($arrs);
				  if($counts > $num){
					  unset($arrs[$counts - 1]);
					  $recent_visit=implode(',',$arrs); 
				  }
			  }
			  if(strpos($recent_visit,$id) ===false) cookie('recent_visit',rtrim($id.','.$recent_visit,','),604800);//保存一个星期	 
		 }else{
			  $cookie=cookie('recent_visit');
			  if(empty($cookie)) return;
		      $field="id,{$this->langfx}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,";
			  $field.="goods_thumb,goods_thumb2,stock,volume";
			  $where['shelves']=1;
			  $where['recycle_bin']=0;
			  $where['id']=array('in',$cookie);
			  $goods=M('goods');
			  if($pageSize > 0){
				 $counts=$goods->where($where)->count();
				 $this->doPage($counts,$pageSize);
				 $limit=$GLOBALS['limit'];   				  
			  }else{
			     $limit=$num;  
			  }
			  return $goods->field($field)->where($where)->order()->limit($limit)->select();
		 }
	}
	
	/**
     * 猜你喜欢(根据用户浏览习惯，记录浏览次数最多的分类ID)
	 * $cateId    写入COOKIE时的商品分类ID
	 * $getNum 大于0时获取数据库条数，否则写入COOKIE  
	 * *$where  其它条件查询
     * */
	public function guessYouLike($cateId=0,$getNum=0,$where=''){
		if(!C('IS_GUESS_YOU_LIKE')) return '';
		$arrarys=json_decode(cookie('guess_you_like'),true);
		if($getNum > 0){ //获取数据
		   $recentVisit=cookie('recent_visit'); //已浏览器过的商品ID
		   $temp=multi_array_sort($arrarys,'count',SORT_DESC);			
		   $descId=$temp[0]['id'];	
		   $g=M('goods');
		   $where="goods_category_id IN($descId) AND id NOT IN ({$recentVisit}) AND shelves=1 AND recycle_bin=0 ".emptyCity()." ".$where;
		   $field="id,{$this->langfx}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,";
		   $field.="goods_thumb,goods_thumb2";
		   $result=$g->field($field)->where($where)->order(array('volume'=>'DESC'))->limit(30)->select();
		   $counts=count($result);
		   if($counts < 10){
		       $notIds='';
			   if($recentVisit) $notIds=$recentVisit;
			   if($result) $notIds=$notIds.','.in_id($result,'id');
			   if($notIds) $where="id NOT IN ({$notIds}) AND shelves=1 AND recycle_bin=0";
			   else $where="shelves=1 AND recycle_bin=0";
			   $res=$g->field($field)->where($where)->order(array('volume'=>'DESC'))->limit(20)->select();
		   }
		   foreach($res as $k=>$v) $result[$counts++]=$v;
		   return $result;
		}else{//写入数据
			if(count($arrarys) >= 24) {
				if($arrarys[$cateId]['id']) $arrarys[$cateId]['count']+=1;
				$temp=multi_array_sort($arrarys,'count',SORT_ASC);			
				unset($arrarys[$temp[0]['id']]);
			}else{
				$arrarys[$cateId]['id']=$cateId;
				$arrarys[$cateId]['count']+=1;			
			}
			cookie('guess_you_like',json_encode($arrarys),86400 * 30);			
		}
	}	
	
	/**
	*COOKIE 商品收藏
	*$id 商品ID,大于0时写入收藏;否则获取收藏列表
	*$idDelete   true 删除；否则添加；
	*/
	protected function cookieCollect($id,$idDelete=false){
		 //删除
		 if($idDelete){
			 $cookieCollect=cookie('cookie_collect');
			 $arrs=explode(',',$cookieCollect);
			 
			 foreach($arrs as $k => $v){
				 if($v==$id){
					 unset($arrs[$k]);
					 break;
				 }
			 }
			if($arrs){
				 $cookieCollect=implode(',',$arrs); 
				 if($cookieCollect) cookie('cookie_collect',rtrim($cookieCollect,','),86400 * 360);//保存一年 
			}else{
				cookie('cookie_collect',null,time()-3600);//保存一年 
			}
		     return true;
		 }
		 if($id){
			  $cookieCollect=cookie('cookie_collect');
			  $arrs=explode(',',$cookieCollect);
			  $counts=count($arrs);
			  if($counts > 40){ //最多保存40个商品
				  unset($arrs[$counts - 1]);
				  $cookieCollect=implode(',',$arrs); 
			  }
			  if(strpos($cookieCollect,$id) ===false) cookie('cookie_collect',rtrim($id.','.$cookieCollect,','),86400 * 360);//保存一年 
			  return true;
		 }else{
			  $cookie=cookie('cookie_collect');
			  if(empty($cookie)) return;
		      $field="id,{$this->langfx}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,";
			  $field.="goods_thumb,goods_thumb2,stock,volume";
			  $where['shelves']=1;
			  $where['recycle_bin']=0;
			  $where['id']=array('in',$cookie);
			  $goods=M('goods');
			 /* if($pageSize > 0){
				 $counts=$goods->where($where)->count();
				 $this->doPage($counts,$pageSize);
				 $limit=$GLOBALS['limit'];   				  
			  }else{
			     $limit=$num;  
			  }*/
			  return $goods->field($field)->where($where)->order()->limit($limit)->select();
		 }	
	}
    
	/**获取单页列页 固定in值 3,7
	 * $inFilesType    in(3,7)
	 * 获取时 $arr[3]
	 * */
	protected function getFiles($inFilesType){
		$sql="SELECT a.id,a.{$this->langfx}titletext AS titletext,f.files_type FROM ".PREFIX."files_sort AS f JOIN ".PREFIX."article AS a ";
		$sql.="ON(f.files_id=a.files_id AND f.files_pid > 0 AND f.display=1 AND f.files_type IN({$inFilesType})) ORDER BY files_type ASC";
		$result=M()->query($sql);
		$type='-1';
		$arr=array();
		foreach($result as $k=>$v){
			if($type==$v['files_type']){
				$arr[$type][$v['id']]['id']=$v['id'];
				$arr[$type][$v['id']]['titletext']=$v['titletext'];
				$arr[$type][$v['id']]['files_type']=$v['files_type'];
			}else{
				$type=$v['files_type'];
				$arr[$type][$v['id']]['id']=$v['id'];
				$arr[$type][$v['id']]['titletext']=$v['titletext'];
				$arr[$type][$v['id']]['files_type']=$v['files_type'];				
			}
		}
		return $arr;
	}
}