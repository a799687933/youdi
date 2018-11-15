<?php
// 本类由系统自动生成，仅供测试用途

class SearchAction extends Action {
	//检索(能检索所有表，最多获取两个字段)
    public function article(){
      if($_GET['Keywords']) $where[$_GET['fieName']]=array('LIKE',"%{$_GET['Keywords']}%");
	  if($_GET['catIdVal'] && $_GET['catIdName']) $where[$_GET['catIdName']]=array('eq',$_GET['catIdVal']);
	  $result=M($_GET['tableName'])->field($_GET['fieId'].','.pfix($_GET['fieName']).' AS '.$_GET['fieName'])->where($where)->select();
	  die(json_encode($result));
    }
 
 	//关联商和关联配件检索
	public function retrieval(){
		$where=array();
		if($_GET['goods_category_id']){
			$where['goods_category_id']=$_GET['goods_category_id'];
		}
		if($_GET['goods_brand_id']){
			$where['goods_brand_id']=$_GET['goods_brand_id'];
		}		
		if($_GET['goods_name']){
			$where[pfix('goods_name')]=array('LIKE',"%{$_GET['goods_name']}%");
		}		
		//$where['is_general']=$_GET['is_general'];		
		$where['recycle_bin']=0;
		$where['shelves']=1;
		$where['stock']=array('gt',0);
		$result=M('goods')->field(array('id',pfix('goods_name').' AS goods_name'))->where($where)->select();
		die(json_encode($result));
	}
	
	//ajax查找城市区域
	public function getCityName(){
		$result=M('city')->field('city_id,city_name')->where(array('city_pid'=>$_GET['id']))->select();
		if($result){
			echo json_encode($result);
		}else{
			die('error');
		}
	}	 
	
}