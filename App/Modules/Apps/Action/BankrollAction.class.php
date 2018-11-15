<?php
/*资金管理控制器*/
class BankrollAction extends CommonAction{
    
	//平台总帐户
	public function totalList(){
		$M=M('affairs_total');
        $order=$this->orderBy();//临时排序
		$where="1=1";
	   if($_REQUEST['times']) {
	       $times=strtotime($_REQUEST['times']);
		   $startTime=$times;
		   $endTime=$times + 86400;
		   $where.=" AND (times >= '{$startTime}' AND times <='{$endTime}') ";
	   }		
        $order=$order ? $order : 'times DESC';  		
		$this->addMoney=$M->sum('add_money'); //收入
		$this->subtractMoney=$M->sum('subtract_money'); //支出
		$counts=$M->where($where)->count();
		$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
		$this->result=$M->where($where)->order($order)->limit($GLOBALS['limit'])->select();
	    $this->display();
	}
	
	//调节平台总帐户界面
	public function adjust(){
		$M=M('affairs_total');
		$this->addMoney=$M->sum('add_money'); //收入
		$this->subtractMoney=$M->sum('subtract_money'); //支出		
        $this->display();
	}

	//调节平台总帐户表单处理
	public function adjustForm(){
		$this->chkTokenAjax();
        $money=is_numeric($_REQUEST['money']) && $_REQUEST['money'] > 0 ? $_REQUEST['money'] : return_json(300,'调节金额错误');
		$changeData=$_REQUEST['change_data'] ? emptyHtml($_REQUEST['change_data']) : return_json(300,'变动原因不可为空');
		$type=$_REQUEST['type'];
		if($type !=-1 && $type !=1) return_json(300,'非法操作');
		$comModel=new CommonModel();	 
		$comModel->startTrans(); //启动事务
		if($comModel->affairsTotal($type,5,$money,$changeData,$_SESSION[C('SESSION_NAME_VAL')])){
		     $comModel->commit();//成功则提交
			 return_json(200,'操作成功！','totalList','','closeCurrent');			
		}else{
		     $comModel->rollback();//不成功，则回滚
			 return_json(300,'操作失败！');		
		}
	}
	
	//总帐户收支清单
	public function totalExpenses(){
	   $M=M('affairs_change');	
	   $where="1=1";
	   
	   if($_REQUEST['id']) {
		   $where.=" AND affairs_total_id=".getNum($_REQUEST['id'])." ";
	   }
	   if($_REQUEST['datas']) {
	       $times=strtotime($_REQUEST['datas']);
		   if($times){
			   $times=$times;
			   $this->datas=$_REQUEST['datas'];
		   }else{
			   $times=$_REQUEST['datas'];
			   $this->datas=date('Y-m-d',$_REQUEST['datas']);
		   }
		   $startTime=$times;
		   $endTime=$times + 86400;
		   $where.=" AND (change_time >= '{$startTime}' AND change_time <='{$endTime}') ";
	   }	   
	   if($_REQUEST['type'] !=''){
		   $where.=" AND type='{$_REQUEST['type']}' ";
	   }
       $order=$this->orderBy();//临时排序
       $order=$order ? $order : 'change_time DESC';  		   
	   $counts=$M->where($where)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->result=$M->where($where)->order($order)->limit($GLOBALS['limit'])->select();
	   $this->display();
	}

	//服务商收支清单
	public function supplierExpenses(){
		$where='1=1';
	    if($_REQUEST['trade_type'] !=''){
			 $where.=" AND trade_type='{$_REQUEST['trade_type']}' ";
	   }	   
	   $id=getNum($_REQUEST['supplier_id']);
	   $where.=" AND goods_supplier_id='{$id}' ";
	   $suuplier=M('goods_supplier')->field('name,bankroll')->where(array('id'=>$id))->find();
	   if(!$suuplier)  return_json(300,'不存在此数据');
	   $this->suuplier=$suuplier;
       $order=$this->orderBy();//临时排序
       $order=$order ? $order : 'change_time DESC';  	 
	   $M=M('goods_supplier_account');
	   $counts=$M->where($where)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->result=$M->where($where)->order($order)->select();
	   $this->display();
	}
		
