/*DIV层居中*/
/**
*DIV层居中edcfsAed('#content',true,30);
*noes  DIV区块的ID 
*layer   是否显示遮掩层 true显示;false不显示
*trans   遮掩层透明度默认60
*/
function edcfsAed(noes,layer,trans,_fn){
	 if(!arguments[2]) trans = 60;
	 if(!arguments[3]) _fn = '';
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
			'z-index':100000,
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
*info  提示消息
*toUrl  点击确认后如果有值就执行AJAX请求
*locationUrl  点击确认后如果有值就执行跳转URL
*isMobile      是否是手机板使用
*/
function myAlert(title,info,toUrl,locationUrl,isMobile){
	var config=false; //配置弹层位置；true上下中间位置；false顶0；
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

		/*var padding3='0.3rem';
		var padding8='0.6rem';
		var fontSize13='0.65rem';		
		var myAlertsWhere='12rem'; //弹层的宽度
		var myAlertHeight='6.5rem'; //弹层的高度
		var posTop='-3.5rem'; //隐藏层负数
		var moebliWidth=($(document).width()  * 0.025); //手机宽度rem
		 //如果小于弹层的宽度
		if(moebliWidth < 12){
			var posLeft=((12- moebliWidth) / 2) +'rem';
		}else{
			var posLeft=(($(document).width()  * 0.025) -12) / 2 +'rem';
		}
		var div1Where='11.6rem'; //提示框宽度
		var div1Height='4.5rem'; //提示框高度
		var div1Margin='0.125rem'; 
		var div2Padding='0.2rem';
		var div2H1Height='1rem';
		var div2H1FontSize='0.75rem';		
		var hidePosition='-11rem';*/
	}else{
		var padding3='3px';
		var padding8='8px';
		var fontSize13='13px';
		var myAlertsWhere='300px'; //弹层的高度
		var myAlertHeight='140px'; //弹层的宽度		
		var div1Where='290px'; //提示框宽度
		var div1Height='100px'; //提示框高度	
		var div1Margin='5px'; 
		var div2Padding='8px';
		var div2H1Height='30px';
		var div2H1FontSize='12px';
		var hidePosition='-320px';
		var posTop='-140px'; //隐藏层负数
		var posotonLeft=(($(window).width() - parseInt(myAlertsWhere.replace(/[^0-9]/ig,""))) / 2) + 'px';
		var posLeft=posotonLeft;		
	}
	window.toUrl=toUrl;
	window.locationUrl=locationUrl;	
	window.hidePosition=hidePosition;	
   var alerts='<div class="my-alerts09656895748" style="z-index:200001;width:'+myAlertsWhere+';height:'+myAlertHeight+';border:1px #ccc solid;padding:0;margin:0;background:'+bg+';border-color: '+bo+';border-radius: 2px;position: fixed;top:'+posTop+';left:'+posLeft+';">';
   alerts+='<div style="width:'+div1Where+';height:'+div1Height+';margin:'+div1Margin+' auto;background: #fefacf;border:1px solid  '+bo+';">';
   alerts+='<div style="padding:'+div2Padding+';">';
   alerts+='<h1 style="height:'+div2H1Height+';margin:0;padding:0;font-size:'+div2H1FontSize+';line-height:'+div2H1Height+';text-align:left;border-bottom:1px solid #ccc;padding-bottom:10px;text-indent:5px;" >'+title+'</h1>';
   alerts+='<div style="line-height:160%;font-size:'+div2H1FontSize+';padding:'+padding3+';text-align:left;">'+info+'</div>';
   alerts+='</div></div>';
   alerts+='<div style="float:right;padding-right:5px;">';
   alerts+='<input type="button" name="" value="'+infoCancel+'" class="baibtn90 fr hideall" onclick="removeAlert(locationUrl,hidePosition)" style="border:1px solid  '+bo+';padding:'+padding3+' '+padding8+';font-family: Arial, sans-serif;font-size:'+fontSize13+';font-weight:bold;color:'+fontColor+';outline: none;border-radius: 2px;cursor: pointer;margin-left:'+div1Margin+';background:'+bubg+';">';
   if(toUrl){
	    alerts+='<input type="button" name="" value="'+infoConfirm+'" class="lvbtn90 fr mr15 hideall" onclick="alertDo(toUrl,hidePosition)" style="border:1px solid  '+bo+';padding:'+padding3+' '+padding8+';font-family: Arial, sans-serif;font-size:'+fontSize13+';font-weight:bold;color:'+fontColor+';border-radius: 2px;outline: none;margin-left:'+div1Margin+';cursor: pointer;background:'+bubg+';">';
   }else{
	   alerts+='<input type="button" name="" value="'+infoConfirm+'" class="lvbtn90 fr mr15 hideall" onclick="removeAlert(locationUrl,hidePosition)" style="border:1px solid  '+bo+';padding:'+padding3+' '+padding8+';font-family: Arial, sans-serif;font-size:'+fontSize13+';font-weight:bold;color:'+fontColor+';border-radius: 2px;outline: none;margin-left:'+div1Margin+';cursor: pointer;background:'+bubg+';">';
   }
   alerts+='</div></div>';
   if($('.my-alerts09656895748').length==0) $('body').append(alerts);
   if(config){
	   var positionCenter=(($(window).height() - parseInt(myAlertHeight.replace(/[^0-9]/ig,""))) / 2) +'px';
   }else{
	   var positionCenter=0;
   }   
   $('.my-alerts09656895748').animate({top:hidePosition},0).stop(true).animate({top:positionCenter},500).show();
   masks(40);
}

/*隐藏对话框*/
function removeAlert(locationUrl,hidePosition){
	if(!arguments[0]) locationUrl = "";
	$('.my-alerts09656895748').animate({top:hidePosition},700);
	setTimeout(function(){
	    $('.my-alerts09656895748').remove();
	   closeEdcfsAed('');
	   if(locationUrl) window.location.href=locationUrl;		
	},700);

}

/*对话框请求服务器*/
function alertDo(url,hidePosition){
	$('.my-alerts09656895748').animate({top:hidePosition},700);
	closeEdcfsAed('');	
	 $('#load-img').html('<img src="images/13221814.gif" style="margin-left:100px;position:relative;top:8px;"/> 正在玩命加载....');
	  $.ajax({
		 type:'post',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			 $('.my-alerts09656895748').remove();
			 $('#load-img').html('');
			// $('.hideall').show();
			if(response[0][1]){
			    var title='成功提示';
			}else{
			    var title='失败提示';
			}
			if(response[0][6]){
				if(response[0][2]){
					alert(response[0][2]);
					if(response[0][3]) window.location.href=response[0][3];
				}else{
					if(response[0][3]) window.location.href=response[0][3];
				}
			}else{
			  myAlert(title,response[0][2],'',response[0][3],response[0][6]);
			}			 
		  }
	   });		
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