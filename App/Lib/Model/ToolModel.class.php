<?php
/*工具模型*/
class ToolModel extends Model{
    
	/**
	 *重组POST数组与数据表一致
	 *$tableName 表名称
	 */
	public function requestArrayModel($tableName){
		$res=M()->query("SHOW FULL COLUMNS FROM ".PREFIX.$tableName."");
		foreach($res as $k=>$v){
			 if(strtoupper($v['Key']) !==strtoupper('PRI')){
				  if((strpos(strtoupper($v['Type']),'INT') !==false) || 
					  (strpos(strtoupper($v['Type']),'DECIMAL') !==false) || 
					  (strpos(strtoupper($v['Type']),'TINYINT') !==false) || 
					  (strpos(strtoupper($v['Type']),'MEDIUMINT') !==false) || 
					  (strpos(strtoupper($v['Type']),'BIGINT') !==false)){
						$_POST[$v['Field']]=$_POST[$v['Field']] ? trim($_POST[$v['Field']]) : 0;			  	  	
				  }else if(
					 (strpos(strtoupper($v['Type']),'CHAR') !==false) || 
					 (strpos(strtoupper($v['Type']),'BINARY') !==false) || 
					 (strpos(strtoupper($v['Type']),'BLOB') !==false) || 
					 (strpos(strtoupper($v['Type']),'TEXT') !==false)){
					  if(!is_array($_POST[$v['Field']])){
						 $_POST[$v['Field']]=$_POST[$v['Field']] ? trim($_POST[$v['Field']]) : '';		
					  }					  	  	  
				  }else{
					 $_POST[$v['Field']]=$_POST[$v['Field']] ? trim($_POST[$v['Field']]) : '';		
				  }
			 }
		} 	
	}
    
	/**
	 *是否使用多语言(表字段命名规则 cn_name,us_name)
	 * $table_name  表名称
	 * $field               某字段是否多语言(titletext) 
	 * langIsMore('article','titletext');
	 * return array();
	 */
	 public function langIsMoreModel($table_name,$field){
	    $sql="select COLUMN_NAME from information_schema.COLUMNS where table_name = '".PREFIX.$table_name."' and table_schema ='".C('DB_NAME')."'";		
		$res=M()->query($sql);
		//如果开启多语言
	    if(C('LANG_SWITCH_ON')){
			$langArr=C('LANG_LIST');
		}else{ //默认语言
			$langArr=array(C('DEFAULT_LANG'));
		}
		$langInfoArr=C('LANG_LIST_INFO');
		$returnArr=array();
		foreach($langArr as $lk=>$lv){
			$la=explode('-', $lv);
			foreach($res as $k=>$v){
				if(strtoupper($la[1]).'_'.strtoupper($field)==strtoupper($v['COLUMN_NAME'])){
					$returnArr[$lk]['info']=$langInfoArr[$lk];
					$returnArr[$lk]['field']=$v['COLUMN_NAME'];
					break;
				}
			}			
		}	
		return $returnArr;		
	 }

	/**
	 * 获取综合检索数据
	 * $typeWhere  可指定类型 hensive('0,1,2');不指定类型则是全部
	 * */
	public function hensive($typeWhere=''){
		if($typeWhere) $typeWhere=" AND type_where IN ({$typeWhere})";
		$result=M('goods_category_combination')->field('id,name,pid,type_where')->where('is_show=1'.$typeWhere)->order("type_where,pid,reorder ASC")->select();
		$gccId=$result[0]['id'];
		$i=0;
		$j=0;
		$arrays=array();
		foreach($result as $k=>$v){
			if($gccId==$v['id']){
				if($v['pid']==0) {
					$arrays[$v['type_where']]['name']=$v['name'];
					$arrays[$v['type_where']]['id']=$v['id'];
					$i=$j;	
			    }else{
					$arrays[$v['type_where']]['child'][$j]['name']=$v['name'];
					$arrays[$v['type_where']]['child'][$j]['id']=$v['id'];						
				}						
			}else{
				$gccId=$v['id'];
				if($v['pid']==0) {
					$arrays[$v['type_where']]['name']=$v['name'];
					$arrays[$v['type_where']]['id']=$v['id'];	
					$i=0;
			    }else{
					$arrays[$v['type_where']]['child'][$j]['name']=$v['name'];
					$arrays[$v['type_where']]['child'][$j]['id']=$v['id'];						
				}				
			}
			$j++;
		}
		return $arrays;
	}	

	//显示用户点击现金卷
	public function showBonusType(){
		$times=time();
	    $where['send_start_date']=array('elt',$times);
		$where['send_end_date']=array('egt',$times);
		$where['send_type']=6;
		$where['is_show']=1;
		$field="id,".pfix('bonus_name')." AS bonus_name,picture,bonus_money,min_amount,send_start_date,send_end_date,use_start_date,use_end_date";
		$result=M('bonus_type')->field($field)->where($where)->order(array('orders'=>'ASC'))->select();
		return $result;
	}

	/**跟据用户选择的城市直接锁定用户收货地址所选择区域
	*$cityId  城市ID
	*返回 (国、省、市、且)
	**/
	public function lockCity($cityId=''){
		if(empty($cityId)) $cityId=cookie('city_id');
		$db=M('region');
		$sql="SELECT r0.region_id AS g_id,r0.region_name AS g_name,r1.region_id AS p_id,r1.region_name  AS p_pro_name,r2.region_id AS c_id,";
		$sql.="r2.region_name AS c_city_name FROM ".PREFIX."region AS r1 JOIN ".PREFIX."region AS r2 ON (r1.region_id=r2.parent_id) ";
		$sql.="JOIN ".PREFIX."region AS r0 ON(r0.region_id=r1.parent_id) WHERE r2.region_id='".$cityId."' LIMIT 1";
		$region=$db->query($sql);
		$arrays=array();
		$arrays['country']['region_id']=$region[0]['g_id'];
		$arrays['country']['region_name']=$region[0]['g_name'];		
		$arrays['province']['region_id']=$region[0]['p_id'];
		$arrays['province']['region_name']=$region[0]['p_pro_name'];
		$arrays['city']['region_id']=$region[0]['c_id'];
		$arrays['city']['region_name']=$region[0]['c_city_name'];		
		//所在区县
		$district=$db->field('region_id,region_name')->where(array('parent_id'=>$cityId))->select();
		$strassing=array();
		foreach($district as $k=>$v){
			if($v['region_id']==cookie('district_id')){
				$strassing[0]['region_id']=$v['region_id'];
				$strassing[0]['region_name']=$v['region_name'];
				break;
			}
		}
		if(empty($strassing)) $strassing=$district;
		$arrays['district']=$strassing;	
		return $arrays;
	}
	
}
?>