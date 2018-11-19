<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <?php if(isset($message)): ?><title>操作成功--跳转提示</title>
<?php else: ?>
<title>操作失败--跳转提示</title><?php endif; ?>
<script type="text/javascript">

window._ROOT_="__ROOT__";
window.__COM__="__COMMON__";
window.isConfirm="<?php echo isL(L('Confirm'),'确定');?>";
window.isCancel="<?php echo isL(L('Cancel'),'取消');?>";
window.pleaseLater="<?php echo isL(L('PleaseLater'),'提交中...');?>";
window.Login="<?php echo isL(L('Login'),'登录');?>";
window.PleaseUserName="<?php echo isL(L('PleaseUserName'),'用户帐号');?>";
window.PleaseEnterApassword="<?php echo isL(L('PleaseEnterApassword'),'用户密码');?>";
window.ForgePassword="<?php echo isL(L('ForgePassword'),'忘记密码？');?>";
window.RememberPassword="<?php echo isL(L('RememberPassword'),'记住密码');?>";
window.AccountEmpty="<?php echo isL(L('AccountEmpty'),'用户帐号不能为空');?>";		
window.UserPasswordCanNot="<?php echo isL(L('UserPasswordCanNot'),'用户密码不能为空');?>";
window.LoginSuccessTurning="<?php echo isL(L('LoginSuccessTurning'),'登录成功');?>";
window.ResourceLoading="<?php echo isL(L('ResourceLoading'),'正在玩命加载...');?>";
window.ThirdPartyLogin="<?php echo isL(L('ThirdPartyLogin'),'合作网站登录帐号');?>";
window.DisplayPart="<?php echo isL(L('DisplayPart'),'显示较少内容');?>";
window.ReadMore="<?php echo isL(L('ReadMore'),'继续阅读');?>";
window.Loading="<?php echo isL(L('Loading'),'正在加载...');?>";
window.LoadingCompleted="<?php echo isL(L('LoadingCompleted'),'已全部加载完毕');?>";
window.MaximumPossibleUpload="<?php echo isL(L('MaximumPossibleUpload'),'最大可上传10张图片');?>";
</script>
<script type="text/javascript" src="__COMMON__/Js/jquery-1.7.2.min.js"></script>
<link rel="stylesheet" href="__COMMON__/Css/alert.css" />
<script type="text/javascript" src="__COMMON__/Js/div-dialog2.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<link rel="stylesheet" href="__HOME__/css/global.css" />
<link rel="stylesheet" href="__HOME__/css/essentials.min.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<style>
 .bg{background: #000;
    height: 100%;
    left: 0;
    opacity: 0.5;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 200;}
 .box{background: #fff;
    height:210px; padding:10px 20px; box-sizing:border-box;
    left: 50%; margin:-145px 0 0 -10rem;
    position: fixed;
    top: 50%;
    width:20rem; 
    z-index: 201;}	
.box h1{border-bottom: 1px solid #ededed;
    color: #000; font-weight:normal;
    font-size: 18px;
    height: 40px;
    line-height: 40px;}	
.close{ color: #000;
    cursor: pointer;
    display: inline-block;
    float: right;
    font-size: 20px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    width: 40px;}
.boxm{ padding:20px 0}		
</style>
<script>
 $(function(){
	 $(".close").click(function(){
		 $(".bg").fadeOut()
		 $(".box").slideUp()
		 })
	 })
</script>
</head>
<body style="padding:0;margin:0;">
<div class="bg"></div>
<?php if(isset($message)): ?><div class="box">
     <h1><?php echo isL(L("SuccessTips"),"成功提示");?><span class="close"><span class="glyphs icon-close" style="line-height:35px;"></span></span></h1>
     <div class="boxm">
      <p style="text-align:center; font-size:16px; padding-bottom:20px;"><?php echo ($message); ?></p>
      <div style="position:relative; float:right; width:50%;margin-top:30px;">
      <?php if($jumpUrl && strpos($jumpUrl,'(-1)') ===false){ ?>
        <input id="pwd-submit"  value="<?php echo isL(L('Confirm'),'确定');?>" onclick="window.location.href='<?php echo ($jumpUrl); ?>';" class="tjdzbtn" style=" width:100%; float:none" type="button">
     <?php }else{ ?> 
        <input id="pwd-submit"  value="<?php echo isL(L('Confirm'),'确定');?>" onclick="history.go(-1)" class="tjdzbtn" style=" width:100%; float:none" type="button">
     <?php } ?>
      <span class="glyphs icon-thinArrow" style="position:absolute; right:10px; line-height:38px; color:#fff; font-size:1.25em"></span>
      </div>
      <div class="cb"></div>
     </div><!--boxm end-->
<?php else: ?>
    <div class="box">
     <h1><?php echo isL(L("ErrorTips"),"失败提示");?>
     <span class="close"><span class="glyphs icon-close" style="line-height:35px;"></span></span>
     </h1>
     <div class="boxm">
      <p style="text-align:center; font-size:16px; padding-bottom:20px;"><?php echo ($error); ?></p>
      <div style="position:relative; float:right; width:50%;margin-top:30px;">
      <?php if($jumpUrl && strpos($jumpUrl,'(-1)') ===false){ ?>
        <input id="pwd-submit"  value="<?php echo isL(L('Confirm'),'确定');?>" onclick="window.location.href='<?php echo ($jumpUrl); ?>';" class="tjdzbtn" style=" width:100%; float:none" type="button">
     <?php }else{ ?> 
        <input id="pwd-submit"  value="<?php echo isL(L('Confirm'),'确定');?>" onclick="history.go(-1)" class="tjdzbtn" style=" width:100%; float:none" type="button">
     <?php } ?>
      <span class="glyphs icon-thinArrow" style="position:absolute; right:10px; line-height:38px; color:#fff; font-size:1.25em"></span>
      </div>
      <div class="cb"></div>
     </div><!--boxm end--><?php endif; ?> 
</div>
</body>
</html>