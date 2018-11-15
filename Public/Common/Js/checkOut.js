
var options={
	//公共
	'ROOT':'', //根目录
	'IsMobile':'', //是否手机模块;如果手机模块直接使用 '<php>echo APP_MOBILE;</php>';
	'CatrIds':'', //购物车ID集合
	//红包
	'redAmount':'#red-amount', //显示现金卷金额节点ID    
	//积分对换
    'integralAmount':0,  //订单可换积分
	'userIntergal':0,  //会员可用积分  
	'showIntegralNode':'#integral-amount', //显示已使用积分对换金额节点
	'CUR_REP':0,  //PHP货币汇率
	'integralFormula':0.001, //积分换算位
	//物流
	'logisticsSelectName':'logistics_name', //物流列表select节点名称
	'totalLogisticsNode':'#total_logistics', //显示物流费节点
    //供应商ID
    'goodsSupplier':'goodsSupplier',
	//总费用
	'orderAmount':'#order-amount', //显示应付总金额节点
	
	//提交
	'addressInputName':'selectAdderss', //地址列表input type="radio" name="selectAdderss" 节点名称
	'isInv':'is_inv', //发票列表 input type="radio" name="is_inv" 节点名称
	'payType':'pay_type',//支付方式列表 input type="radio" name="is_inv" 节点名称
	'integral':'integral', //用户输入的积分input 节点名称
	'redSelectName':'member_bonus_id', //红包select节点名称
	'promptDelivery':'#prompt-delivery', //购物满即送金额节点
	'orderMessage':'order_message',//订单留言input 节点名称
	'tokenInputName':'', //令牌input节点名称
	'Error':'错误提示',  //错误提示标题
	'Pctrgapca':'请选择收货地址，如还没有收货地址请新建一个',
	'PleaseOrdData':'请先保存普通发票资料',
	'PleaseVAT':'请先保存增值税发票资料',
	'botBg':'#ea9901', //提交按钮背景色
	'botColor':'#fff', //提交按钮字体色
	'disabBotBg':'#eee', //提交中的背景色
	'disabBotColor':'#999',  //提交中的字体色	
	'NoLogisticsCity':'你的地址所在区域暂无物流信息或免运费商品' //无物流运费时提示信息
};

/*提交*/
function sendForm(_this){
    var config=true;
    //订单ID集
   var cartId=options.CatrIds;
   //地址ID
   var adderss=$('input[name="'+options.addressInputName+'"]');
   var selectAdderss=0;
   for(var i=0;i < adderss.length;i++){
      if(adderss.eq(i).attr('checked')){
          if(adderss.eq(i).val()){
              selectAdderss=parseInt(adderss.eq(i).val());
              break;
          }
      }
   }
   if(selectAdderss==0){
       //地址ID可能是select标签
       var adderss=$('select[name="'+options.addressInputName+'"]');   
       selectAdderss=adderss.val();
       if(!selectAdderss){
           myAlert(options.Error,options.Pctrgapca,'','',options.IsMobile,3);
           return;           
       }    
   }
   //检查发票
   var usInv=$('input[name="'+options.isInv+'"]');
   var isInv=0;
   if(usInv.length > 0){      
       for(var j=0;j < usInv.length;j++){
           if(usInv.eq(j).attr('checked')){
               isInv=parseInt(usInv.eq(j).val());
               break;
           }
       }       
   }else{
       isInv=parseInt($('select[name="'+options.isInv+'"]').val());
   }

   
   if(isInv==1){ //普通发票
       var toIsInvUrl=options.ROOT+options.IsMobile+"/Member/isInv?data=member_pu_tong_inv";
       var result=ajaxToDo(toIsInvUrl,'text');
       if(parseInt(result)==0){
          myAlert(options.Error,options.PleaseOrdData,'','',options.IsMobile,3);
          config=false;
       }
   }else if(isInv==2){ //增值税发票
       var toIsInvUrl=options.ROOT+options.IsMobile+"/Member/isInv?data=member_zeng_zhi_inv";
       var result=ajaxToDo(toIsInvUrl,'text');
       if(parseInt(result)==0){
          myAlert(options.Error,options.PleaseVAT,'','',options.IsMobile,3);
          config=false;
       }       
   }
   //支付方式
   var pay=$('input[name="'+options.payType+'"]');
   var payType=0;
   if(pay.length > 0){
       for(var i=0;i < pay.length;i++){
           if(pay.eq(i).attr('checked')){
               payType=pay.eq(i).val();
               break;
           }
       }       
   }else{
       payType=parseInt($('select[name="'+options.payType+'"]').val());
   }
   
   //检查积分
   var integral = parseInt($('input[name="'+options.integral+'"]').val()) > 0 ? $('input[name="'+options.integral+'"]').val() : 0;
   
   //检查红包
   var memberBonusId=parseInt($('select[name="'+options.redSelectName+'"]').val()) > 0 ? $('select[name="'+options.redSelectName+'"]').val() : 0;
   
   //检查选择的物流公司
   var logisticsId=parseInt($('select[name="'+options.logisticsSelectName+'"]').val()) > 0 ? $('select[name="'+options.logisticsSelectName+'"]').val() : 0;

   //检查配送点
   var supplierId=parseInt($('select[name="'+options.goodsSupplier+'"]').val()) > 0 ? $('select[name="'+options.goodsSupplier+'"]').val() : 0;
      
   //订单留言
   var orderMessage = $('input[name="'+options.orderMessage+'"]').val() ? $('input[name="'+options.orderMessage+'"]').val() : '';
   var parameters="cartId="+cartId+"&selectAdderss="+selectAdderss+"&isInv="+isInv+"&payType="+payType+"&integral="+integral;
   parameters+="&memberBonusId="+memberBonusId+"&orderMessage="+orderMessage+"&logisticsId="+logisticsId;
   parameters+="&supplierId="+supplierId;
   var url=options.ROOT+options.IsMobile+"/CheckOut/addOrder?"+parameters+'&'+options.tokenInputName+'='+$('input[name="'+options.tokenInputName+'"]').val();
   //alert(url);return;
   if(config){
	   if($(_this).prop("tagName")=='A') $(_this).attr('onclick','');
       $(_this).attr('disabled',true);
       $(_this).css('background',options.disabBotBg+" url('"+$(_this).attr('img')+"') no-repeat 3% center").css('cursor','auto');
       $(_this).css('color',options.disabBotColor);
       var json=ajaxToDo(url,'json');
       if(json[0][1]==true){
           if(json[0][3]) window.location.href=json[0][3];
       }else{
           if(json[0][3]){
              myAlert('','','',json[0][3],json[0][6]); 
           }else{
              myAlert(options.Error,json[0][2],'',json[0][3],json[0][6]); 
           }          
		   if($(_this).prop("tagName")=='A') $(_this).attr('onclick','sendForm(this)');
           $(_this).attr('disabled',false);
           $(_this).css('background',options.botBg);
           $(_this).css('color',options.botColor).css('cursor','pointer');    
       }
   }   
}

