<?php
// 注册公共控制器
class RegisterBasicAction extends BasicAction {
	//注册
	protected function basicRegDo(){
	    nonlicet_url();
	    $this->hckToken($_REQUEST);
		$submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
		
		//从购物车直接注册(收货地址)
		if($submits=='not-regster' && $_GET['ids'] && $_GET['total']){
			$cartUrl=sign_url(array('ids'=>$_GET['ids'],'total'=>$_GET['total']), U('CheckOut/selectAddress','','')); //跳到选择地址页
		    $data['surname']=$_REQUEST['surname'] ? 
		    quotes(strip_tags(trim($_REQUEST['surname']))) : val_json($submits,false,isL(L('NameCanNotBeEmpty'),"收货人姓名不能为空"),'','','');		   
		    $data['consignee']=$_REQUEST['full_name'] ? 
		    quotes(strip_tags(trim($_REQUEST['full_name']))) : val_json($submits,false,isL(L('NameCanNotBeEmpty'),"收货人姓名不能为空"),'','','');
		   
		  // $data['mobile']=is_numeric($_REQUEST['mobile']) ?  
		  // quotes(strip_tags(trim($_REQUEST['mobile']))) : val_json("mobile",false,isL(L('HandNumberError'),"手机格式错误"),'','','');
		  // if(!($_REQUEST['country'] > 0)) val_json($submits,false,isL(L('SelectCountry'),"请选择国家"),'','','');
		  //$_REQUEST['country']=$_REQUEST['country'] ? $_REQUEST['country'] : 1; //默认中国
		   /*$region=M('region')->field('region_id,region_name')->where(array('region_id'=>$_REQUEST['country']))->find();
		   $data['coun_id']=$region['region_id'];
		   $data['country']=$region['region_name'];*/
		   
		   $data['country']=$_REQUEST['country'] ? $_REQUEST['country'] : '中国';
           $data['province']=$_REQUEST['province'] ?  
		   emptyHtml($_REQUEST['province']) : val_json($submits,false,isL(L('SelectProvince'),'请选择省份'),'','','');
		   
           $data['city']= $_REQUEST['city'] ?  
		   emptyHtml($_REQUEST['city']) : val_json($submits,false,isL(L('SelectCity'),'请选择城市'),'','','');
		   
           $data['district']=$_REQUEST['district'] ?  emptyHtml($_REQUEST['district']) : 0;
	       $data['email']=$_REQUEST['email'] ?  quotes(strip_tags(trim($_REQUEST['email']))) : '';
           $data['address']=$_REQUEST['address'] ?  
		   quotes(strip_tags(trim($_REQUEST['address']))) : val_json($submits,false,isL(L('AddressCanNotBeEmpty'),"祥细地址不能为空"),'','','');
		   $data['zipcode']=$_REQUEST['zipcode'] ? $_REQUEST['zipcode']: '';
		   $data['tel']=$_REQUEST['tel'] ? $_REQUEST['tel']: '';
		   //$data['user_id']=$this->userId;			
		}		
		$times=time();
		$member=array();
		//if(md5($_REQUEST['code']) !=$_SESSION['verify']) $this->myInfos(false,'code',isL(L('VerificationCodeError'),'验证码错误'),'','','');
		
		//if(!$_REQUEST['agree']) $this->myInfos(false,'agree',isL(L('RegistrationAgreement'),'请同意注册协议'),'','','');

		//$member['full_name']=$_REQUEST['full_name'] ? 
		//quotes(trim(strip_tags($_REQUEST['full_name']))) :$this->myInfos(false,$submits,isL(L('NameCanEmpty'),'姓名不能为空'),'','','');
        //名称
        $member['full_name']=$_REQUEST['full_name'] ? 
		quotes(trim(strip_tags($_REQUEST['full_name']))) :'';
        //姓氏
        $member['surname']=$_REQUEST['surname'] ? 
		quotes(trim(strip_tags($_REQUEST['surname']))) :'';
		
		$member['user_name']=is_email($_REQUEST['user_name']) ? 
		$_REQUEST['user_name'] : $this->myInfos(false,$submits,isL(L('EmailFormatError'),'电子邮件格式错误'),'','','');
		 
		$arr=$_REQUEST['password'] ? 
		pwd_sha1(quotes(trim(strip_tags($_REQUEST['password'])))) :$this->myInfos(false,$submits,isL(L('PleaseEnterApassword'),'请输入密码'),'','','');	
		
		if($_REQUEST['password'] !=$_REQUEST['not_password']) 
		$this->myInfos(false,$submits,isL(L('TwoInputConsistent'),'两次密码输入不一致'),'','','');
		
		$member['password']=$arr['SHA1'];
		
		$member['sha1_random']=$arr['RANDOM'];	
		
        $member['mobile_phone']=check_mobile($_REQUEST['mobile_phone']) ? $_REQUEST['mobile_phone'] :'';	
		
		//$member['reg_email']=is_email($_REQUEST['reg_email']) ? 
		//$_REQUEST['reg_email'] : $this->myInfos(false,$submits,isL(L('EmailFormatError'),'电子邮件格式错误'),'','','');
		
        $member['reg_time']=$times;
		$m=M('member');
        //验证会员是否重复
		if($m->field('id')->where("user_name='{$member['user_name']}'")->find()) 
		$this->myInfos(false,$submits,isL(L('EmailHasBeenRegistered'),'此电子邮件已被其他用户注册'),'','','');		
			
			/*****验证注册码**********/
			/*$code=is_nums($_REQUEST['str_code']) ? $_REQUEST['str_code'] : val_json('str_code',false,'邀请码格式输入错误','','');	
			$dbQuery=$this->strCode($code);
			if($dbQuery['status']==0){
				val_json('str_code',false,$dbQuery['msg'],'','');	
			}else{
				//设置已注册状态
				M('lnvite')->where(array('str_code'=>$code))->save(array('status'=>1,'make_time'=>time()));
				$shops['agent_shops_id']=$dbQuery['shops_id'];
			}*/
		    /*****验证注册码处理结束**********/
	
		if($id=$m->add($member)){
			
			//是否为订阅邮件
			if(intval($_REQUEST['subs']) > 0){
				M('email_subscribe')->add(array('email'=>$member['user_name'],'user_name'=>$member['full_name'],'add_time'=>$times));
			}
            
			//写入会员收货地址
			if($data){
				$data['user_id']=$id;
				$data['default_address']=1;
				$selectAdderss=M('member_address')->add($data);
				//直接跳到支付页
				$cartUrl=sign_url(array('ids'=>$_GET['ids'],'total'=>$_GET['total']), U('CheckOut/selectPay','','')).'&selectAdderss='.$selectAdderss; 
			}
			
			//新注册用户增送积分
			$grade=$this->sendingPoints($id);
			
			//新注册用户增送现金卷
			$this->sendingCash($id,$times);
			
			//推荐者增送操作			
			$recommend=$_REQUEST['recommend'] ? trim(strip_tags($_REQUEST['recommend'])) : ''; //获取推荐者信息
			$recResult=array();
			if($recommend){
				$recResult=$m->field('id,user_name')->where(array('user_name'=>$recommend))->find();
				$member['recommend']=$recommend; //推荐人
			}else{
				$member['recommend']=''; //推荐人
			}			
			if($recResult){
				//送现金卷
				$this->sendingRecommendCash($recResult['id'],$times);
				//积分处理
				$this->sendingRecommendPoints($recResult['id'],$member['user_name']);
			}
						
			//普通会员写入用户登录信息
			/*$_SESSION[C('SE_USER_LEVEL')]=$member['user_type'] ? $member['user_type'] : '';//用户级别
			$_SESSION[C('SE_USER_ID')]=$id;//用户ID
			$_SESSION[C('SE_USER_NAME')]=$member['user_name'];//用户帐号
			$_SESSION[C('SE_USER_HEAD_PIC')]=C('USER_IMG'); // //用户头像
			$_SESSION[C('SE_USER_IP')]=get_ip(); //用户IP 
			$_SESSION[C('SE_USER_LIGON_TIEM')]=$times; //用户登录时间	
			*/
			$logins[C('SE_USER_GRADR_ID')]=$grade['id'];//会员等级ID
			$logins[C('SE_USER_GRADR_NAME')]=$$grade['grade_name'];//会员等级名称
			$logins[C('SE_USER_ID')]=$id;//会员名称
			$logins[C('SE_USER_NAME')]=$member['user_name'];//用户帐号
			$logins[C('SE_NICKNAME')]=$member['full_name'].' '.$member['surname'];//用户名字
			$logins[C('SE_USER_HEAD_PIC')]=C('USER_IMG'); // //用户头像
			$logins[C('SE_USER_IP')]=get_ip(); //用户IP 
			$logins[C('SE_USER_LAST_TIME')]=$times; //用户上次登录时间
			$logins[C('SE_USER_LIGON_TIEM')]=$times; //用户登录时间	
			set_login($logins,true,0);//写SESSION
			//订阅邮件通知
			if(intval($_REQUEST['subs']) > 0){
				$tpl='./Public/EmailTpl/subscribe/'.pfix('yjdy').'.html';
				$contents=file_get_contents($tpl);
				$contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
				//$contents=str_replace('{$str1}',gmstrftime(" %d %b, %X "),$contents);
				$contents=str_replace('{$str1}',date('Y.m.d H:i',$times),$contents);
				$contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name'].' '.$member['surname']),$contents);
				send_mail2($member['user_name'],isL(L('ThankYouFor'),'感谢您订阅了我们的电子报'),$contents);
			}
			$cartUrl=$cartUrl ? $cartUrl : base64_decode($_REQUEST['cart_url']); 
			if($cartUrl){
				header('location:'.$cartUrl);
				//$this->myInfos(true,$submits,isL(L('RegisteredSuccess'),'注册成功'),$cartUrl,'','');	
			}else{
				$this->myInfos(true,$submits,'',__ROOT__.'/'.is_m(),'','');	
			}						
		}else{
			if($cartUrl){
				$this->myInfos(false,$submits,isL(L('RegisteredFailure'),'注册失败，请重新注册'),U(is_m().'/Index/index'),'','');
			}else{
				$this->myInfos(false,$submits,isL(L('RegisteredFailure'),'注册失败，请重新注册'),U(is_m().'/Register/index'),'','');
			}			
		}			
	}

