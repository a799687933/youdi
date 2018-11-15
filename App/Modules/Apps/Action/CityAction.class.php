<?php

class CityAction extends CommonAction{
 
     //国家列表
    public function index(){
		$this->CityArr=M('region')->where(array('parent_id'=>0))->order("orders ASC")->select();
        $this->display();
    }
	   
    //省份列表
    public function province(){
		$this->privateList();
        $this->display();
    }
	
	//城市列表
	public function cityList(){
		$this->privateList();
        $this->display();	
	}
	
	//区县列表
	public function districtList(){
		$this->privateList();
        $this->display();	
	}	
	//街道列表
	public function streetList(){
		$this->privateList();
        $this->display();	
	}		
	
	//公共列表 
	private function privateList(){
		if($_GET['region_id'] > 0) {
			$where['parent_id']=$_GET['region_id'];
			$this->CityArr=M('region')->where($where)->order("orders ASC")->select();
		}else{
			$where['region_type']=array('lt',2);
			$cate=M('region')->where($where)->order("orders ASC")->select();
			import('Class.Category',APP_PATH);//无限级分类类
			$this->CityArr=Category::unlimitedForLevel($cate,'region_id','parent_id');			
		}	
	}
    
    //添加城市
    public function cityAdd(){
		//if($_GET['region_id']=='') return_json(300,'非法操作');
		$this->code=md5($_GET['city_pid'].$_GET['action'].C('APP_KEY'));
        $this->display();
    }
    
    //添加城市表单处理
    public function cityAddForm(){
		$regionId=$_REQUEST['region_id'] ? $_REQUEST['region_id'] : 0;
		$regionName=$_REQUEST['region_name'] ? emptyHtml($_REQUEST['region_name']) : return_json(300,'名称不可空');
		$regionCbc=$_REQUEST['region_abc'] ? emptyHtml($_REQUEST['region_abc']) : return_json(300,'ABC字母检索不可空');
		$orders=$_REQUEST['orders'] > 0 ? $_REQUEST['orders'] :0;
		$isShow=$_REQUEST['is_show'] > 0 ? $_REQUEST['is_show'] :0;
		$action=$_REQUEST['action'] ? $_REQUEST['action'] : return_json(300,'非法操作1');
		$code=$_REQUEST['code'] ? $_REQUEST['code'] : return_json(300,'非法操作2');
		if(md5($regionId.$action.C('APP_KEY')) !=$code) return_json(300,'非法操作3');
        $region=M('region')->where(array('region_id'=>$regionId))->find();
		//if(!$region)  return_json(300,'非法操作');
		$data['parent_id']=$regionId;
		$data['region_name']=$regionName;
		$data['region_abc']=$regionCbc;
		$data['region_type']=$region['region_type'] + 1;
		$data['agency_id']=0;
		$data['orders']=$orders;
		$data['is_show']=$isShow;
        if(M('region')->add($data)){
            return_json(200,'操作成功！',$action,'','closeCurrent');
        }else{
            return_json(300,'操作失败！');
        }           
    }
	
	//修改城市
	public function citySave(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
	}
    
    //删除城市
    public function cityDelete(){
        $regionId=$_REQUEST['ids'];  
		$region=M('region');
		if($region->field('region_id')->where(array('parent_id'=>$regionId))->find()) return_json(300,'请先删除子级区域？');
        if($region->where(array('region_id'=>$regionId))->delete()){
            return_json(200,'删除成功！','','','forward',U(APP_APPS.'/City/'.$_REQUEST['action'],array('region_id'=>$_REQUEST['region_pid'])));
        }else{
            return_json(300,'删失败！');
        }  
    }
}
?>