/*DIV层居中*/
/**
*DIV层居中edcfsAed('#content',true,30);
*noes  DIV区块的ID 
*layer   是否显示遮掩层 true显示;false不显示
*trans   遮掩层透明度默认60
*/
function edcfsAed(noes,layer,trans,_fn,orderId){
	 if(!arguments[2]) trans = 60;
	 if(!arguments[3]) _fn = '';
	 if(!arguments[4]) orderId = '';
     if(layer) masks(trans);
	  var W= jQuery(noes).width();
	  var H= jQuery(noes).height();
	  var left=(jQuery(window).width()-W) / 2 ;
	  var top=(jQuery(window).height()-H) / 2 ;
	  jQuery(noes).css({
	       'position':'absolute',
			'left':left+'px',
			'top':top+'px',
			'position':'fixed',
			'_position':'absolute',
			'z-index':200000
	  }).fadeIn(500);	
	  jQuery(window).resize(function(){
			if($(noes).css('display')=='block') edcfsAed(noes);
	  });
	  if(_fn) _fn();
	  if(orderId) $('#order-id').val(orderId);
}

/*关闭DIV与遮掩层*/
function closeEdcfsAed(nodeId){
     jQuery(nodeId).fadeOut(500);
	 jQuery('#my-alerts-masks').fadeOut(500);
}

/*
遮掩层
*/
function masks(trans){
	
    jQuery(function(){
		 var width=jQuery(document.body).outerHeight(true)+'px';
		 var height=jQuery(document.body).outerWidth(true)+'px';
		 if(jQuery('#my-alerts-masks').length==0){
			  var DIV='<div id="my-alerts-masks"'; 
			  DIV+=' style="background:#000;filter:alpha(opacity='+trans+');-moz-opacity:'+trans / 100+';-khtml-opacity:'+trans / 100+';opacity:'+trans / 100+';width:'+ width +';height:'+ height;
			  DIV+=';position:absolute;top:0;left:0;position:fixed;_position:absolute;z-index:100000;display:none;">';
			  DIV+='</div>';
			  jQuery('body').append(DIV);
		 }
		 jQuery('#my-alerts-masks').css({
			'height':+width,
			'width':+height,
			'position':'absolute',
			'height':'100%',
			'width':'100%',
			'top':0,
			'left':0,
			'position':'fixed',
			'_position':'absolute',
			'z-index':10000,
			'background':'#000',
			'filter':'alpha(opacity='+trans+')',
			'-moz-opacity':trans / 100,
			'-khtml-opacity':trans / 100,
			'opacity': trans / 100,			   
		  }).fadeIn(500);
		  jQuery(window).resize(function(){
				  var block=jQuery('#my-alerts-masks').css('display');
				  if(block=='block'){
				   jQuery('#my-alerts-masks').css({
						   'height':+jQuery(document.body).outerHeight(true)+'px',
						   'width':+(jQuery(document.body).outerWidth(true)+jQuery(document).scrollLeft()+300)+'px'
					});
				  }
			 });   
	 });
}

