<?php
//会员中心公共模型
class BasicMemberModel extends CommonModel{
	
    //获取会员等级ID、等级权限、等级名称、商品折扣；会员资料
	public function getMemberData($userId,$dvWidth=320){
        $user=M('member')->where(array('id'=>$userId))->find();//会员表	
		$gr=M('member_grade');
		$gWhere['min_points']=array('ELT',$user['rank_points']);
		$gWhere['max_points']=array('EGT',$user['rank_points']);
		$grade=$gr->field('id,'.pfix('grade_name').' AS grade_name,min_points,max_points,discount,show_price,special_rank')->where($gWhere)->find();
		$highest=$gr->field('max(min_points) AS min_points,max(max_points) AS max_points,count(id) AS grade_count')->find();
		$user['grade_name']=$grade['grade_name']; //当前会员等级名称
		$user['grade_count']=$highest['grade_count']; //会员等级个数
		$user['min_points']=$highest['min_points']; //会员组最高等级积分起点
		$user['max_points']=$highest['max_points']; //会员组最高等级积分
		
		//如果会员积分超过最高点
		if($user['rank_points'] > $highest['max_points']) 
		$user['grade_name']=$gr->order("max_points DESC")->getField(pfix('grade_name'));
		
		//用户等级和成长
		$userRandPoints=$user['rank_points'] ? $user['rank_points'] : 1;
		$sql='';
		$sql.="SELECT g2.".pfix('grade_name')." AS grade_name,g2.min_points,";
		$sql.="(SELECT g.max_points FROM ".PREFIX."member_grade AS g ";
		$sql.="WHERE g.min_points < '".$userRandPoints."' AND g.max_points >= '".$userRandPoints."') ";
		$sql.="AS min_p FROM ".PREFIX."member_grade AS g2  HAVING g2.min_points=min_p ";
		$grade1=$this->db->query($sql);
		$user['next_grade_name']=$grade1[0]['grade_name'];//下一个等级名称
		$user['next_grade_points']=$grade1[0]['min_points']-$user['rank_points'];//距离下一个等级分值	
		$mygr=$gr->where(array('id'=>array('elt',$grade['id'])))->count(); //我的进条度个数
		$edeect=$dvWidth / $user['grade_count'];//每一个等级进条度宽度
		$edepoe=$edeect / $dvWidth;//每一个等级进条度百分比
		$width=$edeect * $mygr;//我的进条度宽度
		$per = ($edepoe * $mygr) * 100; //我的进条度百分比
		$user['width']=$width;
		$user['per']=$per;
		return $user;				
	}
	
	//用户提醒
	public function remind($userId){
	    $times=time();
		//开始查询时间
		$startTime=$times- (86400 * 30);
		//结束查询时间
		$endTime= $times; 
		$M=M('order_info');
		//待处理订单；未付款的和已发货订单个数；
		$orderCountUnt=$M->where("(pay_status < 2 OR shipping_status=2) AND user_id='{$userId}'")->count();
		//最近30天内提交的订单
		$orderCount30=$M->where("(add_time >='{$startTime}' AND add_time <='{$endTime}') AND user_id='{$userId}'")->count(); 
		//待评价商品个数
		$sql="SELECT count(og.rec_id) AS og_count FROM ".PREFIX."order_goods AS og JOIN ".PREFIX."order_info AS o ";
		$sql.="ON (og.order_id=o.order_id) WHERE (o.order_status=2 AND o.user_id='{$userId}') AND og.is_reviews=0 ";
		$appCount=$M->query($sql);
		//用户现金卷
		$redCount=M('member_bonus')->where("member_id='{$userId}'")->count();
		//近期订单
		$orderList=$M->field("order_id,order_sn,order_status,pay_status,shipping_status,goods_amount,order_amount,pay_type,add_time")
		->where("user_id='{$userId}'")->order("add_time DESC")->limit(6)->select();
		//近期收藏
		$sql="SELECT g.id,g.".pfix('goods_name')." AS goods_name,g.goods_num,g.goods_market_price,g.goods_retail_price,g.goods_thumb,";
		$sql.="g.hot,g.is_new,gc.rec_type FROM ".PREFIX."goods AS g ";
		$sql.="JOIN ".PREFIX."goods_collect as gc ON(g.id=gc.goods_id) ";
		$sql.="WHERE g.shelves=1 AND g.recycle_bin=0 AND g.stock > 0 AND gc.user_id='{$userId}' ORDER BY gc.add_time DESC LIMIT 10";
		$gc=$M->query($sql);
        $data['order_count_unt']=$orderCountUnt;//待处理订单
        $data['red_count']=$redCount;//用户现金卷个数
		$data['order_count_30']=$orderCount30;//最近30天内提交的订单个数
		$data['app_count']=$appCount[0]['og_count'];//待评价商品个数
		$data['order_list']=$orderList;//近期订单
		$data['goods_collect']=$gc;//近期收藏
        return $data;
	}
	
	//会员站内信息
	public function instationInfo($userId){

	}
  	
}
?>