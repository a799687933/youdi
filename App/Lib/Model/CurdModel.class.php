<?php
class CurdModel extends Model{

	/**
	 * insert  添加
	 * @$tableName string  要新增的表名
	 * @$action  要重新加载的dialog 名称
	 * @$imgArray 其它图片数组，如缩略图等
	 * @$ditorContent 编辑器变量；多个编著辑器内容使用数组
	 * @$related  相关相册图片数组
	 * @$bewrite  相关相册文字描述数组
	 */
	public function insert($tableName,$action,$imgArray='',$ditorContent='',$related='imgAll',$bewrite='title'){	
	    $ditorText='';
	    if(is_array($ditorContent)){
		    foreach($ditorContent as $v) $ditorText.=$v;
		    $ditorText=stripcslashes($ditorText);
		}else{
		    $ditorText=stripcslashes($_POST[$ditorContent]);
		}
		if($id=M($tableName)->add($_POST)){
			  //处理图片
			 if($imgArray || $_POST[$related] || $_POST[$bewrite] || $ditorText){
			   import('Class.DisposeImg',APP_PATH);
			   DisposeImg::addPictures($tableName,$id,$imgArray,$_POST[$related],$_POST[$bewrite],$ditorText);
			 } 
			 return_json(200,'操作成功！',$action,'','closeCurrent');
		}else{
			return_json(300,'操作失败！');
		}	
	 }
	
	
	 /**
	 * modify 修改
	 * @$tableName string  要修改的表名
	 * @$action  要重新加载的方法名称
	 * @$url  要跳转的URL
	 * @$id string  当前的主键名称
	 * @$imgArray 其它图片数组，如缩略图等
	 * @$ditorContent 编辑器变量；多个编著辑器内容使用数组
	 * @$related  相关相册图片数组
	 * @$bewrite  相关相册文字描述数组
	 */
	 public function modify($tableName,$action,$url,$id='id',$imgArray='',$ditorContent='',$related='imgAll',$bewrite='title'){
	    $ditorText='';
	    if(is_array($ditorContent)){
		    foreach($ditorContent as $v) $ditorText.=$v;
		    $ditorText=stripcslashes($ditorText);
		}else{
		    $ditorText=stripcslashes($_POST[$ditorContent]);
		}	 
		if(M($tableName)->save($_POST)){
		     //处理图片
		    if($imgArray || $_POST[$related] || $_POST[$bewrite] || $ditorText){
			    import('Class.DisposeImg',APP_PATH);
				DisposeImg::updatePictures($tableName,$_POST[$id],$imgArray,$_POST[$related],$_POST[$bewrite],$ditorText);
			}	
			if($url){
				return_json(200,'操作成功！',$action,'','forward',$url);
			}else{
				return_json(200,'操作成功！',$action,'','closeCurrent');
			}				
		}else{
			 //处理图片
			if($imgArray || $_POST[$related] || $_POST[$bewrite] || $ditorText){ 
			  import('Class.DisposeImg',APP_PATH);			
			  DisposeImg::updatePictures($tableName,$_POST[$id],$imgArray,$_POST[$related],$_POST[$bewrite],$ditorText);
					
			  if($url){
				return_json(200,'操作成功！',$action,'','forward',$url);
			  }else{
				return_json(200,'操作成功！',$action,'','closeCurrent');
			  }
			}else{
				return_json(300,'操作失败！');
			}				
		}
	 }
	
	 /**
	 * del 删除或批量删除
	 * @$tableName string  要删除数据的表名
	 * @$action string  要重新加载的dialog 名称
	 * @$action string  重载的真正URL
	 * @$id int  要删除数据条件字段ID，默认ID字段
	 * @$ids string  传过来的ID集或一个ID，默认ids字段
	 */
	 public function del($tableName,$action,$url,$ids='ids',$id='id'){
	 		
		$where[$id]=array('in',$_REQUEST[$ids]);
		if(M($tableName)->where($where)->delete()){
			$this->del_img($_REQUEST[$ids],$tableName);
			return_json(200,'操作成功！',$action,'','forward',$url);
		}else{
			return_json(300,'操作失败！');
		}
	 } 
	 
	/**
	 * 批删除图片
	 * $data string ID集合(1,2,3,4)
	 * $tablename string 表名称，不带前缀
	 */
	 public function del_img($data,$tablename){
	    import('Class.DisposeImg',APP_PATH);
		$imgId=explode(',', $data);
		foreach($imgId as $v){
		  DisposeImg::delPictures($v,$tablename);	
		} 	
	 }
	 
