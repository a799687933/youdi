<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title><?php echo ($result['titletext']); ?> | YOUDI WU 中国</title>
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
<link rel="stylesheet" href="__HOME__/css/flexslider.css" />
<link rel="stylesheet" href="__HOME__/css/font-awesome.min.css" />
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<script type="text/javascript" src="__HOME__/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="__COMMON__/Js/clickMoerPage.js"></script>
<!--<script src="__PLUGINS__/kindeditor-4.1.10/kindeditor.js" type="text/javascript"></script> 
<script src="__PLUGINS__/kindeditor-4.1.10/lang/<?php echo getLang();?>.js" type="text/javascript"></script>

<script type="text/javascript">

window.items=[
            'emoticons'
]    
</script>
<script src="__PLUGINS__/kindeditor-4.1.10/kindelitor-config.js" type="text/javascript"></script> -->

<script type="text/javascript">
$(document).ready(function() {	
$('.bxslider').bxSlider({
	   controls: true,
	   pager: null,
	   auto:true
	  });	
});
</script>
<!--[if IE]>
		<script src="js/html5shiv.min.js"></script>
<![endif]-->
<script defer src="__HOME__/js/flexslider.js"></script> 
<script>
 $(function(){
      $('.flexslider').flexslider({
        animation: "slide",directionNav: true,slideshow:false,animationSpeed:1000
     
      });
	  $(".flexslider").hover(function(){
		  $(this).toggleClass("flexslidernow")
     });

	
	/*$('.onaclick').on('click','.col2ab',function(){
		 var onaclick=$(this).parents('.xspjb');									 
		 if($(this).is('.pjbbtxtdj')){
			 $(this).hide();
			 onaclick.find('.pjbbtxtdj2').show();
			 onaclick.find('.pjbbtxt').css('height','auto');
		 }else if($(this).is('.pjbbtxtdj2')){
			 $(this).hide();
			 onaclick.find('.pjbbtxtdj').show();
			 onaclick.find('.pjbbtxt').css('height','24px');
		}
	});*/
	
	
	var aurl="<?php echo U('Article/index');?>";
	$.ajax({
		 type:'get',
		 dataType: "text",
		 url:aurl,
		 success:function(response,status,xhr){
              $('#article-list').html(response);
		  },
		  complete:function(){
		  },
		   beforeSend:function(){
		  },         
		 error:function(xhr,errorText,errorType){
			 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
		 }
	});   	

});

$(function(){
	$(".rating-static-module label").click(function(){
		$(this).find('span').addClass("Hui-iconfont-cang-selected")
		$(this).nextAll("label").find('span').removeClass("Hui-iconfont-cang-selected")
		$(this).prevAll("label").find('span').addClass("Hui-iconfont-cang-selected")
		$('input[name="score"]').val($(this).index() + 1);
		})
	//弹出表情
	$('body').on('click','.emotion',function(){
	     var show_face=$(this).find('.show_face2');
		 if(show_face.css('display')=='none'){
			 $(this).find('.show_face2').show();
		 }else{
			 $(this).find('.show_face2').hide();
		 }
	});
	//表情付值
	$('body').on('click','.show_face2 .face-item',function(){
	    var _this=$(this);
		var c_name=_this.attr('set_value');
		var face=_this.attr('data-ubb');
		var centent=$('.'+c_name).val();
		$('.'+c_name).val(centent+face);
		//$('.up-arrow1,.up-arrow2').hide();
	});
});

/*点赞*/
var isForm6=false;
function praise(_this){	
	var num=parseInt($(_this).find('.paraise_num').text());
	var url="<?php echo U('Article/clickLike',array('id'=>$result['id']));?>";
	if(isForm6) return;
	isForm6=true;		
	$.getJSON(url,{},function(res){
		 isForm6=false;							  
		 res=res[0];					  
	     if(!res[1]){
			 if(res[3]) window.location.href=res[3];
			 return;
		}
		if(res[5] == 'save'){
			$(_this).find('.iconstyle').removeClass('commentlr').addClass('col999');	
			num--;
			$(_this).find('.paraise_num').text(num);		
		}else{
			$(_this).find('.iconstyle').removeClass('col999').addClass('commentlr');	
			num++;
			$(_this).find('.paraise_num').text(num);			
		}
	});    
}

//列表评论点赞
var isForm7=false;
function praiseList(_this,id,user_id){
	if(isForm7) return;
	isForm7=true;	
    var li=$(_this).parents('li');
	var num=parseInt(li.find('.list_nums').text());
	var url="<?php echo sign_url(array('table_num'=>1),U('Article/clickLike'));?>&id="+id+"&user_id="+user_id;
	$.getJSON(url,{},function(res){
		 isForm7=false;							  
		 res=res[0];					  
	     if(!res[1]){
			 if(res[3]) window.location.href=res[3];
			 return;
		}
		if(res[5] == 'save'){
			li.find('.iconstylelist').addClass('col999');	
			num--;
			li.find('.list_nums').text(num);		
		}else{
			li.find('.iconstylelist').removeClass('col999');	
			num++;
			li.find('.list_nums').text(num);			
		}
	});    	
}

//收藏夹
var isForm8=false;
function setCollection(_this){
	if(isForm8) return;
	isForm8=true;		
	var url="<?php echo sign_url(array('id'=>$result['id']),U('Article/setCollection'));?>";
	$.getJSON(url,{},function(res){
		 isForm8=false;					  
		 res=res[0];					  
	     if(!res[1]){
			 if(res[3]) window.location.href=res[3];
			 return;
		}
		if(res[5] == 'save'){
			$(_this).removeClass('fa-star').addClass('fa-star-o');
		}else{
			$(_this).removeClass('fa-star-o').addClass('fa-star');			
	    }
	});
}
//提交主体评论
var isForm9=false;
function comment(_this){
    var content=$('.dptjlbm_text').val();
	if($(_this).is('.disccc')) return;
	if(content)  content=content.replace(/(^\s*)|(\s*$)/g, "");
	if(!content) {
		$('.dptjlbm_text').focus();
		return;
	}
	if(isForm9) return;
	isForm9=true;	
	$(_this).addClass('disccc');
	var url="<?php echo sign_url(array('id'=>$result['id']),U('Article/commnetForm'));?>";
    $.ajax({
		 type:'post',
		 dataType: "json",
		 url:url,
		 data:{content:content},
		 success:function(response,status,xhr){	
		         isForm9=false;
				 res=response[0];	
				 if(!res[1]){
					 if(res[3]) window.location.href=res[3];
					 return;
				}
				$('.dptjlbm_text').val('');
				getComment();
		  },
		   beforeSend:function(){
			 //请求前
		  }
	 });		
}
//提交主体评论后重新获取评论列表
function getComment(type){
	type = arguments[0] ? arguments[0] : '';
    var url="<?php echo U('Article/commentList','','');?>?id=<?php echo ($result['id']); ?>&order_type="+type;
    $.ajax({
		 type:'get',
		 dataType: "text",
		 url:url,
		 success:function(response,status,xhr){
              $('#comment-list').html(response);		  
		  },
		   beforeSend:function(){
			 //请求前
		  }
	 });		
}

