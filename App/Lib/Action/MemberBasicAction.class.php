<?php
// 会员中心公共控制器
class MemberBasicAction extends BasicAction {
	public $basicModel;
	public $userId;
	public $level;
	public function _initialize(){
		parent::_initialize();
		 if(!is_login()) {
		      header('location:'.U(is_m().'/Login/index','','').'?url='.base64_encode(get_url()));
			  die();
		 }
		$this->orderStart=isL(L('ORDER_START'),C('ORDER_START')); //订单状态数组
		$this->shippingStart=isL(L('ORDER_SHIPPING'),C('ORDER_SHIPPING')); //配送状态
		$this->payStart=isL(L('ORDER_PAY'),C('ORDER_PAY'));//支付状态	
		$this->payType=isL(L('PAYMENT_METHOD'),C('PAYMENT_METHOD'));//支付方式
		$this->level=getUserC('SE_USER_GRADR_ID');//用户等级ID
		$this->userId=getUserC('SE_USER_ID'); //用户ID
		$this->actionName = ACTION_NAME;
		$this->basicModel=new BasicMemberModel();
	} 
	
	//会员首页
	protected function basicIndex(){
	   //用户信息
	   $userData=M('member')->where(array('id'=>$this->userId))->find();
	   if($userData['birthday']){
	      $userData['birthday']=explode('-',$userData['birthday']);
		  $this->ymd=$userData['birthday'][0].'-'.$userData['birthday'][1].'-'.$userData['birthday'][2];
	   }else{
		   $userData['birthday']=array();
		   $this->ymd='';
	   }
	   $this->userData=$userData;
	   
	   //国家列表
	   $this->countryList=M('region')->where(array('parent_id'=>0,'is_show'=>1))->order("orders ASC")->select();
	   //地址列表
	   $addList=M('member_address')->where(array('user_id'=>$this->userId))->order("default_address DESC")->select();
	   $this->addList=$addList;
      //订单列表
      $fo='order_id,order_sn,order_status,pay_status,shipping_status,goods_number,goods_amount,shipping_time,order_amount,add_time';
      $this->orderList=M('order_info')->field($fo)->where(array('user_id'=>$this->userId))->order(array('add_time'=>'DESC'))->select();
	  $sqlExecute=M();
	  //我的收藏
	  $sql="SELECT c.url,a.id,a.cn_titletext,a.count_click,a.like_count,a.comment,a.thumb FROM ".PREFIX."article_collection AS c JOIN ";
	  $sql.=PREFIX."article AS a ON (c.article_id = a.id) WHERE c.user_id='{$this->userId}' ORDER BY c.add_time DESC";
	  $collection=$sqlExecute->query($sql);
	  $this->assign('collection',$collection);
	  //我评论过的大资讯
	  $sql="SELECT c.content,c.like_count AS c_like_count,a.id,a.cn_titletext,a.count_click,a.like_count,a.comment,a.thumb FROM ".PREFIX."comment AS c JOIN ";
	  $sql.=PREFIX."article AS a ON (c.article_id = a.id) WHERE c.user_id='{$this->userId}' ORDER BY c.times DESC";
	  $comment=$sqlExecute->query($sql);
      $this->assign('comment',$comment);
	  //我的评论过的大资讯赞的总数
	  $this->assign('like_count',M('comment')->where("user_id='{$this->userId}'")->sum('like_count'));
	  //大资讯/小资讯管理员回复消息未读
	  $sql="SELECT c.article_id,c.comment_id,c.times,a.id,a.cn_titletext,a.count_click,a.like_count,a.comment,a.thumb FROM ".PREFIX."comment_reply AS c JOIN ";
	  $sql.=PREFIX."article AS a ON(c.article_id=a.id) WHERE c.read_user_id='{$this->userId}' AND is_read=0 ORDER BY c.comment_id ASC,c.article_id ASC,c.times DESC";
      $allAarr= $sqlExecute->query($sql);
      $tempBig=array();
	  $tempmin=array();
	  $comment_id=$allAarr[0]['comment_id'];
	  foreach($allAarr as $k=>$v){
		  if($v['comment_id'] > 0){//大资讯
			  if($comment_id == $v['comment_id']){
			     $tempBig[$k]=$v;
			  }else{
			      $comment_id = $v['comment_id'];
				  $tempBig[$k]=$v;
			  }		  
		  }else{ //小资讯
			  if($comment_id == $v['comment_id']){
			     $tempmin[$k]=$v;
			  }else{
			      $comment_id = $v['comment_id'];
				  $tempmin[$k]=$v;
			  }			  
		  }
	  }
	  //大资讯回复消息未读
	  $bigMessge=array();	  
	  $article_id=$tempBig[0]['article_id'];
	  $i=0;
	  $j=1;
	  foreach($tempBig as $v){
		  if($v['article_id'] == $article_id){
			  $bigMessge[$i]=$v;
			  $bigMessge[$i]['not_read']=$j++;		  
		  }else{
			   $article_id=$v['article_id'];
			   $i++;$j=1;
			   $bigMessge[$i]['not_read']=$j++;
		  }
	  }
	  $this->assign('bigMessge',$bigMessge);
	  //小资讯回复消息未读
	  $minMessge=array();	  
	  $article_id=$tempmin[0]['article_id'];
	  $i=0;
	  $j=1;
	  foreach($tempmin as $v){
		  if($v['article_id'] == $article_id){
			  $minMessge[$i]=$v;
			  $minMessge[$i]['not_read']=$j++;		  
		  }else{
			   $article_id=$v['article_id'];
			   $i++;$j=1;
			   $minMessge[$i]=$v;
			   $minMessge[$i]['not_read']=$j++;
		  }
	  }
	  $this->assign('minMessge',$minMessge);
	  //商品评论回复未读
	  $sql="SELECT p.goods_id,p.times,p.user_id,g.id,g.cn_goods_name,g.goods_thumb,g.goods_thumb2 FROM ".PREFIX."goods_appraise_reply AS p JOIN ";
	  $sql.=PREFIX."goods AS g ON (p.goods_id=g.id) WHERE read_user_id='{$this->userId}' AND is_read=0 ORDER BY p.goods_id ASC,p.times DESC";
	  $tempArr=$sqlExecute->query($sql);
	  $appraiseReply=array();
	  $goodsId=$tempArr[0]['goods_id'];
	  
	  $i=0;
	  $j=1;
	  foreach($tempArr as $v){
		  if($v['goods_id'] == $goodsId){
			  $appraiseReply[$i]=$v;
			  $appraiseReply[$i]['not_read']=$j++;		  
		  }else{
			   $goodsId=$v['goods_id'];
			   $i++;$j=1;
			   $appraiseReply[$i]=$v;
			   $appraiseReply[$i]['not_read']=$j++;
		  }
	  }	
	  $this->assign('appraiseReply',$appraiseReply);
	  //p($appraise_reply);die;
	}
	

   /**
    * 站内信息列表
    * $pageLimt 分页控制
    * $indexShow 是否首页使用
    * */
   protected function basicInfoList($pageLimt,$indexShow=0){
		 //站内信息
		 $infoWhere['receive_user_id']=$this->userId;
		 $inst=M('instation_info');
		 if($indexShow){
		 		$dbLimit=$indexShow;
		 }else{
			 	$counts=$inst->where($infoWhere)->count();
		     	$this->doPage($counts['counts'],$pageLimt);	
				$dbLimit=$GLOBALS['limit'];	 	
		 }
		 $instationInfo=$inst->field('id,info_type,send_user_id,'.$this->langfx.'title AS title,'.$this->langfx.'content AS content,add_time')
		 ->where($infoWhere)
		 ->order(array('id'=>'DESC'))
		 ->limit($dbLimit)
		 ->select();
		 $members=M('member');
		 foreach($instationInfo as $k=>$v){
		     if($instationInfo['send_user_id'] > 0){
				   $ctaace=$members->field('user_name,nickname,head_pic')->where(array('id'=>$v['send_user_id']))->find();
				   $tempName=$ctaace['user_name'];				 
			 }
			 $instationInfo[$k]['add_name']=$tempName;
			 $tempName='';
		 }
		 return $instationInfo;	   
   }	
	
   //删除站内信息
   public function basicDeleteInfo(){
       $where['receive_user_id']=$this->userId;
	   $where['id']=$_REQUEST['id'];
	   if(M('instation_info')->where($where)->delete()){
		   val_json("submits",true,isL(L('DeleteSuccess'),'删除成功'),$_SERVER["HTTP_REFERER"],'','');
	   }else{
		   val_json("submits",false,isL(L('DeleteFailure'),'删除成功'),'','','');
	   }
   }	
	
	//订单列表
	protected function basicOrderList($pageLimit){
		 $where="1=1 AND user_id='{$this->userId}' ";
	     if($_GET['order_type']==1){ //退款退货
			$where.="AND order_status>=3 ";
		 }
	     $M=M('order_info');
		 if(!$_GET['order_type']){
			 //待付款，已取消的不计算
			 $pay_0="(SELECT count(p0.order_id) AS pay_0 FROM ".PREFIX."order_info AS p0 ";
			 $pay_0.="WHERE p0.pay_status < 2 AND p0.user_id='{$this->userId}' AND (p0.order_status <>0)) AS pay_0";
			 //待发货
			 $pay_2="(SELECT count(p2.order_id) AS pay_2 FROM ".PREFIX."order_info AS p2 ";
			 $pay_2.="WHERE p2.pay_status=2 AND p2.user_id='{$this->userId}' AND p2.order_status =1 AND p2.shipping_status < 2) AS pay_2";
			 //待确认
			 $shipping_status_2="(SELECT count(p3.order_id) AS shipping_status_2 FROM ".PREFIX."order_info AS p3 ";
			 $shipping_status_2.="WHERE p3.shipping_status>=2 AND p3.user_id='{$this->userId}' AND p3.order_status =1) AS shipping_status_2";
			 //交易完成
			 $order_status_2="(SELECT count(p4.order_id) AS order_status_2 FROM ".PREFIX."order_info AS p4 ";
			 $order_status_2.="WHERE p4.order_status=2 AND p4.user_id='{$this->userId}') AS order_status_2";
			 //已取消
			 $order_status_5="(SELECT count(p5.order_id) AS order_status_5 FROM ".PREFIX."order_info AS p5 ";
			 $order_status_5.="WHERE p5.order_status=0 AND p5.user_id='{$this->userId}') AS order_status_5";
			 
			 //待评价订单(交易完成)
			 $evaluated=$this->evaluatedDate($M);	 
			 $this->evaluated=$evaluated['evaluated_count'];//待评价个	
			 			 
			 $all=$pay_0.','.$pay_2.','.$shipping_status_2.','.$order_status_2.','.$order_status_5;
			 $sql="SELECT count(o.order_id) AS counts,{$all} FROM ".PREFIX."order_info AS o WHERE o.user_id='{$this->userId}'";
			 $sums=$M->query($sql);
			 $this->sums=$sums[0];
		 }

		 if($_GET['status'] !='') {
		 	$getStatus=intval($_GET['status']);
			 if($getStatus==1){ //待付款
				 $where.=" AND order_status=1 AND pay_status < 2 AND shipping_status < 2";
				 $this->boderTitle='待付款订单';
			 }else if($getStatus==2){ //待发货
			     $where.=" AND (order_status =1) AND pay_status >= 2  AND shipping_status < 2";
				 $this->boderTitle='待发货订单';
			 }else if($getStatus==3){ //待确认
			     $where.=" AND (order_status  =1) AND pay_status >= 2 AND shipping_status IN(2,3) ";
				 $this->boderTitle='待确认订单';
			 }else if($getStatus==4){ //交易完成
			     $where.=" AND order_status = 2";
				 $this->boderTitle='交易完成订单'; 
			 }else if($getStatus==5){ //已取消
			     $where.=" AND order_status = 0";
				 $this->boderTitle='已取消单订单'; 
			 }else if($getStatus==6){ //待评价订单
			     $where.=" AND order_id IN({$evaluated['ids']}) ";
				 $this->boderTitle='待评价订单'; 
			 }
		 }else{
			 $this->boderTitle='订列单表'; 
		 }
		 $this->_URL_=$_GET['_URL_'];
		 $counts=$M->where($where)->count();
		 $this->doPage($counts,$pageLimit);
		 $field="order_id,order_sn,order_status,pay_status,shipping_status,goods_amount,order_amount,add_time,pay_type,logistics_sn,logistics_id";
		 $this->orderList=$M->field($field)->where($where)->order('add_time DESC')->limit($GLOBALS['limit'])->select();
	}
	
