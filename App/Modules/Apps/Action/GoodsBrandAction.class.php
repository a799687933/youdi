<?php
class GoodsBrandAction extends CommonAction{
		
		//商品品牌列表
		public function brandList(){
			$curd=D('Curd');		
			$M=M('goods_brand');	
			$where=$curd->search(pfix('name')); //询查条件返回数组
			$order=$this->orderBy();//临时排序，返回数组
	    	$order=$order ? $order : array('orders'=>'ASC');			
			$counts=$M->where($where['searchArr'])->count();
			$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
			$brand=$M->where($where['searchArr'])->limit($GLOBALS['limit'])->order($order)->select();
			foreach($brand as $k=>$v){
				 $sql="SELECT cat.".pfix('name')." AS name FROM ".PREFIX."goods_category AS cat JOIN ".PREFIX."goods_category_brand_bc_id AS cbb ";
				 $sql.="ON(cat.id=cbb.category_id) WHERE cbb.brand_id=".$v['id'];
				 $brand[$k]['goods_category_name']=$M->query($sql);				
			}
			$this->brand=$brand;
			$this->url=$where['url']; //查询条件分页URL
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
			$this->display();
		}
		
		//添加/修改商品品牌
		public function brandAddSave(){
			//商品分类
			$this->cate=M('goods_category')->field('id,'.pfix('name').' AS name,pid,padding')->where("is_show=1 AND pid = 0")->order("reorder ASC")->select();		
			if($_GET['id']){
                $save=M('goods_brand')->where(array('id'=>$_GET['id']))->find();
				$center=M('goods_category_brand_bc_id')->field('category_id')->where(array('brand_id'=>$_GET['id']))->select();
				foreach($center as $k=>$v) $save['categoryIds'][$k]=$v['category_id'];
				$this->save=$save;
			}	
		   $this->names=$this->langIsMore('goods_brand','name');
		   $this->bewrites=$this->langIsMore('goods_brand','bewrite');	
		   $this->brandCover=$this->langIsMore('goods_brand','brand_cover');	
		   $this->mBrandCover=$this->langIsMore('goods_brand','m_brand_cover');	
		   $this->display();
		}
		
		//品牌操作表单处理
		public function brandForm(){
			
		 	if($_GET['one']=='ok'){
		 		$this->saveOen();
		 	}else if($_GET['yes_no']=='ok'){
		 		$this->saveSwitch();
		 	}else if($_POST['id']){  //修改
		 	    if($_POST['goods_category_id'][0]){
		 	    	$i=0;
					$insertId=array();
					foreach($_POST['goods_category_id'] as $k=>$v){
						if($v){
							$insertId[$i]['category_id']=$v;
							$insertId[$i]['brand_id']=$_POST['id'];
							$i++;
						}
					}
                    M('goods_category_brand_bc_id')->where(array('brand_id'=>$_POST['id']))->delete();
					M('goods_category_brand_bc_id')->addAll($insertId);
		 	    }else{
		 	    	return_json(300,'请选择商品分类');
		 	    }
				D('Curd')->modify('goods_brand','brandList','','id',array($_POST['logo'],$_POST['ad_img'])); //修改数据	 		
		 	}else{ //新增
		 	    if($_POST['goods_category_id'][0]){
		 	    	$i=0;
					$insertId=array();
					$isId=getNextId(PREFIX.'goods_brand');
					foreach($_POST['goods_category_id'] as $k=>$v){
						if($v){
							$insertId[$i]['category_id']=$v;
							$insertId[$i]['brand_id']=$isId;
							$i++;
						}
					}
					M('goods_category_brand_bc_id')->addAll($insertId);
		 	    }else{
		 	    	return_json(300,'请选择商品分类');
		 	    }		 	
			    $this->requestArray('goods_brand');
		 		$_POST['create_time']=time();
		 		D('Curd')->insert('goods_brand','brandList',array($_POST['logo'],$_POST['ad_img'])); //修改数据	 
		 	}			
		}
		
		//删除商品品牌
		public function brandDel(){
             $url=U(APP_APPS.'/GoodsBrand/brandList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
			 D('Curd')->del('goods_brand','brandList',$url,'ids','id');
		}
}
?>