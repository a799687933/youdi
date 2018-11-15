<?php
// 推荐品牌列表
class BrandAction extends BrandBasicAction {
	//品牌列表
    public function index(){
	    $this->basicIndex(24);		
	    //热门推荐
	    //$this->hots=$this->getGoodsTb(array('recycle_bin'=>0,'shelves'=>1,'hot'=>1,'stock'=>array('gt',0),'is_promotion'=>0),"volume DESC ",10);
	    
    }
	
	//品牌商品列表
	public function cover(){
	   $this->brandCover(20);
	   $this->display();
	}

}