//提交二次评论
var isForm10=false;
function mentReply(_this,url,name){
   var parents=$(_this).parents('li');
   var content=parents.find('.content_input').val();
   if(content)  content=content.replace(/(^\s*)|(\s*$)/g, "");
   	if(!content) {
		parents.find('.content_input').focus();
		return;
	}
	if(isForm10) return;
	isForm10=true;
    $.ajax({
		 type:'POST',
		 dataType: "JSON",
		 data:{content:content},
		 url:url,
		 success:function(response,status,xhr){
			  isForm10=false;
			  res=response[0];
			  if(!res[1]){
				 if(res[3]) window.location.href=res[3];
				 return;
			  }			  
			  parents.find('.huifu').css('color','#ccc').removeClass('huifu');
			 $(_this).parent(".duih").hide();
			 $(_this).parent(".duih").siblings(".liuylbbb").show();
			 $(_this).parents("li").removeClass("now")	;	  
			 //生成节点
		     content= repFace(content);//替换表情
			 var node='<p><span class="vam mr5">'+name+'</span><span class="vam" style="color:#023867;">@</span>';              
              node+='<img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5">'+content+'<span class="col999 pl15 f12">刚刚</span></p>';
              parents.find('.liuylbbdh').append(node);
		  },
		   beforeSend:function(){
			 //请求前
		  }
	 });	
}
/*递归替换表情*/
function repFace(content){
	 var face={
		   'face_01':'[流汗]',
		   'face_02':'[钱]',
		   'face_03':'[发怒]',
		   'face_04':'[浮云]',
		   'face_05':'[给力]',
		   'face_06':'[大哭]',
		   'face_07':'[憨笑]',
		   'face_08':'[色]',
		   'face_09':'[奋斗]',
		   'face_10':'[鼓掌]',
		   'face_11':'[鄙视]',
		   'face_12':'[可爱]', 
		   'face_13':'[闭嘴]',
		   'face_14':'[疑问]',
		   'face_15':'[抓狂]',
		   'face_16':'[惊讶]',
		   'face_17':'[可怜]',
		   'face_18':'[弱]',
		   'face_19':'[强]',
		   'face_20':'[握手]',
		   'face_21':'[拳头]',
		   'face_22':'[酒]',
		   'face_23':'[玫瑰]',
		   'face_24':'[打酱油]'				 
	 };
	 for(var i in face){
		 var fa='<span title="" class="margin0 face-item '+i+'" style="display:inline-block;height:23px;width:23px;position:relative;top:5px;"></span>';
		 var test=eval("/"+face[i]+"/");
		 if(test.test(content)){
			 content=content.replace(face[i],fa);		
			 return  repFace(content);
		 }
	 }	
	 return content;    
}
</script>
<style type="text/css">
.show_face{
	width:288px;height:110px;border:1px #666 solid;background:#fff;border-radius:6px;position:absolute;
	top:30px;
	left:-12px;
	display:none; z-index:100
}
.up-arrow1{position:absolute;top:15px;z-index:102;font-size:22px;color:#000;left:3px;display:none;}
.up-arrow2{position:absolute;top:16px;z-index:102;font-size:22px;color:#fff;left:3px;display:none;}
@media screen and (-webkit-min-device-pixel-ratio:0) {
 .up-arrow1{top:19px}  
 .up-arrow2{top:20px} 
 .show_face{ top:34px;}
}
@media screen and (max-width:767px){
	.up-arrow1{top:19px !important}  
 .up-arrow2{top:20px !important} 
	}


.show_face ul li{
    width:35px;
	height:30px;
    border-left: 1px dashed #e5e5e5;
    float: left;
    margin: 0;	
}
.show_face ul li span,.pjbbtxt span{
    display: inline-block;
    width: 22px;
    height: 22px;
    margin: 8px 9px;
    overflow: hidden; vertical-align:middle
}
.show_face ul{border-top: 1px dashed #e5e5e5;height:35px;}
.show_face ul li span.face-item,.pjbbtxt span.face-item{
    background: url(__COMMON__/Images/face-map.png) no-repeat center;
	/**background-size:26px 557px;**/
	cursor:pointer;
}
.show_face ul li span.face_01,.pjbbtxt span.face_01{
   background-position: -2px -2px;
}
.show_face ul li span.face_02,.pjbbtxt span.face_02{
   background-position: -2px -24px;
}
.show_face ul li span.face_03,.pjbbtxt span.face_03{
   background-position: -1px -49px;
}
.show_face ul li span.face_04,.pjbbtxt span.face_04{
   background-position: -2px -73px;
}
.show_face ul li span.face_05,.pjbbtxt span.face_05{
   background-position: -2px -97px;
}
.show_face ul li span.face_06,.pjbbtxt span.face_06{
   background-position: -2px -121px;
}
.show_face ul li span.face_07,.pjbbtxt span.face_07{
   background-position: -2px -145px;
}
.show_face ul li span.face_08,.pjbbtxt span.face_08{
   background-position: -2px -168px;
}
.show_face ul li span.face_09,.pjbbtxt span.face_09{
   background-position: -2px -192px;
}
.show_face ul li span.face_10,.pjbbtxt span.face_10{
   background-position: -2px -215px;
}
.show_face ul li span.face_11,.pjbbtxt span.face_11{
   background-position: -2px -238px;
}
.show_face ul li span.face_12,.pjbbtxt span.face_12{
   background-position: -2px -260px;
}
.show_face ul li span.face_13,.pjbbtxt span.face_13{
   background-position: -2px -284px;
}
.show_face ul li span.face_14,.pjbbtxt span.face_14{
   background-position: -2px -307px;
}
.show_face ul li span.face_15,.pjbbtxt span.face_15{
   background-position: -2px -331px;
}
.show_face ul li span.face_16,.pjbbtxt span.face_16{
   background-position: -2px -355px;
}
.show_face ul li span.face_17,.pjbbtxt span.face_17{
   background-position: -2px -378px;
}
.show_face ul li span.face_18,.pjbbtxt span.face_18{
   background-position: -2px -401px;
}
.show_face ul li span.face_19,.pjbbtxt span.face_19{
   background-position: -2px -425px;
}
.show_face ul li span.face_20,.pjbbtxt span.face_20{
   background-position: -2px -445px;
}
.show_face ul li span.face_21,.pjbbtxt span.face_21{
   background-position: -2px -465px;
}
.show_face ul li span.face_22,.pjbbtxt span.face_22{
   background-position: -2px -488px;
}
.show_face ul li span.face_23,.pjbbtxt span.face_23{
   background-position: -2px -511px;
}
.show_face ul li span.face_24,.pjbbtxt span.face_24{
   background-position: -2px -535px;
}
.margin0{
    margin:0 !important;
}



.face-wrapper-dw{top:24px;left:-10px;display:none;width:300px;height:145px;position:absolute;z-index:2;background:url(__COMMON__/Images/face-bg.png);overflow:hidden}
 .face-wrapper-dw .wrapper-cont-dw{width:288px;height:107px;margin-top:6px;margin-left:1px}
 .face-wrapper-dw .wrapper-cont-dw ul{border-top:1px dashed #e5e5e5; height:35px;}
.face-wrapper-dw .wrapper-cont-dw ul:first-child{border-top:0}
.face-wrapper-dw .wrapper-cont-dw ul li{border-left:1px dashed #e5e5e5;width:35px;height:35px;float:left;margin:0}
.face-wrapper-dw .wrapper-cont-dw ul li:hover{background:#f2f2f2}
.face-wrapper-dw .wrapper-cont-dw ul li:first-child{border-left:0}
.face-wrapper-dw .wrapper-cont-dw ul:first-child li:first-child{border-top-left-radius:8px}
.face-wrapper-dw .wrapper-cont-dw ul:first-child li:last-child{border-top-right-radius:8px}
.face-wrapper-dw .wrapper-cont-dw ul:last-child li:first-child{border-bottom-left-radius:8px}.face-wrapper-dw .wrapper-cont-dw ul:last-child li:last-child{border-bottom-right-radius:8px}
.face-wrapper-dw .wrapper-cont-dw ul li a{width:35px;height:24px;display:inline-block;text-align:center;overflow:hidden;padding:8px 0 0}
.face-wrapper-dw .wrapper-cont-dw ul li span{display:inline-block;width:22px;height:22px;margin:8px 9px;overflow:hidden}
.face-item{background:url(__COMMON__/Images/face-map.png) no-repeat center}.face-item.face_01{background-position:-2px -2px}.face-item.face_02{background-position:-2px -24px}.face-item.face_03{background-position:-1px -49px}.face-item.face_04{background-position:-2px -73px}.face-item.face_05{background-position:-2px -97px}.face-item.face_06{background-position:-2px -121px}.face-item.face_07{background-position:-2px -145px}.face-item.face_08{background-position:-2px -168px}.face-item.face_09{background-position:-2px -192px}.face-item.face_10{background-position:-2px -215px}.face-item.face_11{background-position:-2px -238px}.face-item.face_12{background-position:-2px -260px}.face-item.face_13{background-position:-2px -284px}.face-item.face_14{background-position:-2px -307px}.face-item.face_15{background-position:-2px -331px}.face-item.face_16{background-position:-2px -355px}.face-item.face_17{background-position:-2px -378px}.face-item.face_18{background-position:-2px -401px}.face-item.face_19{background-position:-2px -425px}.face-item.face_20{background-position:-2px -445px}.face-item.face_21{background-position:-2px -465px}.face-item.face_22{background-position:-2px -488px}.face-item.face_23{background-position:-2px -511px}.face-item.face_24{background-position:-2px -535px}@media (-webkit-min-device-pixel-ratio:2),(min--moz-device-pixel-ratio:2),(min-resolution:2dppx),(min-resolution:192dpi){.face-item{background:url(__COMMON__/Images/face-map-2x.png) no-repeat center;background-size:44px auto}.face-item.face_01{background-position:0 -176px}.face-item.face_02{background-position:-22px -66px}.face-item.face_03{background-position:0 -44px}.face-item.face_04{background-position:-22px -154px}.face-item.face_05{background-position:0 -88px}.face-item.face_06{background-position:-22px 0}.face-item.face_07{background-position:0 -110px}.face-item.face_08{background-position:0 -66px}.face-item.face_09{background-position:0 0}.face-item.face_10{background-position:0 -22px}.face-item.face_11{background-position:-22px -44px}.face-item.face_12{background-position:0 -132px}.face-item.face_13{background-position:-22px -88px}.face-item.face_14{background-position:-22px -22px}.face-item.face_15{background-position:0 -154px}.face-item.face_16{background-position:-22px -132px}.face-item.face_17{background-position:-22px -110px}.face-item.face_18{background-position:0 -220px}.face-item.face_19{background-position:0 -198px}.face-item.face_20{background-position:-22px -198px}.face-item.face_21{background-position:-22px -220px}.face-item.face_22{background-position:-22px -244px}.face-item.face_23{background-position:0 -132px}.face-item.face_24{background-position:-22px -176px}}


.comm999 {
    display: inline-block;
    vertical-align: middle;
    line-height: 30px;
    color: #999;
    font-size: 15px;
}
.disccc{background:#ccc !important;cursor:auto!important;}
</style>

</head>

<body>
<div class="box">
<link rel="stylesheet" href="__HOME__/css/essentials.min.css"/>
<link rel="stylesheet" href="__HOME__/css/iconfont2.css"/>
<link rel="stylesheet" href="__HOME__/css/font-awesome.min.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<link rel="stylesheet" href="__HOME__/css/style0417.css" />
<link rel="stylesheet" href="__HOME__/css/jquery.bxslider.css"/>
<script type="text/javascript" src="__PLUGINS__/formValidation/formValidation.js"></script>
<!--<link rel="Stylesheet" href="__PLUGINS__/ValidationEngine/Css/validationEngine.jquery.css"/>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/jquery.validationEngine.min.js"></script>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/jquery.validationEngine-<?php echo getLang();?>.js"></script>
<script type="text/javascript" src="__PLUGINS__/ValidationEngine/Js/myValidationEngine.js"></script>-->
<script>
 $(function(){
	/** $("a.hui").mouseover(function(){
		 $(this).siblings("a.hui").css("opacity","0.4")
		 })
	$("a.hui").mouseout(function(){
		 $(this).siblings("a.hui").css("opacity","1")
		 })	 **/
	$(document).on('click', ".guojili", function(e){
     $(".guojb").toggle()
	 stopPropagation(e);
});	 

$(document).bind('click',function(){

	 $(".guojb").hide()

});

	
	
	 })
</script>
<script type="text/javascript" src="__HOME__/js/jquery.bxslider2.js"></script>
<div class="phonebg"></div>
<div class="topbg"></div>
<div class="header">

<div class="headert"><div class="headertm">
<span class="close wdzhclose"><span class="glyphs icon-close" style="line-height:35px;"></span></span>

 <h1 style="width:30%"><?php if(is_login()): echo strip_tags(getUserC('SE_NICKNAME')); else: echo isL(L('UserName'),'用户名'); endif; ?></h1>
 <p class="zhtxt"><a href="<?php echo U('Member/index',array('personal'=>'show'));?>#personal" class="col2a"><?php echo isL(L('MyAccount'),'我的账户');?></a></p>
 <p class="zhtxt pr">
     <a href="<?php echo U('Member/index',array('xiaoxi'=>'show'));?>#xiaoxi" class="col2a">我的消息</a>
      <?php $massge_count=remind_get_num(); ?>
      <?php if($massge_count > 0): ?><b class="xxnum"><?php echo ($massge_count); ?></b><?php endif; ?>
 </p>
 <p class="zhtxt"><a href="<?php echo U('Member/index',array('address'=>'show'));?>#address" class="col2a"><?php echo isL(L('AddressBook'),'地址簿');?></a></p>
 <p class="zhtxt"><a href="<?php echo U('Member/index',array('order'=>'show'));?>#order-h1" class="col2a"><?php echo isL(L('MyOrdersReturns'),'我的订单与退货');?></a></p>
 <?php if(is_login()): ?><p class="lh30"><a href="<?php echo U('Login/exitLogin');?>" class="col2a"><?php echo isL(L('Exit'),'退出');?></a></p>
<?php else: ?>
     <p class="lh30">
         <a href="<?php echo U('Login/index');?>" class="col2a"><?php echo isL(L('Login'),'登录');?></a>
         <a href="<?php echo U('Login/index');?>" class="col2a"><?php echo isL(L('Registered'),'注册');?></a>
     </p><?php endif; ?>
 <p class="tr pv10"><a href="<?php echo U('Member/index');?>" class="wdzh"><?php echo isL(L('MyAccount'),'我的账户');?> <span class="glyphs icon-thinArrow" style="float:right; font-size:1.25em; line-height:inherit"></span></a></p>
</div></div><!---->
<div class="ckywwrap">

<div class="ckyw">
 <!--<div class="ckywl"><p class="col222 f16 lh30">您的愿望单中暂无商品</p></div>没商品时-->
 <div class="ckywl">
  <div class="ckywlbox zjll3" <?php if(count($pbulcCollect) <= 5){ ?>style="background: none !important;"<?php } ?>>
         <a href="javascript:void(0)" class="icon30a zjll3right" style="top:100px; margin-top:0"></a>
         <a href="javascript:void(0)" class="icon30b zjll3left"  style="top:100px; margin-top:0"></a>
     <div class="ckywlm zjll1m">
     <ul class="get-new-collection">
      
<?php $cookiecookie=cookie('cookie_collect'); $cookiefield="id,".pfix()."goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,"; $cookiefield.="goods_thumb,goods_thumb2,stock,volume"; $cookiewhere['shelves']=1; $cookiewhere['recycle_bin']=0; $cookiewhere['id']=array('in',$cookiecookie); $cookiegoods=M('goods'); $cookieresult = $cookiegoods->field($cookiefield)->where($cookiewhere)->select(); $publicCount=$cookieresult ? count($cookieresult) : 0; ?>
<?php if(!$cookieresult): ?><div  class="col222 f16 lh30" style="display:inline-block;margin-top:20px">您的愿望单中暂无商品</div><?php endif; ?>
<?php if(is_array($cookieresult)): foreach($cookieresult as $key=>$g): ?><li>
		<!--<span class="icon31" onclick="del_collection('<?php echo U('Cookie/deleteDoodsCollect',array('id'=>$g['id'],'is_location'=>0));?>',<?php echo ($g['id']); ?>)"></span>-->
        <span class="dbclose" onclick="del_collection('<?php echo U('Cookie/deleteDoodsCollect',array('id'=>$g['id'],'is_location'=>0));?>',<?php echo ($g['id']); ?>)"><span class="glyphs icon-close"></span></span>
		<div class="ckywlmt"> 
			<table width="100%" height="100%">
			  <tr>
				  <td align="center" valign="middle" width="100%" height="100%">
					  <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
				   </td>
			   </tr>
			</table>
		 </div>
		 <div class="ckywlmtb">
			 <table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle" width="100%" height="100%">
						<a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
					</td>
				 </tr>
			  </table>
		 </div>
		<div class="ckywlmb">
		<!--<p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>-->
        <?php if($g['stock'] <= 0): ?><span class="sold">sold out</span><?php endif; ?>
		<p><b><?php echo C('KTEC_H');?> <?php echo ($g['goods_retail_price']); ?></b></p>
		</div>
	</li><?php endforeach; endif; ?>
<script type="text/javascript">
colletionCounts();
function colletionCounts(){ 
	var count=<?php echo ($publicCount); ?>; 
    $(function(){
		 if(count > 0){
			 $('.iconfont-like2').show();
			 $('.iconfont-like').hide();
		 }else{
			 $('.iconfont-like').show();
			 $('.iconfont-like2').hide();			 
		 }	    
	     $('.public-collect').text(count);
		 if(count <= 5) $('.zjll3left,.zjll3right').hide();
		 else $('.zjll3left,.zjll3right').show();
		wckyw=$(".ckywlbox").width();
		$(".ckywlm li").css("width",wckyw/5);
		$(".zjll3").myscroll({ prev: ".zjll3left", next: ".zjll3right", scrollbox: ".zjll3m" });
		var jrxy=$('.jrxy');
		for(var i=0;i < jrxy.length;i++){ 
			if(parseInt(window.goodsId) == parseInt(jrxy.eq(i).attr('id'))){
				if(jrxy.eq(i).find('span').is('.fa-heart-o')) jrxy.eq(i).find('span').attr('class','fa fa-heart');
				else jrxy.eq(i).find('span').attr('class','fa fa-heart-o');
				break;
			}
		}
	});
}
/*添加原望单*/
function coll_and_cac(_this){
	var url='';
    if($(_this).attr('class').indexOf('now') > -1) {
		$(_this).attr('class','jrxy');
		$(_this).children("span").attr('class','fa fa-heart-o');
		url=$(_this).attr('del-url');
	}else {
		$(_this).attr('class','jrxy jrxynow');
		$(_this).children("span").attr('class','fa fa-heart');
		url=$(_this).attr('add-url');
	}
	$.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			   var getUrl="<?php echo U('Piece/collection');?>";
			   //实时获得
				$.ajax({
					 type:'get',
					 url:getUrl,
					 dataType:'text',
					 success:function(response,status,xhr){
						   $('.get-new-collection').html(response); 
					  } 
				});	
		  }
    });	
}
/*删除原望单*/
function del_collection(url,id){
	window.goodsId=id;
	$.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			   var getUrl="<?php echo U('Piece/collection');?>";
			   //实时获得
				$.ajax({
					 type:'get',
					 url:getUrl,
					 dataType:'text',
					 success:function(response,status,xhr){
						   $('.get-new-collection').html(response); 
					  } 
				});	
		  }
    });	
}
</script>

    </ul>
      <div class="cb"></div>
     </div>
    </div> 
 </div><!--ckywl end-->
 
 <div class="ckywr">
  <h1 class="huan lh22 pr mt20"><b class="f14"><?php echo isL(L('ViewWishList'),'查看愿望单');?></b>
  <span class="close  ywclose"><span class="glyphs icon-close" style="line-height:35px;"></span></span>
  </h1>
  <p class=" col222 f13 lh22 " style="font-weight:bold"><span class="public-collect"><?php echo count($pbulcCollect) > 0 ? count($pbulcCollect) : 0;?></span> <?php echo isL(L('Number'),'数量');?></p><!--请不要删除这个 public-collect -->
  <div class="cb pt30"></div>
  <!--<a href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>">-->
  <a href="<?php echo U('Cart/index');?>#ywd">
  <div class="bcbtn" style="width:100%">
        <input value="<?php echo isL(L('ViewWishList'),'查看愿望单');?>" class="btn" type="button">
       <span class="glyphs icon-heart btnb colfff" style="font-size:1.5em; line-height:38px"></span>    
  </div>
  </a>
  <!--
  <p class="pt20"><a class="cky" href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>"><?php echo isL(L('ViewWishList'),'查看愿望单');?> <span class="glyphs icon-heart" style="display:inline-block; float:right; font-size:1.25em; color:#df302d; line-height:inherit"></span></a></p>-->
 </div><!--ckywr end-->
 <div class="cb"></div>
</div>

</div><!--ckywwrap end-->

<div class="ckgwdwrap" id="show-cart-list"> <!-- ckgwdwrap 购物车-->
    
<?php $f="id,goods_id,goods_sn,goods_thumb,".pfix('goods_name')." AS goods_name,goods_price,goods_number"; $user_id=getUserC('SE_USER_ID') ? getUserC('SE_USER_ID') : '-1'; $hw="user_id='".$user_id."' OR user_key='".return_key()."'"; $headercart=M('cart')->field($f)->where($hw)->select(); ?>
<div class="ckyw">
 <?php if($headercart){ ?>
     <div class="ckywl ckywl2">   
     
     
    
      <div class="ckywlbox4 zjll4" <?php if(count($headercart) <= 5){ ?>style="background: none !important;"<?php } ?>>
      <?php if(count($headercart) > 5){ ?>
         <a href="javascript:void(0)" class="icon30a zjll4right" style="top:100px; margin-top:0"></a>
         <a href="javascript:void(0)" class="icon30b zjll4left"  style="top:100px; margin-top:0"></a>
       <?php } ?>  
         <div class="ckywlm zjll4m">
          <ul> 
              <?php $cartSum=0; $cartCount=0; ?>
              <?php if(is_array($headercart)): foreach($headercart as $key=>$g): $cartSum+=$g['goods_price'] * $g['goods_number']; $cartCount+=$g['goods_number']; ?>
                <li>
                 <span class="dbclose" onclick="deleteTopCatr('<?php echo get_up_url(array('delete'=>mt_rand(1000,9000)),U('Cart/delCart','',''),true);?>&ids=<?php echo ($g['goods_id']); ?>')"><span class="glyphs icon-close"></span></span>
                   
                    <div class="ckywlmt">
                        <table width="100%" height="100%">
                          <tr>
                              <td align="center" valign="middle" width="100%" height="100%">
                                  <a href="<?php echo U('Goods/index',array('html'=>$g['goods_id']));?>"  target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                               </td>
                           </tr>
                        </table>
                     </div>
                     <div class="ckywlmtb">
                         <table width="100%" height="100%">
                            <tr>
                                <td align="center" valign="middle" width="100%" height="100%">
                                    <a href="<?php echo U('Goods/index',array('html'=>$g['goods_id']));?>"  target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                                </td>
                             </tr>
                          </table>
                     </div>
                    <div class="ckywlmb">
                     <span class="sold">sold out</span>
                    <p><b><?php echo C('KTEC_H'); echo ($g['goods_price']); ?></b></p> 
                    </div>
                </li><?php endforeach; endif; ?>
          </ul>
          <div class="cb"></div>
         </div>
        </div>
     </div><!--ckywl end-->
 <?php }else{ ?>
  <div class="ckywl"><p class="col222 f16 lh22 pt20"><?php echo isL(L('NoGoodsInYour'),'您的购物袋中暂无商品');?></p></div><!--没商品时-->
 <?php } ?>
 
 <div class="ckywr">
  <h1 class="huan lh22 pr mt20"><b class="f14"><?php echo isL(L('ShoppingBag'),'购物袋');?></b>
  
  <span class="close  gwdclose"><span class="glyphs icon-close" style="line-height:35px;"></span></span>
  </h1>
  <p class=" col222 f13 lh22 " style="font-weight:bold"><span class="public-cart"><?php echo ($cartCount ? $cartCount : 0); ?></span> <?php echo isL(L('Commodity'),'数量');?></p><!--请不要删除这个 public-cart -->
  <div class="cb pt30"></div>
  <a href="<?php echo U('Cart/index');?>"><div class="bcbtn" style="width:100%">
    <input value="<?php echo isL(L('ViewShoppingBags'),'查看购物袋');?>" class="btn" type="button">
   <span class="glyphs icon-heart btnb colfff"></span>    
 </div></a>
 </div><!--ckywr end-->
 <div class="cb"></div>
</div>
<script type="text/javascript">
cartsCounts();
function cartsCounts(){
	var sum="<?php echo number_format($cartSum ? $cartSum : 0,2,'.',''); ?>";
	var CUR='<?php echo C('KTEC_H');?>';
    $(function(){
	     $('.public-cart-moeys').text(CUR+sum);
		 $('.public-cart').text(<?php echo ($cartCount ? $cartCount : 0); ?>);
		 wckyw=$(".ckywl2").width();
		 $(".ckywlm li").css("width",wckyw/5);		 
		 $(".zjll4").myscroll({ prev: ".zjll4left", next: ".zjll4right", scrollbox: ".zjll4m" });
	});
	
}


function deleteTopCatr(url){
	
      var getCurl="<?php echo U('Piece/headerCart');?>";	  
	  $.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			  $.ajax({
				 type:'get',
				 url:getCurl,
				 success:function(response,status,xhr){
					 $('#show-cart-list').html(response);
				  }
			   });				 
		  }
	   });		
	  
}

