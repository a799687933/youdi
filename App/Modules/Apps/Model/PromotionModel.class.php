<?php
class PromotionModel extends Model{

	public function returnList($limit='',$where='',$getAllId='',$order=''){

	  if($order){
	  	$order=' ORDER BY '.$order;
	  }
	  
	  if($where){
		  import('Class.Category',APP_PATH);
		  $id=Category::unlimitedForInId(M('goods')->field('id')->where(pfix('goods_name')." like '%".$where."%'")->select());
		  $amp=" WHERE p.goods_id IN(".$id.") ";
	  }	
	  if($limit){
		   $sql="SELECT p.*,g.".pfix('goods_name')." AS goods_name,g.goods_retail_price FROM  ".PREFIX."goods_promotion AS p LEFT JOIN ".PREFIX."goods AS g";
		   $sql.=" ON(p.goods_id=g.id) ".$amp.$order." LIMIT ".$limit;
		   $result=M()->query($sql);
	   }else{
	   	   if($getAllId){
			   import('Class.Category',APP_PATH);
			   $id=Category::unlimitedForInId(M('goods_promotion')->field('goods_id')->select(),'goods_id');
			   $result=$id;	   	   	   
	   	   }else{
			   $sql="SELECT count(p.id) AS counts FROM  ".PREFIX."goods_promotion AS p LEFT JOIN ".PREFIX."goods AS g";
			   $sql.=" ON(p.goods_id=g.id)  ".$amp." ";	
			   $result=M()->query($sql);
			   $result=$result[0]['counts'];   	
		   }
	   }	
	   return $result;
	}
	
	public function returnUpdae($id){
		$sql.="SELECT p.*,g.id,g.".pfix('goods_name')." AS goods_name,g.goods_retail_price,i.".pfix('name')." AS name FROM ";
		$sql.=PREFIX."goods_promotion AS p LEFT JOIN ".PREFIX."goods AS g ON(p.goods_id=g.id)";
		$sql.="LEFT JOIN ".PREFIX."goods_category AS i ";
		$sql.="ON(p.goods_category_id=i.id) WHERE p.id='".$id."' LIMIT 1";
		$result=M()->query($sql);
		$result[0]['name']='<option value="'.$result[0]['goods_category_id'].'">'.$result[0]['name'].'</option>'; //选定分类
		$result[0]['goods_name']='<option value="'.$result[0]['goods_id'].'">'.$result[0]['goods_name'].'</option>'; //选定商品
		$result[0]['goods_retail_price']='零售价:'.$result[0]['goods_retail_price']; //显示原价
		return $result[0];
	}
}
?>