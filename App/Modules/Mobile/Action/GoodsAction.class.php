<?php
class GoodsAction extends GoodsBasicAction {
    
	//商品内容页
	public function index(){
		$this->baiscIndex(20);
		$this->display();
	}

	//手机板商品图文页
	public function goodsContent(){
		 $id=$_GET['goods_id'];
		 $where=array('id'=>$id,'shelves'=>1,'recycle_bin'=>0,'stock'=>array('gt',0));
		 $goods=M('goods')->field($this->langfx."content AS content,{$this->langfx}goods_name AS goods_name")->where($where)->find();
		 $this->title=$goods['goods_name'];
		 $this->goods=$goods;
	     $this->display();
	}
	
	//手机板商品评价列表页
	public function goodsAppraiseList(){
	   $id=getNum($_GET['id']);	
	   $where=array('id'=>$id,'shelves'=>1,'recycle_bin'=>0);	
	   $goods=M('goods')->field("id,{$this->langfx}goods_name AS goods_name,trade_integral,stock")->where($where)->find();	
	   if(!$goods) die('error!');
	   $this->goods=$goods;
	   $result=$this->goodsScore($_GET['goods_id'],$this->DB,10);
	   $this->title='商品评价列表';
	   $this->goodsScore=$result;
	   if(IS_AJAX){
		   $this->display('Piece/Goods_goodsAppraiseList');
	   }else{
	      $this->display();
	   }
	   
	}
	
	//手机板组合购买
	public function combination(){
		$id=getNum($_GET['id']);
		$where="id='{$id}' AND shelves=1 AND recycle_bin=0 ".emptyCity();
		$field="id,{$this->langfx}goods_name AS goods_name,goods_thumb,goods_market_price,goods_retail_price,is_promotion";
		$goods=M('goods')->field($field)->where($where)->find();
		if(!$goods) die('error!');
		//是否是促销商品
		if($goods['is_promotion']){
			$promotion=$this->basicIsGoodsPromotion($goods['id']);
			$this->promotion=$promotion;
		}	
		//获取组合购买	
		$zhuhe=$this->goodsZhuhe($goods['id']);
		if(!$zhuhe) die('error!');
		$this->zhuhe=$zhuhe;	
		$this->goods=$goods;
		$this->display();	
	}
	
	//选择属性动太显示价格
	public function attrGoodsPrice(){
		$this->basicAttrGoodsPrice();	
	}
	
    //收藏处理
	public function collection(){
		$this->basicCollection();	
	}

	//获取物流城市和区县
    public function getContets(){
		$this->BasicgetContets();	
	}
	
	//获取物流费用
	public function getFreight(){
		$this->basicGetFreight($_GET['goods_id'],$_GET['city_name'],$_GET['district']);		
	}
   
   //商品咨询处理
   public function consultant(){
   	   $this->basicConsultant();
   }

}