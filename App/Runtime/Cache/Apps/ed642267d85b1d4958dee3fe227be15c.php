<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo C('APPS_TITLE');?></title>

<link href="__J_UI__/themes/azure/style.css" rel="stylesheet" type="text/css" />
<link href="__J_UI__/themes/css/core.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="__PUBLIC__/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script src="__J_UI__/js/speedup.js" type="text/javascript"></script>
<script src="__J_UI__/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="__J_UI__/js/jquery.cookie.js" type="text/javascript"></script>
<script src="__J_UI__/js/jquery.validate.js" type="text/javascript"></script>
<script src="__J_UI__/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="__EDITOR__/xheditor/xheditor-1.2.1.min.js" type="text/javascript"></script><!--xheditor编辑器js-->  
<script src="__EDITOR__/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script> <!--xheditor编辑器中文语言-->  
<script src="__EDITOR__/kindeditor-apps-4.1.10/kindeditor.js" type="text/javascript"></script><!--kindeditor-4.1.10编辑器js-->  
<script src="__EDITOR__/kindeditor-apps-4.1.10/lang/zh_CN.js" type="text/javascript"></script><!--kindeditor-4.1.10编辑器中文语言--> 
 
<script src="__J_UI__/js/dwz.min.js" type="text/javascript"></script>

<script type="text/javascript" src="__J_UI__/chart/raphael.js"></script><!--报表-->
<script type="text/javascript" src="__J_UI__/chart/g.raphael.js"></script>
<script type="text/javascript" src="__J_UI__/chart/g.bar.js"></script>
<script type="text/javascript" src="__J_UI__/chart/g.line.js"></script>
<script type="text/javascript" src="__J_UI__/chart/g.pie.js"></script>
<script type="text/javascript" src="__J_UI__/chart/g.dot.js"></script><!--报表结束-->

<script src="__J_UI__/js/dwz.regional.zh.js" type="text/javascript"></script>
<script type="text/javascript">
function fleshVerify(){
    //重载验证码
    $('#verifyImg').attr("src", '__APP__/Public/verify/'+new Date().getTime());
}
//会话超时，弹出框登录URL
var adminLoginUrl="<?php echo U(APP_APPS.'/AppLogin/loginDialog','','');?>";

$(function(){
     DWZ.init("__J_UI__/dwz.frag.xml", {
        loginUrl:adminLoginUrl, loginTitle:"登录",    // 弹出登录对话框
        statusCode:{ok:200, error:300, timeout:301}, //【可选】
        pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
        debug:false,    // 调试模式 【true|false】
        callback:function(){
            initEnv();
            $("#themeList").theme({themeBase:"__J_UI__/themes"});
        }
    });
 
});

//JS全局变量和
var clsaaImgUrl="<?php echo C('__IMG__');?>";
var _root_='__ROOT__';
var closes='<?php echo L("Close");?>';
var Related="<?php echo U(MODULE_NAME.'/Publics/searchArticle','','');?>";//关联文章提交URL
</script>
<script src="__J_UI__/js/ajaxfileupload.js" type="text/javascript"></script><!--onchange上传事件-->
<script src="__J_UI__/js/editOen.js" type="text/javascript"></script><!--input 编辑-->
<script src="__J_UI__/js/fold.js" type="text/javascript"></script><!--折叠分类列表,表格形式-->
<script src="__J_UI__/js/related.js" type="text/javascript"></script><!--关联文章-->
<script src="__J_UI__/js/function.js" type="text/javascript"></script><!--我的JS涵数-->
<script src="__J_UI__/js/systemInfoNode.js" type="text/javascript"></script><!--系统新消息提示框-->
<script>
//getSystemInfo('生成提示框的节点','AJAX请求地址','提示音地址')  获取系统新消息
var music= "<?php echo C('ISINFOMUSIC');?>" ==1 ? '<?php echo C("__IMG__");?>/4331.wav' : '';
//getSystemInfo('body','<?php echo U(APP_APPS."/SystemInfo/index");?>',music,1);

