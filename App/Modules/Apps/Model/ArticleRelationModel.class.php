<?php
define('FILES_NAME',pfix('files_name'));
//多对一重属关系
class ArticleRelationModel extends RelationModel{
	    protected $tableName='article'; 
	    protected $_link=array(
		    'files_sort'=>array( //分类表，多对一
		     'mapping_type'=>BELONGS_TO, //多对一
		     'foreign_key'=>'files_id',
		     'mapping_fields'=>FILES_NAME,
		     'as_fields'=>FILES_NAME, //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
			/*'comment'=>array( //文章评述表,一对多
			  'mapping_type'=>HAS_MANY, //一对多
			  'foreign_key'=>'article_id',
			  'mapping_fields'=>'user_id',
			),*/
		);
	
}
?>