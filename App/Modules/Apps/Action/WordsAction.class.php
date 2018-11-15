<?php
// 本类由系统自动生成，仅供测试用途

class WordsAction extends CommonAction {
	//在线留言列表
    public function msgList(){
       $db=M('words');
	   $order=$this->orderBy();//临时排序，返回数组
	   $order=$order ? $order : array('id'=>'DESC');		   
       $this->callAjaxPage(C('BGPAGENUM'),$db->count());//分页
	   $this->msglist=$db->limit($GLOBALS['limit'])->order($order)->select();
       $this->display();	   
    }
    
	//查看留言资料
   public function showMsg(){
   	   $this->result=M('words')->where(array('id'=>$_GET['id']))->find();
	   $this->display();
   }
   
   //回复留言
   public function reply(){
        $_POST['reply']=$_POST['reply'] ? quotes(trim(strip_tags($_POST['reply']))) : return_json(300,'请输入回复内容！');
		$_POST['reply_time']=time();
		if(M('words')->where(array('id'=>$_POST['id']))->save($_POST)){
			return_json(200,'操作成功！','msgList','','closeCurrent','');			
		}else{
			return_json(300,'操作失败！');
	    }
   }
   
   //删除在线留
   public function delMsg(){
   	if(M('words')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
        $url=U(APP_APPS.'/Words/msgList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		return_json(200,'操作成功！','msgList','','forward',$url);
   	}else{
   		return_json(300,'操作失败！');
   	}
   }
	
}