<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>
<?php if($_GET['text'] == 'new'){ ?>
   <?php echo isL(L('NewArrivals'),'最新发布');?> | YOUDI WU 中国
<?php }else{ ?>
   <?php echo isL(L('Categories'),'产品分类');?> | YOUDI WU 中国
<?php } ?>  
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
<link rel="stylesheet" href="__HOME__/css/essentials.min.css" />
<link rel="stylesheet" href="__HOME__/css/font-awesome.min.css" />
<link rel="stylesheet" href="__HOME__/css/style.css" />
<link rel="stylesheet" href="__HOME__/css/chose.css"/>
<link rel="stylesheet" href="__HOME__/css/jquery.bxslider.css"/>
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<script type="text/javascript" src="__HOME__/js/waypoints.min.js"></script>
<!--[if IE]>
		<script src="__HOME__/js/html5shiv.min.js"></script>
<![endif]-->
<script type="text/javascript">
function sendClick(_this){
    $(function(e){
        $('.not-now').attr('class','not-now ');
        $(_this).attr('class','not-now active');
        var href=$(_this).attr('href');
        var loc="window.location.href='"+href+"';";
        $('#to-content').attr('onclick',loc);
    });
    return false;
}  


/*$(document).ready(function() {	
 $(".icon29").click(function(){
	 $(this).toggleClass("icon29now");
	 }) 	
});  */

/**/
$(document).on('click', ".sxcmr", function(){
    $(this).parent("li").slideUp();
	//处理清除条件
	clearSelect(this,false);
});
$(document).on('click', ".splb h1", function(){
    $(this).toggleClass("active")
	$(this).parent(".splb").siblings(".splb").children("h1").removeClass("active")
   $(this).siblings(".splbul,.clear2").slideToggle()
   $(this).parent(".splb").siblings(".splb").children(".splbul").slideUp()
});

//关闭手机检索栏
$(document).on('click','.tcshxlt,.close-retrieval',function(){
	 $(".tcshxl").fadeOut();
	 $(".tcshxlb").slideUp();	
	 $('body,html').stop().animate({
		'scrollTop': 0,
		'duration': 300,
		'easing': 'ease-in'
	  });			
});
</script>

</head>
<body>
<?php if(is_mobile()): ?><div class="box yyhddb">
<?php else: ?>
<div class="box"><?php endif; ?>
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
  <a href="<?php echo U('Article/index2');?>" class="navlink navlinkzx" style="margin-left: 218px;"></a>
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
<div class="flmin">
 <span class="flspan sxtjdj"><!----<?php echo isL(L('Categories'),'产品分类');?>--><?php echo isL(L('ScreeningConditions'),'筛选条件');?></span>
 <!--<span class="flspan2 sxtjdj">筛选条件 <span class="glyphs icon-tickbox"></span></span>已选后的样式-->
 <span class="flspan">
  <span class="pxdj"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?><!--<?php echo isL(L('Classification'),'分类');?>--></span>
  <div class="pxb">
   
   <a href="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>'DESC'));?>"><?php echo isL(L('New'),'新品');?></a>
   <a href="<?php echo U('Category/index',array('order_type'=>'goods_retail_price','order'=>'DESC'));?>"><?php echo isL(L('Price'),'价格');?></a>
   <a href="<?php echo U('Category/index',array('order_type'=>'volume','order'=>'DESC'));?>"><?php echo isL(L('SalesVolume'),'销量');?></a>
  <a href="<?php echo U('Category/index',array('order_type'=>'coll_numeber','order'=>'DESC'));?>"><?php echo isL(L('Wishlist'),'愿望单');?></a>
   <!--<a href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>"><?php echo isL(L('Wishlist'),'愿望单');?></a>-->
   
  </div>
 </span>
<!-- <div class="flspan">
        <select class="pxselect" name="" onchange="return ajaxTo(this)">
         <option value="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'create_time'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('New'),'新品');?></option>
         <option value="<?php echo U('Category/index',array('order_type'=>'volume','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'volume'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('SalesVolume'),'销量');?></option>
         <option value="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" <?php if($_GET['wishlist'] == 'condition'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('Wishlist'),'愿望单');?></option>
         </select>
     </div>-->
 
</div><!---flmin end-->



 <div class="lbt">
     <div class="wrap"><div class="lbtm fl">
 <?php if($cateName): ?><h1><?php echo ($cateName); ?></h1>
     <p><?php echo isL(L('StopPurchaseOf'),'在YOUDI WU一站式选购全球最新款式的设计师pp男装精品，打造时尚男士的专属衣服！');?></p>