</script>
<style type="text/css">
.dbclose1 {
    position: absolute;
    z-index: 10;
    top: 5px;
    right: 5px;
    color: #222;
    background: #fff;
    cursor: pointer;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    box-sizing: border-box;
    border-radius: 50%;
}
</style>
</div><!--ckgwdwrap 购物车 end-->

 <div class="headertbwrap"><div class="headertb">
 <?php if(is_mobile()): ?><a href="__ROOT__/" class="logo4"></a>
 <?php else: ?>
 <a href="__ROOT__/" class="logo"></a><?php endif; ?>
 <div class="tbr">
  <ul>
  	<li class="guojili"><a href="javascript:void(0)" class="flag">
    <?php if($_COOKIE['think_language'] == 'en-us'): ?><span class="icon1c" style="line-height:20px">English</span>
    <?php else: ?>
    <span class="icon1" style="line-height:20px">CHINA</span><?php endif; ?>
    </a></li>  
    <?php if(is_login()): ?><li class="userli"><a href="javascript:void(0)" class="user"><span class="glyphs icon-user"></span><span class="name"><?php echo getUserC('SE_NICKNAME');?></span></a><b class="sanj"></b><b class="sanj2"></b></li>
  <?php else: ?>
      <li class="userli"><a href="<?php echo U('Login/index');?>"><span class="glyphs icon-user"></span></a></li><?php endif; ?> 
    
    <?php if(is_mobile()): ?><li class="iconfont-like" style="<?php if($pbulcCollect): ?>display:none<?php endif; ?>"><a href="<?php echo U('Cart/index');?>" class=" like"><span class="fa fa-heart-o"></span></a></li>
     <li class="iconfont-like2" style="<?php if(!$pbulcCollect): ?>display:none<?php endif; ?>"><a href="<?php echo U('Cart/index');?>" class=" like"><span class="fa fa-heart"></span><span class="name public-collect"><?php echo count($pbulcCollect) > 0 ? count($pbulcCollect) : '';?></span></a></li>
      <li><a href="<?php echo U('Cart/index');?>" class="ckgwd"><span class="glyphs icon-bag"></span>
      <span class="name public-cart">0</span>
      <!--<span class="name public-cart-moeys"><?php echo C('KTEC_H');?> 0.00</span>-->
      </a></li>
      <li>      
  <a href="javascript:void(0)" class="icon6 fl"><span class="glyphs icon-search"></span></a>
      </li>
      <li><a href="javascript:void(0)" class="navphone fl"><span class="glyphs icon-trigger"></span></a></li>
     
    <?php else: ?>

     <?php if(!$is_cart){ ?>
        <li class="iconfont-like" style="<?php if($pbulcCollect): ?>display:none<?php endif; ?>"><a href="javascript:void(0)" class=" like"><span class="fa fa-heart-o"></span></a><b class="sanj"></b><b class="sanj2"></b></li>
        <li class="iconfont-like2" style="<?php if(!$pbulcCollect): ?>display:none<?php endif; ?>"><a href="javascript:void(0)" class=" like"><span class="fa fa-heart"></span><span class="name public-collect"><?php echo count($pbulcCollect) > 0 ? count($pbulcCollect) : '';?></span></a><b class="sanj"></b><b class="sanj2"></b></li>
    <?php }else{ ?>
        <!---->
        <li ><a href="#ywd" class="likeywd"><span class="fa fa-heart"></span><span class="name public-collect">0</span></a><b class="sanj"></b><b class="sanj2"></b></li>
    <?php } ?>
    <!---->
     <li><a href="javascript:void(0)" class="ckgwd"><span class="glyphs icon-bag"></span><span class="name public-cart-moeys"><?php echo C('KTEC_H');?> 0.00</span></a><b class="sanj"></b><b class="sanj2"></b></li><?php endif; ?>
    
   
  </ul>
       <?php if($_COOKIE['think_language'] == 'en-us'): ?><div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'zh-cn'));?>" class="icon1">China</a>
       </div>
       <?php else: ?>       
       <div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'en-us'));?>" class="icon1c">EN</a>
       </div><?php endif; ?>  
 </div><!--tbr end-->
  <!--
  <a href="javascript:void(0);" class="icon5 fr"></a>
  <a href="<?php echo U('Cart/index');?>" class="icon4 fr glyphs icon-bag hui"></a>
  <span>|</span>
  <?php if(is_mobile()): ?><a href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" class="icon3 fr hui"><span style="color:#000; font-size:12px; padding:0; line-height:33px;" class="public-collect"><?php echo count($pbulcCollect) > 0 ? count($pbulcCollect) : '';?></span></a>
   <?php else: ?>
     <a href="javascript:void(0)" class="icon3 fr glyphs icon-heart hui"><span style="color:#000; font-size:12px; padding:0; line-height:33px;" class="public-collect"><?php echo count($pbulcCollect) > 0 ? count($pbulcCollect) : '';?></span></a><?php endif; ?>
  <span>|</span>
  <?php if(is_login()): ?><a href="javascript:void(0)" class="icon2 fr glyphs icon-user hui"></a>
  <?php else: ?>
      <b class="to-login fr"><a href="<?php echo U('Login/index');?>" class="icon2dj" style="display: inline-block">&nbsp;</a></b><?php endif; ?>
  
  <span>|</span> 
  <div class="guoj fr">
  <?php if($_COOKIE['think_language'] == 'en-us'): ?><h1 class=" icon1c col222">English</h1>   
       <div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'zh-cn'));?>" class="icon1">China</a>
       </div>
  <?php else: ?>
       <h1 class="  icon1 col222">China</h1>   
       <div class="guojb">
        <a href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'en-us'));?>" class="icon1c">English</a>
       </div><?php endif; ?>  

  
  </div>-->
  
  
  <div class="cb"></div>
 </div></div><!----> 
 <div class="nav"><div class="navm">
  <a href="__ROOT__/" class="navlink" style="color:#f00; padding-left:0"><span style="color:#f00">HOME</span></a>
  <a href="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>desc,'text'=>'new'));?>" class="navlink"><span><?php echo isL(L('NewArrivals'),'最新发布');?></span></a>
  <a href="<?php echo U('Category/index');?>" class="navlink"><span><?php echo isL(L('Categories'),'产品分类');?></span></a>
  <a href="<?php echo U('Modular/singleProduct',array('html'=>$files[3][C('A_5')]['id']));?>" class="navlink"><span><?php echo ($files[3][C('A_5')]['titletext']); ?></span></a>
  <a href="<?php echo U('Modular/lookbook');?>" class="navlink"><span>LOOKBOOK</span></a>
  <a href="<?php echo U('Modular/brandImage');?>" class="navlink"><span><?php echo ($files[3][C('A_8')]['titletext']); ?></span></a>
  <a href="<?php echo U('Modular/enjoy',array('html'=>$files[3][C('A_6')]['id']));?>" class="navlink"><span><?php echo isL(L('CustomerRespect'),'客户尊享');?></span></a>
  <a href="<?php echo U('Article/index');?>" class="navlink"><span>YOUDI WU<!--<?php echo isL(L('News'),'YOUDI WU最新资讯');?>--></span></a>
  <a href="<?php echo U('Modular/opportunity',array('html'=>$files[3][C('A_2')]['id']));?>" class="navlink"><span><b class="icon7" style="font-weight:normal"><?php echo isL(L('CareerOpportunities'),'职业机会');?></b></span></a>
  <a href="<?php echo U('Article/index2');?>" class="navlink navlinkzx">资讯</a>
  <span class="icon8" ><span class="glyphs icon-search" ></span><?php echo isL(L('Search'),'搜索');?></span>
  <form action="<?php echo U('Category/index','','');?>" id="keywords-form">
  <div class="sswrap"><span class="glyphs icon-search" onclick="topRetrieval(this)"></span>
  <input type="submit" name="" value="" onclick="return topRetrieval(this)" class="ss_btn"/>
  <input type="text" name="keywords" value="" class="ss_input"/>
  </div>
  </form> 
  <script type="text/javascript">
  function topRetrieval(_this){
	  var form=$('#keywords-form');
	  var keywords=form.find('input[name="keywords"]');
	  if(!keywords.val()){
		  keywords.focus();
		  return false;
	  }
	  var url="<?php echo U('Category/index','','');?>/keywords/"+keywords.val();
	  window.location.href=url;
	  return false;
  }
  </script>
  <div class="cb"></div>
 </div></div><!--nav end-->
 </div><!--header end-->
 
  <div class="headerb"></div>
   <?php if(is_mobile()): ?><div class="toptxt">
     <div class="navb">
      <div class="navbt"><span class="toptxtl"><?php echo isL(L('ExpressOnly'),'<span>YOUDI WU</span>唯一指定配送&nbsp;&middot;&nbsp;顺丰航空');?></span>
      <span class="glyphs icon-more fr color-black pl10 pr10"></span>
      <div class="cb"></div></div>
      <p class="navbb"><?php echo isL(L('FreeReturnOn'),'<span>用户均享受</span>免运费配送及免运费退货<span>服务</span>');?></p>
     </div>
     
      <div class="navb" style="display:none">
      <div class="navbt"><span class="toptxtl"><?php echo isL(L('OurNewsletter'),'订阅我们的电子报');?></span>
      <span class="glyphs icon-more fr color-black pl10 pr10"></span>
      <div class="cb"></div></div>
      <p class="navbb"><?php echo isL(L('ProductsAndA'),'第一时间了解独家减价季预览邀请、最in的新近单品、潮流咨询以及各种活动信息|马上订阅');?></p>
     </div>
     
    </div>
   <?php else: ?>
   
