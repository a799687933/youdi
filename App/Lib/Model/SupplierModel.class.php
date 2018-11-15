<?php
/*供应商模型*/
class SupplierModel extends Model{
	private $supplierId;
	public function __construct($supplierId){
		parent::__construct(); 
		if(empty($supplierId)) die('Error');
		$this->supplierId=$supplierId;
	}
	
	//首页
	public function index(){
       $supplier=M('goods_supplier')->where(array('id'=>$this->supplierId))->find();
	   if($supplier['district']) $district=','.$supplier['district'];
	   $rInId=$supplier['country'].','.$supplier['province'].','.$supplier['city'].$district;
	   $region=M('region')->field('region_name')->where(array('region_id'=>array('in',$rInId)))->order("region_type ASC")->select();
	   $supplier['country']=$region[0]['region_name'];
	   $supplier['province']=$region[1]['region_name'];
	   $supplier['city']=$region[2]['region_name'];
	   $supplier['district']=$region[3]['region_name'];
	   //最近订单
	   $sql="SELECT o.order_id,o.order_sn,o.order_status,o.pay_status,o.shipping_status,o.goods_amount,o.order_amount,";	  
	   $sql.="o.add_time,o.pay_type,IF(m.nickname <>'',m.nickname,m.user_name) AS user_name FROM ".PREFIX."order_info AS o ";
	   $sql.="JOIN ".PREFIX."member AS m ON (m.id = o.user_id) WHERE o.goods_supplier='{$this->supplierId}' ORDER BY o.add_time DESC LIMIT 20";
	   $order=$this->db->query($sql);
	   //待处理的订单
	   $pending="(SELECT count(o1.order_id) FROM ".PREFIX."order_info AS o1 ";
	   $pending.="WHERE o1.goods_supplier='{$this->supplierId}' AND o1.pay_status=2 AND o1.shipping_status < 2) AS pending_count";
	   //昨天交易成功量
	   $tiems=strtotime(date('Y-m-d'));
	   $beginTime=$tiems - 86400;
	   $endTime=$tiems;
	   $ms="(SELECT count(o2.order_id) FROM ".PREFIX."order_info AS o2 ";
	   $ms.="WHERE (o2.add_time >='{$beginTime}' AND o2.add_time <='{$endTime}') AND (o2.goods_supplier='{$this->supplierId}')) AS cesterday";
	   $sql="SELECT {$pending},{$ms},count(o.order_id) AS counts FROM ".PREFIX."order_info AS o WHERE o.goods_supplier='{$this->supplierId}' ";
	   $penResult=$this->db->query($sql);
	   $orderData['counts']=$penResult[0]['counts']; //全部订单
	   $orderData['pending_count']=$penResult[0]['pending_count']; //待处理订单
	   $orderData['cesterday']=$penResult[0]['cesterday']; //昨天成交量
	   $orderData['order_list']=$order; //最近订单列表
	   unset($order);
	   return array('supplier'=>$supplier,'order_data'=>$orderData);
	}

