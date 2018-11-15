;(function($){
	$.fn.extend({
		"myscroll2":function(opt){
			
			var init={
				prev:"",
				next:"",
				scrollbox:"",
				number:"3"
			}
			var self=this;
			var setting=$.extend(init,opt);
			
			var box=self.find("ul");
			var boxli=self.find("li");
			var boxliwidth=self.find("li").outerWidth(true)*2;
			var next=setting.next;
			var prev=setting.prev;
			var shul=setting.number;
			
			if(boxli.length > shul){
			$(next).click(function(){
				if(box.is(":animated")) {return};
				var boxliwidth=self.find("li").outerWidth(true);
				var boxli=self.find("li");
				box.animate({left:-boxliwidth},1000,
					function(){
					box.css({left:0})
					boxli.eq(0).appendTo(box)
				})
			})
			
			$(prev).click(function(){alert(0);
				if(box.is(":animated")) {return};
				var boxliwidth=self.find("li").outerWidth(true);
				var boxli=self.find("li");
				box.css({left:-boxliwidth});
				box.animate({left:0},1000)
				boxli.last().prependTo(box);
			})
			
			
			/**var auto = setInterval(function(){
				$(next).trigger("click");	
			},5000)
			
			
			$(self).hover(function(){
				clearInterval(auto)
			},function(){
				auto = setInterval(function(){
				$(next).trigger("click");	
				},5000)	
			})
			**/
			}		
			
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
				num:3
			},roll);
			
			var box = $(fnbox.fnboxrollcontent);
			var boxli = $(fnbox.fnboxrollcontent_child);
			var boxliwidth = $(fnbox.fnboxrollcontent_child).outerWidth(true)*3;
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