<?php
// 结算
class MemberAction extends MemberBasicAction {
	
	//会员首页
    public function index(){
		$this->basicIndex();
		//p(year_arr());
		 $this->display();
    }
	
	//读取未读信息
	public function readMessge(){
	   $table=$_GET['table'];
	   $id=intval($_GET['id']);
	   $counts=intval($_GET['counts']);
	   $url=base64_decode($_GET['url']);
	   $remove=$_GET['remove'];
	   if($table == 'comment_reply'){//资讯回复
	       $result=M('comment_reply')->where("article_id='{$id}' and read_user_id='{$this->userId}'")->save(array('is_read'=>1));
	   }else if($table == 'goods_appraise_reply'){
	       $result=M('goods_appraise_reply')->where("goods_id='{$id}' and read_user_id='{$this->userId}'")->save(array('is_read'=>1));
	   }
	   if($result){
		   $this->assign('counts',$counts);
		   $this->assign('url',$url);		
		   $this->assign('remove',$remove);
	   }else{
		   $this->assign('counts',0);
		   $this->assign('url',$url);		
		   $this->assign('remove',$remove);
	   }
	   $this->display();
	}
	
	//我的订单列表
	public function orderList(){
		$this->basicOrderList();
		$this->display();
	}
	
	//订单祥情
	public function orderShow(){
		$this->basicOrderShow();
		$this->display();
	}
	
	//订单物流
	function orderLogistics(){
	     $order_sn=$_REQUEST['order_sn'];
		 $logistics_sn=$_REQUEST['logistics_sn'];
		 $shipping_code=$_REQUEST['shipping_code'];
		 $result=baiduKuaidi100($logistics_sn,$shipping_code);
		 $this->result=$result['data'];
		 $this->order_sn=$order_sn;
		 $this->logistics_sn=$logistics_sn;
		 $this->display();		
	}
	
	//余额支付
	public function balancePays(){
	    $this->basicBalancePays();
	}	
	
