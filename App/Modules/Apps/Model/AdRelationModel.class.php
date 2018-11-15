<?php

class AdRelationModel extends RelationModel{
	   protected $tableName='ad'; 
	    protected $_link=array(
		    'city'=>array(
		     'mapping_type'=>BELONGS_TO,
		     'foreing_key'=>'city_id',
		     'mapping_fields'=>'city_name',
		      'as_fields'=>'city_name', //不显示二维数组，与User表平级显示,加冒号改变键值
			),
		);
	
}
?>