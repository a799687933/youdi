

jQuery.extend({

    createUploadIframe: function(id, uri)
	{
			//create frame
            var frameId = 'jUploadFrame' + id;
            
            if(window.ActiveXObject) {
                var io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
                if(typeof uri== 'boolean'){
                    io.src = 'javascript:false';
                }
                else if(typeof uri== 'string'){
                    io.src = uri;
                }
            }
            else {
                var io = document.createElement('iframe');
                io.id = frameId;
                io.name = frameId;
            }
            io.style.position = 'absolute';
            io.style.top = '-1000px';
            io.style.left = '-1000px';

            document.body.appendChild(io);

            return io;			
    },
    createUploadForm: function(id, fileElementId)
	{
		//create form	
		var formId = 'jUploadForm' + id;
		var fileId = 'jUploadFile' + id;
		var form = $('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');	
		var oldElement = $('#' + fileElementId);
		var newElement = $(oldElement).clone();
		$(oldElement).attr('id', fileId);
		$(oldElement).before(newElement);
		$(oldElement).appendTo(form);
		//set attributes
		$(form).css('position', 'absolute');
		$(form).css('top', '-1200px');
		$(form).css('left', '-1200px');
		$(form).appendTo('body');		
		return form;
    },

    ajaxFileUpload: function(s) {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout		
        s = jQuery.extend({}, jQuery.ajaxSettings, s);
        var id = s.fileElementId;        
		var form = jQuery.createUploadForm(id, s.fileElementId);
		var io = jQuery.createUploadIframe(id, s.secureuri);
		var frameId = 'jUploadFrame' + id;
		var formId = 'jUploadForm' + id;		
        // Watch for a new set of requests
        if ( s.global && ! jQuery.active++ )
		{
			jQuery.event.trigger( "ajaxStart" );
		}            
        var requestDone = false;
        // Create the request object
        var xml = {};   
        if ( s.global )
            jQuery.event.trigger("ajaxSend", [xml, s]);
        // Wait for a response to come back
        var uploadCallback = function(isTimeout)
		{			
			var io = document.getElementById(frameId);
            try 
			{				
				if(io.contentWindow)
				{
					 xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                	 xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
					 
				}else if(io.contentDocument)
				{
					 xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                	xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
				}						
            }catch(e)
			{
				
				jQuery.handleError(s, xml, null, e);
			}
            if ( xml || isTimeout == "timeout") 
			{				
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if ( status != "error" )
					{
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = jQuery.uploadHttpData( xml, s.dataType );    
                        // If a local callback was specified, fire it and pass it the data
                        if ( s.success )
                            s.success( data, status );
    
                        // Fire the global callback
                        if( s.global )
                            jQuery.event.trigger( "ajaxSuccess", [xml, s] );
                    } else
                        jQuery.handleError(s, xml, status);
                } catch(e) 
				{
                    status = "error";
                    jQuery.handleError(s, xml, status, e);
                }

                // The request was completed
                if( s.global )
                    jQuery.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if ( s.global && ! --jQuery.active )
                    jQuery.event.trigger( "ajaxStop" );

                // Process result
                if ( s.complete )
                    s.complete(xml, status);

                jQuery(io).unbind();

                setTimeout(function()
									{	try 
										{
											$(io).remove();
											$(form).remove();	
											
										} catch(e) 
										{
											jQuery.handleError(s, xml, null, e);
										}									

									}, 100);

                xml = null;

            }
        };
        // Timeout checker
        if ( s.timeout > 0 ) 
		{
            setTimeout(function(){
                // Check to see if the request is still happening
                if( !requestDone ) uploadCallback( "timeout" );
            }, s.timeout);
        }
        try 
		{
           // var io = $('#' + frameId);
			var form = $('#' + formId);
			$(form).attr('action', s.url);
			$(form).attr('method', 'POST');
			$(form).attr('target', frameId);
            if(form.encoding)
			{
                form.encoding = 'multipart/form-data';				
            }
            else
			{				
                form.enctype = 'multipart/form-data';
            }			
            $(form).submit();

        } catch(e) 
		{			
            jQuery.handleError(s, xml, null, e);
        }
        if(window.attachEvent){
            document.getElementById(frameId).attachEvent('onload', uploadCallback);
        }
        else{
            document.getElementById(frameId).addEventListener('load', uploadCallback, false);
        } 		
        return {abort: function () {}};	

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        // If the type is "script", eval it in global context
        if ( type == "script" )
            jQuery.globalEval( data );
        // Get the JavaScript object, if JSON is used.
        if ( type == "json" )
            eval( "data = " + data );
        // evaluate scripts within html
        if ( type == "html" )
            jQuery("<div>").html(data).evalScripts();
			//alert($('param', data).each(function(){alert($(this).attr('value'));}));
        return data;
    },
	
	//��������
	/*handleError: function( s, xhr, status, e ) 		{
	// If a local callback was specified, fire it
	if ( s.error ) {
		s.error.call( s.context || s, xhr, status, e );
	}

	// Fire the global callback
	if ( s.global ) {
		(s.context ? jQuery(s.context) : jQuery.event).trigger( "ajaxError", [xhr, s, e] );
	}
}*/
});

