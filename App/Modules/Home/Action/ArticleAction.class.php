<?php
// 资讯控制器
class ArticleAction extends BasicAction {
    //资讯一
	public function index(){

		//$files=M('files_sort')->field('files_id')->where(array('files_type'=>5,'display'=>1))->select();
		//$ids=in_id($files,'files_id');
		$ids=498; //资讯一ID
		$where['files_id']=$ids;//array('in',$ids);
		$where['is_show']=1;
		$where['general']=0;
		$M=M('article');
		//排序
		if(!$_GET['order_field'] && !$_GET['order_type']){
		    $_GET['order_field']='addtime';
			$_GET['order_type']='desc';
		} 
		if(in_array($_GET['order_field'],array('attr','count_click','addtime'))){
			$this->assign('orders',$_GET['order_type'] == 'desc' ? 'asc' : 'desc');
			$orders="{$_GET['order_field']} {$_GET['order_type']}";
		}else{
			$this->assign('orders','asc');
		    $orders="addtime DESC";
		}
		//标签检索
		$babel=intval($_GET['babel']);
		if($babel > 0) $where['attr']=$babel;	
		$counts=$M->where($where)->count();
		$this->_URL_=$_GET['_URL_'];
		$this->doPage($counts,10);
		$f="id,{$this->langfx}titletext AS titletext,{$this->langfx}author AS author,thumb,addtime,{$this->langfx}bewrite AS bewrite,count_click,comment";
		$result=$M->field($f)->where($where)->order($orders)->limit($GLOBALS['limit'])->select();
		$this->result=$result;
		if(IS_AJAX){
		    $this->display('index_list');
			die;
		}
		//置顶文章
		$where['general']=1;
		unset($where['attr']);
		$this->general=$M->field($f)->where($where)->order('addtime DESC')->select();
		//广告图
		$this->newAd=$this->getAd(47);
		$this->display();
	}
	
	///资讯一文章内容
	public function content(){	
	    $id=$_GET['id'] > 0 ? $_GET['id'] :  $_GET['html'] > 0 ? $_GET['html'] : 0;
	    $f="id,files_id,attr,{$this->langfx}titletext AS titletext,{$this->langfx}keywords AS keywords,{$this->langfx}bewrite AS bewrite,";
	    $f.="{$this->langfx}content AS content,addtime,{$this->langfx}author AS author,like_count,count_click,comment";
	    $result=M('article')->field($f)->where(array('id'=>$id,'is_show'=>1))->find();
		//增加点击数
		if($result) M('article')->where(array('id'=>$id))->setInc('count_click');
        $this->result=$result;
		//多图切换
		$album=M('album')->where(array('tableId'=>$id,'table_name'=>'article'))->select();
		$this->album=$album;
		$this->imgcount=count($album);
		//广告图
		//$this->newAd=$this->getAd(55);	
		//评论列表
		$comment=$this->commentList($id,true);
		$this->comment=$comment;
		//p($comment);die;
		//文章列表总页数
		$this->pageSum=ceil(M('article')->where(array('files_id'=>$result['files_id'],'is_show'=>1))->count() / 10);
		//该用户是否已点赞过
		$memberId=getUserC('SE_USER_ID');
		if($memberId) {
			//已评论该文章
			$this->isComment=M('comment')->where("article_id='{$id}' AND user_id='{$memberId}'")->find();
			//已点赞过
			$this->clicks =M('article_click')->field('id')->where(array('article_id'=>$id,'member_id'=>$memberId))->find();
			//已收藏
			$this->collection =M('article_collection')->field('id')->where(array('article_id'=>$id,'user_id'=>$memberId))->find();
		}		
		//6条标签文章
		$act_num=6;
		if($result['attr'] > 0) $t="attr = '{$result['attr']}'";
		else $t="files_id = '{$result['files_id']}'";
		$tres=M('article')->field($f.",thumb")->where($t." AND is_show=1")->order("count_click DESC")->limit($act_num)->select();
		$tcou=count($tres);
		if($tcou > 0 && $tcou < $act_num){
			if($result['attr']){
				$notid=in_id($tcou,'id');
				$tems=M('article')->field($f.",thumb")->where("files_id = '{$result['files_id']}' AND is_show=1")->order("count_click DESC")->limit($act_num - $tcou)->select();	
				if($tems) $tres=array_merge($tres,$tems);
			}
		}

		$this->assign('tres',$tres);
	    $this->display();
	}
	
