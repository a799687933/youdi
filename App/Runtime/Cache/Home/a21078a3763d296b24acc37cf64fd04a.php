<?php if (!defined('THINK_PATH')) exit();?>
<style type="text/css">

.zxpltext {
float: left;
width: 530px;
height: 35px;
border: none;
resize: none;
color: #000;
font-size: 12px;
line-height: 16px;
font-family: "\5FAE\8F6F\96C5\9ED1";
}
#youdi {
  width: 11%;
  height: 10%;
  border-radius: 0;
  margin-left: 11px;
  margin-top: 4px;
}
#ydhand {
  margin-right: 4px;
  margin-top: 3px;
}
</style>


<?php if(is_array($result)): foreach($result as $reskey=>$res): ?><li>
    <h2 class="zixunt">
        <img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1542375294737&di=cd44d196936ce50906027c88db82e8cd&imgtype=0&src=http%3A%2F%2Fwww.jituwang.com%2Fuploads%2Fallimg%2F151007%2F258199-15100G2513752.jpg" alt="" style="margin-top: 3px;margin-left: 0px;">
      <img id='youdi' src="__HOME__/images/youdi.png"  alt=""/>
        <div class="cb"></div>
     </h2>
    <div class="zxpic zxpic_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>">
     <div class="swiper-wrapper">
     <?php if(is_array($res['album'])): foreach($res['album'] as $key=>$alb): ?><div class="swiper-slide"><img src="__ROOT__/<?php echo ($alb['img']); ?>" alt=""></div><?php endforeach; endif; ?>
   </div>
   <span class="zxpicl fa  fa-caret-left zxpicl_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></span><span class="zxpicr fa  fa-caret-right zxpicr_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></span>
   <div class="zxpicicon"><b></b>HAPPY</div>
    </div><!--zxpic end-->
    
    <div class="zxpicbwrap">
    
    <div class="zxpicb zxpicb_<?php echo ($_GET['p'] ? $_GET['p'] :1); ?>_<?php echo ($reskey); ?>"></div>
    <?php if($memberId > 0): ?><b class="zxdz fa <?php echo ($res['is_like'] ? 'zxdznow fa-heart' : 'fa-heart-o'); ?>" style="margin-left:-1px;"  onClick="praise(this,'<?php echo U('Article/clickLike',array('id'=>$res['id']));?>')"></b>
         <a href="javascript:void(0);" class="zxplun fa fa-commenting-o" style="margin-left: 0px;font-size: 24px;" onClick="positions(this)"></a> 
         <b class="zxsc fa <?php echo ($res['is_colle'] ? 'fa-bookmark' : 'fa-bookmark-o'); ?>" style="margin-top: 1px;margin-left: 0px;font-size: 23px;" onClick="setCollection(this,'<?php echo sign_url(array('id'=>$res['id'],'to_action'=>'Article/index2'),U('Article/setCollection'));?>')"></b>                     
     <?php else: ?>
         <b class="zxdz fa fa-heart-o" onClick="window.location.href='<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>';"></b>
          <a href="<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>" class="zxplun fa fa-commenting-o"></a> 
         <b class="zxsc fa fa-bookmark-o" onClick="window.location.href='<?php echo sign_url(array('url'=>base64_encode(get_url())),U('Login/index'));?>';"></b> <?php endif; ?> 
    </div><!--zxpicbwrap end-->
    <p class="pl15 col333 f12"><span class="paraise_num"><?php echo ($res['like_count']); ?></span>次赞</p>
    <div class="zxfbnr">
      youdi&nbsp;wu 
      <?php if($res['attr']): $YOU_LABEL=C('YOU_LABEL'); ?>
         <span>#<?php echo ($YOU_LABEL[$res['attr']]['cn_name']); ?></span><?php endif; ?>
      <?php echo (strip_tags($res['bewrite'])); ?><b>更多</b>
    </div><!--zxfbnr end-->
    <?php if($memberId > 0): ?> 
        <div class="zxdh list_p">
         <?php $times=0; ?>
         <?php if(is_array($res['reply_arr'])): foreach($res['reply_arr'] as $replyKey=>$reply): $times=$reply['times']; ?>
              <?php if($reply['user_id'] == 0): ?><p style="position:relative;"  class="plneir">
                     <img src="/Public/Home/images/logo.png" width="68" alt="" class="vam">
                     <span class="vam mh5" style="color:#023867;">@<?php echo ($reply['user_name']); ?></span>
                     <span><?php echo ($reply['content']); ?></span>
                     <span class="show_all" onclick="showAlls(this)">显示全部</span>
                   </p>
               <?php else: ?>
                  <p style="position:relative;"  class="plneir">
                     <span class="vam mr5"><?php echo ($reply['user_name']); ?></span><span class="vam" style="color:#023867;">@</span>
                     <img src="/Public/Home/images2/logo.png" width="68" alt="" class="vam mr5">
                     <span><?php echo ($reply['content']); ?></span>
                     <span class="show_all" onclick="showAlls(this)">显示全部</span>
                  </p><?php endif; endforeach; endif; ?>
         </div><!--zxdh end-->
        
             <div class="zxdhb" style="<?php if(!$res['reply_arr']): ?>display:none;<?php endif; ?>">
                <span class="show_time"><?php echo from_time($times,'');?></span><b>全部<span class="show_app_num"><?php echo ($res['comment']); ?></span>条评论</b>
                <div class="cb"></div>
             </div><?php endif; ?>  
     <div class="zxplbox" id="zxpl1">
         <form action="<?php echo sign_url(array('article_id'=>$res['id']),U('Article/commentReply'));?>" onSubmit="return formSend(this)">
              <textarea name="content" class="zxpltext" value="添加评论···" onblur="if(this.value==''){this.value='添加评论···'}" onfocus="if(this.value=='添加评论···'){this.value=''}" v-model="cureInfo.Symptom" id="symptomTxt" oninput="autoTextAreaHeight(this)" ></textarea>
              <div class="zxplboxr">

             <b><img id="ydhand" width="20px" src="__HOME__/images/ydhand.png"  alt=""/></b>
              <input type="submit" value="发送" class="zxplbtn">
          </form>
      </div>
      <div class="cb"></div>
     </div>

   </li><?php endforeach; endif; ?>
 <script>
    function autoTextAreaHeight(o) {
        o.style.height = o.scrollTop + o.scrollHeight + "px";
    }
    $(function () {
        var ele = document.getElementById("symptomTxt");
        autoTextAreaHeight(ele);
    })

     $(function(){
		$(".zxpic .swiper-wrapper").click(function(e){
			$(this).parents(".zxpic").find(".zxpicicon").fadeIn()
			num=$(this).parents(".zxpic").find(".zxpicicon").width()/2
			$(this).parents(".zxpic").find(".zxpicicon").css({top:event.offsetY+10,left:event.offsetX-num-10})
		
			}) 
		 
		 })
    </script>