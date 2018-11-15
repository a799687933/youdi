<?php

class BonusModel extends Model{
	
	//按用户发放现金券
	public function userSend(){
		if(empty($_POST['member_id'])) return_json(300,'至少选择一个用户！');
		foreach ($_POST['member_id'] as $key => $value) {
			$data.="('{$_POST["bonus_type_id"]}','{$_POST['type']}','{$value}'),";
		}
		if($data){
			$data=rtrim($data,',');
		}
		$sql="INSERT INTO ".PREFIX."member_bonus (bonus_type_id,send_type,member_id) VALUES ".$data;
		M()->query($sql);
		return_json(200,'操作成功！','bonusList','','closeCurrent');
	}
	
	//按商品发放现金券
	public function goodsSend(){
		if(empty($_POST['goods_id'])) return_json(300,'至少选择一个商品！！');
		$strId=implode(',',$_POST['goods_id']);
		M('goods')->where(array('bonus_type_id'=>$_POST['bonus_type_id']))->save(array('bonus_type_id'=>0));//把所有改为0
		$amp=array();
		$amp['id']=array('IN',$strId);
	    $amp['bonus_type_id']=$_POST['bonus_type_id'];
        $info=M('goods')->save($amp);
		$info ? return_json(200,'操作成功！','bonusList','','closeCurrent') : return_json(300,'操作失败！','bonusList','','closeCurrent');
	}
	
	//按线下发放现金券
	public function offlineSend(){
	    /* 生成现金券序列号 */
        $bonus_typeid = !empty($_POST['bonus_type_id']) ? $_POST['bonus_type_id'] : 0;
        $bonus_sum    = !empty($_POST['bonus_sum'])     ? $_POST['bonus_sum']     : 1;	    
	    $num=M('member_bonus')->field('MAX(bonus_sn) AS sn')->find();
	    $num = $num['sn'] ? floor($num['sn'] / 10000) : 100000;	
	    for ($i = 0, $j = 0; $i < $bonus_sum; $i++){
	        $bonus_sn = ($num + $i) . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
	       $data.="('$bonus_typeid','{$_POST['type']}', '$bonus_sn'),";	
	        $j++;
	    }
		if($data){
			$data=rtrim($data,',');
			M()->query("INSERT INTO ".PREFIX."member_bonus (bonus_type_id,send_type,bonus_sn) VALUES ".$data);
			return_json(200,'操作成功！','bonusList','','closeCurrent');
		}
	}
	
	//现金券列表
	public function ticketListM($type='',$order='',$limit=''){
		$limit=$limit ? " LIMIT ".$limit : '';
		$field=$type ? " m.*,IF(me.nickname <> '',me.nickname,me.user_name) AS user_name,b.".pfix('bonus_name')." AS bonus_name " : 'count(m.id) AS counts ';
		$sql="SELECT ".$field." FROM (".PREFIX."member_bonus AS m LEFT JOIN ".PREFIX."member AS me ON(m.member_id=me.id)) JOIN ".PREFIX."bonus_type AS b ON(m.bonus_type_id=b.id)";
		$sql.="WHERE m.bonus_type_id='{$_REQUEST['bonus_type_id']}' ".$order.$limit;
		$result=M()->query($sql);		
		if($type){
			return $result;
		}else{
			return $result[0]['counts'];
		}
	}
}
?>