    //商户提现申请列表
    public function supplierApplyList(){
        $order=$this->orderBy('sw');//临时排序，返回数组
        $order=$order ? $order : 'sw.times DESC'; 		
		$where='';
		if($_REQUEST['supplier_id']) $where.=" AND sw.goods_supplier_id='{$_REQUEST['supplier_id']}' ";
		if($_REQUEST['is_deal'] !='') $where.=" AND sw.is_deal='{$_REQUEST['is_deal']}' ";
		if($_REQUEST['datas']){
			$times=strtotime($_REQUEST['datas']);
			if($times){
				$this->datas=$_REQUEST['datas'];
				$emdTime=$times + 86400;
				$where.=" AND (sw.times >='{$times}' AND sw.times < '{$emdTime}') ";			
			}
		}	
		$M=M('shops_withdraw');
		$countSql="SELECT COUNT(sw.id) AS counts FROM ".PREFIX."goods_supplier_withdraw AS sw WHERE 1=1 ".$where;
        $counts=$M->query($countSql);
		$this->callAjaxPage(C('BGPAGENUM'),$counts[0]['counts']);//分页
		$sql="SELECT sw.*,su.id AS supplier_id,su.name AS supplier_name,su.bankroll,sb.bank_type,sb.bank_name,sb.bank_sn,";
		$sql.="sb.bank_child,sb.user_name FROM ".PREFIX."goods_supplier_withdraw AS sw JOIN ".PREFIX."goods_supplier AS su ";
		$sql.="ON (su.id=sw.goods_supplier_id) JOIN ".PREFIX."goods_supplier_bank AS sb ON (sw.shops_bank_id=sb.id) WHERE 1=1 ".$where;
		$sql.=" ORDER BY ".$order." LIMIT ".$GLOBALS['limit'];
		$this->resut=$M->query($sql);
        $this->display();
    }
	
	//商户提现转帐记录界面
	public function transferRecord(){
	    $result=M('goods_supplier_withdraw')->where(array('id'=>getNum($_GET['id'])))->find();
		if($result['is_deal']==0){
			unset($result['bank_sn']);
			unset($result['fee']);
			unset($result['reason']);
			unset($result['real_account']);
		}
		$this->result=$result;
	    $this->display();
	}
	
	//商户提现转帐记录表单
	public function transferRecordForm(){
        $times=time();
	    $id=getNum($_REQUEST['id']);
		$comModel=new CommonModel();	
		$sql="SELECT sw.*,sb.bank_type,sb.bank_name,sb.bank_sn,sb.bank_child,sb.user_name FROM ".PREFIX."goods_supplier_withdraw AS sw ";
		$sql.="JOIN ".PREFIX."goods_supplier_bank AS sb ON(sw.shops_bank_id=sb.id) WHERE sw.id='{$id}' AND sw.is_deal=0 LIMIT 1";
		$sw=$comModel->query($sql);
		$sw=$sw[0];
		if(!$sw) return_json(300,'不存在此数据');
		$businessSn=is_abc_num($_REQUEST['business_sn']) ? $_REQUEST['business_sn'] : return_json(300,'银行交易号格式错误');
		$totalPrice=is_numeric($_REQUEST['total_price']) && $_REQUEST['total_price'] > 0 ? $_REQUEST['total_price'] : return_json(300,'转帐总金额错误');
		if($totalPrice !=$sw['money']) return_json(300,'你不能改变商户提现的金额');
		$fee=is_numeric($_REQUEST['fee']) && $_REQUEST['fee'] >= 0 ? $_REQUEST['fee'] : return_json(300,'手续费金额错误');
		$changeDesc=$_REQUEST['reason'] ? emptyHtml($_REQUEST['reason']) : return_json(300,'转帐原因不可为空');
		$tempCentum=round($fee / $totalPrice,2) * 100; //百分比例
		$centum=$tempCentum;
		$actualPrice=$totalPrice - $fee; //实际金额	
		//修改已处理数据
		$withdraw['is_deal']=1;
		$withdraw['bank_sn']=$businessSn;
		$withdraw['fee']=$fee;
		$withdraw['real_account']=$actualPrice;
		$withdraw['reason']=$changeDesc;
		$withdraw['update_time']=$times;
		//商户进出清单表数据
	    $data['goods_supplier_id']=$sw['goods_supplier_id'];
	    $data['trade_type']=-1;
	    $data['total_price']=$totalPrice;
	    $data['centum']=$centum;
	    $data['fee']=$fee;
	    $data['actual_price']=$actualPrice;
	    $data['change_desc']=$changeDesc;
	    $data['change_time']=$times;	
	    //启动事务
		$comModel->startTrans();
		$states=true;	
		//修改已处理请求
		if(!M('goods_supplier_withdraw')->where(array('id'=>$id))->save($withdraw)) $states=false;	
		//写入商户清单
	    if(!M('goods_supplier_account')->add($data)) $states=false;	
		//减少商户资金
		if(!M('goods_supplier')->where(array('id'=>$sw['goods_supplier_id']))->setDec('bankroll',$totalPrice)) $states=false;	
		//操作平台帐户
		if(!$comModel->affairsTotal(-1,2,$totalPrice,$changeDesc,$_SESSION[C('SESSION_NAME_VAL')],$times)) $states=false;
		if($states){
		     $comModel->commit();//成功则提交
			 return_json(200,'操作成功！','supplierApplyList','','closeCurrent');			
		}else{
		     $comModel->rollback();//不成功，则回滚
			 return_json(300,'操作失败！');			
		}

	}
	
