<?php
return  array(
	//'配置项'=>'配置值'
	'RBAC_SUPERADMIN'=>'root',  //指定超级管理员和程度应用名称
	'ADMIN_AUTH_KEY'=>'superadmin', //超级管理员识别（程序员）
	'USER_AUTH_ON'=>true, //是否开启验证
	'USER_AUTH_TYPE'=>1, //验证类型（1：登录验证，2时时验证）
	'USER_AUTH_KEY'=>'uid',  //用户验证识别号
	'NOT_AUTH_MODULE'=>'', //无需验证的模块控制器
	'NOT_AUTH_ACTION'=>'', //无需验证的方法
	'RBAC_ROLE_TABLE'=>PREFIX.'role',//角色表
	'RBAC_USER_TABLE'=>PREFIX.'role_user',//用户与角色中间表
	'RBAC_ACCESS_TABLE'=>PREFIX.'access',//权限表
	'RBAC_NODE_TABLE'=>PREFIX.'node',//节点表
	//'TMPL_ACTION_ERROR'     => './Error/Tpl/adminInfo.html', // 默认错误跳转对应的模板文件
   // 'TMPL_ACTION_SUCCESS'   => './Error/Tpl/adminInfo.html', // 默认成功跳转对应的模板文件
  // 	'TMPL_ACTION_ERROR'     => './'.APP_NAME.'/'.MODULES.'/'.APP_APPS.'/Tpl/Common/prompt', // 默认错误跳转对应的模板文件
   // 'TMPL_ACTION_SUCCESS'   =>  './'.APP_NAME.'/'.MODULES.'/'.APP_APPS.'/Tpl/Common/prompt', // 默认成功跳转对应的模板文件
	/*'TMPL_PARSE_STRING'=>array(
	 '__J_UI__'=>__ROOT__.'/Public/Apps/dwz',// J-UI JS路径，J-UI XML路径
	 '__EDITOR__'=>__ROOT__.'/Public/Apps',//编辑器路径
	  '__SYS_IMG__'=>__ROOT__.'/Public/Apps/Images',// 后台系统图片
	
     ),*/
     '__IMG__'=>__ROOT__.'/Public/Apps/Images', //

    //后台登录
    'APPS_LOGIN_TITLE'=>'登录后台管理-悦阁网络科技有限公司',    
    //后台标题
    'APPS_TITLE'=>'悦阁网络科技有限公司',
    //后台底部版权信息
    'APPS_COPR'=>'Copyright © 2014 悦阁网络科技有限公司 技术支持：<a href="http://www.yuegekeji.com/" target="_blank">悦阁科技</a>',
    
	//获取指定文档分类
	'GET_FILES_CLASS'=>'knowledge,bbs,webhelp',
	 
	
	// 'TMPL_FILE_DEPR'=>'_',//模板文件少一个目录
	 'DB_DIR'=>'./my_back_db_ette@4JtsE2',//数据库备份目录

   /***************************/
   'TOKEN_ON' => true,  // 是否开启令牌验证 默认关闭   
   'TOKEN_NAME' =>TOKEN_NAME,    // 令牌验证的表单隐藏字段名称，默认为__hash__   
   'TOKEN_TYPE' => 'md5',  //令牌哈希验证规则 默认为MD5   
   'TOKEN_RESET' =>  true,  //令牌验证出错后是否重置令牌 默认为true
   /***************************/	
   
   /*验证频道权限SESSION*/
   'CHANNEL_GROUPS_STRING'=>'channel_groups',
   /*****************************/
);
?>