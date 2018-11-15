<?php
//商品分类列表控制器
class CategoryAction extends CategoryBasicAction {
    //商品列表页
    public function index(){

	  $this->basicIndex(18);
	  //热门推荐
	  $this->hot=$this->getGoodsTb(array('recycle_bin'=>0,'shelves'=>1,'hot'=>1,'stock'=>array('gt',0),'is_promotion'=>0),"volume DESC ",8);
	  //促销商品
	  $this->promotion=$this->isPromotion(0,8);
	  //导航下单张广告图片
	  $this->navAdOen=$this->getAd(59,$ad,false);	
    }
    
	//AJAX检索区域
	public function searchRegion(){
		$model=new CommonModel();
		return $model->searchRegion();
	}			  
  
}