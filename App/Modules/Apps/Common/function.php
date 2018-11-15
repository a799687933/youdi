<?php

/*
*验证频道权限
*$files_id  频道分类ID my_files_sort表ID
*$num     1添加/修改权限；2审核权限；3删除权限
*
*/
function is_channel($files_id,$num){
   if($_SESSION[C('SESSION_NAME_VAL')]==C('ADMIN_KEY') || $_SESSION[C('SESSION_NAME_VAL')] ==C('RBAC_SUPERADMIN')) return true; 
   if(in_array($num,$_SESSION[C('CHANNEL_GROUPS_STRING')][$files_id])){
	   return true;
   }
   return false;
}

//删除垃圾图片传一个路径clear_img('Uploads/2014-10')
function clear_img($patharr){
	$patharr=rtrim($patharr,'/');
	$patharr=ltrim($patharr,'/');
    $dirMonth=explode('/', $patharr);
	$where['month_dir']=strtotime($dirMonth[1]);//查找条件以月份的时间截
    ini_set("max_execution_time", '0'); //设置脚本运行的最大时间，0为无限际
    $rault=M('entire_img')->field('img')->where($where)->select();//获取数据库资料然后对比
    foreach($rault as $v){
        $img[]=$v['img'];
    }
    $i=0;
	$j=0;
        if(is_dir($patharr)){ 
            $handle = opendir($patharr); //当前目录 
             while (false !== ($file = readdir($handle))) { //遍历该php文件所在目录 
                list($filesname,$kzm)=explode(".",$file);//获取扩展名 
                 $kzm=strtoupper($kzm);
                if($kzm=="GIF" or $kzm=="JPEG" or $kzm=="JPG" or $kzm='PNG') { //文件过滤 
                    if (!is_dir('./'.$file)) { //文件夹过滤 
                    $array[]=$patharr.'/'.$file;//把符合条件的文件名存入数组 
                    $j++;
                    } 
                } 
             } 
            $delImg=array_diff($array,$img);
            foreach($delImg as $v){
                  if(file_exists($v)){
                      unlink($v);
                      $i++;
                  }
            }
            $array='';
       }    
		
    ini_get("max_execution_time");//运行结束返回原来的默认执行时间值
    $info=$j ? '共扫描了'.$j.'张片图片，其中删除'.$i.'张残留图片' : '该文件夹还有没有任何图片！';
    return_json(200,$info);
}




/**
 * tpl_left_menu() 左边导航
 * 模板中使用规则{:tpl_left_menu('WebConfig/index',L('BasicParameters'),'id/5')}
 */
function tpl_left_menu($string,$info,$urlPara=''){
	   $string=trim($string,'/');
	   if($urlPara){
	   	 $urlPara=trim($urlPara,'/');
		 $urlPara='/'.$urlPara;  
	   }
	   $arr=explode('/', $string);
	   if($urlPara){
		   $rel=$string.$urlPara;
	   }else{
		   $rel=$arr[1];
	   }
       if(access($string)){
       	$str='<li><a href="'.U(APP_APPS.'/'.$string,'','').$urlPara.'" target="navTab" rel="'.$rel.'" >'.$info.'</a></li>';
       }
       return $str;
}

//J-UI表单提交返回信息
/**
"statusCode"=>$status, //状态，200成功，300失败，301会话超时
"message"=>$msg,
"navTabId"=>$load,  //重新加载那个navtab
"callbackType"=>$call_function,  //callbackType如果是closeCurrent就会关闭当前tab
"forwardUrl"=>$load_url //客户端可以重新调转到某个界面当'callbackType'值为'forward'时URL才有效
*/
function return_json($statusCode=200,$message='操作成功！',$navTabId='',$rel='',$callbackType='',$forwardUrl=''){
	
	 $json=array(
		"statusCode"=>$statusCode, //状态，200成功，300失败，301会话超时
		"message"=>$message,
		"navTabId"=>$navTabId,  //重新加载那个navtab
		"rel"=>$rel,
		"callbackType"=>$callbackType,  //callbackType如果是closeCurrent就会关闭当前tab
		"forwardUrl"=>$forwardUrl //客户端可以重新调转到某个界面';
		);
	die(str_replace("\\/", "/",  json_encode($json)));
}


 /**
 * tpl_yes_no()  Ajax修改数据库0/1在模板中使用，显示或不显示
 * 使用规则在模板中：{$V.display|tpl_yes_no=###,'article','display',$V['id'],'id','Article/articleSaveForm'}
 * $data  string   条件true是执行修改数据库为0，否则修改数据库为1
 * $tableName  string  表名称
  * $idVal  int  ID的值
  * $field  string  要修改的字段名称，默认display
  * $id   int   主键名称，默认id
 */ 
function tpl_yes_no($data,$tableName,$field='display',$idVal,$id='id',$url=''){
	$PUBLIC=C('__IMG__');
	if($data==1){
		if(access($url)){
			$str='<a href="javascript:;"><img src="'.$PUBLIC.'/yes.gif" onclick='."'".'return cutover(this,"'.U(APP_APPS."/".$url,array("yes_no"=>"ok"),"").'","/tableName/'.$tableName.'/'.$id.'/'.$idVal.'/'.$field.'/");'."'".'title="点击修改"/></a>';
		}else{
			$str='<img src="'.$PUBLIC.'/yes.gif" title="你没有权限"/>';
		}		
	}else{
		if(access($url)){
			$str='<a href="javascript:;"><img src="'.$PUBLIC.'/no.gif" onclick='."'".'return cutover(this,"'.U(APP_APPS."/".$url,array("yes_no"=>"ok"),"").'","/tableName/'.$tableName.'/'.$id.'/'.$idVal.'/'.$field.'/");'."'".'title="点击修改"/></a>';
		}else{
			$str='<img src="'.$PUBLIC.'/no.gif" title="你没有权限"/>';
		}		
	}	
	echo $str;
}