<?php else: ?>
    <?php if($_GET['text'] == 'new'): ?><h1><?php echo isL(L('NewArrivals'),'最新发布');?></h1>     
        <p><?php echo isL(L('StopPurchaseOf'),'在YOUDI WU一站式选购全球最新款式的设计师pp男装精品，打造时尚男士的专属衣服！');?></p>
    <?php else: ?>
        <h1><?php echo isL(L('All'),'全部');?></h1>     
        <p><?php echo isL(L('StopPurchaseOf'),'在YOUDI WU一站式选购全球最新款式的设计师pp男装精品，打造时尚男士的专属衣服！');?></p><?php endif; endif; ?>
     </div><div class="cb"></div></div>
 </div><!--lbt end-->
<div class="zxfb">
 <div id="ajax-div">
   <style type="text/css">

.zxfbrbt img {
    width: auto;
    height: 71.49%;
    background: #fff;
}

.zxfbrbtb img {
    width: auto;
    height: 71.49%;
    background: #fff;
}
</style>




<script>

/*$(document).on('click', ".sxcmr", function(){
    $(this).parent("li").slideUp()
});
$(document).on('click', ".splb h1", function(){
    $(this).toggleClass("active")
   $(this).siblings(".splbul,.clear2").slideToggle()
});*/



$(function(){

	w=$(window).width()
	 wm=$(".zxfb").width()
	 wl=$(".shxl").width()
	 wr=$(".shxr").width()
     hr=$(".shxr").height()
	 h=$(window).height()
    ch=$(".shxl").height()

 /**$(".qgd").click(function(){
	  $ (window).unbind ('scroll');
	 })**/
    $(window).scroll(function () {
		
		h1= $(".shxr").offset().top
	 h2= $(".xian").offset().top
	// h3= $(".xianl").offset().top
	 
 
	 
		
		 h=$(window).height()
		ch=$(".shxl").height()
		cch=h-135
		

		var targetTop = $(this).scrollTop();
if (targetTop > h1 ) {
$(".shxl").addClass("shxlfd")
$(".shxl").css({
	"position":"fixed",
	"width":wm-wr,
	"top":130,
	"left":(w-wm)/2,
	"float":"none",
	})

/**if(ch>cch){
	$(".shxl").css({
	"top":"auto",
	"left":(w-wm)/2,
	"bottom":20,
	"float":"none",
	})
			}	
	
	***/	 

}
/**else if(targetTop >1080){
	$(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
	
	
	
	}**/
else {
	$(".shxl").removeClass("shxlfd")
$(".shxl").css({
	"position":"relative",
	"width":"25%",
	"top":"auto",
	"left":"auto",
	"bottom":"auto",
	"float":"left",
	})
	}
	
	/**if (targetTop = 200) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
	
	//alert(h3)
			}**/
			h4=$(window).height()-$(".shxl").height()-75
			h5=$(".footer").offset().top

	if (h2 >= $(window).scrollTop() && h2 < ($(window).scrollTop()+$(window).height())-h4) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
			}
			
   if (h5 >= $(window).scrollTop() && h5 < ($(window).scrollTop()+$(window).height())) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none", 
	})
			}
	
	
	<?php if(count($goods) <8){ ?>
	   $(window).unbind ('scroll');
	   $(".shxl").removeClass("shxlfd")
$(".shxl").css({
	"position":"relative",
	"width":"25%",
	"top":"auto",
	"left":"auto",
	"bottom":"auto",
	"float":"left",
	})
	 
	<?php } ?> 
	
});





 
})


function ajaxTo(_this){
   url=$(_this).val() ? $(_this).val() : $(_this).attr('href');
   getHtml(url);
   return false;
}


function clearSelect(_this,isall){

	//全部清除
   if(isall){
	   var splb=$(_this).parents('.splb');
	   splb.find('a').removeClass('ffInput--active');
	   $('input[type="text"]').val('');
	   retrieval('');	  
	   return;
   }
   //单个清单
   var type=$(_this).attr('type');
   var value = parseInt($(_this).attr('value'));
   var splb=$('.splb');
   var a=splb.find('a');   
   for(var i = 0; i < a.length;i++){
	  var tt=a.eq(i).attr('type');
	  var tv=parseInt(a.eq(i).attr('value'));
	  if(type == tt && value == tv){
	      a.eq(i).removeClass('ffInput--active');
		  break;
	  }else if(type == 'text'){
	      $('input[type="text"]').val('');
		  break;
	  }
   }
   retrieval('');	
}


function clearPrice(){
   $('input[type="text"]').val('');

}


function clearClass(_this){
   var splb=$(_this).parents('.splb');
   splb.find('a').removeClass('ffInput--active');
    retrieval('');
}


