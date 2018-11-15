<?php
/**
--
-- 表的结构 `my_album`
--

CREATE TABLE IF NOT EXISTS `my_album` (
  `img` char(200) NOT NULL COMMENT '//相关相册图片路径',
  `img_bewrite` char(100) NOT NULL COMMENT '//图片文字描述',
  `tableId` int(11) NOT NULL COMMENT '//相关相册表ID',
  `table_name` char(50) NOT NULL COMMENT '//相关相册表名称',
  KEY `table_name` (`table_name`),
  KEY `tableId` (`tableId`),
  KEY `img_bewrite` (`img_bewrite`),
  KEY `img` (`img`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 表的结构 `my_entire_img`
--

CREATE TABLE IF NOT EXISTS `my_entire_img` (
  `img` char(200) NOT NULL COMMENT '//图片路径',
  `tableId` int(11) NOT NULL COMMENT '//表ID',
  `table_name` char(50) NOT NULL COMMENT '//表名称',
  `month_dir` int(10) unsigned NOT NULL COMMENT '//图片所属的文件夹',
  KEY `tableId` (`tableId`),
  KEY `table_name` (`table_name`),
  KEY `img` (`img`),
  KEY `month_dir` (`month_dir`),
  KEY `img_2` (`img`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/
class DisposeImg{

/**
*获取百度编辑器图片
* editorImg($allImg)
* @access public
* @param $allImg  百度编辑器变量
* @return array
 */	
 static public function editorImg($allImg){
        $img=str_replace("\\",'',$allImg);//取消反义斜杠
		$img=str_replace(' ','',$img);
        $editor= '/src\=(\"|\')([\w\.\-\/]+)(\"|\')/';//变量里获取图片匹配模式
        if(preg_match_all($editor,$img,$arr)){//匹配
		   $returnArr=array();
		   foreach($arr[2] as $value){
		       if(__ROOT__){
		         $returnArr[]=str_replace(__ROOT__.'/','',$value);  
		       }else{
		         $returnArr[]=ltrim($value,'/');  
		       }
               
             }             
		}
	    return $returnArr;
	}
	
/**
*addPictures($tableNames,$id,$commonImg,$related='',$bewrite='',$ditorContent='')
* 添加内容页处理上传图片
* @$tableNames  记录表名称不带前缀
* @$id 记录表ID
* @$commonImg 普通图片集合数组（缩略图、各种扩展添加图等。不抱括相关相册图片数组）
* @$related  相关相册图片数组
* @$bewrite  相关相册文字描述数组
* @$ditorContent 编辑器变量

*/
   static public function addPictures($tableNames,$id,$commonImg,$related='',$bewrite='',$ditorContent=''){
	   
		//普通图片集合数组与相关相册数组合拼
		if($related && $commonImg){
			$imgAll=array_merge($related,$commonImg); 
			$postimgall=$related;
		}elseif($related && !$commonImg){
			$imgAll=$related;
			$postimgall=$related;
		}elseif($commonImg){
			$imgAll=$commonImg;
		}        
		//（缩略图和大图）与（相关相册数组）与（编辑器）
		if(DisposeImg::editorImg($ditorContent) && $imgAll){
		    
			$imgAll=array_merge(DisposeImg::editorImg($ditorContent),$imgAll);
            
		}elseif(DisposeImg::editorImg($ditorContent) && !$imgAll){
			$imgAll=DisposeImg::editorImg($ditorContent);
            
		}
		 //如果相关相册图片不是数组，强制转换成数组
		 if(!is_array($postimgall)){
			 $postimgall=array($postimgall);
		  }         
		  $album=M('album');
		 foreach($postimgall as $k=>$value){//写入相关相册图片供显示使用
		    if($value){
		    	$album->add(array('img'=>$value,'img_bewrite'=>$bewrite[$k],'tableId'=>$id,'table_name'=>$tableNames));
		    }		   
		 }		 
		 //如果不是数组，强制转换成数组
		 if(!is_array($imgAll)){
			 $imgAll=array($imgAll);
		  }
		 $db=M('entire_img');
		 foreach($imgAll as $v){//全部图片写入my_entire_img表供删除时使用
		     if($v){
		         $saveDir=DisposeImg::dirToTime($v); //把以日期为文件夹转换成时间截
		     	 $db->add(array('img'=>$v,'tableId'=>$id,'table_name'=>$tableNames,'month_dir'=>$saveDir));
		     }		 
		  }
    }

