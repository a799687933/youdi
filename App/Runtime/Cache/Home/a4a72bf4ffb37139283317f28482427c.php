<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title><?php echo isL(L('RetrievePassword'),'重设密码');?> | YOUDI WU 中国</title>
<link rel="stylesheet" href="__HOME__/css/global.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<link rel="stylesheet" href="__HOME__/css/essentials.min.css"/>
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<!--[if IE]>
		<script src="__HOME__/js/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
<div class="box">
<!--<link rel="Stylesheet" href="__PLUGINS__/ValidationEngine/Css/validationEngine.jquery.css"/>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/jquery.validationEngine.min.js"></script>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/jquery.validationEngine-<?php echo getLang();?>.js"></script>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/myValidationEngine.js"></script>-->
<script type="text/javascript" src="__HOME__/js/jquery.bxslider2.js"></script>
<script type="text/javascript" src="__PLUGINS__/formValidation/formValidation.js"></script>
<div class="header bgfff">


<?php if(is_mobile()): ?><div class="headertb2">
  <a href="__ROOT__/" class="logo"></a>

  
  <a href="<?php echo U('Modular/needHelp',array('html'=>$files[7][C('B_1')]['id']));?>" class="fr lxr" style="display:inline-block; margin-top:-7px;">
  
      	<span class="icon-contact glyphs f20  ml5"></span>
    </a>
    <b class="fr col000 ml10" style="margin-top:-7px"><?php echo isL(L('NeedHelp'),'需要帮助？');?></b>
  
  <div class="cb"></div>
 </div><!----> 
<?php else: ?>
 
 <div class="headertb2">
  <a href="__ROOT__/" class="logo"></a>

  
  <a href="<?php echo U('Modular/needHelp',array('html'=>$files[7][C('B_1')]['id']));?>" class="fr lxr" style="display:inline-block; line-height:28px;">
  
      	<span class="icon-contact glyphs f20  ml10"></span>
    </a>
    <b class="fr col000 ml10"><?php echo isL(L('NeedHelp'),'需要帮助？');?></b>
  <div class="guoj fr">
  <?php if($_COOKIE['think_language'] == 'en-us'): ?><h1 class="icon1c col222">English</h1>   
       <!--<div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'zh-cn'));?>" class="icon1">CHINA</a>
       </div>  -->
  <?php else: ?>
       <h1 class="icon1 col222">CHINA</h1>   
       <!--<div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'en-us'));?>" class="icon1c">English</a>
       </div>  --><?php endif; ?>  
  </div>
  

  <div class="cb"></div>
 </div><!----><?php endif; ?>
 </div><!--header end-->
 <div class="cb pv25"></div>
 
 
<script type="text/javascript">
 /* $(function(){


 var url="<?php echo U('Forget/checks','','');?>";
   myValidationEngine('ajaxUser',url,'my=data','<?php echo isL(L("WithoutThisUser"),"不存在此用户");?>','<?php echo isL(L("Correct"),"验证信息通过");?>','<?php echo isL(L("JustAmoment"),"正在验证，请稍等...");?>','');     
   valinit('.forget-form','get','',true,'bottomLeft'); 
});    */
</script>
<!--content begin-->
 <h1 class="title"><div class="wrap"><span><?php echo isL(L('RetrievePassword'),'重设密码');?></span></div></h1>
 <div class="app">
<div class="csmmwrap">
          <div class="csmm">
           <p class="tj pb30"><?php echo replace_isl('SentAnEmail','我们已将电邮发送至：%d 邮件中包含一个可以重设密码的链接',$_GET['email']);?></p>
           <h1 style="font-weight:bold;font-size:14px;"><?php echo isL(L('VeryImportant'),'非常重要');?>：</h1>
          <p class="tj pb30"><?php echo isL(L('ByCutomatic'),'如果你未收到我们的邮件，请检查你的垃圾邮箱，有可以你的电子邮件服务提供商设置的垃圾邮件过滤器将未添加在你的地址簿中的寄件方自动过滤所造成。');?></p> 
          <p class="tj "><a href="__ROOT__/" class="qux2 mr10"><?php echo isL(L('ClickHere'),'点击此处');?></a><?php echo isL(L('ContinueBuy'),'继续选购');?> </p>
 </div><!--zyjh end-->
 </div>
 
