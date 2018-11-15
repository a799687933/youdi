<?php
// 本类由系统自动生成，仅供测试用途
class CommonAction extends Action {

    //优先执行
	public function _initialize(){
	    //是否登录
        if(!isset($_SESSION[C('USER_AUTH_KEY')]) || 
		!isset($_SESSION[C('SESSION_NAME_VAL')]) || !isset($_SESSION[C('SESSION_TIME_VAL')]) ||  
		!isset($_SESSION[C('SESSION_LOGINIP_VAL')])){
            if(IS_AJAX){
               return_json(301,L('会话超时，请登录！')); 
            }else{
              $this->redirect(APP_APPS.'/AppLogin/index');  
            }           
        }
		
		//除首页以外，其它页面不得直接访问
		if(!strpos(strtoupper(__ACTION__),'INDEX/INDEX')){
			//nonlicet_url();
		}	
		
		//验证权限
		if(C('USER_AUTH_ON')){
			import('ORG.Util.RBAC');	 
			RBAC::AccessDecision(APP_APPS) || $this->error('你没有权限！');//如果是独立分组要加GROUP_NAME参数
			//var_dump(RBAC::AccessDecision(APP_APPS));
			//p($_SESSION);
		}	
		//后台强制中文显示
		setcookie('think_language','zh-cn',0,'/'); 
		
		//if($_POST && !isset($_POST['ids']) && !isset($_GET['dwz'])) $this->chkTokenAjax(); //验证令牌
	}		

    //DWZ Ajax分页
    public function callAjaxPage($pageSize=20,$counts){
    	
        import('Class.Page',APP_PATH);
		if($_REQUEST[C('VAR_PAGE')]){
		   $_GET[C('VAR_PAGE')]= $_REQUEST[C('VAR_PAGE')];	
		}else{
		   $_GET[C('VAR_PAGE')]= $_REQUEST['pageNum'];	
		}		
        $page=new Page($counts,$pageSize);				
		if($_GET[C('VAR_PAGE')]>=$page->totalPages) $_GET[C('VAR_PAGE')]=$page->totalPages;		
        $GLOBALS['limit']=$page->firstRow . ',' . $page->listRows;//组合limti条件
        $this->totalCount=$page->totalRows;//总行数
        $this->pageNumShown=$page->totalPages;//总页数
        $this->numPerPage=$pageSize;//每页显示多少条
        $this->currentPage=$page->nowPage;//当前页
    }	
	
    //公共修改数据库一个字段Ajax
    //三个参数:/tableName/传表名称/id/传ID/字段名/传字段名
    //示例:tableName=user&id=12&name=黎明  
    protected function saveOen(){
      $demo=M($_GET['tableName']);
	  
      if($demo->save($_GET)){
          echo 1;
          exit(1);
       }else{
          echo 0;
          exit(0);
       }       
    }  
    
    //修改数据库一个字段,只修改成0或1
    //收到的$_GET参数,第一个表名称，第二个主键，第三个修改的值
    protected function saveSwitch(){
      $arra=$_GET;
	  unset($arra['_URL_']);	       
      foreach($arra as $v){
          $returnInfo=$v;//获取返回信息,第三个修改的值0/1
      }
      nonlicet_url();//URL检测
      $demo=M($arra['tableName']);
      if($demo->save($arra)){
           echo $returnInfo;
           exit();
       }else{
           echo $returnInfo;
          exit();
       }
    } 	

	//判断是否AJAX提交和判令牌是否正确
	protected function chkTokenAjax(){
		//if(!IS_AJAX) return_json(300,'Error!');
		if(C('TOKEN_ON')){
			$CheckToken=M();
	        if(!$CheckToken->autoCheckToken($_POST)) return_json(300,'Error!'); //令牌  	
		}
	}
	
	//公共临时按字段进行数据排序
	//return array
	protected function orderBy($as=''){		
	   if($as) $tableAs=$as.'.';
       if(isset($_POST['orderFieldGet']) && preg_match('/\$/', $_POST['orderField'])){
 			$orderFieldGet=$_POST['orderFieldGet'];
			if($orderFieldGet){
			    $orderBy=$tableAs.$orderFieldGet.' '.$_POST['orderGet']; //排序字符串形式   
			}
			if($_POST['orderGet']=='ASC'){ //保存已选择的排序方式 ASC 或 DESC
				$this->orderGet='ASC';
				$this->$orderFieldGet='asc';
			} else{
				$this->orderGet='DESC';
				$this->$orderFieldGet='desc';
			} 
        }elseif(isset($_POST['orderField'])){
        	if(!preg_match('/\$/', $_POST['orderField'])){
        		 $orderFieldGet=$_POST['orderField'];
        		 $_REQUEST[C('VAR_PAGE')]= $_REQUEST['orderPage'];	//保存排序当前页
		       	 $orderField=$_POST['orderField'];
				 $order=$_POST['order'];  
				 if($order=='ASC'){
				 	$this->order='DESC';
					 $cla='asc';
				 }else{
				 	$this->order='ASC';
					$cla='desc';
				 }
				 $orderBy=$tableAs.$orderField.' '.$order; //排序字符串形式
			}
			$this->orderGet=$_POST['order'];
			$this->$_REQUEST['orderField']=$cla; //小箭头图片付值
       }
	   $this->	orderFieldGet=$orderFieldGet; //排序数据库字段名称
	  return 	$orderBy;	
	}	
	

