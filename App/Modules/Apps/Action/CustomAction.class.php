<?php
// 本类由系统自动生成，仅供测试用途

class CustomAction extends CommonAction {
	//私人订制列表
    public function customList(){
       $db=M('custom');  
       $this->callAjaxPage(C('BGPAGENUM'),$db->count());//分页
	   $this->custom=$db->limit($GLOBALS['limit'])->order("add_time DESC")->select();
       $this->display();	   
    }
    
	//查看私人订制内容
   public function showCustom(){
   	   $this->result=M('custom')->where(array('id'=>$_GET['id']))->find();
	   $this->display();
   }
   
   //删除私人订制
   public function delCustom(){
           M('custom')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete();
	       $url=U(APP_APPS.'/Custom/customList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		   return_json(200,'操作成功！',$action,'','forward',$url);
   }
	
}