<?php

class CommentRelationModel extends RelationModel{
	    protected $tableName='comment'; 
	    protected $_link=array(
			    'article'=>array( //分类表，多对一
			     'mapping_type'=>BELONGS_TO, //多对一
			     'foreign_key'=>'article_id',
			     'mapping_fields'=>'cn_titletext',
			     'as_fields'=>'cn_titletext:titletext', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
			//这里关联会员表
			'member'=>array(
	             'mapping_type'=>BELONGS_TO,
	             'foreign_key'=>'user_id',		 
	             'mapping_fields'=>"(case when nickname <> '' then nickname else user_name end) AS name ",  
	             'as_fields'=>'name',
			),

		);
	
}
?>