/**
 *tpl_modify_oen() Ajax修改数据库一个字段在模板中使用
 * 使用规则在模板中：{$V.attr_name|tpl_modify_oen=###,'article_attr','attr_name',$V['id'],'id','ArticleAttr/attrSaveForm'}
 * $data  stirng  HTML值
 * $tableName  string  表名称
 * $field   string    要修改的字段名称
 * $idVal    int     要修改的的主键值
 * $id       string  主键名称,默认是ID
 */
 function tpl_modify_oen($data,$tableName,$field,$idVal,$id='id',$url=''){
 	$url =$url ?  $url : 'Publics/saveOen';
 	if(access($url)){
 		//$str='<span class="update" onclick='."'".'edit(this,"'.U(APP_APPS.'/'.$url.'',array('one'=>'ok'),'').'","/tableName/'.$tableName.'/'.$id.'/'.$idVal.'/'.$field.'/");'."'".' title="点击进行修改">'.$data.'</span>';
		$str='<span class="update" onclick='."'".'edit(this,"'.U(APP_APPS.'/'.$url.'','','').'","?one=ok&tableName='.$tableName.'&'.$id.'='.$idVal.'&'.$field.'=");'."'".' title="点击进行修改">'.$data.'</span>';
 	}else{
 		$str='<span >'.$data.'</span>';
 	}
	return $str;
 }
 
  /**
  *tpl_action()   列表（操作）字段几个超链接显示
  * 模板中使用规则 {$action|tpl_action='add','Navigation/navAdd','nav_pid/'.$app["nav_id"],'标题',宽度,高度}，其中$action是占位符必须的
  * $action  占位参数不能去掉
  * $class  string  操作类型：dialog弹出新窗口,navTab列表显示,ajaxTodo删除
  * $access string  控制器名称加方法名称，自动验证权限 'VIP/vipList'
  * $urlPar  string    URL后面的参数  'id/98/p/2/action/form'
  * $title   stirng      标题，默认系统提示
  * $dialog_widht  int  打开dialog的宽度
  * $acc_img   有权限显示的图标
  * $no_img  没有权限操作时显示的图标
 * $dialog_height  int  打开dialog的高度
  */
  function tpl_action($action,$class,$access,$urlPar='',$title='',$acc_img='icon_add.gif',$no_img='',$dialog_widht='',$dialog_height='',$text=''){
    if(empty($no_img)) $no_img=$acc_img;
	if ($dialog_widht) $dialog_widht='width="'.$dialog_widht.'"';
	if ($dialog_height) $dialog_height='height="'.$dialog_height.'"';	
	if(access($access)){
		if(strtoupper($class)=='DIALOG'){
			$title = $title ? $title : '添加';
			if($text){
				 $str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="dialog" mask="true" '.$dialog_widht.' '.$dialog_height.' title="'.$title.'" class="auto-size">'.$text.'</a>';
			}else{
				$str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="dialog" mask="true" '.$dialog_widht.' '.$dialog_height.' title="'.$title.'" class="auto-size">';
				$str.='<img src="'.C(__IMG__).'/'.$acc_img.'"  title="'.$title.'"/></a>';						
			}
		}else if(strtoupper($class)=='AJAXTODO'){
			$title = $title ? $title : '操作不可恢复确定执行本次操作！';
			if($text){
				$str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="ajaxTodo"  title="'.$title.'">'.$text.'</a>';	
			}else{
			   $str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="ajaxTodo"  title="'.$title.'"><img src="'.C(__IMG__).'/'.$acc_img.'"  title="'.$title.'" /></a>';	
			}
		}else if(strtoupper($class)=='NAVTAB'){
			$arrays=explode('/', $access);
			$rel=$arrays[1];
			$title = $title ? $title : '修改';
			if($text){
				$str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="navTab"  rel="'.$rel.'"  title="'.$title.'" class="auto-size">'.$text.'</a>';	
			}else{
				$str='<a  href="'.U(APP_APPS.'/'.$access,"","").'/'.$urlPar.'"  target="navTab"  rel="'.$rel.'"  title="'.$title.'" class="auto-size"><img src="'.C(__IMG__).'/'.$acc_img.'"  title="'.$title.'"/></a>';	
			}
		}
	}else{
       if(strtoupper($class)=='DIALOG'){
		   if($text){
		       $str='<span style="color:#ccc;">'.$text.'</span>';
		   }else{
			   $str='<img src="'.C(__IMG__).'/'.$no_img.'"  title="你没有权限"/>';
		   }
       }else if(strtoupper($class)=='AJAXTODO'){
		   if($text){
		       $str='<span style="color:#ccc;">'.$text.'</span>';
		   }else{
			   $str='<img src="'.C(__IMG__).'/'.$no_img.'"  title="你没有权限"/>';
		   }		   
       }else if(strtoupper($class)=='NAVTAB'){
		   if($text){
		       $str='<span style="color:#ccc;">'.$text.'</span>';
		   }else{
			   $str='<img src="'.C(__IMG__).'/'.$no_img.'"  title="你没有权限"/>';
		   }			   
       }
	}  	
	return $str;
  }
  
  

?>