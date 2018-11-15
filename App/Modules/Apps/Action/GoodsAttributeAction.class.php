<?php

class GoodsAttributeAction extends CommonAction {
	
	//属性列表
    public function goodsAttributeList(){
       $amp=D('Curd')->search();//询查条件,返回数组
       $amp['searchArr']['goods_attr_type_id']=$_REQUEST['goods_attr_type_id'];
       $counts=D('GoodsAttributeRelation')->relation(true)->where($amp['searchArr'])->count();
       $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $Attribute=D('GoodsAttributeRelation')->relation(true)->where($amp['searchArr'])->limit($GLOBALS['limit'])->order("sort_order ASC")->select();
	   $this->Attribute=$Attribute;
	   $this->url=$amp['url'].'/goods_attr_type_id/'.$_REQUEST['goods_attr_type_id']; //保存查询条件
	   $this->goods_attr_type_id=$_REQUEST['goods_attr_type_id'];
       $this->display();	   
    }
	
	//添加属性
	public function goodsAttributeAdd(){
		$this->goods_type=M('goods_attr_type')->where(array('id'=>$_GET['goods_attr_type_id']))->find();
		if($_GET['id']) $this->save=M('goods_attribute')->where(array('id'=>$_GET['id']))->find();
		$this->attrNames=$this->langIsMore('goods_attribute','name'); //属性名称多语言
		$this->display();
	}
	
	//修改属性表单处理
	public function goodsAttributeSaveForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}else{
			if($_POST['id']){
			     D('Curd')->modify('goods_attribute','goodsAttributeList','','id'); //修改数据	 	
			}else{
			     D('Curd')->insert('goods_attribute','goodsAttributeList');
			}
           				
		}
	}
	
	//删除属性
	public function  goodsAttributeDelete(){
		$ids=getNum($_REQUEST['ids']);
	   $url=U(APP_APPS.'/GoodsAttribute/goodsAttributeList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')],'goods_attr_type_id'=>$_REQUEST['goods_attr_type_id']),'');
	   M('goods_attr')->where(array('goods_attribute_id'=>$ids))->delete();
	   M('goods_attribute_value')->where(array('attribute_id'=>$ids))->delete();
	   D('Curd')->del('goods_attribute','goodsAttributeList',$url);//删除		 
	}
	
	//属性值列表
	public function attrValueList(){
		$attrbuteId=getNum($_GET['attribute_id']);
		$this->values=M('goods_attribute_value')->where(array('attribute_id'=>$attrbuteId))->order("orders ASC")->select();
		$this->attrName=M('goods_attribute')->where(array('id'=>$attrbuteId))->getField(pfix('name'));
	    $this->display();
	}
	
	//添加/修改属性值
	public function attrValueSave(){
	    $attrbuteId=getNum($_GET['attribute_id']);
		$id=getNum($_GET['id']);
		if($id) $this->save=M('goods_attribute_value')->where(array('id'=>$id))->find();
		$this->attrValues=$this->langIsMore('goods_attribute_value','attr_value'); //属性名称多语言
		$this->attrName=M('goods_attribute')->where(array('id'=>$attrbuteId))->getField(pfix('name'));
		$this->attrbuteId=$attrbuteId;
		$this->id=$id;
		$this->display();
	}

	//添加/修改属性值表单处理
	public function attrValueSaveForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}	
		if($_POST['id']){
			 D('Curd')->modify('goods_attribute_value','attrValueList','','id'); //修改数据	 	
		}else{
			 D('Curd')->insert('goods_attribute_value','attrValueList');
		}		
	}
	
	//删除属性值
	public function attrValueDelete(){
	     $ids=emptyHtml($_REQUEST['ids']);
		 if(M('goods_attribute_value')->where(array('id'=>array('in',$ids)))->delete()){
			 return_json(200,'操作成功','','','');
		 }else{
			  return_json(300,'操作失败','','','');
		 }
	}
	
}