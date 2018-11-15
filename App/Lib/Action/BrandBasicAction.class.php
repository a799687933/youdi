<?php
// 推荐品牌公共控制器
class BrandBasicAction extends BasicAction {
    //品牌列表
    protected function basicIndex($limtSize){
		/*$c_id=str_replace('c-','',$_GET['html']);
		$cg=M('goods_category');
        $this->cates=$cg->field('id,'.$this->langfx.'name AS name')->where("pid =0 AND is_show=1")->order("reorder ASC")->select();
		if($c_id > 0){
			$carrayId=$cg->field('id')->where("path LIKE '%-{$c_id}-%' OR id='{$id}' AND is_show=1")->select();
			$inId=in_id($carrayId,'id');
			$where['goods_category_id']=array('in',$inId);
		}
		$where['display']=1;
		$this->_URL_=$_GET['_URL_'];
		$counts=M('goods_brand')->where($where)->count();
		$this->doPage($counts,$limtSize);*/
		$brandList=M('goods_brand')->field('id,'.$this->langfx.'name AS name,logo,'.$this->langfx.'bewrite AS bewrite')->where($where)->order("orders ASC")->select();	
		$arr=array();
		$i=0;
		$j=0;
		foreach($brandList as $k=>$v){
			$arr[$i]['lists'][$k]['id']=$v['id'];
			$arr[$i]['lists'][$k]['name']=$v['name'];
			$arr[$i]['lists'][$k]['logo']=$v['logo'];
			$arr[$i]['lists'][$k]['bewrite']=$v['bewrite'];
			if($j >=2){
				$i++;
				$j=0;
			}else{
				$j++;
			}
		}
		$this->brandList=$arr;	
	    //导航下单张广告图片
	    //$this->brandAd=$this->getAd(73,true);	
		if(IS_AJAX){
		    $this->display('Piece/Brand_index');			
		}else{
			$this->display();		
		}		
	}
	
	//品牌商品列表(静态页面，用户自定义)
	protected function brandCover($limtSize){
		$brandCover='';
		$id=getNum($_GET['html']);
        if(is_mobile()){
        	$brandCover='id,'.$this->langfx.'name AS name,logo,'.$this->langfx.'bewrite AS bewrite,'.$this->langfx.'m_brand_cover as brand_cover';
        }else{
        	$brandCover='id,'.$this->langfx.'name AS name,logo,'.$this->langfx.'bewrite AS bewrite,'.$this->langfx.'brand_cover as brand_cover';
        }
		$result=M('goods_brand')->field($brandCover)->where(array('id'=>$id,'display'=>1))->find();
		$this->title=$result['name'];
		$this->result=$result;
	}
	
}