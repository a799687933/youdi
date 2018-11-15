<?php
// 本类由系统自动生成，仅供测试用途

class GoodsCategoryAction extends CommonAction {
	//商品分类列表
    public function cateList(){
       $nodeArr=M('goods_category')->order(array('reorder'=>'ASC'))->select();
       import('Class.Category',APP_PATH);
       $this->Cate=Category::unlimitedForLevel($nodeArr);	
       $this->display();
    }

	//添加商品分类
	public function cateAdd(){
		if(!$_GET['id']){
	        $this->pid=I('pid',0,'intval');
	        $id=I('pid',0,'intval');
	         if(is_numeric($id) && $id > 0){
	             $path=M('goods_category')->where(array('id'=>$id))->select();
	             $this->path=$path[0]['path'];
	         }else{
	             $this->path=0;
	         }
		 }else{
		 	$reslut=M('goods_category')->where(array('id'=>$_GET['id']))->find();
			$this->save=$reslut;
			$this->adArray=json_decode ($reslut['ad_array'], true);
			if($reslut['goods_attribute_id_all']){
			    $attrType=M('goods_attribute')->field('id,goods_attr_type_id')->where(array('id'=>array('in',$reslut['goods_attribute_id_all'])))->select();
				$db=M('goods_attribute');
				foreach($attrType as $k=>$v){
					$attrType[$k]['attribute']=$db->field('id,'.pfix('name').' AS name')->where(array('goods_attr_type_id'=>$v['goods_attr_type_id']))->select();
				}
				//p($attrType);
				$this->attrType=$attrType;
			}
		 }
		 $this->attr_type=M('goods_attr_type')->select();
		 $this->names=$this->langIsMore('goods_category','name'); 
		 $this->adTitle=$this->langIsMore('goods_category','ad_title'); 
		 $this->display();
	}

	//添加商品分类表单处理
	public function cateAddForm(){
	    //AJAX跟根属性类型获取属性表列
	    if($_REQUEST['attr']=='where'){
		   $this->getAttrList();
		   die();
		}
		if(!$_POST['id']){
	        $id=getNextId(PREFIX.'goods_category');//获得表的新增时的ID;
	        path_padding($id,'pid','path','padding'); //无限级分类路径和左缩进处理			
		}	
        $_POST['goods_attribute_id_all']=implode(',',$_POST['filter_attr']);
		unset($_POST['filter_attr']);
		$img=$_POST['ad_alone'];
		if(!empty($img) && !empty($_POST['imgAll'])){
			$imgArr=array_merge(array($img), $_POST['imgAll']);
		}else if(!empty($img)){
			$imgArr=array($img);
		}else if(!empty($_POST['imgAll'])){
			$imgArr=$_POST['imgAll'];
		}
        
		if($_POST['ad_alone1']){
			$imgArr=array_merge(array($_POST['ad_alone1']),$imgArr);
		}
		
		if($_POST['imgAll']){
			$arr=array();
			foreach($_POST['imgAll'] as $k=>$v){
				$arr[$k]['img']=$v;
				$arr[$k]['title']=$_POST['title'][$k];
				$arr[$k]['links']=$_POST['links'][$k];
			}
			$_POST['ad_array']=$arr ? json_encode($arr) : ''; 
			unset($_POST['imgAll']);
			unset($_POST['title']);
			unset($_POST['links']);
		}else{
			$_POST['ad_array']='';
		}
		$_POST['goods_attribute_id_all']=$_POST['goods_attribute_id_all'] ? $_POST['goods_attribute_id_all'] : '';
		if(!$_POST['id']){
			$this->requestArray('goods_category');
			D('Curd')->insert('goods_category','cateList',$imgArr);//插入	
		}else{
			D('Curd')->modify('goods_category','cateList','','id',$imgArr,'name');//修改	
		}
        
	}
		    
