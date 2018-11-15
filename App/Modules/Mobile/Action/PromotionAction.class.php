<?php
// 促销商品列表页
class PromotionAction extends PromotionBasicAction {
    //促销商品列表
	public function index(){	
		$this->basicIndex(20);	
	}

}