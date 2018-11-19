<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">

.zxfbrbt img {
    width: auto;
    height: 71.49%;
    background: #fff;
}

.zxfbrbtb img {
    width: auto;
    height: 71.49%;
    background: #fff;
}
</style>




<script>

/*$(document).on('click', ".sxcmr", function(){
    $(this).parent("li").slideUp()
});
$(document).on('click', ".splb h1", function(){
    $(this).toggleClass("active")
   $(this).siblings(".splbul,.clear2").slideToggle()
});*/



$(function(){

	w=$(window).width()
	 wm=$(".zxfb").width()
	 wl=$(".shxl").width()
	 wr=$(".shxr").width()
     hr=$(".shxr").height()
	 h=$(window).height()
    ch=$(".shxl").height()

 /**$(".qgd").click(function(){
	  $ (window).unbind ('scroll');
	 })**/
    $(window).scroll(function () {
		
		h1= $(".shxr").offset().top
	 h2= $(".xian").offset().top
	// h3= $(".xianl").offset().top
	 
 
	 
		
		 h=$(window).height()
		ch=$(".shxl").height()
		cch=h-135
		

		var targetTop = $(this).scrollTop();
if (targetTop > h1 ) {
$(".shxl").addClass("shxlfd")
$(".shxl").css({
	"position":"fixed",
	"width":wm-wr,
	"top":130,
	"left":(w-wm)/2,
	"float":"none",
	})

/**if(ch>cch){
	$(".shxl").css({
	"top":"auto",
	"left":(w-wm)/2,
	"bottom":20,
	"float":"none",
	})
			}	
	
	***/	 

}
/**else if(targetTop >1080){
	$(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
	
	
	
	}**/
else {
	$(".shxl").removeClass("shxlfd")
$(".shxl").css({
	"position":"relative",
	"width":"25%",
	"top":"auto",
	"left":"auto",
	"bottom":"auto",
	"float":"left",
	})
	}
	
	/**if (targetTop = 200) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
	
	//alert(h3)
			}**/
			h4=$(window).height()-$(".shxl").height()-75
			h5=$(".footer").offset().top

	if (h2 >= $(window).scrollTop() && h2 < ($(window).scrollTop()+$(window).height())-h4) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none",
	})
			}
			
   if (h5 >= $(window).scrollTop() && h5 < ($(window).scrollTop()+$(window).height())) {
		
              $(".shxl").removeClass("shxlfd")
	$(".shxl").css({
	"position":"absolute",
	"top":"auto",
	"left":0,
	"bottom":40,
	"float":"none", 
	})
			}
	
	
	<?php if(count($goods) <8){ ?>
	   $(window).unbind ('scroll');
	   $(".shxl").removeClass("shxlfd")
$(".shxl").css({
	"position":"relative",
	"width":"25%",
	"top":"auto",
	"left":"auto",
	"bottom":"auto",
	"float":"left",
	})
	 
	<?php } ?> 
	
});





 
})


function ajaxTo(_this){
   url=$(_this).val() ? $(_this).val() : $(_this).attr('href');
   getHtml(url);
   return false;
}


function clearSelect(_this,isall){

	//全部清除
   if(isall){
	   var splb=$(_this).parents('.splb');
	   splb.find('a').removeClass('ffInput--active');
	   $('input[type="text"]').val('');
	   retrieval('');	  
	   return;
   }
   //单个清单
   var type=$(_this).attr('type');
   var value = parseInt($(_this).attr('value'));
   var splb=$('.splb');
   var a=splb.find('a');   
   for(var i = 0; i < a.length;i++){
	  var tt=a.eq(i).attr('type');
	  var tv=parseInt(a.eq(i).attr('value'));
	  if(type == tt && value == tv){
	      a.eq(i).removeClass('ffInput--active');
		  break;
	  }else if(type == 'text'){
	      $('input[type="text"]').val('');
		  break;
	  }
   }
   retrieval('');	
}


function clearPrice(){
   $('input[type="text"]').val('');

}


function clearClass(_this){
   var splb=$(_this).parents('.splb');
   splb.find('a').removeClass('ffInput--active');
    retrieval('');
}


