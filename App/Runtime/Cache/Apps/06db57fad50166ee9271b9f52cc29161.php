<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo C('APPS_LOGIN_TITLE');?></title>
<link href="__J_UI__/themes/default/style.css" rel="stylesheet" type="text/css" />
<link href="__J_UI__/themes/css/core.css" rel="stylesheet" type="text/css" />
<link href="__J_UI__/themes/css/AppLogin.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="__PUBLIC__/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script src="__J_UI__/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="__J_UI__/js/jquery.validate.js" type="text/javascript"></script>
<script src="__J_UI__/js/dwz.min.js" type="text/javascript"></script>
<script src="__J_UI__/js/dwz.regional.zh.js" type="text/javascript"></script>

<script type="text/javascript">
//弹出框登录URL
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
    
      //登录背景随机
  var rand=Math.floor(Math.random()*5)+1;
  $('body')
    .css('background','url(<?php echo C('__IMG__');?>/login_bg' + rand + '.jpg) no-repeat')
    .css('background-size', '100%');
    
});
/**
*2014-10-4表单提交回调
*不依赖navTabId和dialogAjaxDone直接跳转URL
**/
function locationAjaxDone(json){
    DWZ.ajaxDone(json);
    if(json[DWZ.keys.statusCode]==DWZ.statusCode.ok){
        if(json.navTabId){
            if(json.forwardUrl){
                setTimeout(function(){
                        window.location.href=json.forwardUrl;           
                },1000);                        
            }   
        }else{
        var $pagerForm=$("#pagerForm",navTab.getCurrentPanel());
        var args=$pagerForm.size()>0?$pagerForm.serializeArray():{}
        navTabPageBreak(args,json.rel);}
        if("closeCurrent"==json.callbackType){
            setTimeout(function(){navTab.closeCurrentTab(json.navTabId);},100);}else if("forward"==json.callbackType){
            navTab.reload(json.forwardUrl);}else if("forwardConfirm"==json.callbackType){
            alertMsg.confirm(json.confirmMsg||DWZ.msg("forwardConfirmMsg"),{
            okCall:function(){
            navTab.reload(json.forwardUrl);},
            cancelCall:function(){
            navTab.closeCurrentTab(json.navTabId);}});
        }else{
            navTab.getCurrentPanel().find(":input[initValue]").each(function(){
            var initVal=$(this).attr("initValue");
            $(this).val(initVal);});
        }
    }
}
</script>
<style>
#splitBar{
    background:none !important;
    height:0 !important;
}    
</style>
</head>
 
<body>
<!--<div id="header"></div>不要LOGO图-->
                      
<!--可编辑区 开始-->                            
       <div id="main" >
        <form method="post" action="<?php echo U(APP_APPS.'/AppLogin/loginDo');?>" id="login" class="pageForm required-validate" onsubmit="return validateCallback(this,locationAjaxDone)">
           <div class="top">
            <input type="text" name="account" placeholder="用户名" class="required"></span>
            <span><input type="password" name="password" placeholder="密码"  class="required"></span>
            <input type="submit" name="submit" value="登录">
           </div> 
         </form>
      </div>
 <!--可编辑区 结束-->
    <div id="navTab" class="tabsPage"></div><!--提交邦定全局AJAX Load-->
<div id="footer"></div>
<p class="footer"><?php echo C('APPS_COPR');?></p> 
</body>
</html>