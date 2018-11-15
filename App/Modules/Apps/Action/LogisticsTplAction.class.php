<?php
// 本类由系统自动生成，仅供测试用途
class LogisticsTplAction extends CommonAction {
    //运费模板列表
    public function tplList(){
		$country="(SELECT r1.region_name FROM ".PREFIX."region AS r1 WHERE r1.region_id=t.country) AS country";
		$province="(SELECT r2.region_name FROM ".PREFIX."region AS r2 WHERE r2.region_id=t.province) AS province";
		$city="(SELECT r3.region_name FROM ".PREFIX."region AS r3 WHERE r3.region_id=t.city) AS city ";
		$district="(SELECT r4.region_name AS district FROM ".PREFIX."region AS r4 WHERE r4.region_id=t.district) AS district";
		$sql="SELECT t.*,{$country},{$province},{$city},{$district} FROM ".PREFIX."logistics_tpl AS t ORDER BY t.id DESC";
		$result=M()->query($sql);
		$this->result=$result;
		$this->display();
    }
	
	//添加物流运费模板
	public function addSaveTpl(){
	   $model=new CommonModel();
	   //物流公司
	   $shipping=M('shipping')->field('shipping_id,shipping_name')->where("enabled=1")->order("orders ASC")->select();   
	   if($_GET['id']){
		   $result=M('logistics_tpl')->where(array('id'=>$_GET['id']))->find();
		   $logisticsTplPrice=M('logistics_tpl_price');
		   foreach($shipping as $k=>$v){
			   $tplPrce=$logisticsTplPrice->where(array('tpl_id'=>$_GET['id'],'shipping_id'=>$v['shipping_id']))->select();
			   foreach($tplPrce as $k1=>$v1){
				   $city=explode(',',$v1['city_id']);
				   $tplPrce[$k1]['city_arr']=$city;
				   $tplPrce[$k1]['province_arr']=explode(',',$v1['province_id']);
				   $tplPrce[$k1]['city_count']=count($city)-1;
			   }			   
		      $shipping[$k]['loginstis']=$tplPrce;
		   }
	   }
	   if($result['pricing'] ==0){
		   $this->type='件';
	   }else if($result['pricing'] ==1){
		   $this->type='g';
	   }else if($result['pricing'] ==2){
	       $this->type='cm³';
	   }
	   $this->result=$result;
	   $this->shipping=$shipping;
	   $this->address=$model->getRegion($result['country'],$result['province'],$result['city'],$result['district']);
	   $city=M('region')->field('region_id,parent_id,region_name')->where("region_type < 3")->order("orders ASC")->select();
	   $city=unlimitedForLayer($city,'parent_id','region_id','child',0);
	   $this->city=$city[0]['child'];

	   $this->display();
	}
	
