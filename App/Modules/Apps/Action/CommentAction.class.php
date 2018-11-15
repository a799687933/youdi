<?php
class CommentAction extends CommonAction{
		
		//评述列表
		public function commentList(){
			$curd=D('Curd');
			$Relation=D('CommentRelation');
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
			$ArticleComment=$Relation->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
			$this->ArticleComment=$ArticleComment;
			$this->url=$where['url']; //查询条件分页URL
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
			//p($ArticleComment);
			$this->display();
		}
		
		//查看评
		public function commentShow(){
            $this->show=M('comment')->field('content')->where(array('id'=>$_GET['id']))->find();
			$this->display();
		}
		
		//修改评述
		public function commentSave(){
			if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}
		}
		
		//删除评述
		public function commentDelete(){
            $wheres['comment_id']=array('in',$_REQUEST['ids']);
			//获取ID集
			$arrId=M('comment_reply')->where($wheres)->select();

			//先删除回复表
			M('comment_reply')->where($wheres)->delete();
			D('Curd')->del_img(D('Curd')->id_set($arrId),'article_comment_reply');//批删回复图片
		   $url=U(APP_APPS.'/Comment/commentList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')],'action'=>'commentList'));
		   D('Curd')->del('comment','commentList',$url);//删除		
		}

}
?>