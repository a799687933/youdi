<?php
// 结算收银台
 
class CheckOutAction extends CheckOutBasicAction {
	
    public function _initialize(){
		parent::_initialize();
    	//不允许未登录购物
    	if(!return_key()){
		      if(!is_login()) $this->myInfos(false,'submits',isL(L('PleaseLogIn'),'请先登录'),U('Login/index').'?url='.base64_encode(get_url()));
		}		
        $this->userId=getUserC('SE_USER_ID') ? getUserC('SE_USER_ID') :-1;
		$this->userKey=return_key();
		$this->model=new BasicCheckOutModel();
	}
	
	//1、选择收货地址
	public function selectAddress(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		if(!verify_req_sign($_GET,array(),-1)) $this->error('error!');
		$login=is_login();
		$ids=$_GET['ids'];
		$total=$_GET['total'];
		$userName=emptyHtml($_REQUEST['user_name']);
		if(!$login && empty($userName)){
			$url=sign_url(array('ids'=>$ids,'total'=>$total),U('Login/notLogin','',''),'');
			header("location:".$url);
			die();
		}
		//未登录用户强制填写注册资料
		if($userName){ 
			if(M('Member')->where(array('user_name'=>$userName))->getField('id')){
				$this->myInfos(false,'',isL(L("BeRegistered"),"此用户已被其他人注册"));
			}else{
				if(IS_AJAX){
					$url=sign_url(array('ids'=>$ids,'total'=>$total,'user_name'=>$userName),U('CheckOut/selectAddress','',''));
					$this->myInfos(true,'','',$url);
				}
			}
			$this->userName=$userName;
	        //省份列表
			$r=new RegionModel();
			$this->province=$r->getRegion(array('province'=>''));	
			$this->display('notLoginAddress');
			die;
		}
		//获取会员地址列表
		if($login) {
			$adderss=M('member_address')->where(array('user_id'=>$this->userId))->order("default_address DESC")->select();
		  /* $rids='';
		   foreach($adderss as $v){
			   $rids.=$v['coun_id'].','.$v['province'].','.$v['city'].','.$v['district'].',';
		   }
		   $rids=rtrim($rids,',');
		   $regin=M('region')->field("region_id,region_name")->where("region_id IN ({$rids})")->select();
		   $adrr=array();
		   foreach($regin as $k=>$v) $adrr[$v['region_id']]=$v['region_name'];
		   unset($regin);
		   foreach($adderss as $k=>$v){
			   $adderss[$k]['coun_id']=$adrr[$v['coun_id']];
			   $adderss[$k]['province']=$adrr[$v['province']];
			   $adderss[$k]['city']=$adrr[$v['city']];
			   $adderss[$k]['district']=$adrr[$v['district']];
			}	*/
			$this->adderss=$adderss;
	    }
	    $this->display();		
	}
	
	//2、选择支付方式
	public function selectPay(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		if(!is_login()) $this->error('error!');
		if(!verify_req_sign($_GET,array('selectAdderss'),-1)) $this->error('error!');
		$selectAdderss=$_REQUEST['selectAdderss'] > 0 ? $_REQUEST['selectAdderss'] : $this->error('error!');
		//是否存在该地址
		if(!M('member_address')->where(array('address_id'=>$selectAdderss,'user_id'=>$this->userId))->getField('address_id'))
		$this->error('error!');
		$this->selectAdderss=$selectAdderss;
		$this->display();
	}
	
	//3、检查结算祥细
	public function billingDetails(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
		if(!is_login()) $this->error('error!');
		if(!verify_req_sign($_GET,array('pay'),-1)) $this->error('error!');
		$pay=$_REQUEST['pay'] ? $_REQUEST['pay'] : $this->error('error!');
		$addressId=$_REQUEST['selectAdderss'] > 0 ? $_REQUEST['selectAdderss'] : $this->error();
		//允许支付方式
		$bids=array('alipay');
		if(!in_array($pay,$bids)) $this->error('error!');
		//获得发货地址
		$address=M('member_address')->where(array('user_id'=>$this->userId,'address_id'=>$addressId))->find();
	    /*$inid=$address['coun_id'].','.$address['province'].','.$address['city'].','.$address['district'];
		$region=M('region')->field('region_id,region_name')->where("region_id IN ({$inid})")->order("parent_id ASC")->select();	
		$address['coun_id']=$region[0]['region_name'];
		$address['province']=$region[1]['region_name'];
		$address['city']=$region[2]['region_name'];
		$address['district']=$region[3]['region_name'];*/
		$this->address=$address;
		//获得购物车数据
		$ids=rtrim($_GET['ids'],',');
		if(!$ids) $this->error('error!');
		$cart=new CartModel();	
		$result=$cart->cartList($ids);
		if(!$result['total']) header("location:".U('Index/index'));
		$this->result=$result;
		$this->display();
	}
	
