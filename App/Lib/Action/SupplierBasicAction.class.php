<?php
/*供应商公共控制器*/
class SupplierBasicAction extends BasicAction {
	 private $supplierId;//供应商ID
	 private $model;
	 protected function _initialize(){
	 	parent::_initialize();
	 	if(is_supplier_login()){	 		
	 		$this->supplierId=getUserC('SUPPLIER_ID');	
			$this->model=new SupplierModel($this->supplierId);			
	 	}
	 }
	
	//供应商首页 
	protected function basicIndex(){
		 $this->isLogin();//登录才可见
		 $this->orderStart=isL(L('ORDER_START'),C('ORDER_START')); //订单状态数组
		 $this->shippingStart=isL(L('ORDER_SHIPPING'),C('ORDER_SHIPPING')); //配送状态
		 $this->payStart=isL(L('ORDER_PAY'),C('ORDER_PAY'));//支付状态	
		 $this->payType=isL(L('PAYMENT_METHOD'),C('PAYMENT_METHOD'));//支付方式		 
		 $this->result=$this->model->index();
		 $this->display();
	}
	
	//订单列表
	protected function basicOrderList(){
		 $this->isLogin();//登录才可见
		 $this->orderStart=isL(L('ORDER_START'),C('ORDER_START')); //订单状态数组
		 $this->shippingStart=isL(L('ORDER_SHIPPING'),C('ORDER_SHIPPING')); //配送状态
		 $this->payStart=isL(L('ORDER_PAY'),C('ORDER_PAY'));//支付状态	
		 $this->payType=isL(L('PAYMENT_METHOD'),C('PAYMENT_METHOD'));//支付方式			 
		 $this->result=$this->model->orderList($this,20);
		 $this->display();
	}
	
	//订单祥情
	protected function basicOrderShow(){
		$this->isLogin();//登录才可见
		$this->model->orderShow($this);
		$this->display();
	}
	
	//设置配送状态
	protected function basicSetOrder(){
		$this->model->setOrder();
	}	
	
	//取消订单
	protected function basicCancelOrder(){
		$this->isLogin();//登录才可见
		$this->model->cancelOrder();
		$this->display();
	}	
	
	//同意退款设置
	protected function basicRefund(){
	    $this->isLogin();//登录才可见
		$this->model->refund();
	}
	
	//银行帐户
	protected function  basicSupplierBankList(){
		$this->isLogin();//登录才可见
	    $this->result=M('goods_supplier_bank')->where(array('supplier'=>$this->supplierId))->order("add_time DESC")->select(); 
	    $this->display();	
	}
	
	//添加修改银行帐户
	protected function basicAddSaveBank(){
		$this->isLogin();//登录才可见
		$id=getNum($_GET['id']);
		if($id){
			$this->result=M('goods_supplier_bank')->where(array('supplier_id'=>$this->supplierId,'id'=>$id))->find(); 
		}		
	    $this->display();
	}
	
	//添加银行帐户表单处理
	protected function basicAddSaveForm(){
		 $this->isLogin();//登录才可见
	     $this->hckToken($_REQUEST);
		 $data['supplier_id']=$this->supplierId;
		 $data['bank_type']=$_REQUEST['bank_type'];
		 $data['bank_name']=$_REQUEST['bank_name'] ? emptyHtml($_REQUEST['bank_name']) : val_json('bank_name',false,'开户银行不可空','','');
		 $data['bank_sn']=is_abc_num($_REQUEST['bank_sn']) ? $_REQUEST['bank_sn'] : val_json('bank_sn',false,'银行帐号格式错误','','');
		 $data['user_name']=$_REQUEST['user_name'] ? emptyHtml($_REQUEST['user_name']) : val_json('user_name',false,'帐户名称不可空','','');
		 $data['bank_child']=$_REQUEST['bank_child'] ? emptyHtml($_REQUEST['bank_child']) : '';
		 if($_REQUEST['id']){
		    $sw="(bank_name='{$data['bank_name']}' AND bank_sn='{$data['bank_sn']}') ADN (supplier_id<>'{$this->supplierId}')";
			if(M('goods_supplier_bank')->field('id')->where($sw)->find()){
			   val_json('submits',false,'银行帐号重复，请检查后提交','','');		 
			}		 
		    M('goods_supplier_bank')->where(array('id'=>getNum($_REQUEST['id']),'supplier_id'=>$this->supplierId))->save($data);
			val_json('submits',true,'操作成功',U(is_m().'/Supplier/supplierBankList'),'');
		 }else{
		    $data['add_time']=time();
			$counts=M('goods_supplier_bank')->where(array('supplier_id'=>$this->supplierId))->count();
			if($counts >=6) val_json('submits',false,'银行帐号最多可添加6个','','');	
			$sw="(bank_name='{$data['bank_name']}' AND bank_sn='{$data['bank_sn']}') ADN (supplier_id='{$this->supplierId}')";
			if(M('goods_supplier_bank')->field('id')->where($sw)->find()){
			   val_json('submits',false,'银行帐号重复，请检查后提交','','');		 
			}
		    M('goods_supplier_bank')->add($data);
			val_json('submits',true,'操作成功',U(is_m().'/Supplier/supplierBankList'),'');		 
		 }	
	}
	
