<?php
class CartBasicAction extends BasicAction {

	//购物车列表
	protected function basicIndex(){
		//购物车列表
		$model=new CartModel();
		$result=$model->cartList();
		return $result;
	}
 
   //添加购物车
   /***
   *按照折扣优惠思想：
   * 商品限时促销价格的优先级第一；   
   * 商品数量优惠价格的优先级第二；
   * 商品会员等级价格优先级三；
   * */   	   
   protected function basicAddCart(){
	 $cartModel=new CartModel();
     return $cartModel->cartAction();
   }
   
  //修改购物车数量
  public function basicUpdateCart(){
	  $model=new CartModel();
	  $result=$model->updateCart();
	  return $result;
  } 
  
  //删除购物车
  public function basicDelCart(){
  	 $model=new CartModel();
	 return $model->delCart();
  }
  
}