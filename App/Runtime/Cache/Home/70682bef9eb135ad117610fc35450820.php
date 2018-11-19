<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>
<?php $emtp=$position; sort($emtp); ?>
<?php echo (strip_tags($goods[pfix('goods_name')])); ?> - 
<?php if(is_array($emtp)): foreach($emtp as $key=>$po): echo ($po['name']); ?> -<?php endforeach; endif; ?> <?php echo isL(L('Categories'),'产品分类');?> | YOUDI WU 中国
</title>
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

<!--<link rel="stylesheet" href="__HOME__/css/plugins.css" />-->
<link href="__HOME__/css/essentials.min.css" rel="stylesheet">    
<link href="__HOME__/css/detail.min.css" rel="stylesheet">
<link rel="stylesheet" href="__HOME__/css/iconfont.css" />
<link rel="stylesheet" href="__HOME__/css/font-awesome.min.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<link rel="stylesheet" href="__HOME__/css/swiper.css" />
<link rel="stylesheet" href="__HOME__/css/jquery.jqzoom.css" />
<link rel="stylesheet" href="__HOME__/css/style0417.css" />
<!--<script type="text/javascript" src="__COMMON__/Js/jquery-1.7.2.min.js"></script>-->
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>

<script type="text/javascript" src="__HOME__/js/global.js"></script>
<script type="text/javascript" src="__COMMON__/Js/alertAjax.js"></script>
<script type="text/javascript" src="__HOME__/js/swiper4.js"></script>
<script type="text/javascript" src="__HOME__/js/jquery.jqzoom-core.js"></script>
<!--[if IE]>
		<script src="__HOME__/js/html5shiv.min.js"></script>
<![endif]-->

<script type="text/javascript">
 /**
function pjbbtxtdj(){
$('.pjbbtxtdj').toggle(function(){
        $(this).text(window.DisplayPart);
        $(this).parent().siblings(".pjbbtxt").css("height","auto");
        },function(){
        $(this).text(window.ReadMore);
        $(this).parent().siblings(".pjbbtxt").css("height","25px");
});  
} **/  
/*无刷新收藏*/
function addColle(){
	var url="<?php echo U('Cookie/goodsCollect',array('id'=>$goods['id']));?>";
	$.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			 $('.falses').hide();
			 $('.trues').show();
			 //实时获得
              getNewcollection(); 
		  }
    });	
}
</script>
<script type="text/javascript">
var staticInfo = { fontVersion: 19, fontIconsVersion: 8, staticFolder: "", requireMainFolder: "__HOME__/js/farfetch/" };
</script>
<script>
 $(function(){
	var bigpicm = new Swiper('.bigpicm',{
  slidesPerView : 3,

loop:true,
      loopedSlides: 5,
  slideToClickedSlide: true,  
 navigation: {
    nextEl: '.bigpicmr,.bigpicmr2',
   prevEl: '.bigpicml',
  },
})


var smallpic = new Swiper('.smallpic',{
		 slidesPerView: 5,
		 spaceBetween : 10,
		//slidesPerGroup : 5,
      slideToClickedSlide: true,
	  centeredSlides: true,
	  loop:true,
      loopedSlides: 5, 
		
		})	
		smallpic.controller.control = bigpicm;
    bigpicm.controller.control = smallpic;

  var pictcm = new Swiper('.pictcm',{
		 slidesPerView: 2,
		 spaceBetween : 36,
      slideToClickedSlide: true,
		navigation: {
    nextEl: '.pictcmr',
    prevEl: '.pictcml',
  },
  pagination: {
    el: '.pictcb', 
  },
		})	
 $(".tcpicbg").click(function(){
	// alert(0)
	 $(".tcpicbg").fadeOut()
	 $(".pictc").slideUp()
	 })		
  
w1=$(".imgcc").width()
h1=$(".imgcc").height()		 	
  $('.jqzoom').jqzoom({
            zoomType: 'innerzoom',
            preloadImages: false,
            alwaysOn:false,
			xOffset:0,
			zoomWidth: 480,
			zoomHeight: 640,
			title: false,
			
        });
	 })
</script>
<style type="text/css">
.rating-static-module span{padding-left:0px;margin-left:0px;}

.bx-wrapper{ float:left}
.sliderProductModule .bx-wrapper{ float:none; height:auto}
.zjllb .bx-wrapper .bx-prev::before {
    content: "\e602";
    left: 10px;
    position: relative;
    top: 10px;
}
.sliderProductModule-fullscreen-container .button-white-circle:hover{ box-shadow:none !important; background:none}
.zjllb .bx-wrapper .bx-prev{font-family: "iconGliphs";
	background-color: white;
    border: medium none;
    border-radius: 20px; color:#222; font-size:13px;
    left: -14px;
    margin-bottom: auto;
    margin-top: auto;
    opacity: 0.8;
    top: calc(50% - 40px);height: 32px;
    outline: 0 none;
    position: absolute;
    width: 32px;
    z-index: 9;}
.zjllb .bx-wrapper .bx-next::before {
    content: "\e605";
    left: 10px;
    position: relative;
    top: 10px;
}
.zjllb .bx-wrapper .bx-next{font-family: "iconGliphs";
	background-color: white;
    border: medium none;
    border-radius: 20px; color:#222; font-size:13px;
    right: -14px;
    margin-bottom: auto;
    margin-top: auto;
    opacity: 0.8;
    top: calc(50% - 40px);height: 32px;
    outline: 0 none;
    position: absolute; 
    width: 32px;
    z-index: 9;}	