<div style="background:#ddd;">   
    <div class="navb" style="background:#ddd;">
         <span class="huan pr5 navbl">
         <?php echo isL(L('ExpressOnly'),'<span>YOUDI WU</span>唯一指定配送&nbsp;&middot;&nbsp;顺丰航空');?>
         </span> 
         <span class="tc" style="display:inline-block; background:#000; width:1px; height:13px; vertical-align:middle"></span>
         <span class="kz pl5 navbr"><?php echo isL(L('FreeReturnOn'),'<span>用户均享受</span>免运费配送及免运费退货<span>服务</span>');?></span>
     </div> 
    <div class="navb" style="display:none;background:#ddd;height:50px; overflow:hidden !important">
         <span class="huan pr5 navbl vam" style="width:auto"><?php echo isL(L('OurNewsletter'),'订阅我们的电子报');?></span>
         <span class="tc" style="display:inline-block; background:#000; width:1px; height:13px; vertical-align:middle"></span>
         
         <span class="kz vam navbr" style=" width:auto; text-align:center">第一时间了解独家减价季预览邀请、最in的新近单品、潮流咨询以及各种活动信息</span> 
         <span class="tc" style="display:inline-block; background:#000; width:1px; height:13px; vertical-align:middle"></span>
         <a href="javascript:void(0);" onclick="toEmail()" style="border-bottom:1px #666 solid;color:#666; padding-left:5px; vertical-align:middle; display:inline-block; box-sizing:border-box">马上订阅</a>
        <!-- <span class="kz pl5"><?php echo isL(L('ProductsAndA'),'第一时间了解独家减价季预览邀请、最in的新近单品、潮流咨询以及各种活动信息|马上订阅');?></span>-->
     </div>
 </div><?php endif; ?>
<script type="text/javascript">
$(function(){
	$(".navbt").click(function(){
		$(this).toggleClass("navbtnow") 
		$(this).siblings(".navbb").toggle()
		})
  	
		
	})
/**切换文字广告*/
switchs('.navb');
function switchs(c){
  var timer1=null;	
  timer1=setInterval(function(){
	 var navb=$(c);		
	 var len=navb.length;
	 var index=-1;
	 for(i = 0; i < len; i++){
		 if(navb.eq(i).css('display')=='block') {
			 index=i;
			/* navb.eq(i).hover(function(){
				clearInterval(timer1);
			 },function(){
				 clearInterval(timer1);
				 switchs(c);
			 });*/
			 break;
		 }
	 }
	 index=index >= len - 1 ? -1 : index;
	 navb.fadeOut(0);
	 navb.eq(index + 1).fadeIn(800);
  },5000);  
 // clearInterval(timer1);
}

/*跳到底部邮件订阅*/
function toEmail(){
   $('input[name="email"]').focus();
}
/***/
</script>
<!--content begin-->
<!--<div class="banner">
  <ul class="bxslider">
      <?php if(is_array($newAd)): foreach($newAd as $key=>$a): ?><li><a  <?php if(trim($a['ad_url'])): ?>href="<?php echo ($a['ad_url']); ?>"<?php endif; ?> target="_blank"><img src="__ROOT__/<?php echo ($a['ad_img']); ?>" /></a></li><?php endforeach; endif; ?>
	</ul>
</div><!--banner end-->
<div class="flexsliderwrap">
			<div class="flexslider" >
			   <ul class="slides">
                   <?php if(is_array($album)): foreach($album as $aks=>$a): ?><li><b> <?php echo ($aks+1); ?> of <?php echo count($album);?> </b>
                        <table width="100%" height="100%">
                          <tr>
                              <td align="center" valign="middle" width="100%" height="100%">
                                 <a  <?php if(trim($a['img_bewrite'])): ?>href="<?php echo ($a['img_bewrite']); ?>"<?php endif; ?> target="_blank"><img src="__ROOT__/<?php echo ($a['img']); ?>" /></a>
                              </td>
                          </tr>
                    </table>
                  </li><?php endforeach; endif; ?>
		    </ul>
	    </div>
 </div>
 
 
