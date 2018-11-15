<?php
//多对一重属关系
class LnviteRelationModel extends RelationModel{
	    protected $tableName='lnvite'; 
	    protected $_link=array(
			'shops'=>array(
			  'mapping_type'=>BELONGS_TO, 
			  'foreign_key'=>'shops_id',
			  'mapping_fields'=>'shop_name',
			  'as_fields'=>'shop_name'
			),
		);
	
}
?>