<?php
//属性类

class Attrs{
	//获取属性
	/**
	*$attrTypeId  属性类型ID
	*$goodsId    商品ID
	*/
	static function goodsSaveAttr($attrTypeId,$goodsId){
		 $urlArr['thum']='ok';
		 $urlArr['remove']='ok';
		 $urlArr['width']=C('GOODS_ATTR_WIDTH');
		 $urlArr['height']=C('GOODS_ATTR_HEIGHT');
		 $upUrl=get_up_url($urlArr,U(APP_APPS.'/UploadFile/upload','',''),false);
		 $infoPrice=isL(L('InfoPrice'),'价格');
		 $infoDelete=isL(L('Delete'),'删除');
		 $infoStock=isL(L('InfoStock'),'库存');
         $result=self::attrList($attrTypeId);
		 $defaultImg=C('__IMG__').'/a_pic.png';
		 //修改
		 if($goodsId) $attrArr=self::selectAttrGoods($goodsId);
		 foreach($result as $key=>$val){
			 $str.='<tr><td style="text-align:right;" width="9%">'.$val['attribute_name'].'</td>';
			 $str.='<td style="line-height: 180%;padding:5px 0;">';
			 $i=0;
			  foreach($val['attr_values'] as $k=>$v){
				  $uploadUrl='onchange="ajaxFileUpload(\''.$upUrl.'\',\'up_'.$v['attr_id'].'\',\'imgs_'.$v['attr_id'].'\',\'val_'.$v['attr_id'].'\',\'\',\'\',\''.__ROOT__.'\',\'\');"';
				  $i++;
				  $str.='<span style="border:1px #ccc solid;padding:3px 5px 0 0;margin:0 5px;" title="'.$v['attr_name'].'">';
				  $str.='<input type="hidden" name="attribute_id[]" value="'.$val['attribute_id'].'"/>';
				  if(in_array($v['attr_id'],$attrArr['ids'])){
					  $str.='<input type="hidden" name="attr_id_list[]" value="'.$attrArr['attr_arr'][$v['attr_id']]['attribute_value_id'].'"/>';
					  $str.='<input type="checkbox" value="'.$v['attr_id'].'" name="attr_value_list[]" checked="checked" style="position:relative;top:3px;">';
					  $str.='<b style="font-wegiht:bold;color:red;">'.$v['attr_name'].'</b>';
					  
					  //属性价格
					  if(C('IS_GOODS_ATTRIBUTE_PRICE')){
						  $str.='&nbsp; | '.$infoPrice.'('.CUR().')<input type="text"  name="attr_price_list[]"  value="'.getPrice($attrArr['attr_arr'][$v['attr_id']]['attr_price'],false).'" ';
						  $str.='style="height:10px;width:50px;border:none;text-align:center;"/>'; 
					  }
					  //使用属性库存
					  if(C('IS_GOODS_ATTRIBUTE_STOCK')){					  
						  $str.='&nbsp; | '.$infoStock.'<input type="text" name="attr_stock[]"  value="'.$attrArr['attr_arr'][$v['attr_id']]['attr_stock'].'" ';
						  $str.='style="height:10px;width:40px;border:none;text-align:center;"/>'; 
					  }
					  //上传属性图片
					  if(C('IS_GOODS_ATTRIBUTE_IMGS')){
						  $str.='&nbsp; | <input type="hidden" name="attr_pic[]" value="'.$attrArr['attr_arr'][$v['attr_id']]['attr_pic'].'" id="val_'.$v['attr_id'].'">';
						  $str.='<span style="position:relative;">';
						  $str.='<b style="position:absolute;top:0px;right:2px;z-index:9999;color:red;cursor:pointer;" title="'.$infoDelete/*删除*/.'"';
						  $str.=' onclick="delAttrImg('.$v['attr_id'].')">×</b>';
						  $str.='<img src="'.showImg($attrArr['attr_arr'][$v['attr_id']]['attr_pic'],$defaultImg).'" default-img="'.$defaultImg.'" id="imgs_'.$v['attr_id'].'"';
						  $str.='style="width:22px;height:17px;position:relative;top:5px;" />';
						  $str.='<input type="file" name="up_'.$v['attr_id'].'" id="up_'.$v['attr_id'].'"'.$uploadUrl;
						  $str.='style="position:absolute;top:0;left:0;opacity: 0;filter:alpha(opacity=0);width:100%;height:100%;">';
						  $str.='</span>';	
					  }
				  }else{
					  $str.='<input type="hidden" name="attr_id_list[]" value=""/>';
					  $str.='<input type="checkbox" value="'.$v['attr_id'].'" name="attr_value_list[]" style="position:relative;top:3px;">';
					  $str.='<b style="font-wegiht:bold;color:red;">'.$v['attr_name'].'</b>';
					  
					  //属性价格
					   if(C('IS_GOODS_ATTRIBUTE_PRICE')){
					      $str.='&nbsp; | '.$infoPrice.'('.CUR().')<input type="text"  name="attr_price_list[]"  value="" style="height:10px;width:50px;border:none;text-align:center;"/>'; 
					   }
					  //使用属性库存
					  if(C('IS_GOODS_ATTRIBUTE_STOCK')){	
					      $str.='&nbsp; | '.$infoStock/*库存*/.'<input type="text" name="attr_stock[]"  value="" style="height:10px;width:40px;border:none;text-align:center;"/>'; 
					  }
					  //上传属性图片
					  if(C('IS_GOODS_ATTRIBUTE_IMGS')){
						  $str.='&nbsp; | <input type="hidden" name="attr_pic[]" value="" id="val_'.$v['attr_id'].'">';
						  $str.='<span style="position:relative;">';
						  $str.='<b style="position:absolute;top:0px;right:2px;z-index:9999;color:red;cursor:pointer;" title="'.$infoDelete/*删除*/.'"';
						  $str.=' onclick="delAttrImg('.$v['attr_id'].')">×</b>';
						  $str.='<img src="'.$defaultImg.'" default-img="'.$defaultImg.'" id="imgs_'.$v['attr_id'].'" ';
						  $str.='style="width:22px;height:17px;position:relative;top:5px;" />';
						  $str.='<input type="file" name="up_'.$v['attr_id'].'" id="up_'.$v['attr_id'].'"'.$uploadUrl;
						  $str.='style="position:absolute;top:0;left:0;opacity: 0;filter:alpha(opacity=0);width:100%;height:100%;">';
						  $str.='</span>';	
					  }
				  }
				  $str.='</span>';
				  if($i==2) {$str.='<br/>';$i=0;}
			  }  
			 $str.='</td>';
		 }
		 $str.='</tr>';
		 return $str;
	}
	
