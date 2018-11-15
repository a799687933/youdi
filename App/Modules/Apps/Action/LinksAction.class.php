<?php
class LinksAction extends CommonAction{
	 //友情链接列表
	 public function index(){
	 	$amp=D('Curd')->search('link_name'); //搜索反回数组
		$counts=M('links')->where($amp['searchArr'])->count();
		$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	 	$this->links=M('links')->where($amp['searchArr'])->limit($GLOBALS['limit'])->order('link_sort')->select();
		$this->url=$amp['url'];
		$this->searchVal=$amp['keyword']; //保存输入的搜索关键字
	 	$this->display();
	 }
	 
	 //添加/修改友情链接界面
	 public function addOrSave(){
	 	if($_GET['id']){
	 	   $this->save=M('links')->where(array('id'=>$_GET['id']))->find();
		}
	 	$this->display();
	 }
	 
	 //修改友情链接表单处理
	 public function addOrSaveForm(){
	 	if($_GET['one']=='ok'){
	 		$this->saveOen();
	 	}else if($_GET['yes_no']=='ok'){
	 		$this->saveSwitch();
	 	}else if($_POST['id']){  //修改
		 	$where['link_url']=$_POST['link_url'];
			$where['id']=array('neq',$_POST['id']);
			if(M('links')->where($where)->select()) return_json(300,'友情链接重请检查后再提交！');
			$url=U(APP_APPS.'/Links/'.$_POST['action'],array(C('VAR_PAGE')=>$_POST[C('VAR_PAGE')],'action'=>$_POST['action']));
			
			D('Curd')->modify('links','index','','id',array($_POST['logos'])); //修改数据	 		
	 	}else{ //新增
	 		if(M('links')->where(array('link_url'=>$_POST['link_url']))->find()) return_json(300,'友情链接重请检查后再提交！'); //友情链接是否重复
	 		$_POST['add_tiem']=time();
	 		D('Curd')->insert('links','index',array($_POST['logos'])); //修改数据	 
	 	}

	 }
	 
	 //删除友情链接
	 public function linkDelete(){
		$url=U(APP_APPS.'/Links/'.$_REQUEST['action'],array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')],'action'=>$_REQUEST['action']));
		D('Curd')->del('links','index',$url);//删除	
	 }
}
?>