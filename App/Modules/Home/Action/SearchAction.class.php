<?php

class SearchAction extends CommonAction {

    public function index(){
        $keywrods=strip_tags($_GET['keywords']);
		$this->keywrods=$keywrods;
        $db=M('goods');  //商品表对像

        if($_GET['o']=='0'){ //默认排序
		   $order=" ORDER BY g.id DESC ";
		   $this->oClass="mor";
		}else{
		   $this->oClass="mor1"; 
		}
		
	   if($_GET['v']=='desc'){ //销售量排序
		   $order=" ORDER BY g.volume DESC ";
		   $this->vClass="ons";
		   $this->Vorders='asc';
		} else{
		   if($_GET['v']=='asc') $order=" ORDER BY g.volume ASC ";
		   $this->vClass="";
		   $this->Vorders='desc';		
		}  

	   if($_GET['h']=='desc'){ //点击量排序
		   $order=" ORDER BY g.click_count DESC ";
		   $this->gClass="ons";
		   $this->Gorders='asc';
		} else{
		   if($_GET['h']=='asc') $order=" ORDER BY g.click_count ASC ";
		   $this->gClass="";
		   $this->Gorders='desc';		
		}   

	   if($_GET['r']=='desc'){ //价格排序
		   $order=" ORDER BY g.goods_retail_price DESC ";
		   $this->rClass="ons";
		   $this->Rorders='asc';
		} else{
		   if($_GET['r']=='asc') $order=" ORDER BY g.goods_retail_price ASC ";
		   $this->rClass="";
		   $this->Rorders='desc';		
		}   
		echo $order;
		//商品列表
		$this->getGoodsList($db,$order,$keywrods);
		//热卖排行/热门点击
		$this->hot=$this->getGoodsTb($db,array('click_count'=>'DESC'),10);		
		 //左边分类下广告
	    $this->leftAd=$this->getAd(12,'',false);		
		
        $this->display();
    }


  
    //商品列表
    private function getGoodsList($db,$order,$keywrods){

		//基础查询条件
		$where=" g.shelves = 1 AND g.recycle_bin = 0  ";

		if($keywrods){
             $where.=" AND (g.goods_name LIKE '%{$keywrods}%' OR g.commodity_key LIKE '%{$keywrods}%') ";
		}

		$field='g.id,g.goods_name,g.goods_market_price,g.goods_retail_price,g.pay_points,g.rank_points,g.integral_amount,g.goods_thumb,';
		$field.="s.id AS s_id,s.shop_name,catalog,s.shops_type";
		$gsql="SELECT count(g.id) AS counts FROM ".PREFIX."goods AS g LEFT JOIN ".PREFIX."shops AS s ON(s.id=g.shops_id) ";
		$gsql.="WHERE ".$where;
		$counts=$db->query($gsql);
		$this->doPage($counts[0]['counts'],20,'');
		$listSql="SELECT {$field} FROM ".PREFIX."goods AS g LEFT JOIN ".PREFIX."shops AS s ON (s.id=g.shops_id) WHERE ".$where.$order." LIMIT ".$GLOBALS['limit'];
    	$result=$db->query($listSql);
		foreach($result as $k=>$v){
		    $result[$k]['appraise']=$this->shopsHaoP($v['id'],'goods_id');
		}
		$this->result=$result;
    }
	
	//热门点击和最新上市
	private function getGoodsTb($db,$order,$limit){       
		return $db->field('id,goods_name,goods_market_price,goods_retail_price,goods_thumb,click_count')
		->where(array('recycle_bin'=>0,'shelves'=>1))
		->order($order)
		->limit($limit)
		->select();		
	}   

}