<?php
class CommentReplyRelationModel extends RelationModel{
	    protected $tableName='comment_reply'; 
	    protected $_link=array(
		     'comment'=>array( //分类表，多对一
			     'mapping_type'=>BELONGS_TO, //多对一
			     'foreign_key'=>'comment_id',
			     'mapping_fields'=>'content',
			     'as_fields'=>'content:comm_content', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
			//这里关联会员表
			'article'=>array(
			    'mapping_type'=>BELONGS_TO,
			    'foreign_key'=>'article_id', 
			    'mapping_fields'=>'titletext',
			    'as_fields'=>'titletext',
			),
			
			'member'=>array(
			   'mapping_type'=>BELONGS_TO,
			   'foreign_key'=>'user_id',
			   'mapping_fields'=>"(case when dear <> '' then dear else reg_email end) AS name ",
			   'as_fields'=>'name',
			),
			
		);
	
}
?>