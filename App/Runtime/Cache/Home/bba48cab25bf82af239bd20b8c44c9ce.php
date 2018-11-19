<?php if (!defined('THINK_PATH')) exit(); if(is_array($result)): foreach($result as $key=>$res): ?><li>
         <div class="zxlbli">
          <div class="zxlblil">
          <a href="<?php echo U('Article/content',array('html'=>$res['id']));?>" title=""><img src="__ROOT__/<?php echo ($res['thumb']); ?>" alt=""/></a>
          <span class="zixunbt"><?php echo (sub_str(strip_tags($res['titletext']))); ?></span>
          </div>
          <div class="zxlblir">
           <p class="zxlblirt f14"><?php echo date('Y.m.d',$res['addtime']);?></p>
           <a href="<?php echo U('Article/content',array('html'=>$res['id']));?>"><h1 class="f28"><?php echo (sub_str(strip_tags($res['titletext']),20)); ?></h1></a>
           <p class="zxlblirb f14"><!--By <span class="col222">Kenneth Deng</span>--><?php echo ($res['author']); ?></p>
           <p class="zxlblirbb f15"><?php echo (sub_str(strip_tags($res['bewrite']),80)); ?></p>
           <div class="zxlblirbbb">
           <?php if($res['count_click'] <= C('LIMIT_SHOW1')): ?><span class="fa fa-eye vam"></span><span class="col999 vam ml5 mr10"><?php echo ($res['count_click']); ?> Read</span>
           <?php elseif($res['count_click'] > C('LIMIT_SHOW1') AND $res['count_click'] <= C('LIMIT_SHOW3')): ?>
             <span class="fa fa-eye vam" style=" color:#ffb400"></span><span class="col999 vam ml5 mr10"><?php echo ($res['count_click']); ?> Read1</span>
           <?php elseif($res['count_click'] >= C('LIMIT_SHOW3')): ?>
             <span class="fa fa-eye vam" style=" color:#c9140a"></span><span class="col999 vam ml5 mr10"><?php echo ($res['count_click']); ?> Read2</span><?php endif; ?>
           <span class="fa fa-commenting f13 vam"></span><span class="col999 vam ml5"><?php echo ($res['comment']); ?> Comments</span>
           </div>
          </div>
          <div class="cb"></div>
         </div>
        
         
        </li><?php endforeach; endif; ?>
<div class="cb"></div>
 <script>

     $(function(){

		$(".zxlblil").mouseenter(function(e){		
			$(this).find(".zixunbt").fadeIn()
			num=$(this).width()/2
			$(this).find(".zixunbt").css({top:event.offsetY,left:event.offsetX-num-10})
			}) 
		$(".zxlblil").mouseleave(function(e){		
			$(this).find(".zixunbt").fadeOut()
			
			}) 	
		 
		 })
    </script>