	///资讯一评论列表
	public function commentList($id=0,$isArray=false){
		$id=intval($id);
	    $M=M('comment');
		$memberId=getUserC('SE_USER_ID');
		$counts=$M->where(array('article_id'=>$id,'is_show'=>1))->count();
		$this->doPage($counts,10);
		$orders='id DESC';
		$order_type=intval($_GET['order_type']);
		if($order_type == 1){ //最热
		     $orders='like_count DESC';
		}else if($order_type == 2){ //最新
		     $orders='times DESC';
		}else if($order_type == 3){ //最旧
		     $orders='times ASC';
		}
		$result=$M->where(array('article_id'=>$id,'is_show'=>1))->limit($GLOBALS['limit'])->order($orders)->select();
		$aid=in_id($result,'id');
		//列出二次回复
		if($result){
			$rep=M('comment_reply')->where("(comment_id IN ({$aid})) AND (is_show = 1)")->order("comment_id ASC,times ASC")->select();
			$cid=$rep[0]['comment_id'];
			$reply=[];
			foreach($rep as $v){
				if($cid == $v['comment_id']){
					$reply[$cid][]=$v;
				}else{
					$cid = $v['comment_id'];
					$reply[$cid][]=$v;
				}
			}
		}
		//是否已点赞
		if($memberId > 0 && $result){
			$is_w="(article_id IN ({$aid})) AND (table_num=1) AND (member_id='{$memberId}')";
			$cli=M('article_click')->field('article_id')->where($is_w)->select();
			foreach($cli as $v) $clicks[$v['article_id']]=true;
		}
		//合拼会员信息
		$mids=in_id($result,'user_id');
		$member=M('member')->field('id,user_name,nickname,head_pic,surname,full_name')->where("id IN ({$mids})")->select();
		foreach($result as $k=>$v){
			$result[$k]['is_true']=$clicks[$v['id']];
		    $temp_reply=$reply[$v['id']];
		    $result[$k]['reply_list']=$temp_reply; //回复列表
		    $cou=count($temp_reply);	
			if($cou > 0 && $memberId == $result[$k]['user_id']){
			   if($temp_reply[$cou-1]['user_id'] ==0) $result[$k]['open_reply']=true;
			}else{
			  $result[$k]['open_reply']=false;
			}
			foreach($member as $v2){
			   if($v2['id']==$v['user_id']){
				   if($v2['nickname']) $userName=$v2['nickname'];
				   else if($v2['full_name']) $userName=$v2['full_name'].' '.$v2['surname'];
				   else if($v2['nickname']) $userName=$v2['user_name'];
				   $result[$k]['user_name']=$userName;
				   $result[$k]['head_pic']=$v2['head_pic'];
				   break;
			   }
			}
		}
		if($isArray) return $result;
		$this->comment=$result;
		$this->display();
	}
	
	//提交资讯评论
	public function commnetForm(){
		if(!verify_req_sign($_GET,array('_'),86400))  val_json('',false,'网络繁忙，请稍后再试~','','','');
		if(!is_login()) val_json('comment',false,'',U('Login/index','','').'?url='.base64_encode($_SERVER["HTTP_REFERER"]),'','');
	    $content=$_REQUEST['content'] ?  cleanScript($_REQUEST['content']) : val_json('comment-t',false,isL(L('ContentEmptys'),'内容不可空'),'','','');
		$id=$_REQUEST['id'] > 0 ? $_REQUEST['id'] :  val_json('comment-t',false,'error!','','','');
		$userId=getUserC('SE_USER_ID');
		if(!M('article')->where(array('id'=>$id))->getField('id')) val_json('comment-t',false,'error!','','','');
		if(M('comment')->where(array('article_id'=>$id,'user_id'=>$userId))->find()) val_json('comment-t',false,isL(L('EvaluatedOnce'),'只可评价一次'),'','','');
        $filter=$this->_filterContent($content); 
		if($filter['error'] > 0) val_json('',false,$filter['msg'],'','','');
		$content=$filter['data'] ;
		$data['article_id']=$id;
		$data['user_id']=$userId > 0 ? $userId : 0;
		$data['content']=$content;
		$data['score']=0;
		$data['is_show']=1;
		$data['times']=time();
		//isL(L('EvaluationSuccess'),'评价提交成功')
		if(M('comment')->add($data)) {
			//文章表加1 
			M('article')->where(array('id'=>$id))->setInc('comment');
			val_json('comment-t',true,'',$_SERVER["HTTP_REFERER"],'','');
		}
		else val_json('comment-t',false,isL(L('Failure'),'提交失败'),'','','');
	}
	
