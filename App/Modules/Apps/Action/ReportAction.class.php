<?php
// 本类由系统自动生成，仅供测试用途

class ReportAction extends CommonAction {
	
    //商户注册报表
    public function shopsReport(){
    	$this->_allReport('shops','shop_time',array(),'商户注册量','shopsReport','shopsReportClass');	   
    }

    //会员注册统计报表报表
    public function memberReport(){
    	$this->_allReport('member','reg_time',array(),'会员注册量','memberReport','memberReportClass');	     
    }	

    //订单统计报表报表
    public function orderReport(){
    	$this->_allReport('order_info','add_time',array(),'订单量','orderReport','orderReportClass');	     
    }	
				
    //访问量统计报表报表
    public function vititReport(){
       $this->_allReport('web_visit','visit_time',array(),'访问量','vititReport','vititReportClass');	   	  
    }	
			
	//月报表公共调用
    public function chart(){
    	$fieldArr=str_replace('\\','',$_GET['arrfiled']);
		$fieldArr=unserialize($fieldArr);
		if($fieldArr){
			$whereFiled=$fieldArr;
		}else{
			$whereFiled=array();
		}

       if($_GET['report']){
       	   if(!$_GET['date'] || !$_GET['table'] || !$_GET['field']) return_json(300,'参数错误！'); 
	       $repModel=D('Report');
			   if($_GET['report']=='month'){
			   	  $this->report=$repModel->getMonthReport($_GET['date'],$_GET['table'],$_GET['field'],$whereFiled); //月报报表
			   }else if($_GET['report']=='year'){
			   	  $this->report=$repModel->getYearReport($_GET['date'],$_GET['table'],$_GET['field'],$whereFiled); //年报报表
			   }	
			  $this->orderAmount=$repModel->geTyearToTime($_REQUEST['date']); //处理订单表金额   
			  $this->table=$_GET['table'];	  
	          $this->display();	 
	   }else{
	   	   return_json(300,'参数错误！'); 
	   }	  
    }    
	
	/**
	 * 调用处理
	 * $tableName  表名称不带前缀
	 * $whereFiled  表时间字段名称
     * $whereArr 其它查找条件array('is_show'=>1,'count'=>1,'data'=>1,'logic'=>'AND');
	 * $title 月报表标题
	 * $nodeId  节点ID
	 * $nodeClass  节点Class
	 */
	private function _allReport($tableName,$whereFiled,$whereArr,$title,$nodeId,$nodeClass){
       $this->actionReport($tableName,$whereFiled,$whereArr);
	   $this->title=$title;
	   //动态变更报表DIV ID
	   $this->RaphaelDiv=$nodeId;
	   $this->RaphaelClass=$nodeClass;	   
	   $this->display('Report/report');			
	}	
}