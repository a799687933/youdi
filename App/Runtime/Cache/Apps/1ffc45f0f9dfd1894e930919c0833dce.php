<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
var recommend='<?php echo ($goods["recommend"]); ?>' ? '<?php echo ($goods["recommend"]); ?>' : 0;
$(function(){

    //计算市场价格返回本商城价格
    $('.price-click2').click(function(){
        var market_price=parseFloat($('#market_price1').val());        
        if(market_price=='' || isNaN(market_price)) {alert('请输入市场价格');return;}
        var retail_price=market_price * parseFloat("<?php echo C('MARKETSRICERATIO');?>");
       $('#retail_price1').val(retail_price);
       $('#retail_price1').focus();
    });
    
    //打开促销    
    if('<?php echo ($promote["no_ffo"]); ?>'){ //修改商品状态如果有促销信息默认可编辑状态
        $('.promotion_price').attr('readonly',false);
        $('.promotion_price').css('background','#fff');
        $('.date').attr('disabled',false);
        $('.date').css('background','#fff');
		$('.numbers').attr('readonly',false).css('background','#fff');
		$('#goodsFileToUpload1').attr('disabled',false);
		$('#goods_imgloads1').show();
    }else{
        $('#goodsFileToUpload1').attr('disabled',true);
        $('#goods_imgloads1').hide();       
    }
	
    //点击事件
    $('.promotion').click(function(){
       if($(this).attr('checked')){          
          $('.promotion_price').attr('readonly',false);
          $('.promotion_price').css('background','#fff');
		  $('.numbers').attr('readonly',false).css('background','#fff');
          $('.date').attr('disabled',false);
          $('.date').css('background','#fff');
          $('#goodsFileToUpload1').attr('disabled',false);
          $('#goods_imgloads1').show();
      }else{
          $('.promotion_price').attr('readonly','readonly').val('');
          $('.promotion_price').css('background','#eee');
          $('.date').attr('disabled','disabled');
          $('.date').css('background','#eee');
		  $('.numbers').attr('disabled','disabled').css('background','#eee').val('');
		  $('#goodsFileToUpload1').attr('disabled',true);
		  $('#goods_imgloads1').hide();
      }
   });
   
   //选择商品属性
   $('.goods-attr').change(function(){
        var openUrl="<?php echo U(APP_APPS.'/Goods/goodsSendForm','','');?>"+"/get_attr/ok/goods_attr_type_id/"+$(this).val();
        var response=callAjaxDo(openUrl+'/type/ok','text');//获取数据    
        $('.atrr-tr').html(response);  
   });  
   
   $('input[name="attr_value_list[]"]').live('click',function(){
		 var _this=$(this);		
		 var prev=_this.prev();
		 if(_this.attr('checked')){
			 prev.val(_this.val());
		 }else{
			 prev.val('');
		 }   
   });
   //checkedx商品属性付值到隐藏域
  /* $('input[name="attr_value_list[]"').live('click',function(){
		 var _this=$(this);		
		 var prev=_this.prev();
		 if(_this.attr('checked')){
			 prev.val(_this.val());
		 }else{
			 prev.val('');
		 }
	});   */
     
   //商品关联检索
    $('.pro-ajax').click(function(){
         var url="<?php echo U(APP_APPS.'/Search/retrieval','','');?>";
         retrieval(url,'s_name','cart-ajax','bra-ajax',1,'selectLeftProList');
    }); 
  
   //商品关联检索
    $('.acc-ajax').click(function(){
         var url="<?php echo U(APP_APPS.'/Search/retrieval','','');?>";
         retrieval(url,'acc_name','acc-cart-ajax','acc-bra-ajax',0,'selectLeftacc');
    }); 
     
    //指定会员价格
   $('#retail_price1').blur(function(){
         var prev=0;
         if(!isNaN($(this).val())){
            var data=<?php if($json_date): echo ($json_date); else: ?>{}<?php endif; ?>;
            var len=$('.grade_price').length;
                for(var i=0;i<len;i++){
                    prev=(data[i].discount/100)*$(this).val();
                    $($('.grade_price')[i]).val(prev);
                }
        }else{
            alert('本店价格必须是数字！');
        }      
   });
   
   //修改商品时是否已有推荐
   if(recommend==1){
       $('.temp-name-s,.open-temps').show();
   }
   
   //打开分类推荐
   $('input[name="recommend"]').click(function(){
         if($(this).val()==1){
		     $('.temp-name-s').show();
		 }else{
		     $('.temp-name-s').hide();
		 }
   });   
   
});

//复制节点
function addNode(node){
      $(function(){
        var tr=$(node).parent().parent();     
        var str=$(tr).clone(true);     
        str='<tr>'+$(str).html()+'</tr>';
        str=str.replace('addNode', 'delNode');
        str=str.replace('[+]', '[-]');
        var s=/data\=\"1\" value\=\"[0-9.]+\"/ ;  
        str=str.replace(s,'value="0"');
       $(tr).after(str);    
      });
}

//删除节点
function delNode(_this){
      $(function(){
        $(_this).parent().parent().remove();         
      });
} 


 //AJAX获取区域
function getPlace(_ths,node){
   if(!arguments[1]) node = "";	
   var urls=$(_ths).attr('to-url');
    if($(_ths).val() > 0){
      $.ajax({
         type:'post',
         dataType: "json",
         url:urls+"/region_id/"+$(_ths).val()+'/city/ajax',
         success:function(response,status,xhr){
            var str='<option value="">请选择</option>';
            for(var i in response){
                str+='<option value="'+ response[i].region_id +'">'+ response[i].region_name +'</option>';
            }
            if($(_ths).next('select').length >0){
                $(_ths).next().html(str);
            }    
			if(node){
			      var htmlString=$(_ths).children('option:selected').text();
				  $(node).val(htmlString);			
			}
          }
       });    
   }                              
}  

//检索下级分类
function whereClass(key,id){
	var url="<?php echo U(APP_APPS.'/Goods/goodsSendForm','','');?>/id/"+ id +'/key/'+ key +'/cate/ajax';
	var tr=$('.show-trs');
	for(var i=tr.length;i > 0;i--){
		tr.eq(i).remove();
		if(i<=key) break;
	}
	$.ajax({
		 type:'get',
		 url:url,
		 dataType:'json',
		 success:function(response,status,xhr){
			//分类
		    if(response.cates) tr.eq(key-1).after(response.cates);
			//分类品牌
			if(response.b){
				if(response.bands) {
					$('#bands-td').html(response.bands); 
					$('#bands').show();
				}else{
					$('#bands-td').html(''); 
					$('#bands').hide();			
				}
			}
		  }
   });		 
}

///获取字母品牌
function getBrandBAC(abc,_this){
      $('.boands').attr('class','boands');
	  $(_this).attr('class','boand-on boands');
	  var url="<?php echo U(APP_APPS.'/Goods/goodsSendForm','','');?>/bac/"+abc+'/band_bac/ajax';
	  var result=callAjaxDo(url,'text');
      $('#brand_data').html(result);  
}

//删除属性图片
function delAttrImg(num){
   var inputValId=$('#val_'+num);
   var img=$('#imgs_'+num);
   inputValId.val('');
   img.attr('src',img.attr('default-img'));
}
</script>
<script type="text/javascript" src="__J_UI__/js/DialogEditorSize.js"></script>
<style type="text/css">
.temp-name-s,.open-temps{
    display:none;
 }