/**
*ajaxFileUpload(uploadurl,fileId,imgAttr,show,inputVal) 上传
*parameter uploadurl 上传地址{:U(GROUP_NAME.'/Common/thumbUpload')}
*parameter fileId   file input 控件ID
*parameter imgAttr 显示返回图片路径IMG标签ID
*parameter inputVal 缩略的input ID
*parameter big_inputVal  大图片的input ID
*parameter click_big  点击显示大图片的类名称 a 标签和IMG标签
*parameter _ROOT_  根路径
*parameter node  可选，要显示那个区块 属性ID
*TOKEN  令牌名称
*/
function ajaxFileUpload(uploadurl,fileId,imgAttr,inputVal,big_inputVal,click_big,_ROOT_,node,TOKEN){	
   if(!arguments[4]) big_inputVal = ""; 
   if(!arguments[5]) click_big = ""; 
   if(!arguments[6]) _ROOT_ = ""; 
   if(!arguments[7]) node = ""; 
   if(!arguments[8]) TOKEN = ""; 
   if(TOKEN){
	   var token=$('input[name="'+TOKEN+'"]').val();
	   uploadurl=uploadurl.replace(/.html/ig,'');
	   uploadurl+='/'+TOKEN+'/'+token;
   }
   jQuery(function(){   
   //用户输入的高度和宽度
   if($('#w').val() && $('#h').val()){
	   var w=$('#w').val();
	   var h=$('#h').val();
	   uploadurl=uploadurl.replace(/\/width\/(.*)\/height\/(.*)\/remove/,"/width/"+w+"/height/"+h+"/remove");    
   } 					   
    $.ajaxFileUpload({
        url:uploadurl,//����ͼƬ�ű�
        secureuri :false,
        fileElementId :fileId,//file�ؼ�id
        dataType : 'json',
		type:'get',
        success : function (data, status){
            if(data.state !='SUCCESS'){
                alert(data.state);
                return;
            }
            if(!data.termarkurl) {
                var imgUlrs=data.url;
            }else{
                var imgUlrs=data.termarkurl;
            }
			$('#'+imgAttr).attr('src',_ROOT_+'/'+imgUlrs).show();

			$('#'+inputVal).val(imgUlrs);  //小图片路径
			if(big_inputVal) $('#'+big_inputVal).val(data.url); //大图片路径
			if(click_big){ //点击查看大图片
			    var len=$('.'+click_big).length;
			    for(var i=0;i<len;i++){
			        if($($('.'+click_big)[i]).attr('href')){
			            $($('.'+click_big)[i]).attr('href',_ROOT_+'/'+data.url);
			        }else if($($('.'+click_big)[i]).attr('src')){
			            $($('.'+click_big)[i]).attr('src',_ROOT_+'/'+imgUlrs);
			        }
			    }
			} 
		    if(node) $('#'+node).show();//要显示那个区块,可选
			
            if(typeof(data.error) != 'undefined'){
                if(data.error != ''){
                    alert(data.error);
                }else{
                    alert(data.msg);
                }
            }
        },
        error: function(data, status, e){
            alert(e);
        }
    });
    return false;
  }) ;
}