.zjllb .bx-wrapper .bx-next:hover,.zjllb .bx-wrapper .bx-next:hover{ box-shadow:1px 1px 0 #ccc inset; opacity:1}

.fwcnb img{max-width:100% !important;}

</style>

</head>
<body data-page="detail" class="globalPos__hide" style="overflow-x:hidden; width:100%;position: relative;">
<div class="box" style="overflow:hidden; width:100%">
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
<div class="bigpic">
 <div class="bigpicm"><span class="bigpicml"></span><span class="bigpicmr"></span><span class="bigpicmr2"></span></span><span class="bigpicml2"></span>
 <div class="swiper-wrapper">
 <?php if(is_array($goods['goods_photo'])): foreach($goods['goods_photo'] as $alkey=>$al): ?><div class="swiper-slide"><a href="__ROOT__/<?php echo ($al['img']); ?>" class="jqzoom"><img src="__ROOT__/<?php echo ($al['img']); ?>" alt="" class="imgcc"/></a></div><?php endforeach; endif; ?> 
 </div>
 </div><!--bigpicm end-->
</div><!--bigpic end-->
<div class="small">
 <div class="smalll">
  <div class="smallpic">
   <div class="swiper-wrapper">
   <?php if(is_array($goods['goods_photo'])): foreach($goods['goods_photo'] as $alkey=>$al): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($al['img']); ?>" alt=""/></div><?php endforeach; endif; ?> 
 </div>
  </div><!--smallpic end-->
 </div><!--smalll end-->
 <div class="cb"></div>
</div><!--small end-->
<div class="tcpicbg"></div>
<div class="pictc"><div class="pictcbox"><span class="pictcml"></span><span class="pictcmr"></span>
 <div class="pictcm">
   <div class="swiper-wrapper">
  <?php if(is_array($goods['goods_photo'])): foreach($goods['goods_photo'] as $alkey=>$al): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($al['img']); ?>" alt=""/></div><?php endforeach; endif; ?>
 </div>
 </div><!--pictcm end-->
 <div class="pictcb"></div>
</div></div><!--pictc end-->
<style>
 .zoomWrapperImage img{ width:auto !important; height:auto !important; }
</style>
<!---->




<div class="cpxqb">
<div class="cpxqbl" id="cpxqbl158">
  <div class="ljpj">
   <h1><span style="display:inline-block; line-height:30px; float:left"><?php echo isL(L('CumulativeEvaluation'),'累计评价');?></span><span class="ljpjsz"><?php echo ($goods['review_count'] ? $goods['review_count'] : 0); ?></span> 
   <div class="cb"></div>
   </h1>
   <div class="ljpjb">
    <div class="ljpjbl">
         <p><?php echo ($showScore); echo ($showScoreFloat); ?></p>
         <div class="rating-static-module" >
         
         
    <span class="<?php if($score >= 1 && (1+1) ==$points){ ?>rateit-halfselected svg<?php }else if($score >= 1){ ?>rateit-selected svg<?php }else{ ?>rateit svg<?php } ?>" style="margin:0; display:inline-block"></span>
    <span class="<?php if($score >= 2 && (2+1) ==$points){ ?>rateit-halfselected svg<?php }else if($score >=2){ ?>rateit-selected svg<?php }else{ ?>rateit svg<?php } ?>" style="margin:0; display:inline-block"></span>
    <span class="<?php if($score >= 3 && (3+1) ==$points){ ?>rateit-halfselected svg<?php }else if($score >= 3){ ?>rateit-selected svg<?php }else{ ?>rateit svg<?php } ?>" style="margin:0; display:inline-block"></span>
    <span class="<?php if($score >= 4 && (4+1) ==$points){ ?>rateit-halfselected svg<?php }else if($score >= 4){ ?>rateit-selected svg<?php }else{ ?>rateit svg<?php } ?>" style="margin:0; display:inline-block"></span>
    <span class="<?php if($score >= 5 && (5+1) ==$points){ ?>rateit-halfselected svg<?php }else if($score >= 5){ ?>rateit-selected svg<?php }else{ ?>rateit svg<?php } ?>" style="margin:0; display:inline-block"></span>              
         
         
         <!--星星标本
         <span class="rateit-selected svg" style="margin:0; display:inline-block"></span>  全黑色星
         <span class="rateit svg" style="margin:0; display:inline-block"></span> 灰色星星
        <span class="rateit-halfselected svg" style="margin:0; display:inline-block"></span> 半黑半灰星星
          -->
         
         </div>
         <!--<img src="__HOME__/images/img11.jpg" alt=""/><img src="__HOME__/images/img11.jpg" alt=""/><img src="__HOME__/images/img11.jpg" alt=""/>   -->
         <div class="cb"></div>
    </div>
    <div class="ljpjbr"  id="container" style="/*<?php if($goods['goods_app']['app_list']){ ?>height:180px;<?php }else{ ?>height:80px;<?php } ?>*/">
          <table width="100%" height="100%">
               <tr>
                   <td align="center" valign="middle" width="100%" height="100%"><img src="__HOME__/images/img4b.jpg" alt=""/></td>
               </tr>
          </table>
    </div>
    <div class="cb"></div>
   </div>
  </div><!--ljpj end-->
  <?php if(is_mobile()): else: ?>
  <div class="xspj cpxqpj pv20">
     <ul id="app-end">
         <?php if(is_array($goods['goods_app']['app_list'])): foreach($goods['goods_app']['app_list'] as $key=>$gs): ?><li>
    <?php if(is_mobile()): ?><div class="xspjt">
     <div style="display:inline-block;" class="rating-static-module fl"> 
            
            <span class="<?php if($gs['score'] >= 1){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 2){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 3){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 4){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 5){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
                     
                   
             <!--星星标题本
              <span class="rateit-selected fl svg"></span> 全黑色星
              <span class="rateit-halfselected fl svg"></span>半黑半灰的星星
             <span class="rateit fl svg"></span>灰色的星星
              -->
           </div>
           <span class="fr colaaa"><?php echo (date('Y.m.d',$gs["times"])); ?></span>
           <div class="cb pb5"></div>
           <div class="xspjtl">
       <p>
          <?php if($gs['is_open']){ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); ?>
          <?php }else{ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo isL(L('Anonymous'),'匿名用户');?>
          <?php } ?>
       </p>
       <p><?php if($gs['goods_attr']){ ?>
                  <?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?>
              <?php } ?>   </p>
      </div>
    </div><!--xspjt end-->
    
    
    <?php else: ?>
     <div class="xspjt">
      <div class="xspjtl">
       <p>
          <?php if($gs['is_open']){ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); ?>
          <?php }else{ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo isL(L('Anonymous'),'匿名用户');?>
          <?php } ?>
       </p>
       <p><?php if($gs['goods_attr']){ ?>
                  <?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?>
              <?php } ?>  
        </p>
      </div>
      <div class="xspjtr">
      <p class="colaaa"><?php echo (date('Y.m.d',$gs["times"])); ?></p>
      <div style="display:inline-block; float:right" class="rating-static-module"> 
            
            <span class="<?php if($gs['score'] >= 1){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 2){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 3){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 4){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 5){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
                     
                   
             <!--星星标题本
              <span class="rateit-selected fl svg"></span> 全黑色星
              <span class="rateit-halfselected fl svg"></span>半黑半灰的星星
             <span class="rateit fl svg"></span>灰色的星星
              -->
           </div>  
           <div class="cb"></div>
      </div>
      <div class="cb"></div>
     </div><!--xspjt end--><?php endif; ?>
     
     
     <div class="xspjb">
      <div class="pjbbtxt"><?php echo (strip_tags($gs["content"])); ?></div>
      
      <?php if(strlen(strip_tags($gs['content'])) > 200){ ?>
         <p class="tr">
            <a href="javascript:void(0)" class="pjbbtxtdj col2ab"><?php echo isL(L('ReadMore'),'继续阅读');?></a>
            <a href="javascript:void(0)" class="pjbbtxtdj2 col2ab" style="display:none"><?php echo isL(L('DisplayPart'),'显示较少内容');?></a>
         </p>
      <?php } ?>
      

     <?php if($gs['reply_list']): ?><div class="liuylbbdh mt10">
             <?php if(is_array($gs['reply_list'])): foreach($gs['reply_list'] as $reply_key=>$reply): if($reply['user_id'] == 0): ?><p>
                          <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                          <span class="vam mh5" style="color:#023867;">@  <?php if($gs['is_open']){ echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); }else{ echo isL(L('Anonymous'),'匿名用户'); } ?></span>
                         <?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
                      </p>
                  <?php else: ?>
                      <p>
                          <span class="vam mr5"><?php if($gs['is_open']){ echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); }else{ echo isL(L('Anonymous'),'匿名用户'); } ?></span>
                          <span class="vam" style="color:#023867;">@</span>
                          <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5"><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
                      </p><?php endif; endforeach; endif; ?>  
         </div><!----->
         <div class="liuylbbb"></span><b class="ckdh">查看对话</b><b class="xsjsnr" style="display:none">显示较少内容</b></div><?php endif; ?>
      
      
      <?php if($gs['slide_show']){ ?>                    
                  <div class="pjbbimg pt10">
                       <ul>
                            <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><li><img src="__ROOT__/<?php echo ($att); ?>" /><span></span></li><?php endforeach; endif; ?>
                       </ul>
                       
                       <div class="tctp">
 
 <div class="tpm">
 <span class="tpprev glyphs icon-prev"></span><span class="tpnext glyphs icon-next"></span>
 <!--<span class="close tpclose"><span class="glyphs icon-close" style="line-height:35px;"></span></span>-->
 <div class="text"></div>
 <b><img src="" alt=""/></b>
 </div>
</div><!--tctp end-->
                  </div>
      <?php } ?>
     
     </div><!--xspjb end-->
     
     <div class="pjiapic"></div><!--pjiapic end-->
     <div class="pjiapicm">
      <div class="pjiapicml pjiapic1">
        <div class="swiper-wrapper">
        <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($att); ?>" alt=""></div><?php endforeach; endif; ?> 
       </div>
   
      </div><!--pjiapicml end-->
      <!--<span class="pjiapicl fa  fa-caret-left "></span><span class="pjiapicr fa  fa-caret-right"></span>-->
      <div class="pjiapicb"></div>
      <div class="cb"></div>
     </div><!--pjiapicm end-->
     
     
     
    <!--
    
     <div class="pjbt">
     <p><span>Privacy：<?php echo (center_tsterisk($gs["user_name"],1)); ?></span><span class="fr"><?php echo (date('Y.m.d',$gs["times"])); ?></span></p> 
      <div>
          
          <?php if($gs['goods_attr']){ ?>
             <span><?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?></span>
          <?php } ?>
          
          <div style="display:inline-block; float:right" class="rating-static-module"> 
          
                 <?php $__FOR_START_23072__=0;$__FOR_END_23072__=$gs['score'];for($i=$__FOR_START_23072__;$i < $__FOR_END_23072__;$i+=1){ ?><span class="rateit-selected fl svg"></span><?php } ?>
           </div>  
      </div>
      <div class="cb"></div>
     </div>
     
     <div class="pjbb">
      <div class="pjbbtxt"><?php echo (strip_tags($gs["content"])); ?></div>
      <p class="tr"><span class="pjbbtxtdj"><?php echo isL(L('ReadMore'),'继续阅读');?></span><span class="pjbbtxtdj2"><?php echo isL(L('ReadMore'),'显示较少内容');?></span></p>
      <p class="tr"><span class="pjbbtxtdj">继续阅读</span><span class="pjbbtxtdj2" style="display:none; text-decoration:underline; cursor:pointer">显示较少内容</span></p>
      
      <?php if($gs['slide_show']){ ?>
          <div class="pjbbimg pt10" >
           <ul>
               <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><li><img src="__ROOT__/<?php echo ($att); ?>" /><span></span></li><?php endforeach; endif; ?>
           </ul>
           <div class="tctp">
 <span class="tpprev glyphs icon-prev"></span><span class="tpnext glyphs icon-next"></span>
 <div class="tpm"><span class="close tpclose" style="top:-30px; right:-40px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span><b><img src="" alt=""/></b></div>
</div>
          </div>
      <?php } ?>
     </div>-->
    </li><?php endforeach; endif; ?>