window.toUrl='';
window.locationUrl='';
window.hidePosition='';
/**对话框
*title   提示标题
*info  提示消息(包含html标签p)
*toUrl  点击确认后如果有值就执行AJAX请求
*locationUrl  点击确认后如果有值就执行跳转URL
*isMobile      是否是手机板使用
* type    提示类型1成功；2确定提示；3错误提示
*/
function myAlert(title,info,toUrl,locationUrl,isMobile,type){
	var config=true; //配置弹层位置；true上下中间位置；false顶0；
	//不提示直接请求
	if(!info && toUrl){
		alertDo(toUrl);
		return;
	}else if(!info && locationUrl){//直接跳转
		window.location.href=locationUrl;
		return;
	}
	var bg='#8ec835'; //背景色
	var bo="#5E8523";//边框色
	var bubg="#5E8523"; //按钮背景色
	var fontColor="#fff";//按钮字色
    if(!arguments[2]) toUrl = "";
	if(!arguments[3]) locationUrl = "";
	if(!arguments[4]) isMobile = '';
	if(!arguments[5]) type = 1;
	//提示类型
	var inco='';
	if(type==1){
	    inco='my-alerts09656895748-icon21';
	}else if(type==2){
	    inco='my-alerts09656895748-icon23';
	}else if(type==3){
	     inco='my-alerts09656895748-icon22';
	}
	var infoCancel='取消';
	if(window.isCancel) infoCancel= window.isCancel;
	var infoConfirm='确定';
	if(window.isConfirm) infoConfirm= window.isConfirm;	
	if(isMobile){		
		//不提示直接请求
		if(!info && toUrl){
			alertDo(toUrl);
			return;
		}else if(!info && locationUrl){//直接跳转
			window.location.href=locationUrl;
			return;
		}else{
			if(confirm(info)){
				alertDo(toUrl,0);
				return;
			}else{
				return false;
			}		
		}			
	}else{
		var hidePosition='-400px'; //首次头隐藏负数
	}
	window.toUrl=toUrl;
	window.locationUrl=locationUrl;	
	window.hidePosition=hidePosition;	
   var alerts='';
   /*alerts+='<div class="my-alerts09656895748" style="dispay:block;">';
   alerts+='<span class="my-alerts09656895748-icon20" onclick="removeAlert(locationUrl,hidePosition)">&nbsp;</span>';
   alerts+=' <p class="my-alerts09656895748-lqcgt">';
   alerts+='<span class="'+inco+'">'+title+'</span></p>';
   alerts+='<div class="my-alerts09656895748-lqcg" style="text-align:center;">';
   alerts+=info;
   alerts+=' </div>';
   alerts+='<p class="tc" style="text-align:center;">';
   if(type==2) alerts+='<a href="javascript:void(0);" class="my-alerts09656895748-btnlink1 my-alerts09656895748-mr20" onclick="removeAlert(locationUrl,hidePosition)">'+infoCancel+'</a>';  
   if(toUrl){
       alerts+='<a href="javascript:void(0);" class="my-alerts09656895748-btnlink2" onclick="alertDo(toUrl,hidePosition)">'+infoConfirm+'</a></p>';
   }else{
       alerts+='<a href="javascript:void(0);" class="my-alerts09656895748-btnlink2" onclick="removeAlert(locationUrl,hidePosition)">'+infoConfirm+'</a></p>';
   }  */
   alerts+='<div class="my-alerts09656895748" style="dispay:block;">';
   alerts+='<h1>'+title+'<span class="close" onclick="removeAlert(locationUrl,hidePosition)">X</span></h1>';
   alerts+='<div class="boxm">';
   alerts+='<p style="text-align:center; font-size:16px; padding-bottom:20px;">'+info+'</p>';
   alerts+='<div style="position:relative; float:right; width:50%;margin-top:30px;">';
    if(toUrl){
       alerts+='<input id="pwd-submit"  value="'+infoConfirm+'" onclick="alertDo(toUrl,hidePosition)" class="tjdzbtn" style=" width:100%; float:none" type="button">';
	}else{
	   alerts+='<input id="pwd-submit"  value="'+infoConfirm+'" onclick="removeAlert(locationUrl,hidePosition)" class="tjdzbtn" style=" width:100%; float:none" type="button">';
	}
   alerts+='<span class="glyphs icon-thinArrow" style="position:absolute; right:10px; line-height:38px; color:#fff; font-size:1.25em"></span>';
   alerts+='</div>';
   alerts+='<div class="cb"></div>';
   alerts+='</div>';
   if($('.my-alerts09656895748').length==0) $('body').append(alerts);
   var posotonLeft=(($(window).width() - parseInt($('.my-alerts09656895748').width())) / 2) + 'px';
   var posotonTop=(($(window).height() - parseInt($('.my-alerts09656895748').height())) / 2 )-50 + 'px';
   if(config){
	   var positionCenter=posotonTop;
   }else{
	   var positionCenter=0;
   }  
  
   $('.my-alerts09656895748').css('left',posotonLeft+'px');
   $('.my-alerts09656895748').animate({top:hidePosition},0).stop(true).animate({top:positionCenter},500).show();
   masks(40);
}

/*隐藏对话框*/
function removeAlert(locationUrl,hidePosition){
	if(!arguments[0]) locationUrl = "";
	$('.my-alerts09656895748').animate({top:hidePosition},500);
	setTimeout(function(){
	    $('.my-alerts09656895748').remove();
	   closeEdcfsAed('');
	   if(locationUrl) window.location.href=locationUrl;		
	},700);

}

