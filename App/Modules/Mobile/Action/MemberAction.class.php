<?php
// 结算
class MemberAction extends MemberBasicAction {
	
	//会员首页
    public function index(){
		 $this->basicIndex();
		 $this->title=isL(L('UserCenter'),'用户中心');
		 $this->display();		 
    }
	
	//我的订单列表
	public function orderList(){
		$this->basicOrderList(12);
		if($_GET['order_type']==1){
			$this->title=isL(L('ReturnORefund'),'退货/退款');
		}else{
			$this->title=isL(L('OrderList'),'订单列表');
		}
		
		 if(IS_AJAX){
			 $this->display('Piece/Member_orderList');
	     }else{
			 $this->display();
		 }
	}
	
	//订单祥情
	public function orderShow(){
		$this->basicOrderShow();
		$this->title=isL(L('OrderDetails'),'订单详情');
		$this->display();
	}
	
	//余额支付
	public function balancePays(){
	    $this->basicBalancePays();
	}	
	
	//选择支付页面
	public function selectPay(){
		$where=array(
					 'order_status'=>array('in','0,1'),
					 'pay_status'=>array('lt',2),
					 'shipping_status'=>array('lt',2),
					 'order_id'=>$_GET['order_id'],
					 'user_id'=>$this->userId
					 );
		$orders=M('order_info')->field('order_id,order_sn,goods_amount,order_amount,add_time')->where($where)->find();
		if(!$orders) $this->error('不存在此数据');
		$payment=M('payment')->where(array('enabled'=>1,'is_show'=>1))->order('pay_id ASC')->select();
        $this->orders=$orders;
		$this->payment=$payment;
		$this->title=isL(L('OnlinePayment'),'在线支付');
	    $this->display();
	}	
	
	//提交支付中转
	public function payment(){
        $this->basicPayment(is_m().'/MobilePay/index');
	}
	
	//取消订单
	public function cancelOrder(){
       $this->basicCancelOrder();
	}
	
	//确认交易
	public function comDeal(){
        $this->basicComDeal();
	}	
	
	//退货退款管理表单处理
	public function refund(){
        $this->basicRefund();
	}
	
   //我收藏商品列表
   public function collectionList(){
	  $this->basicCollectionList(12);
	  $this->title=isL(L('MyFavorites'),'我的收藏');
	  if(IS_AJAX){
		  $this->display('Piece/Member_collectionList');
	  }else{
		  $this->display();
	  }
   	  
   }	
   
   //删除收藏商品
   public function delCollection(){
       $this->basicDelCollection();
   }

    //我的浏览器历史
    public function history(){
    	$this->basicHistory();
    }

    //删除浏览器历史
    public function deleteHistory(){
    	$this->basicDeleteHistory();
    }	
	
	//评价页面或修改评价页面可用AJAX
	public function appraise(){
         $this->basicAppraise();
	}
	
	//评价表单处理
	public function appraiseForm(){
         $this->basicAppraiseForm();
	}
	
	//商品评价列表
	public function assessList(){
	    $this->basicAssessList(5);
		$this->title=isL(L('MyEvaluation'),'我的评价');
		if(IS_AJAX){
	        $this->display('Piece/Member_assessList');
		}else{
		    $this->display();
		}
	    
	}
	
	//个人资料
	public function personal(){
        $this->basicPersonal();
		$this->title=isL(L('PersonalInformation'),'个人信息');
	    $this->display();
	}
	
	//修改个人资料
	public function savePersonal(){
        $this->basicPersonalForm();
	}
	
	//修改密码
	public function changePWD(){		
	    $this->title=isL(L('ChangePassword'),'修改密码');
	    $this->display();
	}
	
	//修改密码提交
	public function changePWDform(){
        $this->basicChangePWDform();
	}
	
	//收货地址列表
	public function addressList(){
		$this->basicAddressList();
		$this->title=isL(L('AddressList'),'收货地址');
		$this->display();
	}
	
	//設為默地址
	public function setDefault(){
        $this->basicSetDefault(); 
	}
	
	//添加修改地址界面
	public function address(){
		$this->basicAddress();
		$this->title=isL(L('AddModifyAddress'),'添加/修改地址');
		$this->display();
	}
	
	//添加修改地址表单处理
   public function addressForm(){
        $this->basicAddressForm();
   }
   
