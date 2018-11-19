<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title><?php echo isL(L('LoginAndRegistration'),'登录和注册');?> | YOUDI WU 中国</title>
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
<link rel="stylesheet" href="__HOME__/css/global.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<link rel="stylesheet" href="__HOME__/css/essentials.min.css"/>
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<script>

 $(function(){
	 /****/
		 
		 $(".iconmm1").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType("psw","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType("psw","password");
				 })
				function changeType(t,action){
			var p = document.getElementById("psw");
			p.type=action;
			}  
				 
				$(".iconmm2").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType2("password","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType2("password","password");
				 }) 

            function changeType2(t,action){
			var p2 = document.getElementById("password");
			p2.type=action;
			} 
			
			$(".iconmm3").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType3("not_password","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType3("not_password","password");
				 }) 

            function changeType3(t,action){
			var p3 = document.getElementById("not_password");
			p3.type=action;
			} 
			

	$(".mmpsw").focus(function(){
     $(this).parent(".mmbox").addClass("mmboxnow")
    });
   
  function stopPropagation(e) {
		  if (e.stopPropagation) 
			  e.stopPropagation();
		  else 
			  e.cancelBubble = true;
	  }

	  $(document).bind('click',function(){
		  $('.mmbox').removeClass("mmboxnow")
	  });

	   $('.mmbox').bind('click',function(e){
		  stopPropagation(e);
	  }); 
	/****/  
	 })
</script>

<!--[if IE]>
		<script src="__HOME__/js/html5shiv.min.js"></script> 
<![endif]-->
<style>
 a.ttc11:hover,a.ttc12:hover{ text-decoration:underline}

