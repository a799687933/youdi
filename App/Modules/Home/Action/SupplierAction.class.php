<?php
/*供应商控制器*/
class SupplierAction extends SupplierBasicAction {
    
	//供应商首页 
	public function index(){
		 $this->basicIndex();
	}
	
	//订单列表
	public function orderList(){
		$this->basicOrderList();
	}
	
	//订单祥情
	public function orderShow(){
		$this->basicOrderShow();
	}
	
	//设置配送状态
	public function setOrder(){
		$this->basicSetOrder();
	}
	
	//取消订单
	public function cancelOrder(){
		$this->basicCancelOrder();
	}

	//同意退款设置
	public function refund(){
		$this->basicRefund();
	}
	
	//银行帐户
	public function  supplierBankList(){
		$this->basicSupplierBankList();
	}

	//添加修改银行帐户表单处理
	public function addSaveBank(){		
	    $this->basicAddSaveBank();
	}
	
	//添加银行帐户表单处理
	public function addSaveForm(){
	    $this->basicAddSaveForm();
	}	
	
	//删除银行帐户
	public function deleteBank(){
	    $this->basicDeleteBank();
	}	
	
	//我的帐户
	public function supplierAccount(){
	    $this->basicSupplierAccount();
	}	
	
	//提现申请列表
	public function withdraw(){
	    $this->basicWithdraw();
	}	
	
	//提现申请
	public function withdrawApply(){
	    $this->basicWithdrawApply();
	}	
	
	//提现申请表单处理
	public function withdrawApplyForm(){
        $this->basicWithdrawApplyForm();
	}	
	
    //修改密码界面
    public function changePassword(){
    	$this->basicChangePassword();
    }

    //修改密码表单处理
    public function changePasswordForm(){
    	$this->basicChangePasswordForm();
    }
		
    //供应商登录页
    public function login(){
		 $this->basicLogin();
    }
	
	//供应商登录表单处理
    public function loginDo(){
		 $this->basicLoginDo();
    }	
	
	//供应商退出登录
	public function exitUser(){
	    $this->basicExitUser();
	}
	
	//供应商注册页
	public function register(){
	    $this->basicRegister();
	}
	
	//供应商注册表单处理
	public function registerDo(){
	    $this->basicRegisterDo();
	}
	
	//检索城市
	public function searchRegion(){
		$model=new CommonModel();
		return $model->searchRegion();		
	}

	//显示验证码
	public function showCode(){
		$this->basicVerify(40,26);
	}	
	
	//验证验证码是否正确
	public function checkCode(){
		$this->basicAjaxYzm();
	}	
	 
}