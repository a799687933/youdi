//获得当前访问的localhost



var js=document.scripts;



var jsPath;



for(var i=js.length;i>0;i--){



 if(js[i-1].src.indexOf("common.js")>-1){



   jsPath=js[i-1].src.substring(0,js[i-1].src.lastIndexOf("/")+1);



 }



}



var localhost=jsPath.replace('/Public/Home/js/','');



$(document).ready(function(){

	$(".topbg").click(function(){

		$(".ckgwdwrap").slideUp()

       $(".ckgwdwrap").removeClass("ckgwdwrapnow")

	   $(".ckywwrap").slideUp()

      $(".ckywwrap").removeClass("ckywwrapnow") 

	   $(".headert").slideUp()

	  $(".headert").removeClass("headertnow")

	  $(".tbr").removeClass("opent")

	  $(".tbr").removeClass("closet")

	  $(".tbr li a.user").removeClass("op1")

	  $(this).hide()

	    

		})

	

	

	



		$("a.navlink").hover(function(){



			 $(this).addClass("navlinknow")



			 $(this).siblings("a.navlink").addClass("navlinklr")



			 },function(){



			 $(this).removeClass("navlinknow")



			 $(this).siblings("a.navlink").removeClass("navlinklr")



		})



		$(".icon8").click(function(){



					$(this).hide()



					$(".sswrap").css("display","block")



		})



		$(".navphone").click(function(){



				if ($(window).width() < 990) {



					$(".header").toggleClass("headernow")



					$(".phonebg").toggleClass("phonebgnow")



					$(".navmin").toggleClass("navminnow")



                    $(".navminm").toggleClass("navminmnow")



					$(".box").toggleClass("boxnow")



				} 

		})	



$(".phonebg").click(function(){



				if ($(window).width() < 990) {



					$(".header").toggleClass("headernow")



					$(".phonebg").toggleClass("phonebgnow")



					$(".navmin").toggleClass("navminnow")



$(".navminm").toggleClass("navminmnow")



					$(".box").toggleClass("boxnow")



				} 



		})	



		$(".user").click(function(){



				if ($(window).width() > 990) {

					$(".topbg").show()



					$(".headert").slideToggle()



					$(".headert").toggleClass("headertnow")



					$(".ckywwrap").slideUp()



					$(".ckywwrap").removeClass("ckywwrapnow")



					$(".ckgwdwrap").slideUp()



					$(".ckgwdwrap").removeClass("ckgwdwrapnow")



$(this).siblings(".sanj").show()



				    $(this).siblings(".sanj2").show()



					$(this).parent("li").siblings("li").children(".sanj").hide()



					$(this).parent("li").siblings("li").children(".sanj2").hide()



                    $(this).parent("li").children("a").addClass("op1")



					$(this).parent("li").siblings("li").children("a").removeClass("op1")



					$(".tbr").addClass("opent")



					$(".tbr").removeClass("closet")



				} 



		})



		$(".wdzhclose").click(function(){

$(".topbg").hide()

					$(".headert").slideUp()



					$(".headert").removeClass("headertnow")



					$(".tbr").removeClass("opent")

					$(".tbr").removeClass("closet")



                    $(".tbr li a.user").removeClass("op1")



		})



		$(".like").click(function(){



				if ($(window).width() > 990) {

$(".topbg").show()

					$(".ckywwrap").slideToggle()



					$(".ckywwrap").toggleClass("ckywwrapnow")



					$(".headert").slideUp()



					$(".headert").removeClass("headertnow")



					$(".ckgwdwrap").slideUp()



					$(".ckgwdwrap").removeClass("ckgwdwrapnow")



                   $(this).siblings(".sanj").show()



				    $(this).siblings(".sanj2").show()



					$(this).parent("li").siblings("li").children(".sanj").hide()



					$(this).parent("li").siblings("li").children(".sanj2").hide()



			       $(this).parent("li").children("a").addClass("op1")



				   $(this).parent("li").siblings("li").children("a").removeClass("op1")



					$(".tbr").addClass("opent")



					$(".tbr").removeClass("closet")



					wckyw=$(".ckywlbox").width();







					$(".ckywlm li").css("width",wckyw/5);



					//首次点击运行 slider ，否则每点一次图片就缩小一点点



					if(!window.isClick) {slider3();window.isClick=1;}





                 /*   wk=$(".ckywl").width()





	                i=$(".slider3 .slide").length;	 



		            if(i<6){



						var slider3= $('.slider3').bxSlider2({			



							slideWidth: wk/5,



							minSlides: 4,



							maxSlides: 5,



						   controls: false





						  });



				    }else{



						 var slider3= $('.slider3').bxSlider2({			



							slideWidth: wk/5,



							minSlides: 4,



							maxSlides: 5,



							moveSlides: 2,



							prevText:"",



							nextText:"",



						   controls: true



						  }); 



				  }  



			  $('.dbcloselike').click(function(e){



					  e.preventDefault();



					  $(this).parent('.slide').remove();



					  slider3.reloadSlider({	  



							slideWidth: wk/5,



							   minSlides: 4,



								maxSlides: 5,



							   controls: false



					   });



				  });	 */



	 } 





})	



		$(".ckgwd").click(function(){



				if ($(window).width() > 990) {

$(".topbg").show()

					$(".ckgwdwrap").slideToggle()

					$(".ckgwdwrap").toggleClass("ckgwdwrapnow")

					$(".headert").slideUp()

					$(".headert").removeClass("headertnow")



					$(".ckywwrap").slideUp()



					$(".ckywwrap").removeClass("ckywwrapnow")



$(this).siblings(".sanj").show()



				    $(this).siblings(".sanj2").show()



					$(this).parent("li").siblings("li").children(".sanj").hide()



					$(this).parent("li").siblings("li").children(".sanj2").hide()



					$(this).parent("li").children("a").addClass("op1")



					$(this).parent("li").siblings("li").children("a").removeClass("op1")



					$(".tbr").addClass("opent")



					$(".tbr").removeClass("closet")

					wckyw=$(".ckywl2").width();

					$(".ckywlm li").css("width",wckyw/5);



wk2=$(".ckywl2").width()



//alert(wk2)





	  j=$(".slider4 .slide").length	 



	 if(j<6){



		var slider4= $('.slider4').bxSlider2({			

            slideWidth: wk2/5,

            minSlides: 4,

            maxSlides: 5,



           controls: false



          });



		  }else{

			 slider4= $('.slider4').bxSlider2({			

            slideWidth: wk2/5,

            minSlides: 4,

            maxSlides: 5,

			moveSlides: 2,

			prevText:"",

			nextText:"",

           controls: true



          }); 



			  }  



 		  $('.dbclosegwd').click(function(e){

  e.preventDefault();



  $(this).parent('.slide').remove();



  slider4.reloadSlider({	  



	    slideWidth: wk2/5,



           minSlides: 4, 

            maxSlides: 5,



           controls: false





	  });



});	  



			   } 





		})	



		$(".ywclose").click(function(){

$(".topbg").hide()

			$(".ckywwrap").slideUp()



			$(".ckywwrap").removeClass("ckywwrapnow")



			$(".tbr").removeClass("opent")



					$(".tbr").removeClass("closet")



 $(".tbr li a.like").removeClass("op1")





		})



/*$(".gwdclose").click(function(){

	$(".topbg").hide();

	$(".ckgwdwrap").slideUp();

	$(".ckgwdwrap").removeClass("ckgwdwrapnow");

	$(".tbr").removeClass("opent");

	$(".tbr").removeClass("closet");

	$(".tbr li a.ckgwd").removeClass("op1");

})*/

$(document).on('click','.gwdclose',function(){

	$(".ckgwdwrap").slideUp()

	$(".ckgwdwrap").removeClass("ckgwdwrapnow")

	$(".tbr").removeClass("opent")

	$(".tbr").removeClass("closet")

	$(".tbr li a.ckgwd").removeClass("op1")

});



$(".tbr li a").mouseenter(function(){



	$(this).parent("li").siblings("li").addClass("op3")



	})



$(".tbr li a").mouseleave(function(){



	$(this).parent("li").siblings("li").removeClass("op3")



	})	



/**$(".tbr").hover(function(){



	$(this).addClass("closet")



	},function(){



	$(this).removeClass("closet")



	})



$(".tbr").mouseenter(function(){



				$(this).addClass("closet")



				})



			$(".tbr").mouseleave(function(){



				$(this).removeClass("closet")



				})	



**/



		$(".icon6").click(function(){



				$(".ssmin").slideDown()



		})	



		$(".ssminclose").click(function(){



			  $(".ssmin").slideUp()



		})



		$(".guoj").click(function(){



				$(".guojb").toggle()



		})					



			$(".footerm >ul>li> h1").click(function(){



				if ($(window).width() < 990) {



					$(this).toggleClass("now")



					$(this).next(".fh1b").slideToggle()



					$(this).siblings("h1").removeClass("now")



					$(this).siblings("h1").next(".fh1b").slideUp()



					$(this).parent("li").siblings("li").children("h1").removeClass("now")



					$(this).parent("li").siblings("li").children(".fh1b").slideUp()



					$(this).parent("li").siblings("li").children(".kfzx").children("h1").removeClass("now")



					$(this).parent("li").siblings("li").children(".kfzx").children(".fh1b").slideUp()



				} 



		})	



		$(".footerm >ul>li>.kfzx> h1").click(function(){



				if ($(window).width() < 990) {



					$(this).toggleClass("now")



					$(this).next(".fh1b").slideToggle()



					$(this).parent(".kfzx").siblings(".kfzx").children("h1").removeClass("now")



					$(this).parent(".kfzx").siblings(".kfzx").children(".fh1b").slideUp()



					$(this).parent(".kfzx").parent("li").siblings("li").children(".kfzx").children("h1").removeClass("now")



					$(this).parent(".kfzx").parent("li").siblings("li").children(".kfzx").children(".fh1b").slideUp()



				} 



		})



		$(".select_option li").click(function(){



				txt=$(this).text()



				$(this).parent().siblings(".select_showbox").text(txt)



				name=$(this).attr('class')



				$(this).parent().siblings(".select_showbox").addClass(name)



				$(this).parent().slideUp()



				$(".select_box").removeClass("select_boxnow")



		})		



		$(window).scroll(function() {



			 var targetTop = $(this).scrollTop();



			 if ($(window).width() > 990) {



				  if (targetTop >= 10){

					$(".headert").slideUp()



					$(".ckywwrap").slideUp()

					$(".header").css("position","fixed")



				  }else{



					$(".header").css("position","")



				  }



			 }



		});				



		$('.fhdb').click(function() {



			$('body,html').stop().animate({



				'scrollTop': 0,



				'duration': 100,



				'easing': 'ease-in'



			})	



		});



		$(".zxfblz").click(function(){



			$(this).toggleClass("zxfblf")



			$(this).parent().siblings("ul").slideToggle()



		})



		/*删除保存COOKIE（你可能还喜欢、最近浏览、收藏）*/



		$('body').on('click','.icon31',function(){



			var url=$(this).attr('url');	



			var _this=$(this);



			//_this.attr('class','icon31 icon31-load');



			if(url){



			  $.ajax({



				 type:'get',



				 url:url,

				 dataType:'json',



				 success:function(response,status,xhr){



					 if(response.success){



					   _this.parents("li").hide();



                      // _this.parents(".slide").hide();



					   //重新获取实时数据



					  getNewcollection();



					 }



				  }



			   });				



			}else{



			   $(this).parents("li").hide();



			}		



		});



		$(".fldj").click(function(){



			$(".zxfbl").removeClass("zxfblmin")



			$(".fljg").show()



		})



		$(".fhpx").click(function(){



			$(".zxfbl").addClass("zxfblmin")



			$(".fljg").hide()



		})		



		$(".pxdj").click(function(){



			$(".pxb").slideToggle();



		});



		$(".fwcn li h1").click(function(){





			$(this).children(".fwf").toggle()





			$(this).children(".fwz").toggle()



			$(this).siblings(".fwcnb").slideToggle()



			$(this).parent("li").siblings("li").children(".fwcnb").slideUp()





			$(this).parent("li").siblings("li").children("h1").children(".fwf").hide()





			$(this).parent("li").siblings("li").children("h1").children(".fwz").show()



			});	



		$(".ppxxboxt").click(function(){



			$(this).siblings(".ppxxboxb").children("p").children(".ppxxrz").toggleClass("ppxxrf")



			$(this).siblings(".ppxxboxb").children(".ppxxboxbb").slideToggle()



			$(this).parent(".ppxxbox").siblings(".ppxxbox").children(".ppxxboxb").children("p").children(".ppxxrz").removeClass("ppxxrf")



			$(this).parent(".ppxxbox").siblings(".ppxxbox").children(".ppxxboxb").children(".ppxxboxbb").slideUp()



			$(this).parent().parent("li").siblings("li").children(".ppxxbox").children(".ppxxboxb").children(".ppxxboxbb").slideUp()



			$(this).parent().parent("li").siblings("li").children(".ppxxbox").children(".ppxxboxb").children("p").children(".ppxxrz").removeClass("ppxxrf")





		});



		 $(".ppxxrz").click(function(){



			 $(this).toggleClass("ppxxrf")



			 $(this).parent("p").siblings(".ppxxboxbb").slideToggle()



			 $(this).parent().parent().parent().siblings(".ppxxbox").children(".ppxxboxb").children("p").children(".ppxxrz").removeClass("ppxxrf")



			 $(this).parent().parent().parent().siblings(".ppxxbox").children(".ppxxboxb").children(".ppxxboxbb").slideUp()



			 $(this).parent().parent().parent().parent().siblings("li").children(".ppxxbox").children(".ppxxboxb").children("p").children(".ppxxrz").removeClass("ppxxrf")



			 $(this).parent().parent().parent().parent().siblings("li").children(".ppxxbox").children(".ppxxboxb").children(".ppxxboxbb").slideUp()



			 })	;  	



		$(".cjr li h2").click(function(){



			$(this).children(".cjrf").toggle()



			$(this).children(".cjrz").toggle()



			$(this).siblings(".cjrb").slideToggle()



			$(this).parent("li").siblings("li").children(".cjrb").slideUp()



			$(this).parent("li").siblings("li").children("h2").children(".cjrf").hide()



			$(this).parent("li").siblings("li").children("h2").children(".cjrz").show()



		})	;



		$(".zhub li h1").click(function(){



			$(this).children(".cjrf").toggle()



			$(this).children(".cjrz").toggle()



			$(this).siblings(".zhubb").slideToggle()



			$(this).parent("li").siblings("li").children(".zhubb").slideUp()



			$(this).parent("li").siblings("li").children("h1").children(".cjrf").hide()



			$(this).parent("li").siblings("li").children("h1").children(".cjrz").show()



		});



		$(".ckxj").click(function(){



			$(".ddbg").fadeIn("fast",function(){



				$(".ddbh").slideDown()



				})



		})	;



		$(".ddclose").click(function(){





			$(".ddbh").slideUp("fast",function(){



				$(".ddbg").fadeOut()



				})





		})	;		





		$(".dldj").click(function(){



			if ($(window).width() < 990) {



			$(this).addClass("now")



			$(".dlzcboxl").removeClass("dlzcn")



			$(".dlzcboxr").addClass("dlzcn")



			$(".zcdj").removeClass("now")



			}



		});



		$(".zcdj").click(function(){



			if ($(window).width() < 990) {



			$(this).addClass("now")



			$(".dlzcboxr").removeClass("dlzcn")



			$(".dlzcboxl").addClass("dlzcn")



			$(".dldj").removeClass("now")



			}



		});		



		$(".gwdbjzgd").click(function(){

			$(".minli").fadeIn()



			$(this).hide()



		})



		$(".dyclose").click(function(){



		  $(".dyym").slideUp("fast",function(){



			  $(".dybg").fadeOut()



			  })

		})	



		$("body").bind("click",function(evt){

				if($(evt.target).parents(".cmbox").length==0) {

					$('.cmboxb').hide();

				}

		});



		/*展开属性*/

		$(".cmboxtxt").click(function(){

			$(".cmboxb").slideUp();

			$(this).siblings(".cmboxb").slideToggle();

		});



		/*选择属性后关闭属性层*/

		$(".cmboxb li").click(function(){

				val=$(this).text();

				var attrId=$(this).attr('value');

				//点击了没有合适尺寸

				if(!attrId){

					$('.dybg').show();

					$('.dyym').show();

					return;

				}

				$(this).parent().parent(".cmboxb").slideUp();

				var cmboxtxt=$(this).parent().parent().siblings(".cmboxtxt");

				var attrClass=cmboxtxt.attr('attr-class');

				$('.'+attrClass).attr('value',attrId);

				$('.'+attrClass).text(val);

				//$(this).parent().parent().siblings(".cmboxtxt").text(val);

				//$(this).parent().parent().siblings(".cmboxtxt").attr('value',attrId);

		});





}); //load执行结束



