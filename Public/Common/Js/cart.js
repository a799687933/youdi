/**
*inputName  值input名称
*fuhao   增加或减少+或-
*retrievalUrl  检查库存URL
*cartUrl  添加或修改购物车URL
*nums  商品一次限购个数
*logistics  物流费input name名称
* zhuhe_name 组合购买<input type="checkbox" name="zhuhe_name">input名称
*/
function carts(inputName,fuhao,retrievalUrl,cartUrl,nums,logistics,zhuhe_name){
	  if(!arguments[4]) nums = 0;
	  if(!arguments[5]) {
		  logistics = '';
	  }else{
		  logistics='&freight='+$('input[name="'+logistics+'"]').val();
	  }
	  if(!arguments[6]){
	      zhuhe_name="";
	  }else{
	      var zhuhe=$('input[name="'+zhuhe_name+'"]');
	      var zhuheId='';
	      for(var i=0;i < zhuhe.length;i++){
	          if(zhuhe.eq(i).attr('checked')) zhuheId+=zhuhe.eq(i).val()+',';
	      }
	      zhuhe_name="&zhuhe_id="+zhuheId;
	  }
	  
	  //城市名称
	  var cityName='';
	  if($('input[name="city_name"]').length > 0){
		  cityName='&city_name='+$('input[name="city_name"]').val();
	  }
	  
	  //商品属性ID集合
	   var attrIds='';
    /* var radioClass=$('.radio_class');    
     for(var i=0;i < radioClass.length;i++){
            if(radioClass.eq(i).attr('checked')) attrIds+=radioClass.eq(i).val()+',';
     }   */
	 
	  if(fuhao){
		   if(nums > 0 && fuhao=='+'){
			   if(parseInt($("input[name='"+inputName+"']").val()) >=nums){
				   return;
			   }
		   }
		   var numRe=/^\d+$/;
		   var _curNum = parseInt($("input[name='"+inputName+"']").val());
		   if(!numRe.test(_curNum)){ $("input[name='"+inputName+"']").val(2);return;}
		   if(fuhao=="+"){
			  $("input[name='"+inputName+"']").val(_curNum +1);
		   }else if(fuhao=="-"){
			  if(_curNum >1){
				   $("input[name='"+inputName+"']").val(_curNum -1);
			  }
		  }	  
	  }
	  var num=0;
	  if(inputName){
		  num=$("input[name='"+inputName+"']").val();
	  }else{
		  num=1;
	  }
	  
	  //检查库存
      if(retrievalUrl){
		  if(!isNaN(num) && num >0){
			  $.ajax({
				 type:'get',
				 url:retrievalUrl+'&num='+num,
				 success:function(response,status,xhr){
				
				  }
			   });				  
		  }
	  }

	  //添加或修改购物车
      if(cartUrl){
		  if(!isNaN(num) && num >0){
			  $.ajax({
				 type:'get',
				 url:cartUrl+'&num='+num+logistics+zhuhe_name+cityName+'&attr_ids='+attrIds,
				 dataType:'json',
				 success:function(response,status,xhr){
					 if(response[0][1]){
					     if(response[0][3]) window.location.href=response[0][3];
					 }else{
					     myAlert('错误提示',response[0][2],'',response[0][3],response[0][6],3);
					 }
				  }
			   });				  
		  }
	  }	
}