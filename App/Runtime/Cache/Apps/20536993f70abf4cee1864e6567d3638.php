<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
div{
	position:relative;
}
</style>
<div class="pageContent">
<div class="tabs" currentindex="1" eventtype="click">
    <div class="tabsHeader">
        <div class="tabsHeaderContent">
            <ul>
                <li></li>
               <?php if(is_array($pid)): foreach($pid as $k=>$p): ?><li class="selected"><a href="javascript:;"><span><?php echo ($p["configname"]); ?></span></a></li><?php endforeach; endif; ?>
            </ul>
        </div>
    </div>
    <form method="post" action="<?php echo U(APP_APPS.'/WebConfig/configForm','','');?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="tabsContent" style="background:#fff;" layoutH="70">
             <div inited="1000" style="display: block;"></div>
           <?php if(is_array($pid)): foreach($pid as $k=>$p): ?><div inited="1000" style="display: block;">
                               
                 <?php if(is_array($valueAll)): foreach($valueAll as $key=>$value): if($value["parent_id"] == $p["id"]): if($value["type"] == "input"): ?><div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                            <label style="display: block;width:400px;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                            <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                            <input type="text" size="70"  name="<?php echo ($value["code"]); ?>" value="<?php echo ($value["value"]); ?>" class="<?php echo ($value['chk_js']); ?>"/>
                        </div>
                        <?php elseif($value["type"] == "password"): ?>
                        <div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                            <label style="display: block;width:400px;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                            <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                            <input type="password" size="70"  name="<?php echo ($value["code"]); ?>" value="<?php echo ($value["value"]); ?>" class="<?php echo ($value['chk_js']); ?>"/>
                        </div>                    
                        <?php elseif($value["type"] == "textarea"): ?>
                        <div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                            <label style="display: block;width:100%;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                            <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                            <textarea name="<?php echo ($value["code"]); ?>" class="<?php echo ($value['chk_js']); ?> textInput" cols="70" rows="10" style="resize:none;"><?php echo ($value['value']); ?></textarea>
                        </div>
                        <?php elseif($value["type"] == "file"): ?>
                         <div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                            <label style="display: block;width:200px;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                            <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                            <input type="text" name="<?php echo ($value["code"]); ?>" id="d_thumb<?php echo ($value["id"]); ?>" value="<?php echo ($value["value"]); ?>" size="50" readonly="readonly"/> <input type="file" value="上传" size="20" name="file<?php echo ($value["id"]); ?>" id="fileToUpload<?php echo ($value["id"]); ?>"onchange="ajaxFileUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'','width'=>'','height'=>'',remove=>''));?>','fileToUpload<?php echo ($value["id"]); ?>','img_show<?php echo ($value["id"]); ?>','d_thumb<?php echo ($value["id"]); ?>','','a-show<?php echo ($value["id"]); ?>','__ROOT__','imgloads<?php echo ($value["id"]); ?>');"/>
                            <p><img src="__ROOT__/<?php echo ($value["value"]); ?>" width="100" height="70" id="img_show<?php echo ($value["id"]); ?>"/></p>
                        </div>  
                        <?php elseif($value["type"] == "forRadio"): ?>  
                               <div style="width:49%;float:left;padding-left:10px;margin:5px 0;">  
                              <label style="display: block;width:500px;height:20px;line-height:20px;margin-bottom:3px;"><strong><?php echo ($value['configname']); ?>：</strong></label>    
                              <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                              <input type="radio" name="<?php echo ($value["code"]); ?>" value="1" <?php if($value["value"] == 1): ?>checked='checked'<?php endif; ?>/>所有用户
                              <input type="radio" name="<?php echo ($value["code"]); ?>" value="2" <?php if($value["value"] == 2): ?>checked='checked'<?php endif; ?>/>仅登录用户
                              <input type="radio" name="<?php echo ($value["code"]); ?>" value="3" <?php if($value["value"] == 3): ?>checked='checked'<?php endif; ?>/>有过一次以上购买行为用户
                              <input type="radio" name="<?php echo ($value["code"]); ?>" value="4" <?php if($value["value"] == 4): ?>checked='checked'<?php endif; ?>/>仅购买过该商品用户
                              </div>                       
                        <?php elseif($value["type"] == "radio"): ?>  
                               <?php if(strtoupper($value['code']) == 'WATERMARK_POAITION' ): ?><div style="width:60%;float:left;padding-left:10px;margin:5px 0;">  
                                      <label style="display: block;width:200px;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>    
                                      <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="1" <?php if($value["value"] == 1): ?>checked='checked'<?php endif; ?>/>左上角
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="2" <?php if($value["value"] == 2): ?>checked='checked'<?php endif; ?>/>上居中
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="3" <?php if($value["value"] == 3): ?>checked='checked'<?php endif; ?>/>右上角
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="4" <?php if($value["value"] == 4): ?>checked='checked'<?php endif; ?>/>左居中
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="5" <?php if($value["value"] == 5): ?>checked='checked'<?php endif; ?>/>居中
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="6" <?php if($value["value"] == 6): ?>checked='checked'<?php endif; ?>/>右居中
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="7" <?php if($value["value"] == 7): ?>checked='checked'<?php endif; ?>/>左下角
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="8" <?php if($value["value"] == 8): ?>checked='checked'<?php endif; ?>/>下居中
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="9" <?php if($value["value"] == 9): ?>checked='checked'<?php endif; ?>/>右下角
                                      </div>
                              <?php elseif(strtoupper($value['code']) == 'REDUCING_INVENTORY' ): ?>    
                                    <div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                                      <label style="display: block;width:200px;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                                      <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="1" <?php if($value["value"] == 1): ?>checked='checked'<?php endif; ?>/>下订单时
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="0" <?php if($value["value"] == 0): ?>checked='checked'<?php endif; ?>/>发货时
                                     </div>                                        
                               <?php else: ?>   
                                    <div style="width:49%;float:left;padding-left:10px;margin:5px 0;"> 
                                      <label style="display: block;width:100%;height:20px;line-height:20px;"><strong><?php echo ($value['configname']); ?>：</strong></label>
                                      <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="1" <?php if($value["value"] == 1): ?>checked='checked'<?php endif; ?>/>是
                                      <input type="radio" name="<?php echo ($value["code"]); ?>" value="0" <?php if($value["value"] == 0): ?>checked='checked'<?php endif; ?>/>否
                                     </div><?php endif; ?>
                        <?php elseif($value['type'] == 'editor' ): ?>            
                        <div style="width:100%;clear:both;">
                            <div style="height: 40px;line-height:40px;font-weight:bold;text-indent:5px;
                            margin-top: 20px;font-size: 24px;background: #F0F0EE;border:1px solid #CCCCCC;border-bottom: none;width: 100%;">
                                <?php echo ($value['configname']); ?>
                            </div>
                            <input type="hidden" value="<?php echo ($value["id"]); ?>" name="id[]"/>
                            <textarea name="<?php echo ($value["code"]); ?>" style="height: 300px;" 
                            class="kindeditor"  up-url="<?php echo U(APP_APPS."/UploadFile/upload",'','');?>?dwz=ok" space-url=""><?php echo (stripcslashes($value["value"])); ?></textarea>
                        </div><?php endif; endif; endforeach; endif; ?>                    
               
            </div><?php endforeach; endif; ?>
            
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