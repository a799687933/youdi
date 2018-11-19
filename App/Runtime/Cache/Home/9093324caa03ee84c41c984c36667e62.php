<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh" class="no-js">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title><?php echo isL(L('News'),'YOUDI WU最新资讯');?> | YOUDI WU 中国</title>
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
<link rel="stylesheet" href="__HOME__/css/swiper.css" />
<script type="text/javascript" src="__HOME__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__HOME__/js/global.js"></script>
<script type="text/javascript" src="__HOME__/js/swiper4.js"></script>
<link rel="stylesheet" href="__HOME__/layui/css/layui.css" />
<script type="text/javascript" src="__HOME__/layui/layui.all.js"></script>
<script type="text/javascript">


$(document).ready(function() {
	$(".zixun li").each(function(){
		var i = $(this).index();
		var zxpic1 = new Swiper(".zxpic_1_" + i + "", {
		autoHeight: true,
        		pagination: {
   		 el: ".zxpicb_1_" + i + "",
			clickable :true,
		  },
		   navigation: {
		    nextEl: ".zxpicr_1_" + i + "",
		    prevEl: ".zxpicl_1_" + i + "",
		  },
		    });
	})
	
	

	
	
	 /*$(".zxdz").click(function(){
		 $(this).addClass("zxdznow fa-heart")
		 $(this).removeClass("fa-heart-o")
		 })	*/
	/*$(".zxsc").click(function(){
		 $(this).addClass("fa-bookmark")
		 $(this).removeClass("fa-bookmark-o")
	})	*/
	//展开更多文章内容
	$(document).on('click','.zxfbnr b',function(){
		 $(this).hide()
		 $(this).parent(".zxfbnr").css("height","auto")	
	});
	//展开更多评价
    $(document).on('click','.zxdhb b',function(){
		 $(this).hide()
		 $(this).parent(".zxdhb").siblings(".zxdh").children("p").show()	
	});
	$(".zxdh p:gt(2)").hide(); 
});
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
				 p65();
	var j = 1;
	$(".zixun li").each(function(){
		var i = $(this).index();
		if(i > 4){
			i = 0;
			j++;
		}
		var zxpic1 = new Swiper(".zxpic_"+j+"_" + i + "", {
		autoHeight: true,
        		pagination: {
   		 el: ".zxpicb_"+j+"_" + i + "",
			clickable :true,
		  },
		   navigation: {
		    nextEl: ".zxpicr_"+j+"_" + i + "",
		    prevEl: ".zxpicl_"+j+"_" + i + "",
		  },
		    });
	})
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
/*定位光标*/
function positions(_this){
	var  li=$(_this).parents('li');
	li.find('.zxpltext').focus();
}

/*提交评论*/
var isForm10=false;
function formSend(_this){
	var li=$(_this).parents('li');
     var content=$(_this).find('input[name="content"]').val();
	 if(content)  content=content.replace(/(^\s*)|(\s*$)/g, "");
	 if(!content) {
		$(_this).find('input[name="content"]').val('').focus();
		return false;
	 }	 
	 var url=$(_this).attr('action');
	if(isForm10) return;
	isForm10=true;	 
    $.ajax({
		 type:'POST',
		 dataType: "JSON",
		 data:{content:content},
		 url:url,
		 success:function(response,status,xhr){
			  //isForm10=false;
			  res=response[0];
			  if(!res[1]){
				 if(res[3]) window.location.href=res[3];
				 return;
			  }			   
			 //生成节点
		     //content= repFace(content);//替换表情
			 var node='<p><span class="vam mr5"><?php echo getUserC("SE_NICKNAME");?></span><span class="vam" style="color:#023867;">@</span>';
             node+='<img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5">'+content+'</p>';
              li.find('.zxdh').append(node);
			  li.find('.show_time').text('刚刚');
			  $(_this).find('input[name="content"]').val('');
			  var num=parseInt(li.find('.show_app_num').text());
			  num++;
			  li.find('.show_app_num').text(num);
			  li.find('.zxdhb').show();
		  },
		   beforeSend:function(){
			 //请求前
		  }
	 });		 
	 return false;
}

