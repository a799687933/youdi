<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {

    public function index(){ 
    	$this->rundir=RUNTIME_PATH;
    	  //显示用户等级
	      if(isset($_SESSION['grade'])){
		       foreach($_SESSION['grade'] as $k=>$v){
		       	$userGrade.=$v['remark'].',';
		       }
			   $userGrade=rtrim($userGrade,',');
			   $this->userGrade=$userGrade;
		  }

         /* $this->decoration=$this->getCount('decoration', 'addtime','d_isshow'); //装修公司，今天、昨天、近一月的统计、未审核
 	      $this->tender=$this->getCount('service', 'add_time'); //会员发布招标，今天、昨天、近一月的统计
 	      
 	      $ids=D('Curd')->id_set(M('files_sort')->field('files_id')->where(array('virtual_table'=>'knowledge'))->select(),'files_id');
		  $this->article=$this->getCount('article', 'addtime','is_show','files_id',$ids); //站内资讯，今天、昨天、近一月的统计、未审核
		  
 	      $ids=D('Curd')->id_set(M('files_sort')->field('files_id')->where(array('virtual_table'=>'bbs'))->select(),'files_id');
		  $this->bbs=$this->getCount('article', 'addtime','is_show','files_id',$ids); //论坛贴子，今天、昨天、近一月的统计、未审核
		  
		  $this->visit=$this->getCount('web_visit', 'visit_time'); //访问量，今天、昨天、近一月的统计*/
		  
		  //左边导航
		  $navs=M('files_sort')->field('files_id,'.pfix('files_name').' AS files_name,files_type,files_pid,article_type')->where("display=1")->order("files_sort ASC")->select();
		  $this->navs=unlimitedForLayer($navs,'files_pid','files_id','child',0);
		  //自动确认到期订单
		  $comModel=new CommonModel();
		  $comModel->autoConfirmOrder();		  

         $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            '运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION,
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y-n-j- H:i:s"),
            '北京时间'=>gmdate("Y-n-j- H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
           // '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
         $this->assign('info',$info);	 		 		  
		 $this->display();
    }

    //清空缓存
    public function cleHtml(){
    	rmdirs('./'.HTML_PATH); 
		return_json(200,'操作成功！');
    }

    /**
	 * $tableName  表名称
	 * $tableTime   存放表的时间截的字段名
	 * $review     如果有值就是要统计未审核的数据,字段名
	 * $amp  其它查找条件
	 * $ids    其它查找条件IN数据
	 */
	 private function getCount($tableName,$tableTime,$review='',$amp='',$ids=''){
	 	$result=array();
	 	$result['Today']=$this->countData($tableName, $tableTime,time(),$amp,$ids); //当天的数据
	 	$result['Yesterday']=$this->countData($tableName, $tableTime,time()-86400,$amp,$ids); //当天的数据
	 	$result['January']=$this->achieveData($tableName, $tableTime,2592000,$amp,$ids); //夸时段获取最近一月
	 	if($review){
	 		$where[$review]=array('eq',0);			
	 		if($amp && $ids) $where[$amp]=array('in',$ids);
	 		$result['review']=M($tableName)->where($where)->count(); //等审核的
	 	}
	 	return $result;
	 }

   /**
    * 获取当天的统计数据
    * $tableName  表名称
    * $tableTime   存放表的时间截的字段名
    * $strtotime    时间截
    * $amp  表其它条件字段名
    * $ids      表其它条件字段名IN $ids
    */
   private function countData($tableName,$tableTime,$strtotime,$amp='',$ids=''){
           $Today=strtotime(date('Y-m-d',$strtotime));
		   $start=strtotime(date('Y-m-d H:i:s',$Today));
		   $end=strtotime(date('Y-m-d H:i:s',$Today+86400));
           $whereToday[$tableTime]=array(array('EGT',$start),array('ELT',$end),'AND');
		   if($amp && $ids) $whereToday[$amp]=array('in',$ids);
           return M($tableName)->where($whereToday)->count();
   }
   
   /**
    * 夸时段获取统计数据
    * $tableName  表名称
    * $tableTime   存放表的时间截的字段名
    * $strtotime    夸时段的时间截
    * $amp  表其它条件字段名
    * $ids      表其它条件字段名IN $ids
    */
   private function achieveData($tableName,$tableTime,$strtotime,$amp='',$ids=''){
           $Today=strtotime(date('Y-m-d',time()-$strtotime));
		   $start=strtotime(date('Y-m-d H:i:s',$Today));
		   $end=strtotime(date('Y-m-d H:i:s',$Today+$strtotime));
           $whereToday[$tableTime]=array(array('EGT',$start),array('ELT',$end),'AND');
		   if($amp && $ids) $whereToday[$amp]=array('in',$ids);
           return M($tableName)->where($whereToday)->count();
   }   
   	
}