	//删除银行帐户
	protected function basicDeleteBank(){
		$this->isLogin();//登录才可见
	    if(M('goods_supplier_bank')->where(array('id'=>getNum($_REQUEST['id']),'supplier_id'=>$this->supplierId))->delete()){
			val_json('submits',true,'删除成功',U(is_m().'/Supplier/supplierBankList'),'');		
		}else{
		   val_json('submits',false,'删除失败','','');		
		}	
	}
	
	//我的帐户
	protected function basicSupplierAccount(){
	   $this->isLogin();//登录才可见
	   $this->supplier=M('goods_supplier')->where(array('id'=>$this->supplierId))->getField('bankroll');
	   $M=M('goods_supplier_account');
	   $where['supplier_id']=$this->supplierId;
	   if($_GET['trade_type']=='1'){
		   $where['trade_type']=1;
	   }else if($_GET['trade_type']=='-1'){
		   $where['trade_type']=-1;
	   }
	   $counts=$M->where($where)->count();
	   $this->doPage($counts,20);
	   $this->result=$M->where($where)->order("change_time DESC")->limit($GLOBALS['limit'])->select();
	   $this->display();
	}
	
	//提现申请列表
	protected function basicWithdraw(){
	   $this->isLogin();//登录才可见
	   $this->supplier=M('goods_supplier')->where(array('id'=>$this->supplierId))->getField('bankroll');
       $M=M('goods_supplier_withdraw');
	   $where['goods_supplier_id']=$this->supplierId;
	   $counts=$M->where($where)->count();
	   $this->doPage($counts,20);
	   $sql="SELECT w.*,sb.bank_type,sb.bank_name,sb.bank_sn,sb.bank_child,sb.user_name FROM ".PREFIX."goods_supplier_withdraw AS w ";	 
	   $sql.="JOIN ".PREFIX."goods_supplier_bank AS sb ON(w.shops_bank_id=sb.id) WHERE w.goods_supplier_id='{$this->supplierId}' ";
	   $sql.="ORDER BY w.times DESC LIMIT ".$GLOBALS['limit'];
	   $this->result=$M->query($sql);
	   $this->display();
	}
	
	//提现申请
	protected function basicWithdrawApply(){
	    $this->isLogin();//登录才可见
	    $this->supplier=M('goods_supplier')->where(array('id'=>$this->supplierId))->getField('bankroll');		
		$this->bank=M('goods_supplier_bank')->where(array('goods_supplier_id'=>$this->supplierId))->select();
	    $this->display();
	}

	//提现申请表单处理
	protected function basicWithdrawApplyForm(){
	    $this->isLogin();//登录才可见
		//是否在已提交未处理的申请
		$swh=array('is_deal'=>0,'goods_supplier_id'=>$this->supplierId);
		if(M('goods_supplier_withdraw')->where($swh)->find()) val_json('submits',false,'你已申请过提现，正在处理中...','','');
		$shopsBankId=$_REQUEST['shops_bank_id'] ? getNum($_REQUEST['shops_bank_id']) : val_json('submits',false,'非法提交','','');
		$number=is_numeric($_REQUEST['number']) && $_REQUEST['number'] > 0 ? $_REQUEST['number'] : val_json('submits',false,'提现金额输入错误','','');
	    $supplier=M('goods_supplier')->where(array('id'=>$this->supplierId))->getField('bankroll');	
		if($number > $supplier)  val_json('submits',false,'余额不满足，你的当前余额￥'.$supplier,'','');
		$data['goods_supplier_id']=$this->supplierId;
		$data['shops_bank_id']=$shopsBankId;
		$data['money']=$number;
		$data['is_deal']=0;
		$data['bank_sn']='';
		$data['fee']=0;
		$data['real_account']=0;
		$data['reason']='';
		$data['times']=time();
		if(M('goods_supplier_withdraw')->add($data)){
			val_json('submits',true,'申请成功',U(is_m().'/Supplier/withdraw'),'');	
		}else{
			val_json('submits',false,'申请失败','','');	
		}		
	}
	
