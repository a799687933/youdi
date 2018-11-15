<?php
// 本类由系统自动生成，仅供测试用途

class AdAction extends CommonAction {
	//广告类型列表
    public function index(){
       $curd=D('Curd');
	   $relation=D('AdRelation');
       $amp=$curd->search('ad_name','city_id');//询查条件,返回数组
	   $amp['searchArr']['ad_pid']=0;
       $counts=$relation->relation(true)->where($amp['searchArr'])->count();
       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->ad_list=$relation->relation(true)->where($amp['searchArr'])->limit($GLOBALS['limit'])->order("ad_order ASC")->select();//关联模型
       $cate=M('region')->where("region_type < 3 AND is_show=1")->order("orders ASC")->select();
       import('Class.Category',APP_PATH);//无限级分类类
       $this->City=Category::unlimitedForLevel($cate,'region_id','parent_id');	   
	   $this->searchVal=$amp['keyword']; //保存输入的搜索关键字
	   $this->url=$amp['url']; //保存查询条件
       $this->display();	   
    }
    
	//添加广告类型
	public function adClassAdd(){
		$this->City=D('Curd')->citySelect(1); //选择城市
		if($_GET['id']) $this->save=M('ad')->where(array('ad_id'=>$_GET['id']))->find();
		$this->adName=$this->langIsMore('ad','ad_name'); //广告类型多语言
		$this->display();
	}
	
	//添加广告类型表单处理
	public function adClassAddForm(){	
		$db=M('ad');
	   // if($_POST['city_id']=='0') return_json(300,'你还没有选择城市？');
	    $_POST['ad_time']=time();
		$where['city_id']=$_POST['city_id'];
		if($_POST['ad_id']){
			D('Curd')->modify('ad','index','','ad_id');//修改
		}else{
			$this->requestArray('ad');
		    D('Curd')->insert('ad','index');//插入
		}
	}
	
	//修改广告类型
	public function adClassSave(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}
	}
	
	//删除广告类型
	public function adClassDelete(){

	   if(!!$result=M('ad')->field("ad_pid")->where(array('ad_pid'=>$_GET['ids']))->select()) return_json(300,'本广告类型已实现二级广告列表，请先删除二级列表广告！');
	   $url=U(APP_APPS.'/Ad/index',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
	   D('Curd')->del('ad','index',$url,'ids','ad_id');
	}
	
	//广告类型二级列表
	public function adList(){
	   $position=M('ad')->field("ad_id,city_id")->where(array('ad_id'=>$_GET['ad_id']))->find();
	   $this->List=M('ad')->where(array('ad_pid'=>$_GET['ad_id']))->order("ad_order ASC")->select();
	    $this->ad_id=$_GET['ad_id'];
		$this->city_id=$position['city_id'];
		$this->display();
	}
	
	//二级列表添加广告
	public function adAdd(){
	    $this->widthHwidth=M('ad')->field('ad_width,ad_height')->find($_GET['ad_id']);
	    $this->ad_pid=$_GET['ad_id'];
	   	$this->adTitle=$this->langIsMore('ad','ad_title'); //广告标题多语言
		$this->bewrite=$this->langIsMore('ad','ad_class_bewrite'); //广告描述多语言
		$this->display();
	}
	
	//二级列表添加广告表单处理
	public function adAddForm(){
	   
	   if($_POST['ad_img']=='') return_json(300,'请先上传广告图');
	   $_POST['ad_time']=time();
	   $this->chkTokenAjax(); //验证令牌是否正确，是否是AJAX提交	
	   $this->requestArray('ad');
	   D('Curd')->insert('ad','adList',array($_POST['ad_img']));//插入
	}
	
	//修改广告界面
	public function adUpdate(){
		 $this->adsave=M('ad')->find($_GET['ad_id']);
	   	 $this->adTitle=$this->langIsMore('ad','ad_title'); //广告标题多语言
		 $this->bewrite=$this->langIsMore('ad','ad_class_bewrite'); //广告描述多语言		
		 $this->display();
    }	
    
	//修改广告表单处理
	public function adUpdateForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}else{
	        D('Curd')->modify('ad','adList','','ad_id',array($_POST['ad_img'])); //修改数据			
		}

	}
	
	//删除广告图
	public function adDelete(){
	   $url=U(APP_APPS.'/Ad/adList',array('ad_id'=>$_GET['ad_pid']));
	   D('Curd')->del('ad','adList',$url,'ids','ad_id');//删除		  
	}
	
}