function retrieval(_this){
	if(_this){
		 if($(_this).is('.ffInput--active')) $(_this).removeClass('ffInput--active');
		 else $(_this).addClass('ffInput--active');	
	}
     var splb=$('.splb');
	 var a=splb.find('a');
	 var url="<?php echo U('Category/index','','');?>";
	 //分类检索
	 var html='';
	 //属性组检索
	 var attrs='';
	 for(var i=0;i < a.length;i++){
		 var sa=a.eq(i);
		 if(sa.is('.ffInput--active')){
			 if(sa.attr('type')=='html') html+=sa.attr('value')+',';
			 else if(sa.attr('type')=='attr') attrs+=sa.attr('value')+',';			 
		 }
	 }
	 if(html) url+='/html/'+html;
	 if(attrs) url+='/attrs/'+attrs;
	 var min=$('input[name="min"]').val();
	 var max=$('input[name="max"]').val();
	 if(isNaN(min)) $('input[name="min"]').val(''); 
	 if(isNaN(max)) $('input[name="min"]').val(''); 
	 if((min && !isNaN(min)) && (max && !isNaN(max))){
		 url+='/min/'+min+'/max/'+max;
	 }
     getHtml(url);
     return false;
	 
	
}

function getHtml(url){
  $.ajax({
	 type:'get',
	 dataType: "text",
	 url:url,
	 success:function(response,status,xhr){
		 var  show=$('.tcshxl').css('display');
		 var h=(parseInt($('.tcshxl').scrollTop()) + $('.shxlb').height());
		
		 $('#ajax-div').html(response);
		 if(show=='block'){
			 $('.tcshxl').show();
			 $('.tcshxlb').show();	
			 $('.tcshxl').stop().animate({
					'scrollTop':h,
					'duration': 0,
					'easing': 'ease-in'
			  });		 
			 
		 }else{
			// if(!$('input[name="min"]').val() && !$('input[name="max"]').val()){
				 $('body,html').stop().animate({
					'scrollTop': 0,
					'duration': 300,
					'easing': 'ease-in'
				  });		 
			// }			 
		 }
		 eval($(".chosen-select").chosen());
	  },
	  beforeSend:function(){
		  //开始请求
		  $('.curtain').show();
	  },         
	 error:function(xhr,errorText,errorType){
		 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
	 }
 });    
}
</script>
<style type="text/css">
.zxfbrb{
   position:relative;
}
.curtain{
    position:absolute;
	width:100%;
	height:100%;
	z-index:10;
	background:#fff;
	filter:alpha(opacity=90);  
	-moz-opacity:0.9;  
	-khtml-opacity: 0.9;  
	opacity: 0.9;  	
	display:none;
}
.chosen-select {
    display: inline!important;
    visibility: hidden;
    opacity: 0;
    position: absolute;
    z-index: -1;
}

</style>
<div class="fabut">
 <div class="fabutl"><b class="mr5 vam"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?>>60</a></div><!--fabutl end-->
 <div class="fabutr">
  <div class="fabutrl">
   <span class="fabutrltxt"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?><!--<?php echo isL(L('Classification'),'分类');?>--></span>
    <div style="width:160px; display:inline-block; vertical-align:middle">
        <select class="chosen-select" name="" onchange="return ajaxTo(this)">
         <option value="<?php echo U('Category/index',array('order_type'=>'create_time','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'create_time'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('New'),'新品');?></option>
        
         <option value="<?php echo U('Category/index',array('order_type'=>'volume','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'volume'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('SalesVolume'),'销量');?></option>
         <!--<option value="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" <?php if($_GET['wishlist'] == 'condition'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('Wishlist'),'愿望单');?></option>-->
          <option value="<?php echo U('Category/index',array('order_type'=>'coll_numeber','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'coll_numeber'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('Wishlist'),'愿望单');?></option>
 <option value="<?php echo U('Category/index',array('order_type'=>'goods_retail_price','order'=>'ASC'));?>" <?php if($_GET['order_type'] == 'goods_retail_price' AND $_GET['order'] == 'ASC'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('LowToHigh'),'低至高');?></option>     
 <option value="<?php echo U('Category/index',array('order_type'=>'goods_retail_price','order'=>'DESC'));?>" <?php if($_GET['order_type'] == 'goods_retail_price' AND $_GET['order'] == 'DESC'): ?>selected="selected"<?php endif; ?>><?php echo isL(L('HighToLow'),'高至低');?></option>        
         </select>
     </div>
  </div>
  <div class="fabutrr">
   <ul class="to-page-ul">
   <?php if($currentPage <= 1): ?><li><a  class="disable"><span class="glyphs icon-pageFirst"></span></a></li>
        <li><a  class="disable"><span class="glyphs icon-prev"></span></a></li>
    <?php else: ?>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,1);?>" class=""><span class="glyphs icon-pageFirst"></span></a></li>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,'-');?>" class=""><span class="glyphs icon-prev"></span></a></li><?php endif; ?>
    <li class="txt"><span><?php echo ($currentPage); ?></span> of <span><?php echo ($page_sze); ?></span></li>
    <?php if($currentPage >= $pageNumShown): ?><li><a  class="disable"><span class="glyphs icon-next"></span></a></li>
         <li><a  class="disable"><span class="glyphs icon-pageLast"></span></a></li>   
    <?php else: ?>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,'+');?>"><span class="glyphs icon-next"></span></a></li>
        <li><a onclick="return ajaxTo(this)" href="<?php echo go_page($_URL_,$pageNumShown,$pageNumShown);?>"><span class="glyphs icon-pageLast"></span></a></li><?php endif; ?>
   </ul>
  </div>
  <div class="cb"></div>
 </div><!--fabutr end--> 
 <div class="cb"></div>
 
