var url=''; //请求获取下级城市地址
var goodsId='';//商品ID
var urlGetFreight=''; //获取物流费地址
/**
*切换
*/
function switchs(index){
   var myDiv=$('.my-city-2245784-list');
   myDiv.hide();
   myDiv.eq(index).show();
}

/**
*检索
*节点索引 
*id   请求ID
*/
function getCitys(index,id){
    var myDiv=$('.my-city-2245784-list');
	myDiv.hide();
	myDiv.eq(index).show();
	var spanshow=$('.my-city-2245784-h2-spans');
	getCitysGo(myDiv.eq(index),id);
	if(index==1){
		spanshow.eq(index).show();
		spanshow.eq(index+1).hide();
	}else{
	    spanshow.eq(index).show();
	}
	
}

/*AJAX获取下级列表*/
function getCitysGo(htmls,id){
	  $.ajax({
		 type:'post',
		 url:url+"?id="+id+'&goods_id='+goodsId,
		 dataType:'text',
		 success:function(response,status,xhr){
			 $(htmls).children('ul').html(response);
		  }
	   });		    
}

/*显示城市列表*/
function showCity(isMobile){
   if(!arguments[0]) isMobile = "";
   //手机板使用
   if(isMobile){
	   var windowHeight=$(window).height();
	   var windowWidth=$(window).width();
	   var myCityNode=$('#my-city-2245784');
	   var myCityNodeHeight=myCityNode.height();
	   var myCityNodeWidth=myCityNode.width();
	   var postTop=(windowHeight-myCityNodeHeight) / 2;
	   var postLeft=(windowWidth-myCityNodeWidth) / 2;
	   myCityNode.css('position','fixed').css('top',postTop+'px').css('left',postLeft+'px');
   }
   $('#my-city-2245784').show();
}

/*关闭城市列表*/
function closeCity(){
   $('#my-city-2245784').hide();
}

/**
*AJAX请求
*区县ID
*/
function ajaxGo(regionId,goodsId,district){
	  $('#my-city-2245784').hide();
 	  $.ajax({
		 type:'post',
		 url:urlGetFreight+"?city_name="+regionId+'&district='+district+'&goods_id='+goodsId,
		 dataType:'json',
		 success:function(response,status,xhr){
                  //alert(response.city_str);return;
				  $('.city-text').text(response.province_city_str);
				  if(response.first_price){
					  $('.ckxqtm_txt2').html('<span class="chen"><span id="freight">'+response.first_price+' </span></span>');
					  $('input[name="logistics"]').val(response.id);
					  $('input[name="city_name"]').val(regionId);
				  }else{
				      $('.ckxqtm_txt2').html('运费待议');
					  $('input[name="logistics"]').val('');
					  $('input[name="city_name"]').val(regionId);
				  }

		  }
	 });	   
}