	//订单列表
	public function orderList(&$_this,$pageLimit){
		 $where="1=1 AND goods_supplier='{$this->supplierId}' ";
	     if($_GET['order_type']==1){ //退款退货
			$where.="AND order_status>=3 ";
		 }
	     $M=M('order_info');
		 if(!$_GET['order_type']){
			 //未付款
			 $pay_0="(SELECT count(p0.order_id) AS pay_0 FROM ".PREFIX."order_info AS p0 ";
			$pay_0.="WHERE p0.pay_status=0 AND p0.goods_supplier='{$this->supplierId}' AND p0.order_status < 3) AS pay_0";
			 //已付款
			 $pay_2="(SELECT count(p2.order_id) AS pay_2 FROM ".PREFIX."order_info AS p2 ";
			 $pay_2.="WHERE p2.pay_status=2 AND p2.goods_supplier='{$this->supplierId}' AND p2.order_status < 3) AS pay_2";
			 //已发货
			 $shipping_status_2="(SELECT count(p3.order_id) AS shipping_status_2 FROM ".PREFIX."order_info AS p3 ";
			 $shipping_status_2.="WHERE p3.shipping_status=2 AND p3.goods_supplier='{$this->supplierId}' AND p3.order_status < 3) AS shipping_status_2";
			 //交易完成
			 $order_status_2="(SELECT count(p4.order_id) AS order_status_2 FROM ".PREFIX."order_info AS p4 ";
			 $order_status_2.="WHERE p4.order_status=2 AND p4.goods_supplier='{$this->supplierId}' AND p4.order_status < 3) AS order_status_2";
			 //已取消
			 $order_status_5="(SELECT count(p5.order_id) AS order_status_5 FROM ".PREFIX."order_info AS p5 ";
			 $order_status_5.="WHERE p5.order_status=-1 AND p5.goods_supplier='{$this->supplierId}') AS order_status_5";			 
			 $all=$pay_0.','.$pay_2.','.$shipping_status_2.','.$order_status_2.','.$order_status_5;
			 $sql="SELECT count(o.order_id) AS counts,{$all} FROM ".PREFIX."order_info AS o WHERE o.goods_supplier='{$this->supplierId}'";
			 $sums=$M->query($sql);
			 $counts=$sums[0]['counts'];
		 }else{
		 	 $counts=$M->where($where)->count();
		 }

		 if($_GET['status'] !='') {
			 if($_GET['status']=='shipping_status' && $_GET['value']=='2'){ //已发货
				 $where.=" AND order_status IN(0,1) AND pay_status=2 AND shipping_status=2";
			     $counts=$sums[0]['shipping_status_2'];
			 }else if($_GET['status']=='pay_status' && $_GET['value']=='2'){ //已付款
			     $where.=" AND (order_status < 3 AND order_status >-1) AND pay_status=2";
			     $counts=$sums[0]['pay_2'];
			 }else if($_GET['status']=='pay_status' && $_GET['value']=='0'){ //未付款
			     $where.=" AND (order_status < 3 AND order_status >-1) AND pay_status < 2";
			     $counts=$sums[0]['pay_0'];
			 }else if($_GET['status']=='order_status' && $_GET['value']=='2'){ //交易完成
			     $where.=" AND order_status = 2";
				 $counts=$sums[0]['order_status_2'];
			 }else if($_GET['status']=='order_status' && $_GET['value']=='-1'){ //已取消
			     $where.=" AND order_status = -1";
				 $counts=$sums[0]['order_status_5'];
			 }
		 }
		 $_this->_URL_=$_GET['_URL_'];		 
		 $_this->doPage($counts,$pageLimit);
		 $field="order_id,order_sn,order_status,pay_status,shipping_status,goods_amount,order_amount,add_time,pay_type,logistics_sn,logistics_id,voucher";
		 $orderList=$M->field($field)->where($where)->order('add_time DESC')->limit($GLOBALS['limit'])->select();
		 $data['data']=$sums[0];
		 $data['order_list']=$orderList;
		 return $data;
	}
	
	//订单详情
	public function orderShow(&$_this){
	    $orderWhere=array('order_id'=>getNum($_REQUEST['order_id']),'goods_supplier'=>$this->supplierId);
		//修改订单地址
		/*if($_REQUEST['address_update']){
		   M('order_info')->where($orderWhere)->save($_REQUEST);
		   val_json("submits",true,isL(L('ModifySuccess'),"修改成功"),'','','');
		}*/
		
     	$order=M('order_info')->where($orderWhere)->find();
		$goodsList=M('order_goods')->where(array('order_id'=>$order['order_id']))->select(); //商品列表
		foreach($goodsList as $k=>$v){
			$goodsList[$k]['goods_attr']=json_decode($v['goods_attr'],true);
		}
		$order['goodsList']=$goodsList;
		$comm=new CommonModel();
		$order['region']=$comm->getRegion($order['country'],$order['province'],$order['city'],$order['district']);   //国家、省、城市、区县
        //是否使用现金卷
		if($order['bonus_sn'] > 0){
		     $sql="SELECT t.".pfix('bonus_name')." AS bonus_name,t.bonus_money FROM ".PREFIX."bonus_type AS t JOIN ";
			 $sql.=PREFIX."member_bonus AS b ON (t.id=b.bonus_type_id) WHERE b.id='{$order['bonus_sn']}' LIMIT 1";
			 $bonus=$this->db->query($sql);
			 $order['bonus_name']=$bonus[0]['bonus_name'];
			 $order['bonus_money']=$bonus[0]['bonus_money'];
		}	
		if($order['is_inv'] >0){ //需要发票
		     if($order['is_inv']==1){//普通发票
			     $invs=M('member_pu_tong_inv')->where(array('member_id'=>$order['user_id']))->find();
			 }else if($order['is_inv']==2){//增值税发票
			     $invs=M('member_zeng_zhi_inv')->where(array('member_id'=>$order['user_id']))->find();
				 $district=$invs['district'] ? ','.$invs['district'] : '';
				 $regId=$invs['country'].','.$invs['province'].','.$invs['city'].$district;
				 $region=M('region')->field('region_name')->where("region_id IN({$regId})")->order(array('region_id'=>'ASC'))->select();
				 $invs['address']=$region[0]['region_name'].$region[1]['region_name'].$region[2]['region_name'].$region[3]['region_name'].$invs['address'];
			 }
		}
		$_this->invs=$invs;		
		$_this->order=  $order; 
		$_this->goodsSats=isL(L('GOODS_SATISFY'),C('GOODS_SATISFY')); //买后感受
				
	    //是否有满即送
	    $_this->orderDiscount=M('order_discount')->where(array('order_id'=>$order['order_id']))->find();		
		
		//物流去向信息			
		 if($order['logistics_id']){
		   $shipping=M('shipping')->field('shipping_code,shipping_name')->where(array('shipping_id'=>$order['logistics_id']))->find();
		  $this->shipping=$shipping;
		   if($order['logistics_sn']){
			   if(C('IS_LOGISTICS')){	
				   if($shipping){
						$this->iframeUrl=get_kuaidi100($shipping['shipping_code'],$order['logistics_sn']);
				   }					
				}
			}
		 }
	}

