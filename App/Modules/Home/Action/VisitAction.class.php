<?php
// 记录访问
class VisitAction extends Action {

	public function index(){	
		//记录访问
		$visit=D('VisitRecord');
		if(!$visit->autoCheckToken($_REQUEST)) {
			 die(json_encode(array('success'=>0)));
		}		
		if(!$visit->isSpider()){
			   $visit->visitStats($_REQUEST['city_name']);
		}
		die(json_encode(array('success'=>1)));
	}
	

}