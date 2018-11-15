<?php
class UserRelationModel extends RelationModel{
	protected $tableName='user';//主表名称，用户表
	protected $_link=array(
		  'role'=>array(
		  'mapping_type'=>MANY_TO_MANY,//一对多关联
		  'foreign_key'=>'user_id',//外键
		  'relation_key'=>'role_id',//外键
		  'relation_table'=>'my_role_user',
		  'mapping_fields'=>'id, name, remark'//只读字段
	  )
	);
	
}
?>