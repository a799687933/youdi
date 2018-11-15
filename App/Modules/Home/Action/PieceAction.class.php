<?php
// 公共碎片控制，此控制器不可再继承其它父类
class PieceAction extends Action {
   
	 //系统所有公共最顶部
	 public function publicTop(){
		//购物车个数
		$cartCount=0;
		if((string)$_COOKIE[C('CART_COUNT')]==''){
			if(is_login()){	
				$cart=new CartModel();
				$cartCount=$cart->getCartCount();
			}
		}else{	
			$cartCount=$_COOKIE[C('CART_COUNT')] > 0 ? $_COOKIE[C('CART_COUNT')] : 0;
		}		
		$this->cartCount=$cartCount;
		//热门关键字
		$this->hotKeywordsc=explode('|',C(pfix('HOTKEYWORD')));
	    $this->display();
	 }
	 
	 //收藏列表碎片
	 public function collection(){
		  /*$cookie=cookie('cookie_collect');
		  if(empty($cookie)){
			  //收藏列表
			  $this->pbulcCollect=array();
			  //收藏个数
			  $this->publicCount=0;		  
			  $this->display();	
			  die;
		  }
		  $pfix=pfix();
		  $field="id,{$pfix}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,";
		  $field.="goods_thumb,goods_thumb2,stock,volume";
		  $where['shelves']=1;
		  $where['recycle_bin']=0;
		  $where['id']=array('in',$cookie);
		  $goods=M('goods');
		  $result = $goods->field($field)->where($where)->select();	

		  //收藏列表
		  $this->pbulcCollect=$result;
		  //收藏个数
		  $this->publicCount=$result ? count($result) : 0;*/
	      $this->display();	
	}
	
	//猜你喜欢
	public function likes(){
		   $this->display();	
	}
	
	//最近浏览
	public function visits(){
		  //sleep(1);
		  $cookie=cookie('recent_visit');
		  if(empty($cookie)) return;
		  $pfix=pfix();
		  $field="id,{$pfix}goods_name AS goods_name,goods_num,goods_market_price,goods_retail_price,pay_points,rank_points,integral_amount,";
		  $field.="goods_thumb,goods_thumb2,stock,volume";
		  $where['shelves']=1;
		  $where['recycle_bin']=0;
		  $where['id']=array('in',$cookie);
		  $goods=M('goods');
		  $result = $goods->field($field)->where($where)->order()->limit()->select();	
		  $this->recentVisit=$result;
		  $this->display();	
	}
	
	//购物车
	public function headerCart(){
        $this->display();
	}
	 
}