	//提交支付中转
	public function payment(){
        $this->basicPayment(is_m().'/Payment/index');
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
	  $this->basicCollectionList(10);
   	  $this->display();
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
	
	//评价页面或修改评价页面
	public function appraise(){
		if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
         $result=$this->basicAppraise();
		 if(!$result['success']) $this->myInfos(false,'','Operation timeout');
		 //买后感受
		 $this->GOODS_SATISFY=$result['data']['goods_sats'];
		 //订单商品
		 $this->order_goods=$result['data']['order_goods'];
		 //已评价过，修改
		 $this->goods_appraise=$result['data']['goods_appraise'];
		 //p($result['data']['goods_appraise']);die;
		 $this->display();
	}

	//评价表单处理
	public function appraiseForm(){
		if($_GET['is_success']){
			$this->display('appraiseSuccess');
			exit();
		}   
        if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
        $result=$this->basicAppraiseForm();
		$str='<script type="text/javascript">window.location.href="'.U('Member/appraiseForm',array('is_success'=>true)).'";</script>';
		exit($str);
        if($result['success']){
		   //$str="<img src=".__ROOT__."'/Public/Common/Images/icon102.png' style='position:relative;top:3px;margin-right:3px;' /><strong style='color:green;'>".$result['msg']."</strong>";
		   //$this->myInfos(true,$result['error_class'],$str,U('Member/index'));
	    }else{
		  // $str="<img src=".__ROOT__."'/Public/Common/Images/icon103.png' style='position:relative;top:3px;margin-right:3px;' /><strong style='color:red;'>".$result['msg']."</strong>";
		  // $this->myInfos(false,$result['error_class'],'','');		   
	    }
	}
	
	//商品等待评价列表
	public function waitAppraise(){
		$this->basicWaitAppraise(15,'');
	} 	
	

	
	//商品评价列表
	public function assessList(){
	    $this->basicAssessList(15);
	    $this->display();
	}
	
	//个人资料
	public function personal(){
        $this->basicPersonal();
	    $this->display();
	}
	
	//修改个人资料
	public function savePersonal(){
        $this->basicPersonalForm();
	}
	
	//修改密码
	public function changePWD(){		
	    $this->display();
	}
	
	//修改密码提交
	public function changePWDform(){
        $this->basicChangePWDform();
	}
	
	//收货地址列表
	public function addressList(){
		$this->basicAddressList();
		$this->display();
	}
	
	//設為默地址
	public function setDefault(){
        $this->basicSetDefault(); 
	}
	
	//添加修改地址界面
	public function address(){
		$this->basicAddress();
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
   
   //密码安全问题界面
   public function question(){
        $this->basicQuestion();
        $this->display();
   }
   
   //提交密码安全问题
   public function questionFrom(){
       $this->basicQuestionFrom(); 
   }
   
   //我的咨询列表
   public function wordsList(){
        $this->basicWordsList(20);
        $this->display();
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
   	   $this->display('Member/userAccount');   
   }
   
   //我的冻结资金
   public function frozen(){
	   $this->basicFrozen();
   	   $this->display('Member/userAccount');   
   }   
   
   // 等级积分清单
   public function rankPoints(){
	   $this->basicRankPoints();
   	   $this->display('Member/userAccount');
   }
   
   //消费积分 清单
   public function payPoints(){
	   $this->basicPayPoints();
   	   $this->display('Member/userAccount');
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
         $this->display();
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
       $this->basicIdentity();
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
   
   /**申请退货*/
   
   //申请退货选择商品
   public function returnGoods(){
	    if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
	    $result=$this->basicReturnGoods();
		if(!$result['success']) $this->myInfos(false,'','Error!',$_SERVER['HTTP_REFERER']);
		$this->order=$result['data']['order'];
		$this->goods_list=$result['data']['goods_list'];
		$this->return_cause=$result['data']['return_cause'];
		$this->retreat_data=$result['data']['retreat_data'];
		$goods_num=0;
		foreach($result['data']['retreat_data']['goods_data'] as $v) $goods_num+=$v['goods_num'];
		$this->goods_num=$goods_num;
	    $this->display();
   }
   
   //申请退货填写地址
   public function returnAddress(){
	    if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
        $data=$this->basicReturnAddress();
		if(!$data['goods_data'] || !$data['order']) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
		//包果个数
		$this->packages_num=$data['packages_num'];
		//退货数组
		$this->goods_data=base64_encode(json_encode($data['goods_data']));
		//订单信息
		$this->order=$data['order'];
		//省/市/区
		$this->region=$data['region'];
		//p(json_decode(base64_decode($data['goods_data']),true));die;
		//商品个数
		$goods_num=0;
		foreach($data['goods_data'] as $k=>$v)  $goods_num+=$v['goods_num'];
		$this->goods_num=$goods_num;
	    $this->display();
   } 
   
   //申请退确认页(终)
   public function returnConfirm(){
	    if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
		$data=$this->basicReturnConfirm();
		if(!$data['success']) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
		//需要提交的表单数据
		$this->form_data=$data['data']['form_data'];
		//订单信息
		$this->order=$data['data']['order'];
		//商品信息
		$this->goods_list=$data['data']['goods_list'];
		//显示地址信息
		$this->temp_addre=$data['data']['temp_addre'];
		//p($data['data']['temp_addre']);die;
		//包果个数
		$this->packages_num=$data['data']['packages_num'];
	    $this->display();
   }   
   
   //提交确认页表单
   public function returnConfirmForm(){
	   if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','Operation timeout',$_SERVER['HTTP_REFERER']);
        $result=$this->basicReturnConfirmForm();
		$this->myInfos($result['success'],'','',$result['url']);
   }
   
   //提交提示页
   public function returnSuccess(){
       $this->display();
   }
   
    /**申请退货结束*/
      
}