/*点赞*/
var isForm6=false;
function praise(_this,url){	
    var  li=$(_this).parents('li');
	var num=parseInt(li.find('.paraise_num').text());
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
			$(_this).addClass("fa-heart-o")
			$(_this).removeClass("zxdznow fa-heart")			
			num--;
			li.find('.paraise_num').text(num);		
		}else{
			$(_this).addClass("zxdznow fa-heart")
			$(_this).removeClass("fa-heart-o")					
			num++;
			li.find('.paraise_num').text(num);			
		}
	});    
}
//收藏夹 
var isForm8=false;
function setCollection(_this,url){
	if(isForm8) return;
	isForm8=true;		
	$.getJSON(url,{},function(res){
		 isForm8=false;					  
		 res=res[0];					  
	     if(!res[1]){
			 if(res[3]) window.location.href=res[3];
			 return;
		}
		if(res[5] == 'save'){
			$(_this).removeClass('fa-bookmark').addClass('fa-bookmark-o');
		}else{
			$(_this).removeClass('fa-bookmark-o').addClass('fa-bookmark');			
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

//只显示3行
p65();
function p65(){
$(function(){
	 var p=$('.list_p p');
	 for(var i = 0;i < p.length;i++){
		 var ph=p.eq(i).height();
		 if(ph > 40){
			// alert(p.eq(i).html());
			 p.eq(i).css('height','67px').css('overflow','hidden').find('.show_all').addClass('show_class');
		 }
	 }
});
}
//展开多行
function showAlls(_this){
    var p=$(_this).parents('p');
	 p.css('overflow','auto').css('height','auto');
	 $(_this).hide();
}
</script>
<!--[if IE]>
		<script src="js/html5shiv.min.js"></script>
<![endif]-->

<style type="text/css">
.show_all{
   display:inline-block; padding:0 10px;
   height:22px;
   border:1px solid #ccc;
   color:#666;
   border-radius:6px;
   text-align:center;
   line-height:22px;
   font-size:12px;
   position:absolute;
   bottom:0px;
   right:0;
   background:#fff;
   display:none;
   cursor:pointer;
}
.show_class{
   display:inline-block;
    line-height:20px;
   height:22px; z-index:10
}
#black{
top:19.5%;
position:absolute;
float:left;
width: 100%;
height: 13%;
}

.wrap {
    margin-top: 115px !important;
}
.swiper-pagination-bullet {
    width: 6.5px;
    height: 6.5px;
   
}
.swiper-pagination-bullet-active {
    background:rgba(72, 141, 243, 0.99) !important;
    opacity: 10;
}
.swiper-pagination-bullet {

    background: #000000a3;
   
}
</style>
</head>

<body>

 <div class="nav" id="black" ><div class="navm">
  <div class="sswrap"><span class="glyphs icon-search" onclick="topRetrieval(this)"></span>
  <input type="submit" name="" value="" onclick="return topRetrieval(this)" class="ss_btn"/>
  <input type="text" name="keywords" value="" class="ss_input"/>
  </div>
 </div></div><!--nav end-->
<!-- <div class="layui-layer layui-layer-page layui-layer-dir" style="z-index: 19891015; top: 284px; left: 1067px; margin-left: -15px;"><div class="layui-layer-title" style="cursor: move;">内容</div><div id="" class="layui-layer-content" style="height: 300px;background: black"></div><span class="layui-layer-setwin"><a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;"></a></span><span class="layui-layer-resize"></span></div> -->


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

<div class="wrap">
 <div class="zixun">
  <ul id="article-list">
      
<style type="text/css">

.zxpltext {
float: left;
width: 530px;
height: 35px;
border: none;
resize: none;
color: #000;
font-size: 12px;
line-height: 16px;
font-family: "\5FAE\8F6F\96C5\9ED1";
}
#youdi {
  width: 11%;
  height: 10%;
  border-radius: 0;
  margin-left: 11px;
  margin-top: 4px;
}
#ydhand {
  margin-right: 4px;
  margin-top: 3px;
}
</style>


<?php if(is_array($result)): foreach($result as $reskey=>$res): ?><li>
    <h2 class="zixunt">
        <img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1542375294737&di=cd44d196936ce50906027c88db82e8cd&imgtype=0&src=http%3A%2F%2Fwww.jituwang.com%2Fuploads%2Fallimg%2F151007%2F258199-15100G2513752.jpg" alt="" style="margin-top: 3px;margin-left: 0px;">
      <img id='youdi' src="__HOME__/images/youdi.png"  alt=""/>
        <div class="cb"></div>
     </h2>
    <div class="zxpic zxpic_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>">
     <div class="swiper-wrapper">
     <?php if(is_array($res['album'])): foreach($res['album'] as $key=>$alb): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($alb['img']); ?>" alt=""></div><?php endforeach; endif; ?>
   </div>
   <span class="zxpicl fa  fa-caret-left zxpicl_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></span><span class="zxpicr fa  fa-caret-right zxpicr_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></span>
   <div class="zxpicicon"><b></b>HAPPY</div>
    </div><!--zxpic end-->
    
    <div class="zxpicbwrap">
    
    <div class="zxpicb zxpicb_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></div>

    
    <?php if($memberId > 0): ?><b class="zxdz fa <?php echo ($res['is_like'] ? 'zxdznow fa-heart' : 'fa-heart-o'); ?>" style="margin-left:-1px;"  onClick="praise(this,'<?php echo U('Article/clickLike',array('id'=>$res['id']));?>')"></b>
         <a href="javascript:void(0);" class="zxplun fa fa-commenting-o" style="margin-left: 0px;font-size: 24px;" onClick="positions(this)"></a> 
         <b class="zxsc fa <?php echo ($res['is_colle'] ? 'fa-bookmark' : 'fa-bookmark-o'); ?>" style="margin-top: 1px;margin-left: 0px;font-size: 23px;" onClick="setCollection(this,'<?php echo sign_url(array('id'=>$res['id'],'to_action'=>'Article/index2'),U('Article/setCollection'));?>')"></b>                     
     <?php else: ?>
         <b class="zxdz fa fa-heart-o" onClick="window.location.href='<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>';"></b>
          <a href="<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>" class="zxplun fa fa-commenting-o"></a> 
         <b class="zxsc fa fa-bookmark-o" onClick="window.location.href='<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>';"></b> <?php endif; ?> 
    </div><!--zxpicbwrap end-->
    <p class="pl15 col333 f12"><span class="paraise_num"><?php echo ($res['like_count']); ?></span>次赞</p>
    <div class="zxfbnr">
      youdi&nbsp;wu 
      <?php if($res['attr']): $YOU_LABEL=C('YOU_LABEL'); ?>
         <span>#<?php echo ($YOU_LABEL[$res['attr']]['cn_name']); ?></span><?php endif; ?>
      <?php echo (strip_tags($res['bewrite'])); ?><b>更多</b>
    </div><!--zxfbnr end-->
    <?php if($memberId > 0): ?> 
        <div class="zxdh list_p">
         <?php $times=0; ?>
         <?php if(is_array($res['reply_arr'])): foreach($res['reply_arr'] as $replyKey=>$reply): $times=$reply['times']; ?>
              <?php if($reply['user_id'] == 0): ?><p style="position:relative;"  class="plneir">
                     <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                     <span class="vam mh5" style="color:#023867;">@<?php echo ($reply['user_name']); ?></span>
                     <span><?php echo ($reply['content']); ?></span>
                     <span class="show_all" onclick="showAlls(this)">显示全部</span>
                   </p>
               <?php else: ?>
                  <p style="position:relative;"  class="plneir">
                     <span class="vam mr5"><?php echo ($reply['user_name']); ?></span><span class="vam" style="color:#023867;">@</span>
                     <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5">
                     <span><?php echo ($reply['content']); ?></span>
                     <span class="show_all" onclick="showAlls(this)">显示全部</span>
                  </p><?php endif; endforeach; endif; ?>
         </div><!--zxdh end-->
 
                <span class="show_time"></span>
             <div class="zxdhb" style="<?php if(!$res['reply_arr']): ?>display:none;<?php endif; ?>">
                <b>全部<span class="show_app_num"><?php echo ($res['comment']); ?></span>条评论</b>
                <div class="cb"></div>
             </div><?php endif; ?>  

       <span>
                  <?php echo ($res['addtime']['hour']); ?>小时
                前</span>
     <div class="zxplbox" id="zxpl1">
         <form action="<?php echo sign_url(array('article_id'=>$res['id']),U('Article/commentReply'));?>" onSubmit="return formSend(this)">
              <input name="content" class="zxpltext" value="添加评论···" onblur="if(this.value==''){this.value='添加评论···'}" onfocus="if(this.value=='添加评论···'){this.value=''}" v-model="cureInfo.Symptom" id="symptomTxt" oninput="autoTextAreaHeight(this)" >
              <div class="zxplboxr">

             <b><img id="ydhand" width="20px" src="__HOME__/images/ydhand.png"  /></b>
              <input type="submit" value="发送" class="zxplbtn">
          </form>
      </div>
      <div class="cb"></div>
     </div>

   </li><?php endforeach; endif; ?>
 <script>
    function autoTextAreaHeight(o) {
        o.style.height = o.scrollTop + o.scrollHeight + "px";
    }
    $(function () {
        var ele = document.getElementById("symptomTxt");
        autoTextAreaHeight(ele);
    })

     $(function(){
		$(".zxpic .swiper-wrapper").click(function(e){
			$(this).parents(".zxpic").find(".zxpicicon").fadeIn()
			num=$(this).parents(".zxpic").find(".zxpicicon").width()/2
			$(this).parents(".zxpic").find(".zxpicicon").css({top:event.offsetY+10,left:event.offsetX-num-10})
		
			}) 
		 
		 })
    </script>
  </ul>
 </div><!--zixun end-->
 <div class="cb"></div>
 <div class="dptjl">
  <?php if($pageNumShown > 1): ?><a href="javascript:void(0)" class="more clickMoerPage" >
          <input class="morebtn" value="Load More" type="button" onClick="mobile.clickPage(this,<?php echo ($pageNumShown); ?>,'#article-list','<?php echo U('Article/index2','','');?>/p/');"/> 
       </a><?php endif; ?>
 </div>
 <div class="cb"></div>
</div><!--wrap end--> 
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