   //设置订单状态
   public function setOrder(){
   	   $orderWhere=array('order_id'=>getNum($_REQUEST['order_id']),'goods_supplier'=>$this->supplierId);
	   $orderData=M('order_info')->field('order_id,order_status,pay_status,shipping_status,pay_type')->where($orderWhere)->find();
	   if(!$orderData) val_json("submits",false,'非法操作','','','');
	   if($_REQUEST['type']=='shipping'){
		   	   if($orderData['pay_type']==0 || $orderData['pay_type']==3){
		   	   	   $orderWhere['pay_status']=2;
				   $orderWhere['order_status']=array('in','0,1');
		   	   }
		   	   if($_REQUEST['val']==1){ //备货中
		   	   	  $save['shipping_status']=1;
			      $save['order_status']=1;
		   	   }else if($_REQUEST['val']==2){//已货中
		   	   	  $save['shipping_status']=2;
				  $save['shipping_time']=time();
				  $save['order_status']=1;
		   	   }else{
		   	   	  val_json("submits",false,'非法操作','','','');
		   	   }
	   }else{
	   	      val_json("submits",false,'非法操作','','','');
	   }
	   if(M('order_info')->where($orderWhere)->save($save)){
	   	  val_json("submits",true,'你的设置操作成功',$_SERVER["HTTP_REFERER"],'','');
	   }else{
	   	  val_json("submits",false,'你的设置操作失败','','','');
	   }
   }

   //取消订单
   public function cancelOrder(){
   	  $orderId=getNum($_REQUEST['order_id']);
   	  $orderWhere=array('order_id'=>$orderId,'goods_supplier'=>$this->supplierId);
	  $orderData=M('order_info')->field('order_id,order_status,pay_status,shipping_status,pay_type')->where($orderWhere)->find();
	  if(!$orderData) val_json("submits",false,'非法操作','','','');
	  if($orderData['pay_type']==0 || $orderData['pay_type']==3){
	  	 $orderWhere['pay_status']=0;
	  }
	  $orderWhere['order_status']=array('in','0,1');
	  $orderWhere['shipping_status']=array('lt',2);
	  if(M('order_info')->where($orderWhere)->save(array('order_status'=>-1))){
	  	  $model=new CommonModel();
	  	  $model->returnUser($orderId);//退回用户的优惠政策
	  	  val_json("submits",true,'你的设置操作成功',$_SERVER["HTTP_REFERER"],'','');
	  }else{
	  	  val_json("submits",false,'你的设置操作失败','','','');
	  }
   }
   
   //同意退款
   public function refund(){
       $orderId=getNum($_REQUEST['order_id']);
	   $where['order_id']=$orderId;
	   $where['goods_supplier']=$this->supplierId;
	   $where['order_status']=3;
	   $where['pay_status']=2;
	   $where['shipping_status']=array('gt',1);
	   $where['pay_type']=array('in','0,3'); //在线支付或多余额支付
	   $o=M('order_info');
	   $order=$o->field('order_sn,user_id,goods_amount,order_amount')->where($where)->find();
	   if(!$order)  val_json("submits",false,'不存在此数据','','','');
	   $model=new CommonModel();
	   //启动事务
	   $this->startTrans(); 
	   $startTrans=true;
	   //退回用户的优惠政策	   
	   if(!$model->returnUser($orderId)) $startTrans=false;
	   //操作订单状态
	   if(!$o->where($where)->save(array('order_status'=>4))) $startTrans=false;
	   //返回用户资金
	   if(!M('member')->where(array('id'=>$order['user_id']))->setInc('member_funds',$order['order_amount'])) $startTrans=false;
	   //用户帐户清单
	   $account['member_id']=$order['user_id'];
	   $account['transaction']=0;
	   $account['return_mark']=0;
	   $account['pay_code']='';
	   $account['member_money']=$order['order_amount'];
	   $account['frozen_money']=0;
	   $account['rank_points']=0;
	   $account['pay_points']=0;
	   $account['change_time']=time();
	   $account['change_desc']='退款订单，退回用户资金。订单号：'.$order['order_sn'];
	   $account['change_type']=99;
	   if(!M('member_account')->add($account)) $startTrans=false;
	   if($startTrans){
		     $this->commit();			 
			 val_json('submits2',true,'操作成功',$_SERVER["HTTP_REFERER"],'');		   
	   }else{
		    $this->rollback();	
			val_json('submits2',false,'操作失败','','');
	   }
   }
   	
}
?>