</div><!--fabut end-->
<div class="pr">

<?php if(is_mobile()){ ?>
    <div class="tcshxl" id="tcshxle">
     <div class="tcshxlt"><span class="glyphs icon-arrowLeft"></span><?php echo isL(L('ScreeningConditions'),'筛选条件');?></div>
     <div class="shxlm">
     <div class="shxlb"> 
      <b class="lh22"><?php echo isL(L('Screening'),'筛选');?></b>
      <!--<span class="colaaa fr f12 lh22">(<?php echo replace_isl('CateCount','项',$dataCount);?>)</span> --> 
      <div class="cb pb10"></div>
      <div class="sxcm">
       <ul>
       
       <?php if(is_array($scs)): foreach($scs as $key=>$s): ?><li><span class="xscml"><?php echo ($s['name']); ?></span><span class="sxcmr" type="html" value="<?php echo ($s['id']); ?>"><span class="glyphs icon-close"></span></span></li><?php endforeach; endif; ?>
       
       <?php if($selectPrice): ?><li><span class="xscml"><?php echo ($selectPrice); ?></span><span class="sxcmr"  type="text" value=""><span class="glyphs icon-close"></span></span></li><?php endif; ?>
       
       <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): ?><li>
              <span class="xscml"><b><?php echo ($ss['attribute_name']); ?>：</b><?php echo ($ss['attr_value']); ?></span>
              <span class="sxcmr" type="attr" value="<?php echo ($ss['attr_id']); ?>"><span class="glyphs icon-close"></span></span>
          </li><?php endforeach; endif; ?>
       </ul>
      </div>
      
      
      <?php if($scs || $selectPrice || $showSelectAttrs){ ?>
             <button type="submit" class="button-white mv20" onclick="return ajaxTo(this)" 
             href="<?php echo U('Category/index',array('order_type'=>$_GET['order_type'],'order'=>$_GET['order'],'text'=>$_GET['text']));?>"><div class="tc" ><?php echo isL(L('ClearAll'),'清除全部');?></div>  
            </button>
     <?php }else{ ?>  
          <p style="color:#ccc;height:40px;line-height:40px;"><?php echo isL(L('FilterItemsWill'),'您的筛选项目将显示于此');?></p> 
     <?php } ?>
       
     </div><!--筛选 end-->
     <div class="splb" id="to-tops1">
     <h1 class="active"><?php echo isL(L('CommodityCategory'),'商品类别');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
     
     <ul class="splbul" style="display:block">
      <?php if($_GET['html']){ ?><li class="clear" onclick="clearClass(this)"><?php echo isL(L('Clear'),'清除');?></li><?php } ?>
      <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
           <a href="javascript:void(0);" type="html" value="<?php echo ($c['id']); ?>" onclick="retrieval(this)" class="not-now <?php if(in_array($c['id'],$ch)): ?>ffInput--active<?php endif; ?>">
             <span class="ffInput__checkbox"></span><?php echo ($c['name']); ?>
           </a>
        </li><?php endforeach; endif; ?>
     </ul>
     </div><!--商品类别 end-->
     
     
    <?php if(is_array($attrs)): foreach($attrs as $keys=>$attr): ?><div class="splb " id="to-tops-attr-<?php echo ($keys); ?>">
         <h1 <?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>class="active"<?php endif; ?>>
             <?php echo ($attr['attribute_name']); ?> <span class="glyphs icon-more facet-icon fr"></span>
         </h1>
         <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): if($ss['attribute_name']==$attr['attribute_name']){ ?>
             <div class="clear2" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>" onclick="clearClass(this)">
                 <?php echo isL(L('Clear'),'清除');?>
              </div>
              <?php break; ?>
          <?php } endforeach; endif; ?>
         <div class="splbul" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
         <ul  style="  width:100%;<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
         <?php if(is_array($attr['attr_list'])): foreach($attr['attr_list'] as $ak=>$a): if($ak > 0): ?><li style="<?php if(($attr['attribute_name'] == '尺码') OR (strtoupper($attr['attribute_name']) == 'SIZE')): ?>width:49%;float:left;<?php endif; ?>">
                  <a href="javascript:void(0);" type="attr" value="<?php echo ($a['attr_id']); ?>" 
                  <?php if(!$a['is_true']): ?>class="disabled"<?php else: ?> class="not-now <?php if(in_array($a['attr_id'],$selectAttr)): ?>ffInput--active<?php endif; ?>" onclick="retrieval(this)"<?php endif; ?>>
                     <span class="ffInput__checkbox"></span><?php echo ($a['attr_value']); ?>
                  </a>
              </li><?php endif; endforeach; endif; ?>
         <div class="cb"></div>
         </ul>
         <?php if((strpos($attr['attribute_name'],'尺码') !== false) || (strpos(strtoupper($attr['attribute_name']),'SIZE') !==false)){ ?>
             <a href="javascript:void(0)" class="col2ab cmznn fr"><?php echo isL(L('SizeDescription'),'尺码指南');?></a>
         <?php } ?>    
         <div class="cb pb10"></div>
         </div>
         
     </div><!--尺码 end--><?php endforeach; endif; ?>  
    
    
     <div class="splb" id="to-tops2">
     <h1 <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>class="active"<?php endif; ?>><?php echo isL(L('Price'),'价格');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
     <ul class="splbul" <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>style="display:block;"<?php endif; ?>>
         <?php if($_GET['min'] && $_GET['max']){ ?>
              <li class="clear" onclick="clearPrice()"><?php echo isL(L('Clear'),'清除');?></li>
         <?php } ?>
          <li>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left">
                    <input type="text"  name="min" value="<?php echo htmlspecialchars($_GET['min']);?>" class="jiagel_input" 
                    onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('FloorPrice'),'最低价格');?>"/>
                </div>
               </div>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left">
                     <input type="text" name="max" value="<?php echo htmlspecialchars($_GET['max']);?>" class="jiagel_input" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" 
                     onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('CeilingPrice'),'最高价格');?>"/>
                </div>
               </div>
               <div class="cb"></div>
               
               <?php if($prcieError AND !$goods): ?><!--<p class="tsy2" style="display:block;"><?php echo ($prcieError); ?></p>--><?php endif; ?>
                <button type="submit" class="button-offWhite mt10 fr" onclick="retrieval(false)" style="width:34%">
                                <span class="fl"><?php echo isL(L('Determine'),'确定');?></span>
                                <span class="glyphs icon-thinArrow fr"></span>
                </button>
                <div class="cb"></div>
          </li> 
     </ul>
     </div><!--颜色 end-->
    </div><!--shxlm end-->
     
    </div>
    <div class="tcshxlb">
     <b><?php echo replace_isl('CateCount','%d项',$dataCount);?></b><input type="button" name="" value="<?php echo isL(L('DisplayResults'),'显示结果');?>" class="tcshxlb_btn close-retrieval"/>
    </div>
    
