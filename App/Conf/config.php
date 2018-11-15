<?php
return array(
	   'APP_GROUP_LIST'=>APP_INDEX.','.APP_ADMIN.','.APP_MOBILE.','.APP_APPS,  //项目分级		
	   'DEFAULT_GROUP'=>APP_INDEX,//默认分组
	   'APP_ADMIN_PATH'=>APP_ADMIN, //后台分组
	   'APP_GROUP_MODE'=>1,//新版独立分组
	   'APP_GROUP_PATH'=>MODULES,//分组目录
	   'DB_HOST'=>'localhost', //数据库服务器地址
	   'DB_PORT'=>'3306', //数据库端口号
	   'DB_NAME'=>'youdi',  //选择数据库
	   'DB_PREFIX'=>PREFIX, //表前缀
	   'DB_USER'=>'root',   //数据库用户名
	   'DB_PWD'=>'',  //数据库密码   
	   'TMPL_TEMPLATE_SUFFIX'  => '.html',     // 默认模板文件后缀
	   'URL_MODEL' =>1,  //URL模式pathinfo
	   'URL_HTML_SUFFIX'=>'html',//URL静态模式后缀（可以任意后缀名），为空时为没有后缀名
	   'TMPL_VAR_IDENTIFY'=>'array',//点语法默认解析数组

	   //'TMPL_FILE_DEPR'=>'_',//模板文件少一个目录
	   'SESSION_TYPE'=>'db',//自定义SESSION 数据库存储
	   'LOAD_EXT_CONFIG'=>'SystemConfig,GoodsCategory', //载入多个配置文件用逗号隔开
	  'SHOW_PAGE_TRACE'=>false,  //开启视图调试模式  
	  'USER_IMG' =>'Data/Img/noavatar_middle.gif', //系统默认会员头像
	  'GOODS_IMG' =>'Data/Img/no_picture.gif', //商品缺少缩略图时默认图片
	  'THUMBNAIL_PATH'=>'./Data/Img/Watermark.png', //水印图片路径
	  'COMMON_SHOPS_LOGO_PATH'=>'./Data/Img/logo.png', //商户公共LOGO图
	   'IMG_UPLOADS'=>'Uploads',//后台图片上传路径，为搛容模板路径名前后不带/或./
	   'FILE_UPLOADS'=>'UploadFile',//后台上传文件的路径，如（.rar）
	   'ORDER_RETURN_DATE'=>30, //订单可退货时间(天)
	   /*指定可使用的功能模块*/
	   'WHETHER_TO_ALLOW'=>true, //是否允许未登录购物
	   'IS_GOODS_SUPPLIER'=>false, //是否开启商品供应商模块
	   'IS_GOODS_CITY_SELECT'=>false, //开启商品城市选择
	   'CHECK_STOCK'=>true,  //是否检查商品库存
	   'IS_GOODS_LOGISTICS'=>false, //商品详情页是否启用显示物流功能
	   'IS_GOODS_ATTRIBUTE'=>true, //是否启用商品属性功能
	   'IS_GOODS_ATTRIBUTE_MANUAL'=>false, //是否启用商品属性手工输入功能
	   'IS_GOODS_ATTRIBUTE_PRICE'=>false, //是否启用商品属性价格
	   'IS_GOODS_ATTRIBUTE_IMGS'=>false, //是否启用商品属性上传图片功能
	   'IS_GOODS_ATTRIBUTE_STOCK'=>true, //是否启用商品属性库存功能
	   'IS_GOODS_PARAMETERS'=>true, //是否启用商品规格参数
	   'IS_GOODS_AFTER_SALES'=>false,//是否启用商品售后服务
	   'IS_GOODS_LINK'=>false, //是否启用商品关联
	   'IS_GOODS_ACCESSORIES'=>false, //是否启用商品配件关联
	   'IS_GOODS_LINK_ARTICLE'=>false, //是否启用商品文章关联
	   'IS_GOODS_ZHUHE'=>false, //是否启用商品组合购买
	   'IS_CONSUlTATION'=>false, //是否启用商品咨询模块
	   'IS_GOODS_CLASS'=>false, //是否启用商品价格分级
	   'IS_GOODS_MEMBER_PRICE'=>false, //是否启用商品(会员等级价格)
	   'IS_GOODS_VOLUME_PRICE'=>false,  //是否启用商品(数量价格优惠)
	   'IS_GOODS_NEW'=>false, //是否启用商品设置(新品)
	   'IS_RECOMMEND'=>false, //是否启用商品设置(本店推荐)
	   'IS_GOODS_HOT'=>false, //是否启用商品设置(热销商品)
	   'IS_GOODS_WEEK'=>false, //是否启用商品设置(每周优惠)
	   'IS_GOODS_BRAND'=>true, //是否启用商品品牌
	   'IS_BRAND_COVER'=>false, //是否启用自定义PC版封面HTML代码
	   'IS_M_BRAND_COVER'=>false, //是否启用自定义手机版封面HTML代码
	   'IS_LOGISTICS'=>false, //订单查询是否启用物流功能
	   'IS_LOGISTICS_TPL'=>false, //是否启用物流模板功能
	   'IS_LETTER'=>false, //是否启用站内信息
	   'IS_RANK_POINTS'=>false, //是否启用会员等级积分
	   'IS_PAY_POINTS'=>false,  //是否启用消费积分
	   'IS_ACCOUNT'=>false, //是否启用会员资金帐户
	   'IS_PROMOTION'=>false, //是否启用促销模块
	   'IS_BONUS'=>false, //是否启用现金卷功能
	   'IS_dISCOUNT'=>false, //是否启用优惠活动(满即送)
	   'IS_EMAIL_SUBSCRIBE'=>true, //是否使用邮件订阅模块
	   'IS_RECENT_VISIT'=>true, //是否开启最近浏览功能
	   'IS_GUESS_YOU_LIKE'=>true, //开启猜你喜欢(根据用户浏览习惯，记录浏览次数最多的分类ID)
	   /*指定可使用的功能模块结束*/
	   //其它值
	   'COOKIE_CITY_ID'=>'city_id',  //城市检索(城市COOKIE变量键名)
	   'COOKIE_CITY_NAME'=>'city_name',  //城市检索(城市COOKIE名称变量键名)	   
	   'COOKIE_DISTRICT_ID'=>'district_id',//区县检索(区县COOKIE变量键名)
	   'COOKIE_DISTRICT_NAME'=>'district_name',//区县检索(区县COOKIE名称变量键名) 
	   'COOKIE_IS_SELECT'=>'is_select',//是否选择了城市

	   'GOODS_IMG_WIDTH'=>200,  //商品缩略图宽
	   'GOODS_IMG_HEIGHT'=>200,  //商品缩略图高	   
	   'GOODS_ALBUM_WIDTH'=>800,//商品相册图宽
	   'GOODS_ALBUM_HEIGHT'=>800,//商品相册图高
	   'GOODS_ATTR_WIDTH'=>800,//商品属性图宽
	   'GOODS_ATTR_HEIGHT'=>800,//商品属性图高	   
	   'DIS_IMG_WIDTH'=>228, //优惠卷宽
	   'DIS_IMG_HEIGHT'=>105,//优惠卷高
	   
       //消费积分最低要使用
       'INTEGRAL_LOWEST_POINT'=>1000,
       
	   //购物车优惠信息字段名
	   'CART_REC_INFO'=>'rec_info',
	   //购物车个数COKIE名称
	   'CART_COUNT'=>'cart_count',
	   
	   /*公共显示订单状态*/
	   'ORDER_START'=>array(
							                       0=>'已取消',
												   1=>'<span style="color:blue;">未确认</span>',
												   2=>'<span style="color:green;">交易完成</span>',
												   3=>'<span style="color:red;">退货/退款</span>',
												   4=>'<span style="color:red;">退款完成</span>',
										      ),
	   /*公共显示配送状态*/
	   'ORDER_SHIPPING'=>array(
							                      0=>'未发货',
												  1=>'<span style="color:blue;">备货中</span>',
												  2=>'<span style="color:green;">已发货</span>',
												  3=>'<span style="color:green;">已收货</span>'
												),
	   /*公共显示支付状态*/
	   'ORDER_PAY'=>array(
						                          0=>'未付款',
												  1=>'<span style="color:blue;">付款中</span>',
												  2=>'<span style="color:green;">已付款</span>'
											  ),
	   /*买商品后评价感受*/
	   'GOODS_SATISFY'=>array(
							                  0=>array('id'=>1,'name'=>'还不错'),
											  1=>array('id'=>2,'name'=>'质感很好'),
											  2=>array('id'=>3,'name'=>'配送很快'),
											  3=>array('id'=>4,'name'=>'价格便宜'),
											  4=>array('id'=>5,'name'=>'价格合理'),
											  5=>array('id'=>6,'name'=>'服务很好')
											  ),
	  //支付方式
	   'PAYMENT_METHOD'=>array(
							                  0=>'在线支付',
											  1=>'快递代收',
											  2=>'线下支付',
											  3=>'余额支付'
											  ),
	   
	 /*密码问题*/			
	 'QUESTION'=>array(
	                                  0=>'您母亲的姓名是',
									  1=>'您父亲的姓名是',
									  2=>'您配偶的姓名是',
									  3=>'您的出生地是',
									  4=>'您高中班主任的名字是',
									  5=>'您初中班主任的名字是',
									  6=>'您小学班主任的名字是',
									  7=>'您的小学校名是',
									  8=>'您父亲的生日是',
									  9=>'您母亲的生日是',
									  10=>'您配偶的生日是'
	 ),	
	 
	   //URL模式(2去掉Index.php)
	   'URL_MODEL'=>2,	   
		//开启URL路由
		'URL_ROUTER_ON'=>true,	
		//URL规则
		'URL_ROUTE_RULES'=>array(
			//商品列表页路由					 
			'/^c-(\d+)$/'=>'Category/index?id=:1',	// 分类页路由	,大U('/c-'.$result['id'])生成URL;	
			'/^c-(\d+)-p-(\d+)$/'=>'Category/index?id=:1&p=:2',
		),	
		
    //多语言配置
   /***************************/
   //多语言配置
   'LANG_SWITCH_ON'=>true, //开启语言包功能
   'LANG_AUTO_DETECT'=>true, //自动侦测语言开启多语言功能后有效
   'DEFAULT_LANG' => 'zh-cn', // 默认语言
   'LANG_LIST'=>array('zh-cn','en-us'), //充许切换的语言列表用逗号分隔,后台显示多语根据这里配置当前数据库语言种类array('zh-cn','en-us','vi-vi')
   'LANG_LIST_INFO'=>array('中文','English'), //充许使用的语言后台界面显示提示当前数据库语言种类array('中文',English'','Việt Nam')
   'VAR_LANGUAGE'=>'lang', //默认语言切换变量
   'CUR'=>array('zh-cn'=>'￥','en-us'=>'$'),//货币符号当前数据库语言种类array('zh-cn'=>'￥','en_us'=>'$','vi-vi'=>'₫')
   'CUR_REP'=>array('zh-cn'=>'CNY','en_us'=>'USD'),//货币字母代号当前数据库语言种类array('zh-cn'=>'CNY','en-us'=>'USD','vi-vi'=>'VND')
   'CUR_INFO'=>array('zh-cn'=>'人民币','en-us'=>'美元'),//货币消息提示当前数据库语言种类  array('zh-cn'=>'人民币','en-us'=>'美元','vi-vi'=>'越南盾') 
   /***************************/				

   //分页键值
  'VAR_PAGE'=>'p',
  'APP_KEY'=>'5fFd545FSf3F9fDsf4g3d7Gf34HDf4F3w6kodm',//加密串
  
     /***发放现金现金券识别代号**********/
   '_ZERO'=>0, //按用户发放
   '_ONE'=>1, //按商品发放
   '_TWO'=>2, //按订单金额发放
   '_THREE'=>3, //按线下发放
   '_FOUR'=>4, //按推荐会员者发放
   '_FIVE'=>5,  //新注册用户自动发放
   '_SIX'=>6,  //会员点击领取
   /************/
   //会员头像大小
   'USER_PIC_W'=>183,
   'USER_PIC_H'=>183,
   //服务商的LOGO大小
   'SERVER_W'=>183,
   'SERVER_H'=>183,
   
   //Apps后台登录SESSION值配置///////////////////////////////////////////////////////////
   'SESSION_NAME_VAL'=>'username',//保存SESSION的用户变量
   'SESSION_TIME_VAL'=>'logintime',//保存SESSION的登录时间变量
   'SESSION_LOGINIP_VAL'=>'loginip',//保存SESSION的用户登录IP
   'ADMIN_KEY'=>'admin',//后台超级管理员识别（管理员）   
   
	//注册用户写SESSION////////////////////////////////////////////////////////////////////////////////////
	'SE_USER_GRADR_ID'=>'user_grade_id',//会员等级ID
	'SE_USER_GRADR_NAME'=>'user_grade_name',//会员等级名称
	'SE_USER_RANK_POINTS'=>'user_grade_points',//会员等级积分
	'SE_USER_ID'=>'uid_id', //用户ID
	'SE_USER_NAME'=>'user_name', //用户帐号
	'SE_NICKNAME'=>'nickname', //用户昵称
	'SE_USER_HEAD_PIC'=>'head_pic',  //用户头像
	'SE_USER_IP'=>'user_ip',  //用户IP
	'SE_USER_LAST_TIME'=>'last_time', //用户上次登录时间
	'SE_USER_LIGON_TIEM'=>'user_login_tiem', //用户登录时间
	
	/*****模板路径******************************************/
	 'TMPL_PARSE_STRING'=> array(//模板路径替换数组
		 '__HOME__'    =>  __ROOT__.'/Public/Home',// Home模块 CSS，JS ，imgaes的目录路径
		 '__MOBILE__'    =>  __ROOT__.'/Public/Mobile',// Mobile模块 CSS，JS ，imgaes的目录路径
		 '__COMMON__'    =>  __ROOT__.'/Public/Common',// Common公共目录
		 '__PLUGINS__'    =>  __ROOT__.'/Public/Plugins',// Plugins公共目录
		 '__UPLOAD__'=> __ROOT__.'/Uploads', //上传目录
		 '__J_UI__'=>__ROOT__.'/Public/Apps/dwz',// J-UI JS路径，J-UI XML路径
		 '__EDITOR__'=>__ROOT__.'/Public/Apps',//编辑器路径
		 '__SYS_IMG__'=>__ROOT__.'/Public/Apps/Images',// 后台系统图片	 
	 ),	

   //商品数量优惠多语言
   'CN_DISCOUNTS'=>'数量折扣',
   'US_DISCOUNTS'=>'Quantity discount',
    'VI_DISCOUNTS'=>'Số lượng giảm giá',
   //商品限时促销
   'CN_PROMOTION'=>'限时促销',
   'US_PROMOTION'=>'promotion',
   'VI_PROMOTION'=>'Chương trình khuyến mãi', 
   //组合购买
   'CN_COMBINED_PRICE'=>'组合价格',
   'US_COMBINED_PRICE'=>'Combined price',   
   'VI_COMBINED_PRICE'=>'Combo giá',   
   //提交定订单使用积分说明
   'CN_ORD_POINTS'=>'结算订单使用积分抵现',
   'US_ORD_POINTS'=>'Settlement goods using integral', 
   'VI_ORD_POINTS'=>'Điểm so với lệnh thanh toán', 
   
   //取消订单和删除订单积分说明
   'CN_RETURN_POINTS'=>'订单被取消退回用户积分',
   'US_RETURN_POINTS'=>'Order is cancelled and returned to the user points', 
   'VI_RETURN_POINTS'=>'Trả lại đơn đặt hàng là người dùng đã hủy tích hợp',  

   //订单退款到用户帐户说明
   'CN_ORD_COM_MONEY'=>'订单退款',
   'US_ORD_COM_MONEY'=>'Order refund', 
   'VI_ORD_COM_MONEY'=>'Hoàn lại tiền hàng',

   //会员增送订单积分说明
   'CN_ORD_COM_POINTS'=>'订单交易完成增送积分',
   'US_ORD_COM_POINTS'=>'Order transaction complete add points', 
   'VI_ORD_COM_POINTS'=>'Trật tự cho điểm thỏa thuận',

   //会员在线充值
   'CN_USER_RECHARGE'=>'在线充值',
   'US_USER_RECHARGE'=>'Online recharge', 
   'VI_USER_RECHARGE'=>'Nạp tiền trực tuyến', 

   //会员订单帐户支付
   'CN_USER_ACC_PAY'=>'订单帐户支付',
   'US_USER_ACC_PAY'=>'Account payment', 
   'VI_USER_ACC_PAY'=>'Thứ tự tài khoản', 
   
   //退货原因中文
   'CN_RETURN_RREAS'=>array(
		1=>'其它',					
		2=>'商品与描述不符',
		3=>'产品质量',
		4=>'商品有缺陷',
		5=>'不喜欢',
		6=>'物流延迟',
		7=>'错发漏发',
		8=>'太小',
		9=>'太大'
	),
   //退货原因英文
   'US_RETURN_RREAS'=>array(
		1=>'Commodity and description does not match',
		2=>'product quality',
		3=>'Defective goods',
		4=>'Dislike',
		5=>'Logistics delay',
		6=>'Wrong hair',
		7=>'Too small',
		8=>'too big'
   ),
   
   //关于我们文章ID
   'A_1'=>138,//	关于我们
   'A_2'=>139,//	职业机会
   'A_3'=>140,//	YOUDI WU服务保证
   'A_4'=>141,//	技术支持
   'A_5'=>142,//	单品推荐
   'A_6'=>143,//	客户尊享
   'A_7'=>158,//	品牌理念
   'A_8'=>211,//	品牌型像
   //客服中心
   'B_1'=>170,//联系我们
   'B_2'=>171,//订单与寄送
   'B_3'=>172,//退货与退款
   'B_4'=>173,//常见问题
   'B_5'=>174,//条款与条件
   'B_6'=>175,//隐私与Cookie政策
   'B_7'=>176,//需要帮助？
   'B_8'=>177,//如何购物
   'B_9'=>195,//商品包装
   'B_10'=>196,//支付
   
   //货币符号
   'KTEC_H'=>'￥',
   'KTEC_CUR'=>'RMB',
   //灰色阅读量  < 1000
   'LIMIT_SHOW1'=>1000,
   //黄色阅读量 >=1000 AND < = 9999 
   'LIMIT_SHOW2'=>9999,
   //红色阅读量 >= 10000
   'LIMIT_SHOW3'=>10000,
   //资讯标签
   'YOU_LABEL'=>array(
	    1=>array(
			'cn_name'=>'YOUDI WU',
			'cn_desc'=>'YOUDIWU 致力于把最完美品质的产品以最竞争力的价格呈现给顾客。 YOUDIWU 将会，也会是新时代FASTFASHION快时尚的代名词。客户是我们独 特商业模式 的核心，我们将始终努力，一直向前，提供给顾客最好的设计、服务 态度以及购物体验。'
		),
	    2=>array(
			'cn_name'=>'MY LIFE',
			'cn_desc'=>'YOUDIWU 致力于把最完美品质的产品以最竞争力的价格呈现给顾客。 YOUDIWU 将会，也会是新时代FASTFASHION快时尚的代名词。客户是我们独 特商业模式 的核心，我们将始终努力，一直向前，提供给顾客最好的设计、服务 态度以及购物体验。'
		),		
	    3=>array(
			'cn_name'=>'MY WORK',
			'cn_desc'=>'YOUDIWU 致力于把最完美品质的产品以最竞争力的价格呈现给顾客。 YOUDIWU 将会，也会是新时代FASTFASHION快时尚的代名词。客户是我们独 特商业模式 的核心，我们将始终努力，一直向前，提供给顾客最好的设计、服务 态度以及购物体验。'
		),				
	),

);
?>