<div class="dptj">
<div class="dptjl">
 <div class="dptjlt">
  <div class="dptjltt">
  <h1><?php echo (strip_tags($result['titletext'])); ?></h1>
  <p class="dptjlttm"><?php echo (strip_tags($result['bewrite'])); ?></p>
  <div class="dptjlttb"><?php echo ($result['author']); ?> <?php echo date('Y.m.d',$result['addtime']);?>
   <div class="dptjlttbr"><span class="fa fa-eye vam col999"></span><span class="col999 vam ml5 mr10"><?php echo ($result['count_click']); ?> Read</span>
   <span class="fa fa-commenting f13 vam col999"></span><a href="#comment" class="col999 vam ml5 comment"><?php echo ($result['comment']); ?> Comments</a>
   <a class="fa fa-facebook ml25" style=" color:#4d679d"></a>
   <a class="fa fa-twitter" style=" color:#5eb0ea"></a>
   <a class="fa fa-google-plus" style=" color:#d54c43"></a>
   <a class="fa fa-pinterest-p" style=" color:#ca2934"></a>
   <a class="fa fa-weibo" style=" color:#d8302f"></a>
   <a class="fa fa-wechat" style=" color:#3dc128"></a>
   </div>
   <div class="cb"></div>
  </div>
  </div>
  <div class="dptjltb"><?php echo (stripcslashes($result['content'])); ?></div>
  <p class="f18 col333 pv30">Voyager Espresso</p>
  <div class="txtmshu"><em></em><span>photographer</span><b class="pl5">tylerglickman/hypebeast</b>
  
  <?php if(is_login()): if($collection): ?><div class="txtmshur"><span class="fa fa-star" onClick="setCollection(this)"></span></div>	
     <?php else: ?>
        <div class="txtmshur"><span class="fa fa-star-o"  onClick="setCollection(this)"></span></div><?php endif; ?>
  <?php else: ?>
     <div class="txtmshur"><a href="<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>"><span class="fa fa-star-o"></span></a></div><?php endif; ?> 
  </div>
  <p class="lh20 col999" style="text-transform:uppercase">share this article</p>
  <div class="fxicon">
   <a href="javascript:void(0);" class="fa fa-facebook" style="background:#4d679d"></a>
   <a href="javascript:void(0);" class="fa fa-twitter" style="background:#5eb0ea"></a>
   <a href="javascript:void(0);" class="fa fa-google-plus" style="background:#d54c43"></a>
   <a href="javascript:void(0);" class="fa fa-pinterest-p" style="background:#ca2934"></a>
   <a href="javascript:void(0);" class="fa fa-weibo" style="background:#d8302f"></a>
   <a href="javascript:void(0);" class="fa fa-wechat" style="background:#3dc128"></a>
  </div>
  <?php $YOU_LABEL=C('YOU_LABEL'); ?>
  <div class="bqtxt">
    <?php if(is_array($YOU_LABEL)): foreach($YOU_LABEL as $youKey=>$you): ?><a href="<?php echo U('Article/index',array('babel'=>$youKey));?>"><span><?php echo ($you['cn_name']); ?></span></a><?php endforeach; endif; ?>
  </div>
 </div><!--dptjlt end-->
 <div class="wztxt1" id="comment"><p><b>Comments for this thread are now closed.</b></p></div>
 <div class="commentbox mb30" id="liuylb">
  <span class="commentl"><?php echo ($result['comment']); ?> Comment</span>
  <span onClick="praise(this)" style="cursor:pointer">
      <span class="<?php echo ($clicks ? 'commentlr' : 'col999'); ?> fa fa-thumbs-up iconstyle"></span>
      <span class="col333 vam f12 mh5">点赞</span>
      <span class="commentlrr paraise_num"><?php echo ($result['like_count']); ?></span>
  </span>
  <div class="zhpx">
   <span class="zhpxtxt">综合排序</span><span class="zhpxtxtr fa fa-sort-desc"></span>
   <div class="zhpxb">
    <span onClick="getComment(1)">最热</span><span onClick="getComment(2)">最新</span><span onClick="getComment(3)">最旧</span>
   </div>
  </div><!--zhpx end-->
  <script>
   $(function(){
	   $(".zhpxtxtr").click(function(){
		   $(".zhpxb").slideToggle()
		   })
	$(".zhpxb span").click(function(){
		txt=$(this).text()
		$(".zhpxtxt").text(txt)
		   $(".zhpxb").slideUp()
		   })	   
	   })
  </script>
  <div class="cb"></div>
 </div><!--commentbox-->
 <textarea name="" class="liuybox dptjlbm_text" ></textarea>
 <div class="liuybb" >
  <a class="icon27j emotion" style="background: none; position:relative; margin-top:6px;">
          <img src="__HOME__/images2/icon7.png" alt="" style="width:25px; height:25px;"/>
          <span class="up-arrow1">◆</span><span class="up-arrow2">◆</span>
          <div node-type="face-box" class="face-wrapper-dw show_face2" >
                    <div node-type="face-cont" class="wrapper-cont-dw">
                        <ul class="clear-g">
                            <li>
                                <span title="流汗" data_path="base" data-ubb="[流汗]" class="face-item face_01" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="钱" data_path="base" data-ubb="[钱]" class="face-item face_02" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="发怒" data_path="base" data-ubb="[发怒]" class="face-item face_03" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="浮云" data_path="base" data-ubb="[浮云]" class="face-item face_04" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="给力" data_path="base" data-ubb="[给力]" class="face-item face_05" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="大哭" data_path="base" data-ubb="[大哭]" class="face-item face_06" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="憨笑" data_path="base" data-ubb="[憨笑]" class="face-item face_07" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="色" data_path="base" data-ubb="[色]" class="face-item face_08" set_value="dptjlbm_text"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="奋斗" data_path="base" data-ubb="[奋斗]" class="face-item face_09" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="鼓掌" data_path="base" data-ubb="[鼓掌]" class="face-item face_10" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="鄙视" data_path="base" data-ubb="[鄙视]" class="face-item face_11" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="可爱" data_path="base" data-ubb="[可爱]" class="face-item face_12" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="闭嘴" data_path="base" data-ubb="[闭嘴]" class="face-item face_13" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="疑问" data_path="base" data-ubb="[疑问]" class="face-item face_14" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="抓狂" data_path="base" data-ubb="[抓狂]" class="face-item face_15" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="惊讶" data_path="base" data-ubb="[惊讶]" class="face-item face_16" set_value="dptjlbm_text"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="可怜" data_path="base" data-ubb="[可怜]" class="face-item face_17" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="弱" data_path="base" data-ubb="[弱]" class="face-item face_18" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="强" data_path="base" data-ubb="[强]" class="face-item face_19" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="握手" data_path="base" data-ubb="[握手]" class="face-item face_20" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="拳头" data_path="base" data-ubb="[拳头]" class="face-item face_21" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="酒" data_path="base" data-ubb="[酒]" class="face-item face_22" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="玫瑰" data_path="base" data-ubb="[玫瑰]" class="face-item face_23" set_value="dptjlbm_text"></span>
                            </li>
                            <li>
                                <span title="打酱油" data_path="base" data-ubb="[打酱油]" class="face-item face_24" set_value="dptjlbm_text"></span>
                            </li>
                        </ul>
                    </div>
                </div>
       </a>
  <?php if(is_login()): ?><input type="button" name="" value="留言" onClick="comment(this)" class="liuybbr <?php echo ($isComment ? 'disccc' : ''); ?>"/><!--disccc-->
  <?php else: ?>
      <input type="button" name="" value="留言" onClick="window.location.href='<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>';" class="liuybbr"/><?php endif; ?>        
       
       <div class="cb"></div>
 </div><!--liuybb end-->
 <div class="liuylb">
  <ul id="comment-list">
     
<?php if(is_array($comment)): foreach($comment as $ckey=>$com): ?><li <?php if($com['user_id'] == getUserC('SE_USER_ID')): ?>style="border:2px solid #bbdcff"<?php endif; ?>>
    <div class="liuylbt">
    <?php if($com['head_pic']): ?><img src="__ROOT__/<?php echo ($com['head_pic']); ?>" alt="" style="width:40px;">
    <?php else: ?>
      <img src="http://aliyun-cdn.hypebeast.cn/cn.hypebeast.com/files/2016/10/interview-with-shawn-yue-3-1.jpg?resize=40,40" alt=""><?php endif; ?> 
      <span class="liuylbtm"><?php echo ($com['user_name']); ?></span>
      <span class="liuylbtmr"><?php echo from_time($com['times'],'');?></span>
      <div class="liuylbtr">
         <span class="commentlr fa fa-thumbs-up <?php echo ($com['is_true'] ? '' : 'col999'); ?> iconstylelist" onClick="praiseList(this,<?php echo ($com['id']); ?>,<?php echo ($com['user_id']); ?>)"></span>
         <span class="col333 vam f12 mh5">点赞</span>
         <span class="commentlrr list_nums"><?php echo ($com['like_count']); ?></span>
      </div>
     <div class="cb"></div>
    </div><!--liuylbt end-->
    <div class="liuylbb">
     <div class="liuylbbt"><?php echo ($com['content']); ?></div>
     <div class="liuylbbdh">
     <?php if(is_array($com['reply_list'])): foreach($com['reply_list'] as $replykey=>$reply): if($reply['user_id'] == 0): ?><p>
                <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                <span class="vam mh5" style="color:#023867;">@<?php echo ($com['user_name']); ?></span><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
              </p>
          <?php else: ?>
              <p>
              <span class="vam mr5"><?php echo ($com['user_name']); ?></span><span class="vam" style="color:#023867;">@</span>
              <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5"><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
              </p><?php endif; endforeach; endif; ?>
     </div><!----->
     <div class="liuylbbb" style="<?php if(!$com['reply_list']): ?>display:none;<?php endif; ?>">
         <?php if($com['open_reply']): ?>
            <span class="fa fa-commenting-o f18 huifu"></span>
         <?php else: ?>
             <span class="fa fa-commenting-o f18" style="color:#ccc;"></span><?php endif; ?>
         <b class="ckdh">查看对话</b>
         <b class="xsjsnr" style="display:none">显示较少内容</b>
     </div>
     <div class="duih">
     <span class="duihl fa fa-user"></span>
     <input type="text" name="" placeholder="回复···" class="duihm dptjlbm_text_<?php echo ($com['id']); ?> content_input"/>
     <input type="button" name="" value="留言" onclick="mentReply(this,'<?php echo sign_url(array('article_id'=>$com['article_id'],'comment_id'=>$com['id']),U('Article/commentReply'));?>','<?php echo ($com['user_name']); ?>')" class="liuybbr2">
     <a class="icon27j emotion" style="background: none; position:relative; margin-top:2.5px; float:right; margin-right:10px;">
          <img src="__HOME__/images2/icon7b.png" alt="" style="width:25px; height:25px;"/>
          <span class="up-arrow1">◆</span><span class="up-arrow2">◆</span>
          <div node-type="face-box" class="face-wrapper-dw show_face2">
                    <div node-type="face-cont" class="wrapper-cont-dw">
                        <ul class="clear-g">
                            <li>
                                <span title="流汗" data_path="base" data-ubb="[流汗]" class="face-item face_01" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="钱" data_path="base" data-ubb="[钱]" class="face-item face_02" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="发怒" data_path="base" data-ubb="[发怒]" class="face-item face_03" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="浮云" data_path="base" data-ubb="[浮云]" class="face-item face_04" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="给力" data_path="base" data-ubb="[给力]" class="face-item face_05" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="大哭" data_path="base" data-ubb="[大哭]" class="face-item face_06" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="憨笑" data_path="base" data-ubb="[憨笑]" class="face-item face_07" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="色" data_path="base" data-ubb="[色]" class="face-item face_08" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="奋斗" data_path="base" data-ubb="[奋斗]" class="face-item face_09" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="鼓掌" data_path="base" data-ubb="[鼓掌]" class="face-item face_10" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="鄙视" data_path="base" data-ubb="[鄙视]" class="face-item face_11" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="可爱" data_path="base" data-ubb="[可爱]" class="face-item face_12" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="闭嘴" data_path="base" data-ubb="[闭嘴]" class="face-item face_13" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="疑问" data_path="base" data-ubb="[疑问]" class="face-item face_14" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="抓狂" data_path="base" data-ubb="[抓狂]" class="face-item face_15" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="惊讶" data_path="base" data-ubb="[惊讶]" class="face-item face_16" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="可怜" data_path="base" data-ubb="[可怜]" class="face-item face_17" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="弱" data_path="base" data-ubb="[弱]" class="face-item face_18" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="强" data_path="base" data-ubb="[强]" class="face-item face_19" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="握手" data_path="base" data-ubb="[握手]" class="face-item face_20" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="拳头" data_path="base" data-ubb="[拳头]" class="face-item face_21" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="酒" data_path="base" data-ubb="[酒]" class="face-item face_22" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="玫瑰" data_path="base" data-ubb="[玫瑰]" class="face-item face_23" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="打酱油" data_path="base" data-ubb="[打酱油]" class="face-item face_24" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                    </div>
                </div>
       </a>
     <div class="cb"></div>
     </div><!--duih end-->
     <div class="cb"></div>
    </div><!--liuylbb end-->
   </li><?php endforeach; endif; ?>   
  </ul>
  <div class="cb pt25"></div>
  <?php if($pageNumShown > 1): ?><a href="javascript:void(0)" class="more clickMoerPage" >
          <input class="morebtn" value="Load More" type="button" onClick="mobile.clickPage(this,<?php echo ($pageNumShown); ?>,'#comment-list','<?php echo U('Article/commentList','','');?>/id/<?php echo ($result['id']); ?>/p/');"/> 
       </a><?php endif; ?>
 </div><!--liuylb end-->
 <script>
 //点击加载更多，按钮必须是button
