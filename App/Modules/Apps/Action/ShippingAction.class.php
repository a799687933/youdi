<?php
//配送方式
class ShippingAction extends CommonAction{
		
		//配送方式列表
		public function disList(){
	       $curd=D('Curd');
		   $model=M('shipping');
	       $amp=$curd->search('shipping_name','');//询查条件,返回数组
		   $order=$this->orderBy();//临时排序，返回数组
		   $order=$order ? $order : array('orders'=>'ASC');	       
	       $counts=$model->where($amp['searchArr'])->count();
	       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
		   $this->shipping=$model->where($amp['searchArr'])->limit($GLOBALS['limit'])->order($order)->select();//关联模型
		   $this->searchVal=$amp['keyword']; //保存输入的搜索关键字
		   $this->url=$amp['url']; //保存查询条件			
		   $this->display();
		}
		
		//添加/修改配送公司
		public function disSend(){
			if(is_numeric($_REQUEST['shipping_id'])){
				$this->save=M('shipping')->where(array('shipping_id'=>$_REQUEST['shipping_id']))->find();
			}
			$this->display();
		}
		
		//添加或修改配送公司表单处理
		public function disSendForm(){
			if($_GET['one']=='ok'){
				$this->saveOen();
			}else if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}	
			if($_POST['shipping_id']){ //修改
				D('Curd')->modify('shipping','disList','','shipping_id',array($_POST['logo']));	
			}else{  //新增
				D('Curd')->insert('shipping','disList',array($_POST['logo']));
			}						
		}
		
		//删除配送公司
		public function disDel(){
             if(M('shipping')->where(array('shipping_id'=>$_REQUEST['ids']))->delete()){
             	return_json(200,'操作成功！','disList','','forward',U(APP_APPS.'/Shipping/disList/',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')])));
             }else{
             	return_json(300,'操作失败！');
             }
		}
}
?>