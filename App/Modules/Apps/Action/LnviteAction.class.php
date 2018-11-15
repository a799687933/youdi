<?php
// 本类由系统自动生成，仅供测试用途

class LnviteAction extends CommonAction {
	//邀请码列表
    public function lnviteList(){
	   $relAtions=D('LnviteRelation'); //关联类
       $order=$this->orderBy();//临时排序，返回数组
       $order=$order ? $order : array('id'=>'DESC');	
	   $where=array();
	   if($_REQUEST['str_code']){
		    $strCode=trim(strip_tags($_REQUEST['str_code']));
			$where['str_code']=$strCode;
	   }
	   if($_REQUEST['shops_id'] !=''){
		    $shopsId=trim(strip_tags($_REQUEST['shops_id']));
			$where['shops_id']=$shopsId;
	   }	   
       $counts=$relAtions->where($where)->relation(true)->count();
       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->lnvite=$relAtions->where($where)->relation(true)->limit($GLOBALS['limit'])->order($order)->select();//关联模型
	   $this->shops=M('shops')->field('id,shop_name')->where(array('shops_id'=>0))->order("id ASC")->select();
       $this->display();	   
    }
	
	//添加邀请码
	public function addLnvite(){
		$this->shops=M('shops')->field('id,shop_name')->where(array('shops_id'=>0))->order("id ASC")->select();
		$this->display();
	}
	
	//添加邀请码表单处理
	public function addLnviteForm(){
		if(!is_numeric($_POST['counts'])) return_json(300,'数量输入错误！');
		if(!is_numeric($_POST['shops_id'])) return_json(300,'请选择邀请码类别！');
		$lnvArr=array();
		$lnvite=M();
		$times=time();
		for($i=0;$i < $_POST['counts'];$i++){
			$lnvArr['str_code']=pk_sn(PREFIX.'lnvite',$lnvite,12);
			$lnvArr['add_time']=$times;
			$lnvArr['shops_id']=$_POST['shops_id'];
			$lnvite->execute("INSERT INTO ".PREFIX."lnvite(str_code,add_time,shops_id) VALUES('{$lnvArr['str_code']}',{$lnvArr['add_time']},'{$lnvArr['shops_id']}')");
		}
		return_json(200,'添加成功！','lnviteList','','closeCurrent');
	}
    
    //删除邀请码
    public function delLnvite(){
    	if(M('lnvite')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
    		$url=U(APP_APPS.'/Lnvite/lnviteList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
			return_json(200,'删除成功！','lnviteList','','forward',$url);
    	}else{
    		return_json(300,'删除成功！');
    	}
    }
}