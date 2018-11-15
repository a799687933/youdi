//编辑器高度自动舒应
$(function(){ 
  if($('.editor').length >0){
       var editorHeight=$('.dialogContent').css('height').replace('px','');//去掉px;
       $('.editor').css('height',editorHeight-100);  
       $(window).resize(function(){
            editorHeight=$('.dialogContent').css('height').replace('px','');//去掉px; 
            $('.editor').css('height',editorHeight-100); 
			
       });    
	   
  }  
  
  if($('.kindeditor').length >0){
       var kindeditorHeight=$('.dialogContent').css('height').replace('px','');//去掉px;
       $('.kindeditor').css('height',kindeditorHeight-100);  
       $(window).resize(function(){
            kindeditorHeight=$('.dialogContent').css('height').replace('px','');//去掉px; 
            $('.kindeditor').css('height',kindeditorHeight-100); 
            
       });    
       
  }    
  //
   if($('.textarea').length >0){
       var editorHeight=$('.dialogContent').css('height').replace('px','');//去掉px;
       $('.textarea').css('height',editorHeight-85);  
       $(window).resize(function(){
            editorHeight=$('.dialogContent').css('height').replace('px','');//去掉px; 
            $('.textarea').css('height',editorHeight-85);  
			if($('.iframes').length >0) $('.iframes').css('height',editorHeight-100);
       });     
  }   

   //iframe自动高度
   if($('.iframe').length >0){
       var editorHeight=$('.dialogContent').css('height').replace('px','');//去掉px;
       $('.iframe').css('height',editorHeight-110); 
       $(window).resize(function(){
            editorHeight=$('.dialogContent').css('min-height').replace('px','');//去掉px; 
            $('.iframe').css('height',editorHeight-110);  
       });     
  }   
  
});    