	//检查用户名、手机、电子邮件是否被占用
	//只需要在input 属性ID值是数据库字段名
	protected function basicChkData(){
	     nonlicet_url();
	     $field=$_REQUEST['fieldId'];
		 $where[$field]=$_REQUEST['fieldValue'];
		 $member['user_name']=$_REQUEST['fieldValue'];
		 //$member['reg_email']=$_GET['fieldValue'];
		 //$member['mobile_phone']=$_GET['fieldValue'];
		 //$member['_logic']='OR';
		 $res=M('member')->field('id')->where($member)->find();	 
		 if($res){
		      die(json_encode(array($_REQUEST['fieldId'],false)));
		  }else{
		     die(json_encode(array($_REQUEST['fieldId'],true)));
		 }
	}	

	//发送手机短信息
	protected function basicSendMobile(){
	  nonlicet_url();
	  $this->hckToken($_POST);
	  if($_POST['chks']==1){ //验证手机是否重复
		   $where['reg_email']=$_POST['moblie'];
		   $where['mobile_phone']=$_POST['moblie'];
		   $where['_logic']='OR';
	       if(M('member')->field('id')->where($where)->find()){
		      die('1');
		   }else{
		      die('0');
		   }	       
	  }

	  if($_POST['chks']==2){ //验证手机是否存在
	       $where['mobile_phone']=$_POST['moblie'];
	       if(M('member')->field('id')->where($where)->find()){
		      die('0');
		   }else{
		      die('1');
		   }	       
	  }

      import('Class.MobileMessage',APP_PATH);	
	  $times=C('CODEUSEFULTIME') * 3600;
	  if($times >=3600){
		  $timeInfo=($times / 3600).'小时';
	  }else{
		  $timeInfo=floor($times / 60).'分钟';
	  }
	  //$info="你好！你的手机验证码：__CODE__，".$timeInfo."内有效";
	  $info="__CODE__。验证码有效期为{$timeInfo}，过期将失效，感谢您的注册！";
	  $moblie=new MobileMessage($_POST['moblie'],'',true,false,$info);	
	  if($moblie->returnInfos){
	     echo $moblie->returnInfos;		
	  }else{
	     echo $moblie->success;		
	  }     	
	  
	}
	
