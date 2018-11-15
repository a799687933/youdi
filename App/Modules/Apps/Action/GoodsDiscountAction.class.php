<?php
class GoodsDiscountAction extends CommonAction{
    
    //优惠活动列表
    public function discountList(){
  	   $order=$this->orderBy();//临时排序，返回数组
	   $amp=D('Curd')->search('discu_name'); //按条件查询
	   $this->callAjaxPage(C('BGPAGENUM'),M('goods_discount')->where($amp['searchArr'])->count());//分页
	   $this->promot=M('goods_discount')->where($amp['searchArr'])->order($order)->limit($GLOBALS['limit'])->select();
	   $this->searchVal=$_REQUEST['discu_name'];
	   if(isset($_REQUEST['discu_name'])) $name='/discu_name/'.$_REQUEST['discu_name'];
       $this->url=__ACTION__.$name;
       $this->display();
    }
    
    //添加/修改优惠活动界面
    public function discountAddSave(){
	 
	  if($_GET['send']=='update'){
	  	  $catHtml=null;
		  $result=M('goods_discount')->where(array('id'=>$_GET['id']))->find();
          $result['member_grade_id']=explode(',', $result['member_grade_id']); //被选定的会员等级数组
          $dis_range_ext=explode(',', $result['dis_range_ext']);//被选定的优惠范围的约束数组
          $dis_goods_id=explode(',', $result['dis_goods_id']);//被选定的赠品的ID集数组	
          if($result['dis_range']==0){
          	  $catHtml.='<option value="0">所有商品进行优惠活动</option>';
          	  $chebox.='<span><input type="checkbox" name="dis_range_ext[]" value="0" class="select-ids" checked="checked" style="position:relative;top:2px;">全部商品</span>';
          }else	if($result['dis_range']==1){
			  $Cat=D('Curd')->cage_select('goods_category','id,'.pfix('name').' AS name,pid','','reorder asc');//商品分类
			  foreach($Cat as $k=>$v){
			  	  $catHtml.='<option value="'.$v['id'].'">'.$v['html'].$v['name'].'</option>';
			  }
              $dis_result=M('goods_category')->field('id,'.pfix('name').' AS name')->where(array('id'=>array('IN',($dis_range_ext))))->select();//优惠范围取值
              foreach ($dis_result as $key => $value) {
                  $chebox.='<span><input type="checkbox" name="dis_range_ext[]" value="'.$value['id'].'" class="select-ids" checked="checked" style="position:relative;top:2px;">'.$value['name'].'</span>';
              }
          }else if($result['dis_range']==2){
          	  $Cat=M('goods_brand')->field('id,'.pfix('name').' AS name_cn')->select();
			  foreach ($Cat as $key => $value) {
				  $catHtml.='<option value="'.$key['id'].'">'.$value['name_cn'].'</option>';
			  }
              $dis_result=M('goods_brand')->field('id,'.pfix('name').' AS name_cn')->where(array('id'=>array('IN',($dis_range_ext))))->select();//优惠范围取值
              foreach ($dis_result as $key => $value) {
                  $chebox.='<span><input type="checkbox" name="dis_range_ext[]" value="'.$value['id'].'" class="select-ids" checked="checked" style="position:relative;top:2px;">'.$value['name_cn'].'</span>';
              }			  
          }else if($result['dis_range']==3){
          	  $Cat=M('goods')->field('id,'.pfix('goods_name').' AS goods_name')->select();
			  foreach ($Cat as $k => $v) {
				  $catHtml.='<option value="'.$v['id'].'">'.$v['goods_name'].'</option>';
			  }
              $dis_result=M('goods')->field('id,'.pfix('goods_name').' AS goods_name,goods_retail_price')->where(array('id'=>array('IN',($dis_range_ext))))->select();//优惠范围取值
              foreach ($dis_result as $key => $value) {
                  $chebox.='<span><input type="checkbox" name="dis_range_ext[]" value="'.$value['id'].'" class="select-ids" checked="checked" style="position:relative;top:2px;">'.$value['goods_name'].'(￥'.$value['goods_retail_price'].'元)</span>';
              }					  
          }
		  $this->cat=$catHtml;//被选定的类型数据
		  $this->chebox=$chebox;  //优惠范围取值		  
		  
		  //优惠方式如果是赠品
		  if($result['dis_type']==0){
              $dis_result=M('goods')->field('id,'.pfix('goods_name').' AS goods_name,goods_retail_price')->where(array('id'=>array('IN',($dis_goods_id))))->select();//优惠范围取值
              foreach ($dis_result as $key => $value) {
                  $chebox2.='<span><input type="checkbox" name="dis_goods_id[]" value="'.$value['id'].'" class="select-id" checked="checked" style="position:relative;top:2px;">'.$value['goods_name'].'(￥'.$value['goods_retail_price'].'元)</span>';
              }		
			  $this->echebox2=$chebox2;			  	 
		  }
		  $this->result=$result;
	  }
      //会员等级
	  $this->leve=M('member_grade')->field('id,'.pfix('grade_name').' AS grade_name')->order(array('id'=>'ASC'))->select(); 
	  //价格和数量表数据
	  $discountExpand=M('goods_discount_expand')->where(array('goods_discount_id'=>$_GET['id']))->order("amount ASC")->select();	  
	  if(!$discountExpand){
	  	  $discountExpand[0]['id']=0;
		  $discountExpand[0]['amount']=0;
		  $discountExpand[0]['dis_type_val']=0;
		  $discountExpand[0]['goods_discount_id']=0;
	  }
	  $this->discountExpand=$discountExpand;
	  $this->disName=$this->langIsMore('goods_discount','dis_name'); //广告类型多语言
   	  $this->display();
    }