function setTab(m,n){



	var tli=document.getElementById("menu"+m).getElementsByTagName("li");



	var mli=document.getElementById("main"+m).getElementsByTagName("blockquote");

	var blockquote='';



	for(i=0;i<tli.length;i++){



		//2016-11-22



		if(i==n){



			tli[i].className='hover';



			mli[i].style.display='block';



			blockquote=$(mli[i]);





		}else{



		    tli[i].className='';



			mli[i].style.display='none';



		}



	}



	//2016-11-22



	var action=blockquote.attr('get');



	var zjllbm=blockquote.find('.show-list');



	//myscroll 动态加载数据不友好，暂不使用



	return;



	if(action){

		  var url=localhost+'/Piece/'+action;



		  //alert(url);return;



		  $.ajax({	 



			 type:'get',



			 dataType: "text",



			 url:url,



			 success:function(response,status,xhr){



                    zjllbm.html(response);



					//$(".zjll1").myscroll({ prev: ".zjll1left", next: ".zjll1right", scrollbox: ".zjll1m" });



					//$(".zjll2").myscroll({ prev: ".zjll2left", next: ".zjll2right", scrollbox: ".zjll2m" });						



			  },



			  complete:function(){



				//alert('请求完成后,不管失败或成功都执行！');



			  },



			   beforeSend:function(){



				 //请求前



				 zjllbm.html('<img src="'+localhost+'/Public/Common/Images/13221814.gif" style="display:block;margin:100px auto;"/>');



			  },		 



			 error:function(xhr,errorText,errorType){



				 alert('error:' + xhr.status + ' : ' + xhr.statusText);



			  }



		   });		 			



	}	 



}	