	//检索邀请码是否正确
	private function basicStrCode($code){
		$ip=get_ip();
		$counts=5; //同一IP一小时最多试5次
		$querys=M('lnvite_query');
		$qu=$querys->where(array('ip'=>$ip))->find();
		if($qu){
			if($qu['query_count'] < $counts){
			    $querys->where(array('id'=>$qu['id']))->save(array('query_count'=>$qu['query_count']+1));
			}else{
				if(time() > ($qu['query_time']+3600)){
					$querys->where(array('id'=>$qu['id']))->save(array('query_time'=>time(),'query_count'=>1));
				}else{
					return array('status'=>0,'msg'=>'请先休息一会，等会再试');
				}				
			}
		}else{
		    $querys->add(array('ip'=>$ip,'query_time'=>time(),'query_count'=>1));
		}
		$result=M('lnvite')->field('id,shops_id')->where(array('str_code'=>$code,'status'=>0,'make_time'=>0))->find();
		if($result){
			   $querys->where(array('ip'=>$ip))->delete();
			   $result['status']=1;
			   $result['msg']='验证成功';
		       return $result;
		}else{
			   return array('status'=>0,'msg'=>'不存在此邀请码');
		}
	}	
	
	/**
	*注册会员是否送现金卷
	*$userId 用户ID
	*$times  时间截
	*/
	private function sendingCash($userId,$times){
		    $returnInfo=true;
			$bWhere['send_type']=C('_FIVE');
			$bWhere['send_start_date']=array('ELT',$times);
			$bWhere['send_end_date']=array('EGT',$times);
			$bWhere['is_show']=1;
			$res=M('bonus_type')->field('id')->where($bWhere)->find();
			if($res){
				$bonus['bonus_type_id']=$res['id'];
				$bonus['send_type']=C('_FIVE');
				$bonus['bonus_sn']=0;
				$bonus['member_id']=$userId;
				$bonus['used_time']=0;
				$bonus['order_id']=0;
				if(!M('member_bonus')->add($bonus)) $returnInfo=false;
			}	
			return $returnInfo;
	} 
	
