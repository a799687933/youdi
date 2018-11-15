<?php
return array(
	//'配置项'=>'配置值'
	 'TMPL_EXCEPTION_FILE'   => './404/indexError.html',// 前端异常页面的模板文件
	 'TMPL_EXCEPTION_PATH'   => __ROOT__.'/404/404.gif',// 前端异常页面的图片路径
	//默认错误跳转对应的模板文件
	 'TMPL_ACTION_ERROR' => 'Common:prompt',
	 //默认成功跳转对应的模板文件
	 'TMPL_ACTION_SUCCESS' => 'Common:prompt',
	 
	 'APP_AUTOLOAD_PATH'=>'@.TagLib',//自动加载自定义类目录
	 'TAGLIB_BUILD_IN'=>'Cx,Mytaglib',//自动加载自定义目录类
	
	//开启静态缓存
	'HTML_CACHE_ON'=>false,
	
	//动态缓存方法，默认File
	'DATA_CACHE_TYPE'=>'File',

   /***************************/
   'TOKEN_ON' => 1,  // 是否开启令牌验证 默认关闭   
   'TOKEN_NAME' =>TOKEN_NAME,    // 令牌验证的表单隐藏字段名称，默认为__hash__   
   'TOKEN_TYPE' => 'md5',  //令牌哈希验证规则 默认为MD5   
   'TOKEN_RESET' =>  true,  //令牌验证出错后是否重置令牌 默认为true
   /***************************/	

);
?>













