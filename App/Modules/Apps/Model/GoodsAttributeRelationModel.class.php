<?php

define('TYPE_NAME',pfix('name'));
define('TYPE_NAME_AS',pfix('name').':type_name');
class GoodsAttributeRelationModel extends RelationModel{
	    protected $tableName='goods_attribute'; 
	    protected $_link=array(
		    'goods_attr_type'=>array(
		     'mapping_type'=>BELONGS_TO,
		     'foreign_key'=>'goods_attr_type_id',
		     'mapping_fields'=>TYPE_NAME,
		      'as_fields'=>TYPE_NAME_AS, //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
		);
	
}
?>