    //修改密码界面
    protected function basicChangePassword(){
    	$this->isLogin();//登录才可见
    	$this->display();
    }

    //修改密码表单处理
    protected function basicChangePasswordForm(){
    	$this->isLogin();//登录才可见
 		$wornPWD=emptyHtml($_REQUEST['wornPWD']);
        $password=emptyHtml($_REQUEST['password']);
		$notPassword=emptyHtml($_REQUEST['notPassword']);
		if(empty($wornPWD)) val_json("wornPWD",false,isL(L('OriginalPWDnot'),"原密码不能为空"),'','','');
		if(empty($password)) val_json("password",false,isL(L('NewPWDnot'),"新密码不能为空"),'','','');
		if($password !=$notPassword) val_json("notPassword",false,isL(L('TwoInputConsistent'),"两次安密码输入不一致"),'','','');
		$userArr=pwd_sha1($password);//处理密码和加密随机码
		$data['password']=$userArr['SHA1'];
		$data['sha1_random']=$userArr['RANDOM'];	
		$data['id']=$this->supplierId;   	
		$supplier=M('goods_supplier');
		$field='id,login_name,password,sha1_random';
		$result=$supplier->where(array('id'=>$data['id']))->find();
        if(!$result || !compare_sha1($wornPWD,$result['sha1_random'],$result['password'])){
				val_json("wornPWD",false,isL(L('OriginalPWDerror'),"原密码错误"),'','','');
        }else{
               if($supplier->save($data)){
					 val_json("submits",true,isL(L('ModifySuccess'),"修改成功"),U(is_m().'/Supplier/index'),'','');
			   }else{
					val_json("submits",false,isL(L('ModifyFailure'),"修改失败"),'','','');
			   }
         }			
    }
		
    //供应商登录页
    protected function basicLogin(){
	     $this->locations();//登录后不可见
		 $this->display();
    }
	
	//供应商登录表单处理
    protected function basicLoginDo(){
    	$this->locations();//登录后不可见
		 nonlicet_url();
         $this->hckToken($_REQUEST);       
		 if(md5($_REQUEST['code']) !=$_SESSION['verify']) $this->myInfos(false,'code',isL(L('VerificationCodeError'),'验证码错误'),'','','');
		 $name=$_REQUEST['name'] ? emptyHtml($_REQUEST['name']) : $this->myInfos(false, 'name', '登录帐号不可空');
		 $password=$_REQUEST['password'] ? emptyHtml($_REQUEST['password']) : $this->myInfos(false, 'password', '登录密码不可空');
		 $supplier=M('goods_supplier');
		 $field='id,name,login_name,password,sha1_random,is_vetted,temp_login_count,temp_login_time,addtime,is_lock,login_count,last_time';
		 $result=$supplier->field($field)->where(array('login_name'=>$name))->find();
		 if($result){
		 	if($result['is_lock']) $this->myInfos(false,'submits','登录失败，你的帐号已被冻结','','','');
		 	if(!$result['is_vetted']) $this->myInfos(false,'submits','你的资料正在审核中，暂不能登录操作...','','','');
			$loginLimit=5;  //第天登录错误限制次数
			$satrTime=strtotime(date('Y-m-d'));
			$endTime=$satrTime+86400;
			if($result['temp_login_time'] >=$satrTime && $result['temp_login_time'] <= $endTime){
				if($result['temp_login_count'] >= $loginLimit){
					$this->myInfos(false,'submits',isL(L('AccountException'),'帐号异常，今天你被限制登录！'),'','','');
				}
			}else{
				$supplier->where(array('id'=>$result['id']))->save(array('temp_login_count'=>0));
			}	 
			//如果登录失败
			if(!compare_sha1($password,$result['sha1_random'],$result['password'])){
                 if($result['temp_login_count']==0) $tempDate['temp_login_time']=time();
				 $tempDate['temp_login_count']=$result['temp_login_count']+1;
				 $supplier->where(array('id'=>$result['id']))->save($tempDate);	
				 $this->myInfos(false,'submits',isL(L('UserNameOrPasswordError'),'帐号或密码错误'),'','','');					
			}	
			//更新用户信息
		   $ip=get_ip();
		   $times=time();
		   $data['login_count']=$result['login_count']+1;
		   $data['last_time']=$times;
		   $data['last_ip']=$ip;
		   $data['temp_login_count']=0; //还原正常状态	
		   $supplier->where(array('id'=>$result['id']))->save($data);	
		   //记录SESSION
		   $_SESSION[C('SUPPLIER_ID')]=$result['id']; //用户ID
		   $_SESSION[C('SUPPLIER_LOGIN_NAME')]=$result['login_name']; //企业登录帐号
		   $_SESSION[C('SUPPLIER_NAME')]=$result['name']; //企业名称
		   $_SESSION[C('SUPPLIER_IP')]=$ip; //当前登录IP
		   $_SESSION[C('SUPPLIER_LAST_TIME')]=$result['last_time']; //上次登录时间
		   $_SESSION[C('SUPPLIER_TIME')]=$times; //当前登录时间
		   $this->myInfos(true,'submits','登录成功',U(is_m().'/Supplier/index'),'','');		
		 }else{
		 	$this->myInfos(false,'submits',isL(L('UserNameOrPasswordError'),'帐号或密码错误'),'','','');		
		 }
    }	
	