/**
 *THIS   this
 * totalPages  总页数
 * addEnd      追加内容节点
 * url               请求地址
 *  */
mobile={};
var page=2;
mobile.clickPage=function(THIS,totalPages,addEnd,url){    
    var _this=this;
    this.run=function(){
         if(page <= totalPages){  
            if($(THIS).attr('ajax') == 'ok') return;      
			if(page == totalPages) $(THIS).parents('a').hide(); //最后一页
             _this.getPage();
         }else{
			 $(THIS).parents('a').hide();
             //$(THIS).attr('disabled',true).val(window.LoadingCompleted);
         }             
    };  
    this.getPage=function(){
          $.ajax({
             type:'get',
             dataType: "text",
             url:url+page+'/req/app',
             success:function(response,status,xhr){
                 $(THIS).attr('ajax','').attr('disabled',false).val('Load More');
                 page++;
                 $(addEnd).append(response);
              },
              beforeSend:function(){
                  $(THIS).attr('ajax','ok').attr('disabled',true).val(window.Loading);
              },         
             error:function(xhr,errorText,errorType){
                 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
             }
         });              
    };  
    //运行
    _this.run();         
};	

$(function(){
	$('body').on('click','.ckdh',function(){
		 $(this).siblings(".xsjsnr").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").show()	  
	});		 
	
	$('body').on('click','.xsjsnr',function(){
		 $(this).siblings(".ckdh").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").hide()		
	});
	
	$('body').on('click','.huifu',function(){
		 $(this).parent(".liuylbbb").hide()
		 $(this).parent(".liuylbbb").siblings(".duih").show()
		 $(this).parents("li").addClass("now")		
	});
});
 </script>
 
 
 
 
 <div class="dptjlb" style="display:none">

       <!-- JiaThis Button BEGIN -->
<div class="jiathis_style_24x24 vat" style="display:inline-block">
	
   
	<a class="jiathis_button_tsina icon-weibo glyphs col000 mr5" style="font-size:20px"></a>
    <a class="jiathis_button_weixin icon-wechat glyphs col000"  style="font-size:20px"></a>
	
</div>
<span class="col000 f14 pl10 vab">Share this article</span>
<style>
 .dptjlb .jiathis_style_24x24 .jtico_tsina,.dptjlb .jiathis_style_24x24 .jtico_weixin,.dptjlb .jiathis_style_24x24 .jtico_fb{ background:none !important; display:none !important}
</style>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->

  
  <div class="dptjlbm">
   <form action="<?php echo U('Article/commnetForm');?>" class="comment" method="post">
       <input type="hidden"  name="id" value="<?php echo ($result['id']); ?>"/>
       <input type="hidden" name="score" value="0" /><!--kindeditor-->
       <textarea id="saytext" name="content" class="dptjlbm_text"></textarea>
       <a class="icon27j emotion" style="background: none; position:relative">
          <img src="__COMMON__/Images/show-fcde.png" />
          <span class="up-arrow1">◆</span><span class="up-arrow2">◆</span>
          <div node-type="face-box" class="face-wrapper-dw show_face2">
                    <div node-type="face-cont" class="wrapper-cont-dw">
                        <ul class="clear-g">
                            <li>
                                <span title="流汗" data_path="base" data-ubb="[流汗]" class="face-item face_01"></span>
                            </li>
                            <li>
                                <span title="钱" data_path="base" data-ubb="[钱]" class="face-item face_02"></span>
                            </li>
                            <li>
                                <span title="发怒" data_path="base" data-ubb="[发怒]" class="face-item face_03"></span>
                            </li>
                            <li>
                                <span title="浮云" data_path="base" data-ubb="[浮云]" class="face-item face_04"></span>
                            </li>
                            <li>
                                <span title="给力" data_path="base" data-ubb="[给力]" class="face-item face_05"></span>
                            </li>
                            <li>
                                <span title="大哭" data_path="base" data-ubb="[大哭]" class="face-item face_06"></span>
                            </li>
                            <li>
                                <span title="憨笑" data_path="base" data-ubb="[憨笑]" class="face-item face_07"></span>
                            </li>
                            <li>
                                <span title="色" data_path="base" data-ubb="[色]" class="face-item face_08"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="奋斗" data_path="base" data-ubb="[奋斗]" class="face-item face_09"></span>
                            </li>
                            <li>
                                <span title="鼓掌" data_path="base" data-ubb="[鼓掌" class="face-item face_10"></span>
                            </li>
                            <li>
                                <span title="鄙视" data_path="base" data-ubb="[鄙视]" class="face-item face_11"></span>
                            </li>
                            <li>
                                <span title="可爱" data_path="base" data-ubb="[可爱]" class="face-item face_12"></span>
                            </li>
                            <li>
                                <span title="闭嘴" data_path="base" data-ubb="[闭嘴]" class="face-item face_13"></span>
                            </li>
                            <li>
                                <span title="疑问" data_path="base" data-ubb="[疑问]" class="face-item face_14"></span>
                            </li>
                            <li>
                                <span title="抓狂" data_path="base" data-ubb="[抓狂]" class="face-item face_15"></span>
                            </li>
                            <li>
                                <span title="惊讶" data_path="base" data-ubb="[惊讶]" class="face-item face_16"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="可怜" data_path="base" data-ubb="[可怜]" class="face-item face_17"></span>
                            </li>
                            <li>
                                <span title="弱" data_path="base" data-ubb="[弱]" class="face-item face_18"></span>
                            </li>
                            <li>
                                <span title="强" data_path="base" data-ubb="[强]" class="face-item face_19"></span>
                            </li>
                            <li>
                                <span title="握手" data_path="base" data-ubb="[握手]" class="face-item face_20"></span>
                            </li>
                            <li>
                                <span title="拳头" data_path="base" data-ubb="[拳头]" class="face-item face_21"></span>
                            </li>
                            <li>
                                <span title="酒" data_path="base" data-ubb="[酒]" class="face-item face_22"></span>
                            </li>
                            <li>
                                <span title="玫瑰" data_path="base" data-ubb="[玫瑰]" class="face-item face_23"></span>
                            </li>
                            <li>
                                <span title="打酱油" data_path="base" data-ubb="[打酱油]" class="face-item face_24"></span>
                            </li>
                        </ul>
                    </div>
                    
                    
                </div>
         <!-- <div class="show_face">
                        <ul class="clear-g" style="border:none">
                            <li>
                                <span title="流汗" data_path="base" data-ubb="[流汗]" class="face-item face_01"></span>
                            </li>
                            <li>
                                <span title="钱" data_path="base" data-ubb="[钱]" class="face-item face_02"></span>
                            </li>
                            <li>
                                <span title="发怒" data_path="base" data-ubb="[发怒]" class="face-item face_03"></span>
                            </li>
                            <li>
                                <span title="浮云" data_path="base" data-ubb="[浮云]" class="face-item face_04"></span>
                            </li>
                            <li>
                                <span title="给力" data_path="base" data-ubb="[给力]" class="face-item face_05"></span>
                            </li>
                            <li>
                                <span title="大哭" data_path="base" data-ubb="[大哭]" class="face-item face_06"></span>
                            </li>
                            <li>
                                <span title="憨笑" data_path="base" data-ubb="[憨笑]" class="face-item face_07"></span>
                            </li>
                            <li>
                                <span title="色" data_path="base" data-ubb="[色]" class="face-item face_08"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="奋斗" data_path="base" data-ubb="[奋斗]" class="face-item face_09"></span>
                            </li>
                            <li>
                                <span title="鼓掌" data_path="base" data-ubb="[鼓掌]" class="face-item face_10"></span>
                            </li>
                            <li>
                                <span title="鄙视" data_path="base" data-ubb="[鄙视]" class="face-item face_11"></span>
                            </li>
                            <li>
                                <span title="可爱" data_path="base" data-ubb="[可爱]" class="face-item face_12"></span>
                            </li>
                            <li>
                                <span title="闭嘴" data_path="base" data-ubb="[闭嘴]" class="face-item face_13"></span>
                            </li>
                            <li>
                                <span title="疑问" data_path="base" data-ubb="[疑问]" class="face-item face_14"></span>
                            </li>
                            <li>
                                <span title="抓狂" data_path="base" data-ubb="[抓狂]" class="face-item face_15"></span>
                            </li>
                            <li>
                                <span title="惊讶" data_path="base" data-ubb="[惊讶]" class="face-item face_16"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="可怜" data_path="base" data-ubb="[可怜]" class="face-item face_17"></span>
                            </li>
                            <li>
                                <span title="弱" data_path="base" data-ubb="[弱]" class="face-item face_18"></span>
                            </li>
                            <li>
                                <span title="强" data_path="base" data-ubb="[强]" class="face-item face_19"></span>
                            </li>
                            <li>
                                <span title="握手" data_path="base" data-ubb="[握手]" class="face-item face_20"></span>
                            </li>
                            <li>
                                <span title="拳头" data_path="base" data-ubb="[拳头]" class="face-item face_21"></span>
                            </li>
                            <li>
                                <span title="酒" data_path="base" data-ubb="[酒]" class="face-item face_22"></span>
                            </li>
                            <li>
                                <span title="玫瑰" data_path="base" data-ubb="[玫瑰]" class="face-item face_23"></span>
                            </li>
                            <li>
                                <span title="打酱油" data_path="base" data-ubb="[打酱油]" class="face-item face_24"></span>
                            </li>
                        </ul>
          </div>-->
       </a>
       <!--<span id="comment" class="fr f16 lh30 col000" style="cursor:pointer">评论</span>-->
       <input type="submit" id="comment" msg="<?php echo isL(L('PleaseLater'),'提交中...');?>"
       style="height:28px;padding:0 16px;color:#000;background:#fff; border:1px solid #dfdfdf;font-size:14px;float:right;display:block;position:absolute; right:5px; bottom:5px; border-radius:5px;" 
       value="<?php echo isL(L('Comment'),'评论');?>" info-text="comment-t1"/>
   </form>    
  </div>
  <!--<p class="tsy comment-t" style="text-align:left;margin-top:-10px;border-top:none;">评论提示层</p>-->
  <script type="text/javascript">       
         //提交评论
	    formInit('.comment',true,true,true,true);    
  </script> 
   <div style="display:inline-block;" class="rating-static-module pl5"> 
      <label><span class="Hui-iconfont Hui-iconfont-cang"></span></label><!--全星CLASS Hui-iconfont-cang-selected-->
      <label><span class="Hui-iconfont Hui-iconfont-cang "></span></label>
      <label><span class="Hui-iconfont Hui-iconfont-cang " ></span></label>
      <label><span class="Hui-iconfont Hui-iconfont-cang"></span></label>
      <label><span class="Hui-iconfont Hui-iconfont-cang"></span></label>
    </div>
  <span class="lh22 f14 col000 pl10 vat pt5" style="display:inline-block">Wonderful index</span>
  
  <!--这里限制用户点击数-->
  <?php if($clicks['id']){ ?>
     <a class="icon27i  show-praise icon27inow">
     <i class="fa fa-thumbs-up"></i><?php echo isL(L('Like'),'点赞');?>(<span class="like_count"><?php echo ($result['like_count']); ?></span>)
     </a>
  <?php }else{ ?>
     <?php if(is_login()){ ?>
         <a class="icon27i  show-praise" href="javascript:void(0);" onClick="praise()">
         <i class="fa fa-thumbs-up"></i><?php echo isL(L('Like'),'点赞');?>(<span class="like_count"><?php echo ($result['like_count']); ?></span>)
         </a>
     <?php }else{ ?>
         <a class="icon27i  show-praise" href="<?php echo U('Login/index','','');?>?url=<?php echo base64_encode(get_url());?>">
         <i class="fa fa-thumbs-up"></i><?php echo isL(L('Like'),'点赞');?>(<span class="like_count"><?php echo ($result['like_count']); ?></span>)
         </a>     
     <?php } ?>
   <?php } ?>
   <!--这里限制用户点击数-->
   
   <!--这里不限制用户点击数
     <a href="javascript:void(0);" onClick="praise()" class="icon27i fr pr20 show-praise">
     <i class="fa fa-thumbs-o-up"></i><?php echo isL(L('Like'),'点赞');?>(<span class="like_count"><?php echo ($result['like_count']); ?></span>)
     </a>
     <!--不显示"谢谢你的赞"提示语
       <span class="fr pr20 like-success" style="position:relative;top:-6px;color:green;display:none;"><?php echo isL(L('ForYourPraise'),'谢谢你的赞');?></span>
       这里不限制用户点击数-->
 </div><!---->
 
 
 <div class="pjlb" style="display:none">
  <ul id="comment-list">
