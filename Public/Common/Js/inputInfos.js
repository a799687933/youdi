/**
success  成功或失败
*input节点显示错误信息
*showInfo('#mobile','* 这里不能为空');
*inputNode  input节点的ID
*info    错误信息 
*/
function showInfo(success,inputNode,info){
	$(function(){		
		var bg='';	   
		if(success){
			bg="green";
		}else{
			bg="#ee0101";
		}				   
		var thisPar=$(inputNode).parent();
		var thisNode=$(inputNode).height();
		var thisWidth=$(inputNode).width();
		var topPar=thisPar.offset().top;//父节点到顶的距离
        var thisPot=$(inputNode).offset().top;//当前节点到顶的距离
		var top=((thisPot-topPar)-(thisNode))-10;		
		var leftPar=thisPar.offset().left;//父节点到左的距离
        var thisLeft=$(inputNode).offset().left;//当前节点到左的距离		
		var left=((thisLeft-leftPar)+thisWidth)-20;
		thisPar.css('position','relative');
		var str='<div style="position:absolute;left:'+left+'px;top:'+top+'px;border:1px red solid;min-width:150px;min-height:30px;line-height:30px;';
		str+='padding-left:10px;border:2px #fff solid;background:'+bg+';color:#fff;border-radius: 8px;box-shadow: 1px 1px 8px #888888;';
		str+='font-weight:normal !important;z-index:10001;display:none;" class="my-news-show-info-007">';
		str+=info;
        str+='<span style="font-size:36px;position:absolute;left:5px;bottom:-17px;color:#fff;z-index:10001;">◆</span>';
		str+='<span style="font-size:36px;position:absolute;left:5px;bottom:-14px;color:#ee0101;z-index:10002;">◆</span>';
		str+='</div>';
		$(inputNode).after(str);
		if($('.parentFormundefined').length > 0) $('.parentFormundefined').remove();
		$('.my-news-show-info-007').fadeIn(500);
	});	
}

/**
*移除input节点显示错误信息
*/
function hideInfo(){
	$(function(){		
        //$('.my-news-show-info-007').fadeOut(1000);
		setTimeout(function() {
		  $('.my-news-show-info-007').remove();
		},4000);   	  
	});
}