</style>
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
$(function(){
   //登录	   
   formInit('.login',true,true,true,true,true);
   //注册
   formInit('#reg-form',true,true,true,true,true);
});
</script>
<!--content begin-->
<div class="dlzc"><div class="dlzcm">
 <h1 class="dlzct"><?php echo isL(L('Login'),'登录');?></h1>
 <div class="dlzctmin">
   <span class="<?php if(!$_GET['reg']){ ?>now<?php } ?> dldj"><?php echo isL(L('Login'),'登录');?></span>
   <span class="zcdj <?php if($_GET['reg']){ ?>now<?php } ?>"><?php echo isL(L('Registered'),'注册');?></span>
 </div>
 <div class="dlzcboxl <?php if($_GET['reg']){ ?>dlzcn<?php } ?>"><div class="dlzcboxlm">
 
  <h1 class="dlzcbt" style="font-weight:normal; line-height:30px; padding-bottom:40px"><?php echo isL(L('RegisteredUsers'),'已注册用户');?></h1>
  
  <p style="font-weight:bold; font-size:12px; padding-bottom:2px; line-height:22px; color:#555;"><?php echo isL(L('Email'),'电子邮件');?> *</p>
  <form action="<?php echo U('Login/loginDo');?>" id="login-form" class="login">
      <input type="hidden" name="returnUrl" value="<?php echo ($returnUrl); ?>" />
      <div class="pr">
      <input type="text" name="user_name" class="dlzc_input validate[custom[email],required]" info-text="user-text" error-info="<?php echo isL(L('EmailFormatError'),'电子邮件格式错误');?>"/>
       <p class="tsy user-text" >请输入电子邮件</p>
       </div>
      <p style="font-weight:bold; font-size:12px; color:#555; margin-top:20px;padding-bottom:2px; line-height:22px; box-sizing:border-box"><?php echo isL(L('Password'),'密码');?> *</p>
      
      <div style="position:relative" class="mmbox">
      <input type="password" id="psw"  name="password" min="6" max="300" class="mm_input validate[required] mmpsw" info-text="pwd-text" error-info="<?php echo isL(L('EnterYourPassword'),'请输入您的密码');?>" />
      <span class="glyphs icon-show mmyj iconmm1"></span>
      <p class="tsy pwd-text login-submit">请输入您的密码</p> 
      </div>
      
      <div class="pt20" style="line-height:22px; margin-bottom:10px;">
          <a href="<?php echo U('/Forget/emailPassword');?>" class="col222" style="border-bottom:1px solid #222"><?php echo isL(L('ForgePassword'),'忘记密码？');?></a>
          <span class="fr" style="position:relative; padding-left:25px;">
          <input type="checkbox" name="remember" id="remember" style="display:none" class="vam" value="1" checked/>
          <label class="ffInput" for="remember">
          <span class="ffInput__checkbox ffInput__checkbox1"></span>
          <span class="pl5"><?php echo isL(L('RememberLogin'),'保持登录状态');?></span>
          </label>
          </span>
          <div class="cb"></div>
      </div>
      <input type="hidden" name="submits" value="login-submit" /> 
     
      <div class="cb"></div>
      <div class="dlzc_btnwrap1"><input type="submit" id="login-submit" msg="<?php echo isL(L('PleaseLater'),'提交中...');?>" value="<?php echo isL(L('Login'),'登录');?>" class="dlzc_btn" style=" width:100%; position:relative; right:0; bottom:0" info-text="pwd-text"/><span class="glyphs icon-thinArrow" style="position:absolute; right:10px; top:0; line-height:38px; color:#fff; font-size:1.25em"></span>
      <p class="hong submit-text" style="position:absolute; left:20px; top:40px; display:none">成功或失败提示</p> 
      </div>
       <div class="cb"></div>
     
  </form> 
 </div></div><!--dlzcbox end-->
 <div class="dlzcboxr <?php if(!$_GET['reg']){ ?>dlzcn<?php } ?>"><div class="dlzcboxlm" >
 
 <h1 class="dlzcbt" style="padding-bottom:0px;"><b><?php echo isL(L('NewUser'),'新用户');?></b></h1>
 <p class="f12 lh20 pb20 col555"><b>* <?php echo isL(L('Required'),'必填项');?></b></p>
 
 <form action="<?php echo U('Register/regDo');?>" id="reg-form">
     <input type="hidden" name="recommend" value="" />
     <p style="font-weight:bold; font-size:12px; padding-bottom:2px; line-height:20px; color:#555">姓名 *</p>
      <div class="pr">
      <!--<input type="text" name="full_name" class="dlzc_input validate[required]" info-text="get-user0-text" error-info="<?php echo isL(L('EnterYourName'),'请输入您的姓名');?>" error-other="<?php echo isL(L('Names5_15'),'姓名在5到15个字符内');?>" onKeyUp="value=value.replace(/[^\a-\z\A-\Z-\@\.\s]/g,'')" onChange="value=value.replace(/[^\a-\z\A-\Z-\@\.\s]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z-\@\.\s]/g,'')" oncontextmenu ="value=value.replace(/[^\a-\z\A-\Z-\@\.\s]/g,'')" min="5" max="15" maxlength="15" autocomplete="off"/>-->
