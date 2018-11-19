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
	<input type="hidden" name="shops_id" value="<?php echo ($_REQUEST['shops_id']); ?>" /><!--商户检索ID-->    
	<input type="hidden" name="action" value="<?php echo ($_REQUEST['action']); ?>" /><!---->    
	<input type="hidden" name="goods_category_id" value="<?php echo ($_REQUEST['goods_category_id']); ?>" /><!---->    
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="<?php echo U(APP_APPS."/Goods/goodsShelvesList","","");?>" method="post">
       <input type="hidden" value="<?php echo ($_REQUEST['type']); ?>" name="type" /> 
       <input type="hidden" value="<?php echo ($_REQUEST['action']); ?>" name="action" /> 
       <input type="hidden" value="<?php echo ($_REQUEST[C('VAR_PAGE')]); ?>" name="<?php echo C('VAR_PAGE');?>" /> 
	   <input type="hidden" name="shops_id" value="<?php echo ($_REQUEST['shops_id']); ?>" /><!--商户检索ID-->
	   <input type="hidden" name="action" value="<?php echo ($_REQUEST['action']); ?>" /><!---->    
    <div class="searchBar">
        <table class="searchContent">
            <tr>
                <td>
                    <select class="combox " name="goods_category_id" ><!--商品分类询查-->
                             <option value="">-商品分类询查-</option>
                              <?php if(is_array($goodCat)): foreach($goodCat as $key=>$C): ?><option value="<?php echo ($C["id"]); ?>" <?php if($C['id'] == $_REQUEST['goods_category_id']): ?>selected="selected"<?php endif; ?>><?php echo ($C["html"]); echo ($C[pfix('name')]); ?></option><?php endforeach; endif; ?>  
                      </select>       
                </td>                                  
               <td>
               <input type="text" name="goods_name" value="<?php echo ($searchVal); ?>"/>
                </td>                
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
          <?php if($_REQUEST['type'] == 2): if(access('Goods/goodsDelete')): ?><li><a title="确定把所选择进行删除？" target="selectedTodo" rel="ids" postType="string" href="<?php echo U(APP_APPS.'/Goods/goodsDelete','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/<?php echo ($types); ?>/action/<?php echo ($_REQUEST['action']); ?>" class="delete"><span>删除</span></a></li><?php endif; ?>  
         <?php else: ?>
           <?php if(access('Goods/goodsAdd')): ?><li><a class="add auto-size" href="<?php echo U(APP_APPS.'/Goods/goodsAdd','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/<?php echo ($types); ?>/action/<?php echo ($_REQUEST['action']); ?>" target="dialog"  mask="true"><span title="添加">添加</span></a></li><?php endif; ?>     
               <?php if(access('Goods/moveRecycleBin')): ?><li><a title="确定把所选择列入回收站？" target="selectedTodo" rel="ids" postType="string" href="<?php echo U(APP_APPS.'/Goods/moveRecycleBin','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/<?php echo ($types); ?>/action/<?php echo ($_REQUEST['action']); ?>" class="delete"><span>加入回收站</span></a></li><?php endif; endif; ?>            
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90"  id="list-table-invet">
        <thead>
            <tr>
                <th width="2%"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
                <th width="3%" orderfield="id"  class="<?php if($id == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">编号</th>
                <th width="22%" orderfield="goods_name"  class="<?php if($goods_name == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">商品名称</th>
                <th orderfield="goods_category_id"  class="<?php if($goods_category_id == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">商品分类</th>     
			    <th orderfield="goods_market_price"  class="<?php if($goods_market_price == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">市场价格</th> 
                <th orderfield="goods_retail_price"  class="<?php if($goods_retail_price == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">价格</th>
                <?php if(C('IS_PROMOTION')): ?><th orderfield="is_promotion"  class="<?php if($is_promotion == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否促销</th><?php endif; ?>
                <th orderfield="shelves"  class="<?php if($shelves == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否上架</th>
                <?php if(C('IS_RECOMMEND')): ?><th orderfield="recommend"  class="<?php if($recommend == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">本店推荐</th><?php endif; ?>
                <?php if(C('IS_GOODS_HOT')): ?><th orderfield="hot"  class="<?php if($hot == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">热销商品</th><?php endif; ?>
			    <?php if(C('IS_GOODS_NEW')): ?><th orderfield="is_new"  class="<?php if($is_new == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否新品</th><?php endif; ?>
                <?php if(C('IS_GOODS_WEEK')): ?><th orderfield="is_week"  class="<?php if($is_week == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">每周优惠</th><?php endif; ?>
                <th orderfield="create_time"  class="<?php if($create_time == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">创建时间</th>
                <th>操作</th>
            </tr>           
            
        </thead>
        <tbody>
            
           <?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$V): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($V["id"]); ?>">
                <td><input name="ids" value="<?php echo ($V["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($V["id"]); ?></td>
                <td><a href="<?php echo U('/Goods/index',array('html'=>'goods_'.$V['id']));?>" target="_blank"><?php echo ($V[pfix('goods_name')]); ?></a></td>
                <td><?php echo ($V[pfix('name')]); ?></td>
				<td><?php echo ($V["goods_market_price"]); ?></td>
                <td><?php echo ($V["goods_retail_price"]); ?></td>
                
                <?php if(C('IS_PROMOTION')): ?><td><?php if($V['is_promotion']): ?><img src="<?php echo C('__IMG__');?>/yes.gif"/><?php else: ?><img src="<?php echo C('__IMG__');?>/no.gif" /><?php endif; ?></td><?php endif; ?>
                
                 <td>
                        <?php if($V['shelves'] == 0): ?><span style="color:red;">已下架</span>
                        <?php elseif($V['shelves'] == 1): ?>
                             <span style="color:green;">已上架</span><?php endif; ?>
                 </td>
                 <?php if(C('IS_RECOMMEND')): ?><td><?php echo (tpl_yes_no($V["recommend"],'goods','recommend',$V['id'],'id','Goods/goodsSendForm')); ?></td><?php endif; ?>   
                 <?php if(C('IS_GOODS_HOT')): ?><td><?php echo (tpl_yes_no($V["hot"],'goods','hot',$V['id'],'id','Goods/goodsSendForm')); ?></td><?php endif; ?>
			     <?php if(C('IS_GOODS_NEW')): ?><td><?php echo (tpl_yes_no($V["is_new"],'goods','is_new',$V['id'],'id','Goods/goodsSendForm')); ?></td><?php endif; ?>   
                 <?php if(C('IS_GOODS_WEEK')): ?><td><?php echo (tpl_yes_no($V["is_week"],'goods','is_week',$V['id'],'id','Goods/goodsSendForm')); ?></td>
                 <if><?php endif; ?>   
                <td><?php echo (date('Y-m-d',$V["create_time"])); ?> </td>
                <td>
               
                <?php if($_REQUEST['type'] == 2): ?><!--回收站列表-->
                   <?php if(access('Goods/moveRecycleBin')): ?><a href="<?php echo U(APP_APPS.'/Goods/moveRecycleBin','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/<?php echo ($types); ?>/ids/<?php echo ($V["id"]); ?>/action/<?php echo ($_REQUEST['action']); ?>" target="ajaxTodo"><img src="<?php echo C('__IMG__');?>/icon_output.gif" /></a>
                  <?php else: ?>
                       <img src="<?php echo C('__IMG__');?>/icon_output_no.gif" title="<?php echo L('Without');?>"/><?php endif; ?> 
                   <?php echo (tpl_action($action,'ajaxTodo','Goods/goodsDelete',C('VAR_PAGE').'/'.$currentPage.'/'.$types.'/ids/'.$V["id"].'/action/'.$_REQUEST['action'],'确定执行删除('.strip_tags($V[pfix('goods_name')]).')？','icon_drop.gif','icon_drop_not.gif')); ?>   <!--删除按钮-->       
                <?php else: ?>  <!--未上架商和上架商品表列列表-->
                 <?php echo (tpl_action($action,'NAVTAB','GoodsAppraise/appList','goods_id/'.$V["id"],$V[pfix('goods_name')].'[评论]','icon_edit.gif','icon_edit_not.gif','','','评论('.($V['review_count'] ? $V['review_count'] : 0).')')); ?>  
                 <?php echo (tpl_action($action,'dialog','Goods/goodsEditing',$types.'/id/'.$V["id"].'/action/'.$_REQUEST['action'],'修改（'.strip_tags($V[pfix('goods_name')]).'）','icon_edit.gif','icon_edit_not.gif')); ?> <!--修改按钮-->
                 <?php echo (tpl_action($action,'dialog','Goods/goodsEditing',$types.'/id/'.$V["id"].'/action/'.$_REQUEST['action'].'/copy/ok'.'/field/'.$_REQUEST['field'].'/'.$_REQUEST['field'].'/'.$_REQUEST[$_REQUEST['field']],'复制（'.strip_tags($V[pfix('goods_name')]).'）','icon_copy.gif','icon_copy_no.gif')); ?> <!--复制按钮-->
                   <?php if(access('Goods/moveRecycleBin')): ?><a href="<?php echo U(APP_APPS.'/Goods/moveRecycleBin','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>/<?php echo ($types); ?>/ids/<?php echo ($V["id"]); ?>/action/<?php echo ($_REQUEST['action']); ?>" target="ajaxTodo" title="确定把(<?php echo ($V[pfix('goods_name')]); ?>)列入回收站？"><img src="<?php echo C('__IMG__');?>/icon_trash.gif" /></a>
                   <?php else: ?>
                       <img src="<?php echo C('__IMG__');?>/icon_trash_no.gif" title="<?php echo L('Without');?>" /><?php endif; endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?> 

        </tbody>
    </table>
    
        <div class="panelBar">
        <div class="pages">
            <span>共<?php echo ($totalCount); ?> 条数据</span>
        </div>
        
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="18" currentPage="<?php echo ($currentPage); ?>"></div>

    </div>
</div>