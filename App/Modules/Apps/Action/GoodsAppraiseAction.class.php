<?php
// 本类由系统自动生成，仅供测试用途

class GoodsAppraiseAction extends CommonAction {
	//商品评述列表
    public function appList(){
		$goods_id=intval($_REQUEST['goods_id']);
		$db=M('goods_appraise');
		$counts=$db->where("goods_id='{$goods_id}'")->count();
		$this->callAjaxPage(20,$counts[0]['counts']);//分页
		$result=$db->where("goods_id='{$goods_id}'")->order('id DESC')->limit($GLOBALS['limit'])->select();
		if($result){
			$uid=in_id($result,'member_id');
			$m=M('member')->field('id,user_name,head_pic,surname,full_name')->where("id IN ({$uid})")->select();
			//p($m);die;
			foreach($m as $v) $member[$v['id']]=$v;
			foreach($result as $k=>$v){
				$name=$member[$v['member_id']]['full_name'].$member[$v['member_id']]['surname'];
			    $result[$k]['user_name']=$name ? $name : $member[$v['member_id']]['user_name'];
			    $result[$k]['head_pic']=	$member[$v['member_id']]['head_pic'];
			}
		}
		$this->assign('goods_id',$goods_id);
		$this->appLists=$result;
        $this->display();
    }

	//查看商品评述
	public function appShow(){
        $result=M('goods_appraise')->where(array('id'=>$_GET['id']))->find();
		$result['feel']=$result['feel'] ? explode('-',trim($result['feel'],'-')) : '';
		$this->feel=C('GOODS_SATISFY'); //买后感受
		$this->result=$result;
		$this->photo=M('album')->where(array('tableId'=>$_GET['id'],'table_name'=>'goods_appraise'))->select();
		$this->display();
	}
	
	//修改显示评述
	public function updateApp(){
			  if($_GET['one']=='ok'){
					$this->saveOen();
			  }else if($_GET['yes_no']=='ok'){
					$this->saveSwitch();
			  }		
			  $feel='-';
			  foreach($_POST['feel'] as $k=>$v) $feel='-'.$v.$feel;
			  if($_POST['score'] < 3){
				 $sva=0;//差评
			  }else if($_POST['score'] == 3){
				 $sva=1;//中评
			  }else{
				 $sva=2;//好评
			  }
			  $_POST['sva']=$sva;
			  $_POST['feel']=$feel;
			  if(M('goods_appraise')->where(array('id'=>$_POST['id']))->save($_POST)){
				return_json(200,'操作成功！','appList','','closeCurrent');
			 }else{
				return_json(300,'操作失败！');
			}
	}
	
	//删除商品评述
	public function delApp(){
		   $url=U(APP_APPS.'/GoodsAppraise/appList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
		   D('Curd')->del('goods_appraise',$action,$url);//删除		
	}
	
	//回复用户评论  每条商品评论@回复记录列表
	public function goodsComReply(){
			$types=$_REQUEST['types'];
			$goods_appraise_id=intval($_REQUEST['goods_appraise_id']);
			$goods_id=intval($_REQUEST['goods_id']);
			if($types == 'add'){ //添加
			   return $this->_editComReply();
			}else if($types == 'del'){ //删除
			   return $this->_delComRely();
			}
			if($types == 'list'){
				$user_id=intval($_REQUEST['user_id']);
				$reuslt=M('goods_appraise_reply')->where(array('goods_appraise_id'=>$goods_appraise_id))->order('times ASC')->select();
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
		$goods_appraise_id=intval($_REQUEST['goods_appraise_id']);
		$goods_id=intval($_REQUEST['goods_id']);		
		$content=strip_tags($_REQUEST['content']);
		$user_id=intval($_REQUEST['user_id']);
		if(!$content) die('0');
		$add['goods_id']=$goods_id;
		$add['goods_appraise_id']=$goods_appraise_id;
		$add['user_id']=0;
		$add['content']=$content;
		$add['is_show']=1;
		$add['times']=time();
		$add['is_read']=0;
		$add['read_user_id']=$user_id;
		if(M('goods_appraise_reply')->add($add)){
			//M('goods_id')->where("id='{$goods_id}'")->setInc('review_count');
			die('1');
		}
		die('0');
	}
	
	//删除@回复记录
	private function _delComRely(){
		$id=intval($_REQUEST['id']);
		$goods_id=intval($_REQUEST['goods_id']);				
		if(M('goods_appraise_reply')->where("id='{$id}'")->delete()){
			//M('article')->where("id='{$goods_id}'")->setDec('review_count');
			die('1');
		}
		die('0');
	}
		
}