function retrieval(_this){
	if(_this){
		 if($(_this).is('.ffInput--active')) $(_this).removeClass('ffInput--active');
		 else $(_this).addClass('ffInput--active');	
	}
     var splb=$('.splb');
	 var a=splb.find('a');
	 var url="<?php echo U('Category/index','','');?>";
	 //分类检索
	 var html='';
	 //属性组检索
	 var attrs='';
	 for(var i=0;i < a.length;i++){
		 var sa=a.eq(i);
		 if(sa.is('.ffInput--active')){
			 if(sa.attr('type')=='html') html+=sa.attr('value')+',';
			 else if(sa.attr('type')=='attr') attrs+=sa.attr('value')+',';			 
		 }
	 }
	 if(html) url+='/html/'+html;
	 if(attrs) url+='/attrs/'+attrs;
	 var min=$('input[name="min"]').val();
	 var max=$('input[name="max"]').val();
	 if(isNaN(min)) $('input[name="min"]').val(''); 
	 if(isNaN(max)) $('input[name="min"]').val(''); 
	 if((min && !isNaN(min)) && (max && !isNaN(max))){
		 url+='/min/'+min+'/max/'+max;
	 }
     getHtml(url);
     return false;
	 
	
}

function getHtml(url){
  $.ajax({
	 type:'get',
	 dataType: "text",
	 url:url,
	 success:function(response,status,xhr){
		 var  show=$('.tcshxl').css('display');
		 var h=(parseInt($('.tcshxl').scrollTop()) + $('.shxlb').height());
		
		 $('#ajax-div').html(response);
		 if(show=='block'){
			 $('.tcshxl').show();
			 $('.tcshxlb').show();	
			 $('.tcshxl').stop().animate({
					'scrollTop':h,
					'duration': 0,
					'easing': 'ease-in'
			  });		 
			 
		 }else{
			// if(!$('input[name="min"]').val() && !$('input[name="max"]').val()){
				 $('body,html').stop().animate({
					'scrollTop': 0,
					'duration': 300,
					'easing': 'ease-in'
				  });		 
			// }			 
		 }
		 eval($(".chosen-select").chosen());
	  },
	  beforeSend:function(){
		  //开始请求
		  $('.curtain').show();
	  },         
	 error:function(xhr,errorText,errorType){
		 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
	 }
 });    
}
</script>
<style type="text/css">
.zxfbrb{
   position:relative;
}
.curtain{
    position:absolute;
	width:100%;
	height:100%;
	z-index:10;
	background:#fff;
	filter:alpha(opacity=90);  
	-moz-opacity:0.9;  
	-khtml-opacity: 0.9;  
	opacity: 0.9;  	
	display:none;
}
.chosen-select {
    display: inline!important;
    visibility: hidden;
    opacity: 0;
    position: absolute;
    z-index: -1;
}

</style>
<div class="fabut">
 <div class="fabutl"><b class="mr5 vam"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?>>60</a></div><!--fabutl end-->
 <div class="fabutr">
  <div class="fabutrl">
   <span class="fabutrltxt"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?><!--<?php echo isL(L('Classification'),'分类');?>--></span>
    <div style="width:160px; display:inline-block; vertical-align:middle">
        <select class="chosen-select" name="" onchange="return ajaxTo(this)">
         <option value="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'create_time'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('New'),'新品');?></option>
        
         <option value="<?php echo U('Category/index',array('order_type'=>'volume','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'volume'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('SalesVolume'),'销量');?></option>
         <!--<option value="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" <?php if($_GET['wishlist'] == 'condition'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('Wishlist'),'愿望单');?></option>-->
          <option value="<?php echo U('Category/index',array('order_type'=>'coll_numeber','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'coll_numeber'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('Wishlist'),'愿望单');?></option>
 <option value="<?php echo U('Category/index',array('order_type'=>'goods_retail_price','order'=>'ASC'));?>" <?php if($_GET['order_type'] == 'goods_retail_price' AND $_GET['order'] == 'ASC'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('LowToHigh'),'低至高');?></option>     
 <option value="<?php echo U('Category/index',array('order_type'=>'goods_retail_price','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'goods_retail_price' AND $_GET['order'] == 'DESC'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('HighToLow'),'高至低');?></option>        
         </select>
     </div>
  </div>
  <div class="fabutrr">
   <ul class="to-page-ul">
   <?php if($currentPage <= 1): ?><li><a  class="disable"><span class="glyphs icon-pageFirst"></span></a></li>
        <li><a  class="disable"><span class="glyphs icon-prev"></span></a></li>
    <?php else: ?>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,1);?>" class=""><span class="glyphs icon-pageFirst"></span></a></li>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,'-');?>" class=""><span class="glyphs icon-prev"></span></a></li><?php endif; ?>
    <li class="txt"><span><?php echo ($currentPage); ?></span> of <span><?php echo ($page_sze); ?></span></li>
    <?php if($currentPage >= $pageNumShown): ?><li><a  class="disable"><span class="glyphs icon-next"></span></a></li>
         <li><a  class="disable"><span class="glyphs icon-pageLast"></span></a></li>   
    <?php else: ?>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,'+');?>"><span class="glyphs icon-next"></span></a></li>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,$pageNumShown);?>"><span class="glyphs icon-pageLast"></span></a></li><?php endif; ?>
   </ul>
  </div>
  <div class="cb"></div>
 </div><!--fabutr end--> 
 <div class="cb"></div>
 
</div><!--fabut end-->
<div class="pr">

