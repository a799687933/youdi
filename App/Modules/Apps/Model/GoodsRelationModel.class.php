<?php
define('CATE_NAME',pfix('name'));
class GoodsRelationModel extends RelationModel{
	    protected $tableName='goods'; 
	    protected $_link=array(
		    'goods_category'=>array( //分类表
		     'mapping_type'=>BELONGS_TO,
		     'foreign_key'=>'goods_category_id',
		     'mapping_fields'=>CATE_NAME,
		      'as_fields'=>CATE_NAME, 
			)
		);
	
}
?>