	 /**
	  * 无限级分类， select下拉框
	  * 
	  */
	 function cage_select($tabName='category',$feild='id,name,en_name,pid',$where='',$order='reorder asc',$id='id',$pid='pid'){
	 	   if(!$where){
	 	     //	$where['virtual_table']=array('in','default,systemHelp');		
	 	   }	
			$belongs=M($tabName)->field($feild)->where($where)->order($order)->select();
		    import('Class.Category',APP_PATH);//无限级分类类
		    return Category::unlimitedForLevel($belongs,$id,$pid);
	 }
	 
	 /**
	  * id_set 获取ID集合
	  * $arr  array   数据表结果集
	  * $id   int       表ID字段名，默认ID
	  */
	  function id_set($arr,$id='id'){
	  	  import('Class.Category',APP_PATH);
		  return Category::unlimitedForInId($arr,$id);
	  }

	/**
	 * 生成查询条件，适合:输入内容搜索,按城市ID搜索,按分类ID搜索,或其它的主键ID
	 * searchContent  stirng 输入内容
	 * city_id  int   选择的城市ID
	 * Category  int 选择的分类ID
	 * return  array  searchArr  获得数据查询条件数组,url 获得搜索条件的URL ,keyword保存输入框的关键字
	 * 用法：
	 * $amp=search('link_name'); //搜索
	 * M('links')->where($amp['searchArr'])->select();
	 * 	$this->url=$amp['url']; 保存分页URL
	 *	$this->searchVal=$amp['keyword']; //保存输入的搜索关键字
	 */
     function search($searchContent='searchContent',$city_id='city_id',$Category='Category'){
		
       if($_REQUEST[$searchContent] && $_REQUEST[$city_id] && $_REQUEST[$Category]){
       	   $searchWhere[$searchContent]=array('LIKE','%'.$_REQUEST[$searchContent].'%');		   
		   $searchWhere[$city_id]=$_REQUEST[$city_id];
		   $searchWhere[$Category]=$_REQUEST[$Category];
		   $searcUrl=__ACTION__.'/'.$searchContent.'/'.$_REQUEST[$searchContent].$city_id.'/'.$_REQUEST[$city_id].'/'.$Category.'/'.$_REQUEST[$Category];
       }else if($_REQUEST[$searchContent] && $_REQUEST[$city_id]){
       	   $searchWhere[$searchContent]=array('LIKE','%'.$_REQUEST[$searchContent].'%');	
		   $searchWhere[$city_id]=$_REQUEST[$city_id];
		   $searcUrl=__ACTION__.'/'.$searchContent.'/'.$_REQUEST[$searchContent].$city_id.'/'.$_REQUEST[$city_id];
       }else if($_REQUEST[$searchContent] && $_REQUEST[$Category]){
       	   $searchWhere[$searchContent]=array('LIKE','%'.$_REQUEST[$searchContent].'%');	
		   $searchWhere[$Category]=$_REQUEST[$Category];
		   $searcUrl=__ACTION__.'/'.$searchContent.'/'.$_REQUEST[$searchContent].'/'.$Category.'/'.$_REQUEST[$Category];
       }else if($_REQUEST[$city_id] && $_REQUEST[$Category]){
       	   $searchWhere[$city_id]=$_REQUEST[$city_id];	
		   $searchWhere[$Category]=$_REQUEST[$Category];
		   $searcUrl=__ACTION__.'/'.$city_id.'/'.$_REQUEST[$city_id].'/'.$Category.'/'.$_REQUEST[$Category];
       }else if($_REQUEST[$searchContent]){
       	   $searchWhere[$searchContent]=array('LIKE','%'.$_REQUEST[$searchContent].'%');		
		   $searcUrl=__ACTION__.'/'.$searchContent.'/'.$_REQUEST[$searchContent];
       }else if($_REQUEST[$city_id]){
       	   $searchWhere[$city_id]=$_REQUEST[$city_id];		
		   $searcUrl=__ACTION__.'/'.$city_id.'/'.$_REQUEST[$city_id];
       }else if($_REQUEST[$Category]){
       	   $searchWhere[$Category]=array('in',$_REQUEST[$Category]);		
		   $searcUrl=__ACTION__.'/'.$Category.'/'.$_REQUEST[$Category];
       }else{
		  $searcUrl=__ACTION__;
       }	
	   $newSearchWhere['searchArr']=$searchWhere;
	   $newSearchWhere['url']=$searcUrl;
	   $newSearchWhere['keyword']=$_REQUEST[$searchContent];
	   return $newSearchWhere;
    }

     public function citySelect($type=0){
		    //城市列表
			if($type)  $where['region_type']=array('elt',2);
			$where['is_show']=1;
			$field='region_id,region_name,parent_id';
	        $cate=M('region')->field($field)->where($where)->order("orders ASC")->select();
			import('Class.Category',APP_PATH);//无限级分类类
			return Category::unlimitedForLevel($cate,'region_id','parent_id');//获取城市列表		 
	 }
	 
}
?>