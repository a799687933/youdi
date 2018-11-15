<?php
// 本类由系统自动生成，仅供测试用途

class CooperationAction extends CommonAction {
	//在线留言列表
    public function cooperationList(){
       $db=M('cooperation');
	   $order=$this->orderBy();//临时排序，返回数组
	   $order=$order ? $order : array('id'=>'DESC');		   
       $this->callAjaxPage(C('BGPAGENUM'),$db->count());//分页
	   $this->msglist=$db->limit($GLOBALS['limit'])->order($order)->select();
       $this->display();	   
    }
    
	//查看留言资料
   public function showCooperation(){
   	   $this->result=M('cooperation')->where(array('id'=>$_GET['id']))->find();
	   $this->display();
   }
   
   //删除在线留
   public function delCooperation(){
   	if(M('cooperation')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
        $url=U(APP_APPS.'/Cooperation/cooperationList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		return_json(200,'操作成功！','cooperationList','','forward',$url);
   	}else{
   		return_json(300,'操作失败！');
   	}
   }
	
}