	//供应商退出登录
	protected function basicExitUser(){
          unset($_SESSION[C('SUPPLIER_ID')]);
		  unset($_SESSION[C('SUPPLIER_LOGIN_NAME')]);
		  unset($_SESSION[C('SUPPLIER_NAME')]);
		  unset($_SESSION[C('SUPPLIER_IP')]);
		  unset($_SESSION[C('SUPPLIER_TIME')]);
		  unset($_SESSION[C('SUPPLIER_LAST_TIME')]);
		  header("location:".$_SERVER["HTTP_REFERER"]);
	}
	
	//供应商注册页
	protected function basicRegister(){
		$this->locations();//登录后不可见
		//默认区域
		$model=new CommonModel();
		$this->region=$model->getRegion('','','','');			
		$this->cates=M('goods_category')->field("id,{$this->langfx}name AS name")->where("pid = 0 AND is_show=1")->order("reorder ASC")->select();
	    $this->display();
	}
	
	//供应商注册表单处理
	protected function basicRegisterDo(){
		nonlicet_url();//登录后不可见
		$this->locations();
        $this->hckToken($_POST);
		$data['name']=$_POST['name'] ? emptyHtml($_POST['name']) : $this->myInfos(false, 'name', '企业名称不可空');
		$data['people_num']=$_POST['people_num'] ? emptyHtml($_POST['people_num']) : 0;
		$data['license']=$_POST['license'] ? emptyHtml($_POST['license']) : $this->myInfos(false, 'license', '请上传执照图片');
		$data['goods_category_id']=$_POST['goods_category_id'][0] ? implode(',',$_POST['goods_category_id']) : $this->myInfos(false, 'goods_category_id', '请选择服务项目');
		$data['circulation']=$_POST['circulation'] ? emptyHtml($_POST['circulation']) : '';
		$data['bewrite']=$_POST['bewrite'] ? emptyHtml($_POST['bewrite']) : '';
		$data['contacts']=$_POST['contacts'] ? emptyHtml($_POST['contacts']) : $this->myInfos(false, 'contacts', '联系人不可空');
		$data['tel']=num_line($_POST['tel']) ? $_POST['tel'] : $this->myInfos(false, 'tel', '电话或手机格式错误');
		$data['qq']=is_nums($_POST['qq']) ? $_POST['qq'] : '';
		$data['email']=is_email($_POST['email']) ? $_POST['email'] : '';
		$data['country']=$_POST['country'] > 0 ? $_POST['country'] : $this->myInfos(false, 'country', '请选择国家');
		$data['province']=$_POST['province'] > 0 ? $_POST['province'] : $this->myInfos(false, 'province', '请选择省份');
		$data['city']=$_POST['city'] > 0 ? $_POST['city'] : $this->myInfos(false, 'city', '请选择省份');
		$data['district']=$_POST['district'] > 0 ? $_POST['district'] : 0;
		$data['address']=$_POST['address'] ? emptyHtml($_POST['address']) : $this->myInfos(false, 'address', '请填写祥细街道');
		$data['addtime']=time();
		
		if($id=M('goods_supplier')->add($data)){
			   import('Class.DisposeImg',APP_PATH);
			   DisposeImg::addPictures('goods_supplier',$id,array($data['license'],$data['circulation']),'','','');			
			$this->myInfos(true, 'submits', '提交成功，请等待客服专员联系你',U(is_m().'/Index/index'));
		}else{
			$this->myInfos(false, 'submits', '提交失败，请重试',U(is_m().'/Supplier/register'));
		}
	}

    //需要登录才可见的页面
    private function isLogin(){
    	if(!is_supplier_login()){
    		header("location:".U(is_m().'/Supplier/login'));
			exit();
    	}
    }
	
	//登录后不可见的页面
	private function locations(){
    	if(is_supplier_login()){
    		header("location:".U(is_m().'/Supplier/index'));
			exit();
    	}		
	}

}