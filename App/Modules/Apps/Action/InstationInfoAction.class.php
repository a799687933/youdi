<?php
// 推送消息控制器 
class InstationInfoAction extends CommonAction {
    
	//发信息列表
	public function sendList(){
	   $ins=M('instation_supervise');	
       $order=$this->orderBy();//临时排序
       $order=$order ? $order : 'i.id DESC';    
	   if($_REQUEST['title']) {
	   	    $countWhere=pfix('title')." LIKE '%{$_REQUEST['title']}%'";	
		   $listWhere=" AND i.".pfix('title')." LIKE '%{$_REQUEST['title']}%' ";
	   }
       $counts=$ins->where($countWhere)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页  
	   $sql="SELECT i.*,m.user_name AS add_name FROM ".PREFIX."instation_supervise AS i LEFT JOIN ".PREFIX."member AS m ";
	   $sql.="ON(i.receive_user_id=m.id) WHERE 1=1 {$listWhere} ORDER BY {$order} LIMIT ".$GLOBALS['limit'];
	   $this->instation=$ins->query($sql);  
	   $this->display();
	}
	
	//发信息界面
    public function sendInfo(){
	   $this->uid=$_GET['id'];
	   $this->type=$_GET['type'];
	   $this->action=$_GET['action'];
	   if($_GET['ins_id']){
		   $this->save=M('instation_supervise')->where(array('id'=>$_GET['ins_id']))->find();
	   }
	   $this->titles=$this->langIsMore('instation_supervise','title'); //标题
	   $this->contents=$this->langIsMore('instation_supervise','content'); //内容
       $this->display();
	}
	
	//发信息表单处理
	public function sendInfoForm(){
		$times=time();
		$action=$_REQUEST['action'];
		$_POST['user_name']=$_SESSION[C('SESSION_NAME_VAL')];		
		$_POST['add_time']=$times;		
		//启动事务
		$db=M();
		$db->startTrans(); 
		$states=true;
		if($_REQUEST['user_id']){ //向一个会员发消息
			if(M('member')->field('id')->where(array('id'=>$_REQUEST['user_id']))->find()){
				   $_POST['receive_user_id']=$_REQUEST['user_id'];	
				    //重组POST数据	
				   $this->requestArray('instation_supervise');
				   if($id=M('instation_supervise')->add($_POST)){
				   	   //后台推送
				   	   $_POST['info_type']=1; 
				   	   $_POST['instation_supervise_id']=	$id;
					   //重组POST数据
				   	   $this->requestArray('instation_info'); 
				   	   if(!M('instation_info')->add($_POST)) $states=false;
				   }else{
				   	   $states=false;
				   }
			}else{
				  $states=false;
			}   
		}else{
           	if($id=M('instation_supervise')->add($_POST)){
           		$member=M('member')->field('id')->select();
				$insert=array();
				$i=0;
				$titleMoer=langAllField('title');
				$content=langAllField('content');
				foreach($member as $k=>$v){
					$insert[$i]['info_type']=1;
					$insert[$i]['send_user_id']=0;
					$insert[$i]['receive_user_id']=$v['id'];
					$insert[$i]['instation_supervise_id']=$id;
					$insert[$i]['add_time']=$times;
					foreach($titleMoer['lang_arr'] as $k1=>$v1){						
						$insert[$i][$v1]=$_POST[$v1];
					}
					foreach($content['lang_arr'] as $k2=>$v2){						
						$insert[$i][$v2]=$_POST[$v2];
					}	
					$i++;				
				}
                if(!M('instation_info')->addAll($insert)) $states=false;
           	}else{
           		$states=false;
           	}	
		}
		if($states){
			//成功则提交
			$db->commit();
			return_json(200,'发送成功！',$action,'','closeCurrent','');		
		}else{
			//不成功，则回滚
			$db->rollback();
			return_json(300,'发送失败');
		}
	}
	                   
	//删除推送消息记录
	public function infoDelete(){
	   $url=U(APP_APPS.'/InstationInfo/sendList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
	   if(M('instation_supervise')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
		   if(M('instation_info')->where(array('instation_supervise_id'=>array('in',$_REQUEST['ids'])))->delete()){
			   return_json(200,'操作成功！','sendList','','forward',$url);																								   
		   }else{
			   return_json(300,'删除失败');
		   }
	   }
	}
	
}