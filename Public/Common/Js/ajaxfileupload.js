
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
	
	handleError: function( s, xhr, status, e ){    
			// If a local callback was specified, fire it    
			if ( s.error ) {    
				s.error.call( s.context || s, xhr, status, e );    
			}    
		
			// Fire the global callback    
			if ( s.global ) {    
				(s.context ? jQuery(s.context) : jQuery.event).trigger( "ajaxError", [xhr, s, e] );    
			}    
	}
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
*parameter node  可选，加载图标ID
*TOKEN 令牌名称
*返回错误信息节点
*/
function ajaxFileUpload(uploadurl,fileId,imgAttr,inputVal,big_inputVal,click_big,_ROOT_,node,loadImg,TOKEN,errorNode){	
   if(!arguments[4]) big_inputVal = ""; 
   if(!arguments[5]) click_big = ""; 
   if(!arguments[6]) _ROOT_ = ""; 
   if(!arguments[7]) node = ""; 
   if(!arguments[8]) loadImg = ""; 
   if(!arguments[9]) TOKEN = ""; 
   if(!arguments[10]) errorNode = ""; 
   var token=$('input[name="'+TOKEN+'"]').val();
   uploadurl=uploadurl+'/'+TOKEN+'/'+token;
   if($('#'+loadImg).length > 0) $('#'+loadImg).show();//加载图标
   $(function(){   
    $.ajaxFileUpload({
        url:uploadurl,//����ͼƬ�ű�
        secureuri :false,
        fileElementId :fileId,//file�ؼ�id
        dataType : 'json',
		type:'post',
        success : function (data, status){
			if($('#'+loadImg).length > 0) $('#'+loadImg).hide();//加载图标
            if(data.state !='SUCCESS'){
                if($(errorNode).length > 0) {
					$(errorNode).html(data.state).show();
					setTimeout(function(){
						$(errorNode).hide();				
					},3000);
				} else {
					alert(data.state);
				}
                return;
            }
            $('.show-text').show();
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

/*直接上传并执行写入操作
*uploadurl  上传地址
*fileId         file控件ID
*loadImg   加载图标
*TOKEN   令牌
*serviceUrl  上传成功后再请求服务的地址
*/
function ajaxExeService(uploadurl,fileId,loadImg,TOKEN,serviceUrl){	

   var token=$('input[name="'+TOKEN+'"]').val();
   uploadurl=uploadurl+'/'+TOKEN+'/'+token;
   serviceUrl=serviceUrl+'/'+TOKEN+'/'+token;
   if($('#'+loadImg).length > 0) $('#'+loadImg).show();//加载图标
   $(function(){   
    $.ajaxFileUpload({
        url:uploadurl,//����ͼƬ�ű�
        secureuri :false,
        fileElementId :fileId,//file�ؼ�id
        dataType : 'json',
		type:'post',
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
							   
		  $.ajax({
			 type:'post',
			 url:serviceUrl,
			 data:{imgUrl:imgUlrs},
			 dataType:'json',
			 success:function(response1,status,xhr){
				 if($('#'+loadImg).length > 0) $('#'+loadImg).hide();//加载图标			
                 myAlert(response1.title,response1.info,'',response1.locationUrl);
			  }
			  
		   });	
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
 * relatedAlbumUpload('上传地址','file上传控件ID','创建图片的ID','根目录路径','令牌','加载图标','显示图片宽度','显示图片的高度','限制上传个数','input名称')
 */
function relatedAlbumUpload(uploadurl,fileId,beforeId,_ROOT_,TOKEN,loadImg,imgWhere,imgHeight,limit,inputName){
	if(!arguments[6]) imgWhere = 200;
	if(!arguments[7]) imgHeight = 200;
	if(!arguments[8]) limit = 0;
    $(function(){
	 if(limit > 0){
		 if($('.divigm').length >=limit) {
			 alert('最多可上传'+limit+'张图片');
			 return;
		 }		 
	 }		   
     var token=$('input[name="'+TOKEN+'"]').val();	
     uploadurl=uploadurl+'/'+TOKEN+'/'+token;
     if($(loadImg).length > 0) $(loadImg).show();
     $.ajaxFileUpload({
        url:uploadurl,//上传地址
        secureuri :false,
        fileElementId :fileId,//file控件ID
        dataType : 'json',
        type:'get',
        success : function (data, status){
		   if($(loadImg).length > 0) $(loadImg).hide();
		   if(data.state=='SUCCESS'){
			   var str='<div class="divigm" style="float:left;margin:3px;position:relative;">';
					  str+='<span style="display:block;width:'+imgWhere+'px; '
					  str+='font-weight:bold;position:absolute;left:0;bottom:0;">';
					  str+='<span class="close_upimg" onclick="delDiv(this);" style="cursor:pointer;float:right;color:red;font-size:14px;background:#893a9a;color:#fff;display: inline-block;width: 15px;height: 15px;line-height: 15px;text-align: center;" title="删除">X</span></span>';
					  str+='<a href="'+_ROOT_+"/"+data.termarkurl+'" target="_blank">';
					  str+='<img src="'+_ROOT_+"/"+data.termarkurl+'" width="'+imgWhere+'" height="'+imgHeight+'" style="display:block;"/></a>';
					  str+='<input type="hidden" value="'+data.termarkurl+'" name="'+inputName+'[]"/>';
					//  str+='<input type="text" name="title[]" style="width:'+imgWhere+'px;height:25px;font-size:12px;border:none;"  placeholder="排序"/><br/>';
					 str+='</div>';
			   $('#'+beforeId).before(str);			   
		   }else{
			   alert(data.state);
		   }		   
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
   var token=$(_this).attr('token');
   var loadImg=$(_this).attr('load-img');
   $('.'+src).attr('src',loadImg);
   uploadurl=uploadurl+'/'+token+'/'+$('input[name="'+token+'"]').val();
   jQuery(function(){   
    $.ajaxFileUpload({
        url:uploadurl,//����ͼƬ�ű�
        secureuri :false,
        fileElementId :fileId,//file�ؼ�id
        dataType : 'json',
		type:'get',
        success : function (data, status){			
		  if(data.statusCode==200){
				 if(src){
					 $('.'+src).attr('src',data.navTabId);
				 }else{
				     myNavTabAjaxDone(data);//重新加载
				 }
				 window.location.href=window.location.href;			  
		  }else{
			   alert(data.message);
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
*删除商户图片
*/
function delShopsImg(_this,loadImg){
      if(confirm('确定删除?')==false)return false;
	  uploadurl=$(_this).attr('href');
	  $.ajax({
		 type:'post',
		 dataType: "json",
		 url:uploadurl,
		 success:function(response,status,xhr){
			 $(_this).parent().parent().remove();
		  },
		  complete:function(){
		  },
		   beforeSend:function(){
			  $('.'+loadImg).attr('src',$(_this).attr('load-img'));	
		  }
	   });	
	  return false;
}

/**
 *商评述我要我要晒图
 * appraiseUpload('上传地址','file上传控件ID','根目录路径','令牌','加载图标',this,'input名称')
 */
function appraiseUpload(uploadurl,fileId,_ROOT_,TOKEN,loadImg,_this,imgnames){
    $(function(){//alert(countImg);return;
	 var ul=$(_this).parents('.imgs_url');		
	  var img_lists=ul.find('.img_lists');
	  if((img_lists.length) >= 6){
		  //$(_this).attr('disabled',true);
		  ul.find('.jan_weis').hide();
		  return;
	  }else{
		   if((img_lists.length) >= 5) ul.find('.jan_weis').hide();
		  else ul.find('.jan_weis').show();  
	  }
	  if(img_lists.length > 0) var last_list=img_lists.last();
	  else  var last_list=ul.find('.upload_click');
	  
     var token=$('input[name="'+TOKEN+'"]').val();	
     uploadurl=uploadurl+'/'+TOKEN+'/'+token;
     if($(loadImg).length > 0) $(loadImg).show();
     $.ajaxFileUpload({
        url:uploadurl,//上传地址
        secureuri :false,
        fileElementId :fileId,//file控件ID
        dataType : 'json',
        type:'get',
        success : function (data, status){
		   if($(loadImg).length > 0) $(loadImg).hide();
		   if(data.state=='SUCCESS'){	
		       var str='<li class="img_lists" style="position:relative;">';
			   str+='<span style="position:absolute;top:-8px;right:5px;color:red;font-weight:bold;font-size:30px; cursor:pointer" onClick="delApprainses(this)">×</span>';
			   str+='<img src="'+_ROOT_+'/'+data.termarkurl+'" style="width:75px;height:75px;">';
			   str+='<input type="hidden" value="'+data.termarkurl+'" name="'+imgnames+'"/>';
			   str+='</li>';
			   last_list.after(str);
		   }else{
			   alert(data.state);
		   }		   
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
 *删除我要晒图
 *
 */
function delAppraise(obj,divigm,countImg){
    $(obj).parent().remove();
   var length=$(divigm).length;
   $(countImg).text(length+'/10');	
}

/*上传文件
*uploadurl 上传地址
*fileId         file控件ID
*_PUBLIC_   images文件夹路径
*addendNode  添加文件节点
*/
function relatedFileUpload(uploadurl,fileId,_PUBLIC_,addendNode){
    $(function(){
      if($('.file-all').length >= 5){
          alert('最多可上传5个文档');
          return;
      }        
     $('#show-load-img').show();    
     $('#submits').attr('disabled',true); 
     $.ajaxFileUpload({
        url:uploadurl,//上传地址
        secureuri :false,
        fileElementId :fileId,//file控件ID
        dataType : 'json',
        type:'get',
        success : function (data, status){
           $('#show-load-img').hide();  
           $('#submits').attr('disabled',false);
           if(data.state=='SUCCESS'){
               //判断文件类型
               var fileType=data.termarkurl;
               var path=fileType;
                var pathName=fileType.split('/');
                pathName=pathName[pathName.length-1].toUpperCase();            
                fileType=fileType.split('.');
                fileType=fileType[fileType.length-1].toUpperCase();

                var imgName='';
                if(fileType=='DOC' || fileType=='DOCX'){
                    imgName=_PUBLIC_+'/doc.png';
                }else if(fileType=='PDF'){
                    imgName=_PUBLIC_+'/adobe_reader.png';
                }else if(fileType=='JPG' || fileType=='PNG' || fileType=='GIF'){
                    imgName=_PUBLIC_+'/imgs.png';
                }else if(fileType=='XLSX' || fileType=='XLS'){
                    imgName=_PUBLIC_+'/xlsx.png';
                }
                var str='';
                str+='<div style="position:relative;" onMouseOver="fileShow(this)" onMouseOut="fileHide(this)" class="file-all">';
                str+='<input type="hidden" name="files[]" value="'+path+'">';
                str+='<div style="width:100%;height:100%;position:absolute;top:0;';
                str+='left:0;z-index:9998;background:#000;filter:alpha(opacity=30); opacity: 0.3;-moz-opacity:0.3;';
                str+='border-radius: 6px;display:none;" class="show-files-44"></div>';
                str+='<span style="position:absolute;right:5px;top:-2px;font-size:30px;z-index:9999;color:red; ';
                str+='cursor:pointer;display:none;" onclick="delFiles(this)" class="show-files-44">×</span>';
                str+='<img src="'+imgName+'" style="position:relative;top:5px;" />'+pathName;
                str+='</div>';                  
                $(addendNode).append(str);
           }else{
                alert(data.state);
           }    
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
/*显示上传文件删除按钮*/
function fileShow(_this){
    $(_this).children('.show-files-44').show();
}
/*隐藏上传文件删除按钮*/
function fileHide(_this){
    $(_this).children('.show-files-44').hide();
}
/*删除上传文件*/
function delFiles(_this){
   $(_this).parent().remove();
}