/*切片效果*/



$(".zjll1").myscroll({ prev: ".zjll1left", next: ".zjll1right", scrollbox: ".zjll1m" });



//$(".zjll2").myscroll({ prev: ".zjll2left", next: ".zjll2right", scrollbox: ".zjll2m" });



$(".zjll2").myscroll2({ prev: ".zjll2left", next: ".zjll2right", scrollbox: ".zjll2m" });



$(".zjll3").myscroll({ prev: ".zjll3right", next: ".zjll3left", scrollbox: ".zjll3m" });



$(".lbb").myscroll({ prev: ".lbbleft", next: ".lbbright", scrollbox: ".lbbm" });



/*收藏商品*/



function cookieCollect(url){



  $.ajax({	 



	 type:'get',



	 dataType: "json",



	 url:url,



	 success:function(response,status,xhr){



		if(response.success){



		  alert(response.msg);



		  if(response.url) window.location.href=response.url;



		}else{



		  alert(response.msg);



		}



	  },





	  complete:function(){



		//alert('请求完成后,不管失败或成功都执行！');



	  },



	   beforeSend:function(){





		 //alert('请求发送前！');



	  },		 



	 error:function(xhr,errorText,errorType){



		 alert('error:' + xhr.status + ' : ' + xhr.statusText);



	  }



   });		   



}



