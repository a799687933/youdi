$(function(){
	 $(".iconmm1").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType("psw","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType("psw","password");
				 })
				function changeType(t,action){
			var p = document.getElementById("psw");
			p.type=action;
			}  
				 
				$(".iconmm2").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType2("password","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType2("password","password");
				 }) 

            function changeType2(t,action){
			var p2 = document.getElementById("password");
			p2.type=action;
			} 
			
			$(".iconmm3").toggle(function(){
			 $(this).addClass("icon-hide")
			  changeType3("not_password","text");
			 },function(){ 
				 $(this).removeClass("icon-hide")
				  changeType3("not_password","password");
				 }) 

            function changeType3(t,action){
			var p3 = document.getElementById("not_password");
			p3.type=action;
			} 
			

	$(".mmpsw").focus(function(){
     $(this).parent(".mmbox").addClass("mmboxnow")
    });
   
  function stopPropagation(e) {
		  if (e.stopPropagation) 
			  e.stopPropagation();
		  else 
			  e.cancelBubble = true;
	  }

	  $(document).bind('click',function(){
		  $('.mmbox').removeClass("mmboxnow")
	  });

	   $('.mmbox').bind('click',function(e){
		  stopPropagation(e);
	  }); 
	})
		 
