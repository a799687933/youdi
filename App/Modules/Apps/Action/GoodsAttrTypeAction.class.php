<?php

class GoodsAttrTypeAction extends CommonAction {
	
	//属性类型
	public function attrTypeList(){
		$sql="select t.*,(select count(b.id)  FROM ".PREFIX."goods_attribute AS b WHERE b.goods_attr_type_id=t.id) AS count_num FROM ".PREFIX."goods_attr_type AS t ORDER BY t.id ASC";
		$this->attr_type=M()->query($sql);
		$this->display();
	}
	
	//添加/修改属性类型
	public function attrTypeAdd(){
		if($_GET['id']) $this->save=M('goods_attr_type')->where(array('id'=>$_GET['id']))->find();
		$this->attrNames=$this->langIsMore('goods_attr_type','name'); //属性名称多语言
		$this->display();
	}
	
	//添加/修改属性类型表单处理
	public function attrTypeForm(){
		if($_GET['one']=='ok'){//ajax修改一个字段
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
		if($_REQUEST['id']){
			D('Curd')->modify('goods_attr_type','attrTypeList');//修改	
		}else{
			D('Curd')->insert('goods_attr_type','attrTypeList');//插入	
	    }
				
	}
	
	//删除属性类型
	public function attrTypeDel(){
		M('goods_attribute')->where(array('goods_attr_type_id'=>$_REQUEST['ids']))->delete();//删除属性表   
		M('goods_attr_type')->where(array('id'=>$_REQUEST['ids']))->delete();//删除属性类型表
		$url=U(APP_APPS.'/GoodsAttrType/attrTypeList','','');	
		return_json(200,'删除成功！','','','forward',$url);  
	}

}