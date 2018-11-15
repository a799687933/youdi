<?php
// 推荐品牌列表
class BrandAction extends BrandBasicAction {
	//品牌列表
    public function index(){
	    $this->basicIndex();		
    }
	
	//品牌商品列表
	public function cover(){
	   $this->brandCover(20);
	   $this->display();
	}

}