<input type="text" name="full_name" class="dlzc_input required letter" info-text="get-user0-text" error-info="<?php echo isL(L('EnterYourName'),'请输入您的姓名');?>" error-other="<?php echo isL(L('Names5_15'),'请使用英文或拼音填写');?>"  maxlength="15" autocomplete="off" placeholder="请用拼音填写"/>      
      <p class="tsy get-user0-text">请输入您的姓名</p>
      </div>
      <p style="font-weight:bold; font-size:12px; padding-bottom:2px; line-height:22px; color:#555; margin-top:20px"><?php echo isL(L('Email'),'电邮地址');?> *</p>
      <div class="pr pb20"><input type="text" name="user_name" class="dlzc_input  required email" info-text="get-user-text" error-info="<?php echo isL(L('EnterYourEmail'),'请输入您的电邮地址');?>" error-other="<?php echo isL(L('EmailFormatError'),'请输入正确的电邮地址');?>"/>
      <p class="tsy  get-user-text reg-submit">请输入您的电邮地址</p>
      </div>
     
      <div class="w45 fl">
       <p style="font-weight:bold; font-size:12px; padding-bottom:2px; line-height:22px; color:#555"><?php echo isL(L('Password'),'密码');?> *</p>
       
       <div style="position:relative" class="mmbox">
      <input type="password" name="password" id="password" min="6" max="300"  class="mm_input  mmpsw required" info-text="get-pwd-text" error-info="<?php echo isL(L('EnterYourPassword'),'请输入您的密码');?>" error-other="<?php echo isL(L('PleaseEnterApassword'),'密码长度必须介于6到300个字母。');?>"/>
      <span class="glyphs icon-show mmyj iconmm2"></span>
      <p class="tsy get-pwd-text">密码长度必须介于6到300个字母。</p>
      </div>
      <!--
       <div class="pr">
      <input type="password" name="password" id="password" min="6" max="300" class="dlzc_input dlzcmm_input validate[required]" info-text="get-pwd-text" error-info="<?php echo isL(L('PleaseEnterApassword'),'请输入密码');?>"/>
      <span class="mmyj glyphs icon-show iconmm"></span>
      <p class="hong get-pwd-text" style=" left:0; top:35px;display:none">密码长度必须介于6到300个字母。</p>
      </div>-->
      
      </div>
       <div class="phcb pb20"></div>
      <div class="w45 fr">
       <p style="font-weight:bold; font-size:12px; padding-bottom:2px; line-height:22px; color:#555"><?php echo isL(L('ConfirmPassword'),'确认密码');?> *</p>
       
       <div style="position:relative" class="mmbox">
      <input type="password" name="not_password" id="not_password"  class="mm_input  mmpsw required" equal-input="#password" info-text="get-pwdc-text" error-info="<?php echo isL(L('PleaseConfirmYourPassword'),'两次输入的密码不一致, 请重新输入');?>"/>
      <span class="glyphs icon-show mmyj iconmm3"></span>
      <p class="tsy get-pwdc-text">两次输入的密码不一致, 请重新输入。</p>
      </div>
       
       <!--<div class="pr">
      <input type="password" name="not_password" id="not_password" class="dlzc_input dlzcmm_input validate[required,equals[password]]" equal-input="#password" info-text="get-pwdc-text" error-info="<?php echo isL(L('PleaseConfirmYourPassword'),'请确认密码');?>"/>
      <span class="mmyj glyphs icon-show"></span>
      <p class="hong get-pwdc-text" style=" left:0; top:35px;display:none">两次输入的密码不一致, 请重新输入</p>
      </div>-->
      
      </div>
      <div class="cb"></div>
      <p class="pt10 pb10 f12 col666"><?php echo web_root(isL(L('OnceRegistereds'),'一旦注册即代表您同意遵守我们的 条件与条款 以及 隐私与Cookie政策'));?></p>
      <p style="position:relative; padding-left:20px;">
          <input type="checkbox" name="subs" value="1" class="vam" id="xiw" checked style=" display:none"/>
          <label class="ffInput" for="xiw">
          <span class="ffInput__checkbox" style="top:5.5px;"></span>
          <span class="pl5 vam" style="font-weight:bold; display:inline-block">
            <?php echo isL(L('GetInformationAbout'),'我希望以电子报及邮件形式获得有关 YOUDIWU 最新动态的信息。');?>
          </span>
          </label>
          
       </p>
       <input type="hidden" name="submits" value="reg-submit" /> 
     
      
      <div class="dlzc_btnwrap1">
      <input type="submit" id="reg-submit" info-text="submit1-text" msg="<?php echo isL(L('PleaseLater'),'提交中...');?>" value="<?php echo isL(L('Registered'),'注册');?>" class="dlzc_btn" style=" width:100%; position:relative; right:0; bottom:0"/><span class="glyphs icon-thinArrow" style="position:absolute; right:10px; top:0;line-height:38px; color:#fff; font-size:1.25em"></span>
      <p class="hong submit1-text" style="position:absolute; left:20px; top:40px; display:none;border:none !imporatnt;">成功或失败提示</p> 
      </div>
  </form>
 </div></div><!--dlzcbox end-->
 <div class="cb pt20"></div>
 <!--<div class="pv20 tl">
     <span class="fhdb"><span class="icon19">&nbsp;</span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span>
 </div>-->
</div></div><!--dlzc end-->
 
<!--content end-->
<div class="cb pv30"></div>

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


<script type="text/javascript">//onBlurInipts()</script>
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