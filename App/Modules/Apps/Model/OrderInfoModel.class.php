<?php

class OrderInfoModel extends RelationModel{

	//订单祥情
	public function orderShow(&$_this,$orderId){
		$sql="SELECT o.*,  (SELECT r1.region_name FROM ".PREFIX."region AS r1 WHERE r1.region_id=o.country) AS region_country,"; //国家
		$sql.=" (SELECT r2.region_name FROM ".PREFIX."region AS r2 WHERE r2.region_id=o.province) AS region_province,"; //省份
		$sql.=" (SELECT r3.region_name FROM ".PREFIX."region AS r3 WHERE r3.region_id=o.city) AS region_city,"; //城市
		$sql.=" (SELECT r4.region_name FROM ".PREFIX."region AS r4 WHERE r4.region_id=o.district) AS region_district"; //区县
		$sql.="SELECT o.* ,(SELECT m.mobile_phone FROM ".PREFIX."member AS m WHERE m.id=o.user_id)  AS user_name, ";
		$sql.=" FROM ".PREFIX."order_info_extends AS o WHERE o.order_id ='{$orderId}' LIMIT 1";
		$orders=$this->db->query($sql);
		$orders=$orders[0];
		$sql='';
		$sql.="SELECT o.* ,(SELECT m.mobile_phone FROM ".PREFIX."member AS m WHERE m.id=o.user_id)  AS user_name, ";
		$sql.="(SELECT s.shop_name FROM ".PREFIX."shops AS s WHERE s.id=o.shops_id) AS shop_name ";
		$sql.=" FROM ".PREFIX."order_info AS o WHERE o.order_id='{$orderId}' LIMIT 1";
		$orders1=$this->db->query($sql);
		$orders2=array_merge($orders, $orders1[0]);
		$_this->orders=$orders2;
		
	}


   
}
?>