/*删除我浏览过的商品*/



function delRecentVisit(url){



  $.ajax({	 



	 type:'get',



	 dataType: "json",



	 url:url,



	 success:function(response,status,xhr){



	  },



	  complete:function(){



		//alert('请求完成后,不管失败或成功都执行！');



	  },



	   beforeSend:function(){



		 //alert('请求发送前！');



	  },		 



	 error:function(xhr,errorText,errorType){



		 alert('error:' + xhr.status + ' : ' + xhr.statusText);



	  }



   });		         



}



function stopPropagation(e) {



                if (e.stopPropagation) 



                    e.stopPropagation();



                else 

                    e.cancelBubble = true;



}



$(document).bind('click',function(){



	$('.sswrap').css('display','none');



	$('.icon8').show()



});



$('.sswrap,.icon8').bind('click',function(e){



	stopPropagation(e);



});//点击页面其它地方隐藏该div



/*启动头部原望单切换*/



function slider3(){



		slider3wk=$(".ckywl").width();



		//alert(slider3wk);



		i=$(".slider3 .slide").length;	 



		if(i<6){



			var slider3= $('.slider3').bxSlider2({			



				slideWidth: slider3wk/5,



				minSlides: 4,



				maxSlides: 5,



			   controls: false



			  });



		}else{



			 var slider3= $('.slider3').bxSlider2({			



				slideWidth: slider3wk/5,



				minSlides: 4,



				maxSlides: 5,



				moveSlides: 2,



				prevText:"",



				nextText:"",



			   controls: true



			  }); 



	  }  



	  window.slider3Obj=slider3;



	  window.slider3WK=slider3wk;



}



