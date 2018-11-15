;(function($){
	$.fn.extend({
		"myscroll":function(opt){
			var init={
				prev:"",
				next:"",
				scrollbox:"",
				number:2
			}
			var self=this;
			var setting=$.extend(init,opt);
			var box=self.find("ul");
			//box.css('width',box.parent().css('width')).css('overflow','hidden');
			var boxli=self.find("li");
			var boxliwidth=self.find("li").outerWidth(true);
			var next=setting.next;
			var prev=setting.prev;
			var shul=parseInt(setting.number);
			$(next).click(function(){
				if(box.is(":animated")) {return};
				var boxliwidth=self.find("li").outerWidth(true);
				var boxli=self.find("li");
				var l=-(boxliwidth * shul);
				box.animate({left:l},300,function(){
					for(var i=0;i < boxli.length;i++) {
						if(i < shul){
							var li=boxli.eq(i).html();
							box.append('<li style="width:'+boxli.eq(i).css('width')+';">'+li+'</li>');
							boxli.eq(i).remove();
						}
					}
					box.css({left:0});
				})
			})
			
			$(prev).click(function(){
			    if(box.is(":animated")) {return};
			    var boxliwidth=self.find("li").outerWidth(true);
				var boxli=self.find("li");
				var r=-(boxliwidth * shul);
				var len=	(boxli.length - 1);	
				for(var i=0;i < boxli.length;i++) {
					if(i > (len -shul)){
					    var en=len--
						var li=boxli.eq(en).html();
						box.prepend('<li style="width:'+boxli.eq(en).css('width')+';">'+li+'</li>');
						boxli.eq(en).remove();
					}
				}	
                box.css({left:r});
				box.animate({left:0},300,function(){});				
			})
		}		
	});
	
	
	
	$.fn.extend({
		
		"rol":function(roll){
			
			var fnboxroll = $(this);
			var fnbox = $.extend(
			{
				next:"",
				prev:"",
				fnboxrollcontent:"",
				fnboxrollcontent_child:"",
				num:8
			},roll);
			
			var box = $(fnbox.fnboxrollcontent);
			var boxli = $(fnbox.fnboxrollcontent_child);
			var boxliwidth = $(fnbox.fnboxrollcontent_child).outerWidth(true);
			var next = $(fnbox.next);
			var prev = $(fnbox.prev);
			var i = 0;
			
			
			
			next.click(function(){
			   if(box.is(":animated")){return false}
			   i++;
			   if( i > boxli.length - fnbox.num ){ 
			   	i--; 
			   	return false;
			   }
			   
			   //var mywidth=$(".dashijilist").innerWidth()+fnboxroll.position().left;
			   
			    var mylen=boxli.slice(3,3+i).length;
			    var myprevWidth=0;
				
				for(var j=3;j<3+mylen;j++)
				{
					myprevWidth+=boxli.eq(j).outerWidth(true);
				}

			     box.animate({left:-myprevWidth+"px"})
			})	
			
			prev.click(function(){
			   i--
			   if(i < 0){i = 0;return}
			   if(box.is(":animated")){return false}
			   
			    var mylen=boxli.slice(3,3+i).length;
			    var myprevWidth=0;
				
				for(var j=3;j<3+mylen;j++)
				{
					myprevWidth+=boxli.eq(j).outerWidth(true);
				}

			     box.animate({left:-myprevWidth+"px"})
			})	
			
		}
	});
	
		
})(jQuery);



;(function($){
  $.fn.xuan = function(xuan1){
    var xuank = {
        current : 'cur',
        xianshi : '', //閫夐」鍗℃寜閽�
        yingc : '' //闅愯棌鐨刣iv
    };
   var bingh = $.extend(xuank,xuan1);
   var txn = this;
   txn.click(function(){
      $(this).toggleClass(xuank.current);
      $(this).parent().siblings().find(bingh.xianshi).removeClass(xuank.current);
      $(this).siblings(bingh.yingc).slideToggle();
      $(this).parent().siblings().find(bingh.yingc).slideUp();
   })
   txn.eq(0).trigger('click')
  }
})(jQuery);