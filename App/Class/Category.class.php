<?php
class Category{

/**
*无限级分类递归方法
* unlimitedForLevel($cate,$id='id',$father='pid',$html='==',$pid=0,$level=0)
* @access public
* @param $id  表字段ID名称字符串形式传值勤（'id'）
* @param $father  表字段父ID名称字符串形式传值勤（'pid'）
* @param $html  select下拉菜单缩进字符串需要时直接取html键值
* @return array
 */	static public function unlimitedForLevel($cate,$id='id',$father='pid',$html='===',$pid=0,$level=0){
		 $arr=array();
		 foreach($cate as $v){
			 if($v[$father]==$pid){
				 $v['level']=$level+1;
				 $v['html']=str_repeat($html,$level);
				 $arr[]=$v;
				 $arr=array_merge($arr,self::unlimitedForLevel($cate,$id,$father,$html,$v[$id],$level+1));
			 }
		 }
	     return $arr;
	}
	
/**
*无限级分类获取下级ID集
* unlimitedForInId($cate)
* @access public
* @param $cate  传结果集
* @param $id  传表ID字段名，默认ID
* @return string
 */		
   static public function unlimitedForInId($cate,$id='id'){
	    foreach($cate as $k=>$v){
	    	if($v){
			    $idAll.=$v[$id].',';
			}		
		}
		$idAll=substr($idAll,0,strlen($idAll)-1);
		return $idAll;
   }
   
/**
*无限级分类返回上一级的所有分类(首页 >> 服装 >> 男装 >> 西裤 >> 七牌西裤)
* unlimitedForInId($cate)
* @access public
* @param $cate  传结果集
* @param $idValue  当前请求ID
* @param $id  传表ID字段名，默认id
* @param $pid  传表父ID字段名，默认pid
* @return string
 */		
   static public function unlimitedForGetParents($cate,$idValue,$id='id',$pid='pid'){
	   $arr=array();
	    foreach($cate as $v){
			if($v[$id]==$idValue){
				$arr[]=$v;
				$arr=array_merge(self::unlimitedForGetParents($cate,$v[$pid],$id,$pid),$arr);
			}
		}
		return $arr;
   }
   
 
 /**
*@无限级分类组合无限下级多维数组
* @unlimitedForInId($cate)
* @access public
* @param $cate  传结果集
* @param $pidString  传表父ID字段名，默认nav_pid
* @param $idString  传表ID字段名，默认nav_id
* @param $childString  下级多维数组键名，默认child
* @return array
 */		  
   static public function unlimitedForLayer($cate,$pidString='nav_pid',$idString='nav_id',$childString='child',$pid=0){
 
     $arr=array();
     foreach($cate as $v){
       if($v[$pidString]==$pid){
          $v[$childString]=self::unlimitedForLayer($cate,$pidString,$idString,$childString,$v[$idString]);
          $arr[]=$v;
       }
     }
     return $arr;
   }
}
?>









