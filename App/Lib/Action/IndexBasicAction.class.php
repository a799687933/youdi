<?php
// 首页公共控制器
class IndexBasicAction extends BasicAction {

    protected function basicIndex(){
		//is_mobile(U(APP_MOBILE.'/Index/index')); //自动跳到手机板
		 //分组商品分类检索ID
		 foreach($GLOBALS['cateList'] as $k=>$v){
			$oneId=in_id($v['child'],'id');			
			if($v['child']){
				foreach($v['child'] as $k1=>$v1){
					$wtoId=in_id($v1['child'],'id');
					$GLOBALS['cateList'][$k]['child'][$k1]['in_id']=$wtoId;
					if($wtoId) $allId.=$wtoId.',';
				}
			}
			if($allId) $oneId.=','.rtrim($allId,',');
			$GLOBALS['cateList'][$k]['in_id']=$oneId;
			$oneId='';
			$allId='';
		 }
       
	     //楼层
	     $floors=array();
	     $goods=M('goods');
	     foreach($GLOBALS['cateList'] as $k=>$v){
	   	   $GLOBALS['cateList'][$k]['floors']=$goods->field("id,".pfix("goods_name")." AS goods_name,goods_thumb,goods_market_price,goods_retail_price")
	   	   ->where("shelves=1 AND recycle_bin=0 AND stock >0 AND is_promotion = 0 AND goods_category_id IN({$v['in_id']})")
	   	   ->order("review_score DESC,volume DESC,click_count DESC")->limit(8)->select();
	     }
	     $this->floors=$GLOBALS['cateList'];
	   
		 //导航下多图切换广告
		 $this->navAd=$this->getAd(45,true);	
		 
		 //新闻导读
		// $sql="SELECT a.id,a.titletext FROM ".PREFIX."article AS a JOIN ".PREFIX."files_sort AS f ON (f.files_id=a.files_id) ";
		// $sql.="WHERE f.files_type=3 AND a.is_show=1 ORDER BY a.id ASC LIMIT 7";
		// $this->news=$this->DB->query($sql);
		 
		 //首页促销5个商品
		 $prom=$this->isPromotion(0,5);
		 $this->prom=$prom;
		 
		 //推荐商品
		 $this->recommend=$this->getGoodsTb("recommend=1 AND stock > 0 AND recycle_bin=0 AND shelves=1 AND is_promotion=0","goods_order ASC",5);
		 
		 //推荐品牌
		 //$this->brand=M('goods_brand')->where(array('display'=>1))->order(array('orders'=>'ASC'))->select();
		 
		 //热门推荐
		/* $hotList=array();
		 $g=M('goods');
		 foreach($GLOBALS['cateList'] as $k=>$v){
              $hotList['name'][$k]['name']=$v['name']; 
			  $hotList['name'][$k]['id']=$v['id']; 
			  $hotList['goods'][$k]=$g->field('id,goods_name,goods_market_price,goods_retail_price,goods_thumb')
			  ->where(array('hot'=>1,'stock'=>array('gt',0),'shelves'=>1,'is_promotion'=>0,'goods_category_id'=>array('in',$v['in_id'])))
			  ->order(array('volume'=>'DESC'))->limit(5)
			  ->select(); 
		 }
         $this->hotList=$hotList;*/
        
		//楼层
	/*	$floors=array();
		$cates=array();
		 foreach($GLOBALS['cateList'] as $k=>$v){
			  //新品
		      $gnews=$g->field('id,goods_name,goods_market_price,goods_retail_price,goods_thumb')
			  ->where("stock > 0 AND shelves=1 AND is_promotion=0 AND is_new=1 AND hot=0 AND goods_category_id IN({$v['in_id']}) ")
			  ->order(array('create_time'=>'DESC'))->limit(4)
			  ->select();
			  //分类商品
              foreach($v['child'] as $k1=>$v2){
				 $cat=$g->field('id,goods_name,goods_market_price,goods_retail_price,goods_thumb')
				  ->where("stock > 0 AND shelves=1 AND is_promotion=0 AND is_new=0 AND hot=0 AND goods_category_id ='{$v2['id']}' ")
				  ->order(array('volume'=>'DESC'))->limit(8)
				  ->select();	
				  $cates[$k1]=$cat;
			  }
			  $floors[$k]['name']=$v['name'];
			  $floors[$k]['id']=$v['id'];
			  $floors[$k]['is_new']=$gnews;
			  $floors[$k]['list_name']=$v['child'];
			  $floors[$k]['list_goods']=$cates;
			  $cates=array();
		 }//p($floors);
		 $this->floors=$floors;     
		 */
		//显示领取现金卷
		 $tool=new ToolModel();
		 $this->bonus=$tool->showBonusType();
		
	     $this->display();
    }
}