   //删除地址
   public function delAddress(){
         $this->basicDelAddress();
   }
   
	//AJAX检索区域
	public function searchRegion(){
		$model=new CommonModel();
		return $model->searchRegion();
	}   
   
   //密码安全问题
   public function question(){
	    $this->basicQuestion(); //问题列表
        $this->display();
   }
   
   //提交密码安全问题
   public function questionFrom(){
       $this->basicQuestionFrom(); 
   }
   
   //我的咨询列表
   public function wordsList(){
        $this->basicWordsList(5);
		$this->title=isL(L('MyMessage'),'我的留言');
		if(IS_AJAX){
		   $this->display('Piece/Member_wordsList');
		}else{
		   $this->display();
		}
        
   }
   
   //查看咨询回复
   public function wordsContent(){
      $this->basicWordsContent(); 
   }
   
   //提交咨询
   public function wordsFrom(){
       $this->basicWordsFrom();
   }
   
   //删除我的咨询
   public function delWords(){
       $this->basicDelWords();
   }
   
   //我的帐户余额
   public function capital(){
	   $this->basicCapital(20);
	   $this->title=isL(L('AccountBalance'),'账户余额');
   	   $this->display('Member/userAccount');   
   }
   
   //我的冻结资金
   public function frozen(){
	   $this->basicFrozen();
	   $this->title=isL(L('MyFrozenFunds'),'我的冻结资金');
   	   $this->display('Member/userAccount');   
   }   
   
   // 等级积分清单
   public function rankPoints(){
	   $this->basicRankPoints();
	   $this->title=isL(L('LevelPoints'),'等级积分');
   	   $this->display('Member/userAccount');
   }
   
   //消费积分 清单
   public function payPoints(){
	   $this->basicPayPoints(5);
	   $this->title=isL(L('PointsConsumption'),'消费积分');
	   if(IS_AJAX){
		  $this->display('Piece/Member_payPoints');
	   }else{
	      $this->display('Member/userAccount');
	   }
   	   
   }   
   
   //帐户在线充值界面
   public function accountCZ(){
	   $this->payment=M('payment')->where("enabled=1 AND is_show=1")->order("pay_id ASC")->select();
	   $this->display();
   }
   
   //在线充值表单处理
   public function accountCZfrom(){
       $this->basicAccountCZfrom(); 
   }
   
	//提现申请列表
	public function extractionList(){
	   $this->basicExtractionList(); 
	}

	//提现申请界面
	public function extraction(){
	   $this->basicExtraction(); 
	}
	
	//提现申请表单处理
	public function extractionForm(){
	    $this->basicExtractionForm(); 
	}	
	   
   //站内信息列表
   public function infoList(){
		 $this->instationInfo=$this->basicInfoList(15);	   
		 if(IS_AJAX){
		 	$this->display('Piece/Member_infoList');
		 }else{
		 	$this->display();
		 }         
   }
   
   //删除站内信息
   public function deleteInfo(){
      $this->basicDeleteInfo();
   }

   //普通发票资料界
   public function puToug(){
        $this->basicPuToug();
        $this->display();
   }
   
   //普通发票表单处理
   public function puTougForm(){
       $this->basicPuTougForm();
   }
   
   //增值税专用发票界面
   public function zengZhi(){
       $this->basicZengZhi(); 
       $this->display();
   }
   
   //增值税专用发票界面表单处理
   public function zengZhiForm(){
       $this->basicZengZhiForm();
   }   
   
   //现金卷列表
   public function redList(){
        $this->basicRedList(10);
		$this->display();
   }
   
   //添加线下红包
   public function redAdd(){
       $this->basicRedAdd(); 
   }   
   
   //身份列表
   public function identity(){
	   $this->title=isL(L('IDmanagement'),'身份证管理');
       $this->basicIdentity();
   }
   
   public function identityAddSave(){
       $this->title=isL(L('AddModifyIdentity'),'添加/修改身份证');
	   $this->basicIdentityAddSave();
	   $this->display();
   }
   
   //添加身份证表单处理
   public function identityForm(){
        $this->basicIdentityForm();
   }
   
   //删除身份证
   public function identityDelete(){
       $this->basicIdentityDelete();
  }

   //检测用户是否有填写过发票资料
   public function isInv(){
      $this->basicIsInv();
   }
      
}