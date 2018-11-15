<?php
//多对一重属关系
class ShopsRelationModel extends RelationModel{
	    protected $tableName='shops'; 
	    protected $_link=array(
		    'region'=>array( //分类表，多对一
			     'mapping_type'=>BELONGS_TO, //多对一
			     'foreign_key'=>'city',
			     'mapping_fields'=>'region_name AS city_name',
			     'as_fields'=>'city_name', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
			//会员表
			'member'=>array(  
				 'mapping_type'=>BELONGS_TO,
				 'foreign_key'=>'member_id',
				 'mapping_fields'=>"user_name",
				 'as_fields'=>'user_name', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
		);
	
}
?>