<?php if(is_mobile()){ ?>
    <div class="tcshxl" id="tcshxle">
     <div class="tcshxlt"><span class="glyphs icon-arrowLeft"></span><?php echo isL(L('ScreeningConditions'),'筛选条件');?></div>
     <div class="shxlm">
     <div class="shxlb"> 
      <b class="lh22"><?php echo isL(L('Screening'),'筛选');?></b>
      <!--<span class="colaaa fr f12 lh22">(<?php echo replace_isl('CateCount','项',$dataCount);?>)</span> --> 
      <div class="cb pb10"></div>
      <div class="sxcm">
       <ul>
       
       <?php if(is_array($scs)): foreach($scs as $key=>$s): ?><li><span class="xscml"><?php echo ($s['name']); ?></span><span class="sxcmr" type="html" value="<?php echo ($s['id']); ?>"><span class="glyphs icon-close"></span></span></li><?php endforeach; endif; ?>
       
       <?php if($selectPrice): ?><li><span class="xscml"><?php echo ($selectPrice); ?></span><span class="sxcmr"  type="text" value=""><span class="glyphs icon-close"></span></span></li><?php endif; ?>
       
       <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): ?><li>
              <span class="xscml"><b><?php echo ($ss['attribute_name']); ?>：</b><?php echo ($ss['attr_value']); ?></span>
              <span class="sxcmr" type="attr" value="<?php echo ($ss['attr_id']); ?>"><span class="glyphs icon-close"></span></span>
          </li><?php endforeach; endif; ?>
       </ul>
      </div>
      
      
      <?php if($scs || $selectPrice || $showSelectAttrs){ ?>
             <button type="submit" class="button-white mv20" onclick="return ajaxTo(this)" 
             href="<?php echo U('Category/index',array('order_type'=>$_GET['order_type'],'order'=>$_GET['order'],'text'=>$_GET['text']));?>"><div class="tc" ><?php echo isL(L('ClearAll'),'清除全部');?></div>  
            </button>
     <?php }else{ ?>  
          <p style="color:#ccc;height:40px;line-height:40px;"><?php echo isL(L('FilterItemsWill'),'您的筛选项目将显示于此');?></p> 
     <?php } ?>
       
     </div><!--筛选 end-->
     <div class="splb" id="to-tops1">
     <h1 class="active"><?php echo isL(L('CommodityCategory'),'商品类别');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
     
     <ul class="splbul" style="display:block">
      <?php if($_GET['html']){ ?><li class="clear" onclick="clearClass(this)"><?php echo isL(L('Clear'),'清除');?></li><?php } ?>
      <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
           <a href="javascript:void(0);" type="html" value="<?php echo ($c['id']); ?>" onclick="retrieval(this)" class="not-now <?php if(in_array($c['id'],$ch)): ?>ffInput--active<?php endif; ?>">
             <span class="ffInput__checkbox"></span><?php echo ($c['name']); ?>
           </a>
        </li><?php endforeach; endif; ?>
     </ul>
     </div><!--商品类别 end-->
     
     
    <?php if(is_array($attrs)): foreach($attrs as $keys=>$attr): ?><div class="splb " id="to-tops-attr-<?php echo ($keys); ?>">
         <h1 <?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>class="active"<?php endif; ?>>
             <?php echo ($attr['attribute_name']); ?> <span class="glyphs icon-more facet-icon fr"></span>
         </h1>
         <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): if($ss['attribute_name']==$attr['attribute_name']){ ?>
             <div class="clear2" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>" onclick="clearClass(this)">
                 <?php echo isL(L('Clear'),'清除');?>
              </div>
              <?php break; ?>
          <?php } endforeach; endif; ?>
         <div class="splbul" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
         <ul  style="  width:100%;<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
         <?php if(is_array($attr['attr_list'])): foreach($attr['attr_list'] as $ak=>$a): if($ak > 0): ?><li style="<?php if(($attr['attribute_name'] == '尺码') OR (strtoupper($attr['attribute_name']) == 'SIZE')): ?>width:49%;float:left;<?php endif; ?>">
                  <a href="javascript:void(0);" type="attr" value="<?php echo ($a['attr_id']); ?>" 
                  <?php if(!$a['is_true']): ?>class="disabled"<?php else: ?> class="not-now <?php if(in_array($a['attr_id'],$selectAttr)): ?>ffInput--active<?php endif; ?>" onclick="retrieval(this)"<?php endif; ?>>
                     <span class="ffInput__checkbox"></span><?php echo ($a['attr_value']); ?>
                  </a>
              </li><?php endif; endforeach; endif; ?>
         <div class="cb"></div>
         </ul>
         <?php if((strpos($attr['attribute_name'],'尺码') !== false) || (strpos(strtoupper($attr['attribute_name']),'SIZE') !==false)){ ?>
             <a href="javascript:void(0)" class="col2ab cmznn fr"><?php echo isL(L('SizeDescription'),'尺码指南');?></a>
         <?php } ?>    
         <div class="cb pb10"></div>
         </div>
         
     </div><!--尺码 end--><?php endforeach; endif; ?>  
    
    
     <div class="splb" id="to-tops2">
     <h1 <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>class="active"<?php endif; ?>><?php echo isL(L('Price'),'价格');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
     <ul class="splbul" <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>style="display:block;"<?php endif; ?>>
         <?php if($_GET['min'] && $_GET['max']){ ?>
              <li class="clear" onclick="clearPrice()"><?php echo isL(L('Clear'),'清除');?></li>
         <?php } ?>
          <li>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left">
                    <input type="text"  name="min" value="<?php echo htmlspecialchars($_GET['min']);?>" class="jiagel_input" 
                    onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('FloorPrice'),'最低价格');?>"/>
                </div>
               </div>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left">
                     <input type="text" name="max" value="<?php echo htmlspecialchars($_GET['max']);?>" class="jiagel_input" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" 
                     onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('CeilingPrice'),'最高价格');?>"/>
                </div>
               </div>
               <div class="cb"></div>
               
               <?php if($prcieError AND !$goods): ?><!--<p class="tsy2" style="display:block;"><?php echo ($prcieError); ?></p>--><?php endif; ?>
                <button type="submit" class="button-offWhite mt10 fr" onclick="retrieval(false)" style="width:34%">
                                <span class="fl"><?php echo isL(L('Determine'),'确定');?></span>
                                <span class="glyphs icon-thinArrow fr"></span>
                </button>
                <div class="cb"></div>
          </li> 
     </ul>
     </div><!--颜色 end-->
    </div><!--shxlm end-->
     
    </div>
    <div class="tcshxlb">
     <b><?php echo replace_isl('CateCount','%d项',$dataCount);?></b><input type="button" name="" value="<?php echo isL(L('DisplayResults'),'显示结果');?>" class="tcshxlb_btn close-retrieval"/>
    </div>
    