	/**
	*注册会员是否增送消费积分和等级积分
	*$userId 用户ID
	*/
	private function sendingPoints($userId){
	    $payPoints=C('INTEGRAL_BESTOWS_PAY') > 0 ? C('INTEGRAL_BESTOWS_PAY') : 0;
		$rankPoints=C('INTEGRAL_BESTOWS_RANK') > 0 ? C('INTEGRAL_BESTOWS_RANK') : 0;
		if($payPoints > 0 || $rankPoints >0){
			$sql="UPDATE ".PREFIX."member SET rank_points=rank_points+{$rankPoints},pay_points=pay_points+{$payPoints} WHERE id={$userId} LIMIT 1";
			$this->DB->execute($sql);
			$account['member_id']=$userId;
			$account['transaction']=0;
			$account['return_mark']=0;
			$account['pay_code']='';
			$account['member_money']=0;
			$account['frozen_money']=0;
			$account['rank_points']=$rankPoints;
			$account['pay_points']=$payPoints;
			$account['change_time']=time();
			$account['change_type']=99;
			$account['change_desc']="新用户注册增送消费积分({$payPoints})，等级积分($rankPoints)。";
			M('member_account')->add($account);
			
			//返回等级信息
			if(C('IS_RANK_POINTS') && ($rankPoints > 0)){
				 $grade=new CommonModel(); 
				 return $grade->getGrade($rankPoints);
			}
		}
	}
	
	/**
	*推荐会员增送现金额
	*$recUserId 推荐会员ID
	*$times   时间截
	*/
	private function sendingRecommendCash($recUserId,$times){
		    $returnInfo=true;
			$bWhere['send_type']=C('_FOUR');
			$bWhere['send_start_date']=array('ELT',$times);
			$bWhere['send_end_date']=array('EGT',$times);
			$bWhere['is_show']=1;
			$res=M('bonus_type')->field('id')->where($bWhere)->find();
			if($res){
				$bonus['bonus_type_id']=$res['id'];
				$bonus['send_type']=C('_FOUR');
				$bonus['bonus_sn']=0;
				$bonus['member_id']=$recUserId;
				$bonus['used_time']=0;
				$bonus['order_id']=0;
				if(!M('member_bonus')->add($bonus)) $returnInfo=false;
			}	
			return $returnInfo;	
	} 

	/**
	*推荐者增送消费积分和等级积分
	*$recUserId 推荐会员ID
	*$userName 被推荐会员名称
	*/
	private function sendingRecommendPoints($recUserId,$userName){
		$returnInfo=true;
	    $payPoints=C('RECOMMEND_PAY_POINTS') > 0 ? C('RECOMMEND_PAY_POINTS') : 0;
		$rankPoints=C('RECOMMEND_RANK_POINTS') > 0 ? C('RECOMMEND_RANK_POINTS') : 0;
		if($payPoints > 0 || $rankPoints >0){
			$sql="UPDATE ".PREFIX."member SET rank_points=rank_points+{$rankPoints},pay_points=pay_points+{$payPoints} WHERE id={$recUserId} LIMIT 1";
			if(!$this->DB->execute($sql)) $returnInfo=false;
			$account['member_id']=$recUserId;
			$account['transaction']=0;
			$account['return_mark']=0;
			$account['member_money']=0;
			$account['pay_code']='';
			$account['frozen_money']=0;
			$account['rank_points']=$rankPoints;
			$account['pay_points']=$payPoints;
			$account['change_time']=time();
			$account['change_type']=99;
			$account['change_desc']="推荐新用户({$userName})注册增送消费积分({$payPoints})，等级积分($rankPoints)。";
			if(!M('member_account')->add($account)) $returnInfo=false;			
		}
		return $returnInfo;
	}	
	
}