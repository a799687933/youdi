<?php
class GoodsAction extends GoodsBasicAction {
    
	//商品内容页
	public function index(){
		//yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		//商品评价
		$goodsApp=$this->goodsScore($_GET['html'] > 0 ? $_GET['html'] : 0,M(),10);
		
		if(IS_AJAX){
		   $result['goods_app']=$goodsApp;	
		   $this->goods=$result;
		   $this->display('index_ajax');
		   die();
		}
		$result=$this->baiscIndex(20);
		$result['goods_app']=$goodsApp;	
		//p($result['attrs']);die;
		//计算总评星
		$dbscore = $result['review_score'];
		//是否有小数点
		if(strpos($dbscore,'.') !==false) {
			//$this->showScore=sprintf('%.1f', (float)$dbscore);
			$ca=explode('.',$dbscore);
			$this->showScore=$ca[0];
			$this->showScoreFloat='.5';
			$this->score=$ca[0] + 1;
			$this->points=$ca[0] + 2;
		}else{
			$this->showScore=$dbscore;
			$this->score=$dbscore;
			$this->points=$dbscore + 5;			
		}			
		
		//所在位置
		$this->position=$result['poistion'];

		$this->goods=$result;
		 //热销排行
		 if(C('IS_GOODS_HOT')) 
		 $this->hot1=
		 $this->getGoodsTb(array('recycle_bin'=>0,'shelves'=>1,'hot'=>1,'is_promotion'=>0),"volume DESC ",10);	
		  		
		//关联商品(你可能还喜欢)
		//if(C('IS_GOODS_LINK')) $this->linkGoods=$this->loves($result['id'],10);		
		
	    //你可能还喜欢
	    $this->youLike=$this->guessYouLike(0,24,'');
	    //最近浏览
	    $this->recentVisit= $this->recentVisit(0,24,0);	
		//原望单商品ID集合，本商品是否在原望单列表中
		$this->cookie_collect=cookie('cookie_collect') ? explode(',',cookie('cookie_collect')) : array();
		$this->display();
	}
	
	//组合购买祥情页
	public function combination(){
	     $data=$this->basicCombination();
		 if(empty($data['success'])) $this->error($data['msg']);
		 $goods=$data['data'];
		 $this->goods=$goods;
		 //p($goods);die;
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