<?php }else{ ?>
    <div class="shxl">
        <div class="shxlm">
         <div class="shxlb"> 
          <b class="lh22"><?php echo isL(L('Screening'),'筛选');?></b>
          <span class="colaaa fr f12 lh22">(<?php echo replace_isl('CateCount','项',$dataCount);?>)</span>  
          <div class="cb pb10"></div>
          <div class="sxcm">
           <ul>
               
               <?php if(is_array($scs)): foreach($scs as $key=>$s): ?><li><span class="xscml"><?php echo ($s['name']); ?></span><span class="sxcmr" type="html" value="<?php echo ($s['id']); ?>"><span class="glyphs icon-close"></span></span></li><?php endforeach; endif; ?>
               
               <?php if($selectPrice): ?><li><span class="xscml"><?php echo ($selectPrice); ?></span><span class="sxcmr"  type="text" value=""><span class="glyphs icon-close"></span></span></li><?php endif; ?>
               
               <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): ?><li>
                      <span class="xscml"><b><?php echo ($ss['attribute_name']); ?>：</b><?php echo ($ss['attr_value']); ?></span>
                      <span class="sxcmr" type="attr" value="<?php echo ($ss['attr_id']); ?>"><span class="glyphs icon-close"></span></span>
                  </li><?php endforeach; endif; ?>
               </ul>
              </div>
              
              
              <?php if($scs || $selectPrice || $showSelectAttrs){ ?>
                     <button type="submit" class="button-white mv20" onclick="return ajaxTo(this)" 
                     href="<?php echo U('Category/index',array('order_type'=>$_GET['order_type'],'order'=>$_GET['order'],'text'=>$_GET['text']));?>"><div class="tc" ><?php echo isL(L('ClearAll'),'清除全部');?></div>  
                    </button>
             <?php }else{ ?>  
                  <p style="color:#ccc;height:40px;line-height:40px;"><?php echo isL(L('FilterItemsWill'),'您的筛选项目将显示于此');?></p> 
             <?php } ?>
               
             </div><!--筛选 end-->
             <div class="splb">
             <h1 class="active"><?php echo isL(L('CommodityCategory'),'商品类别');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
             
             <ul class="splbul" style="display:block">
              <?php if($_GET['html']){ ?><li class="clear" onclick="clearClass(this)"><?php echo isL(L('Clear'),'清除');?></li><?php } ?>
              <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
                   <a href="javascript:void(0);" type="html" value="<?php echo ($c['id']); ?>" onclick="retrieval(this)" class="not-now <?php if(in_array($c['id'],$ch)): ?>ffInput--active<?php endif; ?>">
                     <span class="ffInput__checkbox"></span><?php echo ($c['name']); ?>
                   </a>
                </li><?php endforeach; endif; ?>
             </ul>
             </div><!--商品类别 end-->
             
             
            <?php if(is_array($attrs)): foreach($attrs as $key=>$attr): ?><div class="splb">
                 <h1 <?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>class="active"<?php endif; ?>>
                     <?php echo ($attr['attribute_name']); ?> <span class="glyphs icon-more facet-icon fr"></span>
                  </h1>
                 <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): if($ss['attribute_name']==$attr['attribute_name']){ ?>
                     <div class="clear2" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>" onclick="clearClass(this)">
                         <?php echo isL(L('Clear'),'清除');?>
                     </div>
                     <?php break; ?>
                  <?php } endforeach; endif; ?>
                 <div class="splbul" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
                 <ul  style="  width:100%;<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
                 <?php if(is_array($attr['attr_list'])): foreach($attr['attr_list'] as $ak=>$a): if($ak > 0): ?><li style="<?php if(($attr['attribute_name'] == '尺码') OR (strtoupper($attr['attribute_name']) == 'SIZE')): ?>width:49%;float:left;<?php endif; ?>">
                          <a href="javascript:void(0);" type="attr" value="<?php echo ($a['attr_id']); ?>" 
                          <?php if(!$a['is_true']): ?>class="disabled"<?php else: ?> class="not-now <?php if(in_array($a['attr_id'],$selectAttr)): ?>ffInput--active<?php endif; ?>" onclick="retrieval(this)"<?php endif; ?>>
                             <span class="ffInput__checkbox"></span><?php echo ($a['attr_value']); ?>
                          </a>
                      </li><?php endif; endforeach; endif; ?>
                 <div class="cb"></div>
                 </ul>
                 <?php if((strpos($attr['attribute_name'],'尺码') !== false) || (strpos(strtoupper($attr['attribute_name']),'SIZE') !==false)){ ?>
                     <a href="javascript:void(0)" class="col2ab cmznn fr"><?php echo isL(L('SizeDescription'),'尺码指南');?></a>
                 <?php } ?>    
                 <div class="cb pb10"></div>
                 </div>
                 
             </div><!--尺码 end--><?php endforeach; endif; ?>  
            
            <!---->
            <div class="tccmbg">
             <div class="tccm"><span class="close cmclose" style="right:5px; top:5px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
             <img src="__HOME__/images/1123a.jpg" alt=""/>
             </div>
            </div>
            <!--尺码指南 end-->
             <div class="splb">
             <h1 <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>class="active"<?php endif; ?>><?php echo isL(L('Price'),'价格');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
             <ul class="splbul" <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>style="display:block;"<?php endif; ?>>
             <?php if($_GET['min'] && $_GET['max']){ ?>
                <li class="clear" onclick="clearPrice()"><?php echo isL(L('Clear'),'清除');?></li>
             <?php } ?>
              <li>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left"><input type="text"  name="min" value="<?php echo htmlspecialchars($_GET['min']);?>" class="jiagel_input" 
                onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('FloorPrice'),'最低价格');?>"/></div>
               </div>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left"><input type="text" name="max" value="<?php echo htmlspecialchars($_GET['max']);?>" class="jiagel_input" 
                onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('CeilingPrice'),'最高价格');?>"/></div>
               </div>
               <div class="cb"></div>
               
               <?php if($prcieError AND !$goods): ?><!--<p class="tsy2" style="display:block;"><?php echo ($prcieError); ?></p>--><?php endif; ?>
                <button type="submit" class="button-offWhite mt10" onclick="retrieval(false)">
                        <span class="fl"><?php echo isL(L('Determine'),'确定');?></span>
                        <span class="glyphs icon-thinArrow fr"></span>
                </button>
              </li> 
         </ul>
         </div><!--颜色 end-->
    </div><!--shxlm end-->
    <div class="cb xianl"></div>
  </div><!--shxl end-->