	//订单详情
	protected function basicOrderShow(){
	    $orderWhere=array('order_id'=>$_REQUEST['order_id'],'user_id'=>$this->userId);
		//修改订单地址
		if($_REQUEST['address_update']){
		   M('order_info')->where($orderWhere)->save($_REQUEST);
		   val_json("submits",true,isL(L('ModifySuccess'),"修改成功"),'','','');
		}
     	$order=M('order_info')->where($orderWhere)->find();
		$order['discount_data']=$order['discount_data'] ? json_decode($order['discount_data'],true) : array();
		$order['bonus_data']=$order['bonus_data'] ? json_decode($order['bonus_data'],true) : array();
		$goodsList=M('order_goods')->where(array('order_id'=>$_GET['order_id']))->select(); //商品列表
		$goodsName='';
		foreach($goodsList as $k=>$v){
			$goodsList[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
			$goodsList[$k]['rec_info']=$v['rec_info'] ? json_decode($v['rec_info'],true) : array();
			$goodsName.=$v[pfix('goods_name')].'|';
			if($v['is_reviews'] > 0) $order['is_reviews']=true;
		}
		$this->goodsName=rtrim($goodsName,'|');
		$order['goodsList']=$goodsList;
		$order['region']=$this->basicModel->getRegion($order['country'],$order['province'],$order['city'],$order['district']);   //国家、省、城市、区县
		if($order['is_inv'] >0){ //需要发票
		     if($order['is_inv']==1){//普通发票
			     $invs=M('member_pu_tong_inv')->where(array('member_id'=>$order['user_id']))->find();
			 }else if($order['is_inv']==2){//增值税发票
			     $invs=M('member_zeng_zhi_inv')->where(array('member_id'=>$order['user_id']))->find();
				 $district=$invs['district'] ? ','.$invs['district'] : '';
				 $regId=$invs['country'].','.$invs['province'].','.$invs['city'].$district;
				 $region=M('region')->field('region_name')->where("region_id IN({$regId})")->order(array('region_id'=>'ASC'))->select();
				 $invs['address']=$region[0]['region_name'].$region[1]['region_name'].$region[2]['region_name'].$region[3]['region_name'].$invs['address'];
			 }
		}
		//退货订单
		if($order['order_status'] >= 3){
		    $retreat_data=M('retreat_data')->where("order_id='{$order['order_id']}'")->find();
			$retreat_data['goods_data']=$retreat_data['goods_data'] ? json_decode($retreat_data['goods_data'],true) : array();
			$region_ids=$retreat_data['country'].','.$retreat_data['province'].','.$retreat_data['city'].','.$retreat_data['district'];
			$region=M('region')->field("region_id,region_name")->where("region_id IN ({$region_ids})")->select();
			$reg=array();
			foreach($region as $k=>$v) $reg[$v['region_id']]=$v['region_name'];
			$retreat_data['country']=$reg[$retreat_data['country']];
			$retreat_data['province']=$reg[$retreat_data['province']];
			$retreat_data['city']=$reg[$retreat_data['city']];
			$retreat_data['district']=$reg[$retreat_data['district']];
			$this->retreat_data=$retreat_data;
			//退货理由只获取中文
			$this->RETURN_RREAS=C(pfix('RETURN_RREAS'));		
			//p($retreat_data);die;
		}
		//p($retreat_data);die;
		$this->invs=$invs;		
		$this->order=  $order; 
		$this->goodsSats=isL(L('GOODS_SATISFY'),C('GOODS_SATISFY')); //买后感受
		//是否增送现金卷
		if($order['bonus_type_id'] > 0){
			$this->bt=M('bonus_type')->field(pfix('bonus_name').' AS bonus_name,bonus_money')->where(array('id'=>$order['bonus_type_id']))->find();
		}

		//物流去向信息				
		 if($order['logistics_id']){
		   $shipping=M('shipping')->field('shipping_code,shipping_name')->where(array('shipping_id'=>$order['logistics_id']))->find();
		  $this->shipping=$shipping;
		   if($order['logistics_sn']){
			   if(C('IS_LOGISTICS')){	
				   if($shipping){
						$this->iframeUrl=get_kuaidi100($shipping['shipping_code'],$order['logistics_sn']);
				   }					
				}
			}
		 }
         //支付银行
         if($order['order_status']==1 && $order['pay_status'] < 2 && ((time() - $order['add_time']) < C('ORDERRETAIN'))){
         	 $this->payment=M('payment')->where(array('enabled'=>1,'is_show'=>1))->order('pay_id ASC')->select();
         }
	}
	
	//余额支付
	protected function basicBalancePays(){
		$times=time();
	    $orderId=$_POST['order_id'] ? getNum($_POST['order_id']) : val_json('submits2',false,'非法操作','','');
		$password=$_POST['user_password'] ? emptyHtml($_POST['user_password']) : val_json('user_password',false,'密码不可为空','','');
		$orderM=M('order_info');
		$orderWhere="order_id='{$orderId}' AND user_id='{$this->userId}' AND order_status IN(0,1) AND pay_status < 2 AND shipping_status < 2";
		$orderInfo=$orderM->field('order_id,order_sn,order_amount')->where($orderWhere)->find();
		if(!$orderInfo) val_json('submits2',false,'不存在此订单','','');	
		$ME=M('member');
		$member=$ME->field('user_name,password,sha1_random,member_funds')->where(array('id'=>$this->userId,'is_lock'=>0))->find();		
		if(!compare_sha1($password,$member['sha1_random'],$member['password'])) val_json('submits2',false,'密码错误','','');
		if($orderInfo['order_amount'] > $member['member_funds']) val_json('submits2',false,'余额不足请充值',U(is_m().'/Member/accountCZ'),'');
		//启动事务
		$startTrans=true;
		$this->basicModel->startTrans(); 		
		//减少会员帐户
		if(!$ME->where(array('id'=>$this->userId))->setDec('member_funds',$orderInfo['order_amount'])) $startTrans=false;
		//修改订单状态
		if(!$orderM->where($orderWhere)->save(array('pay_status'=>2,'order_status'=>1,'pay_time'=>$times))) $startTrans=false;
		//帐户变动原因
		$account['member_id']=$this->userId;
		$account['transaction']=0;
		$account['return_mark']=0;
		$account['member_money']=-$orderInfo['order_amount'];
		$account['frozen_money']=0;
		$account['rank_points']=0;
		$account['pay_points']=0;
		$account['change_time']=$times;		
		$account['change_desc']='余额支付订单，订单号:'.$orderInfo['order_sn'];
        $account['change_type']=6;
		if(!M('member_account')->add($account)) $startTrans=false;		
	    //事务是否成功
        if($startTrans){
		     $this->basicModel->commit();			 
			 val_json('submits2',true,'支付成功',$_SERVER["HTTP_REFERER"],'');
	    }else{
		     $this->basicModel->rollback();	
			 val_json('submits2',false,'支付失败','','');
	    }			
	}	
	
	//确定交易完成
	protected function basicComDeal(){
		$o=M('order_info');
		$where="order_id='".getNum($_GET['order_id'])."' AND user_id='{$this->userId}'";
		$ofield="order_id,order_sn,user_id,order_status,pay_status,shipping_status,pay_points,rank_points,bonus_type_id";
		$order=$o->field($ofield)->where($where)->find();
		if(!$order)  val_json("submits",false,'不存在此数据','','','');
		if(!($order['order_status'] ==1) OR !($order['pay_status'] >=2) OR !($order['shipping_status'] >=2)) val_json("submits",false,'订单状态异常','','','');
		 //启动事务
		$o->startTrans();
		if(!$o->where($where)->save(array('order_status'=>2,'shipping_status'=>3))){
			$o->rollback();
			val_json("submits",false,'更新订单状态失败','','','');
		}
		//增送会员优惠
       $os[0]['order_id']=$order['order_id'];
       $os[0]['user_id']=$order['user_id'];		       
	   $os[0]['pay_points']=$order['pay_points'];
	   $os[0]['rank_points']=$order['rank_points'];		
	   $os[0]['bonus_type_id']=$order['bonus_type_id'];  
  	   $model=new CommonModel();
	   $div=$model->deliveryUser($os);
	   if(empty($div['success'])){
	   	   $o->rollback();
		   val_json("submits",false,$div['msg'],'','','');
	   }
	   $o->commit();
	   val_json("submits",true,isL(L('Success'),"操作成功"),sign_url(array('order_id'=>$_GET['order_id']),U('Member/appraise')),'','');	
	}
	
	//取消订单
	protected function basicCancelOrder(){
		   $o=M('order_info');
		   $where['order_id']=getNum($_GET['order_id']);
		   $where['order_status']=1;
		   $where['pay_status']=0;
		   $where['shipping_status']=0;
		   $where['user_id']=$this->userId;
		   $is_true=$o->where($where)->delete();
		   if($is_true) jump($_SERVER["HTTP_REFERER"]);//val_json("submits",true,isL(L('Success'),"操作成功"),$_SERVER["HTTP_REFERER"],'','');   	   
		   jump($_SERVER["HTTP_REFERER"]);
		   exit();
		   $field='order_id,user_id,use_integral,bonus_sn';
		   $order=$o->field($field)->where($where)->find();
		   if($order){
		   	  $o->startTrans(); 
			   //标记为已取消
			   if(!$o->where($where)->save(array('order_status'=>0))){
			   	   $o->rollback();
				   val_json("submits",false,'修改订单状态失败','','','');
			   }
			   //退回用户所使用优惠政策
		       $os[0]['user_id']=$order['user_id'];
		       $os[0]['order_id']=$order['order_id'];
			   $os[0]['use_integral']=$order['use_integral'];
			   $os[0]['bonus_sn']=$order['bonus_sn'];		
			   $model=new CommonModel();
			   $returnu=$model->returnUser($os,false);
			   if(!$returnu['success']) {
			   	   $o->rollback();
				   val_json("submits",false,$returnu['msg'],'','','');
			   }	
			   //能来到这里就成功
			   $o->commit();		
			   val_json("submits",true,isL(L('Success'),"操作成功"),$_SERVER["HTTP_REFERER"],'','');   	   
		   }
	}
	
	//退货退款管理表单处理
	protected function basicRefund(){
	   $orderId=getNum($_REQUEST['order_id']);
	   $data['refund_reason']=
	   $_REQUEST['refund_reason'] ? 
	   emptyHtml(strip_tags($_REQUEST['refund_reason'])) : 
	   val_json("refund_reason",false,isL(L('ReasonForReturning'),"退货退原因不可空"),'','','');
	   $where="order_status=1 AND pay_status >=2 AND shipping_status >=2 AND order_id='{$orderId}' AND user_id='{$this->userId}'";
	   $data['order_status']=3;
	   if(M('order_info')->where($where)->save($data)){
		    //退货需发邮件通知用户
			$of='order_sn,goods_amount,order_amount,add_time,surname,consignee,coun_id,province,city,district,address,zipcode,tel';
			$order=M('order_info')->field($of)->find();
			$og=M('order_goods')->field(pfix('goods_name').' AS goods_name,goods_sn,goods_price,goods_attr')->where("order_id='{$orderId}'")->select();
			foreach($og as $k => $v){
				if($v['goods_attr']) $tempAttrs=json_decode($v['goods_attr'],true);
				else $tempAttrs=array();
				$tr.='<tr><td align="left" style="line-height:40px;">'.$v['goods_name'].'</d><td align="left" style="line-height:40px;">'.$v['goods_sn'].'</d>';
				foreach($tempAttrs as $attrv) {
					if($attrv[pfix('name')]=='尺码' || strtoupper($attrv[pfix('name')]=='SIZE')) {
						$sizeval=$attrv['value'][pfix('attr_value')]; 
						break;
					}
				}
				$tr.='<td align="left" style="line-height:40px;">'.$sizeval.'</td>';
				$tr.='<td align="left" style="line-height:40px;">'.$v['goods_number'].'</td>';
				$tr.='<td align="right" style="line-height:40px;">'.C('KTEC_H').$v['goods_price'].'</td>';
				$tr.='</tr>';	
				$sizeval='';
			}
			
			$member=M('member')->field('user_name,surname,full_name')->where("id = '{$this->userId}'")->find();
			$tpl='./Public/EmailTpl/MemberRefund/'.pfix('thsq').'.html';
			$contents=file_get_contents($tpl);
			$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
			$contents=str_replace('{$str1}',date('Y/m/d',time()),$contents);
			$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name'].' '.$member['surname']),$contents);
			//订单号
			$contents=str_replace('{$order_sn}',$order['order_sn'],$contents);
			//订单日期(只显示英文日期)
			$contents=str_replace('{$date}',date('Y/m/d',time()),$contents);
			//姓名
			$contents=str_replace('{$names}',$order['consignee'].' '.$addresss['surname'],$contents);
			//地址
			$contents=str_replace('{$address}',$order['address'],$contents);
			//国、省、市
			$ids=$order['coun_id'] .','.$order['province'].','.$order['city'].','.$order['district'];
			$region=M('region')->field("region_name")->where("id IN ({$ids})")->order("parent_id ASC")->select();		
			//国家
			if($region[0]) $contents=str_replace('{$country}',$region[0],$contents);
			else $contents=str_replace('{$country}','',$contents);	
			//省份
			if($region[1]) $contents=str_replace('{$province}',$region[1],$contents);
			else $contents=str_replace('{$province}','',$contents);	
			//城市
			if($region[2]) $contents=str_replace('{$city}',$region[2],$contents);
			else $contents=str_replace('{$city}','',$contents);		
			//区县
			if($region[3]) $contents=str_replace('{$district}',$region[3],$contents);		
			else $contents=str_replace('{$district}','',$contents);		
			//电话
			$contents=str_replace('{$tle}',$order['tel'],$contents);
			//邮编
			$contents=str_replace('{$zip}',$order['zipcode'],$contents);
			//商品信息
			$contents=str_replace('{$goodsList}',$tr,$contents);
			//商品总金额
			$contents=str_replace('{$goods_amount}',$order['goods_amount'],$contents);		
			//总金额
			$contents=str_replace('{$orderAmount}',$order['order_amount'],$contents);
		    //货币符号
		    $contents=str_replace('{$KTEC_H}',C('KTEC_H'),$contents);	
			//$result=send_mail($member['user_name'],isL(L('WithdrawalGoods'),'上门提取退货'),$contents,false);				
			send_mail2($member['user_name'],isL(L('WithdrawalGoods'),'上门提取退货'),$contents);
			
			
			
			
			
		   val_json("submits4",true,isL(L('Success'),"操作成功"),$_SERVER["HTTP_REFERER"],'','');
	   }else{
		  val_json("submits4",false,isL(L('Failure'),'操作失败'),'','','');
	   }	
	}
	
	//商品等待评价列表
	protected function basicWaitAppraise($pageLimt,$getCount=false){
		$counts="SELECT count(g.rec_id) counts FROM ".PREFIX."order_goods AS g JOIN ".PREFIX."order_info AS o ";
		$counts.="ON(g.order_id=o.order_id) WHERE o.order_status=2 AND g.is_reviews=0 AND o.user_id='{$this->userId}'";
		$sums=$this->basicModel->query($counts);
		$sums=$sums[0]['counts'];
		//只获取个数
		if($getCount) return $sums;
		$this->doPage($sums,$pageLimt);
		$sql="SELECT g.rec_id,g.order_id,g.goods_brand_id,g.goods_id,".pfix('goods_name')." AS goods_name,";
		$sql.="g.goods_thumb,g.goods_number,g.market_price,g.goods_price,g.times FROM ".PREFIX."order_goods AS g JOIN ".PREFIX."order_info AS o ";
		$sql.="ON(g.order_id=o.order_id) WHERE o.order_status=2 AND g.is_reviews=0 AND o.user_id='{$this->userId}' ORDER BY g.times ASC LIMIT ".$GLOBALS['limit'];
		$this->result=$this->basicModel->query($sql);
		$this->display();
	} 	
	
	//商品评价添加/修改页面
	protected function basicAppraise(){
		$data=array();
		$orderId=getNum($_REQUEST['order_id']);		
	    $appraise=$this->isGoodsApp($orderId); //商品是否可评价		
		if(!$appraise) return array('success'=>false,'msg'=>isL(L('NotPurchasedGoods'),"你未购买过此商品不能进行评价"));
		$data['goods_sats']=isL(L('GOODS_SATISFY'),C('GOODS_SATISFY')); //买后感受
		//订单商品
		$f='rec_id,order_id,goods_id,goods_sn,'.pfix('goods_name').' AS goods_name,goods_thumb,goods_number,';
		$f.='market_price,goods_price,goods_attr,goods_attr_ids,rec_info,times,is_reviews';
		$go=M('order_goods')->field($f)->where(array('order_id'=>$orderId))->select();
		foreach($go as $k=>$v){
			$go[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) :array();
			$go[$k]['rec_info']=$v['rec_info'] ? json_decode($v['rec_info'],true) :array();
			$go[$k]['goods_attr_ids']=$v['goods_attr_ids'] ? json_decode(',',$v['goods_attr_ids']) :array();
			$go[$k]['appraise_id_sign']=md5((0).C('APP_KEY'));
		}
		$data['order_goods']=$go;
		//已评价的数据
		$goods_appraise=M('goods_appraise')->where(array('order_id'=>$orderId))->select();
		foreach($goods_appraise as $k=>$v) {
			$goods_appraise[$k]['slide_show']=$v['slide_show'] ? json_decode($v['slide_show'],true) : array();
			$goods_appraise[$k]['appraise_id_sign']=md5($v['id'].C('APP_KEY'));
		}
		$gaArr=array();
		foreach($goods_appraise as $v) $gaArr[$v['goods_id']]=$v;
		$data['goods_appraise']=$gaArr;
		//p($data);die;
		return array('success'=>true,'msg'=>'','data'=>$data);
	}
	
	//商品评价表单处理
	protected function basicAppraiseForm(){
		  $orderId=getNum($_REQUEST['order_id']);		
	      $appraise=$this->isGoodsApp($orderId); //商品是否可评价		
		  if(!$appraise) return array('success'=>false,'msg'=>isL(L('NotPurchasedGoods'),"你未购买过此商品不能进行评价"));	
		 
		  $times=time();
		  $post=array();
		  $apid='';
		  foreach($_REQUEST['content'] as $k=>$v){
			    $post[$k]['error_class']=$_REQUEST['class_name_'.$k];
			    $post[$k]['goods_id']=$_REQUEST['goods_id'][$k];
				$post[$k]['appraise_id']=$_REQUEST['appraise_id'][$k];
			    //ID签名
			    $appraise_id_sign=$_REQUEST['appraise_id_sign'][$k];
				if(md5($post[$k]['appraise_id'].C('APP_KEY')) != $appraise_id_sign) return array('success'=>false,'msg'=>'Error!','error_class'=>$post[$k]['error_class']);		
				$post[$k]['content']=$v;
				$post[$k]['sva']=$_REQUEST['sva_'.$k];
				$post[$k]['is_open']=$_REQUEST['is_open_'.$k];
				$post[$k]['slide_show']=$_REQUEST['img_'.$k] ? json_encode($_REQUEST['img_'.$k]) : '';
				if($post[$k]['appraise_id']) {
					return array('success'=>false,'msg'=>'Error!');	
					$apid.=$post[$k]['appraise_id'].',';
				}
		  }		
		  //p($_POST);die;
		  $order_goods=M('order_goods');
		  $g=M('goods');
		  $M=M('goods_appraise');
		  if($apid){
			  $apid=rtrim($apid,',');
			  $res=$M->where("id IN ({$apid})")->find();
		  }
		  //写数据库
		  foreach($post as $k=>$v){
			  $data['goods_id']=$v['goods_id'];
			  $data['order_id']=$orderId;
			  
			  $data['feel']='';
			  $data['answer']='';
			  $data['answer_time']=0;
			  $data['padtetimes']=0;
			  $data['content']=$v['content'] ? strip_tags(trim($v['content'])) : '';
			  //if(!$data['content']) return array('success'=>false,'msg'=>isL(L('PleaseEvaluationContent'),"请输入评价内容"),'error_class'=>$v['error_class']);
			  $data['member_id']=$this->userId;
			  if(!$v['appraise_id']) $data['times']=$times;
			  $data['is_show']=C('USER_REVIEWS') ? 0 : 1;		
			  $data['slide_show']=$v['slide_show'];
			  $data['is_open']=$v['is_open'];
			  if(!$data['content'] && !$data['slide_show']){
				  $data['content']='好评~';
				  $v['sva']=2;
				  $score=5;
			  }else{
				  if($v['sva']==2) $score=5;
				  else if($v['sva']==1) $score=3;
				  else if($v['sva']==0) $score=1;	
			  }
              $data['score']=$score;
			  $data['sva']=$v['sva'];
			  //修改
			 if($v['appraise_id']){
				  if($res['padtetimes'] > 0) return array('success'=>false,'msg'=>isL(L('EvaluationCodifiedOnce'),'对不起，评价只可修改一次'),'error_class'=>'submits'); 
				  if(($times-$res['times']) > 86400) 
				  return array('success'=>false,'msg'=>isL(L('EvaluationCodifiedOnce24'),"对不起，评价只可在发表后24小里内进行修改"),'error_class'=>'submits'); 
				  $data['padtetimes']=$times;
				  unset($data['answer']);
				  $M->where(array('id'=>$v['appraise_id'],'member_id'=>$this->userId,'order_id'=>$orderId))->save($data);					 
			 }else{
			      $id=$M->add($data);
			      //标记为已评价
			      $order_goods->where(array('order_id'=>$orderId,'goods_id'=>$v['goods_id']))->save(array('is_reviews'=>1));		
				  //标记订单交易完成
				  M('order_info')->where("id='{$orderId}' AND user_id='{$this->userId}'")->save(['order_status'=>2]);
			 }
		     //把用户评价内容重新计算写到商品表
		     $counts=$M->field("count(id) AS counts,sum(score) AS score")->where(array('goods_id'=>$v['goods_id']))->find();		  
             $goods['review_score']=round($counts['score'] / $counts['counts'],1); //评分
             $goods['good_per']=$goods['review_score'] * 10; //好评百分比
             $goods['stars_count']=$goods['review_score']; //星星个数
             $goods['review_count']=$counts['counts']; //评价个数             
             $g->where(array('id'=>$v['goods_id']))->save($goods);			 
		  }
		 // return array('success'=>true,'msg'=>isL(L('Success'),"操作成功"),'error_class'=>'submits');
		  return array('success'=>true,'msg'=>'','error_class'=>'submits');
	}
	
	//判断的商品是否可评价
	private function isGoodsApp($orderId){
		  $order=M('order_info')->field('order_id,order_status,pay_status,shipping_status,shipping_time')->where(array('order_id'=>$orderId,'user_id'=>$this->userId))->find();
		  if($order['pay_status'] == 2){
		      if(($order['shipping_status'] == 3) || ($order['shipping_time'] + (C('ORDER_RETURN_DATE') * 86400) > time())){
				  return true;
			  }
		  }
	}
	
	//商品评价列表
	protected function basicAssessList($pageLimit){
	    $countSql="SELECT count(ga.id) AS counts FROM ".PREFIX."goods_appraise AS ga JOIN ".PREFIX."goods AS g ";
		$countSql.="ON(ga.goods_id=g.id) WHERE ga.member_id='{$this->userId}'";
		$counts=$this->basicModel->query($countSql);
		$this->_URL_=$_GET['_URL_'];
		$this->doPage($counts[0]['counts'],$pageLimit);
		$sql="SELECT ga.*,g.{$this->langfx}goods_name as goods_name,g.goods_thumb FROM ".PREFIX."goods_appraise AS ga JOIN ".PREFIX."goods AS g ";
		$sql.="ON(ga.goods_id=g.id) WHERE ga.member_id='{$this->userId}' ORDER BY ga.times DESC LIMIT ".$GLOBALS['limit'];
		$this->result=$this->basicModel->query($sql);
	}
	
	//个人资料
	protected function basicPersonal(){
        $res=$this->basicModel->getMemberData($this->userId);
		$tel=explode('-',$res['telephone']);
		$res['tel1']=$tel[0];
		$res['tel2']=$tel[1];
		$this->region=$this->basicModel->getRegion($res['country'],$res['province'],$res['city'],$res['district']);	
		$this->assign('user',$res);
	}
	
	//个人资料表单处理
	protected function basicPersonalForm(){
        $data['head_pic'] =$_REQUEST['head_pic'] ? 
		quotes(trim(strip_tags($_REQUEST['head_pic']))) :'';
		
		$data['nickname'] =$_REQUEST['nickname'] ? 
		quotes(trim(strip_tags($_REQUEST['nickname']))) :'';
		
		$data['country'] =$_REQUEST['country'] ? 
		quotes(trim(strip_tags($_REQUEST['country']))) : 0;//val_json("country",false,isL(L('SelectCountry'),'请选择国家'),'','','');
		
		$data['province'] =$_REQUEST['province'] ? 
		quotes(trim(strip_tags($_REQUEST['province']))) : 0;//val_json("province",false,isL(L('SelectProvince'),'请选择省份'),'','','');
		
		$data['city'] =$_REQUEST['city'] ? 
		quotes(trim(strip_tags($_REQUEST['city']))) : 0;//val_json("city",false,isL(L('SelectCity'),'请选择城市'),'','','');
		
		$data['district'] =$_REQUEST['city'] ? quotes(trim(strip_tags($_REQUEST['district']))) :0;
		
		$data['full_name'] =$_REQUEST['full_name'] ? quotes(trim(strip_tags($_REQUEST['full_name']))) :'';
		
		$data['surname'] =$_REQUEST['surname'] ? quotes(trim(strip_tags($_REQUEST['surname']))) :'';
		
		$data['telephone'] =$_REQUEST['telephone'] ? quotes(trim(strip_tags($_REQUEST['telephone']))) :'';
		
		//$data['birthday'] =$_REQUEST['birthday'] ? quotes(trim(strip_tags($_REQUEST['birthday']))) :'';
		if($_REQUEST['d3'] > 0 && $_REQUEST['d2'] > 0 && $_REQUEST['d1']) $data['birthday']=$_REQUEST['d3'].'-'.$_REQUEST['d2'].'-'.$_REQUEST['d1'];
		
		$data['sex'] =$_REQUEST['sex']  > 0 ? quotes(trim(strip_tags($_REQUEST['sex']))) :0;
		
		//修改密码
		if($_REQUEST['oldpassword'] && $_REQUEST['password'] && $_REQUEST['not_password']){
			if($_REQUEST['password'] !=$_REQUEST['not_password']) val_json("tel2",false,isL(L('PleaseConfirmYourPassword'),"两次输入的密码不一致, 请重新输入"),'','','');
			//原密码
			$userArr=pwd_sha1($_REQUEST['oldpassword']);//处理密码和加密随机码
			$data['password']=$userArr['SHA1'];
			$data['sha1_random']=$userArr['RANDOM'];	
			$field=array('id','user_name','password','sha1_random');
			$user=M('member')->field($field)->where(array('id'=>$this->userId))->find();
			 if(!$user || !compare_sha1($_REQUEST['oldpassword'],$user['sha1_random'],$user['password'])){
					val_json("old-text",false,isL(L('OriginalPWDerror'),"原密码错误"),'','','');
			  }
			 //新密码
			$arr=pwd_sha1($_REQUEST['password']);
		    $data['password']=$arr['SHA1'];
		    $data['sha1_random']=$arr['RANDOM'];				
		}
		
		//$data['address'] =$_REQUEST['address'] ? 
		//quotes(trim(strip_tags($_REQUEST['address']))) : val_json("address",false,isL(L('AddressCanNotBeEmpty'),'地址不能为空'),'','','');
		
		//$data['reg_email'] =is_email($_REQUEST['reg_email']) ? 
		//$_REQUEST['reg_email'] : val_json("reg_email",false,isL(L('EmailNotNull'),'电子邮件不能为空'),'','','');
		
		//$data['mobile_phone'] =check_mobile($_REQUEST['mobile_phone']) ? 
		//$_REQUEST['mobile_phone'] : val_json("mobile_phone",false,isL(L('HandNumberError'),"手机格式错误"),'','','');
		
		$data['qq'] =is_nums($_REQUEST['qq']) ? $_REQUEST['qq'] : 0;
		$data['wechat'] =is_abc_num($_REQUEST['wechat']) ? $_REQUEST['wechat'] : 0;
		
		if($_REQUEST['tel1']){
			if(!is_int(intval($_REQUEST['tel1']))){
				val_json("tel1",false,isL(L('AreaCodeNotNum'),"电话区号必须是数字"),'','','');
			}else{
				$tel1=$_REQUEST['tel1'];
			}
		}
		if($_REQUEST['tel2']){
			if(!is_int(intval($_REQUEST['tel2']))){
				val_json("tel2",false,isL(L('TelephoneNotNum'),"电话号码必须是数字"),'','','');
			}else{
				$tel2=$_REQUEST['tel2'];
			}
		}	
		if($tel1 && $tel2){
			$data['telephone'] =$tel1.'-'.$tel2;
		}
		$data['zip'] =is_int(intval($_REQUEST['zip'])) ? intval($_REQUEST['zip']) :0;
		$submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
		if(M('member')->where(array('id'=>$this->userId))->save($data)){
		    if($data['head_pic']) {
		        import('Class.DisposeImg',APP_PATH);
			    DisposeImg::addPictures('member',$this->userId,array($data['head_pic']),'','','');							
				set_login(array(C('SE_USER_HEAD_PIC')=>$data['head_pic']),true,0);
			}
			set_login(array(C('SE_NICKNAME')=>$data['full_name'].' '.$data['surname']),true,0);
			//val_json($submits,true,isL(L('ModifySuccess'),"修改成功"),'','','');
			val_json($submits,true,'',U('/Member/index'),'','');
		}else{
			val_json($submits,false,isL(L('YouDidNotModifyAny'),"你未修改任何资料"),U('/Member/index'),'','');
		}	
	}
	
	//修改密码表单处理
	protected function basicChangePWDform(){
		$wornPWD=trim($_REQUEST['wornPWD']);
        $password=trim($_REQUEST['password']);
		$notPassword=trim($_REQUEST['notPassword']);
		$submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
		if(empty($wornPWD)) val_json("wornPWD",false,isL(L('OriginalPWDnot'),"原密码不能为空"),'','','');
		if(empty($password)) val_json("password",false,isL(L('NewPWDnot'),"新密码不能为空"),'','','');
		if($password !=$notPassword) val_json("notPassword",false,isL(L('TwoInputConsistent'),"两次安密码输入不一致"),'','','');
		$userArr=pwd_sha1($password);//处理密码和加密随机码
		$data['password']=$userArr['SHA1'];
		$data['sha1_random']=$userArr['RANDOM'];	
		$data['id']=$this->userId;
		$M=M('member');
        $field=array('id','user_name','password','sha1_random');
		$user=$M->field($field)->where(array('id'=>$data['id']))->find();
         if(!$user || !compare_sha1($wornPWD,$user['sha1_random'],$user['password'])){
				val_json("wornPWD",false,isL(L('OriginalPWDerror'),"原密码错误"),'','','');
          }else{
               if($M->save($data)){
					 val_json($submits,true,isL(L('ModifySuccess'),"修改成功"),U(is_m().'/Member/index'),'','');
			   }else{
					val_json($submits,false,isL(L('ModifyFailure'),"修改失败"),'','','');
			   }
         }			
	}
	
	//收货地址列表
	protected function basicAddressList(){
		$sql="SELECT a.*,(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.country) AS r_country,";
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.province) AS r_province,";
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.city) AS r_city,";
		$sql.="(SELECT r.region_name FROM ".PREFIX."region AS r WHERE r.region_id = a.district) AS r_district";
		$sql.=" FROM ".PREFIX."member_address AS a WHERE user_id ='{$this->userId}'  ORDER BY a.default_address DESC";
		$address=$this->basicModel->query($sql);
		$this->address=$address;
		$this->counts=count($address);	
	}
	
	//设置为默认地址
	protected function basicSetDefault(){
			$address=M('member_address');
			$address->where(array('user_id'=>$this->userId))->save(array('default_address'=>0));
			$info=$address->where(array('user_id'=>$this->userId,'address_id'=>$_GET['address_id']))->save(array('default_address'=>1));
			if($info){
				val_json("submits",true,isL(L('SDASUCCESS'),"设置默认地址成功"),$_SERVER["HTTP_REFERER"],'','');
			}else{
				val_json("submits",false,isL(L('SDAFAULURE'),"设置默认地址失败"),'','','');
			}	
	}
	
	//删除会员地址
	protected function basicDelAddress(){
		  $res=M('member_address')->where(array('address_id'=>$_GET['address_id'],'user_id'=>$this->userId))->delete();
		  if($res){
			  val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/index',array('address'=>'show','dre'=>mt_rand(1,100))).'#address-h1','','');
		  }else{
			  val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		  }	
	}
	
	//添加修改地址界面
	protected function basicAddress(){
		//$coms=new  CommonModel();
	     if($_GET['address_id']){
		     $this->title=isL(L('ChangeAddress'),"修改地址");
			 $res=M('member_address')->where(array('address_id'=>$_GET['address_id'],'user_id'=>$this->userId))->find();
			 //$this->region=$coms->getRegion($res['coun_id'],$res['province'],$res['city'],$res['district']); 
	         //省份列表
			 $r=new RegionModel();
			 $regname='';
			 if($res['province']) $regname.="'".$res['province']."',";
			 if($res['city']) $regname.="'".$res['city']."',";
			 if($res['district']) $regname.="'".$res['district']."',";
			 $regname=rtrim($regname,',');
			 $region=M('region')->field('region_id')->where("region_name IN ({$regname})")->order("parent_id ASC")->select();
			 $this->region=$r->getRegion(array('province'=>$region[0]['region_id'],'city'=>$region[1]['region_id'],'district'=>$region[2]['region_id']));				 
			 $this->res=$res;
		 }else{
		     $this->title=isL(L('AddAddress'),"添加地址"); 
			// $this->region=$coms->getRegion('','','','');
	         //省份列表
			 $r=new RegionModel();
			 $this->region=$r->getRegion(array('province'=>''));				 
		 }
		 
		 if($_GET['sends']=='ok'){
		     $this->teturnUrl=$_SERVER["HTTP_REFERER"];
		 }	
	}
	
	//添加修改地址表单处理
	protected function basicAddressForm(){
		  $submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
		  
		   $data['surname']=$_REQUEST['surname'] ? 
		   quotes(strip_tags(trim($_REQUEST['surname']))) : val_json($submits,false,isL(L('NameCanNotBeEmpty'),"收货人姓名不能为空"),'','','');
		   		
		   $data['consignee']=$_REQUEST['consignee'] ? 
		   quotes(strip_tags(trim($_REQUEST['consignee']))) : val_json($submits,false,isL(L('NameCanNotBeEmpty'),"收货人姓名不能为空"),'','','');
		   
		  // $data['mobile']=is_numeric($_REQUEST['mobile']) ?  
		  // quotes(strip_tags(trim($_REQUEST['mobile']))) : val_json("mobile",false,isL(L('HandNumberError'),"手机格式错误"),'','','');
		  // if(!($_REQUEST['coun_id'] > 0)) val_json($submits,false,isL(L('SelectCountry'),"请选择国家"),'','','');
		  // $region=M('region')->field('region_id,region_name')->where(array('region_id'=>1))->find();
		   $data['coun_id']=0;
		   $data['country']=$_REQUEST['country'] ? $_REQUEST['country'] : '中国';
		   
           $data['province']=$_REQUEST['province'] ?  
		   emptyHtml($_REQUEST['province']) : val_json($submits,false,isL(L('SelectProvince'),'请选择省份'),'','','');
           $data['city']= $_REQUEST['city'] ?  
		   emptyHtml($_REQUEST['city']) : val_json($submits,false,isL(L('SelectCity'),'请选择城市'),'','','');
           $data['district']=$_REQUEST['district'] ?  emptyHtml($_REQUEST['district']) : 0;
		   $rids='';
		   if($data['province']) $rids.=$data['province'].',';
		   if($data['city']) $rids.=$data['city'].',';
		   if($data['district']) $rids.=$data['district'].',';
		   $rids=rtrim($rids,',');
		   $region=M('region')->field('region_id,region_name')->where("region_id IN ({$rids})")->select();
		   foreach($region as $v) $reg[$v['region_id']]=$v['region_name'];
		   $data['province']=$reg[$data['province']];
		   $data['city']=$reg[$data['city']]; 
		   $data['district']=$reg[$data['district']]; 
		   
	       $data['email']=$_REQUEST['email'] ?  quotes(strip_tags(trim($_REQUEST['email']))) : '';
           $data['address']=$_REQUEST['address'] ?  
		   quotes(strip_tags(trim($_REQUEST['address']))) : val_json($submits,false,isL(L('AddressCanNotBeEmpty'),"祥细地址不能为空"),'','','');
		   $data['zipcode']=$_REQUEST['zipcode'] ? $_REQUEST['zipcode']: '';
		   $data['tel']=$_REQUEST['tel'] ? $_REQUEST['tel']: '';
		   $data['user_id']=$this->userId;
		   if($_REQUEST['default']) {
		        M('member_address')->where(array('user_id'=>$this->userId))->save(array('default_address'=>0)); 
				$data['default_address']=1;
		   }
		   if($_REQUEST['address_id']){
		        if(M('member_address')->where(array('user_id'=>$this->userId,'address_id'=>$_REQUEST['address_id']))->save($data)){
				   if($_REQUEST['check_out']=='ok') val_json($submits,true,'',$_SERVER["HTTP_REFERER"],'','');
				   if($_REQUEST['returnUrl']){
				        val_json($submits,true,'',$_REQUEST['returnUrl'],'','');
				   }else{
					    //val_json($submits,true,isL(L('ModifySuccess'),"修改成功"),U(is_m().'/Member/index',array('address'=>'show')).'#address-h1','','');
					    val_json($submits,true,'',U('Member/index',array('address'=>'show','dre'=>mt_rand(1,100))).'#address','','');
				   }
				}else{
				   if($_REQUEST['check_out']=='ok')  val_json($submits,true,'',$_SERVER["HTTP_REFERER"],'','');
				   if($_REQUEST['returnUrl']){
					   val_json($submits,true,'',$_REQUEST['returnUrl'],'','');
				   }else{
					   val_json($submits,false,'',U('Member/index',array('address'=>'show','dre'=>mt_rand(1,100))).'#address','','');
				   }				
				}
		   }else{
			   //p($data);die;
			   $data['district']=$data['district'] ? $data['district'] : '';
			   $data['email']=$data['email'] ? $data['email'] : '';
		        if(M('member_address')->add($data)){
					if($_REQUEST['check_out']=='ok') val_json($submits,true,'',$_SERVER["HTTP_REFERER"],'','');
				   if($_REQUEST['returnUrl']){
				        val_json($submits,true,'',$_REQUEST['returnUrl'],'','');
				   }else{
					   val_json($submits,true,'',U('Member/index',array('address'=>'show','dre'=>mt_rand(1,100))).'#address','','');
				   }					   
				}else{
				   if($_REQUEST['check_out']=='ok') val_json($submits,false,isL(L('YouDidNotModifyAny'),'你未修改任何资料'),'','','');
				   if($_REQUEST['returnUrl']){
				        val_json($submits,true,'',$_REQUEST['returnUrl'],'','');
				   }else{
					   val_json($submits,false,isL(L('YouDidNotModifyAny'),'你未修改任何资料'),'','','');
				   }				
				}		   
		   }	
	}
	
	//普通发票
	protected function basicPuToug(){
	     $this->res=M('member_pu_tong_inv')->where(array('member_id'=>$this->userId))->find();
	}
	
	//普通发票表单处理
	protected function basicPuTougForm(){
       $data['inv_title']=$_REQUEST['inv_title'] ? 
	   quotes(strip_tags(trim($_REQUEST['inv_title']))) : val_json("inv_title",false,isL(L('RiseCanNotBeEmpty'),"发票抬头不能为空"),'','','');
	   $data['inv_content']=$_REQUEST['inv_content'] ? quotes(strip_tags(trim($_REQUEST['inv_content']))) : '';
	   $data['member_id']=$this->userId;
	   $submitId=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
	   $puToug=M('member_pu_tong_inv');
	   if($puToug->field('id')->where(array('member_id'=>$this->userId))->find()){
	         $puToug->where(array('member_id'=>$this->userId))->save($data);
			 val_json($submitId,true,isL(L('SaveSuccess'),"保存成功"),$_REQUEST['returnUrl'],'','');
	   }else{
	         $data['add_time']=time();
	         $puToug->add($data);
			 val_json($submitId,true,isL(L('SaveSuccess'),"保存成功"),$_REQUEST['returnUrl'],'','');		 
	   }	
	}
	
	//增值税发票界面
	protected function basicZengZhi(){
		$coms=new  CommonModel();
        $res=M('member_zeng_zhi_inv')->where(array('member_id'=>$this->userId))->find();   
	    $this->region=$coms->getRegion($res['country'],$res['province'],$res['city'],$res['district']); //默城市
	    $this->res=$res;	 	
	}
	
	//增值税发票表单处理
	protected function basicZengZhiForm(){
	   $submitId=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';   	
	   
       $data['company_name']=$_REQUEST['company_name'] ? 
	   quotes(strip_tags(trim($_REQUEST['company_name']))) : val_json($submitId,false,isL(L('UnitNameEmpty'),'单位名称不可空'),'','','');
	   
       $data['id_code']=is_abc_num($_REQUEST['id_code']) ? 
	   $_REQUEST['id_code'] : val_json($submitId,false,isL(L('IdentifiCation'),'纳税人识别码格式错误'),'','','');
	   
       $data['reg_address']=$_REQUEST['reg_address'] ? 
	   quotes(strip_tags(trim($_REQUEST['reg_address']))) : val_json($submitId,false,isL(L('RegAddressEmpty'),'注册地址不可空'),'','','');   
	   
       $data['reg_tel']=$_REQUEST['reg_tel'] ? 
	   $_REQUEST['reg_tel'] : val_json($submitId,false,isL(L('PhoneFormatError'),'电话格式错误'),'','','');  
	   
       $data['bank_name']=$_REQUEST['bank_name'] ? 
	   quotes(strip_tags(trim($_REQUEST['bank_name']))) : val_json($submitId,false,isL(L('BankIsNotAvailable'),"开户银行不可空"),'','','');   
	   
       $data['bank_sn']=is_abc_num($_REQUEST['bank_sn']) ? 
	   $_REQUEST['bank_sn'] : val_json($submitId,false,isL(L('AccountFormatError'),"银行账户格式错误"),'','',''); 
	   
       $data['inv_content']=$_REQUEST['inv_content'] ? 
	   quotes(strip_tags(trim($_REQUEST['inv_content']))) : val_json($submitId,false,isL(L('InvoiceContentEmpty'),'发票内容不可空'),'','',''); 	   
	   
       $data['shou_piao']=$_REQUEST['shou_piao'] ? 
	   quotes(strip_tags(trim($_REQUEST['shou_piao']))) : val_json($submitId,false,isL(L('InvoiceNameEmpty'),"收票人姓名不可空"),'','',''); 	
	   
       $data['shou_mobile']=is_nums($_REQUEST['shou_mobile']) ? 
	   $_REQUEST['shou_mobile'] : val_json($submitId,false,isL(L('HandNumberError'),"手机格式错误"),'','','');
	   
	   $data['country']=is_numeric($_REQUEST['country']) ? 
	   trim($_REQUEST['country']) : val_json($submitId,false,isL(L('SelectCountry'),"请选择国家"),'','','');
	   
	   $data['province']=is_numeric($_REQUEST['province']) ? 
	   trim($_REQUEST['province']) : val_json($submitId,false,isL(L('SelectProvince'),"请选择省份"),'','','');
	   
	   $data['city']=is_numeric($_REQUEST['city']) ? 
	   trim($_REQUEST['city']) : val_json($submitId,false,isL(L('SelectCity'),"请选择城市"),'','','');
	   
	   $data['district']=is_numeric($_REQUEST['district']) ? trim($_REQUEST['district']) : 0; 
	   
	   $data['address']=$_REQUEST['address'] ? 
	   quotes(strip_tags(trim($_REQUEST['address']))) : val_json($submitId,false,isL(L('StreetsCanNotEmpty'),"详细街道不可空"),'','','');		   
	   $data['member_id']=$this->userId;
	   $zengZhi=M('member_zeng_zhi_inv'); 
	   if($zengZhi->field('id')->where(array('member_id'=>$this->userId))->find()){
	         $zengZhi->where(array('member_id'=>$this->userId))->save($data);
			 val_json($submitId,true,isL(L('SaveSuccess'),"保存成功"),'','','');		 
	   }else{
	         $data['add_time']=time();
	         $zengZhi->add($data);
			 val_json($submitId,true,isL(L('SaveSuccess'),"保存成功"),'','','');
	   }   	
	}
	
	protected function basicIsInv(){
			$table=$_REQUEST['data'];
			if(M($table)->where(array('member_id'=>$this->userId))->find()){
			   die('1');
			}else{
			   die('0');
			}	
	}
	
	//我的咨询列表
	protected function basicWordsList($pageLimit){
	    $M=M('words');
	    $counts=$M->where(array('user_id'=>$this->userId))->count();
		$this->_URL_=$_GET['_URL_'];
	    $this->doPage($counts,$pageLimit);
	    $this->res=$M->where(array('user_id'=>$this->userId))->limit($GLOBALS['limit'])->order(array('id'=>'DESC'))->select();	
	}
	
	//提交我的资咨询表单
	protected function basicWordsFrom(){
		  if(empty($_REQUEST['title'])) val_json("title",false,isL(L('TitleEmptys'),"标题不可空"),'','','');
		  if(empty($_REQUEST['content'])) val_json("content",false,isL(L('ContentEmptys'),"内容不可空"),'','','');
		  $data['title']=quotes(trim(strip_tags($_REQUEST['title'])));
		  $data['content']=quotes(trim(strip_tags($_REQUEST['content'])));
		  $data['user_id']=$this->userId;
		  $data['user_name']=getUserC('SE_USER_NAME');
		  $data['addtiem']=time();
		  if(M('words')->add($data)){
			 val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/wordsList'),'','');
		  }else{
			 val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		  }	
	}
	
	//查看咨询回复
	protected function basicWordsContent(){
		  $res=M('words')->field('title,content,reply,reply_time')->where(array('id'=>$_GET['id'],'user_id'=>$this->userId))->find();
		  if(!$res['reply']) $res['reply']='<span style="color:red;">'.isL(L('PatientWaiting'),'暂没有回复此内容，请耐心等待！').'</span>';
		  if($res['reply_time'] > 0) $res['reply_time']=date('Y-m-d H:i:s',$res['reply_time']);
		  if(IS_AJAX){
			  echo json_encode($res);	
		  }else{
			  $this->res=$res;
		  }
	}
	
	//删除我的咨询
	protected function basicDelWords(){
	     if(M('words')->where("user_id='{$this->userId}' AND id='{$_GET['id']}'")->delete()){
			 val_json("submits",true,isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
		 }else{
			 val_json("submits",true,isL(L('Failure'),'操作失败'),'','','');
	     }
		 
	}
	
	//我的收藏商品列表
	protected function basicCollectionList($pageLimit){
		$M=M('goods_collect');
		$counts=$M->where("user_id='{$this->userId}'")->count();
		$this->_URL_=$_GET['_URL_'];
		$this->doPage($counts,$pageLimit);
		$sql="SELECT r.*,g.{$this->langfx}goods_name AS goods_name,g.goods_thumb FROM ".PREFIX."goods_collect as r JOIN ".PREFIX."goods AS g ";
		$sql.="ON (g.id=r.goods_id) WHERE user_id='{$this->userId}' ORDER BY rec_id DESC LIMIT ".$GLOBALS['limit'];
		$this->result=$M->query($sql);
	}
	
	//删除收藏商品
	protected function basicDelCollection(){
	    if(M('goods_collect')->where("rec_id='{$_GET['id']}' AND user_id='{$this->userId}'")->delete()){
			val_json("submits",true,isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
		}else{
			val_json("submits",true,isL(L('Failure'),'操作失败'),'','','');
		}
	}
	
    //我的浏览器历史
    protected function basicHistory(){
    	$this->recentVisit=$this->recentVisit('','',10);
    	$this->display();
    }
    
    //删除浏览器历史
    protected function basicDeleteHistory(){
    	$recent_visit=cookie('recent_visit');
    	$arrs=explode(',',$recent_visit);
    	$ids=explode(',', $_REQUEST['id']);
		$cookieArr=array_diff($arrs,$ids);
    	$wCookie=implode(',',$cookieArr);
		cookie('recent_visit',$wCookie,604800);//保存一个星期
		val_json("submits",true,isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
    }	
	
	//现金卷列表
	protected function basicRedList($pageLimt){
	  	$sql="SELECT count(b.bonus_sn) AS counts FROM ";
		$sql.=PREFIX."member_bonus AS b JOIN ".PREFIX."bonus_type AS t ";
		$sql.="ON(b.bonus_type_id=t.id AND b.member_id=".$this->userId.") ";		
		$counts=$this->basicModel->query($sql);
        $this->doPage($counts[0]['counts'],$pageLimt);
		$sql='';
	  	$sql="SELECT b.bonus_sn,b.used_time,b.order_id,t.picture,t.{$this->langfx}bonus_name AS bonus_name,t.bonus_money,t.min_amount,t.use_start_date,t.use_end_date FROM ";
		$sql.=PREFIX."member_bonus AS b JOIN ".PREFIX."bonus_type AS t ";
		$sql.="ON(b.bonus_type_id=t.id AND b.member_id=".$this->userId.") ORDER BY b.id DESC LIMIT ".$GLOBALS['limit'];
		$this->res=$this->basicModel->query($sql);		
	}
	
	//添加现金卷
	protected function basicRedAdd(){
		  $saves['member_id']=$this->userId;
		  $res=M('member_bonus')->where(array('bonus_sn'=>trim($_GET['bonus_sn']),'member_id'=>0,'used_time'=>0,'order_id'=>0))->save($saves);
		  if($res){
			   val_json("submits",true,isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
		  }else{
			   val_json("submits",true,isL(L('Failure'),'操作失败'),'','','');
		  }	
	}

   //我的帐户余额
   protected function basicCapital($pageLimit){
       $M=M('member_account');
	   $where=array('member_id'=>$this->userId,'member_money'=>array('neq',0));
	   $counts=$M->where($where)->count();
	   $this->_URL_=$_GET['_URL_'];
	   $this->doPage($counts,$pageLimit);
	   $field="id,transaction,return_mark,member_money,change_time,".pfix('change_desc')." AS change_desc,change_type";
	   $result=$M->field($field)->where($where)->order("change_time DESC")->limit($GLOBALS['limit'])->select();
	   $this->result=$result;
	   $userAccount=M('member')->field('member_funds')->where(array('id'=>$this->userId))->find();
	   $this->userAccount=$userAccount['member_funds'];
	   $this->userTitle=isL(L('AccountBalance'),'账户余额');
	   $this->userType=0;
   }

   //我的冻结资金
   protected function basicFrozen($pageLimit){
       $M=M('member_account');
	   $where=array('member_id'=>$this->userId,'frozen_money'=>array('neq',0));
	   $counts=$M->where($where)->count();
	   $this->_URL_=$_GET['_URL_'];
	   $this->doPage($counts,$pageLimit);
	   $field="id,frozen_money,change_time,".pfix('change_desc')." AS change_desc,change_type";
	   $result=$M->field($field)->where($where)->order("change_time DESC")->limit($GLOBALS['limit'])->select();
	   $this->result=$result;
	   $userAccount=M('member')->field('frozen_funds')->where(array('id'=>$this->userId))->find();
	   $this->userAccount=$userAccount['frozen_funds'];
	   $this->userTitle=isL(L('FrozenFunds'),'冻结资金');
	   $this->userType=1;
   }
   
	//我的等级积分清单
   protected function basicRankPoints($pageLimit){
       $M=M('member_account');
	   $where=array('member_id'=>$this->userId,'rank_points'=>array('neq',0));
	   $counts=$M->where($where)->count();
	   $this->_URL_=$_GET['_URL_'];
	   $this->doPage($counts,$pageLimit);
	   $field="id,rank_points,change_time,".pfix('change_desc')." AS change_desc,change_type";
	   $result=$M->field($field)->where($where)->order("change_time DESC")->limit($GLOBALS['limit'])->select();
	   $this->result=$result;
	   $userAccount=M('member')->field('rank_points')->where(array('id'=>$this->userId))->find();
	   $this->userAccount=$userAccount['rank_points'];
	   $this->userTitle=isL(L('GradePoints'),'等级积分');
	   $this->userType=2;
   }

   //消费积分 清单
   protected function basicPayPoints($pageLimit){
       $M=M('member_account');
	   $where=array('member_id'=>$this->userId,'pay_points'=>array('neq',0));
	   $counts=$M->where($where)->count();
	   $this->_URL_=$_GET['_URL_'];
	   $this->doPage($counts,$pageLimit);
	   $field="id,pay_points,change_time,".pfix('change_desc')." AS change_desc,change_type";
	   $result=$M->field($field)->where($where)->order("change_time DESC")->limit($GLOBALS['limit'])->select();
	   $this->result=$result;
	   $userAccount=M('member')->field('pay_points')->where(array('id'=>$this->userId))->find();
	   $this->userAccount=$userAccount['pay_points'];
	   $this->userTitle=isL(L('ConsumptionIntegral'),'消费积分');
	   $this->userType=3;
   }   
   
   //在线充值表单处理
   protected function basicAccountCZfrom(){
	   $memberAccount=M('member_account');
       $orderSn='CZ'.pk_sn(PREFIX.'member_account',$memberAccount,12); //流水号
	   $memberMoney=is_numeric($_REQUEST['member_money']) && $_REQUEST['member_money'] > 0 ? 
	   $_REQUEST['member_money'] : 
	   val_json("member_money",true,isL(L('ChargeInputError'),"充值金输入错误"),'','','');
	   $pay=$_REQUEST['pay'] ? trim(strip_tags($_REQUEST['pay'])) : val_json("submits",true,isL(L('PleaseChooseBank'),"请选择支付银行"),'','','');
	   $data['member_id']=$this->userId;
	   $data['transaction']=$orderSn;
	   $data['return_mark']=0;
	   $data['pay_code']=$pay;
	   $data['member_money']=$memberMoney;
	   $data['frozen_money']=0;
	   $data['rank_points']=0;
	   $data['pay_points']=0;
	   $data['change_time']=time();
	   $data['change_desc']='在线充值';
	   $data['change_type']=1;
	   if($id=$memberAccount->add($data)){
			  val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/payment','','').'?id='.$id.'&cz=ok','','');
	   }else{
			  val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
	   }
   }   
   
	//提交支付中转
	protected function basicPayment($url){
	   if(!verify_req_sign($_GET,array(),3600)) {
	   	   header('location:'.$_SERVER["HTTP_REFERER"]);
		   exit();
	   }	
	   //在线充值	
	   if($_GET['cz']=='ok'){
		       $ac=M('member_account')->field('id,pay_code,transaction,change_time')->where(array('id'=>getNum($_GET['id'])))->find();			   
	           $order_sn=$ac['transaction'];
			   $order_goods=isL(L('OnlineRecharge'),'在线充值');
			   $order_time=$ac['change_time'];
			   $pay=$ac['pay_code'];			      
	   }else{ //订单支付
	           $order_sn=filterRequst($_REQUEST['order_sn']);
			   $order_goods=rtrim(filterRequst($_REQUEST['order_goods']),'|');
			   $order_time=filterRequst($_REQUEST['order_time']);
			   $pay=$_REQUEST['pay'];
	   }	
	   $url=get_up_url(array('order_sn'=>$order_sn,'order_time'=>$order_time,'pay'=>$pay),U('/Payment/index','',''),true);
	   $form.='<form action="'.$url.'" id="formid" name="formid" class="formid" method="post">';
	   $form.='<input type="hidden" name="order_goods" value="'.$order_goods.'">';
	   $form.='<input type="submit" value="submit" style="display:none;">';
	   $form.='</form>';
	   $form.='<script>document.forms["formid"].submit();</script>';
	   die($form);
	}
	
	//提现申请列表
	protected function basicExtractionList(){
	    $M=M('member_extraction');
	    $counts=$M->where(array('member_id'=>$this->userId))->count();
	    $this->doPage($counts,$pageLimt);
	    $this->result=$M->where(array('member_id'=>$this->userId))->order(array('id'=>'DESC'))->select();
	    $this->member=M('member')->field('member_funds')->where(array('id'=>$this->userId))->find();		
	    $this->display();
	}
	
	//提现申请界面
	protected function basicExtraction(){
		$this->memberBank=M('member_bank')->where(array('member_id'=>$this->userId))->find();
	    $this->display();
	}
	
	//提现申请表单处理
	protected function basicExtractionForm(){
		$times=time();
		$mex=M('member_extraction');
		//是否有未处理的提现申请
		if($mex->field('id')->where(array('member_id'=>$this->userId,'bank_sn'=>0))->find())  
		val_json("submits",false,'已提交过申请，处理中','','','');
	    $bank['member_id']=$this->userId;
		$bank['bank_type']=$_REQUEST['bank_type'] > 0 ? $_REQUEST['bank_type'] : 0;
		$bank['bank_name']=$_REQUEST['band_name'] ? emptyHtml($_REQUEST['band_name']) :val_json("band_name",false,'银行名称不可空','','','');
		$bank['bank_sn']=is_abc_num($_REQUEST['band_account']) ? 
		$_REQUEST['band_account'] :val_json("band_account",false,'银行帐号格式错误','','','');
		$bank['user_name']=$_REQUEST['user_name'] ? 
		emptyHtml($_REQUEST['user_name']) :val_json("user_name",false,'帐户名称不可空','','','');		
		$bank['bank_child']=$_REQUEST['bank_child'] ? 
		emptyHtml($_REQUEST['bank_child']) : '';		
		$money=$_REQUEST['money'] > 0 ? $_REQUEST['money'] : val_json("money",false,'提现金额错误','','','');		
		if($money > M('member')->where(array('id'=>$this->userId))->getField('member_funds')) val_json("money",false,'提现金额大于你帐户上的金额','','','');		
		//心录会员银行资料
		$me=M('member_bank');
		$bank['if_default']=0;
		$bank['add_time']=$times;
		if($me->field('id')->where(array('member_id'=>$this->userId))->find()){			
			$me->where(array('member_id'=>$this->userId))->save($bank);
		}else{
			$me->add($bank);
		}
		//写入申请表
		$extraction['member_id']=$this->userId;
		$extraction['band_name']=$bank['bank_name'];
		$extraction['band_account']=$bank['bank_sn'];
		$extraction['band_address']=$bank['bank_child'];
		$extraction['user_name']=$bank['user_name'];
		$extraction['money']=$money;
		$extraction['add_time']=$times;
		$extraction['bank_sn']=0;
		$extraction['update_time']=0;
		if($mex->add($extraction)){
			val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/extractionList'),'','');
		}else{
		   val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		}
	}	
   
	//用户身份证列表
	protected function basicIdentity(){
		   
		   if($_REQUEST['id']){
			   $result=M('member_identity')->where("id='{$_REQUEST['id']}' AND user_id='{$this->userId}'")->find();
			   die(json_encode($result));
		   }else{
			  $this->result=M('member_identity')->where("user_id='{$this->userId}'")->order('id DESC')->select();
			  $this->display();
		   }	
	}
	
	//添加修改用户身份证
	protected function basicIdentityAddSave(){
		   
		   if($_REQUEST['id']){
			   $result=M('member_identity')->where("id='{$_REQUEST['id']}' AND user_id='{$this->userId}'")->find();
			   $this->result=$result;
		   }
	}
	
	//添加修改会员身份证表单处理
	protected function basicIdentityForm(){
		   $data['id_name']=$_REQUEST['id_name'] ? 
		   trim(strip_tags($_REQUEST['id_name'])) :  val_json("id_name",false,isL(L('IDNameIsEmpty'),"身份证名称不可为空"),'','','');
		   
		   $data['id_str']=is_abc_num($_REQUEST['id_str']) ? 
		   $_REQUEST['id_str'] : val_json("id_str",false,isL(L('IDNumericError'),"身份证号码格式错误"),'','','');
		   
		   $data['user_id']=$this->userId;
		   $data['add_time']=time();
		   if($_REQUEST['id']){
			   if(M('member_identity')->where("id='{$_REQUEST['id']}' AND user_id='{$this->userId}'")->save($data)) $strats=true;
		   }else{
			   if(M('member_identity')->add($data)) $strats=true;
		   }
		   if($strats){
			   val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/identity'),'','');
		   }else{
			   val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		   }	
	}
	
	//删除身份证
	protected function basicIdentityDelete(){
	      if(M('member_identity')->where("id='{$_REQUEST['id']}' AND user_id='{$this->userId}'")->delete()){
			  val_json("submits",true,isL(L('Success'),'操作成功'),$_SERVER["HTTP_REFERER"],'','');
		  }else{
			  val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		  }
	}

   //密码问题界面
   protected function basicQuestion(){
		$this->questions=C('LANG_SWITCH_ON') ? L('Question') : C('QUESTION'); //显示问题列表
		$this->questionsValue=C('QUESTION');//问题值   	
   }
	
	//密码问题表单处理
	protected function basicQuestionFrom(){
		   if(empty($_REQUEST['question'])) val_json("question",false,isL(L('PleaseChooseProblem'),"请选择问题"),'','','');
		   if(empty($_REQUEST['answer'])) val_json("answer",false,isL(L('QuestionAnswerEmpty'),"请填写问题答案"),'','','');
		   $answer=trim($_REQUEST['answer']);
		   $data['question']=trim($_REQUEST['question']);
		   $data['answer']=md5($answer);
		   if(M('member')->where(array('id'=>$this->userId))->save($data)){
			 val_json("submits",true,isL(L('Success'),'操作成功'),U(is_m().'/Member/index'),'','');
		   }else{
			 val_json("submits",false,isL(L('Failure'),'操作失败'),'','','');
		   }	
	}

	//待评价订单个数和待评价订单ID集合
	private function evaluatedDate(&$db){
			 $evaluated="SELECT o.order_id FROM ".PREFIX."order_info AS o JOIN ".PREFIX."order_goods AS g ";
			 $evaluated.="ON (o.order_id=g.order_id) WHERE o.order_status=2 AND g.is_reviews=0 AND o.user_id='{$this->userId}' GROUP BY g.order_id";
			 $evaluated=$db->query($evaluated);
			 $evaluatedCount=count($evaluated);//待评价个
			 $inId=in_id($evaluated, 'order_id');			
			 return array('evaluated_count'=>$evaluatedCount,'ids'=>$inId);	
	}
	
   /**申请退货*/
   
   //申请退货选择商品
   protected function basicReturnGoods(){
	    $data=array();
		$pfix=pfix();
	    //退货原因
	    $data['return_cause']=C($pfix.'RETURN_RREAS');
		//订单ID
	    $order_id=intval($_REQUEST['order_id']);
		//退货表的ID
		$rd_id=intval($_REQUEST['rd_id']);
		if($rd_id){
			$retreat_data=M('retreat_data')->where(array('id'=>$rd_id,'order_id'=>$order_id,'user_id'=>$this->userId))->find();
			$retreat_data['goods_data']=$retreat_data['goods_data'] ? json_decode($retreat_data['goods_data'],true) : array();
			$data['retreat_data']=$retreat_data;
		}
		//必须未确定的，已付款的
		$data['order']=M('order_info')->field('order_id,order_sn,add_time,shipping_time')
		->where(array('order_status'=>array('egt',1),'pay_status'=>2,'order_id'=>$order_id,'user_id'=>$this->userId))->find();
		if($data['order']){
			$gf="rec_id,goods_sn,{$pfix}goods_name AS goods_name,goods_thumb,goods_number,market_price,goods_price,goods_attr";
			$go=M('order_goods')->field($gf)->where(array('order_id'=>$data['order']['order_id']))->select();
			foreach($go as $k=>$v){
				$go[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
			}
			$data['goods_list']=$go;
		}
		if($data['order'] && $data['goods_list']) return array('success'=>true,'msg'=>'','data'=>$data);
		else return array('success'=>false,'msg'=>'','data'=>$data);
   }
   
   //申请退货填写地址
   protected function basicReturnAddress(){
	    $data=array();
	    $order_id=intval($_REQUEST['order_id']);
		$ow=array('order_status'=>array('gt',0),'pay_status'=>2,'order_id'=>$order_id,'user_id'=>$this->userId);
		$f="order_id,order_sn,add_time,surname,consignee,coun_id,province,city,district,";
		$f.="address,zipcode,tel,mobile,email,shipping_time";
		$data['order']=M('order_info')->field($f)->where($ow)->find();
		//已过退货期限
		if(!is_return($data['order']['shipping_time'])) $this->myInfos(false,'','退货申请已过有效期');
		//省市区
		/*$r=new RegionModel();
		$arrs['country']=$data['order']['coun_id'];
		$arrs['province']=$data['order']['province'];
		$arrs['city']=$data['order']['city'];
		$arrs['district']=$data['order']['district'];
		$data['region']=$r->getRegion($arrs);*/
		$returns=array();
	    foreach($_REQUEST['rec_id'] as $k=>$v){
			$rec_id=intval($v);
			$choosing_return=intval($_REQUEST['choosing_return'][$k]);
			$return_info=emptyHtml($_REQUEST['return_info'][$k]);
			$goods_num=intval($_REQUEST['goods_num'][$k]);
			if($rec_id > 0 && $choosing_return > 0){
				$returns[$rec_id]['rec_id']=$rec_id;
				$returns[$rec_id]['goods_num']=$goods_num;
				$returns[$rec_id]['choosing_return']=$choosing_return;
				$returns[$rec_id]['return_info']=$return_info;
			}
		}
		//包果个数
		$data['packages_num']=intval($_REQUEST['packages_num']);
		//每个my_order_goods 表ID代表一个数组，并转换json->base64格式表单发送
		//$data['goods_data']=base64_encode(json_encode($returns));
		$data['goods_data']=$returns;
		return  $data;
   } 
   
   //申请退确认页(终)
   protected function basicReturnConfirm(){
	    $data=array();
		//包果个数
		$data['packages_num']=intval($_REQUEST['packages_num']);
		$form_data=array();
		$form_data['packages_num']=$data['packages_num'];
	    $order_id=intval($_REQUEST['order_id']);
		$ow=array('order_status'=>array('gt',0),'pay_status'=>2,'order_id'=>$order_id,'user_id'=>$this->userId);
		$f="order_id,order_sn,add_time,shipping_time";
		$data['order']=M('order_info')->field($f)->where($ow)->find();
		if(!$data['order']) return array('success'=>false,'msg'=>'不存在订单数据');
		//写入数据地址数组
		$retreat_addre['order_id']=$order_id;
		$retreat_addre['consignee']=$_REQUEST['consignee'] ? $_REQUEST['consignee'] : '';
		$retreat_addre['country_text']=$_REQUEST['country_text'] ? $_REQUEST['country_text'] : ''; //国家字符串
		$retreat_addre['country']=$_REQUEST['country'] ? $_REQUEST['country'] : ''; //国家ID
		$retreat_addre['province']=$_REQUEST['province'] ? $_REQUEST['province'] : 0; //省份ID
		$retreat_addre['city']=$_REQUEST['city'] ? $_REQUEST['city'] : 0; //城市ID
		$retreat_addre['district']=$_REQUEST['district'] ? $_REQUEST['district'] : 0; //城市ID
		$retreat_addre['address']=$_REQUEST['address'].$_REQUEST['address1'].$_REQUEST['address2'];
		$retreat_addre['zipcode']=$_REQUEST['zipcode'] ? $_REQUEST['zipcode'] : '';
		$retreat_addre['mobile']=$_REQUEST['mobile'] ? $_REQUEST['mobile'] : '';
		$form_data['retreat_addre']=$retreat_addre;
		
		//临时显示地址数组
		$temp_addre=$retreat_addre;
		$rids=$temp_addre['country'].','.$temp_addre['province'].','.$temp_addre['city'].','.$temp_addre['district'];
		$region=M('region')->field('region_id,region_name')->where("region_id IN ({$rids})")->select();
		$reg=array();
		foreach($region as $k=>$v) $reg[$v['region_id']]=$v['region_name'];
		//$temp_addre['country']=$reg[$temp_addre['country']] ? $reg[$temp_addre['country']] : '';
		//$temp_addre['province']=$reg[$temp_addre['province']] ? $reg[$temp_addre['province']] : $retreat_addre['province'];
	//	$temp_addre['city']=$reg[$temp_addre['city']] ? $reg[$temp_addre['city']] : $retreat_addre['city'];
		//$temp_addre['district']=$reg[$temp_addre['district']] ? $reg[$temp_addre['district']] : $retreat_addre['district'];
		$data['temp_addre']=$temp_addre;
		
		//退货商品
		$goods_data=json_decode(base64_decode($_REQUEST['goods_data']),true);
		$rec_id=in_id($goods_data,'rec_id');
		$pfix=pfix();
		$gf="rec_id,{$pfix}goods_name AS goods_name,goods_sn,goods_thumb,goods_number,goods_price,goods_attr";
		$go=M('order_goods')->field($gf)->where("rec_id IN ({$rec_id})")->select();
		foreach($go as $k=>$v) $go[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
		$data['goods_list']=$go;
		
		//退货商品
		$form_data['goods_data']=$goods_data;
		$data['form_data']=base64_encode(json_encode($form_data));
		return array('success'=>true,'data'=>$data);
   }   
   
   //提交确认页表单
   public function basicReturnConfirmForm(){
      $order_id=intval($_REQUEST['order_id']);
	  $form_data=json_decode(base64_decode($_REQUEST['form_data']),true);
	  //包裹数量
	  $packages_num=$form_data['packages_num'];
	  //退货地址
	  $retreat_addre=$form_data['retreat_addre'];
	  //退货商品ID与原因
	  $goods_data=$form_data['goods_data'];
	  $rec_ids=in_id($goods_data,'rec_id');
	  if(!$retreat_addre || !$goods_data) return array('success'=>false,'msg'=>'Error');
	  $ow=array('order_id'=>$order_id,'order_status'=>array('gt',0),'pay_status'=>2,'user_id'=>$this->userId);
	  if(M('order_info')->where($ow)->save(array('order_status'=>3))){
		  $retreat_data=$retreat_addre;
		  $retreat_data['user_id']=$this->userId;
		  $retreat_data['packages_num']=$packages_num;
		  $retreat_data['goods_data']=json_encode($goods_data);
		  $retreat_data['add_time']=time();
		  M('retreat_data')->add($retreat_data);
		  
		    //退货需发邮件通知用户
			$of='order_sn,goods_amount,order_amount,add_time,surname,consignee,coun_id,province,city,district,address,zipcode,tel';
			$order=M('order_info')->field($of)->where(array('order_id'=>$order_id))->find();
			$order['consignee']=$retreat_addre['consignee'];
			$order['country_text']=$retreat_addre['country_text'];
			$order['coun_id']=$retreat_addre['country'];
			$order['province']=$retreat_addre['province'];
			$order['city']=$retreat_addre['city'];
			$order['district']=$retreat_addre['district'];
			$order['address']=$retreat_addre['address'];
			$order['zipcode']=$retreat_addre['zipcode'];
			$order['tel']=$retreat_addre['mobile'];
			$og=M('order_goods')->field(pfix('goods_name').' AS goods_name,goods_sn,goods_number,goods_price,goods_attr')->where("rec_id IN ({$rec_ids})")->select();
			$ogamount=0;
			foreach($og as $k => $v){
				$ogamount+=$v['goods_price'] * $v['goods_number'];
				if($v['goods_attr']) $tempAttrs=json_decode($v['goods_attr'],true);
				else $tempAttrs=array();
				$tr.='<tr><td align="left" style="line-height:40px;">'.$v['goods_name'].'</d><td align="left" style="line-height:40px;">'.$v['goods_sn'].'</d>';
				foreach($tempAttrs as $attrv) {
					if($attrv[pfix('name')]=='尺码' || strtoupper($attrv[pfix('name')]=='SIZE')) {
						$sizeval=$attrv['value'][pfix('attr_value')]; 
						break;
					}
				}
				$tr.='<td align="left" style="line-height:40px;">'.$sizeval.'</td>';
				$tr.='<td align="left" style="line-height:40px;">'.$v['goods_number'].'</td>';
				$tr.='<td align="right" style="line-height:40px;">'.C('KTEC_H').$v['goods_price'].'</td>';
				$tr.='</tr>';	
				$sizeval='';
			}
			$ogamount=number_format($ogamount,2,'.','');
			$order['goods_amount']=$ogamount;
			$order['order_amount']=$ogamount;
			$member=M('member')->field('user_name,surname,full_name')->where("id = '{$this->userId}'")->find();
			$tpl='./Public/EmailTpl/MemberRefund/'.pfix('thsq').'.html';
			$contents=file_get_contents($tpl);
			$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
			//$contents=str_replace('{$str1}',gmstrftime(" %d %b, %X "),$contents);
			$contents=str_replace('{$str1}',date('Y/m/d',time()),$contents);
			$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name'].' '.$member['surname']),$contents);
			//订单号
			$contents=str_replace('{$order_sn}',$order['order_sn'],$contents);
			//订单日期(只显示英文日期)
			//$contents=str_replace('{$date}',format_date('Y-m-d',time(),'en-us'),$contents);
			$contents=str_replace('{$date}',date('Y/m/d',time()),$contents);
			//姓名
			$contents=str_replace('{$names}',$order['consignee'].' '.$addresss['surname'],$contents);
			//地址
			$contents=str_replace('{$address}',$order['address'],$contents);
			//国、省、市
			$ids=$order['coun_id'] .','.$order['province'].','.$order['city'].','.$order['district'];
			$region=M('region')->field("region_id,region_name")->where("region_id IN ({$ids})")->order("parent_id ASC")->select();	
			$temp=array();
			foreach($region as $k=>$v) $temp[$v['region_id']]=$v['region_name'];
			$order['country']=$temp[$retreat_addre['coun_id']] ? $temp[$retreat_addre['coun_id']] : $order['country_text'];
			$order['province']=$temp[$retreat_addre['province']] ? $temp[$retreat_addre['province']] : '';
			$order['city']=$temp[$retreat_addre['city']] ? $temp[$retreat_addre['city']] : '';
			$order['district']=$temp[$retreat_addre['district']] ? $temp[$retreat_addre['district']] : '';
			
			//p($region);die;
			//国家
			if($order['country']) $contents=str_replace('{$country}',$order['country'],$contents);
			else $contents=str_replace('{$country}','',$contents);	
			//省份
			if($order['province']) $contents=str_replace('{$province}',$order['province'],$contents);
			else $contents=str_replace('{$province}','',$contents);	
			//城市
			if($order['city']) $contents=str_replace('{$city}',$order['city'],$contents);
			else $contents=str_replace('{$city}','',$contents);		
			//区县
			if($order['district']) $contents=str_replace('{$district}',$order['district'],$contents);		
			else $contents=str_replace('{$district}','',$contents);		
			//电话
			$contents=str_replace('{$tle}',$order['tel'],$contents);
			//邮编
			$contents=str_replace('{$zip}',$order['zipcode'],$contents);
			//商品信息
			$contents=str_replace('{$goodsList}',$tr,$contents);
			//商品总金额
			$contents=str_replace('{$goods_amount}',$order['goods_amount'],$contents);		
			//总金额
			$contents=str_replace('{$orderAmount}',$order['order_amount'],$contents);
			send_mail2($member['user_name'],isL(L('WithdrawalGoods'),'上门提取退货'),$contents);
			//$result=send_mail($member['user_name'],isL(L('WithdrawalGoods'),'上门提取退货'),$contents,false);					  
		    return array('success'=>true,'url'=>sign_url(array('order_id'=>$order_id),U(is_m().'/Member/returnSuccess')));
	  }else{
		  return array('success'=>false,'msg'=>'error!','url'=>U(is_m().'/Index/index'));
	  }
   }
   
    /**申请退货结束*/	
	
}