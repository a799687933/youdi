<?php
class CartAction extends CartBasicAction {

	//购物车列表
	public function index(){
		$list=$this->basicCartList();
		$this->display();
	}

   //添加购物
   public function addCart(){
		$list=$this->basicAddCart();        
   }
   
  //修改购物车数量
  public function updateCart(){
		$this->basicUpdateCart();   
  } 
  
  //删除购物车
  public function delCart(){
		$this->basicDelCart();   
  }
  
}