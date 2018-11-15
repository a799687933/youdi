<?php
/*招聘控制器*/
class HiringAction extends CommonAction{
    
    //招聘列表
    public function hiringList(){
	    $order=$this->orderBy('h');//临时排序，返回数组
	    $order=$order ? $order : 'h.id DESC';			
		$where=" WHERE 1=1 ";
		if($_REQUEST['hiring_name']){
			$hiringName=strip_tags(trim($_REQUEST['hiring_name']));
		    $this->hiring_name=$hiringName;
			$where.=" AND h.hiring_name LIKE '%{$hiringName}%' ";
		}
		if($_REQUEST['shops_id']){
			$shopsId=strip_tags(trim($_REQUEST['shops_id']));
			$this->shops_id=$shopsId;
			$where.=" AND h.shops_id ='{$shopsId}' ";
		}
		$db=M();
		$countSql="SELECT count(h.id) AS counts FROM ".PREFIX."hiring AS h JOIN ".PREFIX."region AS r ON(r.region_id=h.city) ";
		$countSql.="JOIN ".PREFIX."files_sort AS f ON(f.files_id=h.files_id) JOIN ".PREFIX."shops AS s ON(s.id=h.shops_id) ".$where;
		$counts=$db->query($countSql);
		$this->callAjaxPage(C('BGPAGENUM'),$counts[0]['counts']);//分页
		$field="h.id,h.hiring_name,h.describe,h.display,h.is_recommend,h.add_time,r.region_name,f.files_name,s.shop_name";
		$sql="SELECT {$field} FROM ".PREFIX."hiring AS h JOIN ".PREFIX."region AS r ON(r.region_id=h.city) ";
		$sql.="JOIN ".PREFIX."shops AS s ON(s.id=h.shops_id) ";
		$sql.="JOIN ".PREFIX."files_sort AS f ON(f.files_id=h.files_id) {$where} ORDER BY {$order} LIMIT ".$GLOBALS['limit'];
		$result=$db->query($sql);
		$this->hiring=$result;
        $this->display();

    }
    
    //添加/修改界面
    public function hiringAddSave(){
		$model=new CommonModel();
		if($_GET['id']){
			$hiring=M('hiring')->where(array('id'=>$_GET['id']))->find();
			$this->hiring=$hiring;
		}
		//国家、省、城市、区县   
		$this->region=$model->getRegion($hiring['country'],$hiring['province'],$hiring['city'],$hiring['district']);   
		//职位分类
		$this->filesSort=M('files_sort')->field('files_id,files_name')->where(array('files_type'=>5,'files_pid'=>array('gt',0),'display'=>1))->order("files_sort ASC")->select();
        $this->display();
    }
    
    //添加/修改表单处理
    public function hiringForm(){
		//检索下级城市
		if($_GET['city']=='ajax'){
			$model=new CommonModel();
			return $model->searchRegion();			
		    die();
		}  
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}	
		$_POST['country']=$_POST['country'] ? $_POST['country'] : json_return(300,'请选择国家');
		$_POST['province']=$_POST['province'] ? $_POST['province'] : json_return(300,'请选择省份');
		$_POST['city']=$_POST['city'] ? $_POST['city'] : json_return(300,'请选择城市');
		$_POST['district']=$_POST['district'] ? $_POST['district'] : json_return(300,'请选择区/县');
		$_POST['address']=$_POST['address'] ? trim(strip_tags($_POST['address'])) : json_return(300,'详细街道不可空');
		$_POST['files_id']=$_POST['files_id'] ? $_POST['files_id'] : json_return(300,'请选择招聘分类');
		$_POST['hiring_name']=$_POST['hiring_name'] ? trim(strip_tags($_POST['hiring_name'])) : json_return(300,'职位名称不可空');
		$_POST['keyword']=$_POST['keyword'] ? trim(strip_tags($_POST['keyword'])) : '';
		$_POST['describe']=$_POST['describe'] ? trim(strip_tags($_POST['describe'])) : '';
		$_POST['content']=$_POST['content'] ? $_POST['content'] : json_return(300,'职位内容不可空');
		if($_POST['id']){ //修改
		   D('Curd')->modify('hiring','hiringList','','id','','content');
		}else{//新增
		   D('Curd')->insert('hiring','hiringList','','content');
		}
    }
    

    
    //删除招聘
    public function hiringDelete(){
		$url=U(APP_APPS.'/Hiring/hiringList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
        D('Curd')->del('hiring','hiringList',$url,'ids','id');
    }
}
?>