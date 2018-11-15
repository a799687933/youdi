/*kindeditor-4.1.10编历 textarea.kindeditor*/
$(function(){      
   $("textarea.kindeditor").each(function () {
        var _this = $(this);
		var token=_this.attr('token') ? _this.attr('token') : '';	
		var width=$(_this).css('width') ? $(_this).css('width') : 500; 
		var height=$(_this).css('height') ? $(_this).css('height') : 300; 
		//var icon=$(_this).attr('icon');//显示指定的按钮图标
		var upImgUrl=_this.attr('up-url') ? _this.attr('up-url') : '';
		if(token){
			upImgUrl+='&'+token+'='+$('input[name="'+token+'"]').val();
		}
		var ImgSpaceUrl=_this.attr('space-url') ? _this.attr('space-url') : '';
		if(!window.items) {var items;}else{items=window.items;}; //显示按扭图片，显示认全部
		KindEditor.create('.kindeditor',
			{
				allowImageUpload:true,
				resizeType : 1,
				width:width,
				height:height,
				allowFileManager: true,
                uploadJson: upImgUrl, //图片上传
                fileManagerJson: ImgSpaceUrl,	//指定浏览远程图片的服务器端程序
                items : items,
                afterBlur: function () { 
				   //编辑器光标失去焦点时执行，获取textarea的值
				  this.sync();
				},	
                afterUpload : function(url) {
					    //上传完成后执行
                        //alert(url);
                }				
		 });
    });   
});