<?php } ?>

<div class="shxr">
 <div class="txtms"><!--<?php echo isL(L('Luxurybrands'),'奢侈品牌：<b>来自顶尖设计师品牌</b>');?>--><?php echo isL(L('TipsThisSite'),'温馨提示：本网站仅支持自助购物');?></div>
 <div class="zxfbrb"><!---显示4行-->
 <div class="curtain"></div>
   <ul>
        <?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span add-url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" del-url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy jrxynow" onclick="coll_and_cac(this)" id="<?php echo ($g['id']); ?>"><span class="fa fa-heart"></span></span>
               <?php else: ?>
                   <span add-url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" del-url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy" onclick="coll_and_cac(this)" style="cursor:pointer" id="<?php echo ($g['id']); ?>"><span class="fa fa-heart-o"></span></span><?php endif; ?>
              
              <a href="javascript:void(0)" class="zxfbbox">
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
            </a>  
             <div class="zxfbrbb">
             <?php if($g['stock'] <= 0): ?><span class="sold">sold out</span><?php endif; ?>
              <p><b>YOUDI WU</b></p>
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p class="pt5">￥ <?php echo ($g['goods_retail_price']); ?></p>
              <!--<p class="pt5 colf00">SOLD OUT</p>产品没货时--->
             </div>
            </li><?php endforeach; endif; ?>
        <!--<?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy jrxynow" onclick="coll_and_cac(this)"><span class="glyphs icon-heart"></span></span>
               <?php else: ?>
                   <span url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" class="jrxy" onclick="coll_and_cac(this)" style="cursor:pointer"><span class="glyphs icon-heart"></span></span><?php endif; ?>
              
                
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
             <div class="zxfbrbb">
              <p><b>YOUDI WU</b></p>
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p class="pt5">￥ <?php echo ($g['goods_retail_price']); ?></p>
             </div>
            </li><?php endforeach; endif; ?>-->
        <div class="cb"></div>
   </ul>
   
  </div><!--zxfbrb end-->
   <div class="cb pb15"></div>
    <?php if(is_mobile()): else: ?>
  <div class="fabutl" style="margin-top:8px">
      <b class="mr5"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?> >60</a>
  </div><?php endif; ?>
  <div class="fabutrr">
   <ul class="to-page-ul">
       <?php if($currentPage <= 1): ?><li><a class="disable"><span class="glyphs icon-pageFirst"></span></a></li>
            <li><a class="disable"><span class="glyphs icon-prev"></span></a></li>
        <?php else: ?>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,1);?>" onclick="return ajaxTo(this)"><span class="glyphs icon-pageFirst"></span></a></li>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,'-');?>" onclick="return ajaxTo(this)"><span class="glyphs icon-prev"></span></a></li><?php endif; ?>
        <li class="txt"><span><?php echo ($currentPage); ?></span> of <span><?php echo ($page_sze); ?></span></li>
        <?php if($currentPage >= $pageNumShown): ?><li><a class="disable"><span class="glyphs icon-next"></span></a></li>
             <li><a class="disable"><span class="glyphs icon-pageLast"></span></a></li>   
        <?php else: ?>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,'+');?>" onclick="return ajaxTo(this)"><span class="glyphs icon-next"></span></a></li>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,$pageNumShown);?>" onclick="return ajaxTo(this)"><span class="glyphs icon-pageLast"></span></a></li><?php endif; ?>
   </ul>
  </div>
  <?php if(is_mobile()): ?><div class="xshib">
  <div class="fabutl"><b class="mr5"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?>>60</a></div><!--fabutl end-->
  <span class="fhdb fr"><span class="icon19"><span class="glyphs icon-arrowUp" style="line-height:30px;"></span></span>
  <div class="cb"></div>
 </div>
 <?php else: endif; ?>
  
  <?php if(is_mobile()): else: ?>
  <span class="fhdb fr mr10" style="margin-top:2px;"><span class="icon19"><span class="glyphs icon-arrowUp" style="line-height:30px;"></span></span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span><?php endif; ?>
  <div class="cb  xian"></div>