<?php }else{ ?>
    <div class="shxl">
        <div class="shxlm">
         <div class="shxlb"> 
          <b class="lh22"><?php echo isL(L('Screening'),'筛选');?></b>
          <span class="colaaa fr f12 lh22">(<?php echo replace_isl('CateCount','项',$dataCount);?>)</span>  
          <div class="cb pb10"></div>
          <div class="sxcm">
           <ul>
               
               <?php if(is_array($scs)): foreach($scs as $key=>$s): ?><li><span class="xscml"><?php echo ($s['name']); ?></span><span class="sxcmr" type="html" value="<?php echo ($s['id']); ?>"><span class="glyphs icon-close"></span></span></li><?php endforeach; endif; ?>
               
               <?php if($selectPrice): ?><li><span class="xscml"><?php echo ($selectPrice); ?></span><span class="sxcmr"  type="text" value=""><span class="glyphs icon-close"></span></span></li><?php endif; ?>
               
               <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): ?><li>
                      <span class="xscml"><b><?php echo ($ss['attribute_name']); ?>：</b><?php echo ($ss['attr_value']); ?></span>
                      <span class="sxcmr" type="attr" value="<?php echo ($ss['attr_id']); ?>"><span class="glyphs icon-close"></span></span>
                  </li><?php endforeach; endif; ?>
               </ul>
              </div>
              
              
              <?php if($scs || $selectPrice || $showSelectAttrs){ ?>
                     <button type="submit" class="button-white mv20" onclick="return ajaxTo(this)" 
                     href="<?php echo U('Category/index',array('order_type'=>$_GET['order_type'],'order'=>$_GET['order'],'text'=>$_GET['text']));?>"><div class="tc" ><?php echo isL(L('ClearAll'),'清除全部');?></div>  
                    </button>
             <?php }else{ ?>  
                  <p style="color:#ccc;height:40px;line-height:40px;"><?php echo isL(L('FilterItemsWill'),'您的筛选项目将显示于此');?></p> 
             <?php } ?>
               
             </div><!--筛选 end-->
             <div class="splb">
             <h1 class="active"><?php echo isL(L('CommodityCategory'),'商品类别');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
             
             <ul class="splbul" style="display:block">
              <?php if($_GET['html']){ ?><li class="clear" onclick="clearClass(this)"><?php echo isL(L('Clear'),'清除');?></li><?php } ?>
              <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
                   <a href="javascript:void(0);" type="html" value="<?php echo ($c['id']); ?>" onclick="retrieval(this)" class="not-now <?php if(in_array($c['id'],$ch)): ?>ffInput--active<?php endif; ?>">
                     <span class="ffInput__checkbox"></span><?php echo ($c['name']); ?>
                   </a>
                </li><?php endforeach; endif; ?>
             </ul>
             </div><!--商品类别 end-->
             
             
            <?php if(is_array($attrs)): foreach($attrs as $key=>$attr): ?><div class="splb">
                 <h1 <?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>class="active"<?php endif; ?>>
                     <?php echo ($attr['attribute_name']); ?> <span class="glyphs icon-more facet-icon fr"></span>
                  </h1>
                 <?php if(is_array($showSelectAttrs)): foreach($showSelectAttrs as $key=>$ss): if($ss['attribute_name']==$attr['attribute_name']){ ?>
                     <div class="clear2" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>" onclick="clearClass(this)">
                         <?php echo isL(L('Clear'),'清除');?>
                     </div>
                     <?php break; ?>
                  <?php } endforeach; endif; ?>
                 <div class="splbul" style="<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
                 <ul  style="  width:100%;<?php if(array_intersect($attr['attr_ids'],$selectAttr ? $selectAttr : array())): ?>display:block;<?php endif; ?>">
                 <?php if(is_array($attr['attr_list'])): foreach($attr['attr_list'] as $ak=>$a): if($ak > 0): ?><li style="<?php if(($attr['attribute_name'] == '尺码') OR (strtoupper($attr['attribute_name']) == 'SIZE')): ?>width:49%;float:left;<?php endif; ?>">
                          <a href="javascript:void(0);" type="attr" value="<?php echo ($a['attr_id']); ?>" 
                          <?php if(!$a['is_true']): ?>class="disabled"<?php else: ?> class="not-now <?php if(in_array($a['attr_id'],$selectAttr)): ?>ffInput--active<?php endif; ?>" onclick="retrieval(this)"<?php endif; ?>>
                             <span class="ffInput__checkbox"></span><?php echo ($a['attr_value']); ?>
                          </a>
                      </li><?php endif; endforeach; endif; ?>
                 <div class="cb"></div>
                 </ul>
                 <?php if((strpos($attr['attribute_name'],'尺码') !== false) || (strpos(strtoupper($attr['attribute_name']),'SIZE') !==false)){ ?>
                     <a href="javascript:void(0)" class="col2ab cmznn fr"><?php echo isL(L('SizeDescription'),'尺码指南');?></a>
                 <?php } ?>    
                 <div class="cb pb10"></div>
                 </div>
                 
             </div><!--尺码 end--><?php endforeach; endif; ?>  
            
            <!---->
            <div class="tccmbg">
             <div class="tccm"><span class="close cmclose" style="right:5px; top:5px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
             <img src="__HOME__/images/1123a.jpg" alt=""/>
             </div>
            </div>
            <!--尺码指南 end-->
             <div class="splb">
             <h1 <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>class="active"<?php endif; ?>><?php echo isL(L('Price'),'价格');?> <span class="glyphs icon-more facet-icon fr"></span></h1>
             <ul class="splbul" <?php if($_GET['min'] > 0 AND $_GET['max'] > 0): ?>style="display:block;"<?php endif; ?>>
             <?php if($_GET['min'] && $_GET['max']){ ?>
                <li class="clear" onclick="clearPrice()"><?php echo isL(L('Clear'),'清除');?></li>
             <?php } ?>
              <li>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left"><input type="text"  name="min" value="<?php echo htmlspecialchars($_GET['min']);?>" class="jiagel_input" 
                onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('FloorPrice'),'最低价格');?>"/></div>
               </div>
               <div class="jiagel">
                <span>¥</span>
                <div style="width:82%; float:left"><input type="text" name="max" value="<?php echo htmlspecialchars($_GET['max']);?>" class="jiagel_input" 
                onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9\.]/g,'')" placeholder="<?php echo isL(L('CeilingPrice'),'最高价格');?>"/></div>
               </div>
               <div class="cb"></div>
               
               <?php if($prcieError AND !$goods): ?><!--<p class="tsy2" style="display:block;"><?php echo ($prcieError); ?></p>--><?php endif; ?>
                <button type="submit" class="button-offWhite mt10" onclick="retrieval(false)">
                        <span class="fl"><?php echo isL(L('Determine'),'确定');?></span>
                        <span class="glyphs icon-thinArrow fr"></span>
                </button>
              </li> 
         </ul>
         </div><!--颜色 end-->
    </div><!--shxlm end-->
    <div class="cb xianl"></div>
  </div><!--shxl end-->