/*使用现金卷*/
function checkDiscount(_this){
    var redPrice=parseFloat($(_this).children("option:selected").attr('price')); //现金卷
    if(redPrice > 0){
        $(options.redAmount).text(fmoney(redPrice));//显示已使用现金卷金额    
    }else{
        $(options.redAmount).text('0.00');//显示已使用现金卷金额
    }
    totalAmount();
}

/*使用积分*/
function usingIntegral(_this){
    var integralAmount=parseInt(options.integralAmount); //订单可换积分
    var userIntegral=parseInt(options.userIntergal); //会员可用积分  
    var integral=parseInt($(_this).val()); //用户输入的积分数
    if(isNaN(integral) || integral < 0){
        $(options.showIntegralNode).text('0.00');
        $(_this).val('');
        totalAmount();
        return;
    }   
    //大于会员可用积分
    if(integral > userIntegral) integral=userIntegral;  
    //大于订单可使用的积分
    if(integral > integralAmount) integral=integralAmount;
    $(_this).val(integral); 
    integralPrice=fmoney(integral * parseFloat(options.integralFormula)); //积分换算
    integralPrice=conversio(integralPrice,options.CUR_REP);//自动转换货币 
    $(options.showIntegralNode).text(integralPrice);
    totalAmount();
    //$('#integral-div').show();  
}

/*选择物流*/
function logistics(_this){
    var price=parseFloat($(_this).children('option:selected').attr('price'));
    if(price > 0){
         $(options.totalLogisticsNode).text(fmoney(price));
         totalAmount();
    }
}

/*选择地址自动获取物流费*/
function selectAddress(_this,cityId,fn){
   if(!arguments[2]) fn = ""; 
   var url=options.ROOT+options.IsMobile+"/CheckOut/showLogistics?ids="+options.CatrIds+"&city_id="+cityId;
   if($(_this).val() && cityId > 0){
         $.getJSON(url,function(response,status,xhr){
             if(response.select_str) {
				 $('select[name="'+options.logisticsSelectName+'"]').html(response.select_str);
			 }else{
				 $('select[name="'+options.logisticsSelectName+'"]').html('<option>'+options.NoLogisticsCity+'</option>');
			 }          
             if(response.total_logistics) {
				 $(options.totalLogisticsNode).text(response.total_logistics);
			 }else{
				 $(options.totalLogisticsNode).text('0.00');
			 } 
             totalAmount();
         });      
   }
   if(fn) fn();
}

/*计算总价格*/
function totalAmount(){
    var orderPrice=parseFloat($(options.orderAmount).attr('total')); //商品总金额
    var integralPrice=$(options.showIntegralNode).text() ? parseFloat($(options.showIntegralNode).text()) : 0; //积分金额
    var logisticsPrice=$(options.totalLogisticsNode).length > 0 ? parseFloat($(options.totalLogisticsNode).text()) : 0; //物流费
    var redPrice=$(options.redAmount).length > 0 ? parseFloat($(options.redAmount).text()) : 0; //现金卷 
    var promptDelivery=$(options.promptDelivery).length > 0 ? parseFloat($(options.promptDelivery).text()) : 0; //购物满即送金额
    if(isNaN(orderPrice) || isNaN(integralPrice)  || isNaN(logisticsPrice) || isNaN(redPrice) || isNaN(promptDelivery)) return;
    if((orderPrice < 0) || (integralPrice < 0)  || (logisticsPrice < 0) || (redPrice < 0) || (promptDelivery < 0)) return;
    $(options.orderAmount).text(fmoney(orderPrice - integralPrice + logisticsPrice - redPrice - promptDelivery)); //显示订单总金额      
}

