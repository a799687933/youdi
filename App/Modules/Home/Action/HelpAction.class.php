<?php
//平台帮助文章 
class HelpAction extends HelpBasicAction {
	
	//帮助文章内容
    public function index(){	 
		$this->basicIndex();
        $this->display();
    }

	
	//帮助文章列表
	public function lists(){
		$result=M('files_sort')->field('files_id,'.pfix('files_name').'files_name AS files_name')->where("fiels_type=4 AND files_pid > 0")->order("files_sort asc")->select();
		p($result);
	}
	

}