	/**返回属性类型与属性值列表
	*$attrTypeId  属性类型ID
	*/
	static function attrList($attrTypeId){
		 $field='ga.id AS attribute_id,ga.'.pfix('name').' AS attribute_name,ga.attr_type,v.id,';
		 $field.='v.'.pfix('attr_value').' AS attr_value_name,v.attribute_id AS bute_id,ga.sort_order';
	     $sql="SELECT {$field} FROM ".PREFIX."goods_attribute AS ga JOIN ".PREFIX."goods_attribute_value AS v ";
		 $sql.="ON (ga.id=v.attribute_id) WHERE ga.goods_attr_type_id='{$attrTypeId}' AND ga.is_show=1 AND v.is_show=1 ";
		 $sql.="ORDER BY ga.sort_order,v.orders ASC";
		 $result=M()->query($sql);
		 $result=multi_array_sort($result,'sort_order',SORT_ASC);
		 $attributeId=$result[0]['attribute_id'];
		 $attrList=array();
		 $i=0;
		 $j=0;
		 foreach($result as $k=>$v){
			 if($attributeId==$v['attribute_id']){
				 $attrList[$i]['attribute_id']=$v['attribute_id'];
				 $attrList[$i]['attribute_name']=$v['attribute_name'];
				 $attrList[$i]['attr_type']=$v['attr_type'];
				 $attrList[$i]['attr_values'][$j]['attr_id']=$v['id'];
				 $attrList[$i]['attr_values'][$j]['attr_name']=$v['attr_value_name'];
				 $j++;
			 }else{
				 $i++;$j=0;
				 $attributeId=$v['attribute_id'];
				 $attrList[$i]['attribute_id']=$v['attribute_id'];
				 $attrList[$i]['attribute_name']=$v['attribute_name'];
				 $attrList[$i]['attr_type']=$v['attr_type'];
				 $attrList[$i]['attr_values'][$j]['attr_id']=$v['id'];
				 $attrList[$i]['attr_values'][$j]['attr_name']=$v['attr_value_name'];				 
				 $j++;				 
			 }
		 }
		 unset($result);
		 return $attrList;
	}

	/**修改商品获取已选择属性
	*$goodsId  商品ID
	*/
	static function selectAttrGoods($goodsId){
	     $result=M('goods_attr')->where(array('goods_id'=>$goodsId))->select();
		 $attrArr=array();
		 foreach($result as $k=>$v){
			 $attrArr['ids'].=$v['attribute_value_id'].',';
			 $attrArr['attr_arr'][$v['attribute_value_id']]['id']=$v['id'];
			 $attrArr['attr_arr'][$v['attribute_value_id']]['goods_id']=$v['goods_id'];
			 $attrArr['attr_arr'][$v['attribute_value_id']]['attribute_value_id']=$v['attribute_value_id'];
			 $attrArr['attr_arr'][$v['attribute_value_id']]['attr_price']=$v['attr_price'];
			 $attrArr['attr_arr'][$v['attribute_value_id']]['attr_stock']=$v['attr_stock'];
			 $attrArr['attr_arr'][$v['attribute_value_id']]['attr_pic']=$v['attr_pic'];
		 }
		 unset($result);
		 $attrArr['ids']=explode(',',rtrim($attrArr['ids'],','));
		 return $attrArr;
	}
	
}
?>