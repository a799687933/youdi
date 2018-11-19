<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="__J_UI__/js/DialogEditorSize.js"></script>
<style type="text/css"> 
.caet-add{
    position: relative;
    top:-1px;
}
</style>
<div class="pageContent">
<div class="tabs" currentindex="1" eventtype="click">
    <div class="tabsHeader">
        <div class="tabsHeaderContent">
            <ul>       
                <li></li>     
                <li><a href="javascript:;"><span>通用信息</span></a></li>
                 <?php if(in_array(1,$filesData['article_fns'])): ?><li ><a href="javascript:;"><span>关联文章</span></a></li><?php endif; ?>  
                <?php if(in_array(2,$filesData['article_fns'])): ?><li><a href="javascript:;"><span>更多参数</span></a></li><?php endif; ?>
                <?php if(in_array(3,$filesData['article_fns'])): ?><li ><a href="javascript:;"><span>文档内容</span></a></li><?php endif; ?>
                <?php if(in_array(4,$filesData['article_fns'])): ?><li ><a href="javascript:;"><span>相册专辑</span></a></li><?php endif; ?>        
                <?php if(in_array($save['id'],array(C('A_6'),C('A_7'),C('A_2')))): ?><li >
                        <a href="javascript:;"><span>右边内容</span></a>                       
                    </li><?php endif; ?> 
            </ul>
        </div>
    </div>
    <form method="post" action="<?php echo U(APP_APPS.'/ExtendsArticle/extendsAddSaveForm','','');?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
       <input type="hidden" name="id" value="<?php echo ($save["id"]); ?>"/>
       <input type="hidden" name="files_id" value="<?php echo ($filesData['files_id']); ?>"/>
       <input type="hidden" name="article_type" value="<?php echo ($filesData['article_type']); ?>"/>
       <input type="hidden" name="files_type" value="<?php echo ($filesData['files_type']); ?>"/>
        <div class="tabsContent" style="background:#fff;" layoutH="70">
            <div inited="1000" style="display: block;"></div>
           
            <div inited="1000" style="display: block;">
                <table cellpadding="0" cellspacing="0" class="mytable">
                
                   <?php if($child): ?><tr>
                          <td  style="text-align:right;">选择分类：</td>
                          <td>
                                <select name="cate_id">
                                     <?php if(is_array($child)): foreach($child as $key=>$ch): ?><option value="<?php echo ($ch['files_id']); ?>" <?php if($save['files_id'] == $ch['files_id']): ?>selected="selected"<?php endif; ?>><?php echo ($ch['files_name']); ?></option><?php endforeach; endif; ?>
                                </select>
                          </td>
                    </tr><?php endif; ?>   
                                
                   <?php if(is_array($titletexts)): foreach($titletexts as $key=>$t): ?><tr>
                          <td  style="text-align:right;">(<?php echo ($t['info']); ?>)文档标题：</td>
                          <td>
                                  <input type="text" name="<?php echo ($t['field']); ?>" 
                                              value="<?php echo (stripcslashes($save[$t['field']])); ?>" maxlength="250" 
                                  class="required" size="50" style="margin:3px 0;"/>
                          </td>
                    </tr><?php endforeach; endif; ?> 
                  
               <?php if(in_array(5,$filesData['article_fns'])): ?><tr>
                      <td  style="text-align:right;">价格：</td>
                      <td>
                              <input type="text" name="price" 
                                          value="<?php echo (stripcslashes($save['price'])); ?>" maxlength="250" 
                              class="required number" size="50" style="margin:3px 0;"/>
                      </td>
                </tr><?php endif; ?>
                  
              <?php if(is_array($keywordss)): foreach($keywordss as $key=>$t): ?><tr>
                      <td  style="text-align:right;">(<?php echo ($t['info']); ?>)文档关键字：</td>
                      <td>
                          <input type="text" name="<?php echo ($t['field']); ?>" 
                                      value="<?php echo (stripcslashes($save[$t['field']])); ?>" 
                          maxlength="250" class="" size="50" style="margin:3px 0;"/>
                      </td>
                </tr><?php endforeach; endif; ?> 
               
             <?php if(in_array(12,$filesData['article_fns'])): if(is_array($authors)): foreach($authors as $key=>$t): ?><tr>
                         <td  style="text-align:right;">(<?php echo ($t['info']); ?>)文档作者：</td>
                         <td>
                                <input type="text" name="<?php echo ($t['field']); ?>" 
                                            value="<?php echo (stripcslashes($save[$t['field']])); ?>" 
                                maxlength="250" class="" size="50" style="margin:3px 0;"/>
                         </td>
                   </tr><?php endforeach; endif; endif; ?>
                                           
               <tr>
                     <td  style="text-align:right;">点击数：</td>
                     <td>
                              <input type="text" 
                                value="<?php if($save['count_click']): echo ($save['count_click']); else: ?>100<?php endif; ?>" 
                             name="count_click" maxlength="250" class="digits" size="50" style="margin:10px 0 3px 0;"/>
                     </td>
               </tr>
               
               <?php if(in_array(15,$filesData['article_fns'])): ?><tr>
                      <td  style="text-align:right;">是否新窗口打开：</td>
                      <td>
                           <label>
                              <input type="radio" 
                              value="1" name="is_target" <?php if($save['is_target'] == 1): ?>checked="checked"<?php else: ?>checked="checked"<?php endif; ?>/>是
                          </label>   
                          <label>
                              <input type="radio" name="is_target" value="0" <?php if($save['is_target'] == '0'): ?>checked="checked"<?php endif; ?>/>否
                          </label>
                      </td>
                </tr><?php endif; ?>
               
               <?php if(in_array(14,$filesData['article_fns'])): ?><tr>
                      <td  style="text-align:right;">外链接地址：</td>
                      <td>
                           <input type="text" name="outside_url" value="<?php echo (stripcslashes($save["outside_url"])); ?>" maxlength="250" class="" size="50" style="margin:3px 0;"/>
                      </td>
               </tr><?php endif; ?>
                                  
               <?php if(in_array(20,$filesData['article_fns'])): ?><tr>
                         <td  style="text-align:right;">缩略图：</td>
                         <td>
                            <input type="hidden" name="thumb" id="article_thumb" value="<?php echo ($save["thumb"]); ?>"/>
                            <input name="file2" type="file" id="articleFileToUpload"  
                            onchange="ajaxFileUpload(
                            '<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'','width'=>'','height'=>'','remove'=>'ok'));?>'
                            ,'articleFileToUpload','article_img_show','article_thumb','','ac-show','__ROOT__','article_imgloads');"/> 
                             <?php if($filesData['article_width'] > 0 AND $filesData['article_height'] > 0): ?>图片规格 (<?php echo ($filesData['article_width']); ?> * <?php echo ($filesData['article_height']); ?>)
                            <?php else: ?>
                                图片规格 (不限制)<?php endif; ?>                               
                         </td>
                   </tr>
                   
                   <tr style="<?php if(!$save['thumb']): ?>display:none;<?php endif; ?> " id="article_imgloads">
                            <td  style="text-align:right;"></td>
                            <td style="margin-top:10px;">
                            <img src="__ROOT__/<?php echo ($save["thumb"]); ?>" 
                                     id="article_img_show" width="180"  style="display:inline-block;position:relative;top:5px;" class="ac-show"/>                            
                           </td>
                  </tr><?php endif; ?>     
             
             <?php if(in_array(16,$filesData['article_fns'])): ?><tr>
                        <td  style="text-align:right;">视频分享代码：</td>
                        <td>
                             <textarea class="required" name="video_code" rows="10" cols="100" style="margin:3px 0;"><?php echo ($save["video_code"]); ?></textarea>
                         </td>
                  </tr><?php endif; ?>      
               
              <?php if(in_array(18,$filesData['article_fns'])): ?><tr>
                      <td  style="text-align:right;">上传文件：</td><td>
                        <input type="hidden" name="thumb" id="article_thumb" value="<?php echo ($save["thumb"]); ?>"/>
                        <input name="file2" type="file" id="articleFileToUpload"  
                        onchange="ajaxFileUpload(
                        '<?php echo U(APP_APPS.'/UploadRar/upload',array('thum'=>'ok','width'=>'','height'=>''));?>'
                        ,'articleFileToUpload','article_img_show','article_thumb','','ac-show','__ROOT__','article_imgloads');"/> 
                      </td>
                 </tr>    
                <tr>
                      <td></td>
                      <td style="color:red;">
                            <img src="__SYS_IMG__/notice.gif" style="position: relative;top:3px;"/>
                            上传文件不得大于<strong><?php echo ini_get('upload_max_filesize');?></strong>，文件类型（.rar .xls .doc）
                      </td>
                </tr><?php endif; ?>
              
               <?php if(is_array($bewrites)): foreach($bewrites as $key=>$t): ?><tr>
                      <td  style="text-align:right;">(<?php echo ($t['info']); ?>)描述：</td>
                      <td><textarea name="<?php echo ($t['field']); ?>" rows="10" cols="100" style="margin:3px 0;"><?php echo (stripcslashes($save[$t['field']])); ?></textarea></td>
                </tr><?php endforeach; endif; ?>  
               
               <?php if(in_array(21,$filesData['article_fns'])): ?><tr>
                       <td  style="text-align:right;">标签：</td>
                       <td>
                             <?php $YOU_LABEL=C('YOU_LABEL'); ?>
                             <?php if(is_array($YOU_LABEL)): foreach($YOU_LABEL as $yk=>$y): ?><label>
                               <input type="radio"  value="<?php echo ($yk); ?>" name="attr" <?php if($save['attr'] == $yk): ?>checked="checked"<?php endif; ?>/><?php echo ($y['cn_name']); ?>
                            </label><?php endforeach; endif; ?>
                       </td>
                   </tr><?php endif; ?>

              <?php if(in_array(10,$filesData['article_fns'])): ?><tr>
                       <td  style="text-align:right;">是否推荐：</td>
                       <td>
                            <label>
                               <input type="radio" 
                               value="1" name="is_recommend" <?php if($save['is_recommend'] == 1): ?>checked="checked"<?php endif; ?>/>是
                            </label>
                            <label>   
                               <input type="radio" name="is_recommend" value="0" 
                               <?php if($save['is_recommend'] == '0'): ?>checked="checked"<?php else: ?>checked="checked"<?php endif; ?>/>否
                            </label>   
                       </td>
                   </tr><?php endif; ?>

              <?php if(in_array(11,$filesData['article_fns'])): ?><tr>
                       <td  style="text-align:right;">文章顶置：</td>
                       <td>
                             <label>
                                   <input type="radio" 
                                   value="1" name="general" <?php if($save['general'] == 1): ?>checked="checked"<?php endif; ?>/>是
                             </label>
                             <label>             
                                   <input type="radio" name="general" value="0" 
                                   <?php if($save['general'] == '0'): ?>checked="checked"<?php elseif($save['general'] == ''): ?>checked="checked"<?php endif; ?>/>否
                             </label>  
                       </td>
                   </tr><?php endif; ?>

              <?php if(in_array(19,$filesData['article_fns'])): ?><tr>
                       <td  style="text-align:right;">浏览收费：</td>
                       <td>
                              <label>
                               <input type="radio" 
                               value="1" name="is_charge" 
                               <?php if($save['is_charge'] == 1): ?>checked="checked"<?php elseif($save['is_charge'] != '0'): ?>checked="checked"<?php endif; ?>/>是
                              </label> 
                              <label>
                               <input type="radio" name="is_charge" value="0" 
                               <?php if($save['is_charge'] == '0'): ?>checked="checked"<?php endif; ?>/>否
                             </label>  
                       </td>
                   </tr><?php endif; ?>               
                              
                
                <?php if(is_channel($_GET['files_id'],2)){ ?>
                    <tr>
                        <td  style="text-align:right;">是否审核：</td>
                        <td>
                            <label>
                               <input type="radio" 
                               value="1" name="is_show" <?php if($save['is_show'] == 1): ?>checked="checked"<?php else: ?>checked="checked"<?php endif; ?>/>是
                            </label>
                            <label>   
                               <input type="radio" name="is_show" value="0" <?php if($save['is_show'] == '0'): ?>checked="checked"<?php endif; ?>/>否
                            </label>   
                         </td>
                     </tr>
                 <?php }else{ ?>   
                      <input type="hidden" name="is_show" value="0" /> 
                 <?php } ?>
              
                  
             </table>
       </div>
   

     
   <?php if(in_array(1,$filesData['article_fns'])): ?><div inited="1000" style="display: block;">
          <table cellpadding="0" cellspacing="0" class="tableshide  tableshow5" border="0" style="width:100%;"><!--关联文章satrt-->      
              <tr><td style="height: 10px;"></td><td></td><td></td></tr> 
          <tr> <!-- 文章搜索 SATRT-->
            <td colspan="3" class="tdleft" >
               标题 <input type="text"  id="titletext"  style="margin-bottom:20px;"/>
                <select class="" id="files_id">
                    <option value="0">选择分类</option>
                    <?php if(is_array($relCate)): foreach($relCate as $key=>$s): if($s['files_pid'] > 0): ?><option value="<?php echo ($s["files_id"]); ?>"><?php echo ($s["html"]); echo ($s["files_name"]); ?></option><?php endif; endforeach; endif; ?>
               </select>
              <input type="button" value="检索" onclick="searchArticle('<?php echo U(APP_APPS.'/ExtendsArticle/extendsAddSaveForm',array('rel'=>1),'');?>','article','id','titletext','files_id','selectLeft')" class="upbutton" /></span>
            </td>
          </tr><!-- 文章搜索END -->
          <tr><td style="height: 10px;"></td><td></td><td></td></tr>
          <!-- 文章列表 -->
          
          <tr>
            <th>可选文章</th>
            <th style="text-align:center;">操作</th>
            <th>关联文章</th>
          </tr>
          <tr><td style="height: 10px;"></td><td></td><td></td></tr>
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
              <select name="selectRight[]" size="20" style="width:100%" multiple class="selectRight">
            <?php if(is_array($take)): foreach($take as $key=>$t): ?><option value="<?php echo ($t["id"]); ?>" selected="selected"><?php echo (strip_tags($t[pfix('titletext')])); ?></option><?php endforeach; endif; ?>                      
              </select>
            </td>
           </tr>      
            </table><!--关联文章end-->
   </div><?php endif; ?>
         
         
    
    <?php if(in_array(2,$filesData['article_fns'])): ?><div inited="1000" style="display: block;">
       <table cellpadding="0" cellspacing="0">
           <?php if($save[pfix('parameter')]): if(is_array($save[pfix('parameter')])): foreach($save[pfix('parameter')] as $keyps=>$pr): ?><tr>
                   <td style="padding:5px 0;text-align: right;">
                        <?php if($keyps == 0): ?><a href="javascript:void(0);" onclick="addNodepc(this)">[+]</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" onclick="delNodepc(this)">[-]&nbsp;</a><?php endif; ?>
                           
                           <?php if(is_array($parameterss)): foreach($parameterss as $pkeys=>$par): ?>参数名称(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="<?php echo ($save[$par['field']][$keyps]['par_name']); ?>" name="<?php echo ($par['field']); ?>[]"/>
                              <br/><br/><?php endforeach; endif; ?>
                   </td>
                   <td style="text-align: left;">
                          <?php if(is_array($parameterss)): foreach($parameterss as $key=>$par): ?>&nbsp;参数值(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="<?php echo ($save[$par['field']][$keyps]['par_value']); ?>" name="<?php echo ($par['field']); ?>_val[]"/>
                           <br/><br/><?php endforeach; endif; ?>
                   </td>
               </tr><?php endforeach; endif; ?>
          <?php else: ?>
               <tr>
                   <td style="padding:5px 0;text-align: right;">
                           <a href="javascript:void(0);" onclick="addNodepc(this)">[+]</a>
                           <?php if(is_array($parameterss)): foreach($parameterss as $pkeys=>$par): ?>参数名称(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="" name="<?php echo ($par['field']); ?>[]"/>
                              <br/><br/><?php endforeach; endif; ?>
                   </td>
                   <td style="text-align: left;">
                          <?php if(is_array($parameterss)): foreach($parameterss as $key=>$par): ?>&nbsp;参数值(<?php echo ($par['info']); ?>)&nbsp;<input type="text" value="" name="<?php echo ($par['field']); ?>_val[]"/>
                           <br/><br/><?php endforeach; endif; ?>
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
                str='<tr>'+$(str).html()+'</tr>';
                str=str.replace('addNodepc', 'delNodepc');
                str=str.replace('[+]', '[-]&nbsp;');
                var s=/value=\"(.*?)\"/g ;  
                str=str.replace(s,'value=""');						
               $(node).parents('table').append(str);    
              });
        }
        
        //删除节点
        function delNodepc(_this){
              $(function(){
                $(_this).parent().parent().remove();         
              });
        } 
    </script><?php endif; ?>
       
    
       
   <?php if(in_array(3,$filesData['article_fns'])): ?><div inited="1000" style="display: block;">
       <?php if(is_array($contents)): foreach($contents as $key=>$t): if(C('LANG_SWITCH_ON')): ?><p style="color:#03408b;height:40px;line-height:40px;
               font-weight:bold;border:1px #ccc solid;font-size:16px;
               text-indent:10px;margin-top:20px;background:#F0F0EE;
               border-bottom:none;width:100%">(<?php echo ($t["info"]); ?>)文档内容</p><?php endif; ?>               
        <textarea name="<?php echo ($t["field"]); ?>"  
           class="kindeditor"  up-url="<?php echo U(APP_APPS."/UploadFile/upload",'','');?>?dwz=ok" space-url=""
          ><?php echo (stripcslashes($save[$t['field']])); ?></textarea><?php endforeach; endif; ?>                      
    </div><?php endif; ?>
      
  
   
   <?php if(in_array(4,$filesData['article_fns'])): ?><div inited="1000" style="display: block;">
        <p style="height: 10px;padding-bottom:10px;">
            上传图片: <input name="file3" type="file" id="fileToUpload7" onchange="relatedAlbumUpload('<?php echo U(APP_APPS.'/UploadFile/upload',array('thum'=>'ok','width'=>6000,'height'=>3000,'remove'=>'ok'));?>','fileToUpload7','setimgdiv','__ROOT__');" style="width:70px;">
            规格：1663 × 808
       </p>  
        <div class="divider"></div> 
        <!--这里上传图片-->
        <?php if(is_array($photo)): foreach($photo as $key=>$m): ?><div class="divigm" style="float:left;margin:3px;position:relative;">
                     <span style="display:block;width:200px;background:#000;filter:alpha(opacity=30);-moz-opacity:0.3;-khtml-opacity: 0.3; opacity: 0.5;font-weight:bold;position:absolute;left:0;top:0;">
                         <b class="close_upimg" onclick="delDiv(this);" style="cursor:pointer; float:right;color:red;font-size:16px;">×</b>
                    </span>
                     <a href="__ROOT__/<?php echo ($m["img"]); ?>" target="_blank"><img src="__ROOT__/<?php echo ($m["img"]); ?>" width="200" height="180" style="display:block;"/></a>
                     <input type="hidden" value="<?php echo ($m["img"]); ?>" name="imgAll[]"/>
                     <input type="text" name="title[]"  value="<?php echo ($m["img_bewrite"]); ?>" style="width:197px;height:16px;font-size:12px;border:none;"  placeholder="链接地址"/>
              </div><?php endforeach; endif; ?>                 
       <div id="setimgdiv" style="width:200px;height:180px;float:left;margin:3px;">
           <img src="__PUBLIC__/Images/13221814.gif" style="display:none;padding:80px 0 0 80px;" id="img_related"/>
       </div>
     </div><?php endif; ?>
    

       
   <?php if(in_array($save['id'],array(C('A_6'),C('A_7'),C('A_2')))): ?><div inited="1000" style="display: block;">
       <?php if(is_array($rightContent)): foreach($rightContent as $key=>$t): if(C('LANG_SWITCH_ON')): ?><p style="color:#03408b;height:40px;line-height:40px;
               font-weight:bold;border:1px #ccc solid;font-size:16px;
               text-indent:10px;margin-top:20px;background:#F0F0EE;
               border-bottom:none;width:100%">(<?php echo ($t["info"]); ?>)文档内容</p><?php endif; ?>               
        <textarea name="<?php echo ($t["field"]); ?>"  
           class="kindeditor"  up-url="<?php echo U(APP_APPS."/UploadFile/upload",'','');?>?dwz=ok" space-url=""
          ><?php echo (stripcslashes($save[$t['field']])); ?></textarea><?php endforeach; endif; ?>                      
    </div><?php endif; ?>
               
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