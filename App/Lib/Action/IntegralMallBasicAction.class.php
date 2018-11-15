<?php
// 积分商城公共控制器
class IntegralMallBasicAction extends BasicAction {
    
	//积分商城列表页
    protected function basicIndex(){
		//导航下广告图
	    $this->allNav=$this->getAd(79,false);	
		//导航下广告图
	    $this->leftNav=$this->getAd(85,false);
				
		$basic="1=1 AND shelves=1 AND recycle_bin=0 AND stock > 0 AND trade_integral > 0 ".emptyCity();
		$lw=$basic;
		//价格
		$min=getNum($_GET['min']);
		$max=getNum($_GET['max']);
		if($min > 0 && $max > 0) $lw.=" AND goods_retail_price >='{$min}' AND goods_retail_price <='{$max}' ";
		//关键字
		$keywords=filterRequst($_GET['keywords']);
		if($keywords) $lw.=" AND ({$this->langfx}goods_name LIKE '%{$keywords}%' OR {$this->langfx}commodity_key LIKE '%{$keywords}%') ";

		$int=M('goods');
		$counts=$int->where($lw)->count();
		$this->_URL_=$_GET['_URL_'];
		$this->doPage($counts,20);
		$field="id,{$this->langfx}goods_name AS goods_name,goods_num,goods_thumb,goods_market_price,goods_retail_price,volume,trade_integral";
	    //排序
	    $getOrder=orderList('order_type','order','volume','');
	    $this->orders=$getOrder['tpl_show_order'];
	    $orderField=$getOrder['db_order'];			
		$result=$int->field($field)->where($lw)->order($orderField)->limit($GLOBALS['limit'])->select();
		//对换排行榜
		$pw=$basic." AND volume > 0";
		$this->parade=$int->field($field)->where($pw)->order(array('volume'=>'DESC'))->limit(12)->select();
		//热卖商品
		$this->hot=$this->getGoodsTb(0,'AND hot=1','volume DESC',6);
		//已登录的用户显示用户的积分
		if(is_login()){
			$this->payPoints=M('member')->where("id='".getUserC('SE_USER_ID')."'")->getField('pay_points');
		}
		//手顶模块
		if(is_m()){
			$mobileData=array();
			$i=0;
			$j=0;
			foreach($result as $k=>$v){
				$i++;
				$mobileData[$j][$k]=$v;
				if($i ==3) {$j++;$i=0;}
			}
			unset($result);
			$this->result=$mobileData;
			$this->mobileTitle='积分商城';	
			if(IS_AJAX){$this->display();}else{$this->display();}
		}else{
			$this->result=$result;
			$this->display();			
		}
    }
	
}