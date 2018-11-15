<?php
class CartAction extends CartBasicAction {
    public function _initialize(){
    	//不允许未登录购物
    	if(!return_key()){
		      if(!is_login()) $this->myInfos(false,'submits',isL(L('PleaseLogIn'),'请先登录'),U('Login/index').'?url='.base64_encode(get_url()));
		}
		if(in_array(ACTION_NAME,array('index'))) {
			parent::_initialize();
		  //你可能还喜欢
		  $this->youLike=$this->guessYouLike(0,24,'');
		   //最近浏览
		  $this->recentVisit= $this->recentVisit(0,24,0);	  
		  //愿望单
		  $wishlistList=$this->wishlistList;
		  if($wishlistList){
			   $ids=in_id($wishlistList,'id');
			   $goods=new GoodsBasicAction();
			   $attrs=$goods->getGoodsAttr(M(),$ids);
			    //p($attrs);die;
			   foreach($wishlistList as $k=>$v){
				  $wishlistList[$k]['attr']=$attrs[$v['id']];
			   }
			   $this->wishlistArr=$wishlistList;
			   //p($wishlistList);die;
		  }		  
		}
	}
	//购物车列表
	public function index(){
	   yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了	
	   $result=$this->basicIndex();
	   $this->cart=$result;//p($result);die;
		$this->attrlist=$result['goods_attrs'];
		$this->display();
	}
 
   //添加购物车  	   
   public function addCart(){
	  if(!verify_req_sign($_GET,array('goods_id','num','attr_id','check','cart_id'),86400)){
	     if(IS_AJAX){
			 $this->myInfos(false,'submits',isL(L('OperationTimeout'),'操作超时'),$_SERVER["HTTP_REFERER"]);	
		 }else{
			 header("location:".$_SERVER["HTTP_REFERER"]);
		 }
		 die();
	  }
      $result=$this->basicAddCart();
	  if($result['success']){
			header("location:".U('Cart/index'));
	  }else{
		    $this->myInfos(false,'submits',$result['msg']);
	  }
   }
   
  //修改购物车数量
  public function updateCart(){
	  if(!verify_req_sign($_GET,array('num','cart_id'),0))
	  $this->myInfos(false,'submits',isL(L('OperationTimeout'),'操作超时'),U('Cart/index'));
      $reslut=$this->basicUpdateCart();
	  if($reslut['success']) $this->myInfos(true,'submits',$reslut['msg'],'');
	  else $this->myInfos(false,'submits',$reslut['msg'],'');
  } 
  
  //删除购物车
  public function delCart(){
	  if(!verify_req_sign($_GET,array('ids','cart_id'),0))
	  $this->myInfos(false,'submits',isL(L('OperationTimeout'),'操作超时'),U('Cart/index'));	  
      $reslut=$this->basicDelCart();
	  if($reslut['success']) $this->myInfos(true,'remove-'.$_GET['ids'],$reslut['msg'],U('Cart/index'),'ajaxToHtml');
	  else $this->myInfos(false,'submits',$reslut['msg'],'');	  
  }
  
}