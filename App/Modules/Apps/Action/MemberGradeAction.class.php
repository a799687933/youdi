<?php
class MemberGradeAction extends CommonAction {
	
	 //会员等级表列表
    public function gradeList(){
       $this->grade=M('member_grade')->order(array('id'=>'ASC'))->select();
       $this->display();
    }
	
	//添加会员等级
	public function gradeAdd(){
        $this->gradeName=$this->langIsMore('member_grade','grade_name'); //广告类型多语言		
		if($_GET['id']) $this->result=M('member_grade')->where(array('id'=>$_GET['id']))->find();
		$this->display();
	}
	
	//添加会员等级表单处理
	public function gradeAddForm(){
			if($_GET['one']=='ok'){
				$this->saveOen();
			}else if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}		
		   if($_POST['id']){
			  D('Curd')->modify('member_grade','gradeList','','id');
		   }else{
			  $this->requestArray('member_grade');
			  D('Curd')->insert('member_grade','gradeList');//插入
		   }		  
	}
	
	//删除会员等级
	public function gradeDelete(){
		   $url=U(APP_APPS.'/MemberGrade/gradeList');
		   D('Curd')->del('member_grade','gradeList',$url);//删除		
	}
}