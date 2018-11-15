<?php
//在家免费试穿控制器
class WantTestAction extends CommonAction {
	//免费试穿列表
    public function testList(){
       $m=M('want_test');
	   $counts=$m->count();
       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
       $this->result=$m->order("id DESC")->limit($GLOBALS['limit'])->select();
       $this->display();	   
    }

	//删除在家试穿
	public function testDelete(){
       $ids=$_REQUEST['ids'];
       M('want_test')->where("id IN ({$ids})")->delete();
	   $url=U(APP_APPS.'/WantTest/testList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
	   return_json(200,'操作成功！',$action,'','forward',$url);
	}

}