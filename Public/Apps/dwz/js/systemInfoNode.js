/*
*systemInfoNode(appNode,repStr) 系统新消息提示框
*appNode   生成提示框的节点('#ajax-info-node')
*repStr        ajax返回的字符串
*musicUrl        提示音 ('__PUBLIC__/Js/4331.wav')
*/
function systemInfoNode(appNode,repStr,musicUrl){
		  var str1='<div id="styst-my-infos" style="position:fixed;_position:absolute;z-index:10000;bottom:5px;right:8px;background:#fff;border:1px #ccc solid;width:250px;border-radius: 4px;box-shadow: 0px 0px 15px #888888;display:none;">';
		  str1+='<h2 style="height:20px;line-height:20px;text-indent:5px;border-bottom:1px #ccc solid;">系统提示<span style="float:right;padding-right:5px;margin-top:5px;color:red;font-size:14px;cursor:pointer;" onclick="closeMyInfos()">×</span></h2>';  
		  str1+='<div id="my-infos-content">';
		  str1+='<ul style="padding:5px;font-size:14px;min-height:50px;max-height:500px;overflow:auto;" id="styts-my-infos-ul">';
		  var str2='<li style="line-height:160%;text-align:left;"><a onclick="removeLi(this)" href="/yangGuangMeiTu/Apps/Main/index.html" target="dialog" width="1000" height="600" rel="sysInfo" mask="true">【新订单】25315647858...</a></li>';
		  str2+='<li style="line-height:160%;text-align:left;" ><a onclick="removeLi(this)" href="/yangGuangMeiTu/Apps/Main/index.html" target="dialog" width="1000" height="600" rel="sysInfo" mask="true">【新需求】我要搬家我要搬家我要搬...</a></li>';	  
		  var str3='</ul>';
		  str3+='</div>';
		  str3+='</div>';	
		  if(!arguments[1]) repStr = str2;
		  if(!arguments[2]) musicUrl = '';
		  var strAll=str1+repStr+str3;		     
	$(function(){
		  if($('#styts-my-infos-ul').length > 0){
			  $('#styts-my-infos-ul').prepend(repStr);		  
		  }	else{
			  $(appNode).append(strAll);      
		  }    
		 $('#styst-my-infos').fadeIn(1000);    
		 //提示音
		 hintVoice(appNode,musicUrl);
	});
}

/*
* closeMyInfos()关闭提示框
*/
function closeMyInfos(){
   $('#styst-my-infos').fadeOut(1000);   
	setTimeout(function(){	
			$('#styst-my-infos').remove();		
			if($('#my-hint-voice').length > 0){
				$('#my-hint-voice').remove();
		    }
	},1000);   	   
}

/*
* removeLi(_this)查看本条信息时删除本条消息
*/
function removeLi(_this){
	$(_this).parent().fadeOut(1000);   
	if($('#styts-my-infos-ul').children('li').length <=1){
		$('#styst-my-infos').fadeOut(1000);
		 setTimeout(function(){	
		        $(_this).parent().remove();			   				
		},1000);   	     
	}else{
		 setTimeout(function(){				   
			   $(_this).parent().remove();
		},1000);   
	}
}

/**
*hintVoice('#ajax-info-node','__PUBLIC__/Js/4331.wav')提示音
*appMusic  生成提示音节点
*musicUrl  提示音地址
*/
function hintVoice(appMusic,musicUrl){
	if(!arguments[0]) musicUrl = '';
	if(!musicUrl) return;
	$(function(){
		var myHintVoice=$(appMusic).children('#my-hint-voice');		
		var audio='<div id="my-hint-voice">';
		audio+='<audio src="'+musicUrl+'" autoplay >';
		audio+='<embed height="0" width="0" autostart ="true" loop="false" src="'+musicUrl+'"></embed>';
		audio+='</audio>';
		audio+='</div>';
		 if(myHintVoice.length >0){
			 myHintVoice.remove();
			 $(appMusic).append(audio);
		}else{
		     $(appMusic).append(audio);
		}	
	});
}

/**
*getSystemInfo('#ajax-info-node','http://www.baidu.com',musicUrl)  获取系统新消息
*appNode  生成提示框的节点('#ajax-info-node')
*url              AJAX请求URL  
*musicUrl    提示音 ('__PUBLIC__/Js/4331.wav')
*setTimes    间隔请求时间单位分钟  (60000 * 1) 等于一分钟
*/
function getSystemInfo(appNode,url,musicUrl,nums){ 
	 setInterval(function(){							  
	  $.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 async:false,
		 success:function(response,status,xhr){
			   if(response.success==1){	
			   	  if(response.notReviewedCount > 0){//新商家注册个数
			   	  	$('#notReviewed').css('color','red').html('('+response.notReviewedCount+')');
			   	  }	
			   	  if(response.auditCount > 0){//新需求个数
			   	  	$('#audit').css('color','red').html('('+response.auditCount+')');
			   	  }				   	  		   
				  systemInfoNode(appNode,response.data,musicUrl);					  
			  }	
		  }
	   });		
	 },60000 * nums);
}

/*DWZ动态邦定Dialog事件*/
function myDialog(_this){
		var title=$(_this).attr("title")||$(_this).text();
		var rel=$(_this).attr("rel")||"_blank";	
		var url=unescape($(_this).attr("href")).replaceTmById($(event.target).parents(".unitBox:first"));
		var options={};	
		var w=$(_this).attr("width");
		var h=$(_this).attr("height");
		if(w)options.width=w;
		if(h)options.height=h;
		options.max=eval($(_this).attr("max")||"false");
		options.mask=eval($(_this).attr("mask")||"false");
		options.maxable=eval($(_this).attr("maxable")||"true");
		options.minable=eval($(_this).attr("minable")||"true");
		options.fresh=eval($(_this).attr("fresh")||"true");
		options.resizable=eval($(_this).attr("resizable")||"true");
		options.drawable=eval($(_this).attr("drawable")||"true");
		options.close=eval($(_this).attr("close")||"");
		options.param=$(_this).attr("param")||"";
		$.pdialog.open(url,rel,title,options);
		removeLi(_this);
		event.preventDefault();
}

/*DWZ动态邦定NavTab事件*/
function myNavTab(_this){
var $this=$(_this);
var title=$this.attr("title")||$this.text();
var tabid=$this.attr("rel")||"_blank";
var fresh=eval($this.attr("fresh")||"true");
var external=eval($this.attr("external")||"false");
var url=unescape($this.attr("href")).replaceTmById($(event.target).parents(".unitBox:first"));
DWZ.debug(url);
if(!url.isFinishedTm()){
alertMsg.error($this.attr("warn")||DWZ.msg("alertSelectMsg"));
return false;}
navTab.openTab(tabid,url,{title:title,fresh:fresh,external:external});
removeLi(_this);
event.preventDefault();
}