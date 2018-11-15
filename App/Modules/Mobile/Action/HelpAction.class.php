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
		$sql="SELECT a.id,a.{$this->langfx}titletext AS titletext FROM ".PREFIX."article AS a JOIN ".PREFIX."files_sort AS f ";
		$sql.="ON (a.files_id=f.files_id AND f.files_type=4) WHERE a.is_show=1 ORDER BY a.orders ASC";
		$this->result=$this->DB->query($sql);
		$this->title="商城帮助";
		$this->display();
	}
	

}