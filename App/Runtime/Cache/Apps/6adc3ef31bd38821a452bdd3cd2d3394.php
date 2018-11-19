<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="__J_UI__/js/DialogAutoSize.js"></script>
<form id="pagerForm" method="post" action="<?php echo ($url); ?>">
    <input type="hidden" name="status" value="${param.status}">
    <input type="hidden" name="keywords" value="${param.keywords}" />
    <input type="hidden" name="pageNum" value="1" />
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>" />
    <input type="hidden" name="orderField" value="${param.orderField}" />
    <input type="hidden" name="order" value="<?php if($order): echo ($order); else: ?>DESC<?php endif; ?>" /><!--默认排序-->
    <input type="hidden" name="orderPage" value="<?php echo ($currentPage); ?>" /><!--排序当前页-->
    <input type="hidden" name="orderFieldGet" value="<?php echo ($orderFieldGet); ?>" /><!--排序数据库字段名称-->
    <input type="hidden" name="orderGet" value="<?php echo ($orderGet); ?>" /><!--保存已选择的排序方式 ASC 或 DESC-->   
    <input type="hidden" name="files_id" value="<?php echo ($fn['files_id']); ?>" />
</form>

<div class="pageHeader">   
        
          <form onsubmit="return navTabSearch(this);" action="" method="post">  
            <div class="searchBar">
                <table class="searchContent">
                    <tr>
                        <td>
                          输入关键字：<input type="text" name="<?php echo pfix('titletext');?>" value="<?php echo ($searchVal); ?>"/>
                        </td>
                        <?php if(in_array(13,$fn['article_fns'])){ ?>
                            <td>
                                <select name="cate_id" >
                                   <option value="">--全部分类--</option>
                                   <?php if(is_array($fileChild)): foreach($fileChild as $key=>$fc): ?><option value="<?php echo ($fc['files_id']); ?>" <?php if($_REQUEST['cate_id'] == $fc['files_id']): ?>selected="selected"<?php endif; ?>>
                                             <?php echo ($fc['files_name']); ?>
                                        </option><?php endforeach; endif; ?>
                                </select>
                            </td>
                        <?php } ?>
                        <td>
                            <div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div>
                        </td>     
                    </tr>
                </table>
            </div>
         </form>    
     
</div>

