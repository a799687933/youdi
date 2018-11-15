<?php
/*新版评论2018-5-5*/
class NewCommentAction extends CommonAction{
		
		//YOUDI WU最新资讯 每条文章评论列表
		public function articleComList(){
			//修改显示状态
			if($_GET['one']=='ok'){
				$this->saveOen();
			}else if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}				
			$article_id=intval($_GET['article_id']);
			$where="article_id='{$article_id}'";
			$db=M('comment');
			$order=$this->orderBy();//临时排序，返回数组
	    	$order=$order ? $order : array('times'=>'DESC');			
			$counts=$db->where($where)->count();
			$this->callAjaxPage(24,$counts);//分页
			$result=$db->where($where)->limit($GLOBALS['limit'])->order($order)->select();
			if($result){
				$uid=in_id($result,'user_id');
				$m=M('member')->field('id,user_name,head_pic,full_name,surname')->where("id IN ({$uid})")->select();
				foreach($m as $v) $member[$v['id']]=$v;
				foreach($result as $k=>$v){
					$name=$member[$v['user_id']]['surname'].$member[$v['user_id']]['full_name'];
					$result[$k]['user_name']=$name ? $name : $member[$v['user_id']]['user_name'];
					$result[$k]['head_pic']=$member[$v['user_id']]['head_pic'];
				}
			}
			$this->result=$result;
			$this->display();
		}

		//删除每条文章评论
		public function articleComDelete(){
			$ids=$_REQUEST['ids'];
			$article_id=intval($_REQUEST['article_id']);
			$del_count=0;
			$del_count=M('comment_reply')->where("comment_id IN ({$ids})")->delete();
			$del_count+=M('comment')->where("id IN ({$ids})")->delete();
			if($del_count > 0){
				M('article')->where("id='{$article_id}'")->setDec('comment',$del_count);
			}
			$url=U(APP_APPS.'/NewComment/articleComList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')],'article_id'=>$article_id,'action'=>'articleComList'));
		    return_json(200,'操作成功！','articleComList','','forward',$url);
		}
		
		////YOUDI WU最新资讯 每条文章评论@回复记录列表
		public function articleComReply(){
			$types=$_REQUEST['types'];
			$comment_id=intval($_REQUEST['comment_id']);
			$article_id=intval($_REQUEST['article_id']);
			if($types == 'add'){ //添加
			   return $this->_editComReply();
			}else if($types == 'del'){ //删除
			   return $this->_delComRely();
			}
			if($types == 'list'){
				$user_id=intval($_REQUEST['user_id']);
				$reuslt=M('comment_reply')->where(array('comment_id'=>$comment_id))->order('times ASC')->select();
				$this->reuslt=$reuslt;
				$member=M('member')->field('id,user_name,surname,full_name')->where(array('id'=>$user_id))->find();
				$name=$member['full_name'].$member['surname'];
				$this->name=$name ? $name : $member['user_name'];
			    $this->display('reply_list');
			    exit();
			}
			$this->display();
		}
		
		//添加@回复记录
		private function _editComReply(){
			$comment_id=intval($_REQUEST['comment_id']);
			$article_id=intval($_REQUEST['article_id']);		
			$content=strip_tags($_REQUEST['content']);
			$user_id=intval($_REQUEST['user_id']);
			if(!$content) die('0');
			$add['article_id']=$article_id;
			$add['comment_id']=$comment_id;
			$add['user_id']=0;
			$add['content']=$content;
			$add['is_show']=1;
			$add['times']=time();
			$add['is_read']=0;
			$add['read_user_id']=$user_id;
			if(M('comment_reply')->add($add)){
				M('article')->where("id='{$article_id}'")->setInc('comment');
				die('1');
			}
			die('0');
		}
		
		//删除@回复记录
		private function _delComRely(){
		    $id=intval($_REQUEST['id']);
			$article_id=intval($_REQUEST['article_id']);				
			if(M('comment_reply')->where("id='{$id}'")->delete()){
			    M('article')->where("id='{$article_id}'")->setDec('comment');
				die('1');
			}
			die('0');
		}
		
		//资讯2评论列表
		public function replyList2(){
		    $article_id=intval($_REQUEST['article_id']);
			$result=M('comment_reply')->where(array('article_id'=>$article_id))->order("times ASC")->select();
			if($result){
				$uid=in_id($result,'user_id');
				$uid.=','.in_id($result,'read_user_id');
				$m=M('member')->field('id,user_name,head_pic,surname,full_name')->where("id IN ({$uid})")->select();
				foreach($m as $v){
					$name=$v['full_name'].$v['surname'];
				    $member[$v['id']]['user_name']=$name ? $name : $v['user_name'];
					$member[$v['id']]['head_pic']= $v['head_pic'];
				}
				foreach($result as $k=>$v){
					$user=$member[$v['user_id']] ? $member[$v['user_id']] : $member[$v['read_user_id']];
					$result[$k]['user_name']=$user['user_name'];
					$result[$k]['head_pic']=$user['head_pic'];
				}
			}
			$this->assign('result',$result);
			$this->display();
		}
		
		//回复资讯2评论
		public function editReplyList2(){
			$article_id=intval($_REQUEST['article_id']);
			$reply_id=intval($_REQUEST['reply_id']);
			$user_id=intval($_REQUEST['user_id']);
			if($_POST){
				$content=strip_tags($_POST['content']);
				if(!$content) return_json(300,'请输入回复内容~');
				$insert['article_id']=$article_id;
				$insert['comment_id']=0;
				$insert['user_id']=0;
				$insert['content']=$content;
				$insert['is_show']=1;
				$insert['times']=time();
				$insert['is_read']=0;
				$insert['read_user_id']=$user_id;
				$isTrue=M('comment_reply')->add($insert);
				if($isTrue) {
					M('article')->where(array('id'=>$article_id))->setInc('comment');
					return_json(200,'回复成功~','replyList2','','closeCurrent');	
				}
				return_json(300,'回复失败~');	
			}
			$this->assign('article_id',$article_id);
			$this->assign('reply_id',$reply_id);
			$this->assign('user_id',$user_id);
		    $this->display();
		}
		
		//删除资讯2评论
		public function delReplyList2(){
		    $ids=$_REQUEST['ids'];
			$article_id=intval($_REQUEST['article_id']);
			$counts=M('comment_reply')->where("id IN ({$ids})")->delete();
			$counts=$counts ? $counts : 0;
			if($counts > 0){
				M('article')->where(array('id'=>$article_id))->setDec('comment',$counts);
			    $url=U(APP_APPS.'/NewComment/replyList2',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')],'article_id'=>$article_id,'action'=>'replyList2'));
		        return_json(200,'操作成功~','replyList2','','forward',$url);					
			}
			return_json(300,'删除失败~');				
		}
		

}
?>