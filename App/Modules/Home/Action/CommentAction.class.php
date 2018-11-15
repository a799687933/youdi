<?php
/*全部商品论列表*/
class CommentAction extends BasicAction {
    
	//列表
	public function index(){
		$M=M();
		$where=" AND ga.times >='".(time()-86400 * 60)."' ";
		$sql="SELECT sum(ga.score) AS score,count(ga.id) AS counts FROM ".PREFIX."goods_appraise AS ga  WHERE ga.is_show=1".$where;
		$counts=$M->query($sql);
		
		$dbscore= $counts[0]['score'] / $counts[0]['counts'];
		 
		//是否有小数点
		if(strpos($dbscore,'.') !==false) {
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
      
		//评论数
		$this->counts=$counts[0]['counts'];
        $view=$_GET['view'] > 0 ? $_GET['view'] : 10;
		$this->doPage($counts[0]['counts'],$view);
		$sql="SELECT ga.*,m.nickname,m.user_name,m.surname,m.full_name,og.goods_attr FROM ".PREFIX."goods_appraise AS ga JOIN ".PREFIX."member AS m ";
		$sql.="ON (ga.member_id=m.id) LEFT JOIN ".PREFIX."order_goods AS og ON(og.order_id=ga.order_id AND og.goods_id=ga.goods_id) ";
		$sql.="WHERE ga.is_show=1 ".$where." ORDER BY ga.times DESC LIMIT ".$GLOBALS['limit'];
		$result=$M->query($sql);
		foreach($result as $k=>$v){
			$result[$k]['slide_show']=$v['slide_show'] ? json_decode($v['slide_show'],true) : array();
			$result[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
			$result[$k]['user_name']=$v['nickname'] ? $v['nickname'] : $v['user_name'];
		}
		$this->result=$result;
		$this->filesName=pfix('zyjhr');
		if(IS_AJAX) $this->display('index_ajax');
		else $this->display();
	}
 
}