/*对话框请求服务器*/
function alertDo(url,hidePosition){
	  if(url.indexOf('history.') > -1) {history.back(-1);return;}
	  $('.my-alerts09656895748').remove();
	  //masks(60);
	  //加载图标
	  var loadString='<div id="ajax-return-divs-loads">';
	  loadString+='<span id="ajax-return-divs-loads-span">loading...</span>';
	  loadString+='</div>';
	  //if($('#ajax-return-divs-loads').length == 0) $('body').append(loadString);						 
	  $.ajax({
		 type:'post',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			$('#ajax-return-divs-loads').remove();
			if(response[0][4]){//回调函数
			    $('#ajax-return-divs').remove();
			    closeEdcfsAed('');  		
				//var data=response[0][3] ? response[0][3] : 
				eval(response[0][4])(response[0][3]);
			}else{
				returnInfos(response[0][1],response[0][2],response[0][3],response[0][6]);
			}		 
		  }
	   });		
}

/**
*ajax请求返回提示
*success      操作成功 
*infos            提示消息
*locationUrl  跳转URL
*isMobile     是否手机模块
*/
function returnInfos(success,infos,locationUrl,isMobile){
	//alert(0);
	//不提示信息，有URL直接跳转
	if(locationUrl) window.location.href=locationUrl;		
	return;
	
   //如果手机模块
   if(isMobile){
	   if(infos) alert(infos);
	   if(locationUrl) window.location.href=locationUrl;		
	   return;
   }
   
   //PC模块
   if(!infos){
	   if(locationUrl) window.location.href=locationUrl;		
	   return;
   }
   var classNames=success ? 'ajax-return-divs-span102' : 'ajax-return-divs-span103';
   var info='';
   info+='<div id="ajax-return-divs">';
   info+='<span id="'+classNames+'">&nbsp;'+infos+'</span>';
   info+='</div>';
   if($('#ajax-return-divs').length==0) $('body').append(info);
   setTimeout(function(){
	   $('#ajax-return-divs').remove();
	   closeEdcfsAed('');   
	   if(locationUrl) window.location.href=locationUrl;		
	},2000);   
}

/*验证手机号*/
function isMobile(mobile){
	var telReg = !!mobile.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/);
    return telReg;
}

/*只能输入数字*/
function isNum(value){
    var reg = /^\d+$/;
     if(reg.test(value)){
	     return true;
	 }else{
	     return false;
	 }
}

/*提取字符串中的数字*/
function getNum(text){
	var value = text.replace(/[^0-9]/ig,""); 
	return value;
}

/*只能输入英文、数字、下横线、中横线、*/
function isNumAbcs(value){
    var reg = /^[A-Za-z0-9-_\u554A-\u9C52]+$/;
     if(reg.test(value)){
	     return true;
	 }else{
	     return false;
	 }
}
/*验证邮箱*/
function isEmail(email){
	   var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	   if(!myreg.test(email)){
			return false;
	  }
	  return true;
}

/**
*格式化金额  
*s    数字金额
*n    保留的小数位数
*y    是否使用以豆号为分隔
**/
function fmoney(s, n, y) {  
    if(!arguments[1]) n = 2;
	if(!arguments[2]) y = false;
    n = n > 0 && n <= 20 ? n : 2;  
    f = s < 0 ? "-" : ""; 
    s = parseFloat((Math.abs(s) + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
	if(y){
		var l = s.split(".")[0].split("").reverse(),  
		r = s.split(".")[1];  
		t = "";  
		for(i = 0; i < l.length; i++ ) {  
		   t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");  
		}  
		return f + t.split("").reverse().join("") + "." + r.substring(0,2);
	}else{
	     return s;
	}
}

/*AJAX同步请求
*url       请求URL
*type    返回类型
*/
function ajaxToDo(url,type){
	  var json='';
	  $.ajax({
		 type:'get',
		 url:url,
		 async : false,
		 dataType : type,
		 success:function(response,status,xhr){
			 json=response;
		  }
	   });	
	  return json;
}

/**
*跟据当前语言货币汇率计算出价格，参照货币为人民币
*以1元为公式，跟据传进来的价格 X 汇率，保留下两位小数点
*amount  总金额
*C_DATA  汇率
*
*/
function conversio(amount,C_DATA){
	if(!arguments[1]) C_DATA = "";
    if(C_DATA){
		return fmoney(amount * parseFloat(C_DATA));
	}else{
		return fmoney(amount);
	}
}