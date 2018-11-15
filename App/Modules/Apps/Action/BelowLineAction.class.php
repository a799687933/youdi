<?php
//线下支付银行
class BelowLineAction extends CommonAction{
		
		//支付银行列表
		public function lineList(){
           $this->result=M('below_line')->order(array('bank_orders'=>'ASC'))->select();	
		   $this->display();
		}
		
		//添加/修改支付银行
		public function lineSend(){
			if(is_numeric($_REQUEST['id'])){
				$this->save=M('below_line')->where(array('id'=>$_REQUEST['id']))->find();
			}
			$this->display();
		}
		
		//添加或修改支付银行表单处理
		public function lineSendForm(){
			if($_GET['one']=='ok'){
				$this->saveOen();
			}else if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}	
			if($_POST['id']){ //修改
				D('Curd')->modify('below_line','lineList','','id',array($_POST['bank_logo']));	
			}else{  //新增
				D('Curd')->insert('below_line','lineList',array($_POST['bank_logo']));
			}						
		}
		
		//删除支付银行
		public function lineDel(){
             if(M('below_line')->where(array('id'=>$_REQUEST['ids']))->delete()){
             	return_json(200,'操作成功！','lineList','','forward',U(APP_APPS.'/BelowLine/lineList/',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')])));
             }else{
             	return_json(300,'操作失败！');
             }
		}
}
?>