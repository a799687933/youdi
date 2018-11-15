//dialog 高度和宽度自动舒应
 $(function(){
      var autoHeight=$(window).height()-50;//窗口高度
      var autoWidth=$(window).width()-300;//窗口宽度
      var autoSize=$('.auto-size');
      for(var i=0;i < autoSize.length;i++){
          if(!$(autoSize[i]).attr('width') && !$(autoSize[i]).attr('height')){
              $(autoSize[i]).attr('width',autoWidth).attr('height',autoHeight);
          }
      }
      $(window).resize(function(){
           if(autoSize.attr('width') && autoSize.attr('height')){  
           var resizeAutoHeight=$(window).height()-50;//窗口高度
           var resizeAutoWidth=$(window).width()-300;//窗口宽度         
               $('.auto-size').attr('width',resizeAutoWidth).attr('height',resizeAutoHeight);
           }
       });
 });   