<?php
header('Content-Type:text/html;charset=utf-8');
define('MODULES','Modules');//分组应用目录,即__ROOT__/App/Modules
define('APP_INDEX','Home');//前端路径,即__ROOT__/App/Modules/Index
define('APP_ADMIN','Admin');//后台路径,即__ROOT__/App/Modules/Admin
//define('APP_SHOPS','Shops');//会员商铺
define('APP_APPS','Apps');//新版后台路径
define('APP_MOBILE','Mobile');//手机板
define('PREFIX','my_'); //数据库表前缀
define('APP_NAME','App');//前台项目名称
define('APP_PATH','./App/');//前台项目文件夹
define('APP_DEBUG',true);//开启调试模式，则不生成缓存文件
define('RUNTIME_PATH',APP_PATH.'Runtime/');//为了搛容重写编译目录路径
/**********动态令牌**************/
session_start();
if(empty($_SESSION['MY_TOKEN'])){
	$str='abcdefghijklmnopqrstuvwxyz'; 
	$rndstr='';	//用来存放生成的随机字符串 
	for($i=0;$i<5;$i++) { 
		$rndcode=rand(0,25); 
		$rndstr.=$str[$rndcode]; 
	} 
	define('TOKEN_NAME','T'.$rndstr); //动态令牌名称
	$_SESSION['MY_TOKEN']='T'.$rndstr;//动态令牌写到SESSION
}else{
	define('TOKEN_NAME',$_SESSION['MY_TOKEN']); //动态令牌名称
}
/********************************/
require './ThinkPHP/ThinkPHP.php';
?>