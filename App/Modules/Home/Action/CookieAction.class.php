<?php
// 控制器
class CookieAction extends BasicAction{
	//收藏商品
	public function goodsCollect(){
	    $id=$_GET['id'] > 0 ? $_GET['id'] : 0;
		if($id<=0) $this->error(isL(L('Failure'),'操作失败'));
		$this->cookieCollect($id,false);
		//删除购物车
		$_REQUEST['ids']=$id;
		$cart=new CartModel();
		$cart->delCart();
		M('goods')->where(array('id'=>$id))->setInc('coll_numeber');
		$this->success(isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"]);
	}

	//删除收藏商品
	public function deleteDoodsCollect(){
	    $id=$_GET['id'] > 0 ? $_GET['id'] : 0;
		if($id<=0) die(json_encode(array('success'=>false,'msg'=>isL(L('Failure'),'操作失败'),'url'=>$_SERVER["HTTP_REFERER"])));
		$this->cookieCollect($id,true);
		M('goods')->where(array('id'=>$id))->setDec('coll_numeber');
		if(!$_GET['is_location']){
           die(json_encode(array('success'=>true,'msg'=>isL(L('Success'),'操作成功'),'url'=>$_SERVER["HTTP_REFERER"])));			
		}else{
		   die(json_encode(array('success'=>true,'msg'=>isL(L('Success'),'操作成功'),'url'=>$_SERVER["HTTP_REFERER"])));
		}
	}
	
	//删除你可以还喜欢
    public function delYouLike(){
    	
    }

    //删除最近浏览
    public function delRecentVisit(){
	    $id=$_GET['id'] > 0 ? $_GET['id'] : 0;
		if($id<=0) die(json_encode(array('success'=>false,'msg'=>isL(L('Failure'),'操作失败'),'url'=>$_SERVER["HTTP_REFERER"])));
		$this->recentVisit($id,0,0,true);
		if(!$_GET['is_location']){
           die(json_encode(array('success'=>true,'msg'=>isL(L('Success'),'操作成功'),'url'=>$_SERVER["HTTP_REFERER"])));			
		}else{
		   die(json_encode(array('success'=>true,'msg'=>isL(L('Success'),'操作成功'),'url'=>$_SERVER["HTTP_REFERER"])));
		}		
    }	

}