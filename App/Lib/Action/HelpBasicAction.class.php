<?php
// 平台帮助文章公共控制器
class HelpBasicAction extends BasicAction {
	//帮助文章内容
    protected function basicIndex(){
	    $id=getNum($_GET['html']);
		$sql="SELECT a.id,a.{$this->langfx}titletext as titletext,a.{$this->langfx}keywords AS keywords,a.{$this->langfx}bewrite AS bewrite,";
		$sql.="a.{$this->langfx}content AS content,a.addtime,a.{$this->langfx}author AS author,a.count_click,f.{$this->langfx}files_name AS files_name,f.files_pid,f.files_id FROM ";
		$sql.=PREFIX."article AS a JOIN ".PREFIX."files_sort AS f ON(a.files_id=f.files_id) WHERE a.id='{$id}' AND a.is_show=1 LIMIT 1";
		$result=$this->DB->query($sql); 
		$this->title=$result[0]['titletext'];
		$this->result=$result[0];
    }

}