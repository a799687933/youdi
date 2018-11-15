<?php
// 本类由系统自动生成，仅供测试用途

class FloatingAction extends CommonAction {
	//漂浮客服列表
    public function floatList(){
       $this->floatLi=M('floating')->order(array('orders'=>'ASC'))->select();
       $this->display();	   
    }
    
	//添加漂浮客服
	public function floatAdd(){

		$this->display('Floating/addSave');
	}

	
	//修改漂浮客服
	public function floatSave(){
		$this->save=M('floating')->where(array('id'=>$_GET['id']))->find();
       $this->display('Floating/addSave');
	}
	
	//漂浮客服添加/修改表单处理
	public function floatSendForm(){
	    //修改单个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}		
		if($_POST['id']){ //修改
			D('Curd')->modify('floating','floatList','','id');
		}else{ //新增
		    $_POST['addtime']=time();
		   D('Curd')->insert('floating','floatList');
	    }
	}
	
	//删除漂浮客服
	public function  floatDelete(){
       if(M('floating')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
		   return_json(200,'操作成功！','floatList','','forward',U(APP_APPS.'/Floating/floatList'));
	   }else{
		   return_json(300,'操作失败！');
	   }
	}
	

	
}