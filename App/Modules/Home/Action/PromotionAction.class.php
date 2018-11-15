<?php
// 促销商品列表页
class PromotionAction extends PromotionBasicAction {
    //促销商品列表
	public function index(){	
		$this->basicIndex(10);
		//$this->leftHot=$this->isPromotion(0,10,"","p.sales DESC");//左边热门促销
		//$this->display();
	}

}