	//结算页
    public function index(){
		yodi_sleep();//我操，客户要求我睡几秒再工作，PHP你的工作效率太高了
    	die('error!');
    	$notGet=array('ids');
    	if(!verify_req_sign($_GET,$notGet,86400)){
    		header('location:'.$_SERVER["HTTP_REFERER"]);
			exit();
    	} 
        $data=$this->basicIndex();
		if(empty($data['success'])) $this->error($data['msg']);
		$cd=$data['data'];
		$this->addressList=$cd['addressList'];//地址列表
		$this->region=$cd['region'];//添加地址选择区域
		$this->vat=$cd['vat']; //增值税发票资料
		$this->vatRegion=$cd['vatRegion'];//增值税发票地址选择区域
		$this->ordinary=$cd['ordinary']; //普通发票资料
		$this->cart=$cd['cartData']; //购物车数据
		$this->ids=$cd['ids']; //商品ID集合
		$this->userIntergal=$cd['userIntergal']; //会员消费积分
		$this->integralFormula=$cd['integralFormula']; //积分换算公式(1000积分可对换1元)
		//p($cd['cartData']);die;
	    $this->display();
    }
	
	//跟据城市ID动态获取物流信息
	public function showLogistics(){
	    $this->basicShowLogistics();
	}
	
	//提交订单页
	public function addOrder(){
		//nonlicet_url();
		if(!is_login()) $this->error('error!');
		$notGet=array('w');//不参加签名的参数
    	if(!verify_req_sign($_GET,$notGet,-1)){
    		header('location:'.$_SERVER["HTTP_REFERER"]);
			exit('Error!');
    	} 	
		$result=$this->basicAddOrder(); 
		if(empty($result['success'])) die($result['msg']);
		/*$payType=$result['pay_type'];
		$data=$result['data'];
		$parr['order_sn']=$data['order_sn'];
		$parr['goods_amount']=$data['goods_amount'];
		$parr['order_amount']=$data['order_amount'];
		$parr['order_time']=$data['order_time'];
		$url=get_up_url($parr,U('CheckOut/paySelect','',''),true);
		$form='<form action="'.$url.'" id="formid" name="formid" class="formid" method="post">';
		$form.='<input type="hidden" name="order_goods" value="'.$data['order_goods'].'">';
		$form.='<input type="hidden" name="consignee" value="'.$data['consignee'].'">';
		$form.='<input type="hidden" name="country" value="'.$data['country'].'">';
		$form.='<input type="hidden" name="province" value="'.$data['province'].'">';
		$form.='<input type="hidden" name="city" value="'.$data['city'].'">';
		$form.='<input type="hidden" name="district" value="'.$data['district'].'">';
		$form.='<input type="hidden" name="address" value="'.$data['address'].'">';
		$form.='<input type="hidden" name="zipcode" value="'.$data['zipcode'].'">';
		$form.='<input type="hidden" name="tel" value="'.$data['tel'].'">';
		$form.='<input type="hidden" name="mobile" value="'.$data['mobile'].'">';
		$form.='<input type="hidden" name="email" value="'.$data['email'].'">';
		$form.='<input type="submit" value="submit" style="display:none;">';
		$form.='</form>';
		$form.='<script>document.forms["formid"].submit();</script>';
		die($form);*/
	}
	
	//选择线下支付提示页
	public function belowLine(){
	   $this->basicBelowLine();
	}

	//选择在线支付提示页
	public function paySelect(){
    	if(!verify_req_sign($_GET,array(),86400)){
			exit('Error!');
    	} 			
		$result=$this->basicPaySelect();
		if(empty($result['success'])) $this->error($result['msg']);
		$this->data=$result['data']['orders'];
		$this->payment=$result['data']['payment'];
		$this->display();
	}

    //选择余额支付提示页
    public function balance(){
    	$this->basicBalance();
    }
	
	//选择积分对换
	public function integralTrade(){
		$this->basicBalance(); 		
	}		
		
	//检查现金卷是否有效
	public function chkRed($id=''){
        $this->basicChkRed();
	}
	
	//添加收货地址选择城市
	public function getCitys(){
		$this->model->searchRegion();
	}
}