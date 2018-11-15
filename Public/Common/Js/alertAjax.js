var opts={}; 
function alertMask(trans){     	
    jQuery(function(){
		 var width=jQuery(document.body).outerHeight(true)+'px';
		 var height=jQuery(document.body).outerWidth(true)+'px';
		 if(jQuery('#my-alerts-masks').length==0){
			  var DIV='<div id="my-alerts-masks"'; 
			  DIV+=' style="background:#000;filter:alpha(opacity='+trans+');-moz-opacity:';
			  DIV+=trans / 100+';-khtml-opacity:'+trans / 100+';opacity:'+trans / 100+';width:'+ width +';height:'+ height;
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
			'background':'#fff',
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

function closeMask(){
    $('#my-alerts-masks').stop(true).fadeOut(300); 
}

function toAjaxs(msg,url,_this){
	if(msg){
		if(confirm(msg)==false){
			closeMask(30);
			return false;
		}
	}
	var loadStr='<div id="alet-load" style="width:200px;height:30px;';
	loadStr+='background:#fff url('+window.__COM__+'/Images/13221814.gif) no-repeat 4% center;position: absolute;top:50px;';
	loadStr+='left:42%;position:fixed;_position:absolute;z-index:100001; line-height:30px;border-radius:4px;box-shadow: 0px 0px 10px #eeeeee;';
	loadStr+='text-indent: 40px;border:1px solid #ffe2a6;">Being loaded...</div>';	
    $.ajax({
         type:'post',
         dataType: 'json',
         url:url,
         success:function(response,status,xhr){            
             if(response.status==1){
				 success(response.info,0);
				 setTimeout(function(){
					 $('#alet-load').remove();	
					 closeMask();
					if(_this) {
						$(_this).parent().parent().remove();
					}else{
						if(response.url){
							window.location.href= response.url;     
						}						
					}
			     },2000);         
             }else{
				 error(response.info,0);
				 setTimeout(function(){
					 $('#alet-load').remove();	
					 closeMask();
					 if(response.url){
					      window.location.href= response.url;   
					 }
			     },2000);        
             }            
          },
          complete:function(){
             $('#alet-load').remove();
          },
           beforeSend:function(){
              alertMask(30);
              $('body').append(loadStr);
          },         
         error:function(xhr,errorText,errorType){
             alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
          }
       });     	
}

/*成功提示*/
function success(info,second){
	  if(second > 0) alertMask(30);
    var returnStr='<div id="alet-load" style="min-width:100px;min-height:30px;';
	returnStr+='background:#fff4d8 url('+window.__COM__+'/Images/success.png) no-repeat 3% center;position: absolute;top:50px;'; 
	returnStr+='left:42%;position:fixed;_position:absolute;z-index:100001; line-height:220%;border-radius:4px;';
	returnStr+='box-shadow: 0px 0px 10px #eeeeee;text-indent: 25px;color:green;border:1px solid #ffe2a6;">'+info+'</div>';					 
	$('body').append(returnStr);		  
	 if(second > 0){
		 setTimeout(function(){
			 $('#alet-load').remove();	
			 closeMask();		 
		 },second);
	 }
}
/*错误提示*/
function error(info,second){
    if(second > 0) alertMask(30);
	 var returnStr='<div id="alet-load" style="min-width:100px;min-height:30px;padding-right:5px;';
	 returnStr+='background:#fff4d8 url('+window.__COM__+'/Images/error.png) no-repeat 3% center;position: absolute;top:50px;'; 
	 returnStr+='left:42%;position:fixed;_position:absolute;z-index:100001; line-height:230%;border-radius:4px;';
	 returnStr+='box-shadow: 0px 0px 10px #eeeeee;text-indent: 25px;color:red;border:1px solid #ffe2a6;">'+info+'</div>';					 
	 $('body').append(returnStr);
    if(second > 0){
		 setTimeout(function(){
			 $('#alet-load').remove();	
			 closeMask();		 
		 },second);
    }	
}
