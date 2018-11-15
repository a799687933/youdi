<?php
// 关于我们控制器
class AboutAction extends CommonAction {

	public function index(){	
	    yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
	    $id=str_replace('about_','',$_GET['html']);
		$db=M('article');
		$contet=$db->field(PREFIX.'article.id,'.PREFIX.'article.titletext,'.PREFIX.'article.keywords,'.PREFIX.'article.bewrite,'.PREFIX.'article.content,'.PREFIX.'article.addtime,'.PREFIX.'files_sort.files_name,'.PREFIX.'files_sort.files_id')
		->join(PREFIX.'files_sort ON '.PREFIX.'files_sort.files_id='.PREFIX.'article.files_id')
		->where(array(PREFIX.'article.id'=>$id))->find();
		$this->result=$contet;
		$this->display();
	}
	

}