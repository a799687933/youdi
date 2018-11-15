<?php
//代理广州市程宇网络科技有限公司短信息接口专用类2015-11-2
//手机发送验证码和验证码比较
//发送号码无须调用方法直截实例化，传参数手机号码即可
/**
--
-- 表的结构 `my_mobile_code`
--
CREATE TABLE IF NOT EXISTS `my_mobile_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `code` char(40) NOT NULL COMMENT '验证码',
  `mobile` char(15) NOT NULL COMMENT '手机号',
  `ip` char(20) NOT NULL COMMENT '用户IP',
  `number` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '手机发送次数；',
  `times` int(11) unsigned NOT NULL COMMENT '写入时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `mobile` (`mobile`),
  KEY `times` (`times`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='手机验证' AUTO_INCREMENT=0 ;

*/
class MobileMessage{
	private  $mobile;  //手机号多个用逗号隔开
	private  $info;  //发送手机信息文字内容
	private  $code; //生成随机验证码
	private  $length=6; //验证码位数
	private  $md5Code; //md5加密后的随机验证码
	private  $num; //接收检测验证码
	private  $codeTime=120; //数据库验证有效时间(单位分钟)
	private  $userId=''; //信息平台用户帐户ID号 测试 (219)
	private  $account=''; //信息平台用户帐号 测试(yuege)
	private  $password=''; //信息平台用户密码	测试(123456)
	public   $success; //验证码成功信息，1为成功 0 为失败
	public   $returnInfos; //提示信息

    /**
     * 构造函数
     *@access  public
	 *@param  $mobile   手机号多个用逗号隔开
	 *@param  $num   接收表单输入手机验证码
	 *@param  $isDb   是否记录验证;false不记录;true记录；
	 *@param  $type   false 发送手机验证码,true比较验证码
	 *@param  $info   发送信息时的提示信息
     *@return void
	 *@发送信息  $moblie=new MobileMessage('13570363196','',true,false,'这里是信息提示');	 
	 *@验证时      $moblie=new MobileMessage('13570363196','0839',false,true,'');echo $moblie->success;
     */
   public  function __construct($mobile,$num='',$isDb=false,$type=false,$info='') {
	      $this->info=$info;
		  $this->mobile=$mobile; //手机号码
		 // if(C('CODEUSEFULTIME') > 0) $this->codeTime=C('CODEUSEFULTIME'); //验证码有效时间
		  if(!$type){ //发送手机验证码
				if($isDb){
					$this->generateCode();//生成验证码
					$this->encryptsWord();//md5加密生成的验证码
					$this->retrunGenerateCode(); //验证码和手机写到数据库				
				}
				$this->sentMessage();//发送手机消息
		  }else{
			   $this->code=$num; //验证随机生成验证码
			   if($this->checkWord()){
				     $this->success=1;
					// $this->unsetDbCode();  //删除验证码
			   }else{ 
			        $this->success=0;
			  }
		  }
    }

    /**
     * 检查给出的验证码是否和session中的一致
     * @access  private
     * @return  bool
     */
    private function checkWord(){ 
	    $this->encryptsWord();
	    $times=time();
		$setTime=$this->codeTime * 60;  //1分钟=60秒
		$where['code']=base64_encode($this->md5Code);
		$where['mobile']=$this->mobile;
	    $codeArr=M('mobile_code')->field('code,mobile,times')->where($where)->find();
		if(($times-$codeArr['times']) <=$setTime){
			$recorded = $codeArr['code'] ? base64_decode($codeArr['code']) : '';
			return (preg_match("/$this->md5Code/", $recorded) && $codeArr['mobile'] == $this->mobile);		   
		}else{
			$this->unsetDbCode(); //删除数据库验证码
		    return false;
		}

    }

    /**
     * 记录验证码到数据库
     * @access  private
     * @return  ''
     */
    private function retrunGenerateCode(){
	    $sendCount=5; //每小时同一手机号最多次发送次数
	    $ip=get_ip();
		$time=time();
	    $mobileCode=M('mobile_code');
		if($res=$mobileCode->where(array('mobile'=>$this->mobile))->find()){
		    if(($time - $res['times']) <= 3600 && $res['number'] >=$sendCount) {
			    $this->success=0;
				$this->returnInfos='一小时内最多发送'.$sendCount.'条信息';
				die();
			}else{
			   if($res['number'] >=$sendCount){
			        $mumber=1;
			   }else{
			        $mumber=$res['number']+1;
			   }
			   $mobileCode->where(array('mobile'=>$this->mobile))->save(array('code'=>base64_encode($this->md5Code),'number'=>$mumber,'times'=>$time));
			}		    
		}else{
		    $mobileCode->add(array('code'=>base64_encode($this->md5Code),'mobile'=>$this->mobile,'ip'=>$ip,'number'=>1,'times'=>$time));
		}
    }

    /**
     * 删除数据库验证码的记录
     * @access  public
     * @return  ''
     */
    public function unsetDbCode(){
		 M('mobile_code')->where(array('code'=>base64_encode($this->md5Code),'mobile'=>$this->mobile))->delete();
	}
	
    /*------------------------------------------------------ */
    //-- PRIVATE METHODs
    /*------------------------------------------------------ */

    /**
     * 对需要记录的串进行加密
     *
     * @access  private
     * @param   string  $word   原始字符串
     * @return  string
     */
   private function encryptsWord() {
        $this->md5Code = substr(md5($this->code), 1, 10);
    }

    /**
     * 生成随机的验证码
     * @access  private
     * @return  string
     */
   private function generateCode(){
        $chars = '123456789';
        for ($i = 0, $count = strlen($chars); $i < $count; $i++) {
            $arr[$i] = $chars[$i];
        }
        mt_srand((double) microtime() * 1000000);
        shuffle($arr);
        $this->code = substr(implode('', $arr), 3, $this->length);
    }
	/**
     * 发送手机消息
     * @access  private
     * @return  string
     */
   private function sentMessage() { 
		$message=str_replace('__CODE__',$this->code,$this->info); //把__CODE__替换成验证码		
	    $content=urlencode($message);
		$urlString='action=send&userid='.$this->userId.'&account='.$this->account.'&password='.$this->password.'&mobile='.$this->mobile.'&content='.$content.'&sendTime=&extno=';
	    $url='http://113.11.210.114:5888/sms.aspx?'.$urlString;	
		$returnInfo = file_get_contents($url);
		if($returnInfo){
			$xml = simplexml_load_string($returnInfo);
			if(strtoupper($xml->returnstatus)=='SUCCESS'){
				$this->success=1;
			}else{
				$this->success=0;
			}
		}else{
			$this->success=0;
		}
    }
}
?>