/**
*updatePictures($tableNames,$id,$commonImg,$related='',$bewrite='',$ditorContent='')
* 修改内容页处理上传图片
* @$tableNames  记录表名称不带前缀
* @$id 记录表ID
* @$commonImg 普通图片集合数组（缩略图、各种扩展添加图等。不抱括相关相册图片数组）
* @$related  相关相册图片数组
* @$bewrite  相关相册文字描述数组
* @$ditorContent 编辑器变量
*/
   static public function updatePictures($tableNames,$id,$commonImg,$related='',$bewrite='',$ditorContent=''){
        
		//普通图片集合数组与相关相册数组合拼
		if($related && $commonImg){
			$imgAll=array_merge($related,$commonImg); 
			$postimgall=$related;
		}elseif($related && !$commonImg){
			$imgAll=$related;
			$postimgall=$related;
		}elseif($commonImg){
			$imgAll=$commonImg;
		}
		//（普通图片集合数组）与（相关相册数组）与（编辑器）
		if(DisposeImg::editorImg($ditorContent) && $imgAll){
			$imgAll=array_merge(DisposeImg::editorImg($ditorContent),$imgAll);
		}elseif(DisposeImg::editorImg($ditorContent) && !$imgAll){
			$imgAll=DisposeImg::editorImg($ditorContent);
		}
        //如果没有任何图片
		if(!$imgAll && !$postimgall){
			DisposeImg::delPictures($id,$tableNames); //删除所有图片
			M('album')->where(array('tableId'=>$id,'table_name'=>$tableNames))->delete();//删除相关相册数据
			return true;
		}
		////////////////////////////////////////////////////
		//处理相关相册修改
		 //如果相关相册图片不是数组，强制转换成数组
		 if(!is_array($postimgall)){
			 $postimgall=array($postimgall);
		  }
		if($postimgall){
			$p=M('album')->where(array('tableId'=>$id,'table_name'=>$tableNames))->select();
			if($p){
				$album_img=array();//相关相册数组
				foreach($p as $v){
					$album_img[]=$v['img'];
				}
				 $Different=array_diff($album_img,$postimgall);//是否修改过相关相册
				 if($Different){//如果有修改
					foreach($Different as $v){
						unlink($v);//删除被修改的图片
					}
				 }	
			  }
			  $album_db=M('album');
			  $album_db->where(array('tableId'=>$id,'table_name'=>$tableNames))->delete();//删除相关相册数据
			  
			  foreach($postimgall as $k=>$v){//重新写入图片数据
			      if($v){
			      	 $album_db->add(array('img'=>$v,'img_bewrite'=>$bewrite[$k],'tableId'=>$id,'table_name'=>$tableNames));
			      }				  
			  }
		   }
		 /////////////////////////////////////////////////////////////
		//处理全部图片修改
		//如果$imgAll不是数组
		 if(!is_array($imgAll)){
			  $imgAll=array($imgAll);
		 }
		if($imgAll){
		   $p=M('entire_img')->where(array('tableId'=>$id,'table_name'=>$tableNames))->select();
			if($p){	
				$album_img=array();//相关相册数组
				foreach($p as $v){
					$album_img[]=$v['img'];
				}
				 $Different=array_diff($album_img,$imgAll);//是否修改过相关相册
				 if($Different){//如果有修改
					foreach($Different as $v){
						unlink($v);//删除被修改的图片
					}
				  }	
					$album_db=M('entire_img');
					$album_db->where(array('tableId'=>$id,'table_name'=>$tableNames))->delete();//删除ID相册数据
					if($imgAll){
					  foreach($imgAll as $v){//重新写入图片数据
					      if($v){
					      	 $saveDir=DisposeImg::dirToTime($v); //把以日期为文件夹转换成时间截
					      	 $album_db->add(array('img'=>$v,'tableId'=>$id,'table_name'=>$tableNames,'month_dir'=>$saveDir));
					      }						  
					  }
					}
			 }else{
				 //如果$imgAll不是数组，强制转换成数组
				 if(!is_array($imgAll)){
					 $imgAll=array($imgAll);
				  }
				  $album_db=M('entire_img');
				  foreach($imgAll as $v){//重新写入图片数据
				      if($v){
				      	  $saveDir=DisposeImg::dirToTime($v); //把以日期为文件夹转换成时间截
				      	  $album_db->add(array('img'=>$v,'tableId'=>$id,'table_name'=>$tableNames,'month_dir'=>$saveDir));
				      }					  
				  }
			 }
		  }
   }

/**
*delPictures($id)
* @ 删除内容页处理上传图片
* @$id 记录ID,不能传数组只能传字符串(1,2,3,4,5)或单个id
* @$table_name  表名称不带前缀
* @return 
*/
   static public function delPictures($id,$table_name){
			//删除相关相册
			$raltr=M('album');
			$amp['tableId']=array('in',$id);
			$amp['table_name']=$table_name;
			$deesee=$raltr->where($amp)->select();
			if($deesee){
				foreach($deesee as $k=>$v){
					@unlink($v['img']);
				}
			  $raltr->where($amp)->delete();	
			}
			
			//删除全部图片
			$all_db=M('entire_img');
			$seldaa=$all_db->where($amp)->select();
			if($seldaa){
				foreach($seldaa as $k=>$v){
					@unlink($v['img']);
				}
			$all_db->where($amp)->delete();	
			}
   }

/**
 * 把以月份的文件夹名称转换成时间截（2014-10）转换成1442546878
 * $str 文件路径
 */
   static public function dirToTime($str){   	
		$str=explode('/', $str);
		return strtotime($str[1]);
    }

}


?>