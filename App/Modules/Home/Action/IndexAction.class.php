<?php
// 首页控制器
class IndexAction extends BasicAction{
	
    public function index(){
		//$this->error('修改完成后通知我');
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
    	$this->indexAd=$this->getAd(45,true);
    	$this->display();
    }

    //不再显示提示框(有效期一年)
    public function notMsg(){
		cookie('app_box',1,86400 * 360);
		die('1');
    }	

}