</div><!--shxr end-->
<div class="cb pv20"></div>
</div>
<!--
<div class="zxfbt">
   <?php echo replace_isl('CateCount','项',$cateCount);?>
    <div class="page fr">
        <?php echo ($page); ?>
        </div><div class="cb"></div>
    </div>-->
  <!--  
 <div class="zxfbl zxfblmin">
  <h1 class="fhpx"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?></h1>
  <h1><?php echo isL(L('Categories'),'产品分类');?><span class="zxfblz zxfblf"></span></h1>
  <ul>
      <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
               <a href="<?php echo U('Category/index',array('html'=>$c['id']));?>" class="not-now <?php if($_GET['html'] == $c['id']): ?>active<?php endif; ?>">
                   <span></span><?php echo ($c['name']); ?>
               </a>
          </li><?php endforeach; endif; ?>
  </ul>
 </div><!--zxfbl end-->
 <!--
 <div class="zxfbr">
  <div class="zxfbrt">
      <span class="ph15 col222"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?></span>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'click_count','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('popularity'),'人气');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'create_time','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('New'),'新品');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'goods_retail_price','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('InfoPrice'),'价格');?></a>
      <a href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" class="icon28 mh15"><?php echo isL(L('Wishlist'),'愿望单');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'volume','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('SalesVolume'),'销量');?></a>
  </div>
  <div class="zxfbrb">
   <ul>
        <?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="icon29 icon29now" onclick="coll_and_cac(this)" style="cursor:pointer"></span>
               <?php else: ?>
                   <span url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" class="icon29" onclick="coll_and_cac(this)" style="cursor:pointer"></span><?php endif; ?>
                
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
             <div class="zxfbrbb">
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p><b>￥ <?php echo ($g['goods_retail_price']); ?></b></p>
             </div>
            </li><?php endforeach; endif; ?>
        <div class="cb"></div>
   </ul>
   
  </div><!--zxfbrb end-->
  <!--<div class="pv20 tc jzgd zxfbrbjzgd"><span onClick="mobile.clickPage(this,'#ajax-div')"><?php echo isL(L('LoadMore'),'加载更多');?></span></div>
  <div class="pv20 tl"><span class="fhdb"><span class="icon19">&nbsp;</span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span></div>
 </div><!--zxfbr end-->
 
 
 <!---->
    <div class="tccmbg">
     <div class="tccm"><span class="close cmclose" style="right:5px; top:5px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
     <img src="__HOME__/images/1123a.jpg" alt=""/>
     </div>
    </div>
    <!--尺码指南 end-->