	//与管理员回复评论
	public function commentReply(){
		if(!verify_req_sign($_GET,array('_'),86400))  val_json('',false,'网络繁忙，请稍后再试~','','','');
	    if(!is_login()) val_json('',false,'',U('Login/index','','').'?url='.base64_encode($_SERVER["HTTP_REFERER"]),'','');
		$content=$_REQUEST['content'] ?  cleanScript($_REQUEST['content']) : val_json('comment-t',false,isL(L('ContentEmptys'),'内容不可空'),'','','');
		$userId=getUserC('SE_USER_ID');
		$article_id=intval($_REQUEST['article_id']);
		$comment_id=intval($_REQUEST['comment_id']);
		if(!$article_id)  val_json('',false,'非法操作','','','');
        $filter=$this->_filterContent($content); 
		if($filter['error'] > 0) val_json('',false,$filter['msg'],'','','');
		$content=$filter['data'] ;		
		$insert['article_id']=$article_id;
		$insert['comment_id']=$comment_id;
		$insert['user_id']=$userId;
		$insert['content']=$content;
		$insert['is_show']=1;
		$insert['times']=time();
		$insert['is_read']=1;
		if(M('comment_reply')->add($insert)){
		   M('article')->where(array('id'=>$article_id))->setInc('comment');
		}
		val_json('',true,'',$_SERVER["HTTP_REFERER"],'','');
	}
	
	//点赞
	public function clickLike(){
		//必须登录
        if(!is_login()) val_json('comment',false,'',U('Login/index','','').'?url='.base64_encode($_SERVER["HTTP_REFERER"]),'','');
		$id=$_GET['id'] > 0 ? $_GET['id'] : 0;
		$table_num=intval($_GET['table_num']);
		$user_id=intval($_GET['user_id']);
		$memberId=getUserC('SE_USER_ID');
		$where['article_id']=$id;
		$where['member_id']=$memberId;
		$ac=M('article_click');
		if($ac->where(array('article_id'=>$id,'table_num'=>$table_num,'member_id'=>$memberId))->delete()){
			if($table_num == 0) M('article')->where(array('id'=>$id))->setDec('like_count');
			else if($table_num == 1) M('comment')->where(array('id'=>$id))->setDec('like_count');
			val_json('',true,'','','','save');
		}else{
			$ac->add(array('article_id'=>$id,'table_num'=>$table_num,'ip'=>get_ip(),'member_id'=>$memberId,'read_user_id'=>$user_id,'add_time'=>time()));
			if($table_num == 0) M('article')->where(array('id'=>$id))->setInc('like_count');
			else if($table_num == 1) M('comment')->where(array('id'=>$id))->setInc('like_count');
			val_json('',true,'','','','del');
		}
	}
	
	//收藏夹
	public function setCollection(){
	    if(!is_login()) val_json('comment',false,'',U('Login/index','','').'?url='.base64_encode($_SERVER["HTTP_REFERER"]),'','');
		$id=$_REQUEST['id'] > 0 ? $_REQUEST['id'] :  val_json('',false,'error!','','','');
		$to_action=$_REQUEST['to_action'];
		$userId=getUserC('SE_USER_ID');		
		$type=$_GET['type'];
		if(M('article_collection')->where("article_id='{$id}' AND user_id='{$userId}'")->delete()){
			 val_json('',true,'','','','save');
		}else{
			$insert['user_id']=$userId;
			$insert['article_id']=$id;
			$insert['url']=$to_action ? $to_action : 'Article/content';
			$insert['add_time']=time();
			M('article_collection')->add($insert);
			val_json('',true,'','','','del');
		}
	}
	
	//过滤评论内容
	private function _filterContent($content){
		$face=array(
		   'face_01'=>'[流汗]',
		   'face_02'=>'[钱]',
		   'face_03'=>'[发怒]',
		   'face_04'=>'[浮云]',
		   'face_05'=>'[给力]',
		   'face_06'=>'[大哭]',
		   'face_07'=>'[憨笑]',
		   'face_08'=>'[色]',
		   'face_09'=>'[奋斗]',
		   'face_10'=>'[鼓掌]',
		   'face_11'=>'[鄙视]',
		   'face_12'=>'[可爱]', 
		   'face_13'=>'[闭嘴]',
		   'face_14'=>'[疑问]',
		   'face_15'=>'[抓狂]',
		   'face_16'=>'[惊讶]',
		   'face_17'=>'[可怜]',
		   'face_18'=>'[弱]',
		   'face_19'=>'[强]',
		   'face_20'=>'[握手]',
		   'face_21'=>'[拳头]',
		   'face_22'=>'[酒]',
		   'face_23'=>'[玫瑰]',
		   'face_24'=>'[打酱油]'
		);
		//不超过140个字
		$text_num=0;
		foreach($face as $k=>$v){
			$test=str_replace($v,'',$content);
			$fa=str_replace('[','',$v);
			$fa=str_replace(']','',$v);
			if(preg_match("/\[{$fa}\]/",$content)) $text_num++;
			$fa='<span title="'.$v.'" class="margin0 face-item '.$k.'" style="display:inline-block;height:23px;width:23px;position:relative;top:5px;"></span>';
			$content=str_replace($v,$fa,$content);
		}
		if(($test+$text_num) > 140)  return array('error'=>1,'msg'=>'评论文本请在140个字内');
		return array('error'=>0,'msg'=>'ok','data'=>$content);
	}
	
