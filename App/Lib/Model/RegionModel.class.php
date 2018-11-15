<?php
/*国家/省份/城市处理*/
class RegionModel extends Model{
	
    /**把my_region城市表数据放入内存
	*
   **/	
	public function regionToArray(){
			//国家
			$cou=$this->field('region_id,region_name')->where(array('region_type'=>0))->order('orders ASC')->select();
			foreach($cou as $k=>$v){
				$country[$v['region_id']]=$v['region_name'];
			}
			unset($cou);
			//省份
			$pro=$this->field('region_id,region_name')->where(array('region_type'=>1))->order('orders ASC')->select();
			foreach($pro as $k=>$v){
				$province[$v['region_id']]=$v['region_name'];
			}
			unset($pro);	
			//城市
			$cit=$this->field('region_id,region_name')->where(array('region_type'=>2))->order('orders ASC')->select();
			foreach($cit as $k=>$v){
				$city[$v['region_id']]=$v['region_name'];
			}
			unset($cit);		
			//区县
			$dis=$this->field('region_id,region_name')->where(array('region_type'=>3))->order('orders ASC')->select();
			foreach($dis as $k=>$v){
				$district[$v['region_id']]=$v['region_name'];
			}
			unset($dis);			
			return array('country'=>$country,'province'=>$province,'city'=>$city,'district'=>$district);
	}
	
	/**获取已选择的option列表或地区数组
	*$regionArr  
	*$isArray  true 返回数组，false 返回select option 下拉框
	*使用getRegion(array('country'=>0,'province'=>10,'city'=>52,'district'=>33),false);
	*return array()
	*/
	public function getRegion($regionArr,$isArray=false){
		$data=array();
		//有国家选择
		$couOption='';
		$ec=array();
	    if(array_key_exists('country',$regionArr)){
			$ec=$this->field('region_id,region_name')->where("region_type=0")->order('orders ASC')->select();
			foreach($ec as $k=>$v){
				if($v['region_id']==$regionArr['country']) {
					$selected='selected="selected"';
					$ec[$k]['on']=true;
				}else {
					$selected='';
				}
				$couOption.='<option value="'.$v['region_id'].'" '.$selected.'>'.$v['region_name'].'</option>';
			}
			if($isArray) $data['country']=$ec;
			else $data['country']=$couOption;
			unset($couOption);
			unset($ec);
		}
		//有省份选择
		$proOption='';
		if(array_key_exists('province',$regionArr)){
			$regionArr['country']=!$regionArr['country'] ? 1 : $regionArr['country'];
			if($regionArr['country'] > 0) $pw="region_type=1 AND parent_id='{$regionArr['country']}' AND is_show=1";
			else $pw="region_type=1 AND is_show=1";
			$pr=$this->field('region_id,region_name')->where($pw)->order('orders ASC')->select();
			foreach($pr as $k=>$v){
				if($v['region_id']==$regionArr['province']) {
					$selected='selected="selected"';
					$pr[$k]['on']=true;
				}else{ 
				    $selected='';
				}
				$proOption.='<option value="'.$v['region_id'].'" '.$selected.'>'.$v['region_name'].'</option>';				
			}
			if($isArray) $data['province']=$pr;
			else $data['province']=$proOption;
			unset($proOption);			
			unset($pr);
		}
		//有城市选择
		$cityOption='';
		if(array_key_exists('city',$regionArr)){
			if($regionArr['province'] > 0) $cw="parent_id='{$regionArr['province']}' AND is_show=1";
			else $cw="region_type=2 AND is_show=1";	
			$ci=$this->field('region_id,region_name')->where($cw)->order('orders ASC')->select();
			foreach($ci as $k=>$v){
				if($v['region_id']==$regionArr['city']) {
					$selected='selected="selected"';
					$ci[$k]['on']=true;
				}else {
					$selected='';
				}
				$cityOption.='<option value="'.$v['region_id'].'" '.$selected.'>'.$v['region_name'].'</option>';				
			}
			if($isArray) $data['city']=$ci;
			else $data['city']=$cityOption;
			unset($cityOption);				
			unset($ci);			
		}
		//有区县选择
		$disOption='';
		if(array_key_exists('district',$regionArr)){
			if($regionArr['city'] > 0){
				$dw="parent_id='{$regionArr['city']}' AND is_show=1";
				$dis=$this->field('region_id,region_name')->where($dw)->order('orders ASC')->select();
				foreach($dis as $k=>$v){
					if($v['region_id']==$regionArr['district']) {
						$selected='selected="selected"';
						$dis[$k]['on']=true;
					}else {
						$selected='';
					}
					$disOption.='<option value="'.$v['region_id'].'" '.$selected.'>'.$v['region_name'].'</option>';				
				}
				if($isArray) $data['district']=$dis;
				else $data['district']=$disOption;
				unset($disOption);					
				unset($dis);						
			}
		}
		return $data;
	}
	
	/***根据ID集合直接返回中文名称
	*$idArr  array(1,2,3)
	*使用 getNames(array(6,76));
	*return string
	*/
	public function getNames($idArr){
	    $inid=implode(',',$idArr);
		$result=$this->field('region_id,region_name')->where("region_id IN ({$inid})")->order("parent_id ASC")->select();
		return $result[0]['region_name'].$result[1]['region_name'].$result[2]['region_name'].$result[3]['region_name'];
	}
}
?>