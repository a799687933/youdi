<?php
// 城市控制器
class CityBasicAction extends Action {
    
	//更多城市列表
    protected function basicIndex(){
	   $this->province=M('region')->field('region_id,region_name')->where("region_type=1 AND is_show=1")->order("orders DESC")->select();	
       $this->display();
	}
	
	//选择城市或区县
	protected function basicSelectCity(){
		if($_GET['city_id'] && $_GET['district_id']) $_GET['city_id']='';
		if(strpos($_SERVER["HTTP_REFERER"],'City') !==false){
			$url=__ROOT__.'/'.is_m();
		}else{
			$url=$_SERVER["HTTP_REFERER"];
		}
		if(!empty($_GET['city_id'])){
			$cityId=getNum($_GET['city_id']);
			$region=M('region')->field('region_id,region_name')->where(array('region_id'=>$cityId,'is_show'=>1))->find();	
			if($region){
				cookie(C('COOKIE_CITY_ID'),$region['region_id'],86400);
				cookie(C('COOKIE_CITY_NAME'),$region['region_name'],86400);				
				cookie(C('COOKIE_DISTRICT_ID'),null);
				cookie(C('COOKIE_DISTRICT_NAME'),null);		
				cookie(C('COOKIE_IS_SELECT'),1,86400);
			}
		    header("location:".$url);
		}else if(!empty($_GET['district_id'])){
			$districtId=getNum($_GET['district_id']);
			$sql="SELECT d.region_id AS d_region_id,d.region_name AS d_region_name,r.region_id,r.region_name FROM ".PREFIX."region AS d ";
			$sql.="JOIN ".PREFIX."region AS r ON (d.parent_id=r.region_id) WHERE d.region_id='{$districtId}' LIMIT 1";
			$region=M()->query($sql);
			if($region[0]){
				$region=$region[0];
				cookie(C('COOKIE_CITY_ID'),$region['region_id'],86400);
				cookie(C('COOKIE_CITY_NAME'),$region['region_name'],86400);
				cookie(C('COOKIE_DISTRICT_ID'),$region['d_region_id'],86400);
				cookie(C('COOKIE_DISTRICT_NAME'),$region['d_region_name'],86400);		
				cookie(C('COOKIE_IS_SELECT'),1,86400);
			}
			header("location:".$url);
		}
	}

	//AJAX跟据城市检索区县
	protected function basciGetRegion(){
         $field=emptyHtml($_GET['field']);
		 $id=emptyHtml($_GET['id']);
		 $region=M('region')->field('region_id,region_name')->where(array('parent_id'=>$id,'is_show'=>1))->select();	
		 $str='';
		 $name=$field =='province' ? 'city_id' : 'district_id';
		 foreach($region as $k=>$v){
			 if($name=='city_id'){
              $str.='<a href="javascript:void(0);" style="position:relative;">';
              $str.=$v['region_name'];
              $str.='<input type="radio"'; 
              $str.='name="'.$name.'"'; 
              $str.='style="position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=0);-moz-opacity:0;-khtml-opacity: 0;opacity: 0;z-index:9999;"';
              $str.='onclick="toCity(this)"';
              $str.='value="'.$v['region_id'].'" />';
              $str.='</a>';			 
			 }else{
              $str.='<a href="javascript:void(0);" style="position:relative;">';
              $str.=$v['region_name'];
              $str.='<input type="radio"'; 
              $str.='name="'.$name.'"'; 
			  $str.='onclick="toCity(this)"';
              $str.='style="position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=0);-moz-opacity:0;-khtml-opacity: 0;opacity: 0;z-index:9999;"';
              $str.='value="'.$v['region_id'].'" />';
              $str.='</a>';					 
			 }
		 }
		 if($str){
			 die($str);
		 }else{
			 die('暂无资料');
		 }
	}

}