<div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if(access('ExtendsArticle/extendsAddSave')): if(is_channel($fn['files_id'],1) && in_array(6,$fn['article_fns'])){ ?>
                      <li>
                              <a class="add auto-size" 
                              href="<?php echo U(APP_APPS.'/ExtendsArticle/extendsAddSave','','');?>/files_id/<?php echo ($fn['files_id']); ?>" 
                              target="dialog" mask="true"  title="添加"><span>添加</span></a>
                       </li>
                  <?php } endif; ?>
    
                <?php if(access('ExtendsArticle/extendsArticleDelete')): if(is_channel($fn['files_id'],3) && in_array(8,$fn['article_fns'])){ ?>
                      <li>
                           <a title="操作不可恢复确定执行本次操作？" 
                           target="selectedTodo" rel="ids" postType="string" 
                           href="<?php echo U(APP_APPS.'/ExtendsArticle/extendsArticleDelete','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/files_id/<?php echo ($fn['files_id']); ?>"
                           class="delete"><span>删除</span></a>
                      </li>
                 <?php } endif; ?>
                <li class="line">line</li>
            </ul>
        </div>
        <table class="list" width="100%" layoutH="90"> 
        <thead>
            <tr>
                <th width="2%"><input type="checkbox" group="ids"  class="checkboxCtrl"></th>
                <th  orderfield="id"  class="<?php if($id == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">编号</th>
                <th orderfield="<?php echo pfix('titletext');?>"  class="<?php if(pfix("titletext") == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">文档标题</th>
                <th >文档类别</th>    
                <?php if(in_array(21,$fn['article_fns'])){ ?>
                    <th orderfield="author"  class="<?php if($author == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">标签</th>
                <?php } ?>                   
                <?php if(in_array(12,$fn['article_fns'])){ ?>
                    <th orderfield="author"  class="<?php if($author == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">文档作者</th>
                <?php } ?>
                <?php if(in_array(10,$fn['article_fns'])){ ?>
                   <th orderfield="is_recommend"  class="<?php if($is_recommend == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否推荐</th>
                <?php } ?>
                <?php if(in_array(11,$fn['article_fns'])){ ?>
                    <th orderfield="general"  class="<?php if($general == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">顶置</th>
                <?php } ?>      
                <?php if(in_array(19,$fn['article_fns'])){ ?>
                    <th orderfield="is_charge"  class="<?php if($is_charge == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">收费浏览</th>
                <?php } ?>                             
                <th orderfield="is_show"  class="<?php if($is_show == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否显示</th>
                <?php if(in_array(9,$fn['article_fns'])){ ?>
                    <th orderfield="orders"  class="<?php if($orders == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">排序</th>
                 <?php } ?>
                <th orderfield="addtime"  class="<?php if($addtime == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">创建时间</th>
                <th>操作</th>
            </tr>           
        </thead>
        <tbody>
           <?php $YOU_LABEL=C('YOU_LABEL'); ?>
           <?php if(is_array($Article)): $i = 0; $__LIST__ = $Article;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$V): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($V["id"]); ?>">
                <td><input name="ids" value="<?php echo ($V["id"]); ?>" type="checkbox"  <?php if(!is_channel($V['files_id'],3)){ ?>disabled="disabled"<?php } ?>></td>
                <td><?php echo ($V["id"]); ?></td>
                <td><?php echo (sub_str(strip_tags($V[pfix('titletext')]),25)); ?></td>
                <td><?php echo ($V[pfix('files_name')]); ?></td>
                <?php if(in_array(21,$fn['article_fns'])){ ?>
                   <td><?php echo ($YOU_LABEL[$V['attr']]['cn_name']); ?></td>
                <?php } ?>
                
                <?php if(in_array(12,$fn['article_fns'])){ ?>
                    <td><?php echo ($V[pfix('author')]); ?></td>
                <?php } ?>
                
                
                <?php if(in_array(10,$fn['article_fns'])){ ?>
                    <td><?php echo (tpl_yes_no($V["is_recommend"],'article','is_recommend',$V['id'],'id','ExtendsArticle/extendsaddSaveForm')); ?></td>
                 <?php } ?>
                 
                
                <?php if(in_array(11,$fn['article_fns'])){ ?>
                    <td><?php echo (tpl_yes_no($V["general"],'article','general',$V['id'],'id','ExtendsArticle/extendsaddSaveForm')); ?></td>
                 <?php } ?>    

                
                <?php if(in_array(19,$fn['article_fns'])){ ?>
                    <td><?php echo (tpl_yes_no($V["is_charge"],'article','is_charge',$V['id'],'id','ExtendsArticle/extendsaddSaveForm')); ?></td>
                 <?php } ?>  
                                               
                <td><?php echo (tpl_yes_no($V["is_show"],'article','is_show',$V['id'],'id','ExtendsArticle/extendsaddSaveForm')); ?></td>
                
                <?php if(in_array(9,$fn['article_fns'])){ ?>
                    <td><?php echo (tpl_modify_oen($V["orders"],'article','orders',$V['id'],'id','ExtendsArticle/extendsaddSaveForm')); ?></td>
                <?php } ?> 
                
                <td><?php echo (date('Y-m-d H:i',$V["addtime"])); ?> </td>
                
                <td>  
                  <?php if(in_array(22,$fn['article_fns'])): echo (tpl_action($action,'NAVTAB','NewComment/articleComList','article_id/'.$V["id"],$V[pfix('titletext')].'[评论]','icon_edit.gif','icon_edit_not.gif','','','评论('.$V['comment'].')')); endif; ?>
                  <?php if(in_array(23,$fn['article_fns'])): ?>
                      <?php echo (tpl_action($action,'NAVTAB','NewComment/replyList2','article_id/'.$V["id"],$V[pfix('titletext')].'[评论]','icon_edit.gif','icon_edit_not.gif','','','评论('.$V['comment'].')')); endif; ?>                  
                  <?php if(is_channel($fn['files_id'],1) AND in_array(7,$fn['article_fns'])): echo (tpl_action($action,'dialog','ExtendsArticle/extendsAddSave','files_id/'.$fn['files_id'].'/id/'.$V["id"],'修改（'.strip_tags($V[pfix('titletext')]).'）','icon_edit.gif','icon_edit_not.gif')); ?> <!--修改按钮--><?php endif; ?>  
                  <?php if(is_channel($fn['files_id'],3) AND in_array(8,$fn['article_fns'])): echo (tpl_action($action,'ajaxTodo','ExtendsArticle/extendsarticleDelete','files_id/'.$fn['files_id'].'/ids/'.$V["id"],'确定执行删除('.strip_tags($V[pfix('titletext')]).')？','icon_drop.gif','icon_drop_not.gif')); ?>   <!--删除按钮--><?php endif; ?>                    
                </td>
                
            </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
        </tbody>
    </table>
    
    <div class="panelBar">
        <div class="pages">
            <span>共 <?php echo ($totalCount); ?> 条数据 <b style="color:red;"><?php echo ($currentPage); ?></b>/<?php echo ($pageNumShown); ?> 页</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="18" currentPage="<?php echo ($currentPage); ?>"></div>
    </div>
</div>