<?php if(is_array($comment)): foreach($comment as $ckey=>$com): ?><li <?php if($com['user_id'] == getUserC('SE_USER_ID')): ?>style="border:2px solid #bbdcff"<?php endif; ?>>
    <div class="liuylbt">
    <?php if($com['head_pic']): ?><img src="__ROOT__/<?php echo ($com['head_pic']); ?>" alt="" style="width:40px;">
    <?php else: ?>
      <img src="http://aliyun-cdn.hypebeast.cn/cn.hypebeast.com/files/2016/10/interview-with-shawn-yue-3-1.jpg?resize=40,40" alt=""><?php endif; ?> 
      <span class="liuylbtm"><?php echo ($com['user_name']); ?></span>
      <span class="liuylbtmr"><?php echo from_time($com['times'],'');?></span>
      <div class="liuylbtr">
         <span class="commentlr fa fa-thumbs-up <?php echo ($com['is_true'] ? '' : 'col999'); ?> iconstylelist" onClick="praiseList(this,<?php echo ($com['id']); ?>,<?php echo ($com['user_id']); ?>)"></span>
         <span class="col333 vam f12 mh5">点赞</span>
         <span class="commentlrr list_nums"><?php echo ($com['like_count']); ?></span>
      </div>
     <div class="cb"></div>
    </div><!--liuylbt end-->
    <div class="liuylbb">
     <div class="liuylbbt"><?php echo ($com['content']); ?></div>
     <div class="liuylbbdh">
     <?php if(is_array($com['reply_list'])): foreach($com['reply_list'] as $replykey=>$reply): if($reply['user_id'] == 0): ?><p>
                <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                <span class="vam mh5" style="color:#023867;">@<?php echo ($com['user_name']); ?></span><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
              </p>
          <?php else: ?>
              <p>
              <span class="vam mr5"><?php echo ($com['user_name']); ?></span><span class="vam" style="color:#023867;">@</span>
              <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5"><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
              </p><?php endif; endforeach; endif; ?>
     </div><!----->
     <div class="liuylbbb" style="<?php if(!$com['reply_list']): ?>display:none;<?php endif; ?>">
         <?php if($com['open_reply']): ?>
            <span class="fa fa-commenting-o f18 huifu"></span>
         <?php else: ?>
             <span class="fa fa-commenting-o f18" style="color:#ccc;"></span><?php endif; ?>
         <b class="ckdh">查看对话</b>
         <b class="xsjsnr" style="display:none">显示较少内容</b>
     </div>
     <div class="duih">
     <span class="duihl fa fa-user"></span>
     <input type="text" name="" placeholder="回复···" class="duihm dptjlbm_text_<?php echo ($com['id']); ?> content_input"/>
     <input type="button" name="" value="留言" onclick="mentReply(this,'<?php echo sign_url(array('article_id'=>$com['article_id'],'comment_id'=>$com['id']),U('Article/commentReply'));?>','<?php echo ($com['user_name']); ?>')" class="liuybbr2">
     <a class="icon27j emotion" style="background: none; position:relative; margin-top:2.5px; float:right; margin-right:10px;">
          <img src="__HOME__/images2/icon7b.png" alt="" style="width:25px; height:25px;"/>
          <span class="up-arrow1">◆</span><span class="up-arrow2">◆</span>
          <div node-type="face-box" class="face-wrapper-dw show_face2">
                    <div node-type="face-cont" class="wrapper-cont-dw">
                        <ul class="clear-g">
                            <li>
                                <span title="流汗" data_path="base" data-ubb="[流汗]" class="face-item face_01" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="钱" data_path="base" data-ubb="[钱]" class="face-item face_02" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="发怒" data_path="base" data-ubb="[发怒]" class="face-item face_03" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="浮云" data_path="base" data-ubb="[浮云]" class="face-item face_04" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="给力" data_path="base" data-ubb="[给力]" class="face-item face_05" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="大哭" data_path="base" data-ubb="[大哭]" class="face-item face_06" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="憨笑" data_path="base" data-ubb="[憨笑]" class="face-item face_07" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="色" data_path="base" data-ubb="[色]" class="face-item face_08" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="奋斗" data_path="base" data-ubb="[奋斗]" class="face-item face_09" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="鼓掌" data_path="base" data-ubb="[鼓掌]" class="face-item face_10" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="鄙视" data_path="base" data-ubb="[鄙视]" class="face-item face_11" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="可爱" data_path="base" data-ubb="[可爱]" class="face-item face_12" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="闭嘴" data_path="base" data-ubb="[闭嘴]" class="face-item face_13" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="疑问" data_path="base" data-ubb="[疑问]" class="face-item face_14" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="抓狂" data_path="base" data-ubb="[抓狂]" class="face-item face_15" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="惊讶" data_path="base" data-ubb="[惊讶]" class="face-item face_16" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                        <ul class="clear-g">
                            <li>
                                <span title="可怜" data_path="base" data-ubb="[可怜]" class="face-item face_17" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="弱" data_path="base" data-ubb="[弱]" class="face-item face_18" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="强" data_path="base" data-ubb="[强]" class="face-item face_19" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="握手" data_path="base" data-ubb="[握手]" class="face-item face_20" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="拳头" data_path="base" data-ubb="[拳头]" class="face-item face_21" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="酒" data_path="base" data-ubb="[酒]" class="face-item face_22" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="玫瑰" data_path="base" data-ubb="[玫瑰]" class="face-item face_23" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                            <li>
                                <span title="打酱油" data_path="base" data-ubb="[打酱油]" class="face-item face_24" set_value="dptjlbm_text_<?php echo ($com['id']); ?>"></span>
                            </li>
                        </ul>
                    </div>
                </div>
       </a>
     <div class="cb"></div>
     </div><!--duih end-->
     <div class="cb"></div>
    </div><!--liuylbb end-->
   </li><?php endforeach; endif; ?>   </ul>
 
  <a href="javascript:void(0)" class="more mb20 clickMoerPage"  <?php if($pageNumShown <= 1){ ?>style="display:none;"<?php } ?>>
         <input class="morebtn" value="Load More" type="button" onClick="clickMoerPage(this,<?php echo ($pageNumShown); ?>,'<?php echo U('Article/commentList','','');?>?id=<?php echo ($result['id']); ?>&p={0}','#comment-list')">
     </a>
 </div><!--pjlb end-->
 

 
</div>

<?php $alist=M('article')->field('id,'.pfix('titletext').' AS titletext,thumb,addtime')->where(array('is_show'=>1,'is_recommend'=>1,'files_id'=>498))->order("comment DESC")->limit(6)->select(); ?>

<div class="hydy">
 
 <div class="hydyt">
  <p class="pb40"><?php echo isL(L('WelcomeToSubscribe'),'欢迎订阅<br/>YOUDI WU最新资讯');?></p>
  <form method="get" action="<?php echo U('/EmailSubscribe/saveEmail');?>" class="save-email1">
  <input type="text"name="email"  class="hydyt_input required email" info-text="emailct-text" error-info="<?php echo isL(L('EnterYourEmail'),'请输入您的电邮地址');?>" error-other="<?php echo isL(L('EmailFormatError'),'请输入正确的电邮地址');?>"/>
  <input type="submit" name="" value="Sumbit" class="hydyt_btn"/>
  </form>
  
  <div class="cb"></div>
  <!--<p class="colf00 f12 lh28 emailct-text" style="display:none">提示层</p>-->
 </div><!--hydyt end-->
  <script type="text/javascript">
     //邮件订阅2
     formInit('.save-email1',true,true,true,true);
</script>      
 <div class="hydyb">
  <h1 class="hydybt">Popular news</h1>
  <div class="hydybb">
   <ul>
   <?php if(is_array($alist)): foreach($alist as $key=>$li): ?><li>
         <div class="hydybbl"><a href="<?php echo U('/Article/content',array('html'=>$li['id']));?>"><img src="__ROOT__/<?php echo ($li['thumb']); ?>" alt=""/></a></div>
         <div class="hydybbr">
          <p><?php echo (date('Y.m.d',$li['addtime'])); ?></p>
          <a href=""><h1><?php echo (show_str($li['titletext'],30)); ?></h1></a>
         </div>
         <div class="cb"></div>
        </li><?php endforeach; endif; ?>
   </ul>
  </div>
 </div><!--hydyb end-->
</div><!--hydy end-->
<div class="cb"></div>



</div><!--zxzx end-->

<div class="dptj" style="padding-top:30px;">
 <div class="alsot">you may also like</div>
 <div class="cb pv15"></div>
 <div class="also">
  <ul>
       <?php if(is_array($tres)): foreach($tres as $tk=>$t): ?><li>
               <a href="<?php echo U('Article/content',array('html'=>$t['id']));?>">
                    <img src="__ROOT__/<?php echo ($t['thumb']); ?>" alt="">
                    <p><?php echo (sub_str($t['titletext'],30)); ?></p>
               </a>
           </li><?php endforeach; endif; ?>
       <div class="cb pb20"></div>
  </ul>
 </div><!--also end-->
 <div class="alsot">what to ready next</div>
 <div class="what2">
 <div class="zxlb">
  <ul id="article-list"></ul>
 </div><!--zxlb end-->
 <a href="javascript:void(0)" class="more" style="margin-top:-10px;<?php if($pageSum <= 1){ ?>style="display:none;"<?php } ?>">
         <input type="button" onClick="clickMoerPage2(this,<?php echo ($pageSum); ?>,'<?php echo U('Article/index','','');?>?id=<?php echo ($result['id']); ?>&p={0}','#article-list')"
          class="morebtn" value="Load More">
     </a>
 </div><!--what2 end-->
 <div class="cb"></div>
</div><!--dptj end-->
 
