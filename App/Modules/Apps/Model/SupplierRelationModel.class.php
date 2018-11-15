<?php
//多对一重属关系
class SupplierRelationModel extends RelationModel{
	    protected $tableName='goods_supplier'; 
	    protected $_link=array(
		    'region'=>array( //分类表，多对一
			     'mapping_type'=>BELONGS_TO, //多对一
			     'foreign_key'=>'city',
			     'mapping_fields'=>'region_name',
			     'as_fields'=>'region_name', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
		);
	
}
?>