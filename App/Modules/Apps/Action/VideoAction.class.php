<?php
// 本类由系统自动生成，仅供测试用途

class VideoAction extends CommonAction {

	//视频列表
    public function vodeoList(){
        $db=M('video'); 
		if($_REQUEST['title']) $where['title']=array('like',"%{$_REQUEST['title']}%"); 
		$counts=$db->where($where)->count();
		$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
		$result=$db->where($where)->order("id DESC")->limit($GLOBALS['limit'])->select();
		$this->result=$result;
        $this->display();	
    }
	
	//添加修改视频界面
	public function addSaveVedeo(){
		if($_GET['id']){
			$this->save=M('video')->where(array('id'=>$_GET['id']))->find();
		}
	    $this->display();
	}
	
	//添加修改视频表单处理
	public function addSaveVedeoForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}				
		$_POST['add_time']=time();	
		if($_POST['id']){
		   D('Curd')->modify('video','vodeoList',$url,'id',array($_POST['thumbnail']),'','','');
		}else{
		   D('Curd')->insert('video','vodeoList',array($_POST['thumbnail']),'','','');
		}
	}
	
	//删除视频讲座
	public function deleteVedelo(){
		$url=U(APP_APPS.'/Video/vodeoList');
	   D('Curd')->del('video',$action,$url,'ids','id');
	}
	

}