.boand-on{
	color:#f60;
	} 
</style>
        <div class="pageContent">
        <div class="tabs" currentindex="1" eventtype="click">
            <div class="tabsHeader">
                <div class="tabsHeaderContent">
                    <ul>    
                        <li></li> 
                        <li ><a href="javascript:;"><span>通用信息</span></a></li>
                        <li ><a href="javascript:;"><span>其它信息</span></a></li>                            
                        <!--<li ><a href="javascript:;"><span>产品详情</span></a></li>-->
                         <?php if(C('IS_GOODS_PARAMETERS')): ?><li ><a href="javascript:;"><span>规格参数</span></a></li><?php endif; ?>
                         <?php if(C(IS_GOODS_AFTER_SALES)): ?><li ><a href="javascript:;"><span>售后保障</span></a></li><?php endif; ?>
                         <li ><a href="javascript:;"><span>商品相册</span></a></li>
                         <?php if(C('IS_GOODS_ATTRIBUTE')): ?><li ><a href="javascript:;"><span>商品属性</span></a></li><?php endif; ?>
                         <?php if(C('IS_GOODS_LINK')): ?><li ><a href="javascript:;"><span>关联商品</span></a></li><?php endif; ?>
                        <?php if(C('IS_GOODS_ACCESSORIES')): ?><li ><a href="javascript:;"><span>关联配件</span></a></li><?php endif; ?>
                        <?php if(C('IS_GOODS_LINK_ARTICLE')): ?><li ><a href="javascript:;"><span>关联文章</span></a></li><?php endif; ?>  
                        <?php if(C('IS_GOODS_ZHUHE')): ?><li ><a href="javascript:;"><span>组合购买</span></a></li><?php endif; ?>         
                    </ul>
                </div>
            </div>

                <form method="post" action="<?php echo U(APP_APPS.'/Goods/goodsSendForm','','');?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
                    <?php if(!$_GET['copy']): ?><input type="hidden" name="id" value="<?php echo ($goods["id"]); ?>" /><?php endif; ?>
                    <input type="hidden" name="inventory_id" value="<?php echo ($_REQUEST['inventory_id']); ?>"/>
                    <input type="hidden" name="storage_id" value="<?php echo ($storage_id); ?>"/>
                    <input type="hidden" name="goods_num" value="<?php echo ($goods["goods_num"]); ?>" /><!--商品货号-->
                    <input type="hidden" name="type" value="<?php echo ($_REQUEST['type']); ?>" />
					<input type="hidden" name="action" value="<?php echo ($_REQUEST['action']); ?>" /><!--返回的跳转方法名称-->

                <div class="tabsContent" style="background:#fff;" layoutH="70">
                    <div inited="1000" style="display: block;"></div><!--占位-->    
                    
                    <div inited="1000" style="display: block;"><!--通用资料内容-->
               
                        <table cellpadding="0" cellspacing="0" class="mytable" style="width:100%;margin:0 auto;">
                          
                          <?php if($selectCate): if(is_array($selectCate)): foreach($selectCate as $keyis=>$sc): ?><tr class="show-trs">
                                     <td align="right" style="text-align:right;"><?php echo ($keyis); ?>级分类：</td>
                                     <td align="left" id="radio-content<?php echo ($keyis); ?>">
                                       <?php if(is_array($sc['lists'])): foreach($sc['lists'] as $key=>$C): ?><label>
                                          <input type="radio" 
                                                 value="<?php echo ($C["id"]); ?>" 
                                                 name="<?php if($keyis == $countCate): ?>goods_category_id<?php else: ?>category_<?php echo ($keyis); endif; ?>"
                                                 <?php if($C['selected']): ?>checked="echecked"<?php endif; ?>  
												 onclick="whereClass(<?php echo ($keyis); ?>,<?php echo ($C["id"]); ?>)"
												 />
												 <?php echo ($C["name"]); ?>
                                          </label><?php endforeach; endif; ?>                 
                                     </td>
                                 </tr><?php endforeach; endif; ?>                  
                          <?php else: ?>
                            <tr class="show-trs">
								<td  style="text-align:right;">1级分类：</td>
								<td align="left" id="radio-content1">
                                       <?php if(is_array($oenCate)): foreach($oenCate as $key=>$C): ?><label>
                                          <input type="radio" 
                                                 value="<?php echo ($C["id"]); ?>" 
                                                 <?php if($C['is_child']){ ?>
                                                     name="category_1"                                                                                                      
                                                 <?php }else{ ?>
                                                     name="goods_category_id"    
                                                 <?php } ?>		
                                                 onclick="whereClass(1,<?php echo ($C["id"]); ?>)"										 
												 />
												 <?php echo ($C['name']); ?>
                                          </label><?php endforeach; endif; ?>        										  				  							  
                                 </td>
							 </tr><?php endif; ?>   
                           <tr><td style="height:10px;"></td></tr>
                                     
							                 
                            <!--供货商
                              <tr><td style="text-align:right;" width="10%">供货商：</td><td>  
                                   <select class="combox" name="goods_supplier">
                                      <option value="0">选择供货商</option>
                                      <?php if(is_array($supplier)): foreach($supplier as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>" <?php if($goods['goods_supplier'] == $C['id']): ?>selected="selected"<?php endif; ?>><?php echo ($C["html"]); echo ($C["name"]); ?></option><?php endforeach; endif; ?>  
                                   </select>  
                            </td></tr>
                            <!--供货商结束-->
                            
                           
							<?php if(C('IS_GOODS_BRAND')): ?><tr id="bands" style="display:<?php if($bands): ?>table-row<?php else: ?>none<?php endif; ?>;">
                                 <td  style="text-align:right;">商品品牌：</td>
                                 <td id="bands-td"><?php echo ($bands); ?></td>
                           </tr>
                           <tr><td colspan="2" style="height:10px;"></td></tr><?php endif; ?>
                         
						  
						   
						 <?php if(C('IS_GOODS_CITY_SELECT')): ?><tr ><td  style="text-align:right;">选择区域：</td><td>
                            <select class=" " name="city" id="city">
                                     <option value="0">-选择城市-</option>
                                <?php if(is_array($citys)): foreach($citys as $key=>$c): if($c['region_type'] == 1): ?><optgroup label="<?php echo ($c["region_name"]); ?>"><?php echo ($c["region_name"]); ?></optgroup>                                        
                                      <?php else: ?>
                                         <option value="<?php echo ($c['region_id']); ?>" <?php if($goods['city'] == $c['region_id']): ?>selected="selected"<?php endif; ?>><?php echo ($c["html"]); echo ($c["region_name"]); ?></option><?php endif; endforeach; endif; ?>
                              </select>       
                            </td></tr><?php endif; ?>
                            
                         
                         <tr>
                              <td  style="text-align:right;width:120px;" >上传商品图片1：</td>
                              <td>
                                  <input type="hidden" name="goods_thumb"  value="<?php echo ($goods["goods_thumb"]); ?>" id="goods_thumb"/>
                                 <input name="file2" type="file" id="goodsFileToUpload"  
                                 onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'ok','remove'=>'ok','width'=>300,'height'=>400));?>',
                                 'goodsFileToUpload','goods_imgloads_show','goods_thumb','','ac-show','__ROOT__','goods_imgloads');" style="width:70px;"/> 
                                 图片规格：300 * 400
                        </td>
                      </tr>
                        
                      <tr style="<?php if(!$goods['goods_thumb']): ?>display:none;<?php endif; ?> " id="goods_imgloads">
                            <td  style="text-align:right;"></td>
                          <td style="margin:10px 0;">
                               <img src="__ROOT__/<?php echo ($goods["goods_thumb"]); ?>" 
                               id="goods_imgloads_show" width="100" height="" style="display:inline-block;position:relative;top:5px;bottom:" class="ac-show"/>
                         </td>
                         </tr>
                          <tr><td colspan="2" style="height:10px;"></td></tr>

                         <tr>
                              <td  style="text-align:right;" >上传商品图片2：</td>
                              <td>
                                  <input type="hidden" name="goods_thumb2"  value="<?php echo ($goods["goods_thumb2"]); ?>" id="goods_thumb2"/>
                                 <input name="file3" type="file" id="goodsFileToUpload2"  
                                 onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'ok','remove'=>'ok','width'=>300,'height'=>400));?>',
                                 'goodsFileToUpload2','goods_imgloads_show2','goods_thumb2','','ac-show2','__ROOT__','goods_imgloads2');" style="width:70px;"/> 
                                 图片规格：300 * 400
                        </td>
                      </tr>
                        
                      <tr style="<?php if(!$goods['goods_thumb2']): ?>display:none;<?php endif; ?> " id="goods_imgloads2">
                            <td  style="text-align:right;"></td>
                          <td style="margin:10px 0;">
                               <img src="__ROOT__/<?php echo ($goods["goods_thumb2"]); ?>" 
                               id="goods_imgloads_show2" width="100" height="" style="display:inline-block;position:relative;top:5px;bottom:" class="ac-show2"/>
                         </td>
                         </tr>
                          <tr><td colspan="2" style="height:10px;"></td></tr>
                                                                               
                           <?php if(is_array($goodsNames)): foreach($goodsNames as $key=>$t): ?><tr>
                                 <td  style="text-align:right;">(<?php echo ($t['info']); ?>)商品名称：</td>
                                 <td>
                                     <input type="text" name="<?php echo ($t['field']); ?>" 
                                                 value="<?php echo ($goods[$t['field']]); ?>" maxlength="250" 
                                     class="required" size="50" style="margin:3px 0;"/>
                                 </td>
                           </tr><?php endforeach; endif; ?> 
                            
                        <tr>
                             <td  style="text-align:right;">市场价格：</td>
                             <td>
                                 <input type="text" name="goods.market_price" 
                                             value="<?php echo ($goods["goods_market_price"]); ?>" maxlength="250" 
                                             id="market_price1" class="required number" size="50" 
                                 style="margin:3px 0;"/>
                              </td>
                        </tr>

                             <tr>
                                  <td  style="text-align:right;">本店售价：</td>
                                  <td>
                                        <input type="text" name="goods.retail_price" 
                                                   value="<?php echo ($goods["goods_retail_price"]); ?>" 
                                                   maxlength="250" 
                                                   class="required number" id="retail_price1" 
                                         size="50" style="margin:3px 0;float:left;margin-right: 3px;"/>
                                         <a class="button price-click2" href="javascript:;" >
                                         <span>本商城零售价格(<?php echo C('MARKETSRICERATIO')*100;?>%)</span></a>
                                  </td>
                           </tr>
                           
                            
                            <tr>
                                 <td  style="text-align:right;">商品库存数量：</td>
                                 <td>
                                        <input type="text"  name="stock" class="digits" 
                                        size="50" value="<?php if($goods['stock']): echo ($goods['stock']); else: ?>0<?php endif; ?>" 
                                        style="margin:3px 0;"> 
                                 </td>
                            </tr>     
                                                           
                            
                            
							<?php if(C('IS_GOODS_MEMBER_PRICE')): ?><tr>
                                    <td  style="text-align:right;">会员价格：</td>
                                    <td>
                                       <?php if(is_array($member)): foreach($member as $k=>$M): ?><input type="hidden" value="<?php echo ($M["id"]); ?>" name="member_grade_id[]" />
                                       <?php echo ($M["grade_name"]); ?>
                                       <input type="text" name="grade_price[]" 
                                       maxlength="250" class="number grade_price" size="7" value="<?php echo ($grade[$k]['member_price']); ?>" style="margin:3px 0;"/>&nbsp;<?php endforeach; endif; ?> 
                                       <br/>
                                       <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                       <span style="color:#666;">
                                             默认会员价格按会员等级折扣率计算。你也可以为每个等级指定一个固定价格,如果设置为0即没有会员等级价格。
                                      </span>   
								</td>
                               </tr><?php endif; ?>
                           
                           
                           
                           <?php if(C('IS_GOODS_VOLUME_PRICE')): if(is_array($volume)): $i = 0; $__LIST__ = $volume;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                     <td style="text-align:right;"><a href="javascript:;" onclick="delNode('preferes');">[-]</a>商品优惠价格：</td>
                                     <td>
                                    优惠数量
                                    <input type="text" name="discount_sum[]" 
                                    maxlength="250" class="number textInput" size="10" value="<?php echo ($v["discount_sum"]); ?>" style="margin:3px 0;">
                                     优惠价格
                                     <input type="text" name="preferential[]" 
                                     maxlength="250" class="number textInput" size="10" value="<?php echo ($v["preferential"]); ?>" style="margin:3px 0;">
                                     <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                     <span style="color:#666;">购买数量达到优惠数量时享受的优惠价格</span>                       
                               </td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            <tr class="preferes"><td  style="text-align:right;"><a href="javascript:;" onclick="addNode(this);">[+]</a>商品优惠价格：</td><td>
                                优惠数量<input type="text" name="discount_sum[]" maxlength="250" class="number" size="10" style="margin:3px 0;"/>
                                优惠价格<input type="text" name="preferential[]" maxlength="250" class="number" size="10" style="margin:3px 0;"/>
                                 <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                 <span style="color:#666;">享受价格</span>                       
                           </td></tr><?php endif; ?>  
                         
                          
                         
                           <?php if(C('IS_PAY_POINTS')): ?><tr>
                                   <td  style="text-align:right;">赠送消费积分数：</td>
                                   <td>
                                     <input type="text" name="pay_points" 
                                     maxlength="250" class="required" size="50" 
                                     value="<?php if($goods['pay_points'] AND $goods['pay_points'] >= 0): echo ($goods['pay_points']); else: ?>-1<?php endif; ?>" style="margin:3px 0;"/>
                                     <br/><img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                     <span style="color:#666;">购买该商品时赠送消费积分数,-1表示按商品价格赠送,0表示不赠送消费积分;大于0表示按输入值赠送</span>
                                  </td>
                            </tr>
                            <tr><td colspan="2" style="height:10px;"></td></tr><?php endif; ?>
                           
                            
                          
                          <?php if(C('IS_RANK_POINTS')): ?><tr>
                                  <td  style="text-align:right;">赠送等级积分数：</td>
                                  <td>
                                     <input type="text" name="rank_points" 
                                     maxlength="250" class="" size="50"  
                                     value="<?php if($goods['rank_points'] AND $goods['rank_points'] >= 0): echo ($goods['rank_points']); else: ?>-1<?php endif; ?>" style="margin:3px 0;"/>
                                     <br/><img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                     <span style="color:#666;">购买该商品时赠送等级积分数,-1表示按商品价格赠送,0表示不赠送等级积分;大于0表示按输入值赠送</span>
                                  </td>
                           </tr><?php endif; ?>         
                          
                           
                           
                           <?php if(C('IS_PAY_POINTS')): ?><tr>
                                    <td  style="text-align:right;">积分购买金额：</td>
                                    <td>
                                         <input type="text" name="integral_amount" 
                                         maxlength="250" class="required digits" size="50" 
                                         value="<?php if($goods['integral_amount']): echo ($goods['integral_amount']); else: ?>0<?php endif; ?>" style="margin:3px 0;"/>
                                         <br/><img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                         <span style="color:#666;">(此处需填写金额)购买该商品时最多可以使用积分的金额</span>                                            
                                    </td>
                                </tr>      
                                <tr><td colspan="2" style="height:10px;"></td></tr><?php endif; ?>
                            

                          
                          <?php if(C('IS_BONUS')): ?><tr><td  style="text-align:right;">是否支持优惠卷：</td><td>
                                    <input type="radio" value="1" name="is_discount" <?php if($goods['is_new'] == 1): ?>checked="checked"<?php endif; ?>/>支持
                                    <input type="radio" name="is_discount" value="0" <?php if($goods['is_new'] == '0'): ?>checked="checked"<?php elseif($goods['is_new'] == ''): ?>checked="checked"<?php endif; ?>/>不支持
                           </td></tr><?php endif; ?>
                                                    

                           
                           <!--<tr>
                                <td  style="text-align:right;">商品每次限购：</td>
                                <td>
                                 <input type="text" name="buy_limit" 
                                            maxlength="250" class="required digits" 
                                            size="50" value="<?php if($goods['buy_limit']): echo ($goods['buy_limit']); else: ?>0<?php endif; ?>" 
                                 style="margin:3px 0;"/>
                                 <br/><img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                 <span style="color:#666;">购买该商品时限购数量。</span>   
                                  </td>
                            </tr>      
                            <tr><td colspan="2" style="height:10px;"></td></tr>-->                                                                            
                          
                           
                             
                           <?php if(C('IS_PROMOTION')): ?><tr>
                                <td  style="text-align:right;">
                                       促销价格 
                                       <input type="checkbox" name="is_promotion"  class="promotion" 
                                       <?php if($promote['no_ffo'] AND $promote['promotion_end_date']): ?>checked="checked"<?php endif; ?>  value="1">
                                 </td>
                                 <td>
                                     <input type="text" name="promotion_price" 
                                     maxlength="250" class="number promotion_price" size="12" 
                                     value="<?php echo ($promote['promotion_price']); ?>" style="margin:3px 0;" readonly="readonly"/><!-- 促销价格 promotion_price-->
                                     促销数量:<input type="text" size="12" 
                                                       name="numbers" class="number numbers" 
                                                       value="<?php if($promote['numbers']): echo ($promote['numbers']); else: ?>0<?php endif; ?>" readonly="readonly" />    
                                     <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">      
                                     <span style="color:#666;">达到此数量，促销活动自动结束。</span>                                              
                                  </td>
                           </tr>   
                                                         
                            <tr><td colspan="2" style="height:10px;"></td></tr>                     
                            <tr>
                                  <td  style="text-align:right;">促销日期：</td>
                                  <td>
                                <?php if($promote['promotion_start_date'] AND $promote['promotion_end_date'] ): ?><input type="text" name="promote_start_date"  
                                     class="date textInput readonly valid" size="18"  
                                     style="margin:3px 0;float:left;" disabled="disabled" 
                                     value="<?php echo (date('Y-m-d',$promote["promotion_start_date"])); ?>"/><!-- 促销日期开始 promote_start_date-->    
                                     <span class="inputDateButton" href="javascript:;" style="float:left;position:relative;top:3px;padding-left:3px;" disabled="disabled">选择</span>       
                                     <input type="text" name="promote_end_date"  
                                     class="date textInput readonly valid" size="18"  
                                     style="margin:3px 0;float:left;" disabled="disabled" 
                                     value="<?php echo (date('Y-m-d',$promote["promotion_end_date"])); ?>"/><!-- 促销日期结束 promote_end_date-->    
                                     <span class="inputDateButton" href="javascript:;" style="float:left;position:relative;top:3px;padding-left:3px;" disabled="disabled">选择</span>
                                <?php else: ?>
                                      <input type="text" name="promote_start_date"  
                                      class="date textInput readonly valid" size="18"  
                                      style="margin:3px 0;float:left;" disabled="disabled" value="<?php echo date('Y-m-d',time());?>"/><!-- 促销日期开始 promote_start_date-->    
                                      <span class="inputDateButton" href="javascript:;" style="float:left;position:relative;top:3px;padding-left:3px;" disabled="disabled">选择</span>
                                     <input type="text" name="promote_end_date"  
                                     class="date textInput readonly valid" size="18"  
                                     style="margin:3px 0;float:left;" disabled="disabled" value="<?php echo date('Y-m-d',time()+2592000);?>"/><!-- 促销日期结束 promote_end_date-->    
                                     <span class="inputDateButton" href="javascript:;" style="float:left;position:relative;top:3px;padding-left:3px;" disabled="disabled">选择</span><?php endif; ?>                                                 
                            </td></tr>    
                            <tr>
                                  <td  style="text-align:right;" >促销广告图片：</td>
                                  <td>
                                      <input type="hidden" name="promotion_img"  value="<?php echo ($goods["promotion_img"]); ?>" id="goods_thumb1" />
                                     <input name="file3" type="file" id="goodsFileToUpload1"  
                                     onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'ok','remove'=>'ok','width'=>465,'height'=>334));?>',
                                     'goodsFileToUpload1','goods_imgloads_show1','goods_thumb1','','ac-show1','__ROOT__','goods_imgloads1');"/> 
                            </td>
                          </tr>
                            
                          <tr style="<?php if(!$goods['promotion_img']): ?>display:none;<?php endif; ?> " id="goods_imgloads1">
                                <td  style="text-align:right;"></td>
                              <td style="margin:10px 0;">
                                   <img src="__ROOT__/<?php echo ($goods["promotion_img"]); ?>" 
                                   id="goods_imgloads_show1" width="150" height="100" style="display:inline-block;position:relative;top:5px;bottom:" class="ac-show1"/>
                             </td>
                             </tr>
                          <tr><td colspan="2" style="height:10px;"></td></tr><?php endif; ?>                         
                        
                                                                                                      
						  
						  <?php if(C('IS_GOODS_NEW')): ?><tr><td  style="text-align:right;">是否新品：</td><td>
                                    <input type="radio" value="1" name="is_new" <?php if($goods['is_new'] == 1): ?>checked="checked"<?php endif; ?>/>是
                                    <input type="radio" name="is_new" value="0" <?php if($goods['is_new'] == 0): ?>checked="checked"<?php endif; ?>/>否
                           </td></tr><?php endif; ?>
                          
                           
                           
                           <?php if(C('IS_RECOMMEND')): ?><tr><td  style="text-align:right;">本店推荐：</td><td>
                                    <input type="radio" value="1" name="recommend" <?php if($goods['recommend'] == 1): ?>checked="checked"<?php endif; ?>/>是
                                    <input type="radio" name="recommend" value="0" <?php if($goods['recommend'] == 0): ?>checked="checked"<?php endif; ?>/>否
                           </td></tr><?php endif; ?>
                          
                           
                           	
                           <?php if(C('IS_GOODS_HOT')): ?><tr><td  style="text-align:right;">热销商品：</td><td>
                                    <input type="radio" value="1" name="hot" <?php if($goods['hot'] == 1): ?>checked="checked"<?php endif; ?>/>是
                                    <input type="radio" name="hot" value="0" <?php if($goods['hot'] == 0): ?>checked="checked"<?php endif; ?>/>否
                           </td></tr><?php endif; ?>       
                          
                         
                            
                          <?php if(C('IS_GOODS_WEEK')): ?><tr><td  style="text-align:right;">每周优惠：</td><td>
                                    <input type="radio" value="1" name="is_week" <?php if($goods['is_week'] == 1): ?>checked="checked"<?php endif; ?>/>是
                                    <input type="radio" name="is_week" value="0" <?php if($goods['is_week'] == 0): ?>checked="checked"<?php endif; ?>/>否
                           </td></tr><?php endif; ?>    
                           
                           
                            <tr><td  style="text-align:right;">是否上架：</td><td>
                                <input type="radio" value="1" name="shelves" <?php if($goods['shelves'] == 1): ?>checked="checked"<?php elseif($goods['shelves'] == ''): ?>checked="checked"<?php endif; ?>/>上架
                                <input type="radio" value="0" name="shelves" <?php if($goods['shelves'] == '0'): ?>checked="checked"<?php endif; ?>/>下架                                                 
                          </td></tr>

                          
                            <?php if(C('IS_GOODS_LOGISTICS')): ?><tr><td  style="text-align:right;">物流运费模板：</td>
							      <td>
										<select class="combox" name="logistics_tpl_id" >
												 <option value="0">-请物流运费模板-</option>
												  <?php if(is_array($logisticsTpl)): foreach($logisticsTpl as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>" <?php if($goods['logistics_tpl_id'] == $C['id']): ?>selected="selected"<?php endif; ?>><?php echo ($C["tpl_name"]); ?></option><?php endforeach; endif; ?>  
										  </select>      
								  </td>
							</tr><?php endif; ?>
                                                                                                                         
                        </table>
                                                                           
                    </div><!--通用资料内容结束-->             
                           
                    <!--其它信息资料内容-->
                    <div inited="1000" style="display: block;">  
                        <table cellpadding="0" cellspacing="0" class="mytable" style="width:100%;margin:0 auto;">
                        
                           
                             <!--<tr>
                                 <td  style="text-align:right;" width="20%">商品重量：</td>
                                 <td>
                                     <input type="text" name="goods_weight" 
                                                 maxlength="250" value="<?php echo ($goods["goods_weight"]); ?>" 
                                     class="digits" size="38" style="margin:0 3px 3px 0;float:left;margin-right:5px;"/>
                                     <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">      
                                     <span style="color:#666;">以(克)为单位整数</span>                    
                                 </td>
                            </tr>-->
                           

                            
                            <!--
                             <tr><td  style="text-align:right;" width="20%">商品体积：</td><td>
                                     <input type="text" name="volume_m3" 
                                             maxlength="250" value="<?php echo ($goods["volume_m3"]); ?>"
                                    class="digits" size="38" style="margin:0 3px 3px 0;float:left;margin-right:5px;"/>
                                    <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">      
                                     <span style="color:#666;">以(cm3)为单位整数</span>                    
                            </td></tr>-->
                           
                                                       
							                            
                            <!--库存警告数量
                              <tr><td  style="text-align:right;">库存警告数量：</td><td><input type="text" name="stock_warning" class="digits" size="50" value="<?php if($goods['stock_warning']): echo ($goods['stock_warning']); else: ?>1<?php endif; ?>" style="margin:3px 0;">                              
                            </td></tr>                                                 
                             <!--库存警告数量结束-->
                             
                             <!--是否普通销售或作为配件
                             <tr><td  style="text-align:right;">普通商品销售：</td><td><input type="checkbox" name="is_general" <?php if(($goods['is_general']) == "1"): ?>checked="checked"<?php endif; ?> value="1"> 
                                 <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                 <span style="color:#666;">打勾表示能作为普通商品销售，否则只能作为配件或赠品销售。</span>
                            </td></tr>
                           <!--是否普通销售或作为配件结束-->
                           
                           
                           <!--<tr><td  style="text-align:right;">是否为免运费商品：</td><td><input type="checkbox" <?php if(($goods['is_free']) == "1"): ?>checked="checked"<?php endif; ?> name="is_free" value="1"> 
                                 <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                 <span style="color:#666;">打勾表示此商品不会产生运费花销，否则按照正常运费计算。</span>
                            </td></tr>-->                                   
                            
                            
                            <!-- 发货仓库-- >
                            <?php if(is_array($warehouses)): foreach($warehouses as $key=>$t): ?><tr>
                                  <td  style="text-align:right;">(<?php echo ($t['info']); ?>)发货仓库：</td>
                                  <td>
                                      <input type="text"  name="<?php echo ($t['field']); ?>" class="" size="50" value="<?php echo ($goods[$t['field']]); ?>" style="margin:3px 0;"> 
                                 </td>
                            </tr><?php endforeach; endif; ?>  
                            <!--发货仓库 -->    

                            <!-- 发货仓库-- >
                            <?php if(is_array($expresss)): foreach($expresss as $key=>$t): ?><tr>
                                <td  style="text-align:right;">(<?php echo ($t['info']); ?>)快递说明：</td>                                
                                <td>
                                    <input type="text"  name="<?php echo ($t['field']); ?>" class="" size="50" value="<?php echo ($goods[$t['field']]); ?>" style="margin:3px 0;"> 
                                </td>
                            </tr><?php endforeach; endif; ?> 
                            <!--发货仓库 -->                                

                         <tr>
                              <td  style="text-align:right;width:120px;" >尺码介绍图(中文)：</td>
                              <td>
                                  <input type="hidden" name="cn_size_img"  value="<?php echo ($goods["cn_size_img"]); ?>" id="cn_size_img"/>
                                 <input name="file10" type="file" id="cn_size_img1"  
                                 onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'','remove'=>'','width'=>'','height'=>''));?>',
                                 'cn_size_img1','cn_size_img_show','cn_size_img','','cn_size_img-show','__ROOT__','cn_size_img_imgloads');" style="width:70px;"/> 
                                 图片规格：宽度 531px  ，高度自由扩展
                        </td>
                      </tr>
                        
                      <tr style="<?php if(!$goods['cn_size_img']): ?>display:none;<?php endif; ?> " id="cn_size_img_imgloads">
                            <td  style="text-align:right;"></td>
                          <td style="margin:10px 0;">
                               <img src="__ROOT__/<?php echo ($goods["cn_size_img"]); ?>" 
                               id="cn_size_img_show" width="100" height="" style="display:inline-block;position:relative;top:5px;bottom:" class="cn_size_img-show"/>
                         </td>
                         </tr>
                          <tr><td colspan="2" style="height:10px;"></td></tr>

                         <tr>
                              <td  style="text-align:right;width:120px;" >尺码介绍图(英文)：</td>
                              <td>
                                  <input type="hidden" name="us_size_img"  value="<?php echo ($goods["us_size_img"]); ?>" id="us_size_img"/>
                                 <input name="file11" type="file" id="us_size_img1"  
                                 onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'','remove'=>'','width'=>'','height'=>''));?>',
                                 'us_size_img1','us_size_img_show','us_size_img','','us_size_img-show','__ROOT__','us_size_img_imgloads');" style="width:70px;"/> 
                                 图片规格：宽度 531px  ，高度自由扩展
                        </td>
                      </tr>
                        
                      <tr style="<?php if(!$goods['us_size_img']): ?>display:none;<?php endif; ?> " id="us_size_img_imgloads">
                            <td  style="text-align:right;"></td>
                          <td style="margin:10px 0;">
                               <img src="__ROOT__/<?php echo ($goods["us_size_img"]); ?>" 
                               id="us_size_img_show" width="100" height="" style="display:inline-block;position:relative;top:5px;bottom:" class="us_size_img-show"/>
                         </td>
                         </tr>
                          <tr><td colspan="2" style="height:10px;"></td></tr>
                                                                                
                            
                            <?php if(is_array($commodityKeys)): foreach($commodityKeys as $key=>$t): ?><tr>
                                    <td  style="text-align:right;">(<?php echo ($t['info']); ?>)关键字：</td>
                                    <td>
                                        <input type="text" name="<?php echo ($t['field']); ?>" 
                                        maxlength="250" value="<?php echo ($goods[$t['field']]); ?>" 
                                        class="" size="50" style="margin:3px 0;"/>
                                        <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
                                        <span style="color:#666;">多个商品关键字用豆号隔开</span>
                                     </td>
                                </tr><?php endforeach; endif; ?>    
                           
                             
                           
                           <?php if(is_array($commodityDescription)): foreach($commodityDescription as $key=>$t): ?><tr>
                                 <td  style="text-align:right;">(<?php echo ($t['info']); ?>)商品描述：</td>
                                 <td>          
                                    <textarea name="<?php echo ($t['field']); ?>" cols="50" rows="5" 
                                    style="margin-bottom:3px;"><?php echo ($goods[$t['field']]); ?></textarea>
                                  </td>
                            </tr><?php endforeach; endif; ?>                                          
                          
                            
                          
                             <tr><td  style="text-align:right;width:120px;">商家备注：</td><td>          
                                <textarea name="business_notes" cols="50" rows="5" class=""><?php echo ($goods["business_notes"]); ?></textarea>
                                <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:-70px;">
                                 <span style="color:#666;position:relative;top:-73px;">仅供商家自己看的信息</span>
                            </td></tr>                                      
                          
                        </table>
                                                                           
                    </div><!--其它信息资料内容结束-->            
          
                    
                    <!--<div inited="1000" style="display: block;">
                           <?php if(is_array($contents)): foreach($contents as $key=>$t): if(C('LANG_SWITCH_ON')): ?><p style="color:#03408b;height:40px;line-height:40px;
                                   font-weight:bold;border:1px #ccc solid;font-size:16px;
                                   text-indent:10px;margin-top:20px;background:#F0F0EE;
                                   border-bottom:none;width:100%">(<?php echo ($t["info"]); ?>)文档内容</p><?php endif; ?>                 
                                <textarea name="<?php echo ($t["field"]); ?>" class="kindeditor"  up-url="<?php echo U(APP_APPS."/UploadFile/upload",'','');?>?dwz=ok" space-url=""
                                  ><?php echo (stripcslashes($goods[$t['field']])); ?></textarea><?php endforeach; endif; ?>                           
                    </div>-->
                    
                    
                    
                    <?php if(C('IS_GOODS_PARAMETERS')): ?><div inited="1000" style="display: block;">
                       <style type="text/css">table{border-collapse: collapse;}</style> 
                       <table cellpadding="0" cellspacing="0" border="0" class="prarm-list">
                           <?php if($goods[pfix('parameters')]): if(is_array($goods[pfix('parameters')])): foreach($goods[pfix('parameters')] as $keyps=>$pr): ?><tr style="border-bottom: 1px solid #ccc;">
                                   <td style="padding:5px 0;text-align: right;">
                                        <?php if($keyps == 0): ?><a href="javascript:void(0);" onclick="addNodepc(this)">[+]</a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" onclick="delNodepc(this)">[-]&nbsp;</a><?php endif; ?>
                                           
                                           <?php if(is_array($parameterss)): foreach($parameterss as $pkeys=>$par): ?>参数名称(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="<?php echo ($goods[$par['field']][$keyps]['par_name']); ?>" name="<?php echo ($par['field']); ?>[]"/>
                                              <br/><br/><?php endforeach; endif; ?>
                                   </td>
                                   <td style="text-align: left;" >
                                          <?php if(is_array($parameterss)): foreach($parameterss as $key=>$par): ?>&nbsp;<span style="position:relative; top:-20px;">参数值</span>&nbsp;<!--<input type="text" value="<?php echo ($goods[$par['field']][$keyps]['par_value']); ?>" name="<?php echo ($par['field']); ?>_val[]"/>-->
                                           <textarea name="<?php echo ($par['field']); ?>_val[]" style="width:300px;height: 60px;"><?php echo ($goods[$par['field']][$keyps]['par_value']); ?></textarea>
                                          <span style="position:relative; top:-20px;">(<?php echo ($par['info']); ?>)</span>
                                           <br/><?php endforeach; endif; ?>
                                   </td>
                               </tr><?php endforeach; endif; ?>
                          <?php else: ?>
                               <tr style="border-bottom: 1px solid #ccc;">
                                   <td style="padding:5px 0;text-align: right;">
                                           <a href="javascript:void(0);" onclick="addNodepc(this)">[+]</a>
                                           <?php if(is_array($parameterss)): foreach($parameterss as $pkeys=>$par): ?>参数名称(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="" name="<?php echo ($par['field']); ?>[]"/>
                                              <br/><br/><?php endforeach; endif; ?>
                                   </td>
                                   <td style="text-align: left;" >
                                          <?php if(is_array($parameterss)): foreach($parameterss as $key=>$par): ?>&nbsp;<span style="position:relative; top:-20px;">参数值</span>&nbsp;<!--<input type="text" value="" name="<?php echo ($par['field']); ?>_val[]"/>-->
                                           <textarea name="<?php echo ($par['field']); ?>_val[]" style="width:300px;height: 60px;"></textarea>
                                           <span style="position:relative; top:-20px;">(<?php echo ($par['info']); ?>)</span>
                                           <br/><?php endforeach; endif; ?>
                                   </td>
                               </tr><?php endif; ?>  
                       </table>
                    </div>
                    <script type="text/javascript">
                        //复制节点
                        function addNodepc(node){
                              $(function(){
                                var tr=$(node).parent().parent();     
                                var str=$(tr).clone(true);     
                                str='<tr class="seat"><td colspan="2" style="height:8px;"></td></tr><tr style="border-bottom: 1px solid #ccc;margin-bottom:10px;">'+$(str).html()+'</tr>';
                                str=str.replace('addNodepc', 'delNodepc');
                                str=str.replace('[+]', '[-]&nbsp;');		
                               $(node).parents('table').append(str);    
                               var emptyVlaue=$('.prarm-list').find('tr').last();
                               emptyVlaue.find('input[type="text"]').val('');
                               emptyVlaue.find('textarea').val('');
                              });
                        }
                        
                        //删除节点
                        function delNodepc(_this){
                              $(function(){
                                  var tr=$(_this).parent().parent();
                                  var prev=tr.prev();   
                                  tr.remove();                                       
                                  if(prev.attr('class')=='seat'){
                                      prev.remove();
                                  }
                              });
                        } 
                    </script><?php endif; ?>
                               
                    
                    
                    <?php if(C('IS_GOODS_AFTER_SALES')): ?><div inited="1000" style="display: block;">
                           <?php if(is_array($afterSales)): foreach($afterSales as $key=>$t): if(C('LANG_SWITCH_ON')): ?><p style="color:#03408b;height:40px;line-height:40px;
                                   font-weight:bold;border:1px #ccc solid;font-size:16px;
                                   text-indent:10px;margin-top:20px;background:#F0F0EE;
                                   border-bottom:none;width:100%">(<?php echo ($t["info"]); ?>)售后服务</p><?php endif; ?>                           
                                <textarea name="<?php echo ($t["field"]); ?>" class="kindeditor"  up-url="<?php echo U(APP_APPS."/UploadFile/upload",'','');?>?dwz=ok" space-url=""
                                  ><?php echo (stripcslashes($goods[$t['field']])); ?></textarea><?php endforeach; endif; ?>
                        </div><?php endif; ?>
                        
                  
                  
                    <div inited="1000" style="display: block;">
                        <p style="height: 10px;padding-bottom:10px;">商品相册: 
                        <input name="file3" type="file" id="fileToUpload7" 
                        onchange="relatedAlbumUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'ok','remove'=>'ok','width'=>1000,'height'=>1334));?>'
                        ,'fileToUpload7','setimgdiv','__ROOT__','');" style="width:70px;"> 图片规格：1000 * 1334 
                        </p>  
                        <div class="divider"></div> 
                        <!--这里上传图片-->
                        <?php if(is_array($photo)): foreach($photo as $key=>$p): ?><div class="divigm" style="float:left;margin:3px;position:relative;">
                               <span 
                                         style="display:block;width:200px;background:#000;filter:alpha(opacity=30);
                                         -moz-opacity:0.3;-khtml-opacity: 0.3; opacity: 0.5;font-weight:bold;
                                         position:absolute;left:0;top:0;">
                                 <b class="close_upimg" onclick="delDiv(this);" style="cursor:pointer;float:right;color:red;font-size:16px;" title="'+closes+'">×</b>
                              </span>
                                   <a href="__ROOT__/<?php echo ($p["img"]); ?>" target="_blank">
                                          <img src="__ROOT__/<?php echo ($p["img"]); ?>" width="200" height="180" style="display:block;"/>
                                  </a>
                                   <input type="hidden" value="<?php echo ($p["img"]); ?>" name="imgAll[]"/>
                                   <input type="text" name="title[]" style="width:197px;height:16px;font-size:12px;border:none;" value="<?php echo ($p["img_bewrite"]); ?>"  
                                   placeholder=""/>
                           </div><?php endforeach; endif; ?>                           
                       <div id="setimgdiv" style="width:200px;height:180px;float:left;margin:3px;">
                             <img src="<?php echo C('__IMG__');?>/13221814.gif" style="display:none;padding:80px 0 0 80px;" id="img_related"/>
                       </div>              
                    </div>
                       
                    
                    
					<?php if(C('IS_GOODS_ATTRIBUTE')): ?><div inited="1000" style="display: block;">
							 <table cellpadding="0" cellspacing="0" class="mytable" style="width:100%;margin:0 auto;">
								 <tr><td  style="text-align:right;" width="10%">商品属性类型：</td><td>
									<select class="combox required goods-attr" name="goods_attr_type_id" >
											 <option value="0">-商品属性类型-</option>
											  <?php if(is_array($attrType)): foreach($attrType as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>" 
                                               <?php if($goods['goods_attr_type_id'] == $C['id']): ?>selected="selected"<?php endif; ?> ><?php echo ($C[pfix('name')]); ?></option><?php endforeach; endif; ?>  
									  </select>     
									 <img src="<?php echo C('__IMG__');?>/notice.gif" style="position:relative;top:3px;">
									 <span style="color:#666;">请选择商品的属性类型，进而完善此商品的属性</span>                                      
								</td></tr>
														
							<tr><td colspan="2">
								<table style="width:100%;margin:0 auto;" class="atrr-tr">
								   <?php echo ($attrList); ?>
								</table>
							</td></tr>
							 
							</table>                
						 </div><?php endif; ?>           
                       
                    
                  
                  <?php if(C('IS_GOODS_LINK')): ?><div inited="1000" style="display: block;">
                              <table cellpadding="0" cellspacing="0" class="tableshide  tableshow5" border="0" style="width:100%;">
                              <tr> 
                               <td colspan="3" class="tdleft" style="height:30px;line-height:30px;"> 
                                    <select class="combox cart-ajax" name="" >
                                             <option value="0">-全部分类-</option>
                                              <?php if(is_array($goodCat)): foreach($goodCat as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>"><?php echo ($C["html"]); echo ($C[pfix('name')]); ?></option><?php endforeach; endif; ?>  
                                      </select>  
                                    <?php if(C('IS_GOODS_BRAND')): ?><select class="combox bra-ajax" name="" >
                                         <option value="0">-全部品牌-</option>
                                          <?php if(is_array($brandList)): foreach($brandList as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>"><?php echo ($C["html"]); echo ($C["name"]); ?></option><?php endforeach; endif; ?>  
                                  </select><?php endif; ?>                                                                      
                                 <input type="text" name="s_name" class="tdinpt" style="float:left;padding-left:5px;"/>
                             </span> <a class="button pro-ajax" href="javascript:;" style="float:left;position:relative;top:-3px;left:3px;" ><span>检索</span></a>  
                              </td>
                              </tr>
                             
                              <tr>
                                <th style="height:30px;line-height:30px;text-indent:10px;">可选商品</th>
                                <th style="text-align:center;">操作</th>
                                <th>跟该商品关联的商品</th>
                              </tr>
                              <tr>
                                <td width="45%">          
                                  <select name="" size="20" style="width:100%" multiple class="selectLeftProList">
                                  </select>             
                                </td>
                                <td align="center" style="text-align:center;">
                                  <p><br />
                                       <input name="price2" type="hidden" size="6" /><input name="is_single" type="hidden"  />
                                  </p><br />
                                   <p style="text-align: center;">
                                   <label>
                                          <input type="radio" value="1" name="two_way" checked="checked" style="position:relative;top:3px;" class="radios"/>双向关联
                                   </label>
                                   </p><br />
                                   <p style="text-align: center;">
                                        <label>
                                             <input type="radio" value="0" name="two_way"style="position:relative;top:3px;" class="radios"/>单向关联
                                         </label>
                                   </p><br />                                  
                                  <p>
                                       <input type="button" value=" >> " onclick="leftValueOen('selectLeftProList','selectRightProList','radios');" class="upbutton" />
                                   </p><br />
                                  <p><input type="button" value=" << " onclick="rightValueOen('selectRightProList');" class="upbutton" /></p><br />
                                </td>
                                <td width="45%">
                                  <select name="link_goods_id[]" size="20" style="width:100%" multiple class="selectRightProList">
                                      <?php if(is_array($goodsRelation)): foreach($goodsRelation as $key=>$g): ?><option value="<?php echo ($g["link_goods_id"]); ?>-<?php if(($g["is_double"]) == "1"): ?>1<?php else: ?>0<?php endif; ?>" selected="selected">
                                             <?php if(($g['is_double']) == "1"): echo ($g["goods_name"]); ?><----- >双向关联<?php else: echo ($g["goods_name"]); ?>---- >单向关联<?php endif; ?>
                                         </option><?php endforeach; endif; ?>
                                  </select>
                                </td>
                               </tr>      
                                </table>              
                     </div><?php endif; ?>  
                                    
                    
                    
                    <?php if(C('IS_GOODS_ACCESSORIES')): ?><div inited="1000" style="display: block;">
                              <table cellpadding="0" cellspacing="0" class="tableshide  tableshow5" border="0" style="width:100%;">   
                              <tr> 
                               <td colspan="3" class="tdleft" style="height:30px;line-height:30px;"> 
                                <select class="combox acc-cart-ajax" name="" >
                                         <option value="0">-全部分类-</option>
                                          <?php if(is_array($goodCat)): foreach($goodCat as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>"><?php echo ($C["html"]); echo ($C["name"]); ?></option><?php endforeach; endif; ?>  
                                  </select>   
                                 <select class="combox acc-bra-ajax" name="" >
                                     <option value="0">-全部品牌-</option>
                                      <?php if(is_array($brand)): foreach($brand as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>"><?php echo ($C["html"]); echo ($C["name_cn"]); ?></option><?php endforeach; endif; ?>  
                              </select>                                                                            
                             <input type="text" name="acc_name" class="tdinpt" style="float:left;padding-left:5px;"/>
                             </span> <a class="button acc-ajax" href="javascript:;" style="float:left;position:relative;top:-3px;left:3px;" ><span>检索</span></a>  
                              </td>
                              </tr> 
                           
                              <tr>
                                <th style="height:30px;line-height:30px;text-indent:10px;">可选商品</th>
                                <th style="text-align:center;">操作</th>
                                <th>该商品的配件</th>
                              </tr>
                              <tr>
                                <td width="45%">          
                                  <select name="" size="20" style="width:100%" multiple class="selectLeftacc">
                                  </select>             
                                </td>
                                <td align="center" style="text-align:center;">
                                  <p><br /><input name="price2" type="hidden" size="6" /><input name="is_single" type="hidden"  /></p><br />
                                  <p><input type="button" value=" >> " onclick="leftValueOen('selectLeftacc','selectRightacc');" class="upbutton" /></p><br />
                                  <p><input type="button" value=" << " onclick="rightValueOen('selectRightacc');" class="upbutton" /></p><br />
                                </td>
                                <td width="45%">
                                  <select name="acc_relevance_id[]" size="20" style="width:100%" multiple class="selectRightacc">
                                      <?php if(is_array($accessories)): foreach($accessories as $key=>$a): ?><option value="<?php echo ($a["goods_acc_id"]); ?>" selected="selected"><?php echo ($a["goods_name"]); ?></option><?php endforeach; endif; ?>
                                  </select>
                                </td>
                               </tr>      
                                </table>                                           
                     </div><?php endif; ?>
                              
                                
                  
                  <?php if(C('IS_GOODS_LINK_ARTICLE')): ?><div inited="1000" style="display: block;">
                          <table cellpadding="0" cellspacing="0" class="tableshide  tableshow5" border="0" style="width:100%;">     
                          <tr> 
                           <td colspan="3" class="tdleft" >                      
                         <span style="float:left;position:relative;top:4px;right:3px;"> 标题 </span><input type="text" name="titletext" id="titletext" class="tdinpt" style="float:left;margin-right: 5px;"/>               
                        <select class="" id="files_id" style="float:left;">
                            <option value="0">选择分类</option>
                            <?php if(is_array($cage)): foreach($cage as $key=>$s): ?><option value="<?php echo ($s["files_id"]); ?>"><?php echo ($s["html"]); echo ($s["files_name"]); ?></option><?php endforeach; endif; ?>
                       </select>                                 
                         </span> <a class="button upbutton" href="javascript:;" style="float:left;position:relative;top:-3px;left:3px;" onclick="searchArticle('<?php echo U(APP_APPS.'/Search/article','','');?>','article','id','titletext','files_id','selectLeft')"><span>检索</span></a>  
                          </td>
                      </tr>  
                             
                      <tr>
                        <th>可选文章</th>
                        <th style="text-align:center;">操作</th>
                        <th>关联文章</th>
                      </tr>
                      <tr>
                        <td width="45%">          
                          <select name="selectLeft[]" size="20" style="width:100%" multiple class="selectLeft">
                          </select>             
                        </td>
                        <td align="center" style="text-align:center;">
                          <p><br /><input name="price2" type="hidden" size="6" /><input name="is_single" type="hidden"  /></p><br />
                          <p><input type="button" value=" >> " onclick="leftValueOen('selectLeft','selectRight');" class="upbutton" /></p><br />
                          <p><input type="button" value=" << " onclick="rightValueOen('selectRight');" class="upbutton" /></p><br />
                        </td>
                        <td width="45%">
                          <select name="selectLinkArticle[]" size="20" style="width:100%" multiple class="selectRight">
                              <?php if(is_array($article)): foreach($article as $key=>$a): ?><option value="<?php echo ($a["article_id"]); ?>" selected="selected"><?php echo ($a["titletext"]); ?></option><?php endforeach; endif; ?>
                          </select>
                        </td>
                       </tr>      
                        </table>
                </div><?php endif; ?> 
             

           
            <div inited="1000" style="display: block;">  
                <table cellpadding="0" cellspacing="0" class="mytable" style="width:100%;margin:0 auto;">
                    <tr>
                         <td  style="text-align:right;width:60px;">商品名称：</td>
                         <td>
                             <input type="text" name="goods_zhuhe" style="float:left;width:200px;" />
                             <a class="button" href="javascript:;" style="float:left;position:relative;top:-3px;left:3px;" onclick="sahceGoods()" >
                                  <span>检索</span>
                             </a> 
                        </td>
                   </tr>   
                   <tr><td colspan="2" style="height:20px;"></td></tr>
                   <tr>
                       <td colspan="2">
                          <table style="width:100%;margin:0 auto;" class="goods-zhuhe-link">
                               <?php echo ($ZuHeGoods); ?>
                         </table>
                    </td>
                 </tr>  
                 <tr><td colspan="2" style="height:20px;"></td></tr>
             </table>
            </div>  
            <style type="text/css">
                .show-goods11{background:#ccc;border-spacing:1px;border-collapse:separate;}
				.show-goods11 tr td{background:#fff;text-align:center;padding:5px 0;}
           </style>  
           <script type="text/javascript">
		         //检索商品
				 function sahceGoods(){
					    var gooodsName=$('input[name="goods_zhuhe"]').val();
					    if(gooodsName==''){
							alert('请先输入关键字');
							return;
						}
						var openUrl="<?php echo U(APP_APPS.'/Goods/goodsSendForm','','');?>?zhu=ok&keywords="+gooodsName+"&goods_cate_id=0";
						var response=callAjaxDo(openUrl,'text');//获取数据    
						$('.goods-zhuhe-link').append(response);  				 
				 }
				 
                 //删除
				 function delLikeGoods(_this){
				    $(_this).parents('.goods-list').remove();
				 }
           </script>      
                      
               
        </div>
        <div class="formBar">
          <ul>
             <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
             <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
         </ul>
       </div>
    </form>
        <div class="tabsFooter">
            <div class="tabsFooterContent"></div>
        </div>
    </div>
</div>