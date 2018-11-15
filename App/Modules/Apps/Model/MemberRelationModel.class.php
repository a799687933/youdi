<?php
//多对一重属关系
class MemberRelationModel extends RelationModel{
	    protected $tableName='member'; 
	    protected $_link=array(
		    'city'=>array( //分类表，多对一
			     'mapping_type'=>BELONGS_TO, //多对一
			     'foreign_key'=>'city_id',
			     'mapping_fields'=>'city_name',
			     'as_fields'=>'city_name', //不显示二维数组，与VIP表平级显示,加冒号改变键值
			),
			
			//获取会员的可用资金，冻结资金，等级积分，消费积分
			'member_account'=>array(  //获取会员的可用资金，冻结资金，等级积分，消费积分
				 'mapping_type'=>HAS_MANY,
				 'foreign_key'=>'member_id',
				 'mapping_fields'=>"sum(member_money) as member_money,sum(frozen_money) as frozen_money,sum(rank_points) as rank_points,sum(pay_points) as pay_points",
			),
			
		);
	
}
?>