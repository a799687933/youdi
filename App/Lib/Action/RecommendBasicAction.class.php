<?php
// 推荐商品公共控制器
class RecommendBasicAction extends BasicAction {
	public function basicIndex($pageLimt){	
	   $where="recommend=1 AND stock > 0 AND shelves=1 AND is_promotion=0 AND recycle_bin=0";
        $keywords=trim(strip_tags($_GET['keywrods2']));
		$min=trim(strip_tags($_GET['min']));
		$max=trim(strip_tags($_GET['max']));
		if($keywords){
		     $where.=" AND {$this->langfx}goods_name LIKE '%{$keywords}%' " ;
		}
		if(($min >=0) && ($max >$min)){
		     $where.=" AND goods_retail_price >='{$min}' AND goods_retail_price <='{$max}' " ;
		}	
	   //排序
	   $getOrder=orderList('order_type','order','create_time','');
	   $this->orders=$getOrder['tpl_show_order'];
	   $orderField=$getOrder['db_order'];		
		$goods=M('goods');
		$counts=$goods->where($where)->count();//p($_GET['_URL_']);
		$this->_URL_=$_GET['_URL_'];//获取GET参数
		$this->doPage($counts,$pageLimt);
		$this->result=$goods->field('id,'.$this->langfx.'goods_name AS goods_name,goods_market_price,goods_retail_price,goods_thumb,click_count,volume')
		->where($where)
		->order($orderField)
		->limit($GLOBALS['limit'])
		->select();
		//热销商品
		$this->hots=$this->getGoodsTb('recycle_bin=0 AND stock > 0 AND shelves=1 AND is_promotion=0 AND hot=1', 'volume DESC', 10);
		
	    //你可能还喜欢
        $this->love=$this->loves(in_id($result, 'id'),10);
		
		//导航下多图切换广告
		$this->recAd=$this->getAd(55,true);
        if(IS_AJAX){
        	$this->display('Piece/Recommend_index');
        }else{
        	$this->display();
        }		
	}
}