	//修改商品分类
	public function cateSave(){
		if($_GET['one']=='ok'){//ajax修改一个字段
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
	}

	//删除商品分类
	public function cateDelete(){
		   $url=U(APP_APPS.'/GoodsCategory/cateList','','');	
		  //这里检查商品表是否有该分类的ID，如果有则不执行删除	
	      $cateId=M('goods_category')->field('id')->where(array('path'=>array('LIKE',"%-{$_GET['ids']}-%"),'_logic'=>'OR','id'=>$_GET['ids']))->select();
		  foreach($cateId as $k=>$v){
		  	  $cids.=$v['id'].',';
		  }
          $cids=rtrim($cids,',');
		  if(M('goods')->field('id')->where(array('goods_category_id'=>array('IN',$cids)))->select()) return_json(300,'此分类下有商品，你不能进行删除？');
	      $amp['path']=array('like',"%-".$_GET['ids']."-%");
		  $amp['_logic']='OR';
		  $amp['id']=$_GET['ids'];
	      $category=M('goods_category')->where($amp)->delete(); //删分类表	   
	      D('Curd')->del_img($_GET['ids'],'goods_category');   
	      return_json(200,'删除成功！','','','forward',$url);
	}	
	
	//商品分类检索集合列表
	public function combination(){
		 $M=M('goods_category_combination');
		 $result=$M->order("reorder asc")->select();
		 foreach ($result as $key => $value) {
			 $sql="SELECT cat.".pfix('name')." AS name FROM ".PREFIX."goods_category AS cat JOIN ".PREFIX."goods_category_cc_id AS cc ";
			 $sql.="ON(cat.id=cc.category_id) WHERE cc.combination_id=".$value['id'];
			 $result[$key]['goods_category_name']=$M->query($sql);
		 }
		 import('Class.Category',APP_PATH);
		 $Cate=Category::unlimitedForLevel($result);	
		 $this->Cate=$Cate; 
	     $this->display();
	}
	
	//添加修改商品分类检索集合界面
	public function addSaveCombination(){
		if(!$_GET['id']){
			$this->pid=I('pid',0,'intval');
			$id=I('pid',0,'intval');
			 if(is_numeric($id) && $id > 0){
				 $path=M('goods_category_combination')->where(array('id'=>$id))->select();
				 $this->path=$path[0]['path'];
			 }else{
				 $this->path=0;
			 }		
			 $save['is_category']=$path[0]['is_category'];
			 $save['type_where']=$path[0]['type_where'];
			 $this->save=$save;
		 }else{
			 $save=M('goods_category_combination')->where(array('id'=>$_GET['id']))->find();
			 //中间表数据
			 $center=M('goods_category_cc_id')->field('category_id')->where(array('combination_id'=>$_GET['id']))->select();
			 $save['goods_category_id']=array();
			 foreach($center as $k=>$v) $save['goods_category_id'][$k]=$v['category_id'];
			 $this->save=$save;
		 }
		 //分类
		 $this->cate=M('goods_category')->field('id,'.pfix('name').' AS name')->where(array('is_show'=>1,'pid'=>0))->order(array('reorder'=>'ASC'))->select();		 
	     $this->display();
	}
	
	//添加/修改检索集合表单处理
	public function addSaveCombinationForm(){
		if($_GET['one']=='ok'){//ajax修改一个字段
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}		

		if(!$_POST['id']){
	        $id=getNextId(PREFIX.'goods_category_combination');//获得表的新增时的ID;
	        path_padding($id,'pid','path','padding'); //无限级分类路径和左缩进处理			
		}	
		if(!$_POST['id']){	
				if($id=M('goods_category_combination')->add($_POST)){
					if($_POST['goods_category_id'][0]){
						$cc=array();
						$i=0;
						foreach($_POST['goods_category_id'] as $k=>$v){
							if($v){
								$cc[$i]['category_id']=$v;
								$cc[$i]['combination_id']=$id;
								$i++;
							}					
						}
	                    M('goods_category_cc_id')->addAll($cc);
					}	
					return_json(200,'操作成功！','combination','','closeCurrent');			
				}else{
					return_json(300,'操作失败！');		
				}			
		}else{
				M('goods_category_combination')->where(array('id'=>$_POST['id']))->save($_POST);
				if($_POST['goods_category_id'][0]){
					$cc=array();
					$i=0;
					foreach($_POST['goods_category_id'] as $k=>$v){
						if($v){
							$cc[$i]['category_id']=$v;
							$cc[$i]['combination_id']=$_POST['id'];
							$i++;
						}					
					}
					M('goods_category_cc_id')->where(array('combination_id'=>$_POST['id']))->delete();
                    M('goods_category_cc_id')->addAll($cc);
				}	
				return_json(200,'操作成功！','combination','','closeCurrent');								
		}		
	}
	
	//删除检索集合
	public function deleteCombination(){
	   if(M('goods_category_combination')->where("id IN({$_REQUEST['ids']}) AND pid > 0")->delete()){
		   $url=U(APP_APPS.'/GoodsCategory/combination');
		   return_json(200,'操作成功！','','','forward',$url);
	   }else{
		   return_json(300,'操作失败');
	   }
	}
	
	//跟根属性类型获取属性表列
	private function getAttrList(){
		$result=M('goods_attribute')->field('id,'.pfix('name').' AS name')->where(array('goods_attr_type_id'=>$_GET['goods_attr_type_id']))->select();
		if($result){
			$str.='<option value="">在这里筛选属性</option>';
			foreach($result as $k=>$v){
				$str.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
			}
		}else{
			$str.='<option value="">暂无数据请选择类型</option>';
		}
		die($str);
	}
	
}