$(function(){
	$('#audit').css('color','red').html('(<?php echo ($cessoce); ?>)');
	$('#notReviewed').css('color','red').html('(<?php echo ($membertex); ?>)');
});

/*验证会员是否重复*/
function chkUserData(_this){
	  var field=$(_this).attr('name');
	  var value=$(_this).val();
	  var id=$(_this).attr('toid');	  
	  if(!id) id=0;
	  $url="<?php echo U(APP_APPS.'/Member/memberSaveForm','','');?>/chk_user/ok/field/"+field+'/value/'+value+'/id/'+id;
	  var parent=$(_this).parent();
	  
	  $.ajax({
			 type:'get',
			 url:$url,
			 dataType:'json',
			 async: false,//使用同步的方式,true为异步方式
			 success:function(response,status,xhr){
				 if(response.statusCode==200){
					// alert(parent.children('.chk-user').length);
					 if(parent.children('.chk-user').length > 0) parent.children('.chk-user').remove();
					 $(_this).after('<span class="chk-user" style="position: absolute;display: block;width: 165px;height: 25px;padding: 0 3px;top: 5px;left: 300px;color:green;">'+response.message+'</span>');
				 }else{
					  if(parent.children('.chk-user').length > 0) parent.children('.chk-user').remove();
					 $(_this).after('<span class="chk-user" style="position: absolute;display: block;width: 165px;height: 25px;padding: 0 3px;top: 5px;left: 300px;color:red;">'+response.message+'</span>');
				 }
			  },
		  complete:function(){
			 $('#class').remove();
		  },
		   beforeSend:function(){
			  if(parent.children('.chk-user').length > 0) parent.children('.chk-user').remove();
			  $(_this).after('<span class="chk-user" style="position: absolute;display: block;width: 165px;height: 25px;padding: 0 3px;top: 5px;left: 300px;"><img src="<?php echo C('__IMG__');?>/13221814.gif"/></span>');
		  },				  
	   });	
}

/*框架提交成功后回调*/
function iframeReturn(json) {
	dialogAjaxOrder(json);
	 //$.pdialog.closeCurrent();
     //navTab.reload(url);
}
/**
 * dialog上的表单提交回调函数
 * 服务器转回navTabId，可以重新载入指定的navTab. statusCode=DWZ.statusCode.ok表示操作成功, 自动关闭当前dialog
 * form提交后返回json数据结构,json格式和navTabAjaxDone一致
 */
function dialogAjaxOrder(json){
      DWZ.ajaxDone(json);
      if (json.statusCode == DWZ.statusCode.ok){
            if (json.navTabId){
                  navTab.reload(json.forwardUrl, {}, json.navTabId);
            }
            $.pdialog.closeCurrent();
      }
}


