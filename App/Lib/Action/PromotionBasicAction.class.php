<?php
// 促销商品公共控制器
class PromotionBasicAction extends BasicAction {
    /**
	*促销商品列表
	*$limtSize  第页显示多少数据；
	*/
    public function basicIndex($limtSize){
	    //检索条件
	    $where='';
		if($_GET['keyorwds']) $where.="AND g.{$this->langfx}.goods_name LIKE '%{$_GET['keyorwds']}%' ";
		if($_GET['min'] >=0 && $_GET['max'] > $_GET['min']) $where.=" AND p.promotion_price >='{$_GET['min']}' AND p.promotion_price <='{$_GET['max']}' ";

	   //排序
	   if($_GET['order_type']){
	        if($_GET['order']=='asc'){
			   $this->orders='desc';
			}else{
			   $this->orders='asc';
			}
			$orderField=" p.{$_GET['order_type']} {$_GET['order']} ";
	   }else{
	       $this->orders='asc';
		   $orderField=" p.promotion_end_date DESC ";
	   }
	   $this->_URL_=$_GET['_URL_'];
       $this->promotion=$this->isPromotion($limtSize,0,$where,$orderField); //列表
       $this->promotionAd=$this->getAd(65,false);//导航下单张广告图
	    //$this->leftHot=$this->isPromotion(0,10,"","p.sales DESC");//左边热门促销
		if(IS_AJAX){
			$this->display('Piece/Promotion_index');
		}else{
			$this->display();
		}	       
	}

}