<style>
 .tctp{ background:rgba(0,0,0,0.7); width:100%; height:100%; position:fixed; left:0; top:0; z-index:300; display:none}
 .tpprev{ display:inline-block; cursor:pointer; width:30px; height:30px; position:absolute; left:0; top:50%; margin-top:-15px; color:#bababa; line-height:30px; text-align:center; font-size:60px; z-index:10; opacity:0.2}
 .tpprev:hover{ opacity:1}
 .tpnext{ display:inline-block; cursor:pointer; width:30px; height:30px; position:absolute; right:30px; top:50%; margin-top:-15px;color:#bababa; line-height:30px; text-align:center; font-size:60px; z-index:10;opacity:0.2}
 .tpnext:hover{ opacity:1}
 .tpm{ background:#444; width:640px; height:640px; position:absolute; left:50%; top:50%; margin:-320px 0 0 -320px; display:none}
 .tpm b{ display:table-cell; vertical-align:middle; text-align:center; width:520px; height:520px;  box-sizing:border-box}
 .tpm img{ max-width:640px; max-height:640px}
 .tpclose{top:10px; right:10px;}
 @media screen and (max-width:767px){
	  .tpm{ width:100%; left:0; height:80%; top:10%; margin:0}
	  .tpm b{ width:100%; height:100%}
	  .tpm img{max-width:100%;}
	  .tpprev{ left:0} 
	  .tpnext{ right:30px}
	  .tpclose{top:50px; right:10px;}
	 }
</style>
<?php if(is_mobile()): ?><!--<script type="text/javascript" src="__HOME__/js/jqueryph.js"></script>
<script type="text/javascript" src="__HOME__/js/jquery.mobile-1.4.5.min.js"></script>-->
<?php else: endif; ?>
<script type="text/javascript">
 $(function(){
	 $(".pjbbimg li img").click(function(){
		 $(this).parents(".xspjb").siblings(".pjiapic").fadeIn()
		 $(".pjiapicm").slideDown()
		 var pjiapic1 = new Swiper('.pjiapic1', {	
     // slidesPerView : 2,
//spaceBetween : 40,
   //navigation: {
   // nextEl: '.pjiapicr',
   // prevEl: '.pjiapicl',
  //},
  pagination: {
    el: '.pjiapicb', 
	clickable :true,
  },
    });
		 })
		 
 
$(".pjiapic").click(function(event) {  
       
	  $(this).fadeOut()	
	  $(".pjiapicm").slideUp();
});
	 
	 
	 $(".ckdh").click(function(){
		 $(this).siblings(".xsjsnr").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").show()
		  })
		$(".xsjsnr").click(function(){
		 $(this).siblings(".ckdh").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").hide()
		  })  
	 
	 
	  $('.pjbbtxtdj').click(function(){
        $(this).hide();
		$(this).siblings(".pjbbtxtdj2").show()
        $(this).parent().siblings(".pjbbtxt").css("height","auto");
       
});  	  
$('.pjbbtxtdj2').click(function(){
        $(this).hide();
		$(this).siblings(".pjbbtxtdj").show()
        $(this).parent().siblings(".pjbbtxt").css("height","22px");
       
});
  
/****/
var bigImg='';
	var _index=0;
	
		$(".tpclose").click(function(){
			$(this).parent(".tpm").hide()
		$(this).parents(".tctp").fadeOut()	
		
	});//关闭
	
	$(".tpm").click(function(e) {  
    $(this).show();  
        e.stopPropagation();  
});  
$(".tctp").click(function(event) {  
      $(".tpm").hide();  
	  $(this).fadeOut()	
});

	
	
	
	
	/**$(".pjbbimg li img").click(function(){
		$(this).parent("li").parent("ul").siblings(".tctp").fadeIn("fast",function(){
			$(this).children(".tpm").show()
			});
		bigImg=$(this).attr("src");//获取点击图片的地址
		$(this).parent("li").parent("ul").siblings(".tctp").children(".tpm").children("b").children("img").attr("src",bigImg); //更换大图的图片地址
		_index=$(this).parent("li").index();//保存图片的序列号
		i=$(this).parent("li").parent("ul").children("li").length;
		
	});***/


	$(".tpnext").bind("click",function(){
			 _index++;
			b= $(this).parents(".tctp").parent(".pjbbimg").children("ul").children("li").length
			//alert(b)
			 
			 if(_index>=b){_index=0}; 
			 bigImg=$(this).parents(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
		$(this).parent(".tpm").children("b").children("img").attr("src",bigImg);
			 })
	$(".tpm b").on("swiperight",function(){
		_index++;
		b= $(this).parent(".tpm").parent(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index>=b){_index=0}; 
		bigImg2=$(this).parent(".tpm").parent(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
  $(this).children("img").attr("src",bigImg2);
});
	
	$(".tpprev").click(function(){
		_index--; //序列号加1 _index+1
		b= $(this).parents(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index< 0){_index=b-1};
		//alert(_index)
		bigImg=$(this).parents(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
		$(this).parent(".tpm").children("b").children("img").attr("src",bigImg);
	});
	
	$(".tpm b").on("swipeleft",function(){
		_index--;
		b= $(this).parent(".tpm").parent(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index< 0){_index=b-1};
		bigImg2=$(this).parent(".tpm").parent(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
  $(this).children("img").attr("src",bigImg2);
});
    }); 

</script>
     
     </ul>
     <?php if($pageNumShown > 1){ ?>
         <a href="javascript:void(0)" class="more mt10">
             <input onClick="mobile.clickPage(this,'#app-end');" class="morebtn" value="Load More" type="button">
         </a>
     <?php } ?>
    </div><!--pjb end--><?php endif; ?>
  
 </div><!--cpxqbl end-->
 <div class="cpxqbr">
  <h1 class="f18 lh22 pb5"><b class="vam pr10" style="color:#000"><?php echo (strip_tags($goods['us_goods_name'])); ?> <img src="__HOME__/images/logo.png" width="100" /><!--YOUDI WU--></b><!--<span class="f13" style="font-weight:normal"><?php echo (strip_tags($goods['us_goods_name'])); ?></span>--></h1>
  <p class="pb10 f13 vam col000" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#333;">Number:<?php echo ($goods['goods_num']); ?></p>
  <p class="pb10 f16 vam col000 ">
     <span class="vam"><?php echo C('KTEC_H');?></span><span id="show-price" class="vam"><?php echo ($goods['goods_retail_price']); ?></span> 
     <span class="col999 f13 vam">(<?php echo isL(L('ValueAddedTax'),'含增值税');?>)</span>
   </p>
  <a href="javascript:void(0)" class="cmzn" style="float:right"><?php echo isL(L('SizeDescription'),'尺码指南');?></a>
  <!--<a href="javascript:void(0)" class="zjsc"><?php echo isL(L('TestAtHome'),'在家试穿');?></a>-->
  <div class="cb"></div>
  
  
   <?php if(is_array($goods['attrs'][$goods['id']])): foreach($goods['attrs'][$goods['id']] as $keyec=>$ar): $counts=count($ar['attr_list']); ?>
     <div class="cmbox">
         <?php if($goods['stock'] <= 0 && C('CHECK_STOCK') && $keyec == 0){ ?>
            <!--<span class="cmboxtxt cmboxtxtred" style="text-transform:uppercase">sold out</span>-->
            <span class="cmboxtxtred" style="text-transform:uppercase">sold out</span>
         <?php }else{ ?>
            <b class="<?php if($goods['stock'] > 0): ?>cmboxtxt<?php endif; ?> get-attr-val attr-class-<?php echo ($keyec); ?>" <?php if($counts==1){ ?>value="<?php echo ($ar['attr_list'][0]['attr_id']); ?>"<?php } ?> 
            attr-class="attr-class-<?php echo ($keyec); ?>"  info-class="error-info-<?php echo ($keyec); ?>" error1="<?php echo isL(L('PleaseSelect'),'请选择'); echo ($ar['attribute_name']); ?>" <?php if($goods['stock'] <= 0): ?>style="color:#999"<?php endif; ?>>
            <?php if($counts==1){ echo ($ar['attr_list'][0]['attr_value']); }else{ echo isL(L('Select'),'选择'); echo ($ar['attribute_name']); } ?>
            </b>
         <?php } ?> 
         
          <div class="cmboxb" style="display: none;background:#fff;">
               <ul style="background:#fff;">       
                   <?php if(is_array($ar['attr_list'])): foreach($ar['attr_list'] as $akey=>$av): ?><li value="<?php echo ($av["attr_id"]); ?>" price="<?php echo ($av['attr_price']); ?>" attr-stock="<?php echo ($av['attr_stock']); ?>">
                         <a href="javascript:void(0)" class="cmsj">
                           
                            <?php if($av['attribute_id'] == 72): ?>
                               <span class="ysq" style="background:#fff"></span>
                            <?php elseif($av['attribute_id'] == 73): ?>
                               <span class="ysq" style="background:yellow"></span>
                            <?php elseif($av['attribute_id'] == 74): ?>
                               <span class="ysq" style="background:orange"></span>     
                            <?php elseif($av['attribute_id'] == 75): ?>
                               <span class="ysq" style="background:red"></span>             
                            <?php elseif($av['attribute_id'] == 76): ?>
                               <span class="ysq" style="background:pink"></span>      
                            <?php elseif($av['attribute_id'] == 208): ?>
                               <span class="ysq" style="background:black"></span>         
                            <?php elseif($av['attribute_id'] == 209): ?>
                               <span class="ysq" style="background:green"></span>      
                            <?php elseif($av['attribute_id'] == 210): ?>
                               <span class="ysq" style="background:turquoise"></span>          
                            <?php elseif($av['attribute_id'] == 211): ?>
                               <span class="ysq" style="background:turquoise"></span>                          
                            <?php elseif($av['attribute_id'] == 212): ?>
                               <span class="ysq" style="background:turquoise"></span><?php endif; ?>
                           
                         <b class="ysqr"><?php echo ($av['attr_value']); ?></b></a>
                         <!--<span class="productDetailModule-dropdown-leftInStock color-black bold"><?php echo replace_isl('OnlyNInventory','仅%d库存',$av['attr_stock']);?></span>库存-->
                        </li><?php endforeach; endif; ?> 
               </ul>
          </div>
          
     </div>
    <!-- <p class="tsy error-info-<?php echo ($keyec); ?>" style="border:none;text-indent:0;position:relative;left:-5px;top:-5px;">提示层</p>--><?php endforeach; endif; ?>
  
  
   <div class="cb pb10"></div>
  <div class="w50 fl">
  <span class="pl7"><?php echo isL(L('Sold'),'已售');?>：<span class="f14"><?php echo ($goods['volume']); ?></span></span>
      <!--<input type="text"  value="<?php echo ($goods['volume']); ?>" class="cpxqbr_input" readonly /><span class="col999 pl5 vam"><?php echo isL(L('Sold'),'已售');?></span>-->
  </div>
  <div class="w50 fl tr">
   <span class="pr30"><?php echo isL(L('CumulativeEvaluation'),'累计评价');?>：<span class="f14"><?php echo ($goods['review_count'] ? $goods['review_count'] : 0); ?></span></span>
       <!--<input type="text" value="<?php echo ($goods['review_count'] ? $goods['review_count'] : 0); ?>" class="cpxqbr_input" readonly />
       <span class="col999 pl5 vam"><?php echo isL(L('CumulativeEvaluation'),'累计评价');?></span>-->
  </div>
  <div class="cb pb10"></div>
<div class="w50 fl">
<span class="pl7 fl" style="display:inline-block; line-height:26px;"><?php echo isL(L('PurchaseQuantity'),'购买数量');?>：</span>
 <a href="javascript:void(0)" class="icon39a fl" onClick="updateNumber('-','goods_num');">-</a>
  <input type="text" name="goods_num" value="1" readonly num="1" class="cpxqbr_input fl"/>
 <a href="javascript:void(0)" class="icon39a fl" onClick="updateNumber('+','goods_num');">+</a>
</div>
<div class="w50 fl tr">
 <span class="pr30"><?php echo isL(L('ResidualInventory'),'已剩库存');?>：<span class="f14"><?php echo ($goods['stock']); ?></span></span>
   <!-- <input type="text" name="" value="<?php echo ($goods['stock']); ?>" class="cpxqbr_input" readonly />
    <span class="col999 pl5 vam"><?php echo isL(L('InfoStock'),'库存');?></span>-->
 </div>
  <div class="cb pb20"></div>
  <!--<input type="button" onClick="toAjaxs('','<?php echo U("Cookie/goodsCollect",array('id'=>$goods['id']));?>','');" 
  value="<?php echo isL(L('AddWishOrder'),'加入愿望单');?>" class="cpxqbr_btn1 fl cpxqbr_btn1now"/>-->
   <?php if($goods['stock'] < 1 AND C('CHECK_STOCK')): ?><!--<input type="button" value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>" class="cpxqbr_btn2 fl" style="background: #eee url(__HOME__/images/icon41.png) no-repeat 95% center;color:#ccc;border:1px solid #ccc;cursor:auto;"/>-->
    <div class="jrgwdbtn" style="opacity:0.5">
    <input type="button" value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>"  class="btnno" />
   <span class="glyphs icon-bag btnb"></span>    
    </div>
  <?php else: ?>
     <!--<input type="button" value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>" onClick="toCart(false)" class="cpxqbr_btn2 fl"/>-->
     <div class="jrgwdbtn">
    <input type="button" value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>" onClick="toCart(false)" class="btn" />
   <span class="glyphs icon-bag btnb"></span>    
 </div><?php endif; ?> 
  <!--<input type="button" onClick="addColle();"   value="<?php echo isL(L('AddWishOrder'),'加入愿望单');?>" class="cpxqbr_btn1 fr cpxqbr_btn1now"/>-->
  
   <div class="trues jrywdbtn" style="<?php if(in_array($goods['id'],$cookie_collect)){ ?>display:block<?php }else{ ?>display:none;<?php } ?>">
        <input type="button"   value="<?php echo isL(L('OnTheWishList'),'在愿望单中');?>" style="cursor:auto" class="btnf5 trues zywdz" />
        <span class="fa fa-heart btnb" style="color:#222"></span><!--实心-->
   </div>    
   
   <div class="falses jrywdbtn" style="<?php if(!in_array($goods['id'],$cookie_collect)){ ?>display:block<?php }else{ ?>display:none;<?php } ?>">   
        <input type="button" onClick="addColle();"   value="<?php echo isL(L('AddWishOrder'),'加入愿望单');?>" class="btnf5 falses" />
        <span class="fa fa-heart-o btnb" style="color:#222"></span><!--空心-->
   </div>
  
  <div class="cb pb15"></div>
 <!-- <p class="cpxqbrtxt lh30 pl10"><?php echo isL(L('Hesitated'),'犹豫不绝？');?></p>
  <p class="cpxqbrtxt f12 pl10"> 
       <span class="line"> 
           <?php echo isL(L('FreeShipping'),'免费送货');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
           <span class="line"><?php echo isL(L('FreeReturns'),'免费退货');?></span>
  </p>-->
  <div class="fwcn">
   <ul>
   <?php if(is_mobile()): ?><li>
     <h1>商品评价<span class="fwz" style="display:none">+</span><span class="fwf" style="display:block">-</span></h1>
     <div class="fwcnb" style="display:block; background:#f5f5f5; padding:10px">
  
  <div class="xspj cpxqpj pv20">
     <ul id="app-end">
         <?php if(is_array($goods['goods_app']['app_list'])): foreach($goods['goods_app']['app_list'] as $key=>$gs): ?><li>
    <?php if(is_mobile()): ?><div class="xspjt">
     <div style="display:inline-block;" class="rating-static-module fl"> 
            
            <span class="<?php if($gs['score'] >= 1){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 2){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 3){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 4){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 5){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
                     
                   
             <!--星星标题本
              <span class="rateit-selected fl svg"></span> 全黑色星
              <span class="rateit-halfselected fl svg"></span>半黑半灰的星星
             <span class="rateit fl svg"></span>灰色的星星
              -->
           </div>
           <span class="fr colaaa"><?php echo (date('Y.m.d',$gs["times"])); ?></span>
           <div class="cb pb5"></div>
           <div class="xspjtl">
       <p>
          <?php if($gs['is_open']){ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); ?>
          <?php }else{ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo isL(L('Anonymous'),'匿名用户');?>
          <?php } ?>
       </p>
       <p><?php if($gs['goods_attr']){ ?>
                  <?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?>
              <?php } ?>   </p>
      </div>
    </div><!--xspjt end-->
    
    
    <?php else: ?>
     <div class="xspjt">
      <div class="xspjtl">
       <p>
          <?php if($gs['is_open']){ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); ?>
          <?php }else{ ?>
             <?php echo isL(L('Privacy'),'顾客');?>：<?php echo isL(L('Anonymous'),'匿名用户');?>
          <?php } ?>
       </p>
       <p><?php if($gs['goods_attr']){ ?>
                  <?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?>
              <?php } ?>  
        </p>
      </div>
      <div class="xspjtr">
      <p class="colaaa"><?php echo (date('Y.m.d',$gs["times"])); ?></p>
      <div style="display:inline-block; float:right" class="rating-static-module"> 
            
            <span class="<?php if($gs['score'] >= 1){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 2){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 3){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 4){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
            <span class="<?php if($gs['score'] >= 5){ ?>rateit-selected fl svg<?php }else{ ?>rateit fl svg<?php } ?>"></span>
                     
                   
             <!--星星标题本
              <span class="rateit-selected fl svg"></span> 全黑色星
              <span class="rateit-halfselected fl svg"></span>半黑半灰的星星
             <span class="rateit fl svg"></span>灰色的星星
              -->
           </div>  
           <div class="cb"></div>
      </div>
      <div class="cb"></div>
     </div><!--xspjt end--><?php endif; ?>
     
     
     <div class="xspjb">
      <div class="pjbbtxt"><?php echo (strip_tags($gs["content"])); ?></div>
      
      <?php if(strlen(strip_tags($gs['content'])) > 200){ ?>
         <p class="tr">
            <a href="javascript:void(0)" class="pjbbtxtdj col2ab"><?php echo isL(L('ReadMore'),'继续阅读');?></a>
            <a href="javascript:void(0)" class="pjbbtxtdj2 col2ab" style="display:none"><?php echo isL(L('DisplayPart'),'显示较少内容');?></a>
         </p>
      <?php } ?>
      

     <?php if($gs['reply_list']): ?><div class="liuylbbdh mt10">
             <?php if(is_array($gs['reply_list'])): foreach($gs['reply_list'] as $reply_key=>$reply): if($reply['user_id'] == 0): ?><p>
                          <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                          <span class="vam mh5" style="color:#023867;">@  <?php if($gs['is_open']){ echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); }else{ echo isL(L('Anonymous'),'匿名用户'); } ?></span>
                         <?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
                      </p>
                  <?php else: ?>
                      <p>
                          <span class="vam mr5"><?php if($gs['is_open']){ echo ($gs["full_name"]); ?> <?php echo ($gs["surname"]); }else{ echo isL(L('Anonymous'),'匿名用户'); } ?></span>
                          <span class="vam" style="color:#023867;">@</span>
                          <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5"><?php echo ($reply['content']); ?><span class="col999 pl15 f12"><?php echo from_time($reply['times'],'');?></span>
                      </p><?php endif; endforeach; endif; ?>  
         </div><!----->
         <div class="liuylbbb"></span><b class="ckdh">查看对话</b><b class="xsjsnr" style="display:none">显示较少内容</b></div><?php endif; ?>
      
      
      <?php if($gs['slide_show']){ ?>                    
                  <div class="pjbbimg pt10">
                       <ul>
                            <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><li><img src="__ROOT__/<?php echo ($att); ?>" /><span></span></li><?php endforeach; endif; ?>
                       </ul>
                       
                       <div class="tctp">
 
 <div class="tpm">
 <span class="tpprev glyphs icon-prev"></span><span class="tpnext glyphs icon-next"></span>
 <!--<span class="close tpclose"><span class="glyphs icon-close" style="line-height:35px;"></span></span>-->
 <div class="text"></div>
 <b><img src="" alt=""/></b>
 </div>
</div><!--tctp end-->
                  </div>
      <?php } ?>
     
     </div><!--xspjb end-->
     
     <div class="pjiapic"></div><!--pjiapic end-->
     <div class="pjiapicm">
      <div class="pjiapicml pjiapic1">
        <div class="swiper-wrapper">
        <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($att); ?>" alt=""></div><?php endforeach; endif; ?> 
       </div>
   
      </div><!--pjiapicml end-->
      <!--<span class="pjiapicl fa  fa-caret-left "></span><span class="pjiapicr fa  fa-caret-right"></span>-->
      <div class="pjiapicb"></div>
      <div class="cb"></div>
     </div><!--pjiapicm end-->
     
     
     
    <!--
    
     <div class="pjbt">
     <p><span>Privacy：<?php echo (center_tsterisk($gs["user_name"],1)); ?></span><span class="fr"><?php echo (date('Y.m.d',$gs["times"])); ?></span></p> 
      <div>
          
          <?php if($gs['goods_attr']){ ?>
             <span><?php if(is_array($gs['goods_attr'])): foreach($gs['goods_attr'] as $key=>$att): echo ($att[pfix('name')]); ?>：<?php echo ($att['value'][pfix('attr_value')]); ?> &nbsp;&nbsp;<?php endforeach; endif; ?></span>
          <?php } ?>
          
          <div style="display:inline-block; float:right" class="rating-static-module"> 
          
                 <?php $__FOR_START_23072__=0;$__FOR_END_23072__=$gs['score'];for($i=$__FOR_START_23072__;$i < $__FOR_END_23072__;$i+=1){ ?><span class="rateit-selected fl svg"></span><?php } ?>
           </div>  
      </div>
      <div class="cb"></div>
     </div>
     
     <div class="pjbb">
      <div class="pjbbtxt"><?php echo (strip_tags($gs["content"])); ?></div>
      <p class="tr"><span class="pjbbtxtdj"><?php echo isL(L('ReadMore'),'继续阅读');?></span><span class="pjbbtxtdj2"><?php echo isL(L('ReadMore'),'显示较少内容');?></span></p>
      <p class="tr"><span class="pjbbtxtdj">继续阅读</span><span class="pjbbtxtdj2" style="display:none; text-decoration:underline; cursor:pointer">显示较少内容</span></p>
      
      <?php if($gs['slide_show']){ ?>
          <div class="pjbbimg pt10" >
           <ul>
               <?php if(is_array($gs['slide_show'])): foreach($gs['slide_show'] as $key=>$att): ?><li><img src="__ROOT__/<?php echo ($att); ?>" /><span></span></li><?php endforeach; endif; ?>
           </ul>
           <div class="tctp">
 <span class="tpprev glyphs icon-prev"></span><span class="tpnext glyphs icon-next"></span>
 <div class="tpm"><span class="close tpclose" style="top:-30px; right:-40px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span><b><img src="" alt=""/></b></div>
</div>
          </div>
      <?php } ?>
     </div>-->
    </li><?php endforeach; endif; ?>

<style>
 .tctp{ background:rgba(0,0,0,0.7); width:100%; height:100%; position:fixed; left:0; top:0; z-index:300; display:none}
 .tpprev{ display:inline-block; cursor:pointer; width:30px; height:30px; position:absolute; left:0; top:50%; margin-top:-15px; color:#bababa; line-height:30px; text-align:center; font-size:60px; z-index:10; opacity:0.2}
 .tpprev:hover{ opacity:1}
 .tpnext{ display:inline-block; cursor:pointer; width:30px; height:30px; position:absolute; right:30px; top:50%; margin-top:-15px;color:#bababa; line-height:30px; text-align:center; font-size:60px; z-index:10;opacity:0.2}
 .tpnext:hover{ opacity:1}
 .tpm{ background:#444; width:640px; height:640px; position:absolute; left:50%; top:50%; margin:-320px 0 0 -320px; display:none}
 .tpm b{ display:table-cell; vertical-align:middle; text-align:center; width:520px; height:520px;  box-sizing:border-box}
 .tpm img{ max-width:640px; max-height:640px}
 .tpclose{top:10px; right:10px;}
 @media screen and (max-width:767px){
	  .tpm{ width:100%; left:0; height:80%; top:10%; margin:0}
	  .tpm b{ width:100%; height:100%}
	  .tpm img{max-width:100%;}
	  .tpprev{ left:0} 
	  .tpnext{ right:30px}
	  .tpclose{top:50px; right:10px;}
	 }
</style>
<?php if(is_mobile()): ?><!--<script type="text/javascript" src="__HOME__/js/jqueryph.js"></script>
<script type="text/javascript" src="__HOME__/js/jquery.mobile-1.4.5.min.js"></script>-->
<?php else: endif; ?>
<script type="text/javascript">
 $(function(){
	 $(".pjbbimg li img").click(function(){
		 $(this).parents(".xspjb").siblings(".pjiapic").fadeIn()
		 $(".pjiapicm").slideDown()
		 var pjiapic1 = new Swiper('.pjiapic1', {	
     // slidesPerView : 2,
//spaceBetween : 40,
   //navigation: {
   // nextEl: '.pjiapicr',
   // prevEl: '.pjiapicl',
  //},
  pagination: {
    el: '.pjiapicb', 
	clickable :true,
  },
    });
		 })
		 
 
$(".pjiapic").click(function(event) {  
       
	  $(this).fadeOut()	
	  $(".pjiapicm").slideUp();
});
	 
	 
	 $(".ckdh").click(function(){
		 $(this).siblings(".xsjsnr").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").show()
		  })
		$(".xsjsnr").click(function(){
		 $(this).siblings(".ckdh").show()
		 $(this).hide() 
		 $(this).parent(".liuylbbb").siblings(".liuylbbdh").hide()
		  })  
	 
	 
	  $('.pjbbtxtdj').click(function(){
        $(this).hide();
		$(this).siblings(".pjbbtxtdj2").show()
        $(this).parent().siblings(".pjbbtxt").css("height","auto");
       
});  	  
$('.pjbbtxtdj2').click(function(){
        $(this).hide();
		$(this).siblings(".pjbbtxtdj").show()
        $(this).parent().siblings(".pjbbtxt").css("height","22px");
       
});
  
/****/
var bigImg='';
	var _index=0;
	
		$(".tpclose").click(function(){
			$(this).parent(".tpm").hide()
		$(this).parents(".tctp").fadeOut()	
		
	});//关闭
	
	$(".tpm").click(function(e) {  
    $(this).show();  
        e.stopPropagation();  
});  
$(".tctp").click(function(event) {  
      $(".tpm").hide();  
	  $(this).fadeOut()	
});

	
	
	
	
	/**$(".pjbbimg li img").click(function(){
		$(this).parent("li").parent("ul").siblings(".tctp").fadeIn("fast",function(){
			$(this).children(".tpm").show()
			});
		bigImg=$(this).attr("src");//获取点击图片的地址
		$(this).parent("li").parent("ul").siblings(".tctp").children(".tpm").children("b").children("img").attr("src",bigImg); //更换大图的图片地址
		_index=$(this).parent("li").index();//保存图片的序列号
		i=$(this).parent("li").parent("ul").children("li").length;
		
	});***/


	$(".tpnext").bind("click",function(){
			 _index++;
			b= $(this).parents(".tctp").parent(".pjbbimg").children("ul").children("li").length
			//alert(b)
			 
			 if(_index>=b){_index=0}; 
			 bigImg=$(this).parents(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
		$(this).parent(".tpm").children("b").children("img").attr("src",bigImg);
			 })
	$(".tpm b").on("swiperight",function(){
		_index++;
		b= $(this).parent(".tpm").parent(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index>=b){_index=0}; 
		bigImg2=$(this).parent(".tpm").parent(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
  $(this).children("img").attr("src",bigImg2);
});
	
	$(".tpprev").click(function(){
		_index--; //序列号加1 _index+1
		b= $(this).parents(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index< 0){_index=b-1};
		//alert(_index)
		bigImg=$(this).parents(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
		$(this).parent(".tpm").children("b").children("img").attr("src",bigImg);
	});
	
	$(".tpm b").on("swipeleft",function(){
		_index--;
		b= $(this).parent(".tpm").parent(".tctp").parent(".pjbbimg").children("ul").children("li").length
		if(_index< 0){_index=b-1};
		bigImg2=$(this).parent(".tpm").parent(".tctp").siblings("ul").children("li").children("img").eq(_index).attr("src");
  $(this).children("img").attr("src",bigImg2);
});
    }); 

</script>
     
     </ul>
     <?php if($pageNumShown > 1){ ?>
         <a href="javascript:void(0)" class="more mt10">
             <input onClick="mobile.clickPage(this,'#app-end');" class="morebtn" value="Load More" type="button">
         </a>
     <?php } ?>
    </div><!--pjb end-->
  
     
     </div>
    </li>
    <li>
     <h1><?php echo isL(L('ServiceCommitment'),'服务承诺');?><span class="fwz" style="display:block">+</span><span class="fwf" style="display:none">-</span></h1>
     <div class="fwcnb"><!--<?php echo isL(L('BuyingTips'),'');?>-->
     <span class="fwtxt"><span class="khzxbicona2" ><i class="iconfont icon-qiandai"></i></span><span class="vam"><?php echo isL(L('NewFreeShipping'),'免运费');?></span></span><!-- -->
      <span class="fwtxt"><span class="khzxbicona2" ><i class="fa fa-plane" style="color:#333"></i></span><span class="vam"><?php echo isL(L('SFAirlines'),'顺丰航空');?></span></span><!-- -->
      <span class="fwtxt"><span class="khzxbicona2" ><i class="iconfont icon-jishiqi"></i></span><span class="vam"><?php echo isL(L('DGNT'),'按时发货');?></span></span>
      <span class="fwtxt"><span class="khzxbicona2" ><i class="iconfont icon-chexiao"></i></span><span class="vam"><?php echo isL(L('Days30Return'),'30天无理由退货');?></span></span><!-- -->
      <span class="fwtxt"><span class="khzxbicona2" ><i class="iconfont icon-renminbi"></i></span><span class="vam"><?php echo isL(L('FreeReturns'),'免费退货');?></span></span><!-- -->
      <span class="fwtxt"><span class="khzxbicona2" ><i class="iconfont icon-dianji"></i></span><span class="vam"><?php echo isL(L('ReturnConvenience'),'一键退货');?></span></span><!-- -->
      <div class="cb"></div>
      <p class="pt20"><span class="fwtxt2"><?php echo isL(L('TipsThisSite'),'温馨提示：本网站仅支持自助购物');?></span></p>
     </div>
    </li>

     <?php else: ?>
     
     
     
   	<li>
     <h1><?php echo isL(L('ServiceCommitment'),'服务承诺');?><span class="fwz" style="display:none">+</span><span class="fwf" style="display:block">-</span></h1>
     <div class="fwcnb" style="display:block"><!--<?php echo isL(L('BuyingTips'),'');?>-->
     <span class="fwtxt"><span class="khzxbicona2" style="background: url(__HOME__/images2/myf_icon.png) no-repeat;background-size: 100%;border: 0;" ></span><span class="vam"><?php echo isL(L('NewFreeShipping'),'免快递费');?></span></span><!--<i class="iconfont icon-qiandai"></i> -->
      <span class="fwtxt"><span class="khzxbicona2" style="background: url(__HOME__/images2/sf_icon.png) no-repeat;background-size: 100%;border: 0;" ></span><span class="vam"><?php echo isL(L('SFAirlines'),'顺丰航空');?></span></span><!--<i class="fa fa-plane" style="color:#333"></i> -->
      <span class="fwtxt"><span class="khzxbicona2"><i class="iconfont icon-jishiqi" style="margin: -8px 0 0 -8.5px; font-weight: bold"></i></span><span class="vam"><?php echo isL(L('DGNT'),'当天发货');?></span></span>
      <span class="fwtxt"><span class="khzxbicona2" style="background: url(__HOME__/images2/zxlp_icon.png) no-repeat;background-size: 100%;border: 0;" ></span><span class="vam"><?php echo isL(L('Days30Return'),'尊贵礼盒');?></span></span><!--<i class="iconfont icon-chexiao"></i> -->
      <span class="fwtxt"><span class="khzxbicona2" style="background: url(__HOME__/images2/mtsf_icon.png) no-repeat;background-size: 100%;border: 0;" ></span><span class="vam"><?php echo isL(L('FreeReturns'),'免退送费');?></span></span><!--<i class="iconfont icon-renminbi"></i> -->
      <span class="fwtxt"><span class="khzxbicona2" style="background: url(__HOME__/images2/yjth_icon.png) no-repeat;background-size: 100%;border: 0;" ></span><span class="vam"><?php echo isL(L('ReturnConvenience'),'一键退货');?></span></span><!--<i class="iconfont icon-dianji"></i> -->
      <div class="cb"></div>
      <p class="pt20"><span class="fwtxt2"><?php echo isL(L('TipsThisSite'),'温馨提示：本网站仅支持自助购物');?></span></p>
     </div>
    </li><?php endif; ?>
    
    
   	<li>
     <h1><?php echo isL(L('Szie'),'尺码');?><span class="fwz" style="display:block">+</span><span class="fwf" style="display:none">-</span></h1>
     <div class="fwcnb">
        <img src="<?php echo showImg($goods[pfix('size_img')]);?>" />
       <p style="text-align:right !important;margin:10px 0;"><a href="javascript:void(0)" class="cmzn" style="float:right;"><?php echo isL(L('MeasuringMethod'),'测量方法');?></a></p>
     </div>
    </li>    
    
    <?php if(is_array($goods[pfix('parameters')])): foreach($goods[pfix('parameters')] as $key=>$p): ?><li>
        <h1><?php echo ($p['par_name']); ?><span class="fwz">+</span><span class="fwf">-</span></h1>
        <div class="fwcnb">
         <!--<table width="100%" cellpadding="0" cellspacing="0" class="cctab">
          <tr>
           <th width="20%" style="background:#8c8c8c; color:#fff;">商品尺寸</th>
           <th width="20%">后肩衣长</th>
           <th width="20%">肩宽</th>
           <th width="20%">身宽</th>
           <th width="20%">袖长</th>
          </tr>
          <tr>
           <td class="bgea col000"><b>XS</b></td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
          </tr>
          <tr>
           <td class="bgea col000"><b>S</b></td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
          </tr>
          <tr>
           <td class="bgea col000"><b>M</b></td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
          </tr>
          <tr>
           <td class="bgea col000"><b>L</b></td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
          </tr>
          <tr>
           <td class="bgea col000"><b>XL</b></td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
           <td>66</td>
          </tr>
         </table>-->
         <p class="tj">
            <?php if(strpos($p['par_name'],'尺码') !==false || strpos(strtoupper($p['par_name']),'SIZE') !==false){ ?>
                 <?php echo ($p['par_value']); ?>
            <?php }else{ ?>
                <?php echo (rep_to_br($p['par_value'])); ?>
            <?php } ?>
         </p>
        </div>
        </li><?php endforeach; endif; ?>
   </ul>
  </div><!--fwcn end-->
  <div class="fwb"><img src="__HOME__/images/img13.jpg" alt=""></div>
  <p class="pl10 pt10 f24 lh35">Luxury Gift Box</p>
  <p class="pl10 f13 lh20"><?php echo isL(L('LuxuryGiftBox'),'尊贵礼盒');?></p>
  <div class="pt30 pl5 fxtp jiathis_style_24x24">
   <span class="pr10 b vam  fxtxt"><?php echo isL(L('Share'),'分享');?></span>
     <!-- JiaThis Button BEGIN --
<div class="jiathis_style_24x24 vat" style="display:inline-block">
	
    
	<a class="jiathis_button_tsina icon-weibo glyphs col000 mr5" style="cursor:pointer"></a>
    <a class="jiathis_button_weixin icon-wechat glyphs col000" style="cursor:pointer"></a>
    
    <a class="jiathis_button_fb icon-facebook glyphs col000" style=" margin:0 -1px 0 -3px"></a>
  
   <a href="https://twitter.com/WuYoudi" target="_blank" class="icon-twitter glyphs col000" ></a>is_button_renren"></a>-->
	<div class="dptjlttbr" style="display:inline-block; vertical-align:middle; float:none">
    <!--<a href="https://www.facebook.com/YOUDIWUHEIHEI" target="_blank" class="icon-facebook glyphs" style="vertical-align:middle; color:#4c669e"></a>
    <a href="https://twitter.com/WuYoudi" target="_blank" class="icon-twitter glyphs" style="margin:0 3px; vertical-align:middle; color:#5eb2ec"></a>
    <a href="#" target="_blank" class="fa fa-google-plus" style="padding:0 3px; vertical-align:middle; line-height:22px; font-size:18px; color:#da302e" ></a>
     <a href="#" target="_blank" class="fa fa-pinterest-p" style="padding:0 3px; vertical-align:middle; line-height:22px; font-size:18px; color:#da302e"></a>
   <a href="http://weibo.com/p/1006061827314140/home?from=page_100606&mod=TAB&is_all=1#place " target="_blank" class="icon-weibo glyphs" style="margin:0 3px; vertical-align:middle; color:#da302e"></a>
   <a href="#" target="_blank" class="icon-wechat glyphs jiathis_button_weixin" style=" padding:0 3px;vertical-align:middle; color:#3cc128"></a>
     --> 
   <a class="fa fa-facebook ml25" style=" color:#222; margin:0"></a>
   <a class="fa fa-twitter" style=" color:#222"></a>
   <a class="fa fa-google-plus" style=" color:#222"></a>
   <a class="fa fa-pinterest-p" style=" color:#222"></a>
   <a class="fa fa-weibo" style=" color:#222"></a>
   <a class="fa fa-wechat" style=" color:#222"></a>
   
   
  
   </div>
</div>
<style>
 .cpxqbr .jiathis_style_24x24 .jtico_tsina,.cpxqbr .jiathis_style_24x24 .jtico_weixin,.cpxqbr .jiathis_style_24x24 .jtico_fb{ background:none !important; display:none !important}
</style>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- JiaThis Button END -->
   
   <div class="cb"></div>
  </div>

  
  
  
 </div><!--cpxqbr end--> 
 
 <div class="cb pb30"></div>
 <div class="zjll">
  <div class="zjllt">
   <ul>
   	<li class="hover"><?php echo isL(L('YouMayLike'),'您可能会喜欢');?></li>
    <li><?php echo isL(L('RecentVisit'),'最近浏览过');?></li>
    <div class="cb"></div>
   </ul>
  </div>
  <div class="zjllb"> 
   <blockquote class="block" get="likes">
    
    <div class="slider2">
     
<!--<ul>
    <?php if(is_array($youLike)): foreach($youLike as $key=>$it): ?><li> 
       
            
             <div class="zjllbmt">
                   <table width="100%" height="100%">
                        <tr>
                            <td align="center" valign="middle" width="100%" height="100%">
                                <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb']); ?>" alt=""/></a>
                            </td>
                        </tr>
                    </table>
             </div>
             <div class="zjllbmtb">
                   <table width="100%" height="100%">
                       <tr>
                            <td align="center" valign="middle" width="100%" height="100%">
                                  <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb2']); ?>" alt=""/></a>
                            </td>
                       </tr>
                   </table>
             </div>
             <div class="zjllbmb">
                   <p class="tc">￥ <?php echo ($it['goods_retail_price']); ?></p>
             </div>
        </li><?php endforeach; endif; ?>
    <div class="cb"></div>
</ul>-->
<?php $basicPiece=new BasicAction(); $youLikeTdde=$basicPiece->guessYouLike(0,30,''); ?>
<?php if(is_array($youLikeTdde)): foreach($youLikeTdde as $key=>$it): ?><div class="slide">
 <div class="zjllbmt">
                   <table width="100%" height="100%">
                        <tr>
                            <td align="center" valign="middle" width="100%" height="100%">
                                <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb']); ?>" alt=""/></a>
                            </td>
                        </tr>
                    </table>
             </div>
             <div class="zjllbmtb">
                   <table width="100%" height="100%">
                       <tr>
                            <td align="center" valign="middle" width="100%" height="100%">
                                  <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb2']); ?>" alt=""/></a>
                            </td>
                       </tr>
                   </table>
             </div>
             <div class="zjllbmb">
                   <p class="tc">￥ <?php echo ($it['goods_retail_price']); ?></p>
             </div>
 
 
 
 </div><?php endforeach; endif; ?> 
    </div>
   </blockquote>
   
   <blockquote get="visits">
    
    <div class="slider1">
<!--<ul>
   <?php if(is_array($recentVisit)): foreach($recentVisit as $key=>$it): ?><li>
         <span  class="dbclose" url="<?php echo U('Cookie/delRecentVisit',array('id'=>$it['id']));?>"><span class="glyphs icon-close"></span></span>
          
           <div class="zjllbmt">
              <table width="100%" height="100%">
                  <tr>
                        <td align="center" valign="middle" width="100%" height="100%">
                            <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb']); ?>" alt=""/></a>
                        </td>
                  </tr>
              </table>
           </div>
           <div class="zjllbmtb">
             <table width="100%" height="100%">
                 <tr>
                     <td align="center" valign="middle" width="100%" height="100%">
                          <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb2']); ?>" alt=""/></a>
                     </td>
                 </tr>
              </table>
           </div>
           <div class="zjllbmb">
           <p class="tc">￥ <?php echo ($it['goods_retail_price']); ?></p>
           </div>
        </li><?php endforeach; endif; ?>
    <div class="cb"></div>
  </ul>--> 
  <?php if(is_array($recentVisit)): foreach($recentVisit as $key=>$it): ?><div class="slide">
   <span  class="dbclose dbcloseb" url="<?php echo U('Cookie/delRecentVisit',array('id'=>$it['id']));?>"><span class="glyphs icon-close"></span></span>
          
          <div class="zjllbmt">
              <table width="100%" height="100%">
                  <tr>
                        <td align="center" valign="middle" width="100%" height="100%">
                            <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb']); ?>" alt=""/></a>
                        </td>
                  </tr>
              </table>
           </div>
           <div class="zjllbmtb">
             <table width="100%" height="100%">
                 <tr>
                     <td align="center" valign="middle" width="100%" height="100%">
                          <a href="<?php echo U('Goods/index',array('html'=>$it['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($it['goods_thumb2']); ?>" alt=""/></a>
                     </td>
                 </tr>
              </table>
           </div>
           <div class="zjllbmb">
           <p class="tc">￥ <?php echo ($it['goods_retail_price']); ?></p>
           </div>
  </div><?php endforeach; endif; ?> </div>
   </blockquote>
   
  </div><!--zjllb end-->
 </div><!--zjll end-->


</div><!--cpxqb end-->

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
<!--顶部购物车-->
  <div class="dbgwc"><div class="dbgwcm"><div class="pv10">
   <div class="dbgwcl" >
    <a class="col2abb"><b class="vam pr10" style="color:#000;font-size:18px;"><?php echo (strip_tags($goods['us_goods_name'])); ?> <img src="__HOME__/images/logo.png" width="100" /></b> </a>
    <p><span>Number:<?php echo ($goods['goods_num']); ?></span><b class="mh10"><?php echo C('KTEC_H'); echo ($goods['goods_retail_price']); ?></b><span class="colaaa">(<?php echo isL(L('ValueAddedTax'),'含增值税');?>)</span></p>
   </div>
   <div class="dbgwcr">
 
       
   <?php if(is_array($goods['attrs'][$goods['id']])): foreach($goods['attrs'][$goods['id']] as $keyec=>$ar): $counts=count($ar['attr_list']); ?>   
     <div class="cmbox" style="float:left; width:26%; margin:2px 5px 0">
           <?php if($goods['stock'] <= 0 && C('CHECK_STOCK') && $keyec == 0){ ?>
                 <span class="cmboxtxtred" style="text-transform:uppercase">sold out</span><!--没货时-->
           <?php }else{ ?>
                <span class="<?php if($goods['stock'] > 0): ?>cmboxtxt<?php endif; ?> attr-class-<?php echo ($keyec); ?>" attr-class="attr-class-<?php echo ($keyec); ?>" <?php if($counts==1){ ?>value="<?php echo ($ar['attr_list'][0]['attr_id']); ?>"<?php } ?> 
                 info-class="error-info-<?php echo ($keyec); ?>" error1="<?php echo isL(L('PleaseSelect'),'请选择'); echo ($ar['attribute_name']); ?>" <?php if($goods['stock'] <= 0): ?>style="color:#999"<?php endif; ?>>
                    <?php if($counts==1){ echo ($ar['attr_list'][0]['attr_value']); }else{ echo isL(L('Select'),'选择'); echo ($ar['attribute_name']); } ?>
                </span>
           <?php } ?>
           <div class="cmboxb" style="display: none;">
               <ul>         
                  <?php if(is_array($ar['attr_list'])): foreach($ar['attr_list'] as $akey=>$av): ?><li value="<?php echo ($av["attr_id"]); ?>" price="<?php echo ($av['attr_price']); ?>" attr-stock="<?php echo ($av['attr_stock']); ?>">              
                           <a href="javascript:void(0)" class="cmsj">
                               
                                <?php if($av['attribute_id'] == 72): ?>
                                   <span class="ysq" style="background:#fff"></span>
                                <?php elseif($av['attribute_id'] == 73): ?>
                                   <span class="ysq" style="background:yellow"></span>
                                <?php elseif($av['attribute_id'] == 74): ?>
                                   <span class="ysq" style="background:orange"></span>     
                                <?php elseif($av['attribute_id'] == 75): ?>
                                   <span class="ysq" style="background:red"></span>             
                                <?php elseif($av['attribute_id'] == 76): ?>
                                   <span class="ysq" style="background:pink"></span>      
                                <?php elseif($av['attribute_id'] == 208): ?>
                                   <span class="ysq" style="background:black"></span>         
                                <?php elseif($av['attribute_id'] == 209): ?>
                                   <span class="ysq" style="background:green"></span>      
                                <?php elseif($av['attribute_id'] == 210): ?>
                                   <span class="ysq" style="background:turquoise"></span>          
                                <?php elseif($av['attribute_id'] == 211): ?>
                                   <span class="ysq" style="background:turquoise"></span>                          
                                <?php elseif($av['attribute_id'] == 212): ?>
                                   <span class="ysq" style="background:turquoise"></span><?php endif; ?>
                                                                  
                               <b><?php echo ($av['attr_value']); ?></b>
                           </a>
                      </li><?php endforeach; endif; ?>
               </ul>
           </div>
           <!--<p class="tsy error-info-<?php echo ($keyec); ?>" style="border:none;text-indent:0;position:relative;left:-16px;">提示层</p>-->
     </div><!----><?php endforeach; endif; ?>
  
  
     <?php if($goods['stock'] > 1 && C('CHECK_STOCK')){ ?> 
         <div class="dbgwcrbtn">
             <input value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>" onClick="toCart(false)" class="btn" type="button">
             <span class="glyphs icon-bag btnb"></span>    
          </div>    
     <?php }else{ ?>  
            <div class="dbgwcrbtn" style=" opacity:0.5;">
             <input value="<?php echo isL(L('AddShoppingBag'),'加入购物袋');?>" class="btn" type="button">
             <span class="glyphs icon-bag btnb"></span>    
           </div>  
     <?php } ?>
   <div class="cb"></div>
  </div>
  <div class="cb"></div>
  </div></div>
  </div>
<!--顶部购物车 end-->


    <div class="bgf50 dybg" style="display:none"></div>
    <div class="dyym" style="height:220px;display:none"><span class="close dyclose"></span>
     <p class="dyymtxt4" style="height:20px;"><!--<?php echo isL(L('latestInformation'),'订阅我们的电子报，即可第一时间获得新近单品信息以及YOUDI WU 最新相关资讯');?>--></p>
     <form method="post" action="<?php echo U('EmailSubscribe/saveEmail');?>" class="form14578">
         <input type="text" name="email" value="" placeholder="<?php echo isL(L('YourEmailAdderss'),'输入邮件的地址');?>" class="dyym_input validate[custom[email],required]"/>
         <!--<a href="#" class="jkdy mt30 mb20"><?php echo isL(L('SubscribeNow'),'即刻订阅');?></a>-->
         <div style="margin-top:20px;"></div>
         <input type="text" name="contents" placeholder="<?php echo isL(L('YourNeeds'),'您的需求');?>" class="dyym_input validate[required]" />
         <input type="hidden" value="submits54" name="submits"/>
         <div style="height: 20px;"></div>
         <input type="submit" id="submits54" msg="<?php echo isL(L('PleaseLater'),'提交中...');?>" class="jkdy  mb20" value="Submit" />
     </form>
     <div class="cb"></div>
    </div>

<!---->
<div class="tccmbg">
 <div class="tccm"><span class="close cmclose" style="right:10px; top:10px;"><span class="glyphs icon-close" style="line-height:35px; font-size:20px; display:none"></span></span>
 <img src="__HOME__/images/1123a.jpg" alt=""/>
 </div>
</div>
<!--尺码指南 end-->
<!---->
<div class="tcscbg"><div class="tcsc"><span class="close scclose" style="opacity:0"><span class="glyphs icon-close" style="line-height:35px;"></span></span>
 <h1><?php echo isL(L('Hesitations'),'犹疑不决？在家试穿。');?></h1>
 <div class="tcscml">
  <div class="pr mb20">
   <input name="sub_email" value="<?php echo getUserC('SE_USER_NAME');?>" error1="<?php echo isL(L('EmailFormatError'),'请输入正确的电邮地址');?>" placeholder="<?php echo isL(L('EmailFormatError'),'请输入正确的电邮地址');?>" class="tcsc_input" type="text">
   <span class="glyphs icon-contact" style="font-size:0.6rem; position:absolute; left:10px; top:1px; height:35px; line-height:35px; color:#aaa;"></span>
   <p class="tsy sub-emaili" style="border:none;text-indent:0;position:relative;left:-5px;top:5px;">提示层</p>
  </div><!---->
  <div class="pr mb20">
  
       
   <?php if(is_array($goods['attrs'][$goods['id']])): foreach($goods['attrs'][$goods['id']] as $keyec=>$ar): $counts=count($ar['attr_list']); ?>
   <div class="cmbox" style="z-index: <?php if($keyec == 0): ?>1000<?php else: echo 1000-($keyec+1); endif; ?>;">
          <?php if($goods['stock'] <= 0 && C('CHECK_STOCK') && $keyec == 0){ ?>
              <span class="cmboxtxt cmboxtxtred" style="text-transform:uppercase">sold out</span><!--没货时-->
          <?php }else{ ?>
             <span class="cmboxtxt attr-class-<?php echo ($keyec); ?>" <?php if($counts==1){ ?>value="<?php echo ($ar['attr_list'][0]['attr_id']); ?>"<?php } ?> attr-class="attr-class-<?php echo ($keyec); ?>" info-class="error-info-<?php echo ($keyec); ?>" error1="<?php echo isL(L('PleaseSelect'),'请选择'); echo ($ar['attribute_name']); ?>"><?php if($counts==1){ echo ($ar['attr_list'][0]['attr_value']); }else{ echo isL(L('Select'),'选择'); echo ($ar['attribute_name']); } ?></span>
          <?php } ?>   
           <div class="cmboxb" style="display: none;background:#fff !important;">
               <ul style="background:#fff;">         
                  <?php if(is_array($ar['attr_list'])): foreach($ar['attr_list'] as $akey=>$av): ?><li value="<?php echo ($av["attr_id"]); ?>" price="<?php echo ($av['attr_price']); ?>" attr-stock="<?php echo ($av['attr_stock']); ?>">              
                           <a href="javascript:void(0)" class="cmsj">
                               
                                <?php if($av['attribute_id'] == 72): ?>
                                   <span class="ysq" style="background:#fff"></span>
                                <?php elseif($av['attribute_id'] == 73): ?>
                                   <span class="ysq" style="background:yellow"></span>
                                <?php elseif($av['attribute_id'] == 74): ?>
                                   <span class="ysq" style="background:orange"></span>     
                                <?php elseif($av['attribute_id'] == 75): ?>
                                   <span class="ysq" style="background:red"></span>             
                                <?php elseif($av['attribute_id'] == 76): ?>
                                   <span class="ysq" style="background:pink"></span>      
                                <?php elseif($av['attribute_id'] == 208): ?>
                                   <span class="ysq" style="background:black"></span>         
                                <?php elseif($av['attribute_id'] == 209): ?>
                                   <span class="ysq" style="background:green"></span>      
                                <?php elseif($av['attribute_id'] == 210): ?>
                                   <span class="ysq" style="background:turquoise"></span>          
                                <?php elseif($av['attribute_id'] == 211): ?>
                                   <span class="ysq" style="background:turquoise"></span>                          
                                <?php elseif($av['attribute_id'] == 212): ?>
                                   <span class="ysq" style="background:turquoise"></span><?php endif; ?>
                                                                  
                               <b><?php echo ($av['attr_value']); ?></b>
                           </a>
                      </li><?php endforeach; endif; ?>
               </ul>
           </div>
     </div>
     <p class="tsy error-info-<?php echo ($keyec); ?>" style="border:none;text-indent:0;position:relative;left:-5px;top:-5px;">提示层</p><?php endforeach; endif; ?>
  
          
  </div><!---->

 </div>  <div class="cb pb20"></div>
  <div class="tcscbl">
   <p style="position:relative; padding-left:18px;">
          <input name="is_subs" value="1" class="vam" id="xiw"  style=" display:none" type="checkbox">
          <label class="ffInput" for="xiw">
          <span class="ffInput__checkbox" style="top:1px;"></span>
          <b class="pl5 vam" style="display:inline-block" ><?php echo isL(L('SubscribeToOur'),'订阅我们的电子报，随时随地掌握YOUDI WU最新资讯。');?></b>
          </label>
   </p>
   <p style="color:#aaa; line-height:22px; font-size:13px; padding-left:22px;">
       <?php echo isL(L('ReadPrivacy'),'阅读我们的隐私与Cookie政策');?> <a href="/Modular/privacy/html/175.html" class="colabb"><?php echo isL(L('ClickHere'),'点击此处');?></a>
   </p>
  </div>
  <div class="cb pb20"></div>
    <?php if($goods['stock'] > 1 && C('CHECK_STOCK')){ ?>
         <div class="btnbox2" >
             <input value="<?php echo isL(L('Submit'),'提交');?>" class="btn" type="button" onClick="wantTset(this)">
             <p class="tsy button-info" style="border:none;margin-top:10px;"><?php echo isL(L('yourEmailAddress'),'请输入电邮地址');?></p>
             <span class="glyphs icon-thinArrow btnb"></span>     
         </div>
     <?php }else{ ?>
         <div class="btnbox2" style="opacity:0.5">
             <input value="<?php echo isL(L('Submit'),'提交');?>" class="btn" type="button" onClick="wantTset(this)">
             <p class="tsy button-info" style="border:none;margin-top:10px;"><?php echo isL(L('yourEmailAddress'),'请输入电邮地址');?></p>
             <span class="glyphs icon-thinArrow btnb"></span>     
         </div>     
     <?php } ?>
 <div class="cb pb20"></div>
 
</div></div>
<!--在家试穿 end-->
<script>
 $(function(){
	 $(".cmbox:eq(0)").css("z-index",100)
	 
	 $(document).on('click',".cmzn", function(){
      $(".tccmbg").fadeIn("fast",function(){
		  $(".tccm").slideDown()
		  })
});
	 $(document).on('click',".cmclose", function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });
		$(document).on('click',".tccmbg", function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });  
		
		$(document).on('click',".zjsc", function(){
      $(".tcscbg").fadeIn("fast",function(){
		  $(".tcsc").slideDown()
		  })
      });
 $(document).on('click',".scclose", function(){
      $(".tcsc").slideUp("fast",function(){
		  $(".tcscbg").fadeOut()
		  })
		  });

  $(document).on('click',".tcscbg", function(){
      $(".tcsc").slideUp("fast",function(){
		  $(".tcscbg").fadeOut()
		  })
		  });


	 })
</script>
<?php if(is_mobile()): ?><script> 
 $(function(){
	 
	 $(window).scroll(function () {
	var targetTop = $(this).scrollTop();
	 b= $(".jrgwdbtn").offset().top
	 a= $(".footer").offset().top
	 if (targetTop >b) {
		$(".dbgwc").addClass("dbgwcnow")
		}
		 if (targetTop <b) {
		 $(".dbgwc").removeClass("dbgwcnow")
		}
		
	  if (a >= $(window).scrollTop() && a < ($(window).scrollTop()+$(window).height())) {
                $(".dbgwc").removeClass("dbgwcnow")
			}
	
	})	 
		  
	
	 })
</script>
<?php else: ?>
<script> 
 $(function(){
	 $(window).scroll(function () {
	var targetTop = $(this).scrollTop();
	if (targetTop >480) {
		$(".dbgwc").addClass("dbgwcnow")
		}else{
			$(".dbgwc").removeClass("dbgwcnow")
			}
	})
	 })
</script><?php endif; ?>
<script type="text/javascript" src="__HOME__/js/plugins.js"></script>
<!--<script type="text/javascript" src="__COMMON__/js/highcharts.js"></script>-->
<script>
/*修改数量*/
function updateNumber(fuhao,inputName){
       var numRe=/^\d+$/;
       var _curNum = parseInt($("input[name='"+inputName+"']").val());
       if(!numRe.test(_curNum)){ $("input[name='"+inputName+"']").val(1);return;}
       if(fuhao=="+"){
          $("input[name='"+inputName+"']").val(_curNum +1);
       }else if(fuhao=="-"){
          if(_curNum >1){
               $("input[name='"+inputName+"']").val(_curNum -1);
          }else if(_curNum==1){
                return;
          }
      }
      showPrice();
}
/*计算价格*/
 function showPrice(){
     var number=parseInt($('input[name="goods_num"]').val()); //商品数量
     if(isNaN(number) || number <= 0) {
         $('input[name="goods_num"]').css('border','1px red solid');
         return;
     }  
 
     var goods="<?php echo ($goods["id"]); ?>";
     var tenkn="<?php echo $_SESSION["MY_TOKEN"]; ?>";
     var radioClass=$('.radio_class');
     var attrIds='';
     for(var i=0;i < radioClass.length;i++){
            if(radioClass.eq(i).val()) attrIds+=radioClass.eq(i).val()+',';
     }

    var url="<?php echo U('Goods/attrGoodsPrice','','');?>";
    url+="?goods="+goods+"&attrIds="+attrIds+"&number="+number+'&'+tenkn+'='+$('input[name="'+tenkn+'"]').val();
    $.ajax({
         type:'post',
         url:url,
         dataType:'json',
         success:function(response,status,xhr){
             if(response[0][1]==true){
                  var data=response[0][5];
				  var showPrice=data.trade_integral ? data.trade_integral : data.price;
                 // $('#show-price').text(showPrice); //显示商品价格
				  $('input[name="goods_num"]').attr('num',number);
             }else{
				 error(response[0][2],3000);
				 $('input[name="goods_num"]').val($('input[name="goods_num"]').attr('num'));
             }
          }
     });        
}

/**
*goodsId商品ID
*check  是否立即购买;false否;true是;
*/
var t=0;
function toCart(check){
     var number=parseInt($('input[name="goods_num"]').val()); //商品数量
     if(isNaN(number) || number <= 0) {
		// error('<?php echo isL(L("ErrorGoodsNum"),"商品数量输入格式错误");?>',3000);
		 $('input[name="goods_num"]').css('border','1px red solid').focus();
         return;
     }  
	 var attrIds=checkAttr();
     if(!attrIds) return;
     var isImmediately='';
     if(check) isImmediately="&check=ok";
     var url="<?php echo get_up_url(array('add'=>'ok'),U('Cart/addCart','',''),true);?>&goods_id=<?php echo ($goods['id']); ?>&num="+number+'&attr_id='+attrIds+isImmediately;
     window.location.href=url;
	 
} 

/*验证属性*/
function checkAttr(){
	
	
     var radioClass=$('.get-attr-val'); 
     var attrIds='';
     for(var i=0;i < radioClass.length;i++){
            if(radioClass.eq(i).attr('value')) {
				attrIds+=radioClass.eq(i).attr('value')+',';
				
			}else{
				var attrClass=radioClass.eq(i).attr('attr-class');
				$('.'+attrClass).parents('.cmbox').css('border','1px red solid');
				var infoClass=radioClass.eq(i).attr('info-class');
				var info=radioClass.eq(i).attr('error1');
				$('.'+infoClass).html(info).show();
				clearTimeout(t);
				t=setTimeout(function(){
					$('.'+attrClass).parents('.cmbox').css('border','1px #dbdbdb solid');	
					$('.'+infoClass).hide(); 
					
				},2000);
				return false;
			}
     }  
	 return attrIds;
	
}

$(function(){
 $('[data-toggle="lightbox-image"]').magnificPopup({type: 'image', image: {titleSrc: 'title'}});

        // Initialize image gallery lightbox
        $('[data-toggle="lightbox-gallery"]').magnificPopup({
            delegate: 'a.gallery-link',
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                arrowMarkup: '<button type="button" class="mfp-arrow mfp-arrow-%dir%" title="%title%"></button>',
                tPrev: 'Previous',
                tNext: 'Next',
                tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
            },
            image: {titleSrc: 'title'}
        });
		$(".select_box").show()
});
//pjbbtxtdj();
/**
 *THIS   this
 * addEnd      追加内容节点
 *  */
mobile={};
page=2;
mobile.clickPage=function(THIS,addEnd){
	var _this=this;
	var totalPages=parseInt("<?php echo ($pageNumShown); ?>");
	var url="<?php echo U('Goods/index','','');?>?html=<?php echo ($_GET['html']); ?>&p="+page;
	if(page >totalPages){
		//$(THIS).attr('disabled',true).val("<?php echo isL(L('LoadingCompleted'),'已全部加载完毕');?>");
		$(THIS).parent('a').hide();
		return;
	}else if(page == totalPages){
	    $(THIS).parent('a').hide();
	}
	this.run=function(){
		 if(page <= totalPages){  
			if($(THIS).attr('ajax') == 'ok') return;              
			 _this.getPage();
		 }else{
			 $(THIS).attr('disabled',true).val(window.LoadingCompleted);
		 }             
	};  
	this.getPage=function(){
		  $.ajax({
			 type:'get',
			 dataType: "text",
			 url:url,
			 success:function(response,status,xhr){
			     page++;
				 $(THIS).attr('ajax','').attr('disabled',false).val('Load More');
				 $(addEnd).append(response);
				// pjbbtxtdj();
			  },
			  beforeSend:function(){
				  $(THIS).attr('ajax','ok').attr('disabled',true).val('Being loaded...');
			  },         
			 error:function(xhr,errorText,errorType){
				 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
			 }
		 });              
	};  
	//运行
	_this.run();         
};  


function wantTset(_this){
   var sub_email=$('input[name="sub_email"]').val();
   var is_subs=$('#xiw');
   if(is_subs.attr('checked')) is_subs=1;
   else is_subs=0;
   var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
   if(!myreg.test(sub_email)){
	   $('input[name="sub_email"]').css('border','1px solid red');
	   var errorInfo=$('input[name="sub_email"]').attr('error1');
	   $('.sub-emaili').html(errorInfo).show();
	   setTimeout(function(){$('input[name="sub_email"]').css('border','1px solid #ddd');$('.sub-emaili').hide();},3000);
	    return;
   }
   var attr_ids=checkAttr();
   if(!attr_ids) return;
   var goods_id="<?php echo ($goods['id']); ?>";
   var url="<?php echo U('EmailSubscribe/wantTest','','');?>?goods_id="+goods_id+"&attr_ids="+attr_ids+"&sub_email="+sub_email+"&is_subs="+is_subs;
   $(_this).attr('disabled',true);
    $.ajax({
         type:'post',
         url:url,
         dataType:'json',
         success:function(response,status,xhr){
             if(response[0][1]==true){
                   $('.button-info').html(response[0][2]).show();
				   setTimeout(function(){
					  $('.button-info').hide();
					  $(".tcsc").slideUp("fast",function(){
					      $(".tcscbg").fadeOut()
					  });
			       },3000);
             }else{
				   $(_this).attr('disabled',false);
                   $('.button-info').html(response[0][2]).show(); 
				   setTimeout(function(){$('.button-info').hide();},3000);
             }
          }
     });        
}
</script> 
<script type="text/javascript" src="__HOME__/js/jquery.bxslider.js"></script>
    <?php if(is_mobile()): ?><script type="text/javascript">

        $(document).ready(function(){

          wk=$(".zjllb").width()
          var slider1=$('.slider1').bxSlider({			
            slideWidth:  wk/3,
            minSlides: 2,
            maxSlides: 3,
            slideMargin: 0,
			prevText:"",
			nextText:"",
			touchEnabled:true

          });
		  
		  $('.dbcloseb').click(function(e){
  e.preventDefault();
  $(this).parent('.slide').remove();
  slider1.reloadSlider({
	  slideWidth:  wk/3,
            minSlides: 2,
            maxSlides: 3,
            slideMargin: 0,
			prevText:"",
			nextText:"",
			
          
	  });
});	  		 	  
		  
		  $('.slider2').bxSlider({			
            slideWidth:  wk/3,
            minSlides: 2,
            maxSlides: 3,
            slideMargin: 0,
			prevText:"",
			nextText:""

          });
	/****/
	
        });

    </script>

<?php else: ?>

     <script type="text/javascript">

        $(document).ready(function(){
			
          wk=$(".zjllb").width()
          var slider1=$('.slider1').bxSlider({			
            slideWidth:  wk/8-1,
            minSlides: 7,
            maxSlides: 8,
            slideMargin: 0,
			prevText:"",
			nextText:""

          });
		  
		  $('.dbcloseb').click(function(e){
  e.preventDefault();
  $(this).parent('.slide').remove();
  slider1.reloadSlider({
	  slideWidth:  wk/8-1,
            minSlides: 7,
            maxSlides: 8,
            slideMargin: 0,
			prevText:"",
			nextText:"",
			
          
	  });
});	  		 	  
		  
		  $('.slider2').bxSlider({			
            slideWidth:  wk/8-1,
            minSlides: 7,
            maxSlides: 8,
            slideMargin: 0,
			prevText:"",
			nextText:""

          });
		  
		  


        });

    </script><?php endif; ?>
 <script>
  $(function(){
	  $(".zjllt li").click(function(){
	var num=$(".zjllt li").index($(this)) 
	$(this).addClass("hover")
	$(this).siblings("li").removeClass("hover")
	$(".zjllb blockquote").eq(num).css("visibility","visible")
	$(".zjllb blockquote").eq(num).siblings("blockquote").css("visibility","hidden")
	 })
	 
	 
	 
	 
	 
	  })
 </script>   
    
    <style>
     .button-white-circle{ width:40px!important; height:40px!important; line-height:25px!important; font-size:20px !important; margin:10px 0 0 !important}
    </style>
</body>
</html>