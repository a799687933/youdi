<?php
// 城市控制器
class CityAction extends CityBasicAction {
    
	//更多城市列表
    public function index(){
         $this->basicIndex();
	}
	
	//选择城市或区县
	public function selectCity(){
		$this->basicSelectCity();
	}
	
	//检索下级数据
	public function getRegion(){
		$this->basciGetRegion();
	}

}