<?php } ?>

<div class="shxr">
 <div class="txtms"><!--<?php echo isL(L('Luxurybrands'),'奢侈品牌：<b>来自顶尖设计师品牌</b>');?>--><?php echo isL(L('TipsThisSite'),'温馨提示：本网站仅支持自助购物');?></div>
 <div class="zxfbrb"><!---显示4行-->
 <div class="curtain"></div>
   <ul>
        <?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span add-url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" del-url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy jrxynow" onclick="coll_and_cac(this)" id="<?php echo ($g['id']); ?>"><span class="fa fa-heart"></span></span>
               <?php else: ?>
                   <span add-url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" del-url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy" onclick="coll_and_cac(this)" style="cursor:pointer" id="<?php echo ($g['id']); ?>"><span class="fa fa-heart-o"></span></span><?php endif; ?>
              
              <a href="javascript:void(0)" class="zxfbbox">
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
            </a>  
             <div class="zxfbrbb">
             <?php if($g['stock'] <= 0): ?><span class="sold">sold out</span><?php endif; ?>
              <p><b>YOUDI WU</b></p>
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p class="pt5">￥ <?php echo ($g['goods_retail_price']); ?></p>
              <!--<p class="pt5 colf00">SOLD OUT</p>产品没货时--->
             </div>
            </li><?php endforeach; endif; ?>
        <!--<?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="jrxy jrxynow" onclick="coll_and_cac(this)"><span class="glyphs icon-heart"></span></span>
               <?php else: ?>
                   <span url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" class="jrxy" onclick="coll_and_cac(this)" style="cursor:pointer"><span class="glyphs icon-heart"></span></span><?php endif; ?>
              
                
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
             <div class="zxfbrbb">
              <p><b>YOUDI WU</b></p>
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p class="pt5">￥ <?php echo ($g['goods_retail_price']); ?></p>
             </div>
            </li><?php endforeach; endif; ?>-->
        <div class="cb"></div>
   </ul>
   
  </div><!--zxfbrb end-->
   <div class="cb pb15"></div>
    <?php if(is_mobile()): else: ?>
  <div class="fabutl" style="margin-top:8px">
      <b class="mr5"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?> >60</a>
  </div><?php endif; ?>
  <div class="fabutrr">
   <ul class="to-page-ul">
       <?php if($currentPage <= 1): ?><li><a class="disable"><span class="glyphs icon-pageFirst"></span></a></li>
            <li><a class="disable"><span class="glyphs icon-prev"></span></a></li>
        <?php else: ?>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,1);?>" onclick="return ajaxTo(this)"><span class="glyphs icon-pageFirst"></span></a></li>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,'-');?>" onclick="return ajaxTo(this)"><span class="glyphs icon-prev"></span></a></li><?php endif; ?>
        <li class="txt"><span><?php echo ($currentPage); ?></span> of <span><?php echo ($page_sze); ?></span></li>
        <?php if($currentPage >= $pageNumShown): ?><li><a class="disable"><span class="glyphs icon-next"></span></a></li>
             <li><a class="disable"><span class="glyphs icon-pageLast"></span></a></li>   
        <?php else: ?>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,'+');?>" onclick="return ajaxTo(this)"><span class="glyphs icon-next"></span></a></li>
            <li><a href="<?php echo go_page($_URL_,$pageNumShown,$pageNumShown);?>" onclick="return ajaxTo(this)"><span class="glyphs icon-pageLast"></span></a></li><?php endif; ?>
   </ul>
  </div>
  <?php if(is_mobile()): ?><div class="xshib">
  <div class="fabutl"><b class="mr5"><?php echo isL(L('View'),'显示');?>：</b><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>30));?>" <?php if($_GET['page_size'] == 30 OR !$_GET['page_size']): ?>class="now"<?php endif; ?>>30</a><a onclick="return ajaxTo(this)" href="<?php echo condition_url($_URL_,array('page_size'=>60));?>" <?php if($_GET['page_size'] == 60): ?>class="now"<?php endif; ?>>60</a></div><!--fabutl end-->
  <span class="fhdb fr"><span class="icon19"><span class="glyphs icon-arrowUp" style="line-height:30px;"></span></span>
  <div class="cb"></div>
 </div>
 <?php else: endif; ?>
  
  <?php if(is_mobile()): else: ?>
  <span class="fhdb fr mr10" style="margin-top:2px;"><span class="icon19"><span class="glyphs icon-arrowUp" style="line-height:30px;"></span></span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span><?php endif; ?>
  <div class="cb  xian"></div>