</script>
<style type="text/css">
textarea{resize:none;}
#header .logo { background:url(<?php echo C('__IMG__');?>/logo.png) no-repeat;}
.progressBar { border:solid 2px #86a5ad; background:#FFF url(<?php echo C('__IMG__');?>/13221814.gif) no-repeat 10px 10px;}
table tr td{position:relative;}
</style>
</head>
<body scroll="no">
    <div id="layout">
        <div id="header">
            <div class="headerNav">
                <img src="<?php echo C('__IMG__');?>/logo.png" style="max-height:51px;position:relative;left:10px;top:2px;" />
                <ul class="nav">
                    <!--<li><a href="<?php echo U(MODULE_NAME.'/Index/index',array(C('VAR_LANGUAGE')=>'zh-cn'));?>"><?php echo L('Language');?></a></li> 
                    <li><a href="<?php echo U(MODULE_NAME.'/Index/index',array(C('VAR_LANGUAGE')=>'en-us'));?>">English</a></li> -->
                    <?php if(access('Root/delRuntime')): ?><li><a href="javascript:;" onclick="testConfirmMsg('<?php echo U(APP_APPS.'/Root/delRuntime/');?>','ajax','确定执行删除（<?php echo ($rundir); ?>）下的所有文件？')">删除运行缓存目录</a></li><?php endif; ?> 
                    <?php if(access('Index/cleHtml')): ?><li><a href="javascript:;" onclick="testConfirmMsg('<?php echo U(APP_APPS.'/Index/cleHtml/');?>','ajax','确定执行清空缓存操作？')">清空缓存</a></li><?php endif; ?>             
                    <li><a href="<?php echo U(APP_APPS.'/Main/index');?>" target="dialog" width="1000" height="700" rel="sysInfo" mask="true">系统消信息</a></li>
                    <?php if(access('User/password')): ?><li><a href="<?php echo U(APP_APPS.'/User/password');?>" target="dialog" mask="true">修改密码</a></li><?php endif; ?>
                    <li><a href="__ROOT__/" target="_blank" >打开首页</a></li>
                     <li><a href="javascript:;" onclick="testConfirmMsg('<?php echo U(APP_APPS.'/AppLogin/logout/');?>','loca','你真的退出后台管理吗？')">退出</a></li>
                </ul>
                <ul class="themeList" id="themeList">   
                    <li theme="azure" ><div class="selected">天蓝</div></li>                
                    <li theme="default"><div >蓝色</div></li>
                    <li theme="green"><div>绿色</div></li>
                    <li theme="purple"><div>紫色</div></li>
                    <li theme="silver"><div>银色</div></li>                   
                </ul>
            </div>
        </div>
        
        <div id="leftside">
            <div id="sidebar_s">
                <div class="collapse">
                    <div class="toggleCollapse"><div></div></div>
                </div>
            </div>
            
            <div id="sidebar">
                <div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
                	
<div class="accordion" fillSpace="sideBar">
       <!--
        <div class="accordionHeader">
            <h2><span>Folder</span>界面组件</h2>
        </div>
        -->
        <div class="accordionContent">
            <ul class="tree treeFolder">
            	
                <?php if(access('Category')): ?><!--内容管理模块 开始-->
                <li><span  target="navTab">内容管理</span>
                    <ul>
                        <?php echo tpl_left_menu('Category/cateList','文档分类列表');?>     
                        <?php echo tpl_left_menu('Article/articleList','全部文档列表');?>   
                        <?php echo tpl_left_menu('Article/notArticleList','等审核的文档列表');?>
                    </ul>
                </li><?php endif; ?>    <!--内容管理模块 结束-->                                                    

               
                <?php if(access('ExtendsArticle')): if(is_array($navs)): foreach($navs as $key=>$na): if(is_channel($na['files_id'],1) OR is_channel($na['files_id'],2) OR is_channel($na['files_id'],3)): ?><li><span  target="navTab"><?php echo ($na['files_name']); ?></span>
                        <ul>
                             <?php if(is_array($na['child'])): foreach($na['child'] as $key=>$n): if(is_channel($n['files_id'],1) OR is_channel($n['files_id'],2) OR is_channel($n['files_id'],3)): echo tpl_left_menu('ExtendsArticle/extendsArticleList',$n['files_name'],'files_id/'.$n['files_id'].'/article_type/'.$n['article_type'].'/files_type/'.$n['files_type']); endif; endforeach; endif; ?>                          
                        </ul>
                    </li><?php endif; endforeach; endif; endif; ?>                                           
                           
               
               
                <?php if(access('Comment')): ?><li><span  target="navTab">用户评论</span>
                        <ul>
                               <?php echo tpl_left_menu('Comment/commentList','评论列表');?>                      
                        </ul>
                    </li><?php endif; ?>                                           
                                                                    
               
               <!--供应商模块 开始-- >
                <?php if(access('Supplier')): ?><li><span  target="navTab">供应商管理</span>
                        <ul>
                             <?php echo tpl_left_menu('Supplier/isSuppList','已审核供应商列表');?>   
                             <?php echo tpl_left_menu('Supplier/notSuppList','未审核供应商列表');?>                  
                        </ul>
                    </li><?php endif; ?>
                <!--供应商模块 结束-->  
                 
              <!--商品管理 开始-->      
               <?php if(access('GoodsAttrType') OR access('Supplier') OR access('Goods') OR access('GoodsCategory') OR access('GoodsAppraise')): ?><li><span  target="navTab">商品管理</span>
                    <ul>
                        <?php echo tpl_left_menu('GoodsCategory/cateList','商品分类');?>    
                        <!--<?php echo tpl_left_menu('GoodsCategory/combination','商品分类检索集合');?> -->
					     <?php if(C('IS_GOODS_ATTRIBUTE')): echo tpl_left_menu('GoodsAttrType/attrTypeList','商品属性类型'); endif; ?>
						<!-- <?php echo tpl_left_menu('Supplier/suppList','供应商列表');?> -->            
                         
                         <?php if(C('IS_GOODS_BRAND')): echo tpl_left_menu('GoodsBrand/brandList','商品品牌'); endif; ?> 
						 <?php echo tpl_left_menu('Goods/goodsAllList','全部商品','action/goodsAllList/type/goodsAll');?>                               	
                         <?php echo tpl_left_menu('Goods/goodsShelvesList','已上架商品列表','action/goodsShelvesList/type/1');?>
                         <?php echo tpl_left_menu('Goods/goodsNotShelvesList','已下架商品列表','action/goodsNotShelvesList/type/0');?>
                         <?php echo tpl_left_menu('Goods/recycleBin','商品回收站','action/recycleBin/type/2');?>   
                         <!--<?php echo tpl_left_menu('GoodsAppraise/appList','商品评价');?>-->    
                         <?php if(C('IS_CONSUlTATION')): echo tpl_left_menu('Goods/consultation','商品咨询'); endif; ?>                      
                    </ul>
                </li><?php endif; ?>    <!--商品管理 结束-->      
				                
               <!--促销管理 开始-- >  
               <?php if(access('Promotion') OR access('GroupDeals') OR access('GoodsDiscount') OR access('GoodsDiscount')): ?><li><span  target="navTab">优惠活动管理</span>
                        <ul>
                            <?php if(C('IS_PROMOTION')): echo tpl_left_menu('Promotion/promotionList','促销商品列表'); endif; ?>
                             <?php echo tpl_left_menu('GroupDeals/groupDealsList','团购活动');?>   
                             <?php echo tpl_left_menu('GoodsDiscount/discountList','优惠活动');?>               
                             <?php echo tpl_left_menu('Bonus/bonusList','现金券');?>           
                        </ul>
                    </li><?php endif; ?>    
                <!--促销管理 结束-->    		 		                 

				<!--物流工具 模块-->
                <?php if(access('LogisticsTpl')): if(C('IS_GOODS_LOGISTICS')): ?><li><span  target="navTab">物流工具</span>
                            <ul>
                                 <?php echo tpl_left_menu('Shipping/disList','物流公司');?>
                                 <?php echo tpl_left_menu('LogisticsTpl/tplList','运费模板');?>                                     
                            </ul>
                        </li><?php endif; endif; ?>               
               <!--物流工具 模块结束-->    
                               
               <?php if(access('OrderInfo') OR access('Custom')): ?><!--订单管理 开始-->    
                <li><span  target="navTab">订单管理</span>
                    <ul>
                         <?php echo tpl_left_menu('OrderInfo/orderToday','今天订单列表');?>
                         <?php echo tpl_left_menu('OrderInfo/orderList','全部订单列表');?>
						 <?php echo tpl_left_menu('OrderInfo/payLogs','订单支付日志');?>
                         <!--<?php echo tpl_left_menu('Custom/customList','私人订制列表');?>-->
                       <!--  <?php echo tpl_left_menu('OrderInfo/discountList','退货单列表');?>              
                         <?php echo tpl_left_menu('OrderInfo/bonusList','添加订单');?>         -->        
                    </ul>
                </li><?php endif; ?>    <!--订单管理 结束-->         
                
                
                <?php if(access('Bankroll')): ?><li><span  target="navTab">资金管理</span>
                        <ul>
						     <?php echo tpl_left_menu('Bankroll/totalList','总帐户');?>  
							 <?php echo tpl_left_menu('Bankroll/totalExpenses','总帐户收支清单');?>  
                             <?php echo tpl_left_menu('Bankroll/supplierApplyList','供应商提现申请列表');?>    
                             <?php echo tpl_left_menu('Bankroll/memberApplyList','会员提现申请列表');?>   
							 <!--<?php echo tpl_left_menu('Bankroll/shopsExpenses','商家收支清单');?>  -->          
                        </ul>
                    </li><?php endif; ?> 
                			
                	                                                    
                <!--会员管理模块 开始-->    
                <?php if(access('Member') OR access('MemberGrade') OR access('Lnvite')): ?><li><span  target="navTab">会员管理</span>
                    <ul>
                        <!-- <?php echo tpl_left_menu('Lnvite/lnviteList','注册邀请码');?>-->

                          <?php if(C('IS_RANK_POINTS')): echo tpl_left_menu('MemberGrade/gradeList','会员等级'); endif; ?>
                          <?php echo tpl_left_menu('Member/memberList','会员列表');?>                  
                         <!--<?php echo tpl_left_menu('Service/index','会员发布需求');?>-->
                    </ul>
                </li><?php endif; ?>    <!--会员管理模块 结束-->         
                
                <!--邮件订阅 开始-->    
                <?php if(access('EmailSubscribe')): if(C('IS_EMAIL_SUBSCRIBE')): ?><li><span  target="navTab">邮件订阅</span>
                            <ul>        
                                  <?php echo tpl_left_menu('EmailSubscribe/emailList','邮件订阅列表');?>         
                                  <?php echo tpl_left_menu('EmailSubscribe/emailContentList','邮件订阅发送列表');?>     
                            </ul>
                        </li><?php endif; endif; ?>    <!--邮件订阅 结束-->                           
                
				<!--信息管理 模块-->
                <?php if(access('Words') OR access('Cooperation') OR access('InstationInfo') OR access('Recruit') OR access('WantTest')): ?><li><span  target="navTab">信息管理</span>
                        <ul>
                            <!-- <?php echo tpl_left_menu('Hiring/hiringList','招聘信息列表');?>-->  
                            <?php echo tpl_left_menu('Recruit/recList','招聘信息列表');?>   
                            <?php echo tpl_left_menu('Words/msgList','在线留言列表');?>     
                            <?php echo tpl_left_menu('WantTest/testList','在家试穿申请列表');?>     
						   <!--	 <?php echo tpl_left_menu('Cooperation/cooperationList','寻求合作列表');?> -->    
                            <?php if(C('IS_LETTER')): echo tpl_left_menu('InstationInfo/sendList','消息推送列表'); endif; ?>   
                        </ul>
                    </li><?php endif; ?>               
               <!--信息管理 模块结束-->

                <?php if(access('WebConfig') OR access('Nav') OR access('City') OR access('Ad') OR access('Links') OR access('ClearImg') OR access('Payment') OR access('Shipping') OR access('BelowLine')): ?><!--系统管理模块 开始-->
                <li><span  target="navTab">系统设置(System)</span>
                    <ul>
                           <?php echo tpl_left_menu('WebConfig/index','系统基本参数');?>
                           <?php echo tpl_left_menu('Payment/index','在线支付');?>
						   <?php echo tpl_left_menu('BelowLine/lineList','线下支付');?>                          
                           <?php echo tpl_left_menu('Floating/floatList','漂浮客服QQ/电话');?>
                           <?php echo tpl_left_menu('Nav/index','网站栏目列表');?>
                           <?php echo tpl_left_menu('City/index','地区列表');?>
                           <?php echo tpl_left_menu('Ad/index','广告管理');?>
                           <?php echo tpl_left_menu('Links/index','友情链接列表');?>
                           <?php echo tpl_left_menu('ClearImg/index','上传图片文件夹');?>
                    </ul>
                </li><?php endif; ?>  <!--系统管理模块 开始-->                                       
                
                <?php if(access('Rbac')): ?><!--RBAC管理模块 开始-->     
                <li><span  target="navTab">权限管理(RBAC)</span>
                    <ul>
                        <?php echo tpl_left_menu('User/index','用户列表');?>
                        <?php echo tpl_left_menu('Rbac/role','角色列表');?>
                        <?php echo tpl_left_menu('Rbac/node','节点列表');?>
                    </ul>
                </li><?php endif; ?><!--RBAC管理模块 开始-->     
                
                <!--报表管理模块 开始-->                   
                <?php if(access('Report')): ?><li><span  target="navTab">年/月统计报表</span>
                    <ul>
                        <?php echo tpl_left_menu('Report/shopsReport','商户注册统计报表');?>
                        <?php echo tpl_left_menu('Report/memberReport','会员注册统计报表');?>
                        <?php echo tpl_left_menu('Report/orderReport','订单统计报表');?>
                        <!--<?php echo tpl_left_menu('Report/vititReport','访问量统计报表');?>-->
                    </ul>
                </li><?php endif; ?><!--报表管理模块 开始-->                     

                <!--流量统计 开始           
                <?php if(access('VisitQuantity')): ?><li><span  target="navTab">流量统计</span>
                    <ul>
                        <?php echo tpl_left_menu('VisitQuantity/visitList','用户访问列表');?>
                         <?php echo tpl_left_menu('VisitQuantity/toVisitList','搜索引擎点击访问');?>
                        <?php echo tpl_left_menu('VisitQuantity/spiderGetWeb','搜索引擎爬取记录');?>
                    </ul>
                </li><?php endif; ?><!--流量统计 结束-->    
				                
            </ul>
        </div>
    
</div>

            </div>
        </div>

        <div id="container">
            <div id="navTab" class="tabsPage">
                <div class="tabsPageHeader">
                    <div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
                        <ul class="navTab-tab">
                            <li tabid="main" class="main"><a href="javascript:void(0)"><span><span class="home_icon">首页</span></span></a></li>
                        </ul>
                    </div>
                    <div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
                    <div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
                    <div class="tabsMore">more</div>
                </div>
                <ul class="tabsMoreList">
                    <li><a href="javascript:void(0)">首页</a></li>
                </ul>
                <div class="navTab-panel tabsPageContent layoutBox">
                    <div class="page unitBox">
                        <div class="accountInfo">
                            <div class="alertInfo">
                                <!--<h2 style="text-align: center;"><span>J-UI 1.4.6框架结合thinkPHP 3.1.2框架联合开发</span></h2>
                                <span style="display:block;height:25px;text-align: center;">2014.10</span>-->
								<h2 style="text-align: center;"><span>佛山市悦阁网络科技有限公司技术团队</span></h2>
                                <span style="display:block;height:25px;text-align: center;">2015.7 </span>
                            </div>
                            <div class="right">
                                <p id="theClock"></p>
                            </div>
                            <p><span><?php echo C('APPS_TITLE');?></span></p>
                            <p>登录用户:<strong><?php echo (session('username')); ?></strong> | 用户等级：
                                <?php if($_SESSION[C('SESSION_NAME_VAL')] == C('ADMIN_KEY')): ?>（超级管理员）
                                <?php else: ?>
                                     <?php if($_SESSION['grade']): ?>（<?php echo ($userGrade); ?>）<?php else: ?>（超级管理员）<?php endif; endif; ?>
                                | 登录IP：<?php echo ($_SESSION[C('SESSION_LOGINIP_VAL')]); ?></p>
                        </div>
                                <!--可编辑区 开始-->
                               <div class="panel" defH="800" style="width:100% !important;">
                                   
                       
                               <!--<h1>不可折叠面板1</h1>-->
                                <div >
                                    
                                   <table class="list" style="width:100%;height:95%;" layoutH="50">
                                            <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="row" ><td width="15%"><?php echo ($key); ?></td><td><?php echo ($v); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </table> 
                                                                        
                                   <!-- <table class="list" width="100%"  style="text-indent: 15px;background: #fff;">
                                        
                                       <thead>
                                            <tr><th width="80" colspan="4" style="height:30px;">装修公司最新情况</th></tr>
                                        </thead>                                     
                                        <tbody>
                                            <tr>
                                                <td style="height:50px;">待审核（<strong style="color:red;"><?php echo ($decoration["review"]); ?></strong>）</td>
                                                <td>今天加入（<strong style="color:red;"><?php echo ($decoration["Today"]); ?></strong>）</td>
                                                <td>昨天加入（<strong style="color:red;"><?php echo ($decoration["Yesterday"]); ?></strong>）</td>
                                                <td>最近一月加入（<strong style="color:red;"><?php echo ($decoration["January"]); ?></strong>）</td>
                                           </tr>
                                        </tbody>
                                        
                                        <thead>
                                            <tr><th width="80" colspan="4" style="height:30px;line-height: 30px;">会员发布招标</th></tr>
                                        </thead>                                       
                                        <tbody>
                                                <td style="height:50px;" colspan="2">今天发布（<strong style="color:red;"><?php echo ($tender["Today"]); ?></strong>）</td>
                                                <td>昨天发布（<strong style="color:red;"><?php echo ($tender["Yesterday"]); ?></strong>）</td>
                                                <td>最近一月发布（<strong style="color:red;"><?php echo ($tender["January"]); ?></strong>）</td>
                                        </tbody>
                                        
                                        <thead>
                                            <tr><th width="80" colspan="4" style="height:30px;line-height: 30px;">站内资讯</th></tr>
                                        </thead>                                       
                                        <tbody>
                                                <td style="height:50px;">待审核（<strong style="color:red;"><?php echo ($article["review"]); ?></strong>）</td>
                                                <td>今天发布（<strong style="color:red;"><?php echo ($article["Today"]); ?></strong>）</td>
                                                <td>昨天发布（<strong style="color:red;"><?php echo ($article["Yesterday"]); ?></strong>）</td>
                                                <td>最近一月发布（<strong style="color:red;"><?php echo ($article["January"]); ?></strong>）</td>
                                        </tbody>        
                                        
                                        <thead>
                                            <tr><th width="80" colspan="4" style="height:30px;line-height: 30px;">论坛贴子</th></tr>
                                        </thead>                                       
                                        <tbody>
                                                <td style="height:50px;">待审核（<strong style="color:red;"><?php echo ($bbs["review"]); ?></strong>）</td>
                                                <td>今天发布（<strong style="color:red;"><?php echo ($bbs["Today"]); ?></strong>）</td>
                                                <td>昨天发布（<strong style="color:red;"><?php echo ($bbs["Yesterday"]); ?></strong>）</td>
                                                <td>最近一月发布（<strong style="color:red;"><?php echo ($bbs["January"]); ?></strong>）</td>
                                        </tbody>                                                                                  

                                        <thead>
                                            <tr><th width="80" colspan="4" style="height:30px;line-height: 30px;">流量统计</th></tr>
                                        </thead>                                       
                                        <tbody>
                                            <tr>
                                                <td style="height:50px;"colspan="2">今天（<strong style="color:red;"><?php echo ($visit["Today"]); ?></strong>）</td>
                                                <td>昨天（<strong style="color:red;"><?php echo ($visit["Yesterday"]); ?></strong>）</td>
                                                <td>最近一月（<strong style="color:red;"><?php echo ($visit["January"]); ?></strong>）</td>
                                           </tr>
                                        </tbody>                                                                                                                                                                     
                                    </table>-->
                                    <div style="width:100%;height:30px;background:#fff;border-bottom-left-radius: 4px;border-bottom-right-radius: 4px;"></div>
                                </div>
                              </div>
                            <!--可编辑区 结束-->
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div id="footer"><?php echo C('APPS_COPR');?></div>    
</body>
</html>