/*删除头部原望单*/



function delSlider3(_this,url){



     $.ajax({	 



		 type:'get',



		 dataType: "json",



		 url:url,



		 success:function(response,status,xhr){



			  var getUrl=localhost+"/Piece/collection";



			  $.ajax({	 



				 type:'get',



				 dataType: "text",

				 url:getUrl,



				 success:function(res,status,xhr){



					 $('.get-new-collection').html(res);



					 setTimeout(function(){slider3();},20);



				  }

			   });	  		  

		  }



      });	  	



	  /*$(_this).parent('.slide').remove();



	  window.slider3Obj.reloadSlider({	  



			slideWidth: window.slider3WK/5,



			   minSlides: 4,



				maxSlides: 5,



			   controls: false



	   });*/



}



/*获得即时原望单数据*/



function getNewcollection(node){



	if($(".ckywwrap").css('display')=='block'){



		$(".ckywwrap").slideToggle()



		/*$(".ckywwrap").toggleClass("ckywwrapnow")



		$(".headert").slideUp()



		$(".headert").removeClass("headertnow")



		$(".ckgwdwrap").slideUp()



		$(".ckgwdwrap").removeClass("ckgwdwrapnow")



		$(this).siblings(".sanj").show()



		$(this).siblings(".sanj2").show()



		$(this).parent("li").siblings("li").children(".sanj").hide()



		$(this).parent("li").siblings("li").children(".sanj2").hide()



		$(this).parent("li").children("a").addClass("op1")



		$(this).parent("li").siblings("li").children("a").removeClass("op1")



		$(".tbr").addClass("opent")



		$(".tbr").removeClass("closet")	*/



	}	



	if(!arguments[0]) node = ".get-new-collection";	



	  var url=localhost+'/Piece/collection';



	  //var url=localhost+'/Category/index';



	  $.ajax({	 



		 type:'get',



		 dataType: "text",



		 url:url,



		 success:function(response,status,xhr){

			  $(node).html(response);



			  setTimeout(function(){slider3();},20);



		  },



		  complete:function(){

			//alert('请求完成后,不管失败或成功都执行！');

		  },



		   beforeSend:function(){

			 //alert('请求发送前！');



		  },		 

		 error:function(xhr,errorText,errorType){

			 alert('error:' + xhr.status + ' : ' + xhr.statusText);

		  }



	  });		       



}



/*AJAX获取HTML*/



function ajaxToHtml(url){

   $.ajax({

		 type:'get',

		 dataType: "text",

		 url:url,

		 success:function(response,status,xhr){

			 //是否是数组

			 var oenStr=response[0];

			 if(oenStr=='[' || oenStr=='{'){

				 response=eval('('+response+')');

				 if(response.url) ajaxToHtml(response.url);

				 return;		  

			 }

			 //获取body内容

			 var reg=/<body[^>]*>([\s\S]*)<\/body>/i;

			 var HTML=reg.exec(response);		

			 //alert(HTML[1]);return;

			// document.write(HTML[1]);return;



			 $('body').html(HTML[1]);

		  },

		   beforeSend:function(){

			 //请求前



		  }







	});		







}