    //添加/修改优惠活动表单处理
    public function AddSaveForm(){
    	if(empty($_POST['member_grade_id'])) return_json(300,'必须选择优惠的会员等级！');
		if(empty($_POST['dis_range_ext'])) return_json(300,'必须选择优惠范围！');
		 //会员等级ID集合
		$_POST['member_grade_id']=implode(',',$_POST['member_grade_id']);
		//优惠范围的约束ID集合；如，如果是商品，该处是商品的id，如果是品牌，该处是品牌的id
		$_POST['dis_range_ext']=implode(',',$_POST['dis_range_ext']); 
		//如果是选择赠品则是赠品的ID集，否则留空
		if(!empty($_POST['dis_goods_id']) && $_POST['dis_type']==0){
			$_POST['dis_goods_id']=implode(',', $_POST['dis_goods_id']);
	    }else{
	    	$_POST['dis_goods_id']='';
	    }		
		if(!$_POST['amount'][0]) return_json(300,'请输入订单金额');
		if(!$_POST['dis_type_val'][0]) return_json(300,'请输入现金减免金额');
   		$start=sqlTime($_POST['start_date']);
		$_POST['start_date']=$start['startTime']; //促销活动开始时间
		$end=sqlTime($_POST['end_date']);
		$_POST['end_date']=$end['endTime']; //促销活动结束时间		
    	if($_POST['send']=='add'){
    		$id=M('goods_discount')->add($_POST);
            $this->_discountExpand($id,false);//写入扩展表
			return_json(200,'操作成功！','discountList','','closeCurrent');
    	}else if($_POST['send']=='update'){
    		$id=M('goods_discount')->where(array('id'=>$_POST['id']))->save($_POST);
            $this->_discountExpand($_POST['id'],true);//修改扩展表
			return_json(200,'操作成功！','discountList','','closeCurrent');
    	}
    }

   //写入优惠活动扩展表
   private function _discountExpand($id,$isUpdate){
   	        $disExpand=M('goods_discount_expand');			
     		$inserts=array();
			$i=0;
			foreach($_POST['amount'] as $k=>$v){
				if($v && $_POST['dis_type_val'][$k]){
					$val=$_POST['dis_type_val'][$k];
					if($_POST['dis_type']==1){
						if($val > 100) $val=100;
						if($val < 10) $val=$val * 10;
					}					
					$inserts[$i]['amount']=$v;
					$inserts[$i]['dis_type_val']=$val;
					$inserts[$i]['goods_discount_id']=$id;
					$i++;
				}
			}
			if($isUpdate) $disExpand->where(array('goods_discount_id'=>$id))->delete();
			return $disExpand->addAll($inserts);  	
   }
	
    //修改排序
	public function saveSort(){
		$this->saveOen();
	}
	
	//删除优惠活动
	public function discountDelete(){
		$url=U(APP_APPS.'/GoodsDiscount/discountList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]),'');
		M('goods_discount_expand')->where(array('goods_discount_id'=>array('in',$_REQUEST['ids'])))->delete();
		D('Curd')->del('goods_discount','discountList',$url);
	}
	
	//Ajax查询选择优惠范围
	public function ajaxCat(){
		$str=null;		
		if($_GET['id']==1){
			$inId=$this->discoInIds(1);
		    if($inId) $where="id NOT IN ({$inId}) ";
			$result=D('Curd')->cage_select('goods_category','id,'.pfix('name').' AS name,pid',$where,'reorder asc');//商品分类			
			foreach($result as $k=>$v){
				$str.='<option value="'.$v['id'].'">'.$v['html'].$v['name'].'</option>';
			}
		}else if($_GET['id']==2){
			$inId=$this->discoInIds(2);
			if($inId) $where="id NOT IN ({$inId}) ";
			$result=M('goods_brand')->field('id,'.pfix('name').' AS name_cn')->where($where)->order(array('orders'=>'DESC'))->select();
			foreach($result as $k=>$v){
				$str.='<option value="'.$v['id'].'">'.$v['name_cn'].'</option>';
			}
		}else if($_GET['id']==3){
			if(!empty($_GET['where'])) $where.=pfix('goods_name')." LIKE '%{$_GET['where']}%' ";		
			$result=M('goods')->field('id,'.pfix('goods_name').' AS goods_name,goods_retail_price')->where($where)->select();
			foreach($result as $k=>$v){
				$str.='<option value="'.$v['id'].'">'.$v['goods_name'].'(￥'.$v['goods_retail_price'].'元)</option>';
			}			
		}
		die($str);
	}
    
	//Ajax查询选择优惠范围过滤已有的优惠活动数据
	private function discoInIds($num){
		$discountExpand=M('goods_discount')->field('dis_range_ext')->where(array('dis_range'=>$num))->select();		
		if(!$discountExpand) return false;
		$temp=array(0);
		foreach($discountExpand as $k=>$v){
			$idsArr=explode(',', $v['dis_range_ext']);
			$temp=array_merge($temp,$idsArr);
		}	        	
		$arrayUnique=array_unique($temp);		
		return implode(',',$arrayUnique);
	}
	
}
?>