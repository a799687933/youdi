<?php
class BasicCheckOutModel extends CommonModel{
	private $userId=0;
	private $userKey='';
	public function __construct(){
		parent::__construct(); 
		$this->userId=getUserC('SE_USER_ID') ? getUserC('SE_USER_ID') :-1;
	}
	
	//获取用户地址
	public function getAdderss(){
		$sql="SELECT a.*,(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.country) AS r_country,"; //国家
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.province) AS r_province,";
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.city) AS r_city,";
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.district) AS r_district";
		$sql.=" FROM ".PREFIX."member_address AS a WHERE user_id ='{$this->userId}'  ORDER BY a.default_address DESC";
		$address=$this->db->query($sql);	
		foreach($address as $key=>$value){
			$arr=array(0=>0,1=>$value['country'],2=>$value['province'],3=>$value['city'],4=>$value['district']);
			$region=M('region');
			$regAll=array();
			foreach($arr as $k=>$v){
				$str='';
				$regionArr=$region->field('region_id,region_name')->where(array('parent_id'=>$v))->select();
				foreach($regionArr as $kk=>$vv){
					 if($vv['region_id']==$value['country'] || $vv['region_id']==$value['province'] || $vv['region_id']==$value['city'] || $vv['region_id']==$value['district']) $select='selected="selected"';
					 $str.='<option value="'.$vv['region_id'].'" '.$select.'>'.$vv['region_name'].'</option>';
					 $select='';
				}
				if($str) $regAll[$k]=$str;
			}		
			$address[$key]['option']=$regAll;	
		}	
		return $address;		
	}
   
   //获取用户的收货地址，如果未登记录用户获取表单的地址
   public function getUserAddress(){
   	   $selectAdderss=$_REQUEST['selectAdderss'];
   	   if(is_numeric($selectAdderss) && $selectAdderss > 0){
   	   	   $res=M('member_address')->where(array('address_id'=>$selectAdderss,'user_id'=>$this->userId))->find();
   	   }else{
   	   	  $res['consignee']=$_REQUEST['consignee'] ? emptyHtml($_REQUEST['consignee']) : '';
		  $res['email']=$_REQUEST['email'] ? emptyHtml($_REQUEST['email']) : '';
		  $res['country']=$_REQUEST['country'] ? intval($_REQUEST['country']) : 0;
		  $res['province']=$_REQUEST['province'] ? intval($_REQUEST['province']) : 0;
		  $res['city']=$_REQUEST['city'] ? intval($_REQUEST['city']) : 0;
		  $res['district']=$_REQUEST['district'] ? intval($_REQUEST['district']) : 0;
		  $res['address']=$_REQUEST['address'] ? emptyHtml($_REQUEST['address']) : '';
		  $res['zipcode']=$_REQUEST['zipcode'] ? emptyHtml($_REQUEST['zipcode']) : '';
		  $res['tel']=$_REQUEST['tel'] ? emptyHtml($_REQUEST['tel']) : '';
		  $res['mobile']=$_REQUEST['mobile'] ? emptyHtml($_REQUEST['mobile']) : '';
		  $res['sign_building']=$_REQUEST['sign_building'] ? emptyHtml($_REQUEST['sign_building']) : '';
		  $res['best_time']=$_REQUEST['best_time'] ? emptyHtml($_REQUEST['best_time']) : '';
   	   }
       return $res;
   }
  	
}
?>