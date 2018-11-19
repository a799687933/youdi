<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="__J_UI__/js/DialogAutoSize.js"></script>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
		    <?php if(access('GoodsCategory/cateAdd')): ?><li><a class="add auto-size" href="<?php echo U(APP_APPS.'/GoodsCategory/cateAdd','','');?>/pid/0" target="dialog"   mask="true" title="添加顶级分类"><span>添加顶级分类</span></a></li><?php endif; ?>
		</ul>
	</div>


	<table class="list" width="100%" layoutH="50" id="list-table-goods-cate">
		<thead>
		<tr>
	
			<th width="250">分类名称</th>
			<?php if(C('IS_GOODS_CLASS')): ?><th width="250">价格分级</th><?php endif; ?>
			<th width="120">排序</th>
			<th width="120">是否显示</th>
            <th width="120">操作</th>
		</tr>
		</thead>
		
		<tbody>
		<?php if(is_array($Cate)): foreach($Cate as $key=>$app): ?><tr target="sid_node" rel="<?php echo ($app['id']); ?>" class="<?php echo ($app['pid']); ?> showtr" id="<?php echo ($app['pid']); ?>_<?php echo ($app['id']); ?>" >
				<td class="first-cell" style="text-align:left;text-indent:5px;padding-left:<?php echo ($app['padding']); ?>px;">
                <img src="__SYS_IMG__/menu_minus.gif" id="icon_<?php echo ($app['pid']); ?>_<?php echo ($app['id']); ?>" width="9" height="9" border="0" style="margin-left:0em" onclick="rowClicked(this,'list-table-goods-cate')" />
                <?php echo (tpl_modify_oen($app[pfix('name')],'goods_category',pfix('name'),$app['id'],'id','GoodsCategory/cateSave')); ?>
                </td>
               <?php if(C('IS_GOODS_CLASS')): ?><td><?php echo (tpl_modify_oen($app["grade"],'goods_category','grade',$app['id'],'id','GoodsCategory/cateSave')); ?></td><?php endif; ?>
                <td><?php echo (tpl_modify_oen($app["reorder"],'goods_category','reorder',$app['id'],'id','GoodsCategory/cateSave')); ?></td>
				<td><?php echo (tpl_yes_no($app["is_show"],'goods_category','is_show',$app['id'],'id','GoodsCategory/cateSave')); ?></td>
                <td >        
                    <?php echo (tpl_action($action,'dialog','GoodsCategory/cateAdd','pid/'.$app["id"],'添加('.strip_tags($app[pfix('name')]).')子分类','icon_add.gif','icon_add_not.gif')); ?> <!--添加按钮-->
                    <?php echo (tpl_action($action,'dialog','GoodsCategory/cateAdd','id/'.$app["id"],'修改('.strip_tags($app[pfix('name')]).')子分类','icon_edit.gif','icon_edit_not.gif')); ?> <!--修改按钮-->
					<?php if($_SESSION[C(SESSION_NAME_VAL)] == C("RBAC_SUPERADMIN")): echo (tpl_action($action,'ajaxTodo','GoodsCategory/cateDelete','ids/'.$app["id"],'确定执行删除('.strip_tags($app[pfix('name')]).')？','icon_drop.gif','icon_drop_not.gif')); ?>   <!--删除按钮--><?php endif; ?>	
                </td>
			</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
</div>