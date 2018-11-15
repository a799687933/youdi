<?php
//招聘职位控制器
class RecruitAction extends CommonAction {
	//用户简历列表
    public function recList(){
       $m=M('recruit');
	   $counts=$m->count();
       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $f='id,type,user_name,sex,mobile,email,add_time';
       $this->result=$m->field($f)->order("id DESC")->limit($GLOBALS['limit'])->select();
       $this->display();	   
    }
    
	//用户简历内容
	public function recShow(){
        $id=intval($_GET['id']); 
		$this->result=M('recruit')->where(array('id'=>$id))->find();
		$this->display();
	}

	//删除用户简历
	public function recDelete(){
       $ids=$_REQUEST['ids'];
       M('recruit')->where("id IN ({$ids})")->delete();
	   $url=U(APP_APPS.'/Recruit/recList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
	   return_json(200,'操作成功！',$action,'','forward',$url);
	}

}