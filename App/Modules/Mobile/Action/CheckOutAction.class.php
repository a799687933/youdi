<?php
// 结算收银台
 
class CheckOutAction extends CheckOutBasicAction {

	//结算页
    public function index(){
        $this->basicIndex();
	    $this->display();
    }
	
	//跟据城市ID动态获取物流信息
	public function showLogistics(){
	    $this->basicShowLogistics();
	}
	
	//提交订单页
	public function addOrder(){
		$this->basicAddOrder();
	}
	
	//选择线下支付提示页
	public function belowLine(){
	   $this->basicBelowLine();
	}

	//选择在线支付提示页
	public function paySelect(){
		$this->basicPaySelect();
	}

    //选择余额支付提示页
    public function balance(){
    	$this->basicBalance();
    }
		
	//检查现金卷是否有效
	public function chkRed($id=''){
        $this->basicChkRed();
	}
}