<?php
class Tool{
  /**
* fontStyle($color,$style,$postContent,type=true)
调用fontStyle($_POST['fontcolor'],$_POST['style'],$_POST['c_title'],'c_title');
* 字体色和字体风格
* @$color 体字色变量
* @$style  字体风格(strong em u del)
* @$postContent 要改变的变量
* @$postKey $_POST[]数组键名
* @return   $_POST['键名']=值
*/    
	  static function fontStyle($color,$style,$postContent,$postKey){
		 if(!empty($color) && $color !='#FFFFFF' && !empty($style)){
			  if($style =='strong'){
				  $_POST[$postKey]='<strong style="color:'.$color.';">'.$postContent.'</strong>';
			  }else if($style =='em'){
				  $_POST[$postKey]='<em style="color:'.$color.';">'.$postContent.'</em>';
			  }else if($style =='u'){
				   $_POST[$postKey]='<u style="color:'.$color.';">'.$postContent.'</u>';
			  }else if($style =='del'){
				  $_POST[$postKey]='<del style="color:'.$color.';">'.$postContent.'</del>';
			  }
		 }else if(!empty($color) && $color !='#FFFFFF' && empty($style)){
				 $_POST[$postKey]='<span style="color:'.$color.';">'.$postContent.'</span>';
		 }else if((empty($color) || $color =='#FFFFFF') && !empty($style)){
			  if($style =='strong'){
				  $_POST[$postKey]='<strong>'.$postContent.'</strong>';
			  }else if($style =='em'){
				  $_POST[$postKey]='<em>'.$postContent.'</em>';
			  }else if($style =='u'){
				   $_POST[$postKey]='<u>'. $postContent.'</u>';
			  }else if($style =='del'){
				   $_POST[$postKey]='<del>'. $postContent.'</del>';
			  }
		 }
		 return $_POST[$postKey];
    }

}


?>