	//资讯二
	public function index2(){
		$where="files_id=501 AND is_show=1";
		//标签检索
		$babel=intval($_GET['babel']);
		if($babel > 0) $where.="AND attr='{$babel}'";
        $db=M('article');
		$counts=$db->where($where)->count();
		$this->_URL_=$_GET['_URL_'];
		$this->doPage($counts,5);
		$f="id,{$this->langfx}titletext AS titletext,attr,{$this->langfx}author AS author,thumb,addtime,{$this->langfx}bewrite AS bewrite,count_click,comment,like_count";
		$result=$db->field($f)->where($where)->order("general DESC,like_count DESC,addtime DESC")->limit($GLOBALS['limit'])->select();
		$memberId=getUserC('SE_USER_ID');
		$this->memberId=$memberId;
		if($result){
			  $ids=in_id($result,'id');
		      //多图切换 
		     $alb=M('album')->where("tableId IN ({$ids}) AND table_name='article'")->order("tableId ASC,id ASC")->select();		
			 $album=[];
			 $albid=$alb[0]['tableId'];
			 foreach($alb as $v){
				 if($albid == $v['tableId']) {
					 $album[$albid][]=$v;
				}else{
					$albid = $v['tableId'];
				    $album[$albid][]=$v;
				}
			 }
			//该用户是否已点赞过
			if($memberId) {
				//已点赞过 
				$cli =M('article_click')->field('article_id')->where("article_id IN ({$ids}) AND member_id='{$memberId}'")->select();
				foreach($cli as $v) $clicks[$v['article_id']]=true;
				//已收藏 
				$coll =M('article_collection')->field('article_id')->where("article_id IN ({$ids}) AND user_id='{$memberId}'")->select();
				foreach($coll as $v) $collection[$v['article_id']]=true;
			}		
			//评论列表
			$reply_arr=[];
			$reply=M('comment_reply')->where("article_id IN ({$ids})")->field('id,user_id,article_id,content,read_user_id,times')->order("article_id ASC,times ASC")->select();
			$uid=in_id($reply,'user_id');
			foreach($reply as $v) $uid.=$v['user_id'].','.$v['read_user_id'].',';
			$uid=rtrim($uid,',');
			if($uid){
				$m=M('member')->field('id,user_name,surname,full_name')->where("id IN ({$uid})")->select();
				foreach($m as $v){
					$user_name=$v['full_name'].$v['surname'];
					$member[$v['id']]=$user_name ? $user_name : $v['user_name'];
				}
			}
			$new_aid=-1;
			foreach($reply as $v){
				$user_name=$member[$v['user_id']] ? $member[$v['user_id']] : $member[$v['read_user_id']];
			    $v['user_name']=$user_name;
				if($new_aid == $v['article_id']){
					$reply_arr[$new_aid][]=$v;
				}else{
					$new_aid = $v['article_id'];
					$reply_arr[$new_aid][]=$v;
				}
			}
			//var_dump($reply);die;
			 //合拼数据
			 foreach($result as $k=>$v){
				 $result[$k]['album']=$album[$v['id']];
				 $result[$k]['is_like']=$clicks[$v['id']]; //是否已赞过
				 $result[$k]['is_colle']=$collection[$v['id']]; //是否已收藏
				 $result[$k]['reply_arr']=$reply_arr[$v['id']];
				 
	             $result[$k]['addtime']=$this->timediff($result[$k]['addtime'],time());
			 }
		}
		//p($result);die;
		$this->result=$result;
	//	die;
		if(IS_AJAX){
		    $this->display('index2_list');
			die;
		}		
	    $this->display();
	}
function timediff($begin_time,$end_time)
{
      if($begin_time < $end_time){
         $starttime = $begin_time;
         $endtime = $end_time;
      }else{
         $starttime = $end_time;
         $endtime = $begin_time;
      }
 
      //计算天数
      $timediff = $endtime-$starttime;
      $days = intval($timediff/86400);
      //计算小时数
      $remain = $timediff%86400;
      $hours = intval($remain/3600);
      //计算分钟数
      $remain = $remain%3600;
      $mins = intval($remain/60);
      //计算秒数
      $secs = $remain%60;
      $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
      return $res;
}
}