	//删除商户提现申请
	public function applyDelete(){
	    $where=array('id'=>array('in',$_REQUEST['ids']),'is_deal'=>1);
		if(M('goods_supplier_withdraw')->where($where)->delete()){
			$url=U(APP_APPS.'/Bankroll/supplierApplyList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
			return_json(200,'操作成功！','','','forward',$url);   
		}else{
			return_json(300,'操作失败！');   
		}
	}
	
	//会员提现申请表
	public function memberApplyList(){
		$db=M('member_extraction');
		if($_REQUEST['is_deal']==1){
			$cw=array('update_time'=>array('gt',0));
			$sw=" AND me.update_time > 0 ";
		}else if($_REQUEST['is_deal']==-1){
			$cw=array('update_time'=>array('eq',0));
			$sw=" AND me.update_time = 0 ";			
		}
		if($_REQUEST['datas']){
			$times=strtotime($_REQUEST['datas']);
			if($times){
				$this->datas=$_REQUEST['datas'];
				$emdTime=$times + 86400;
				$cw['add_time']=array(array('egt',$times),array('elt',$emdTime),'and'); 
				$sw=" AND me.add_time >='{$times}' AND me.add_time <'{$emdTime}'  ";			
			}
		}
		$counts=$db->where($cw)->count();
		$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
		$sql="SELECT me.*,m.id AS m_id,m.user_name AS m_name,m.member_funds FROM ".PREFIX."member_extraction AS me JOIN ";
		$sql.=PREFIX."member AS m ON(me.member_id=m.id) WHERE 1=1 {$sw} ORDER BY me.add_time DESC LIMIT ".$GLOBALS['limit'];
		$this->result=$db->query($sql);
	    $this->display();
	}

	//处理会员提现界面
	public function memberApply(){
		$id=getNum($_GET['id']);
		$sql="SELECT me.*,m.id AS m_id,m.user_name AS m_name,m.member_funds FROM ".PREFIX."member_extraction AS me JOIN ";
		$sql.=PREFIX."member AS m ON(me.member_id=m.id) WHERE me.id='{$id}' LIMIT 1";	
		$result=M()->query($sql);
		$this->result=$result[0];
	    $this->display();
	}
	
	//处理会员提现表单处理
	public function memberApplyListForm(){
		$id=getNum($_POST['id']);
		$comModel=new CommonModel();	
		$sql="SELECT me.id,me.member_id,me.money,m.member_funds FROM ".PREFIX."member_extraction AS me JOIN ";
		$sql.=PREFIX."member AS m ON(me.member_id=m.id) WHERE me.id='{$id}' LIMIT 1";	
		$result=$comModel->query($sql);	 
		$result=$result[0];
		if(!$result) return_json(300,'不存在此数据');
		$bankSn=is_abc_num($_POST['bank_sn']) ? $_POST['bank_sn'] : return_json(300,'银行交易号格式错误');
		$counterFee=$_POST['counter_fee'] > 0 ? $_POST['counter_fee'] : 0;
		$realMoney=$result['money'] - $counterFee;
		$explain=$_POST['explain'] ? emptyHtml($_POST['explain']) : return_json(300,'转帐说明不可空');
		$times=time();
	    //启动事务
		$comModel->startTrans();
		$states=true;	
		//处理申请表
		$mex['counter_fee']=$counterFee;
		$mex['real_money']=$realMoney;
		$mex['bank_sn']=$bankSn;
		$mex['explain']=$explain;
		$mex['update_time']=$times;
		if(!M('member_extraction')->where(array('id'=>$id))->save($mex)) $states=false;
		//减少会员帐户
		if(!M('member')->where(array('id'=>$result['member_id']))->setDec('member_funds',$result['money'])) $states=false;
		//记录会员帐户清单
		$account['member_id']=$result['member_id'];
		$account['transaction']=0;
		$account['return_mark']=0;
		$account['pay_code']='';
		$account['member_money']=-$result['money'];
		$account['frozen_money']=0;
		$account['rank_points']=0;
		$account['pay_points']=0;
		$account['change_time']=$times;
		$account['change_desc']=$explain;
		$account['hange_type']=2;
		if(!M('member_account')->add($account)) $states=false;
		//操作平台帐户
		if(!$comModel->affairsTotal(-1,3,$result['money'],$explain,$_SESSION[C('SESSION_NAME_VAL')],$times)) $states=false;
		if($states){
		     $comModel->commit();//成功则提交
			 return_json(200,'操作成功！','memberApplyList','','closeCurrent');			
		}else{
		     $comModel->rollback();//不成功，则回滚
			 return_json(300,'操作失败！');			
		}		
		
	}
	
	//删除会员提现申请
	public function deleteMemberApplyList(){
	
	}

}
?>