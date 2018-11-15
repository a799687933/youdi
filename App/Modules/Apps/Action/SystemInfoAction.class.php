<?php
// 本类由系统自动生成，仅供测试用途
class SystemInfoAction extends CommonAction {
    //获取系统新消息 
    public function index(){
		 $str='';
		 $notReviewedCount=0; //新注册商家个数
		 $auditCount=0; //新需求个数
		 //新商家注册提醒
		 $mdb=M('member');
		 $member=$mdb->field('id,user_name,user_type')->where(array('new_info'=>0))->select();		 
		 if($member){
			 $mdb->where(array('new_info'=>0))->save(array('new_info'=>1));
			 foreach($member as $k=>$v){
				 if($v['user_type'] > 0){
				 	 $notReviewedCount++;
					 $newInfo='【商家注册】';
				 }else{
					 $newInfo='【会员注册】';
				 }
				 $str.='<li style="line-height:160%;text-align:left !important;" >';
				 $str.='<a onclick="myDialog(this)" href="'.U(APP_APPS.'/Member/memberSave',array('id'=>$v['id'],'action'=>'sellerList')).'" target="dialog" width="1000" height="600" rel="sellerList" mask="true">'.$newInfo.sub_str($v['user_name'],11).'</a>';
				 $str.='</li>';
			 }
         }
		 //新任务提醒
		 $pdb=M('published');
		 $published=$pdb->field('id,title')->where(array('new_info'=>0))->select();
		 if($published){
			 $pdb->where(array('new_info'=>0))->save(array('new_info'=>1));
			 foreach($published as $k=>$v){
			 	 $auditCount++;
				 $str.='<li style="line-height:160%;text-align:left !important;" >';
				 $str.='<a onclick="myDialog(this)" href="'.U(APP_APPS.'/Published/addSave',array('id'=>$v['id'],'action'=>'audited')).'" target="dialog" width="1000" height="600" rel="audited" mask="true" class="auto-size">【最新需求】'.sub_str($v['title'],11).'</a>';
				 $str.='</li>';
			 }
         }		
		 if($str){
			 die(json_encode(array('success'=>1,'data'=>$str,'notReviewedCount'=>$notReviewedCount,'auditCount'=>$auditCount))); 
		 }else{
			 die(json_encode(array('success'=>0,'data'=>$str,'notReviewedCount'=>$notReviewedCount,'auditCount'=>$auditCount))); 
		 }		 
    }

}