<?php

class PromotionAction extends CommonAction {
	
	//限时抢购列表
    public function promotionList(){
	   $order=$this->orderBy();//临时排序，返回数组
	   $model=D('Promotion');
	   $this->callAjaxPage(C('BGPAGENUM'),$model->returnList('',$_REQUEST['goods_name']));//分页
	   $this->promot=$model->returnList($GLOBALS['limit'],$_REQUEST['goods_name'],'',$order);
	   $this->searchVal=$_REQUEST['goods_name'];
	   if(isset($_REQUEST['goods_name'])) $name='/goods_name/'.$_REQUEST['goods_name'];
       $this->url=__ACTION__.$name;
       $this->display();
    }
	
   //添加/修改限时抢购商品界面
   public function promotionAddSave(){
   	  $curd=D('Promotion');
   	  $this->ids=$curd->returnList('','',1);
	  $this->goodCat=D('Curd')->cage_select('goods_category','id,name,pid','','reorder asc');//商品分类
	  if($_GET['send']=='update'){
		  $this->result=$curd->returnUpdae($_GET['id']);
	  }
	  $this->explains=$this->langIsMore('goods_promotion','explain');
   	  $this->display();
   }
   
   //添加/修改限时抢购商品表单处理
   public function promotionAddSaveFrom(){
   		$start=sqlTime($_POST['promotion_start_date']);
		$_POST['promotion_start_date']=$start['startTime']; //促销活动开始时间
		$end=sqlTime($_POST['promotion_end_date']);
		$_POST['promotion_end_date']=$end['endTime']; //促销活动结束时间
	   	if($_POST['send']=='add'){
	   		D('Curd')->insert('goods_promotion','promotionList');
	   	}else if($_POST['send']=='update'){
	   		D('Curd')->modify('goods_promotion','promotionList');
	   	}	
   }
   
   //删除限时抢购商品
   public function promotionDelete(){
   	 $url=U(APP_APPS.'/Promotion/promotionList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
	 D('Curd')->del('goods_promotion','promotionList',$url);
   }
   
   //ajax返回商品列表
   public function ajaxGoodsSelect(){
   	  $where['path']=array('like',"%-".$_GET['id']."-%");
	  $where['_logic']="OR";
	  $where['id']=$_GET['id'];
      import('Class.Category',APP_PATH);
      $id=Category::unlimitedForInId(M('goods_category')->field('id')->where($where)->select());	  

	  $reslut=M('goods')->field('id,goods_name,goods_retail_price')->where(array('goods_category_id'=>array('IN',$id)))->select();
	  if($reslut) {
	  	  $idArr=explode(',',$_GET['ids']);
	  	  $str='<option value="" price="">请选商品</option>';
		  foreach($reslut as $k=>$v){
		  	if(!in_array($v['id'],$idArr)){
		  	    $str.='<option value="'.$v['id'].'" price="'.$v['goods_retail_price'].'">'.$v['goods_name'].'</option>';
			}	
		  }
	  }
      if($str){
      	echo($str);
      }else{
      	echo('<option value="" price="">你所选择的分类暂无商品？</option>');
      }
	  
   }
   
}