<!--content endh-->

<div class="footer2"><div class="footerm">
<span class="footerml">
 <p>不是 <?php echo getUserC('SE_NICKNAME');?>？</p>
 <p><a href="<?php echo U('Login/exitLogin');?>" class="colff"><?php echo isL(L('Exit'),'退出');?></a></p>
</span>
<span style="display:inline-block;padding:16px 0 15px;" class="fr lh20 colfff f13">
<b style="vertical-align:middle"><?php echo isL(L('NeedHelp'),'需要帮助？');?></b>
<a href="/Modular/needHelp" class="xinf">
      	<span class="icon-contact glyphs f20  ml10" style="font-size:22px !important"></span>
    </a>
</span>
<div class="cb"></div>
</div></div>


</div><!--box end-->
<!---->

<div class="navmin"><div class="navminm">
  <a href="__ROOT__/" class="navlink2" >HOME</a>

  <a href="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>desc,'text'=>'new'));?>" class="navlink2"><?php echo isL(L('NewArrivals'),'最新发布');?></a>
  <a href="<?php echo U('Category/index');?>" class="navlink2"><?php echo isL(L('Categories'),'产品分类');?></a>
  <a href="<?php echo U('Modular/singleProduct',array('html'=>$files[3][C('A_5')]['id']));?>" class="navlink2"><?php echo ($files[3][C('A_5')]['titletext']); ?></a>
  <a href="<?php echo U('Modular/lookbook');?>" class="navlink2">LOOKBOOK</a>
  <a href="<?php echo U('Modular/brandImage');?>" class="navlink2"><?php echo ($files[3][C('A_8')]['titletext']); ?></a>
  <a href="<?php echo U('Modular/enjoy',array('html'=>$files[3][C('A_6')]['id']));?>" class="navlink2"><?php echo isL(L('CustomerRespect'),'客户尊享');?></a>
  <a href="<?php echo U('Article/index');?>" class="navlink2"><?php echo isL(L('News'),'YOUDI WU最新资讯');?></a>
  <a href="<?php echo U('Modular/opportunity',array('html'=>$files[3][C('A_2')]['id']));?>" class="navlink2"><?php echo isL(L('CareerOpportunities'),'职业机会');?></a>
  <div class="pt20 pl10 pr10 pb10">
  <?php if(is_login()): ?><a href="<?php echo U('Member/index');?>" class="color-white mr10 glyphs b ff-icon-user js-details-account f14 fl" style="line-height:22px;"><span class="js-details-account-name">ltmisa lt</span></a>
        <a href="<?php echo U('Login/exitLogin');?>" class="color-medium-grey fl" style=" line-height:25px;">退出</a>  
        <div class="cb"></div>  
       <!--<a href="<?php echo U('Login/exitLogin');?>" class="dl"> <?php echo getUserC('SE_NICKNAME');?> <?php echo isL(L('Exit'),'退出');?></a>-->
  <?php else: ?>
      <a href="<?php echo U('Login/index');?>" class="dl"><?php echo isL(L('Login'),'登录');?></a>
      <a href="<?php echo U('Login/index',array('reg'=>1));?>" class="dl"><?php echo isL(L('Registered'),'注册');?></a><?php endif; ?>
  <!--<a href="<?php echo U('Member/index');?>" class="dl"><?php echo isL(L('MyAccount'),'我的账户');?></a>-->
  </div>
</div></div>
<!----> 
<!---->
<div class="ssmin">
    <div class="ph10">
    <span class="close ssminclose"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
    <div class="cb pv25"></div>
    
    <form action="<?php echo U('Category/index');?>">
     <input type="text" name="keywords" value="" placeholder="<?php echo isL(L('InputKey'),'请输入关键字');?>" class="ssmin_input"/>
     </form>
     <p class="lh30 f14 col000"><b><?php echo isL(L('PressEnterSearch'),'请按Enter键搜索');?></b></p>
    </div>
</div>
<!---->
<script type="text/javascript" src="__HOME__/js/MYscroll.js"></script>
<script type="text/javascript" src="__HOME__/js/MYscroll2.js"></script>
<script type="text/javascript" src="__HOME__/js/common.js"></script>
</body>
</html>