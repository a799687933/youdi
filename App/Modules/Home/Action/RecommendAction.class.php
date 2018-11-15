<?php
// 热门推荐控制器
class RecommendAction extends RecommendBasicAction {

	public function index(){	
	  $this->basicIndex(20);
	}
	

}