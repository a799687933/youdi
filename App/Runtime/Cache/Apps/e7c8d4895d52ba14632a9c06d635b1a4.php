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
</form>

<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="" method="post">
    <div class="searchBar">
        <table class="searchContent">
            <tr>
                <td>
                     输入关键字：<input type="text" name="name_cn" value="<?php echo ($searchVal); ?>"/>
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
            <?php if(access('GoodsBrand/brandAddSave')): ?><li><a class="add auto-size" href="<?php echo U(APP_APPS.'/GoodsBrand/brandAddSave');?>" target="dialog" mask="true"  title="添加品牌"><span>添加品牌</span></a></li><?php endif; ?>

            <?php if(access('GoodsBrand/brandDel')): ?><li><a title="操作不可恢复确定执行本次操作？" target="selectedTodo" rel="ids" postType="string" href="<?php echo U(APP_APPS.'/GoodsBrand/brandDel','','');?>/<?php echo C('VAR_PAGE');?>/<?php echo ($currentPage); ?>" class="delete"><span>删除品牌</span></a></li><?php endif; ?>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
            <tr>
                <th width="2%"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
                <th orderfield="id"  class="<?php if($id == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">编号</th>               
                <th orderfield="<?php echo pfix('name');?>"  class="<?php if(pfix('name') == 'desc'): ?>desc<?php else: ?>asc<?php endif; ?>">品牌名称</th>
                <th>所属分类集合</th>
                <th orderfield="abc"  class="<?php if($abc == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">品牌字母排序</th>
                <!--<th orderfield="url"  class="<?php if($url == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">品牌网站</th>-->
                <th orderfield="display"  class="<?php if($display == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">是否显示</th>
                <th orderfield="orders"  class="<?php if($orders == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">排序</th>
                <th orderfield="create_time"  class="<?php if($create_time == "desc"): ?>desc<?php else: ?>asc<?php endif; ?>">添加时间</th>
                <th>操作</th>
            </tr>                       
        </thead>
        <tbody>
            
           <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($r["id"]); ?>">
                <td><input name="ids" value="<?php echo ($r["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($r["id"]); ?></td>
                <td><?php echo (tpl_modify_oen($r[pfix('name')],'goods_brand','name_cn',$r['id'],'id','GoodsBrand/brandForm')); ?></td>
                <td><?php if(is_array($r['goods_category_name'])): foreach($r['goods_category_name'] as $key=>$gcn): ?>[<?php echo ($gcn['name']); ?>]&nbsp;<?php endforeach; endif; ?></td>
                <td><?php echo (tpl_modify_oen($r["abc"],'goods_brand','abc',$r['id'],'id','GoodsBrand/brandForm')); ?></td>
                <!--<td>
                    <?php if($r['url']): ?><a href="<?php echo ($r["url"]); ?>" target="_blank"><?php echo ($r["url"]); ?></a>
                        <?php else: ?>
                        暂无网址<?php endif; ?>
                </td>-->
                <td><?php echo (tpl_yes_no($r["display"],'goods_brand','display',$r['id'],'id','GoodsBrand/brandForm')); ?></td>
                <td><?php echo (tpl_modify_oen($r["orders"],'goods_brand','orders',$r['id'],'id','GoodsBrand/brandForm')); ?></td> 
               <td><?php echo (date('Y-m-d',$r["create_time"])); ?></td>
               <td>
                    <?php echo (tpl_action($action,'dialog','GoodsBrand/brandAddSave','id/'.$r["id"],'修改'.strip_tags($r[pfix('name')]),'icon_edit.gif','icon_edit_not.gif','','')); ?> <!--修改按钮-->
                    <?php echo (tpl_action($action,'ajaxTodo','GoodsBrand/brandDel',C('VAR_PAGE').'/'.$currentPage.'/ids/'.$r["id"],'确定执行删除('.strip_tags($r[pfix('name')]).')','icon_drop.gif','icon_drop_not.gif')); ?>   <!--删除按钮-->
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