<?php
//组合购买
class ZuHeGoods{
	/** 
	*检索商品
	*/
	static function retrievalGoods(){
		    
			$where="1=1 ";
		    if($_GET['goods_cate_id'] > 0) {
				$caets=M('goods_category')->field('id')
				->where("path LIKE '%-{$_GET['goods_cate_id']}-%' OR id='{$_GET['goods_cate_id']}' AND is_show=1")->select();
				$caets=in_id($caets,'id');
				if($caets) $where.="AND goods_category_id IN({$caets}) ";
			}
			if($_GET['keywords']) $where.="AND ".pfix('goods_name')." LIKE '%".filterRequst($_GET['keywords'])."%' ";
			$field='id,'.pfix('goods_name').' AS goods_name,goods_market_price,goods_retail_price,goods_thumb,create_time';
			$goodsLike=M('goods')->field($field)->where($where)->select();
			$str='';
			foreach($goodsLike as $k=>$v){
			   $str.='<tr class="goods-list">
			        <td style="width:3px;"><input type="hidden" name="zhu_he_goods_id[]" value="'.$v['id'].'" /></td>
			        <td style="text-align:left;width:50px;"><img src="'.showImg($v['goods_thumb']).'" style="width:40px;" /></td>
					<td>'.$v['goods_name'].'</td>
					<td style="color:red;">'.getPrice($v['goods_retail_price'],true).'</td>
					<td>组合价格(<b style="color:red;">'.CUR_REP().'</b>) <input type="text" name="zhu_he_price[]" /><b style="color:red;">'.CUR().'</b></td>
					<td><a href="javascript:void(0);" onclick="delLikeGoods(this)">删除</a></td>
			   </tr>';
			}
			return $str;
	}
	
	/**
	*获取已选择的组合购买商品
	*$goodsId 商品ID
	*$isArr      true返回数组，false返回字符串
	*/
	static function getZuGoods($goodsId,$isArr=true){
		 $field='g.id,g.'.pfix('goods_name').' AS goods_name,g.goods_market_price,g.goods_retail_price,z.zhuhe_price,';
		 $field.='g.goods_thumb,g.stock,is_discount,g.create_time ';
		 $sql="SELECT {$field} FROM ".PREFIX."goods AS g  INNER JOIN ".PREFIX."goods_zhuhe AS z ON (z.zhuhe_goods_id=g.id) ";
		 $sql.="WHERE z.main_goods_id='{$goodsId}' AND g.shelves=1";
		 $result=M()->query($sql);
		 if($isArr) return $result;
		 $str='';
		 foreach($result as $k=>$v){
		   $str.='<tr class="goods-list">
				<td style="width:3px;"><input type="hidden" name="zhu_he_goods_id[]" value="'.$v['id'].'" /></td>
				<td style="text-align:left;width:50px;"><img src="'.showImg($v['goods_thumb']).'" style="width:40px;" /></td>
				<td>'.$v['goods_name'].'</td>
				<td style="color:red;">'.getPrice($v['goods_retail_price'],true).'</td>
				<td>
				       组合价格(<b style="color:red;">'.CUR_REP().'</b>) 
				      <input type="text" value="'.getPrice($v['zhuhe_price'],false).'" name="zhu_he_price[]" /><b style="color:red;">'.CUR().'</b>
				</td>
				<td class="hidden-td"><a href="javascript:void(0);" onclick="delLikeGoods(this)">删除</a></td>
		   </tr>';
		 }
		 return $str;		 
	}
	
	

}
?>