<?php
// 登录公共控制器
class LoginBasicAction extends BasicAction {
    
	//登录表单提交
    protected function basicLoginDo(){
		nonlicet_url();
        $this->hckToken($_REQUEST);
		$submits=$_REQUEST['submits'] ? $_REQUEST['submits'] : 'submits';
		$userName=$_REQUEST['user_name'] ? 
		quotes(trim(strip_tags($_REQUEST['user_name']))) :$this->myInfos(false,$submits,isL(L('UserNameIsEmpty'),'用户名称不可为空'),'','','');
		
		$password=$_REQUEST['password'] ? 
		quotes(trim(strip_tags($_REQUEST['password']))) :$this->myInfos(false,$submits,isL(L('PleaseEnterApassword'),'请输入密码'),'','','');
		$mbmer=M('member');
		$userWhere=array('user_name'=>$userName);
		$field=array('id','user_name','mobile_phone','nickname','head_pic','password','sha1_random','rank_points','login_count','is_lock','temp_login_count','surname','full_name','temp_login_time','last_time');
		
		//登录失败是否超过限制次数		
		$loginError=$mbmer->where($userWhere)->find();
		if($loginError){
			$loginLimit=5;  //第天登录错误限制次数
			$satrTime=strtotime(date('Y-m-d'));
			$endTime=$satrTime+86400;
			$arr['satrTime']=$satrTime;
			$arr['endTime']=$endTime;			
			if($loginError['temp_login_time'] >= $arr['satrTime'] && $loginError['temp_login_time'] <=$arr['endTime']){
				if($loginError['temp_login_count'] >= $loginLimit){
					$this->myInfos(false,$submits,isL(L('AccountException'),'帐号异常，今天你被限制登录！'),'','','');
				}			
			}else{
				$mbmer->where(array('id'=>$loginError['id']))->save(array('temp_login_count'=>0));
			}			
		}

		$user=$mbmer->field($field)->where($userWhere)->find();
         if(!$user || !compare_sha1($password,$user['sha1_random'],$user['password'])){
		        if($user){ //记录用户登录失败
                     if($user['temp_login_count']==0) $tempDate['temp_login_time']=time();
					 $tempDate['temp_login_count']=$user['temp_login_count']+1;
					 $mbmer->where(array('id'=>$user['id']))->save($tempDate);
				}
				$this->myInfos(false,$submits,isL(L('UserNameOrPasswordError'),'用户名或密码错误'),'','','');		
          }else{
                if($user['is_lock']) {
					$this->myInfos(false,$submits,isL(L('UsersAreLocked'),'用户被锁定'),'','','');				
                }
         }		
		 
		 //更新用户信息
		 $data['login_count']=$user['login_count']+1;
		 $data['last_time']=time();
		 $data['last_ip']=get_ip();
		 $data['temp_login_count']=0; //还原正常状态
		 if($_SESSION['QQ_KEY']){
		      $data['qq_key']=$_SESSION['QQ_KEY'];
			  unset($_SESSION['QQ_KEY']);
			  $loginInfo='QQ登录邦定成功正在转向...';
		 }else if($_SESSION['SINA_KEY']){
		      $data['sina_key']=$_SESSION['SINA_KEY']; 
			  unset($_SESSION['SINA_KEY']);
		      $loginInfo='新浪微博邦定成功正在转向...';
		 }else{
		     $loginInfo='';
		 }		 
		$mbmer->where(array('id'=>$user['id']))->save($data);
	    $ge=new CommonModel();
		$memberGrade=$ge->getGrade($user['rank_points']);
		$logins[C('SE_USER_GRADR_ID')]=$memberGrade['id'];//会员等级ID
		$logins[C('SE_USER_GRADR_NAME')]=$memberGrade['grade_name'];//会员等级名称
		$logins[C('SE_USER_ID')]=$user['id'];//会员名称
		$logins[C('SE_USER_NAME')]=$user['user_name'];//用户帐号email
		$logins[C('SE_NICKNAME')]=$user['full_name'].' '.$user['surname'];//用户名字
		$logins[C('SE_USER_HEAD_PIC')]=$user['head_pic'] ? $user['head_pic'] : C('USER_IMG'); //用户默认头像
		$logins[C('SE_USER_IP')]=$data['last_ip']; //用户IP 
		$logins[C('SE_USER_LAST_TIME')]=$user['last_time']; //用户上次登录时间
		$logins[C('SE_USER_LIGON_TIEM')]=time(); //用户登录时间
		if(intval($_REQUEST['remember']) > 0) set_login($logins,false,86400 * 360);//写COOKIE
		else set_login($logins,true,0);//写SESSION
		
        //整合购物车
		M('cart')->where("user_key='{$_COOKIE['user_key']}'")->save(array('user_id'=>$user['id']));
		
		 if($_REQUEST['returnUrl']){
			  $url=base64_decode($_REQUEST['returnUrl']);
		 }else{
			  $url=U(is_m().'/Index/index');
		 }		
		 $this->myInfos(true,$submits,$loginInfo,$url,'','');		    
	}
	
	//退出登录
	protected function basicExitLogin(){
       //session_unset();
       //session_destroy();
	  $arrays = array(
			 C('SE_USER_GRADR_ID'),
			 C('SE_USER_GRADR_NAME'),
			 C('SE_USER_ID'),
			 C('SE_USER_NAME'),
			 C('SE_NICKNAME'),
			 C('SE_USER_HEAD_PIC'),
			 C('SE_USER_IP'),
			 C('SE_USER_LIGON_TIEM')
	   );
	   unset_login($arrays);
	   header('location:'.$_SERVER["HTTP_REFERER"]);	
	}
	
	//检测用户是否存在
	protected function basicChecks(){
	     nonlicet_url();
	   /*  $field=$_GET['fieldId'];
		 if($field=='mobile') $field='mobile_phone';
		 $where[$field]=$_GET['fieldValue'];*/
		 $where['user_name']=emptyHtml($_GET['fieldValue']);
		 $res=M('member')->field('id')->where($where)->find();
		 if($res){
		      die(json_encode(array($_GET['fieldId'],true)));
		  }else{
		     die(json_encode(array($_GET['fieldId'],false)));
		 }	
	}
	
}