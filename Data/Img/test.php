<?php
		$root="http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']; 
		$file = $_SERVER['PHP_SELF'];
		$root=str_replace("http://",'',$root);
		$count=substr_count($root,'/');
		$arr=array();
		if($count > 1){
		  $root=$root.'/';
		  preg_match_all('/(\/)([\w\.\-\/]+)(.php\/)/',$root,$arr);
		  $filename= substr($file , strrpos($file , '/')+1); 
		 
		  $out=str_replace($filename,'','/'.$arr[2][0].'.php');
		  $out=rtrim($out,'/');
		}else{
		  preg_match_all('/(\/)([\w\.\-\/]+)(.php)/',$root,$arr);
		  $out=$arr[1][0];
		}
		echo($out);

?>