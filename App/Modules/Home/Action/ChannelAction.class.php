<?php
// 商品列表封面
class ChannelAction extends CommonAction {
	

    public function index(){

		 
		 //分类是否存在
		 $db=M('goods_category');
		 $cate=$db->field('id,name')->where(array('id'=>$_GET['id']))->find();
		 if(!$cate) header('location:'.U('/Index/index'));
		 
		 //你的位置
		 $this->position=$cate;
		 
		 //左边二级分类
		 $passeg=$db->field('id,name')->where(array('pid'=>$cate['id']))->order(array('reorder'=>'ASC'))->select();	 
		 $this->twoCate=$passeg;
		 
		 //执门点击
		 $goodsDb=M('goods');
		 $this->hot=$goodsDb
		 ->field('id,goods_name,goods_retail_price,goods_thumb,click_count')
		 ->where(array('shelves'=>1,'is_general'=>1,'recycle_bin'=>0,'stock'=>array('gt',0)))
		 ->order(array('click_count'=>'DESC'))
		 ->limit(6)
		 ->select();
		 
		 //切换图片
         $this->cAd=M('ad')->field('ad_img,ad_url,ad_class_bewrite')->where(array('position'=>'channel','display'=>1,'ad_pid'=>array('gt',0)))->order(array('ad_order'=>'ASC'))->select();		
		 
		 //分类品牌
		 $sql="SELECT b.id,b.goods_category_id,b.name_cn,b.logo FROM ".PREFIX."goods_brand AS b,".PREFIX."goods_category AS g ";
		 $sql.="WHERE b.goods_category_id=g.id AND g.path LIKE '%-".$_GET['id']."-%' LIMIT 0,6"; 
		 $this->brand=M()->query($sql);
		 
		 //获限三分类
		 foreach($passeg as $k=>$v){
		 	  $three=$db->field('id,name')->where(array('pid'=>$v['id']))->order(array('reorder'=>'ASC'))->limit(12)->select();
			  foreach($three as  $kk=>$vv){
			  	  $inId.=$vv['id'].',';
			  }
			  $inId=$inId.$v['id'];
			 $passeg[$k]['child']=$three;
			 $passeg[$k]['goods']=$goodsDb
			 ->field('id,goods_name,goods_retail_price,goods_thumb')
			 ->where(array('shelves'=>1,'is_general'=>1,'recycle_bin'=>0,'stock'=>array('gt',0),'goods_category_id'=>array('IN',$inId)))
			 ->order(array('goods_order'=>'ASC'))->limit(12)
			 ->select();

			 $inId='';
		 }
		$this->twoF=$passeg;
		
	    $this->display();
    }

}