  /**
   * 报表公共调用
   * $tableName 表名称
   * $whereFiled  时间字段
   * $whereArr 其它查找条件array('is_show'=>1,'count'=>1,'data'=>1,'logic'=>'AND');
   */
  protected function actionReport($tableName,$whereFiled,$whereArr=array()){
      $repModel=D('Report');
       if($_REQUEST['report']){
       	   if(!$_REQUEST['date'] || !$_REQUEST['table'] || !$_REQUEST['field']) return_json(300,'参数错误1！'); 	       
			   if($_REQUEST['report']=='month'){
			   	  $this->report=$repModel->getMonthReport($_REQUEST['date'],$_REQUEST['table'],$_REQUEST['field'],$whereArr); //月报报表
			   }else if($_REQUEST['report']=='year'){
			   	  $this->report=$repModel->getYearReport($_REQUEST['date'],$_REQUEST['table'],$_REQUEST['field'],$whereArr); //年报报表
			   }	 
	   }else{
	   	   $this->report=$repModel->getYearReport(date('Y',time()),$tableName,$whereFiled,$whereArr); //年报报表
		   $this->orderAmount=$repModel->geTyearToTime(date('Y',time())); //处理订单金额
	   }	
	   //动态调用名表和条件
	   $this->table=$tableName;
	   if($whereArr) $this->arrFiled=serialize($whereArr);
	   $this->filed=$whereFiled;
	   	   
	   if($_POST['report']){
	   	   $this->data=$_POST['date'];
	   }else{
	   	   $this->data=date('Y',time());
	   }  	
  }	

	/**
	 * 是否使用多语言(表字段命名规则 cn_name,us_name)
	 * $table_name  表名称
	 * $field               某字段是否多语言(titletext) 
	 * langIsMore('article','titletext');
	 *return array();
	**/
	public function langIsMore($table_name,$field){
	    $sql="select COLUMN_NAME from information_schema.COLUMNS where table_name = '".PREFIX.$table_name."' and table_schema ='".C('DB_NAME')."'";		
		$res=M()->query($sql);
		//如果开启多语言
	    if(C('LANG_SWITCH_ON')){
			$langArr=C('LANG_LIST');
		}else{ //默认语言
			$langArr=array(C('DEFAULT_LANG'));
		}
		$langInfoArr=C('LANG_LIST_INFO');
		$returnArr=array();
		foreach($langArr as $lk=>$lv){
			$la=explode('-', $lv);
			foreach($res as $k=>$v){
				if(strtoupper($la[1]).'_'.strtoupper($field)==strtoupper($v['COLUMN_NAME'])){
					$returnArr[$lk]['info']=$langInfoArr[$lk];
					$returnArr[$lk]['field']=$v['COLUMN_NAME'];
					break;
				}
			}			
		}	
		return $returnArr;	
	}
  
  /**
   * 重组REQUEST数组与数据表一致
   * */	
   public function requestArray($tableName){
		$res=M()->query("SHOW FULL COLUMNS FROM ".PREFIX.$tableName."");
		foreach($res as $k=>$v){
			  if(strtoupper($v['Key']) !==strtoupper('PRI')){
				  	  if((strpos(strtoupper($v['Type']),'INT') !==false) || 
				  	      (strpos(strtoupper($v['Type']),'DECIMAL') !==false) || 
				  	      (strpos(strtoupper($v['Type']),'TINYINT') !==false) || 
				  	      (strpos(strtoupper($v['Type']),'MEDIUMINT') !==false) || 
				  	      (strpos(strtoupper($v['Type']),'BIGINT') !==false)){
				  	      	$_POST[$v['Field']]=$_POST[$v['Field']] ? trim($_POST[$v['Field']]) : 0;			  	  	
				  	  }else if(
				  	     (strpos(strtoupper($v['Type']),'CHAR') !==false) || 
				  	     (strpos(strtoupper($v['Type']),'BINARY') !==false) || 
				  	     (strpos(strtoupper($v['Type']),'BLOB') !==false) || 
				  	     (strpos(strtoupper($v['Type']),'TEXT') !==false)){
				  	      if(!is_array($_POST[$v['Field']])){
				  	      	 $_POST[$v['Field']]=$_POST[$v['Field']] ? trim($_POST[$v['Field']]) : ' ';		
				  	      }					  	  	  
				  	  }
			  }
		} 
   }	
  
}