</div><!--shxr end-->
<div class="cb pv20"></div>
</div>
<!--
<div class="zxfbt">
   <?php echo replace_isl('CateCount','项',$cateCount);?>
    <div class="page fr">
        <?php echo ($page); ?>
        </div><div class="cb"></div>
    </div>-->
  <!--  
 <div class="zxfbl zxfblmin">
  <h1 class="fhpx"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?></h1>
  <h1><?php echo isL(L('Categories'),'产品分类');?><span class="zxfblz zxfblf"></span></h1>
  <ul>
      <?php if(is_array($cateList)): foreach($cateList as $key=>$c): ?><li>
               <a href="<?php echo U('Category/index',array('html'=>$c['id']));?>" class="not-now <?php if($_GET['html'] == $c['id']): ?>active<?php endif; ?>">
                   <span></span><?php echo ($c['name']); ?>
               </a>
          </li><?php endforeach; endif; ?>
  </ul>
 </div><!--zxfbl end-->
 <!--
 <div class="zxfbr">
  <div class="zxfbrt">
      <span class="ph15 col222"><?php echo isL(L('ComprehensiveRanking'),'综合排序');?></span>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'click_count','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('popularity'),'人气');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'create_time','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('New'),'新品');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'goods_retail_price','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('InfoPrice'),'价格');?></a>
      <a href="<?php echo U('Category/index',array('wishlist'=>'condition'));?>" class="icon28 mh15"><?php echo isL(L('Wishlist'),'愿望单');?></a>
      <a href="<?php echo condition_url($_URL_,array('order_type'=>'volume','order'=>$orders));?>" class="icon28 mh15"><?php echo isL(L('SalesVolume'),'销量');?></a>
  </div>
  <div class="zxfbrb">
   <ul>
        <?php if(is_array($goods)): foreach($goods as $key=>$g): ?><li>
               <?php if(in_array($g['id'],$goodsIds)): ?><span url="<?php echo U("Cookie/deleteDoodsCollect",array('id'=>$g['id']));?>" class="icon29 icon29now" onclick="coll_and_cac(this)" style="cursor:pointer"></span>
               <?php else: ?>
                   <span url="<?php echo U("Cookie/goodsCollect",array('id'=>$g['id']));?>" class="icon29" onclick="coll_and_cac(this)" style="cursor:pointer"></span><?php endif; ?>
                
             <div class="zxfbrbt">
                 <table width="100%" height="100%">
                     <tr>
                         <td align="center" valign="middle" width="100%" height="100%">
                             <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb']); ?>" alt=""/></a>
                         </td>
                      </tr>
                  </table>
             </div>
             <div class="zxfbrbtb">
              
                  <table width="100%" height="100%">
                      <tr>
                          <td align="center" valign="middle" width="100%" height="100%">
                              <a href="<?php echo U('Goods/index',array('html'=>$g['id']));?>" target="_blank"><img src="__ROOT__/<?php echo ($g['goods_thumb2']); ?>" alt=""/></a>
                          </td>
                      </tr>
                  </table>
            </div>
             <div class="zxfbrbb">
              <p><?php echo (sub_str(strip_tags($g['goods_name']),12)); ?></p>
              <p><b>￥ <?php echo ($g['goods_retail_price']); ?></b></p>
             </div>
            </li><?php endforeach; endif; ?>
        <div class="cb"></div>
   </ul>
   
  </div><!--zxfbrb end-->
  <!--<div class="pv20 tc jzgd zxfbrbjzgd"><span onClick="mobile.clickPage(this,'#ajax-div')"><?php echo isL(L('LoadMore'),'加载更多');?></span></div>
  <div class="pv20 tl"><span class="fhdb"><span class="icon19">&nbsp;</span><span class="pl10"><?php echo isL(L('ReturnToTheTop'),'返回顶部');?></span></span></div>
 </div><!--zxfbr end-->
 
 
 <!---->
    <div class="tccmbg">
     <div class="tccm"><span class="close cmclose" style="right:5px; top:5px;"><span class="glyphs icon-close" style="line-height:30px;"></span></span>
     <img src="__HOME__/images/1123a.jpg" alt=""/>
     </div>
    </div>
    <!--尺码指南 end-->