	//添加修改物流运费模板表单处理
	public function addSaveTplForm(){
		$updateId=$_POST['id'];
		$strats=true;
	    $data['tpl_name']=$_POST['tpl_name'] ? emptyHtml($_POST['tpl_name']) : return_json(300,'请填写模板名称');
		$data['country']=$_POST['country'] > 0 ? intval($_POST['country']) : return_json(300,'请选择商品发货国家');
		$data['province']=$_POST['province'] > 0 ? intval($_POST['province']) : return_json(300,'请选择商品发货省份');
		$data['city']=$_POST['city'] > 0 ? intval($_POST['city']) : return_json(300,'请选择商品发货城市');
		$data['district']=$_POST['district'] > 0 ? $_POST['district'] : 0;
		$data['delivery_time']=$_POST['delivery_time'] ? emptyHtml($_POST['delivery_time']) : return_json(300,'请选择商品发货发货时间');
		$data['free_shipping']=$_POST['free_shipping'] >0 ? $_POST['free_shipping'] : 0;
		$data['pricing']=$_POST['pricing'] > 0 ? $_POST['pricing'] : 0;
		$data['add_time']=time();
		//不免运费模板
		if($data['free_shipping']==0){
			//默认运费部分
			$data['default_price']=$_POST['default_price'] > 0 ? $_POST['default_price'] : return_json(300,'请填写默认运费首费');
			$data['default_number']=$_POST['default_number'] > 0 ? $_POST['default_number'] : return_json(300,'请填写默认运费首数量');
			$data['add_number']=$_POST['add_number'] > 0 ? $_POST['add_number'] : return_json(300,'请填写默认运费续件数量');
			$data['add_price']=$_POST['add_price'] > 0 ? $_POST['add_price'] : return_json(300,'请填写默认运费续件加价数量');		
			//指定城市运费部分
            $shipping=M('shipping')->field('shipping_id,shipping_name')->where("enabled=1")->order("orders ASC")->select();
			//获得模板ID
			if($updateId){//修改
				$id=$updateId;
				M('logistics_tpl')->where("id='{$id}'")->save($data);
			}else{
				if(!!!$id=M('logistics_tpl')->add($data)) $strats=false;
			}	
			$allArray=array();
			foreach($shipping as $s=>$sv){
				if($_POST['shipping_'.$sv['shipping_id']]){
						$tplPrice=array();
						$provinceId=$_POST['province_id'.$sv['shipping_id']]; //省分ID集合
						$cityIdString=$_POST['city_id'.$sv['shipping_id']]; //城市ID集合
						$cityIdNameString=$_POST['city_name'.$sv['shipping_id']];//城市名称集合
						$firstNumber=$_POST['first_number'.$sv['shipping_id']];//区域首付个数
						$firstPrice=$_POST['first_price'.$sv['shipping_id']];//区域首付价格
						$addNumeberMoer=$_POST['add_numeber_moer'.$sv['shipping_id']];//区域续件数量
						$addPriceMoer=$_POST['add_price_moer'.$sv['shipping_id']];//区域续件数量加的价格		
						if($firstNumber){						
							$i=0;
							foreach($firstNumber as $k=>$v){
								if(!is_numeric($v) || $v < 1) return_json(300,'《'.$sv['shipping_name'].'》第('.($k+1).')区域首数量格式错误'); 
								if(empty($cityIdString[$k])) return_json(300,'《'.$sv['shipping_name'].'》请在第('.($k+1).')个区域编辑城市'); 
								if(empty($cityIdNameString[$k])) return_json(300,'非法操作1'); 
								if(empty($provinceId[$k])) return_json(300,'非法操作2'); 
								if(!is_numeric($firstPrice[$k]) || $firstPrice[$k] < 0) return_json(300,'《'.$sv['shipping_name'].'》第('.($k+1).')区域首运费格式错误'); 
								if(!is_numeric($addNumeberMoer[$k]) || $addNumeberMoer[$k] < 1) {
									return_json(300,'《'.$sv['shipping_name'].'》第('.($k+1).')区域续运费数量格式错误');
								} 
								if(!is_numeric($addPriceMoer[$k]) || $addPriceMoer[$k] < 0) {
									return_json(300,'《'.$sv['shipping_name'].'》第('.($k+1).')区域续运费价格格式错误'); 
								}
								$tplPrice[$i]['tpl_id']=$id;
								$tplPrice[$i]['shipping_id']=$sv['shipping_id'];
								$tplPrice[$i]['province_id']=$provinceId[$k];
								$tplPrice[$i]['city_id']=$cityIdString[$k];
								$tplPrice[$i]['city_name']=$cityIdNameString[$k];
								$tplPrice[$i]['pricing']=$data['pricing'];
								$tplPrice[$i]['first_number']=$firstNumber[$k];
								$tplPrice[$i]['first_price']=$firstPrice[$k];
								$tplPrice[$i]['add_numeber_moer']=$addNumeberMoer[$k];
								$tplPrice[$i]['add_price_moer']=$addPriceMoer[$k];
								$i++;
								
							}
							$allArray[$s]=$tplPrice;
						}
				}
			}//第一个foreach 结束
			//写入运费模板
			if($allArray){
				$addAll=array();
				$i=0;
				foreach($allArray as $ks=>$vs){
					 if($vs){
						 foreach($vs as $ks2=>$vs2){
						    $addAll[$i]=$vs2;
							$i++;
						 }
					}
				}
			}
			if($addAll){
				 if($updateId){//修改
				    M('logistics_tpl_price')->where("tpl_id='{$id}'")->delete();
				    M('logistics_tpl_price')->addAll($addAll);
				 }else{
				    M('logistics_tpl_price')->addAll($addAll);
				 }
			}else{
			    M('logistics_tpl_price')->where("tpl_id='{$id}'")->delete();
			}
		}else{//免运费模板
			if($updateId){//修改
				$id=$updateId;
				M('logistics_tpl')->where("id='{$id}'")->save($data);
			}else{
				if(!!!$id=M('logistics_tpl')->add($data)) $strats=false;
			}			
		}
		return_json(200,'操作成功！','tplList','','closeCurrent');
	}
	
	//删除物流运费模板
	public function loginsticeDelete(){
		$id=$_REQUEST['ids'];
		$statrs=true;
		if(M('logistics_tpl')->where("id IN({$id})")->delete()){
			M('logistics_tpl_price')->where("tpl_id IN({$id})")->delete();
			return_json(200,'操作成功！','','','forward',U(APP_APPS.'/LogisticsTpl/tplList'));
		}else{
			return_json(300,'操作失败'); 
		}
	}
	
}