<script type="text/javascript">
	/**
	 *THIS   this
	 * totalPages  总页数
	 * addEnd      追加内容节点
	 * url               请求地址
	 *  */
	mobile={};
	mobile.clickPage=function(THIS,addEnd){    
		var _this=this;
		var totalPages=parseInt("<?php echo ($pageNumShown); ?>");
		var page="<?php echo ($_GET['p']); ?>";
		page=page ? (parseInt(page)) + 1 : 2;
		var url="<?php echo U('Category/index','','');?>?order_type=<?php echo ($_GET['order_type']); ?>&order=<?php echo ($_GET['order']); ?>&keywords=<?php echo ($_GET['keywords']); ?>&wishlist=<?php echo ($_GET['wishlist']); ?>&p="+page;

		if(page >totalPages){
			$(THIS).attr('disabled',true).text("<?php echo isL(L('LoadingCompleted'),'已全部加载完毕');?>");
			return;
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
					 $(THIS).attr('ajax','').attr('disabled',false).val('Load More');
					 $(addEnd).html(response);
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
</script> 
 <script>
  $(function(){
	  $(".cmznn").bind('click', function(){
      $(".tccmbg").fadeIn("fast",function(){
		  $(".tccm").slideDown()
		  })
});
	 $(".cmclose").bind('click', function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });
		$(document).on('click',".tccmbg", function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });  
		  
	  })
 </script>
<style type="text/css">
.fljg{display:block !important;padding-bottom:10px !important;}
</style>
 </div>   
 <div class="cb"></div>
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
    <div class="cb"></div>
   </blockquote>
   <blockquote get="visits">
    
    <div class="slider1 visits">
      
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
  </div><?php endforeach; endif; ?> 
    </div>
    <div class="cb"></div>
   </blockquote>
  </div><!--zjllb end-->
 </div><!--zjll end-->
 <div class="cb"></div>
</div><!--zxfb end-->
 
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
<script type="text/javascript" src="__HOME__/js/jquery.bxslider.js"></script>
<?php if(is_mobile()): ?><script type="text/javascript">
$(document).ready(function(){
	
	$(window).scroll(function () {
	var targetTop = $(this).scrollTop();
	 
	
	 if (targetTop >100) {
		$(".flmin").addClass("flminnow")
		
		}
		 if (targetTop <100) {
		 $(".flmin").removeClass("flminnow")
		}
		
	 
	
	})	 
	
	
	
	
				
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

	//打开手机检索栏
	$(document).on('click','.sxtjdj',function(){
		$(".tcshxl").fadeIn();
		$(".tcshxlb").slideDown();
	});
	//关闭手机检索栏
	/**$(document).on('click','.tcshxlt',function(){
		alert(1)
		 $(".tcshxl").fadeOut();
		 $(".tcshxlb").slideUp();	
		 $('body,html').stop().animate({
			'scrollTop': 0,
			'duration': 300,
			'easing': 'ease-in'
		  });			
	});**/
	
	$(".tcshxlt").bind("mousedown touchstart", function(event){
event.stopPropagation();
event.preventDefault();
  $(".tcshxl").fadeOut();
		 $(".tcshxlb").slideUp();	
		 $('body,html').stop().animate({
			'scrollTop': 0,
			'duration': 300,
			'easing': 'ease-in'
		  });	
	 })	

	
	
	
});

</script>

<?php else: ?>

     <script type="text/javascript">

		$(document).ready(function(){
		
		/*加载浏览过的商品*/
		wkt=$(".zjllb").width();
		var slider1=$('.slider1').bxSlider({			
				slideWidth:  wkt/8-1,
				minSlides: 7,
				maxSlides: 8,
				slideMargin: 0,
				prevText:"",
				nextText:""
		 });
		
		/*删除浏览过的商品*/
		$(document).on('click','.dbcloseb',function(e){
			 var _this=$(this);
			 var url=_this.attr('url');
			 delRecentVisit(url);
			  e.preventDefault();
			  $(this).parent('.slide').remove();
			  slider1.reloadSlider({
				  slideWidth:  wkt/8-1,
				  minSlides: 7,
				  maxSlides: 8,
				  slideMargin: 0,
				  prevText:"",
				  nextText:"",	
			   });
		});	


/*$('.dbcloseb').click(function(e){
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
});	  */		 	  
		  
		  $('.slider2').bxSlider({			
            slideWidth:  wkt/8-1,
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
    
    
    
<script type="text/javascript" src="__HOME__/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
       $(".chosen-select").chosen();
 </script>
</body>
</html>