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
	'HTML_CACHE_RULES'=>array(
					// Index 控制器下的 index 方法生成缓存。
					// 缓存名规则module代表制器，action代表方法名,id代表当前的GET['id'],	0 代表无时间缓存机制
					'Index:index'=>array('{:module}_{:action}',0), //首页缓存
					'Help:content'=>array('{:module}_{:action}_{id}',0), //平台帮助文章单页
					'About:index'=>array('{:module}_{:action}_{id}',0), //关于我们单页
					/*'Category:index'=>array('{:module}_{:action}_{id}_{p}',0), //商品分类列表
					'Category:index'=>array('{:module}_{:action}_{id}_{co}_{p}',0), //商品分类列表源码检索
					'Category:index'=>array('{:module}_{:action}_{id}_{co}_{min}_{max}_{p}',0), //商品分类列表源码与价格区间检索
					'Category:index'=>array('{:module}_{:action}_{id}_{co}_{s}_{p}',0), //商品分类列表服务商类型检索
					'Category:index'=>array('{:module}_{:action}_{id}_{co}_{s}_{min}_{max}_{p}',0), //商品分类列表服务商类与价格区间型检索
					'Category:index'=>array('{:module}_{:action}_{id}_{min}_{max}_{p}',0), //商品分类列表价格区间检索
					'Category:index'=>array('{:module}_{:action}_{id}_{s}_{p}',0), //商品分类列表服务商类型检索*/
					'Goods:index'=>array('{:module}_{:action}_{id}',0), //商品内容页
					),
	
	//动态缓存方法，默认File
	'DATA_CACHE_TYPE'=>'File',
	'COOKIE_ID'=>'user_ip',  //COOKIE保存用户ID
	'COOKIE_NAME'=>'user_name', //COOKIE保存用户名
	'COOKIE_IMG'=>'user_img', //COOKIE保存用户头像

   /***************************/
   'TOKEN_ON' => 1,  // 是否开启令牌验证 默认关闭   
   'TOKEN_NAME' =>TOKEN_NAME,    // 令牌验证的表单隐藏字段名称，默认为__hash__   
   'TOKEN_TYPE' => 'md5',  //令牌哈希验证规则 默认为MD5   
   'TOKEN_RESET' =>  true,  //令牌验证出错后是否重置令牌 默认为true
   /***************************/	

);
?>