/**
 *相关相册上传
 * relatedAlbumUpload('上传地址','file上传控件ID','创建图片的ID','根目录路径','如果有值分类广告图，否则普通相册','令牌名称')
 */
function relatedAlbumUpload(uploadurl,fileId,beforeId,_ROOT_,cateAd,TOKEN){
    
     if(!arguments[1]) fileId = "fileToUpload";
     if(!arguments[2]) beforeId = "setimgdiv";
     if(!arguments[4]) cateAd = "";
	 if(!arguments[5]) TOKEN = "";
     if(TOKEN){
	   var token=$('input[name="'+TOKEN+'"]').val();
	   uploadurl=uploadurl.replace(/.html/ig,'');
	   uploadurl+='/'+TOKEN+'/'+token;
    }	 
    $(function(){
     $.ajaxFileUpload({
        url:uploadurl,//上传地址
        secureuri :false,
        fileElementId :fileId,//file控件ID
        dataType : 'json',
        type:'get',
        success : function (data, status){
           var str='<div class="divigm" style="float:left;margin:3px;position:relative;">';
                  str+='<span style="display:block;width:200px;background:#000;filter:alpha(opacity=30);-moz-opacity:0.3;-khtml-opacity: 0.3; opacity: 0.5;font-weight:bold;position:absolute;left:0;top:0;">';
                  str+='<b class="close_upimg" onclick="delDiv(this);" style="cursor:pointer;float:right;color:red;font-size:16px;" title="删除">×</b></span>';
                  str+='<a href="'+_ROOT_+"/"+data.termarkurl+'" target="_blank"><img src="'+_ROOT_+"/"+data.termarkurl+'" width="200" height="180" style="display:block;"/></a>';
                  str+='<input type="hidden" value="'+data.termarkurl+'" name="imgAll[]"/>';
                  str+='<input type="text" name="title[]" style="width:197px;height:16px;font-size:12px;border:none;"  placeholder="链接地址"/>';
                 if(cateAd) str+='<br><input type="text" name="links[]" style="width:197px;height:16px;font-size:12px;border:none;"  placeholder="这里输入图片链接"/>';
                 str+='</div>';
           $('#'+beforeId).before(str);
            if(typeof(data.error) != 'undefined'){
                if(data.error != ''){
                    alert(data.error);
                }else{
                    alert(data.msg);
                }
            }
        },
        error: function(data, status, e){
            alert(e);
        }
    });
    return false;       
    });
}

/**
 *删除相关相册
 *
 */
function delDiv(obj){
    $(obj).parent().parent().remove();
}

/**
*shopImgs(_this)
*商铺图片上传
*/
function shopImgs(_this){	
   var uploadurl=$(_this).attr('url');
   var fileId=$(_this).attr('id');
   var src=$(_this).attr('imgs');
   jQuery(function(){   
    $.ajaxFileUpload({
        url:uploadurl,//����ͼƬ�ű�
        secureuri :false,
        fileElementId :fileId,//file�ؼ�id
        dataType : 'json',
		type:'get',
        success : function (data, status){			 
             if(src){
				 $('.'+src).attr('src',data.navTabId);
			 }else{
			   myNavTabAjaxDone(data);//重新加载
			}
			
        },
        error: function(data, status, e){
            alert(e);
        }
    });
    return false;
  }) ;
}

/*DWZ回调*/
function myNavTabAjaxDone(json){
      DWZ.ajaxDone(json);
      if (json.statusCode == DWZ.statusCode.ok){
            if (json.navTabId){ //把指定navTab页面标记为需要“重新载入”。注意navTabId不能是当前navTab页面的
                  navTab.reloadFlag(json.navTabId);
            } else { //重新载入当前navTab页面
                  navTabPageBreak();
            }
            if ("closeCurrent" == json.callbackType) {
                  setTimeout(function(){navTab.closeCurrentTab();}, 100);
            } else if ("forward" == json.callbackType) {
                  navTab.reload(json.forwardUrl);
            }
      }
}