<script type="text/javascript">
	/**
	 *THIS   this
	 * totalPages  总页数
	 * addEnd      追加内容节点
	 * url               请求地址
	 *  */
	mobile={};
	mobile.clickPage=function(THIS,addEnd){    
		var _this=this;
		var totalPages=parseInt("<?php echo ($pageNumShown); ?>");
		var page="<?php echo ($_GET['p']); ?>";
		page=page ? (parseInt(page)) + 1 : 2;
		var url="<?php echo U('Category/index','','');?>?order_type=<?php echo ($_GET['order_type']); ?>&order=<?php echo ($_GET['order']); ?>&keywords=<?php echo ($_GET['keywords']); ?>&wishlist=<?php echo ($_GET['wishlist']); ?>&p="+page;

		if(page >totalPages){
			$(THIS).attr('disabled',true).text("<?php echo isL(L('LoadingCompleted'),'已全部加载完毕');?>");
			return;
		}
		this.run=function(){
			 if(page <= totalPages){  
				if($(THIS).attr('ajax') == 'ok') return;              
				 _this.getPage();
			 }else{
				 $(THIS).attr('disabled',true).val(window.LoadingCompleted);
			 }             
		};  
		this.getPage=function(){
			  $.ajax({
				 type:'get',
				 dataType: "text",
				 url:url,
				 success:function(response,status,xhr){
					 $(THIS).attr('ajax','').attr('disabled',false).val('Load More');
					 $(addEnd).html(response);
				  },
				  beforeSend:function(){
					  $(THIS).attr('ajax','ok').attr('disabled',true).val(window.Loading);
				  },         
				 error:function(xhr,errorText,errorType){
					 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
				 }
			 });              
		};  
		//运行
		_this.run();         
	};      
</script> 
 <script>
  $(function(){
	  $(".cmznn").bind('click', function(){
      $(".tccmbg").fadeIn("fast",function(){
		  $(".tccm").slideDown()
		  })
});
	 $(".cmclose").bind('click', function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });
		$(document).on('click',".tccmbg", function(){
      $(".tccm").slideUp("fast",function(){
		  $(".tccmbg").fadeOut()
		  })
		  });  
		  
	  })
 </script>
<style type="text/css">
.fljg{display:block !important;padding-bottom:10px !important;}
</style>