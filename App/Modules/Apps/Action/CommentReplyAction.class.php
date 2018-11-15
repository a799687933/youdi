<?php
class CommentReplyAction extends CommonAction{
		
		//回复列表
		public function replyList(){
			$curd=D('Curd');
			$Relation=D('CommentReplyRelation');
			if($_POST['titletext']){
				$amp['titletext']=array('LIKE','%'.$_POST['titletext'].'%');
				$arrId=M('article')->field('id')->where($amp)->select();
				$_REQUEST['article_id']=$curd->id_set($arrId);
				$article_id='article_id';		
			}
            if($_REQUEST['article_id']) $article_id='article_id';
			
			$where=$curd->search('','',$article_id); //询查条件返回数组
			$order=$this->orderBy();//临时排序，返回数组
	    	$order=$order ? $order : array('times'=>'DESC');			
			$counts=$Relation->where($where['searchArr'])->relation(true)->count();
			$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
			$this->commentReply=$Relation->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
			$this->url=$where['url']; //查询条件分页URL
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
			//p($commentReply);die;
			$this->display();
		}
		
		//查看评
		public function replyShow(){
            $show=M('comment_reply')->field('content')->where(array('id'=>$_GET['id']))->find();
			 $img= '/(\[)([\w\.\-\/]+)(\])/';//表情匹配模式
			 preg_match_all($img,$show['content'],$arrImg);
			  foreach($arrImg[0] as  $arr_k=>$arr_v){
				 $show['content']=str_replace($arr_v,'<img src="'.$arrImg[2][$arr_k].'"/>',$show['content']);
			  }
			$this->show=$show;
			$this->display();
		}
		
		//修改评述
		public function replySave(){
			if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}
		}
		
		//删除评述
		public function replyDelete(){
		   $url=U(APP_APPS.'/CommentReply/replyList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
		   D('Curd')->del('comment_reply','replyList',$url);//删除		
		}

}
?>