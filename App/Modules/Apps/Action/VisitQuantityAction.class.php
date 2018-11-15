<?php
// 本类由系统自动生成，仅供测试用途

class VisitQuantityAction extends CommonAction {
		
	//正常访问列表
    public function visitList(){
        $this->visit(0);
		$this->display();
    }
    
	//通过搜索引擎点击过来的访问列表
    public function toVisitList(){
       $this->visit(1);
	   $this->display();
    }

    private function visit($spider){
    	$curd=D('Curd');
		$model=M('web_visit');
    	$amp=$curd->search('city');//询查条件,返回数组
        $order=$this->orderBy();//临时排序，返回数组
        if($_REQUEST['dates']){
        	$dates=sqlTime($_REQUEST['dates']);
			$amp['searchArr']['visit_time']=array(array('EGT',$dates['startTime']),array('ELT',$dates['endTime']),'AND');
        }
		if($spider==0){
	        $amp['searchArr']['engine']=array('eq',''); 
			$amp['searchArr']['engine_key']=array('eq',''); 						
		}else if($spider==1){
	        $amp['searchArr']['engine']=array('neq',''); 
			$amp['searchArr']['engine_key']=array('neq',''); 			
		}
	    $order=$order ? $order : array('visit_time'=>'DESC');
        $counts=$model->where($amp['searchArr'])->count();
        $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页		
    	$this->visits=$model->where($amp['searchArr'])->order($order)->limit($GLOBALS['limit'])->select();
		//$this->City=$curd->citySelect(1);
		$this->searchVal=$_REQUEST['dates']; //保存输入的搜索关键字
		$this->url=$amp['url'].'/dates/'.$_REQUEST['dates']; //保存查询条件
    }
    
	//删除访问记录
    public function visitDelete(){
    	$type= $_REQUEST['type']==0 ? 'visitList': 'toVisitList';
    	$url=U(APP_APPS.'/VisitQuantity/'.$type,array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		if(M('web_visit')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
			return_json(200,'操作成功！',$type,'','forward',$url);
		}else{
			return_json(300,'操作失败！');
		}
    }
	
	//搜索爬取记录列表
	public function spiderGetWeb(){
		$amp=D('Curd')->search('');//询查条件,返回数组
		$model=M('spider');
		if($_REQUEST['dates']){
        	$dates=sqlTime($_REQUEST['dates']);
			$amp['searchArr']['visit_time']=array(array('EGT',$dates['startTime']),array('ELT',$dates['endTime']),'AND');
        }
		$order=$this->orderBy();//临时排序，返回数组
	    $order=$order ? $order : array('visit_time'=>'DESC');
        $counts=$model->where($amp['searchArr'])->count();
        $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页			
		$this->visits=$model->where($amp['searchArr'])->order($order)->limit($GLOBALS['limit'])->select();
		$this->searchVal=$_REQUEST['dates']; //保存输入的搜索关键字
		$this->url=$amp['url'].'/dates/'.$_REQUEST['dates']; //保存查询条件		
		$this->display();
	}
	
	//删除搜索爬取记录列表
	public function delSpiderGetWeb(){
		$url=U(APP_APPS.'/VisitQuantity/spiderGetWeb',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		if(M('spider')->where(array('id'=>array('in',$_REQUEST['ids'])))->delete()){
			return_json(200,'操作成功！','spiderGetWeb','','forward',$url);
		}else{
			return_json(300,'操作失败！');
		}
	}

}