<!--content end-->
  <div class="wrap fhdbbox"><div class="pv20 tl"><span class="fhdb"><span class="icon19"><span class="glyphs icon-arrowUp" style="line-height:30px;"></span></span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span></div></div>

 <div class="footer">
  <div class="footerm">
      <ul>
        <li>
         <h1><?php echo isL(L('News1'),'最新资讯');?><span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
         <div class="fh1b" >
         <div style="position:relative;overflow:hidden">
         <form method="get" action="<?php echo U('/EmailSubscribe/saveEmail');?>" class="save-email">
         <!--<input type="text" name="" value="订阅电子报" onfocus="if(value=='订阅电子报') {value=''}" onblur="if (value=='') {value='订阅电子报'}"  class="femail">-->
         <input type="text" name="email" placeholder="<?php echo isL(L('SubscribeTo'),'订阅电子报');?>"  class="femail required email" info-text="email-text" error-info="<?php echo isL(L('EnterYourEmail'),'请输入您的电邮地址');?>" error-other="<?php echo isL(L('EmailFormatError'),'请输入正确的电邮地址');?>">
         <span class="glyphs icon-contact dydzb"></span>
          <input value="" class="xshuo_btn" info-text="email-text" type="submit"  style="position:absolute; right:0; top:-100%; height:35px;/*display:none*/">
         <!-- <p class="colf00 f12 lh28 email-text" style="display:none">您已在YOUDI中有注册账户。</p><!--已注册的提示-->
          <!--<p style="color:#090; background:#fff; position:absolute; left:0; top:0; width:100%; height:100%; line-height:35px; font-size:14px; display:none">感谢您的注册</p><!--注册成功提示-->
          </form>
         </div>
              <script type="text/javascript">
			     //邮件订阅2
                 formInit('.save-email',true,true,true,true);
            </script>            
         <!--<input type="email" name="" value="" placeholder="<?php echo isL(L('SubscribeTo'),'订阅电子报');?>" class="femail">-->
         
         
         <p class="dydzbtxt">
             <?php echo isL(L('ReadPrivacy'),'阅读我们的隐私与Cookie政策');?>&nbsp;
             <a href="<?php echo U('Modular/privacy',array('html'=>$files[7][C('B_6')]['id']));?>" class="djcc"><?php echo isL(L('ClickHere'),'点击此处');?></a>
         </p>
         </div>
         <h1><?php echo isL(L('AboutYourIssue'),'您有什么话想对我说？');?><span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
         <div class="fh1b" style="">
         <div class="xshuo">
            <form action="<?php echo U('/Message/words');?>" id="msg-post" method="post"> 
             <input type="hidden" name="submit" value="msg-bottoms" />   
             <input type="text" name="content" value="" class="xshuo_input required" info-text="content-text" error-info="<?php echo isL(L('Required'),'必填栏');?>"/><input type="submit" id="msg-bottoms"  value="" class="xshuo_btn" info-text="content-text"/>
             <div class="cb"></div>
             <p class="colf00 f12 lh28 content-text" style="display:none;padding-left:0;"></p>
           </form>   
             <script type="text/javascript">
				 //留言给我们
				 formInit('#msg-post',true,true,true,true);
            </script>          
         </div>
        </div>
         <h1><?php echo isL(L('PaymentMethod'),'付款方式');?><span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
         <div class="fh1b">
         <div class="fkfs footer-paymentMethods">
         <a class="paymentType masterCard "></a>
         <a class="paymentType visa"></a>
         <a  class="paymentType americanexpress"></a>
         <a  class="paymentType paypal"></a>
         <a class="paymentType alipay"></a>
         <a class="icon13b" style="background-position:center center; background-size:50px auto;"></a>
         </div>
         </div>
        </li>
        <li>
        <h1><?php echo isL(L('Country'),'国家');?><span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
        <div class="fh1b">
         <div class="select_box" style=" background:none"><span style="display:block; position:absolute; right:0; top:0; width:40px; height:37px;" class="zhez"></span>
         <div style="cursor: pointer;" class="select_showbox faa"><?php if($_COOKIE['think_language'] == 'en-us'): echo isL(L('English'),'美国'); else: echo isL(L('CHINA'),'中国'); endif; ?></div>
         <ul class="select_option" style="display: none;">
         <li class="fe"><a style="display:block; color:#333" href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'en-us'));?>"><?php echo isL(L('English'),'美国');?></a></li>
         <li class="faa"><a style="display:block;color:#333" href="<?php echo U('/Language/index',array(C('VAR_LANGUAGE')=>'zh-cn'));?>"><?php echo isL(L('CHINA'),'中国');?></a></li>
         </ul>
         </div>
    </div>
     <h1>FOLLOW US<span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
     <div class="fllow fh1b">
     <a href="http://weibo.com/p/1006061827314140/home?from=page_100606&mod=TAB&is_all=1#place
" target="_blank" class="icon-weibo glyphs"></a>


      <?php if(is_mobile()): ?><a href="javascript:void(0)" target="_blank" class="icon-wechat glyphs tcwx2" ></a>
      <?php else: ?>
      <a href="/Modular/weiXin" target="_blank" class="icon-wechat glyphs tcwx" ></a><?php endif; ?>

     <a href="https://www.instagram.com/youdiwu/" target="_blank" class="icon-instagram glyphs" ></a>
     <a href="https://www.youtube.com" target="_blank" class="icon-youtube glyphs"></a>
     <a href="https://www.facebook.com/YOUDIWUHEIHEI" target="_blank" class="icon-facebook glyphs"></a>
     <a href="https://twitter.com/WuYoudi" target="_blank" class="icon-twitter glyphs"></a>
     <?php if(is_mobile()): else: ?>
      <img src="__HOME__/images/1118.jpg"  alt=""/><?php endif; ?>
      
     </div>
        </li>
        <li>
        <div class="kfzx">
          <h1><?php echo isL(L('CustomerService'),'客服中心');?><span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
          <div class="fh1b">
         <!-- <p><a href="<?php echo U('Modular/pubarticle',array('html'=>$files[7][C('B_1')]['id']));?>"><?php echo ($files[7][C('B_1')]['titletext']); ?></a></p>-->
           <p><a href="<?php echo U('Modular/needHelp',array('html'=>$files[7][C('B_1')]['id']));?>"><?php echo ($files[7][C('B_1')]['titletext']); ?></a></p>
           
           <!--<?php if(is_mobile()){ ?>
              <p><a href="<?php echo U('Comment/index');?>"><?php echo ($files[7][C('B_2')]['titletext']); ?></a></p>
           <?php }else{ ?>
              <p><a href="<?php echo U('Modular/shipping',array('html'=>$files[7][C('B_2')]['id']));?>"><?php echo ($files[7][C('B_2')]['titletext']); ?></a></p>   
           <?php } ?>-->
           <p><a href="<?php echo U('Modular/shipping',array('html'=>$files[7][C('B_2')]['id']));?>"><?php echo ($files[7][C('B_2')]['titletext']); ?></a></p>   
          
          <p><a href="<?php echo U('Modular/refund',array('html'=>$files[7][C('B_3')]['id']));?>" ><?php echo ($files[7][C('B_3')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/problem',array('html'=>$files[7][C('B_4')]['id']));?>" ><?php echo ($files[7][C('B_4')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/pubarticle',array('html'=>$files[7][C('B_5')]['id']));?>"><?php echo ($files[7][C('B_5')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/privacy',array('html'=>$files[7][C('B_6')]['id']));?>" ><?php echo ($files[7][C('B_6')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/needHelp',array('html'=>$files[7][C('B_7')]['id']));?>" ><?php echo ($files[7][C('B_7')]['titletext']); ?></a></p>
          <div class="cb pb15 phonecb"></div>
          </div>
        </div>
        <div class="kfzx">
          <h1>关于 YOUDI WU<span class="glyphs icon-more fr  colaaa pl10 pr10"></span></h1>
          <div class="fh1b">
          <p><a href="<?php echo U('Modular/about',array('html'=>$files[3][C('A_1')]['id']));?>" ><?php echo ($files[3][C('A_1')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/opportunity',array('html'=>$files[3][C('A_2')]['id']));?>" ><?php echo isL(L('CareerOpportunities'),'职业机会');?></a></p>
          <p><a href="<?php echo U('Modular/pubarticle',array('html'=>$files[3][C('A_3')]['id']));?>" ><?php echo ($files[3][C('A_3')]['titletext']); ?></a></p>
          <p><a href="<?php echo U('Modular/idea',array('html'=>$files[3][C('A_7')]['id']));?>" ><?php echo isL(L('BrandIdea'),'品牌理念');?></a></p>
          <p><a href="<?php echo U('/Modular/download');?>" >YOUDI WU APP</a>
          <p><a href="<?php echo U('Comment/index');?>" ><?php echo isL(L('CustomerReviews'),'YOUDI WU累计评论');?></a></p>
          <p><a href="<?php echo U('Modular/technology',array('html'=>$files[3][C('A_4')]['id']));?>" ><?php echo ($files[3][C('A_4')]['titletext']); ?></a></p>
          <div class="cb pb15 phonecb"></div>
          </div>
        </div>
        <div class="cb"></div>
       
        </li>
        <div class="cb"></div>
      </ul>
      <?php if(is_mobile()): ?><div class="colb0 f13 pl10 lh20 pt30"><span class="logo2">&nbsp;</span>以及 "YOUDI WU" <br/>图形均为youdi-wu.com Limited的商标。</div>
      <?php else: ?>
      <div class="colb0 f13 pl10 lh20 pt30"><span class="logo2">&nbsp;</span>以及 "YOUDI WU" 图形均为youdi-wu.com Limited的商标。</div><?php endif; ?>
      <!--<p class="colb0 f13 pl10 lh20 pt30"><?php echo get_con($langfx.'WEBBOTTOMINOF');?></p>-->
   </div>
 </div><!--footer end-->
 </div><!--sydb end-->
 <!----> 
 <div class="tcwxbg">
  <div class="tcwx">
    <span class="close tcwxclose"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
    <img src="__HOME__/images/1206.png" alt=""/>
    <h1>如何添加 YOUDI WU 微信公众号：</h1>
    <p>请拿起您的另一部分手机进行扫描</p>
  </div>
 </div>
 <!--手机版弹出微信 end-->
 <script>
  $(function(){
	  $(".fh1b p a").mouseenter(function(){
		  $(this).css("color","#fff")
		  $(this).parent("p").siblings("p").children("a").css("color","#aaa")
		  $(this).parents(".kfzx").siblings(".kfzx").children(".fh1b").children("p").children("a").css("color","#aaa")
		  })
		$(".fh1b p a").mouseout(function(){
		  $(".fh1b p a").css("color","#fff")
		  }) 
		  
		$(".select_box").click(function(){
			if ($(window).width() < 990) {
				$(".select_option").slideToggle()
				}
			
			})
			 
		$(".tcwx").hover(function(){
			$(".fllow img").toggle()
			})	
			
		
		$(window).load(function() { 
		hw=$(window).height()
		hd=$("body").height()
/*if(hd<hw){
			$(".fhdbbox").hide()
			$(".footer").addClass("footer2")
			}else{
				$(".footer").removeClass("footer2")
				} */
});

		//alert(hd)	
		
		if($(".box").hasClass("yyhddb")){
			$(".fhdbbox").hide()
			}	
		if($(".box").hasClass("wlgq")){
			$(".footer").removeClass("footer2")
			}				
		/**if(hd<hw){
			$(".footer").addClass("footer2")
			}else{
				$(".footer").removeClass("footer2")
				}	
	    	**/
		//弹层
	  $(".tcwx2").click(function(){						  
	      $(".tcwxbg").fadeIn("fast",function(){
		      $(".tcwx").slideDown();
		  })
	   });
	   //关闭层
	   $(".tcwxclose").click(function(){
	   $(".tcwx").slideUp("fast",function(){
		   $(".tcwxbg").fadeOut()
		   })
	   });	
	  })
	  
 </script> 
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
<script type="text/javascript">
    $(function(){
        setTimeout(function(){
            $('.ke-container').css('width','100%');
        },500);
        
    });    
</script>
</body>
</html>