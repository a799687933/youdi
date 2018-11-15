var __root__="";//根目录
var Error='错误提示';
if(window.Error) Error=window.Error;
var GoodsIsNum='商品数量必须是数字';
if(window.GoodsIsNum) GoodsIsNum=window.GoodsIsNum;
var NumMix1='商品数量不能小于1';
if(window.NumMix1) NumMix1=window.NumMix1;
var SelectGoods='请选择要删除的记录';
if(window.SelectGoods) SelectGoods=window.SelectGoods;
var ConfirmationPrompt='确定提示';
if(window.ConfirmationPrompt) ConfirmationPrompt=window.ConfirmationPrompt;
var isDelete='操作不可恢复确定执行本次操作？';
if(window.isDelete) isDelete=window.isDelete;
var Settlement='结算';
if(window.Settlement) Settlement=window.Settlement;
var CartSubmitGoods='请选择要提交的商品';
if(window.CartSubmitGoods) CartSubmitGoods=window.CartSubmitGoods;
/*计算选中商品种类个数*/
$(function(){
      $('input[name="cart_id[]"]').click(function(){
	       counts();
	  });		
});

/**全选或单选并计算选中商品种类个数
 * _this   对像
 * types  是否全选
 * fn        回调函数
 * */
function selectCheckbox(_this,types,fn){
    if(!arguments[2]) fn = "";
     if(types=='all'){
		 var box=$('#cart-form').find('input[type="checkbox"]');
		 if($(_this).attr('checked')){
			 for(var i=0;i < box.length;i++){ 
				 box.eq(i).attr('checked',true);
			 }
			 $('input[name="all"]').attr('checked',true);
		 }else{
			 for(var i=0;i < box.length;i++){
				 box.eq(i).attr('checked',false);
			 }		
			 $('input[name="all"]').attr('checked',false);
		 }
	 }
	 counts();
	 if (fn) fn(_this,types);
}

/**
*修改购物车数量
*fuhao  +或-
*inputName  数量input 名称
*cartId     修改的ID
* isMobile  是否是手机板访问此参数传手机模块常量
* updateNumber('+','goods_num',25,'<php>echo APP_MOBILE;</php>');
*/
function updateNumber(fuhao,inputName,cartId,isMobile){
      if(!arguments[3]) isMobile='';
	   var numRe=/^\d+$/;
	   var _curNum = parseInt($("input[name='"+inputName+"']").val());
	   if(!numRe.test(_curNum)){ $("input[name='"+inputName+"']").val(2);return;}
	   if(fuhao=="+"){
		  $("input[name='"+inputName+"']").val(_curNum +1);
	   }else if(fuhao=="-"){
		  if(_curNum >1){
		       $("input[name='"+inputName+"']").val(_curNum -1);
		  }
		  if(parseInt($("input[name='"+inputName+"']").attr('temp'))==1) return;
	  }
	 ajaxUpdate(cartId,inputName,isMobile);
}

/**
*修改购物车数量提交
*cartId 修改ID
*inputNum 数量input 名称
*isMobile 手机板模块调用
*/
function ajaxUpdate(cartId,inputNum,isMobile){
     if(!arguments[2]) {
         isMobile = '';
     }else{
         isMobile=isMobile+'/';
     }
	  var inputNode=$('input[name="'+inputNum+'"]');
      var m=parseInt(inputNode.val());
	  if(isNaN(m)) {
	     myAlert(Error,GoodsIsNum,'','');
	     return;
	  }
	  if(m < 1) {
	     myAlert(Error,NumMix1,'','');
	     return;
	  }	  
      var url=__root__+isMobile+"Cart/updateCart/cartId/"+cartId+'/num/'+m;
	  $.ajax({
		 type:'post',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
             if(response[0][1]){
                 if(response[0][3]) window.location.href=response[0][3];
             }else{
				 myAlert(Error,response[0][2],'',response[0][3],response[0][6],3);
				 inputNode.val(inputNode.attr('temp'));
             }		     
		  }
	   });	
}

/*
*删除商品
*ids  如果有值删除单个商品；否则全部删除
*isMoblie  是否是手机板使用
*/
function toDelete(ids,isMoblie){
   var __GERTC__=''; 
   if(!arguments[1]) {
       isMoblie = '';
   }else{
      __GERTC__=   isMoblie+'/'; 
   }
   var str='';   
   if(ids){
	   str=ids;
   }else{
	   var inputs=$('input[name="cart_id[]"]');
	   for(var i=0;i < inputs.length;i++){
		   if(inputs.eq(i).attr('checked')){
			   str+=inputs.eq(i).val()+',';
		   }
	   }	   
   }
  if(!str){
	  myAlert(Error,SelectGoods,'','',isMoblie,3);
	  return;
   }
   var url=__root__+__GERTC__+"Cart/delCart/ids/"+str;
   myAlert(ConfirmationPrompt,isDelete,url,'',isMoblie,2);
}

/***
*counts()
*计算选择提交商品种类个数
*显示种类的节点 .counts
*/
function counts(){
    var inputs=$('input[name="cart_id[]"]');
	var j=0;
	for(var i=0;i < inputs.length;i++){
		  if(inputs.eq(i).attr('checked')) j++;
	}
	$('.counts').text(j);
	$('.counts').val(Settlement+'('+j+')');
}

/**
*提交表单
* url    
*/
function sendForm(isMoblie){
     if(!arguments[0]) {
         isMoblie = '';
     }else{
         isMoblie=isMoblie+'/';
     }
     
   var cleckAll=$('input[name="cart_id[]"]');
   var str='';
   for(var i=0;i<cleckAll.length;i++){
	   if(cleckAll.eq(i).attr('checked')) str+=cleckAll.eq(i).val()+',';
   }
   if(!str){
	   myAlert(Error,CartSubmitGoods,'','',isMoblie,3);
	   return false;
   }
   var url=__root__+isMoblie+"CheckOut/index?cart_id="+str.substring(0,str.length-1);
   window.location.href=url;
}