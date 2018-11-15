<?php
//打印数组
function p($array){
	dump($array,1,'<pre>',0);
}


/*搛容跳转*/
function jump($url){
   header('location:'.$url);
   $str='<script type="text/javascript">window.location.href="'.$url.'";</script>';
   die($str);
}

//未读信息数量提醒
function remind_get_num(){
     $memberId=getUserC('SE_USER_ID');
	 if(!$memberId) return 0;
	 $counts=0;
	 //文章评论管理员回复数
	 $counts+=M('comment_reply')->where("read_user_id='{$memberId}' AND (is_read=0)")->count();
	 //评论用户点赞未读数
	 //$counts+=M('article_click')->where("read_user_id='{$memberId}' AND (is_read=0) AND (table_num=1)")->count();
	 //商品评论未读数
	 $counts+=M('goods_appraise_reply')->where("read_user_id='{$memberId}' AND (is_read=0)")->count();
	 return $counts > 0 ? $counts : 0;
}

//全部标记已读信息
function remind_set_num(){
     $memberId=getUserC('SE_USER_ID');
	 if(!$memberId) return false;
	 //文章评论管理员回复数清零条数字
	 M('comment_reply')->where("read_user_id='{$memberId}' AND (is_read=0)")->save(array('is_read'=>1));
	 //评论用户点赞未读数清零
	 M('article_click')->where("read_user_id='{$memberId}' AND (is_read=0) AND (table_num=1)")->save(array('is_read'=>1));
	 //商品评论未读数清零
	 M('goods_appraise_reply')->where("read_user_id='{$memberId}' AND (is_read=0)")->save(array('is_read'=>1));
	 return -1;
}

/*程序睡眠N秒*/
function yodi_sleep(){
	return;
	sleep(5);
}

/**
*订单是否在可退货期限内
*$shipping_time  发货时间戳
*/
function is_return($shipping_time){
	$shipping_time=intval($shipping_time);
	if($shipping_time <= 0) return true;//没有发货时间，则表示在可退货时间内
    if(($shipping_time + (C('ORDER_RETURN_DATE') * 86400)) > time()) return true;
	return false;
}

/*把WEBROOT替换成目录目录*/
function web_root($str){
	return str_replace('WEBROOT',__ROOT__,$str);
}

/**返回多个年份
*$start_year
*$end_year
*/
function year_arr($start_year='',$end_year=''){
   if($start_year=='') $start_year=1970;
   if($end_year=='') $end_year=intval(date('Y'));
   $arr=array();
   for($i=$start_year;$i <=$end_year;$i++){
	   $arr[$i]=$i;
   }
   return $arr;
}

/**中式与美式日期获取
*$format  获取式格 Y-m-d H:i:s
*$times   时间戳或日期
*$lang      当前语言 默认以中式显示
*/
function format_date($format,$times,$lang='zh-cn'){       
     $times=strtotime($times) ? strtotime($times) : $times;
     if(!$lang || strtolower($lang)=='zh-cn') {// [年/月/日 时:分:秒 (中式日期)]date("Y-m-d h:i:s",time());
	     $strDate=date($format,$times);
	 }else{ // [月/日/年 时:分:秒 (美式) ]date("n/j/Y h:i:s",time());
	    $format=strtolower($format);
	    //格式分隔符
		$separate ='';
		if(strpos($format,'.') !==false) $separate='.';
		if(strpos($format,'-') !==false) $separate='-';
		if(strpos($format,'/') !==false) $separate='/';
	    //格式字符串
		$forStr='';
	    if(strrpos($format,'m') !==false) $forStr.='n'.$separate; //月份	
		if(strrpos($format,'d') !==false) $forStr.='j'.$separate; //日
		if(strrpos($format,'y') !==false) $forStr.='Y'; //年
		if(strrpos($format,'h') !==false)  $forStr.=' h:';
		if(strrpos($format,'i') !==false)  $forStr.='i:';
		if(strrpos($format,'s') !==false)  $forStr.='s:';
		$forStr=rtrim($forStr,$separate);
		$forStr=rtrim($forStr,':');
		$strDate=date($forStr,$times);
	 } 
	 return $strDate;
}

/*生成js对话框提示*/
function jsAlert($info,$url){
	$str='<script type="text/javascript">';
	if($info) $str.='alert("'.$info.'");';
	if($url) $str.='window.location.href="'.$url.'";';
	else $str.='history.go(-1);';
	$str.='</script>';
	die($str);
}

/*获取C配置内容并去掉单引号和双引号*/
function get_con($C){
	return escape_str_red(C($C));
}

/**
 * php判断微信浏览器
 * */
function is_weixin(){
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) return true;  
    return false;	
}

/**选中导航
 * $str  
 * select_nav('Index') 当前控制器是否是 Index
 * select_nav('Index/content') 当前控制器/方法名是否是Index/content
 * */
 function select_nav($str){
 	$str=trim($str,'/');
	if(strpos($str,'/') !==false) $sn=MODULE_NAME.'/'.ACTION_NAME;
	else $sn=MODULE_NAME;
	if($str==$sn) return true;
 }
 
/*生成页面缓存文件路径*/
function set_file_path($md5=''){
	$cookie=getLang();
	if($cookie !=='') $cookie='-'.$cookie;
	$get=$_GET;
	unset($get['_URL_']);
	$data='';	
	foreach($get as $v){
		$data.='-'.$v;
	}
	//echo $data;
	if($md5) $file=md5(MODULE_NAME.'-'.ACTION_NAME.$cookie.$data).'.html';
	else $file=MODULE_NAME.'-'.ACTION_NAME.$cookie.$data.'.html';
	$dir=APP_PATH.'CacheFile/';
	if(!is_dir($dir)) create_dir($dir);//生成文件夹
	$fileName=$dir.$file;
	return $fileName;	
}

/*广告A标签判断是否有链接*/
function a_href($url){
   if($url){
	   return 'href="'.$url.'" target="_blank"';
   }
}

/**
*转义单引号和双引号
*/	
function escape_str($str){
	if(empty($str)) return '';
    $str=str_replace('\\','',$str);
    $str=str_replace('"','\"',$str);
    $str=str_replace("'","\'",$str);
    return $str;
}	
/**
*还原转义的单引号和双引号
*/	
function escape_str_red($str){
	if(empty($str)) return '';
    return str_replace('\\','',$str);
}

/**
 * 中文转换base64格式
 * $str  字符串
 * */
function ch_to_base64($str){
	$arr=array($str);
	$json=json_encode($arr);
	return base64_encode($json);
}

/**
 * base64格式转换中文
 * */
 function base64_to_ch($base64_str){
 	$json=base64_decode($base64_str);
	$arr=json_decode($json,true);
	return $arr[0];
 }

/**
 * 计算完成进条度分比 degree(1000,300,200)
 * $gross_value 总值
 * $current_value 当前所到达的值
 * $css_width     CSS样式的长度
 * return array()
 * */
function degree($gross_value,$current_value,$css_width){
		$sum=$gross_value; //总数
		$current=$current_value; //当前数
		$leng=$css_width; //样式长度
		$percent=$current / $sum;//百分比	
		$minLeng=0;//最小CSS宽度
		if(strpos($percent,'.') !==false){
			if($percent < 0.01){
				if($percent < 0.01 && $percent > 0.005){
					$percent=0.006;
				}elseif($percent >= 0.001 && $percent <= 0.005){
					$percent=0.001;
				}else{
					$percent=0.0001;
				}
				$minLeng=1;
			}else{
				$expPercent=explode('.', $percent);
				$percent=$expPercent[0].'.'.substr($expPercent[1],0,2);				
			}
		}
		if($percent==1){
			$cssLeng=$leng;
		}else{
			$cssLeng=$minLeng ? $minLeng : $percent * $leng;
		}
		return array('percent'=>($percent * 100).'%','css_width'=>$cssLeng);
}

/**
*图片上传随机选择服务器
*$getParameter  array 	GET传参数 thum=ok&remove=ok&width=10&height=5&shop_dir=images&shops_id=10
* $uploadUrl  string  指定URL地址，如$config为true时此URL无效
* $is_force  当$config为true强制$uploadUrl有效
*return string
*/
function get_up_url($getParameter,$uploadUrl='',$is_force=false){
	 $times=time();
	 $config=false;
	 $url='';
	 $get='';
	 $sign='';
	 $getParameter['random']=$times;
	 foreach($getParameter as $k=>$v){
	 	$get.=$k.'='.$v.'&';
		 $gtKey.=$k;
		 $sign.=$v;
	 }
	 $get='?'.$get.'sign='.md5($sign.$gtKey.C('APP_KEY'));
	 if($config){
		 //随机选择服务地址
		 if(!$is_force){
			 $arrays=array('http://bac.com','https://tt.com/tecs','http://www.badfsl.com');	
			 $num=array_rand($arrays,1);
			 $url=rtrim($arrays[$num],'/').$get;	 		 	
		 }else{
		 	 $url=rtrim($uploadUrl,'/').$get;
		 }
	 }else{
	     $url=rtrim($uploadUrl,'/').$get;
	 }
	 return $url;

}

/**带签字名URL
 * $getParameter  GET参数
 * $url   URL
 * $appKey  签名KEY
 * */
function sign_url($getParameter,$url,$appKey=''){
	if(empty($appKey)) $appKey=C('APP_KEY');
	 $times=time();
	 $config=true;
	 $get='';
	 $sign='';
	 $getParameter['random']=$times;
	 foreach($getParameter as $k=>$v){
	 	$get.=$k.'='.$v.'&';
		 $gtKey.=$k;
		 $sign.=$v;
	 }
	 $get='?'.$get.'sign='.md5($sign.$gtKey.$appKey);
	 return rtrim($url,'/').$get;
}
 
/**
 * 接收请求签名
 * $turned     GET 或 POST 数组
 * $del_get  array  不参与签名的参数数组键名
 * $overdue 有效时间，-1不限制时间;0使用默认时间;大于0指定过期时间单位(秒);
 * */
 function verify_req_sign($turned,$del_get=array(),$overdue=0){
 	     if($overdue > -1){
 	     	 $overdue=$overdue > 0 ? $overdue : 1200;//有效时间20分钟
 	     } 	  		
		 $get=$turned;
 	     if(empty($turned['sign'])) return false;	
		 unset($get['sign']);	 
		 unset($get['_URL_']);	
		 foreach($del_get as $v) unset($get[$v]);//删除不需要的GET参数
		 $get_sign=$_GET['sign'];
		 foreach($get as $k=>$v) {
		 	 $string.=$v;
			 $getKey.=$k;
		 }		 
		 if(md5($string.$getKey.C('APP_KEY')) != $get_sign) return false;
		 if($overdue > -1){
		   if((time() - $get['random']) > $overdue) return false;
		 }
		 return true;	 
 }

/**如果图片不在返回默认图片,自动判断http地址
 * $imgPath  图片路径
 * $imgDefault 图片不在时的默认图片
 * retrun string
 * */
function showImg($imgPath,$imgDefault=''){
	if(empty($imgDefault)) $imgDefault=C('GOODS_IMG');
	if((strpos(strtoupper($imgPath),'HTTPS://') !==false) || (strpos(strtoupper($imgPath),'HTTP://') !==false))
	return $imgPath;
	if($imgPath){
		if(file_exists('./'.$imgPath)){
			return __ROOT__.'/'.$imgPath;
		}else{
			return $imgDefault;
		}		
	}else{
		return $imgDefault;
	}
}

/**
 * 显示http路径图片或根目录图片
 * */
function is_http_img($imgPath){
	if((strpos(strtoupper($imgPath),'HTTPS://') !==false) || (strpos(strtoupper($imgPath),'HTTP://') !==false))
	return $imgPath;
	return __ROOT__.'/'.$imgPath;
}

/**
 * 强制图片路径以http形式显示
 * */
function img_to_http($imgPath){
	if((strpos(strtoupper($imgPath),'HTTPS://') !==false) || (strpos(strtoupper($imgPath),'HTTP://') !==false))
	return $imgPath;
	return 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/'.$imgPath;	
}

/**
 * 清除JS、PHP脚本代码
 * */
function cleanScript($string){
   $string=preg_replace('/\<(.*)script(.*)\<(.*)\/script(.*)\>/Uis','',$string);
   $string=preg_replace('/\<\?(.*)\?(.*)\>/Uis','',$string);
   $string=preg_replace('/\<php(.*)\<\/php\>/Uis','',$string);
   return $string;
} 

/*获取新浪IP接口
*return array();
*/
function get_sina_ip_to_city(){	
	$ip=get_ip();
	if($ip=='127.0.0.1') $ip='125.95.26.15';
	$getIpUrl='http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip;
	$ipString=file_get_contents($getIpUrl);
	$str=preg_match_all('/\{(.*)\}/',$ipString,$iparr);
	$ipar=json_decode($iparr[0][0],true);	
    return $ipar;	
}

/**
*是否按城市区县显示内容
*/
function emptyCity($alias=''){
	   if(!C('IS_GOODS_CITY_SELECT')) return '';
	   $cookCityId=cookie(C('COOKIE_CITY_ID'));
	   $cookDistrictId=cookie(C('COOKIE_DISTRICT_ID'));		
	   if($alias){
		   $district=$alias.'.district';
		   $city=$alias.'.city';
	   }else{
		   $district='district';
		   $city='city';		   
	   }
	   if($cookDistrictId){
		   return $where=" AND {$district} = {$cookDistrictId} ";
	   }else  if($cookCityId){
		   return $where=" AND {$city} = {$cookCityId} ";
	   }
	   return '';	
}

/**
 * langReplace($repStr,$L)
 * $repStr  要替换的字符串
 * $L          大L()函数
* */
function langReplace($repStr,$L){
	return preg_replace('/\{.*\}/isU',$repStr,$L);	
}

/**
 * isL($L,$defaultString)
 * $L                     大L()函
 * $defaultString  默认字符串
 * */
function isL($L,$defaultString){
	if(C('LANG_SWITCH_ON')){
	   return $L;
	}else{
	   return $defaultString;	
	}
}

/**
*替换L()获取指定的内容所有要替换的特殊符号 %d
* $L                     大L()函
*$defaultString  默认字符串
*$rep_content   要替换的内容
*/
function replace_isl($L,$defaultString,$rep_content){
    $str=isL(L($L),$defaultString);
	return str_replace('%d',$rep_content,$str);
}
 
/**
*用户名中间以*号显示
*$string  字符串
*$num    字符串两边各取显示多少位
*$code   字符编码
*/
function center_tsterisk($string,$num,$code='utf-8'){
	$leng=0;
    if(function_exists('mb_strlen')){
        $leng = mb_strlen($string,$code);
    }
    else {
        preg_match_all("/./u", $string, $ar);
        $leng = count($ar[0]);
    } 
	if($leng < 3) {
		if($leng==2) return mb_substr($string, 0, 1, $code).'***';
		return $string.'***';
	}
	$left=mb_substr($string, 0, $num, $code);//左边
	$right=mb_substr($string, -$num, $num, $code);//右边
    return $left.'***'.$right;
}

 /**
*例计时
*$andTime  例计时结束时间截
*/
function case_time($andTime){
	$span=$andTime - time();
	$hour=intval($span/60/60); //小时
	$minute=intval(($span-$hour*60*60)/60);//分钟
	$second=($span-$hour*60*60)-$minute*60;//秒
	$expire=$hour."小时".$minute."分".$second."秒";
	return $expire;
}

/**
* 判断提交过来的URL是否合法
*/
 function nonlicet_url(){
	 return true;
	$_alien=$_SERVER["HTTP_REFERER"];//获取来路的URL
	if (empty($_alien)){	
       echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">'."\n";
       echo '<html><head>'."\n";
       echo '<title>404 Not Found</title>'."\n";
       echo '</head><body>'."\n";
       echo '<h1>Not Found</h1>'."\n";
       echo '<p>The requested URL http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' was not found on this server.</p>'."\n";
       echo '<hr>'."\n";
       echo $_SERVER["SERVER_SOFTWARE"]."\n";
       echo '</body></html>';
       exit();
	}else{
       $_localhosturl=$_SERVER["SERVER_NAME"];//本地服务器域名
       $url= $_localhosturl;   //获取完整本地URL
       $str= str_replace("http://","",$url);  //去掉本地URL http://
       $strdomain = explode("/",$str);   // 以“/”分开本地URL成数组
       $domain = $strdomain[0];     ////取得本地URL第一个“/”以前的字符		
       $alien_str= str_replace("http://","",$_alien);  //来路的URL去掉http://
       $alien_str_strdomain = explode("/",$alien_str);   // 以“/”分开成数组
       $alien_domain = $alien_str_strdomain[0];      //取第一个“/”以前的字符
       if ($domain != $alien_domain){ //来路域名不等于本地域名      
           echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">'."\n";
           echo '<html><head>'."\n";
           echo '<title>404 Not Found</title>'."\n";
           echo '</head><body>'."\n";
           echo '<h1>Not Found</h1>'."\n";
           echo '<p>The requested URL http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].' was not found on this server.</p>'."\n";
           echo '<hr>'."\n";
           echo $_SERVER["SERVER_SOFTWARE"]."\n";
           echo '</body></html>';
           exit();
       }
	}
	
}

 /**
 * 获取当前页面完整URL地址
 */
 function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
 }

/**
 * 判断是否是手机访问网
 * $murl        string   跳转的手机URL网站
 *$ipad_url  string   跳转的手机ipad网站
 *return                     当两个URL为空时返回值： 0 PC访问，1 手机访问，2 ipad 访问
 */
function is_mobile($murl='',$ipad_url=''){
	  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	  $ipadchar = "/(ipad|ipad2)/i";
      $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
     if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){ //手机访问
		    if ($murl) {
		        header("Location: $url\n");
		        exit;
		    }else{
		    	return 1;
		    }
     }else if(preg_match($ipadchar, $ua)){ //平板电脑访问
	       $location_ipad=$ipad_url ? $ipad_url : $murl;
		   if($location_ipad){
		        header("Location: $location_ipad\n");
		        exit;			   
		  }else{
			  return 2;
		  }
	 }
	 return 0;
}

/**
 * 访问PC页面自动跳到手机板相应页面
 * */
function header_mobile(){
	if(is_mobile()){
    	$str=get_url();
		$hot=$_SERVER['HTTP_HOST'].__ROOT__;		
		$url=str_replace($hot, $hot.'/'.APP_MOBILE, $str);
		header("Location: $url\n");	
	}
}

/**
 *  accesss($str)根据RBAC权限加载导航调用
 *accesss('Rbac')为检查模块权限
 *accesss('Rbac/node')为检查方法权限
 * @param string $str 字符串('Rbac/node')
 * @return true
 */
function access($str){
	if(session('username')==C('RBAC_SUPERADMIN')) {return true;}//如果是超级程序员
	$access=strtoupper(trim($str));
	 if(preg_match_all('/\//',$access,$a)){
		   foreach($_SESSION['_ACCESS_LIST'] as $k=>$v){
			 foreach($v as $m_k=>$m){
				  foreach($m as $m_k_k=>$m_v){
					  if($m_k.'/'.$m_k_k==$access){
						  return true;
					  }
				 }
			 }	
		 }
   }else{
	   foreach($_SESSION['_ACCESS_LIST'] as $k=>$v){
		 foreach($v as $m_k=>$m){
			   if($m_k==$access){
				   return true;
			   }
			}	
	    }		   
   }
}



/**
*pwd_sha1($pwd,$length = 4) sha1 加密方式
*$pwd string 字符串
*return array
*键名$pwdArr['SHA1']  直接保存在用户密码
*键名$pwdArr['RANDOM']  四位随机码将保存在表中，登录时再比较
*调用:$pwdArr=pwd_sha1('123456');
*echo $pwdArr['SHA1'];
echo $pwdArr['RANDOM'];
*
*/
function pwd_sha1($pwd,$length = 4){
 if($pwd){	
	$num=randCode(); //随机生四位数字
	$pwdArr['RANDOM']=$num;
	$pwdArr['SHA1']=sha1(sha1($pwd).$num);
	return $pwdArr;
 }else{
    echo 'Function generate_word($pwd,$length = 4) Parameter:error';
 }
}

/**
  +----------------------------------------------------------
 * 生成随机字符串
  +----------------------------------------------------------
 */
 function randCode() {
     $str1=rand(1,9);
	 $str2=rand(3,8);
	 $str3=rand(1,7);
	 $str4=rand(0,9);
    return $str1.$str2.$str3.$str4;
 }


/**
*compare_sha1($sendPwd,$random,$savePwd='')验证密码,调用if(compare_sha1('发送过来的密码','数据库保存的随机码','数据库保存的密码'));
*$sendPwd string 字符串，发送过来的密码
*$random num  用户表数据库保存的随机码
*$savePwd  string 用户表数据库保存的密码
*return true
*/

function compare_sha1($sendPwd,$random,$savePwd=''){
	
	 if(sha1(sha1($sendPwd).$random)==$savePwd){
		 return true;
	 }else{
	     return false;
	 }
	
}


/*
*@sqlTime($date)
*@access  publics
*@param   string  $date 一个日期格式(2014-4-16)得到查找开始的时间戳$sqlTime["startTime"] 和结束的时间戳$sqlTime["endTime"]
*@$dat2=strtotime('2014-04-16');// 23:59:00
*/
function sqlTime($date){
	$date=str_replace("\\","-",$date);	
	$date=str_replace("/","-",$date);	
	$sqlTime=array();
	$startTime=strtotime($date);
	$sqlTime["startTime"]=$startTime;
	$sqlTime["endTime"]=$startTime + 86400;
	return $sqlTime;
}

/**
*把回车键符号替换成豆号
**/
function rep_r_n($data){
	$str=str_replace("\r\n",',',$data);
	return $str;	
}

/**
*把回车键符号替换成<br/>
**/
function rep_to_br($data){
	$str=str_replace("\r\n",'<br/>',$data);
	return $str;	
}

/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($str, $length = 0, $charset='UTF-8',$append = true){
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength) {
        return $str;
    }
    elseif ($length < 0) {
        $length = $strlength + $length;
        if ($length < 0) {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr')){
        $newstr = mb_substr($str, 0, $length, $charset);
    }
    elseif (function_exists('iconv_substr')){
        $newstr = iconv_substr($str, 0, $length, $charset);
    }
    else {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

    if ($append && $str != $newstr) {
        $newstr .= '...';
    }

    return $newstr;
}

/**
 * 获得客户端的真实IP地址
 *
 * @access  public
 * @return  string
 */
function get_ip(){
    static $realip = NULL;
    if ($realip !== NULL) {
        return $realip;
    }
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);

                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }else {
            if (isset($_SERVER['REMOTE_ADDR'])){
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    }else{
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif (getenv('HTTP_CLIENT_IP')){
            $realip = getenv('HTTP_CLIENT_IP');
        }else{
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}

/**
 * 获取服务器的ip
 *
 * @access      public
 *
 * @return string
 **/
function server_ip(){
    static $serverip = NULL;

    if ($serverip !== NULL){
        return $serverip;
    }

    if (isset($_SERVER)){
        if (isset($_SERVER['SERVER_ADDR'])){
            $serverip = $_SERVER['SERVER_ADDR'];
        }else{
            $serverip = '0.0.0.0';
        }
    }else{
        $serverip = getenv('SERVER_ADDR');
    }

    return $serverip;
}


/**
 * 计算字符串的长度（汉字按照两个字符计算）
 *
 * @param   string      $str        字符串
 *
 * @return  int
 */
function str_len($str){
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
    if ($length) {
        return strlen($str) - $length + intval($length / 3) * 2;
    } else{
        return strlen($str);
    }
}

/**
 * 文件或目录权限检查函数
 *
 * @access          public
 * @param           string  $file_path   文件路径
 * @param           bool    $rename_prv  是否在检查修改权限时检查执行rename()函数的权限
 *
 * @return          int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
 *                          返回值在二进制计数法中，四位由高到低分别代表
 *                          可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
 */
function file_mode_info($file_path){
    /* 如果不存在，则不可读、不可写、不可改 */
    if (!file_exists($file_path)) {
        return false;
    }

    $mark = 0;

    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        /* 测试文件 */
        $test_file = $file_path . '/cf_test.txt';

        /* 如果是目录 */
        if (is_dir($file_path)) {
            /* 检查目录是否可读 */
            $dir = @opendir($file_path);
            if ($dir === false){
                return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
            }
            if (@readdir($dir) !== false) {
                $mark ^= 1; //目录可读 001，目录不可读 000
            }
            @closedir($dir);

            /* 检查目录是否可写 */
            $fp = @fopen($test_file, 'wb');
            if ($fp === false){
                return $mark; //如果目录中的文件创建失败，返回不可写。
            }
            if (@fwrite($fp, 'directory access testing.') !== false){
                $mark ^= 2; //目录可写可读011，目录可写不可读 010
            }
            @fclose($fp);

            @unlink($test_file);

            /* 检查目录是否可修改 */
            $fp = @fopen($test_file, 'ab+');
            if ($fp === false){
                return $mark;
            }
            if (@fwrite($fp, "modify test.\r\n") !== false) {
                $mark ^= 4;
            }
            @fclose($fp);

            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false) {
                $mark ^= 8;
            }
            @unlink($test_file);
        }
        /* 如果是文件 */
        elseif (is_file($file_path)) {
            /* 以读方式打开 */
            $fp = @fopen($file_path, 'rb');
            if ($fp) {
                $mark ^= 1; //可读 001
            }
            @fclose($fp);

            /* 试着修改文件 */
            $fp = @fopen($file_path, 'ab+');
            if ($fp && @fwrite($fp, '') !== false) {
                $mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
            }
            @fclose($fp);

            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false) {
                $mark ^= 8;
            }
        }
    }else{
        if (@is_readable($file_path)){
            $mark ^= 1;
        }

        if (@is_writable($file_path)) {
            $mark ^= 14;
        }
    }

    return $mark;
}


/**
 *  将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 *
 * @access  public
 * @param   string       $str         待转换字串
 *
 * @return  string       $str         处理后字串
 */
function make_semiangle($str){
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                 'ｙ' => 'y', 'ｚ' => 'z',
                 '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
                 '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
                 '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
                 '》' => '>',
                 '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
                 '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
                 '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
                 '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
                 '　' => ' ');

    return strtr($str, $arr);
}


/**
 *跟根 IP地址获取真实IP归属地(城市)函数
 *需要一个IP数据库文件，当前使用(qqwry.dat)
 * @access  public
 * @param   string      $ip        ip地址
 * @return  string
 */
function city_ip($ip) {
	
	error_reporting(E_ALL^E_NOTICE);
	//IP数据文件路径
	$dat_path = './Data/CityDb/qqwry.dat';

	//检查IP地址
	if(!preg_match("/^([0-9]{1,3}.){3}[0-9]{1,3}$/", $ip)){
		return 'IP Address Invalid';
	}
	//打开IP数据文件
	if(!$fd = @fopen($dat_path, 'rb')){
		return 'IP date file not exists or access denied';
	}

	//分解IP进行运算，得出整形数
	$ip = explode('.', $ip);

	$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

	//获取IP数据索引开始和结束位置
	$DataBegin = fread($fd, 4);
	$DataEnd = fread($fd, 4);
	$ipbegin = implode('', unpack('L', $DataBegin));
	if($ipbegin < 0) $ipbegin += pow(2, 32);
	$ipend = implode('', unpack('L', $DataEnd));
	if($ipend < 0) $ipend += pow(2, 32);
	$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
	 
	$BeginNum = 0;
	$EndNum = $ipAllNum;

	//使用二分查找法从索引记录中搜索匹配的IP记录
	while($ip1num>$ipNum || $ip2num<$ipNum) {
		$Middle= intval(($EndNum + $BeginNum) / 2);

		//偏移指针到索引位置读取4个字节
		fseek($fd, $ipbegin + 7 * $Middle);
		$ipData1 = fread($fd, 4);
		if(strlen($ipData1) < 4) {
			fclose($fd);
			return 'System Error';
		}
		//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
		$ip1num = implode('', unpack('L', $ipData1));
		if($ip1num < 0) $ip1num += pow(2, 32);
		 
		//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
		if($ip1num > $ipNum) {
			$EndNum = $Middle;
			continue;
		}
		 
		//取完上一个索引后取下一个索引
		$DataSeek = fread($fd, 3);
		if(strlen($DataSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
		fseek($fd, $DataSeek);
		$ipData2 = fread($fd, 4);
		if(strlen($ipData2) < 4) {
			fclose($fd);
			return 'System Error';
		}
		$ip2num = implode('', unpack('L', $ipData2));
		if($ip2num < 0) $ip2num += pow(2, 32);

		 
		if($ip2num < $ipNum) {
			if($Middle == $BeginNum) {
				fclose($fd);
				return 'Unknown';
			}
			$BeginNum = $Middle;
		}
	}

	$ipFlag = fread($fd, 1);
	if($ipFlag == chr(1)) {
		$ipSeek = fread($fd, 3);
		if(strlen($ipSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
		fseek($fd, $ipSeek);
		$ipFlag = fread($fd, 1);
	}

	if($ipFlag == chr(2)) {
		$AddrSeek = fread($fd, 3);
		if(strlen($AddrSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(2)) {
			$AddrSeek2 = fread($fd, 3);
			if(strlen($AddrSeek2) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
			fseek($fd, $AddrSeek2);
		} else {
			fseek($fd, -1, SEEK_CUR);
		}

		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr2 .= $char;

		$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
		fseek($fd, $AddrSeek);

		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;
	} else {
		fseek($fd, -1, SEEK_CUR);
		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;

		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(2)) {
			$AddrSeek2 = fread($fd, 3);
			if(strlen($AddrSeek2) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
			fseek($fd, $AddrSeek2);
		} else {
			fseek($fd, -1, SEEK_CUR);
		}
		while(($char = fread($fd, 1)) != chr(0)){
			$ipAddr2 .= $char;
		}
	}
	fclose($fd);

	//最后做相应的替换操作后返回结果
	if(preg_match('/http/i', $ipAddr2)) {
		$ipAddr2 = '';
	}
	$ipaddr = "$ipAddr1 $ipAddr2";
	$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
	$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
	$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
	if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
		$ipaddr = 'Unknown';
	}
	
	return mb_convert_encoding($ipaddr,"UTF-8","GBK");
}

/**
*movePath($ditorContent)
* @处理保存百度编辑器图片路径，实现移植根目录不影响路径
* @string  $ditorContent 编辑器变量
* @return  string
*/
  function movePath($ditorContent){
	 $ROOT=__ROOT__;  
    $ditor = str_replace($ROOT,'__ROOT__',$ditorContent);
	return $ditor;
  }

/**
*returnPath($ditorContent)
* @提取显示内容返回百度编辑器原有图片路径
* @string  $ditorContent 编辑器变量
* @return  string
*/
   function returnPath($ditorContent){
	 $ROOT=__ROOT__;  
    $ditor = str_replace('__ROOT__',$ROOT,$ditorContent);
	return $ditor;
  }

//判断奇数，是(返回TRUE),否(FALSE)
function is_odd($num){
   return (is_numeric($num) & ($num&1));
}

//判断偶数，是(返回TRUE),否(FALSE)
function is_even($num){
	return (is_numeric($num)&(!($num&1)));
}

/*
 * 精确时间间隔函数 from_time($time,$str)
 * $time 发布时间 如 1356973323
 * $str 输出格式 如 Y-m-d H:i:s
 * 半年的秒数为15552000，1年为31104000，此处用半年的时间
 */
function from_time($time,$str){
    isset($str)?$str:$str='Y-m-d';
    $way = time() - $time;
    $r = '';
    if($way < 60){
        $r = '刚刚';
    }elseif($way >= 60 && $way <3600){
        $r = floor($way/60).'分钟前';
    }elseif($way >=3600 && $way <86400){
        $r = floor($way/3600).'小时前';
    }/*elseif($way >=86400 && $way <2592000){
        $r = floor($way/86400).'天前';
    }elseif($way >=2592000 && $way <15552000){
        $r = floor($way/2592000).'个月前';
    }*/else{
        $r = date("$str",$time);
    }
    return $r;
}
/*
 * 当天的文章设为新文章 news()
 * $time 发布时间 如 1356973323
 */
 function news($time){
   $way = time() - $time;
   if($way <86400){
	   return true;
   }
 }

/**
 * 提取字符串中的数字或去除字符串的数字
 * get_str_num($str,$num=true)
 * @param string $str
 * @param $num $num=true 提取字符串中的数字，否则去除字符串的数字
 */
function get_str_num($str,$num=true){
    if($num){        
      for ($i=0; $i < strlen($str); $i++) {
        if(is_numeric($str[$i])){
          $newstr.=$str[$i];
        }    
      }          
    }else{
      for ($i=0; $i < strlen($str); $i++) {
        if(!is_numeric($str[$i])){
          $newstr.=$str[$i];
        }    
      }              
    }
    return $newstr;
}

/**
 * 多维数组排序
 *multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC)
 * @param array $multi_array 多维数组
 * @param string  $sort_key 数组中的键值排序
 * @param sorting SORT_ASC 正序 SORT_DESC返序,这个参数不带引号
 * reutnr array
 */
function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
                $key_array[] = $row_array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_array,$sort,$multi_array);
    return $multi_array;
}

 /**
*@无限级分类组合无限下级多维数组
* @unlimitedForInId($cate)
* @access public
* @param $cate  传结果集
* @param $pidString  传表父ID字段名，默认nav_pid
* @param $idString  传表ID字段名，默认nav_id
* @param $childString  下级多维数组键名，默认child
* @return array
 */		  
function unlimitedForLayer($cate,$pidString='nav_pid',$idString='nav_id',$childString='child',$pid=0){
     $arr=array();
	 $i=0;
     foreach($cate as $k => $v){
       if($v[$pidString]==$pid){
          $v[$childString]=unlimitedForLayer($cate,$pidString,$idString,$childString,$v[$idString]);
          $arr[$i]=$v;
		  $i++;
       }
     }
     return $arr;
   }
   
   
/*创建多级文件夹*/
function create_dir($dir) {  
	if(!is_dir($dir)) {  
		if(!create_dir(dirname($dir))){  
			return false;  
		}  
		if(!mkdir($dir,0777)){  
			return false;  
		}  
	}  
	return true;  
} 		
/****商铺模板操作开始*******************************************************/	
//删除多级文件夹，如果只有一级则删除一级rmdirs('111/222/333') 
function rmdirs($dir){  
	if (is_dir($dir)){
	$d = dir($dir);  
	while (false !== ($child = $d->read())){  
		if($child != '.' && $child != '..'){  
		if(is_dir($dir.'/'.$child))  
			rmdirs($dir.'/'.$child);  
			else unlink($dir.'/'.$child);  
		}  
	}  
	$d->close();  
	rmdir($dir);  
   }
}

/*遍历目录商铺模板*/
function getDir($dir){
	$dirArr=array();
	if (is_dir($dir)){
	$d = dir($dir);  
	while (false !== ($child = $d->read())){  
		if($child != '.' && $child != '..'){  
		if(is_dir($dir.'/'.$child))  
			$dirArr[]=  $child;
		}  
	}  
	$d->close();  
	rmdir($dir);  
   }
   return $dirArr;
}

/*获取模板文件 */
function getDirFile($dir){  
    $dirFile=array();
	if (is_dir($dir)){
	$d = dir($dir);  
	while (false !== ($child = $d->read())){  
		if($child != '.' && $child != '..'){  
			if(!is_dir($dir.'/'.$child)){
				$dirFile[]=$child;
			}		
		}  
	}  
	$d->close();  
   }
   return $dirFile;
}

 /*生成商铺模板
 *$src   要复制目录
 *$dst    要复到的目录路径
 */
 function recurse_copy($src,$dst) { 
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
*repInclde($str,$type) 针对DWZ不搛容 处理头引入标签
*$str      模板内容
*$type   true  添加/; false 去除/
*/
function repInclde($str,$type){
	$pattern="/\<include(.*)\>/Uis";
	$nv='';
	preg_match_all($pattern,$str,$out);
	foreach($out[0] as $k=>$v){
	   if(!$type){
		   $nv=str_replace('/>','>',$v); 
	   }else{
		   if(!preg_match('/\/\>/',$v)){
			   $nv=str_replace('>','/>',$v);
		   }
	   }
	   if($nv) $str=str_replace($v,$nv,$str);
	}
	return $str;
}

/**
*是否有GET参数
*$name_get GET参数名称
*$type  如果1；第一个参数；否则其它；
*/
function is_get($name_get,$type){
   $str='';
   if($_GET[$name_get] !=''){
       if($type){
	      $str='/'.$name_get.'-'.$_GET[$name_get];
	   }else{
	      $str='-'.$name_get.'-'.$_GET[$name_get];
	   }
   }
   return $str;
}

//计算整个目录文件大小
//调用  getDirSize('../cms5.1');
function getDirSize($dir){
	$handle = opendir($dir);//打开文件流
	while (false!==($FolderOrFile = readdir($handle))){//循环判断文件是否可读 
		if($FolderOrFile != "." && $FolderOrFile != ".."){
			if(is_dir("$dir/$FolderOrFile")){//判断是否是目录
				  $sizeResult += getDirSize("$dir/$FolderOrFile");//递归调用
			}else{
				   $sizeResult += filesize("$dir/$FolderOrFile");
			}
		}
	}
	closedir($handle);//关闭文件流
	return $sizeResult;//返回大小
} 

// 单位自动转换函数
//调用  getRealSize(getDirSize('../cms5.1'));
function getRealSize($size){ 
	$kb = 1024; // Kilobyte
	$mb = 1024 * $kb; // Megabyte
	$gb = 1024 * $mb; // Gigabyte
	$tb = 1024 * $gb; // Terabyte 
	if($size < $kb){ 
	      return $size." B";
	}else if($size < $mb){ 
	      return round($size/$kb,2)." KB";
	}else if($size < $gb){ 
	     return round($size/$mb,2)." MB";
	}else if($size < $tb){ 
	     return round($size/$gb,2)." GB";
	}else{ 
	     return round($size/$tb,2)." TB";
	}
}

/****商铺模板操作结束*******************************************************/

 
 /**
  * Snoopy.class.php 表单提交 snoopy_send_form($url,$data,$cookie['cookie'],$cookie['ip'],$sid); //提交文章
  * $url 表单提交的地址
  * $data  表段数组
  * $cookie  登录的COOKIE值获取snoopy_login() 返回的COOKIE
  * $ip 要伪装ip
  * $sid  sessionId 如果登录用户写session
  */
 function snoopy_send_form($url,$data,$referer_url,$cookie='',$ip='',$sid=''){
 	$sid=explode('=', $sid); //取seesionid键名和键值
 	import('Class.Snoopy',APP_PATH);
	$snoopy=new snoopy();
    $submit_url=$url;	//提交表单的URL
	$snoopy->referer =$referer_url ? $referer_url : $url; //伪装来源页地址 http_referer
	//if($sid) $snoopy->cookies[$sid[0]] =$sid[1]; //伪装sessionid	
	$snoopy->agent = $_SERVER['HTTP_USER_AGENT']; //伪装浏览器,显示浏览器头信息
	$snoopy->rawheaders["Pragma"] = "no-cache"; //cache 的http头信息
	$snoopy->maxredirs = 0; //重定向次数
	$snoopy->expandlinks = true;
	if($ip) $snoopy->rawheaders["X_FORWARDED_FOR"] = $ip; //伪装ip
	if($cookie ) $snoopy->rawheaders["COOKIE"] =$cookie;
	$snoopy->submit($submit_url, $data); //提交
	return $snoopy->results;

 }	
 
 /**
 * 获取浏览器语言
 * 简体中文(zh-cn),繁體中文(zh-tw),English英文(en-us),日本语(Japanese)
 */
 function get_browser_lang(){
     $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4); //只取前4位，这样只判断最优先的语言。如果取前5位，可能出现en,zh的情况，影响判断。 
     if (preg_match("/zh-c/i", $lang)) 
          $returnLang='zh-cn'; //简体中文
     else if (preg_match("/zh/i", $lang)) 
          $returnLang ='zh-tw'; //繁體中文
     else if (preg_match("/en/i", $lang)) 
          $returnLang ='en-us'; //English 英文
     else if (preg_match("/fr/i", $lang)) 
          $returnLang ="fr-fr"; //French  法文
     else if (preg_match("/de/i", $lang)) 
          $returnLang = "de-de"; //German //德国
     else if (preg_match("/jp/i", $lang)) 
          $returnLang ='ja-jp'; //Japanese日本语
     else if (preg_match("/ko/i", $lang)) 
          $returnLang ='ko-kr';  //Korean朝鲜
     else if (preg_match("/es/i", $lang)) 
          $returnLang ='ca-es'; //Spanish西班牙
     else if (preg_match("/sv/i", $lang)) 
          $returnLang ='sv-se';  //Swedish 瑞典
    else if (preg_match("/vi/i", $lang)) 
	     $returnLang ='vi-vi';  //越南语      
   else $returnLang = C('DEFAULT_LANG');  //默认语言
   return $returnLang;
} 



//在客户端生成一个唯一的购物车标识，并写入访问的时间
function setKey(){
  	if(!$_COOKIE['user_key'] && C('WHETHER_TO_ALLOW')){
		$userKey=session_id();
		cookie('user_key',$userKey,86400 * 360);
		cookie('user_time',time(),86400 * 360);
    }
}

//返回用户购物唯一标识
function return_key(){
	//允许未登录购物
	if(C('WHETHER_TO_ALLOW') && $_COOKIE['user_key']) return $_COOKIE['user_key'];
	return false;
}
  
//获取购物车COOKIE的过期时间
function getExpiredTime(){
  	if($_COOKIE['user_time']){
  		$times=time();
		$expiredTime=C('CARTRETAIN') * 86400;
		if(($times - $_COOKIE['user_time']) > $expiredTime){
			cookie('user_time',$times,86400 * 360);
			$arr['expiredDbTime'] = $times - $expiredTime;
			$arr['userKey']=$_COOKIE['user_key'];
			return $arr;
		}
  	}
}
  
/**
*preset_time($startTime,$endTime)
*$startTime  开始的时间以24小时制；如从上午(09:00:00) 九时，零分，零秒起
*$endTime   结束的时间以24小时制；如到下午(18:00:00) 六时，零分，零秒止
*return bool
*使用示例 preset_time('09:00:00','18:00:00'); 开放时间从上午(09:00:00)到下午(18:00:00)
*/
function preset_time($startTime,$endTime){
	$current_time=time(); //当前时间戳
	$time1 = strtotime(date('Y-m-d').' '.$startTime);  //规定时间内开始
	$time2 = strtotime(date('Y-m-d').' '.$endTime);  //规定时间内结束
	if($current_time >= $time1 && $current_time <=$time2){
		return true;
	}else{
		return false;
	}
}  

/**
检测字符串是否包含URL地址
$str  字符串
$HTML 是否把字符去除所的HTML,默认不去掉
return  Buer 
*/
function chk_url($str,$HTML=false){
    if($HTML)$str=strip_tags($str); 
	$pattern = "/(www\.|http:\/\/)(\w+)\.(com|net|org|gov|cc|biz|info|cn|hk)/isU";
	if(preg_match($pattern, $str)){
	   return true;
	}	
}

/**
*sectionTime($numMonth) 时间段距间单位(月)
*$numMonth  月数量；如3个月内的数据sectionTime(3);
*retrun array
*/
function sectionTime($numMonth){
	$times=time();
	$three = $times - ((86400 * 30) * $numMonth);
	return array($three,$times);
}

/**
 * 邮件发送函数
 *$receive_email  接收邮件帐号
 *$title   显示标题
 *$content 邮件内容支持HTML
 *  * $all true循环批量发送,false指定单个发送;批量发送需要引入PHPMailer插件
 *send_mail('3307734893@qq.com','这里是邮件标题','<h2>测试邮件发送</h2><a href="http://www.baidu.com">点击这里跳转</a>');
 */
function send_mail($receive_email,$title,$content,$all=false) {
	    if(!$all){
		    require(APP_PATH.'Plugins/PHPMailer/PHPMailerAutoload.php');
			require(APP_PATH.'Plugins/PHPMailer/class.phpmailer.php');
		}
        $mail = new PHPMailer(); 
        $mail->IsSMTP();
        $mail->Host = "smtp.yuegekeji.cn";//"smtp.qq.com";//发件邮相服务器
        $mail->SMTPAuth = true;
        $mail->Username = "ljg@yuegekeji.com";//"3106722693@qq.com"; //发件邮相帐号
        $mail->Password = "Yuegekeji002";//"woaini520914"; //发件邮相密码
        $mail->Port=25;
        $mail->From = "ljg@yuegekeji.com";//"3106722693@qq.com";  //发件邮相帐号
        $mail->FromName = 'YOUDI WU Customer Service';  //发件邮相名称
        $mail->CharSet = 'utf-8';
        $mail->AddAddress($receive_email);
        $mail->IsHTML(true);
        $mail->Subject = $title;
        $mail->Body  =$content; //邮件内容支持HTML
        return $mail->Send();
}

/**
 * 发送邮件方法
 * @param $to：接收者
 * @param $title：标题
 * @param $content：邮件内容
 * @return bool true:发送成功 false:发送失败
 */
function send_mail2($to,$title,$content){
    //引入PHPMailer的核心文件
    require(APP_PATH.'Plugins/phpmailer_2017-11/src/PHPMailer.php');
    require(APP_PATH.'Plugins/phpmailer_2017-11/src/SMTP.php');	
	require(APP_PATH.'Plugins/phpmailer_2017-11/src/Exception.php');	
    $mail = new \PHPMailer\PHPMailer\PHPMailer();//实例化PHPMailer核心类
    //$mail->SMTPDebug = 1;//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->isSMTP();//使用smtp鉴权方式发送邮件
    $mail->SMTPAuth=true;//smtp需要鉴权 这个必须是true
    $mail->Host = "smtp.126.com";//链接qq域名邮箱的服务器地址
    $mail->SMTPSecure = 'ssl';//设置使用ssl加密方式登录鉴权
    $mail->Port = 465;//设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
    $mail->CharSet = 'UTF-8';//设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->FromName = 'YOUDI WU Customer Service';//设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->Username =  "yuegewangluo@126.com";//smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Password = "Zdf87102517s";//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）【非常重要：在网页上登陆邮箱后在设置中去获取此授权码】
    $mail->From =  "yuegewangluo@126.com";//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->isHTML(true);//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->addAddress($to);//设置收件人邮箱地址
    $mail->Subject = $title;//添加该邮件的主题
    $mail->Body = $content;//添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    //简单的判断与提示信息
    if($mail->send()) {
        return true;
    }else{
        return false;
    }
}

/**
*@ path_padding($id,$pid,$path,$padding) 无限级分类路径和/缩进处理
* @param $id   ID
* @param $pid  PID
* @param $path  表的路径字段名
* @param $padding  表的左缩进字段名
*@return array
*/
function path_padding($id,$pid,$path,$padding){
		if($_POST[$path] !='0' && $_POST[$pid] !='0'){
			$_POST[$path]=$_POST[$path].'-'.$id;
		    $_POST[$padding]=(count(explode('-',$_POST[$path]))-1)*15;//缩进增加一个数组值
		}else if($_POST[$path]=='0' && $_POST[$pid] !='0'){
		   	$_POST[$path]=$_POST[$path].'-'.$_POST[$pid].'-'.$id;
			 $_POST[$padding]=(count(explode('-',$_POST[$path]))-1)*15;//缩进增加一个数组值;
		}else{
		   	$_POST[$path]=0 .'-'.$id;
		   $_POST[$padding]=0;//缩进增加一个数组值;
		}
		return $_POST;
}

/**
*递归重组节点信息为多维数组
*@param $node 要处理的节点数组
*@param $pid   父级ID
*@
*/
function node_merge($node,$pid=0){
	$arr=array();
	foreach($node as $v){
		if($v['pid']==$pid){
		   $v['child']=node_merge($node,$v['id']);
		   $arr[]=$v;
		}
	}
	return $arr;
}

/*去掉标签、空格、单引号、双引号*/
function emptyHtml($str){
    $returnString=strip_tags($str);
	$returnString=trim($returnString);
	$returnString=quotes($returnString);
	return $returnString;
}

/**
*去掉单引号、双引号
*/
function quotes($str){
   $str=str_replace('\'','',$str); 
   $str=str_replace('"','',$str); 
   return $str;
}

/*拼装IN(1,2,3,4)*/
function in_id($arr,$field){
	$inId='';
   foreach($arr as $k=>$v){
	   $inId.=$v[$field].',';
   }
   return rtrim($inId,',');
}

/*会员是否登录*/
function is_login(){
	$level=$_SESSION[C('SE_USER_LEVEL')];
	$userId=$_SESSION[C('SE_USER_ID')];
	$userName=$_SESSION[C('SE_USER_NAME')];
	$headPic=$_SESSION[C('SE_USER_HEAD_PIC')];
	$userIP=$_SESSION[C('SE_USER_IP')];
	$login=$_SESSION[C('SE_USER_LIGON_TIEM')];
    if(($level >=0) && $userId && $userName && $headPic && $userIP && $login) return true;
	//session 没有值找COOKIE
	import('Class.CookieUser',APP_PATH);
	$user=new CookieUser();
	$cookies=$user->getUser('');
	$level=$cookies[C('SE_USER_LEVEL')];
	$userId=$cookies[C('SE_USER_ID')];
	$userName=$cookies[C('SE_USER_NAME')];
	$headPic=$cookies[C('SE_USER_HEAD_PIC')];
	$userIP=$cookies[C('SE_USER_IP')];
	$login=$cookies[C('SE_USER_LIGON_TIEM')];	
    if(($level >=0) && $userId && $userName && $headPic && $userIP && $login) return true;
	return false;
}

/**
*写入登录信息
*$arrays   登录信息数组 array('id'=>1,'user_name'=>'黎剑光');
*$type  类型;true 写SESSION;false 写COOKIE
*$time    写COOKIE的保存时间
*/
function set_login($arrays,$type=true,$time=0){
    if($type){ //写SESSION
	    foreach($arrays as $k=>$v) $_SESSION[$k]=$v;
	}else{
		import('Class.CookieUser',APP_PATH);
		$user=new CookieUser();
		//COOKIE保存时间
		$user->expires=$time;
		//登录是否邦定IP
		$user->isBiotinge=true;
		//登录COOKIE
		$user->login($arrays);
    }
}

/*退出登录
*$arrays 退出信息数组 array('id','user_name','user_img');
*/
function unset_login($arrays){
	//删除SESSION
    foreach($arrays as $v) unset($_SESSION[$v]);
	//删除COOKIE
	import('Class.CookieUser',APP_PATH);
	$user=new CookieUser();
	$user->signOut();
}

/*获取登录信息*/
function getUserC($C=''){
	if($C) $se=$_SESSION[C($C)];
	if($se) return $se;
	import('Class.CookieUser',APP_PATH);
	$user=new CookieUser();    
	return $user->getUser(C($C));
}


/*URL参数获取token*/
function get_token_val(){
	$i=0;
	foreach($_SESSION[TOKEN_NAME] as $k=>$v){
		if($i==1) {
			$token=$k.'_'.$v;
			break;
		}
		$i++;
	}
	return $token;
}


/**
 * 生成json的提示
 */
 function json_code($code,$msg,$url='',$data=''){
	$json=array(
	   "statusCode"=>$code,
	   "message"=>$msg,
	   "url"=>$url,
	   "data"=>$data
	);
	die(json_encode($json));
 }
 
/*
 * Validation与自定义对话框div-dialog.js返回JSON值
 * $inputId  提示节点
 * $success 是否成功true,false
 * $msg   提示信息
 * $url     跳转URL
 * $_function  回调函数
 * $data  其它数据json格式
 * 返回信息[["submits",false,"用户名或密码错误","","",{'id':2,'is':3},""]]
 * */
function val_json($inputId,$success,$msg,$url='',$_function='',$data=''){
  $type=$success ? $type='true' :  $type='false';
  $data=is_array($data) ? json_encode($data) : '"'.$data.'"';
  $json='[["'.$inputId.'",'.$type.',"'.$msg.'","'.$url.'","'.$_function.'",'.$data.',"'.is_m().'"]]';
  die($json);
}

//获得字符串中的数字和.符号部分。如果第二个参数为false时只提取数字不提取.点符号
function getNum($str,$arrt=true){
  $num='';
  for($i=0; $i < strlen($str);$i++){
	 if($arrt){	
	   if(is_numeric($str[$i]) || $str[$i]=='.'){
		  $num.=$str[$i];
	   }
	 }else{
	   if(is_numeric($str[$i])){
		  $num.=$str[$i];
	   }		 
	 }
  }
  return $num;
}

/*过滤字符检索防止$_GET参数注入*/
function filterRequst($str){
	$str=emptyHtml($str);
	$str=str_replace(' ', '', $str); //空格
	$str=str_replace('　', '', $str); //TAB键
	$str=str_replace('=', '', $str);
	$str=str_replace('-', '', $str);
	return $str;
}

/*只能输入数字或字母*/
function is_abc_num($str){
    if(!preg_match("/^[a-zA-Z0-9\_\@\.]+$/i",$str)){
      return false;
    }else{
      return true;  
    }
}

/*验证手机号*/
function check_mobile($mobilephone){
	  if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobilephone)){ 
		 return true;
	  }else{
		 return false;
	  } 
}

/*验证座机电话*/
function check_telephone($telephone){
	 $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";
	 if(!preg_match($isMob,$tel) && !preg_match($isTel,$tel)){
	   return false;
	 }	
	 return true;
}

//只能输入数字
function is_nums($str){
    if(!preg_match("/^[0-9]+$/",$str)){
      return false;
    }else{
      return true;  
    }    
}

//只能输入数字和中横线
function num_line(){
    if(!preg_match("/^[0-9\-]+$/",$str)){
      return false;
    }else{
      return true;  
    }    	
}

/*判断邮件*/
function is_email($email){
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if (preg_match( $pattern, $email )){
		     return true;
		}else{
		     return false;
		}
}

/**
 *@getNextId($tableName)基于ThinkPHP 获取表下一个新增的ID
 * @access   public
 * @param    $tableName 传一个表名称
 * @return   int    
 * 
 */
function getNextId($tableName){
		$sql="SHOW TABLE STATUS LIKE'".$tableName."'";
		$idobj=M();
		$getId=$idobj->query($sql);
		return $getId[0]['Auto_increment'];
}

/**
*@ goods_num($goods_id,$prefix_name='MY') 生成商品维一编号
* @param $tableName   商品表名称，自动获取下一个新增的ID
* @param $prefix_name  编号前缀英文字母
*@return string
*/
function goods_num($tableName,$prefix_name='BL-') {
  $num='00000000';
  $getNextId=getNextId($tableName);
	$id=strlen($getNextId);
	$num=mb_substr($num,0,strlen($num)-$id). $getNextId;
	return $prefix_name.$num;
}	

/**
 * 生成唯一定单号
 * @param string $prefix_name 定单号前缀，默认为空
 * @return string
 */
 function order_num($prefix_name=''){
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    return $prefix_name.date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
 }

 /*生成带表主键的唯一单号
 *$tableName  表名称带表前缀
 *$modelObj    模型对像
 *$length          单号长度        
 */
 function pk_sn($tableName,$modelObj='',$length=12){
    $nextId=getNextId($tableName,$modelObj);
	$rand=my_rands($length);
	$nums=mb_substr($rand,0,strlen($rand)-strlen($nextId)).$nextId;
	return $nums;
 }


/*随码数*/
function my_rands($length=4){
        $chars = '0123456789';
        $random = '';
        $random1='';
		for ($i = 0; $i < $length - 1; $i++) {		  
			$random = $chars[ mt_rand(0, strlen($chars) - 1) ];
			if($i==0 && $random==0) $random=$random+1;
			$random1.=$random;
		}	
		return $random1.mt_rand(0,9);	   
}

/**
*判断文件类型
*file_type('./images/1.png',array('text','png','jpg'))
*$filename  文件路径
*$allowType  文件类型array('text','png','jpg');
*return  Boolean
*/
function file_type($filename,$allowType=array()){
    $file = fopen($filename, "rb");
    $bin = fread($file, 2); //只读2字节
    fclose($file);
    $strInfo = @unpack("C2chars", $bin);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    $fileType = '';
    switch ($typeCode){
        case 6063:
            $fileType = array('php');
            break;		
        case 7790:
            $fileType = array('exe');
            break;
        case 7784:
            $fileType = array('midi');
            break;
        case 8297:
            $fileType = array('rar');
            break;        
        case 8075:  //.zip文件或.docx
            $fileType = array('zip','docx');
            break;
        case 3780:
            $fileType = array('pdf');
            break;		
        case 208207: //.doc文件或.xls文件
            $fileType = array('doc','xls');
            break;			
        case 187183: //.txt文件或
            $fileType = array('txt');
            break;				
        case 255216:
            $fileType = array('jpg');
            break;
        case 7173:
            $fileType = array('gif');
            break;
        case 6677:
            $fileType = array('bmp');
            break;
        case 13780:
            $fileType = array('png');
            break;			
      //  default:
      //      $fileType = 'unknown: '.$typeCode;
    }
	if(!empty($allowType)){
		foreach($allowType as $k=>$v){
			if(in_array(strtolower($v),$fileType)){
				return true;
			}
		} 	
	}
	return false;
    //return $fileType;
}

/**
*@快递100提供的快递查询接口
* @access public
* @param $typeCom 快递公司代码，不支持中文
* @param $typeNu  要查询的快递单号，请勿带特殊符号，不支持中文（大小不敏感）
* @return array
*@return string
*接口技术地址：http://www.kuaidi100.com/openapi/api_post.shtml
 */	
 function get_kuaidi100($typeCom,$typeNu){
       $key='0c555619be09fe9d';
       // $url ='http://api.kuaidi100.com/api?id=0c555619be09fe9d&com='.$typeCom.'&nu='.$typeNu.'&show=0&muti=1&order=desc';
		$url = 'http://www.kuaidi100.com/applyurl?key='.$key.'&com='.$typeCom.'&nu='.$typeNu;
		//优先使用curl模式发送数据
		if (function_exists('curl_init') == 1){
		  $curl = curl_init();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_HEADER,0);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		  $get_content = curl_exec($curl);
		  curl_close ($curl);
		}else{
			import('Class.Snoopy',APP_PATH);
			$snoopy=new snoopy();		
		    $snoopy->referer = 'http://www.baidu.com';//伪装来源
		    $snoopy->fetch($url);
		    $get_content = $snoopy->results;
		}  
		return $get_content; //URL
		
 }	
 
/**百度 Kuaidi100物流跟踪接口
*$typeNu      物流单号
* $typeCom  //快递公司代码
*$id               查询类型 4 为百度查询
*/
function baiduKuaidi100($typeNu='456166037607',$typeCom='shunfeng',$id=4){
	$url ='http://baidu.kuaidi100.com/query?type='.$typeCom.'&postid='.$typeNu.'&id='.$id;
    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_HEADER,0);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt ($curl, CURLOPT_TIMEOUT,5);
    $get_content = curl_exec($curl);
    curl_close ($curl);	
	return json_decode($get_content,true);
}

/**
*自定义对话框JSON数据
*$status  状态
*$title      提示标题
*$info      提示信息
*$locationUrl  跳转的URL
*$isMobile   是否是手机请求
*/
function alert_json($status,$title,$info,$locationUrl,$isMobile='',$data=''){
  die(json_encode(array(
     'status'=>$status,
	 'title'=>$title,
	 'info'=>$info,
	 'locationUrl'=>$locationUrl,
	 'isMobile'=>$isMobile,
	 'data'=>$data
  )));
}  

//获取保存SESSION的用户信息，使用C配置
function SC($C){
	return $_SESSION[C($C)];
}

/**
*排序
*$orderType   排序字段
*$orderValue 排序值ASC或DESC
*$defOrder     默认排序字段
*$alias            表别名，如(g.)
*/
function orderList($orderType='order_type',$orderValue='order',$defOrder='create_time',$alias=''){
	   if($_GET[$orderType]){
	        if($_GET[$orderValue]=='asc'){
			   $orders='desc';
			}else{
			   $orders='asc';
			}
			$orderField=" ".$alias.$_GET[$orderType]." ".$_GET[$orderValue]." ";
	   }else{
	       $orders='asc';
		   $orderField=" ".$alias.$defOrder." DESC ";
	   }  
	   return array('tpl_show_order'=>$orders,'db_order'=>$orderField);
}

/*是否是手机板模块，如果是跳转URL加上Mobile*/
function is_m(){
   if(strpos(__GROUP__,APP_MOBILE) !==false){
	   return APP_MOBILE;
   }
}

/**
*导出Excel
*$dataArray 数据数组
*$fieldArray  获取数据字段数组格式如下：
$fieldArray=array(
	//width是单元格的宽度，height是单元格的高度，info 是第一行单元格标题的名称  
	0=>array('type'=>'txt','width'=>'10','height'=>'50','info'=>'用户ID','field'=>'id'),//'type'=>'txt' 文本字段
	1=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'用户名称','field'=>'user_name'),//'type'=>'txt' 文本字段
	2=>array('type'=>'img','width'=>'13','height'=>'50','info'=>'图片','field'=>'head_pic'), //'type'=>'img' 固定值
	3=>array('type'=>'address','width'=>'30','height'=>'50','info'=>'详细地址','field'=>'country,province,city,district,address'),//'type'=>'address'地址字段(如果有country、province、city)
   4=>array('type'=>'date','width'=>'20','height'=>'50','info'=>'注册时间','field'=>'reg_time') //'type'=>'date'时间字段如果此字段存放的是时间截否则可使用文本字段
);
*$Typeface  字体
*$fontSize    字体大小
*$fontColor   字体色
*/
function exportExcel($dataArray,$fieldArray,$defaultImg='',$Typeface='微软黑体',$fontSize=14,$fontColor='FF999999'){
	    if(!$defaultImg) $defaultImg='./'.C('USER_IMG'); //默认图片
	    $fieldCount=count($fieldArray)-1; //字段个数
		$fileName=date('Y-m-d'); //文件名称	 	
		//城市表数据放入内容
        $model=new CommonModel();
		$region=$model->regionToArray();
		//支持52个字段
		$ABC=array(
				'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
				'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO',
		        'AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel.php");    
		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel/Writer/Excel5.php");  
		$objExcel = new PHPExcel(); 		// 创建一个处理对象实例 	
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式  			
		//$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于2007 格式 					 
		$objProps = $objExcel->getProperties ();//创建文件对像		
		$objExcel->setActiveSheetIndex( 0 );//工作表设置
		$objExcel->getSheet(0)->setTitle($fileName); //worksheet工作表标题
		$objActSheet = $objExcel->getActiveSheet ();		
		
		//设置单元格宽度
		foreach($fieldArray as $back =>$bacv){
			if($bacv['width']){
				$objActSheet->getColumnDimension($ABC[$back])->setWidth($bacv["width"]);
			}else{
				$objActSheet->getColumnDimension($ABC[$back])->setAutoSize(true);
			}
		}		
		
		//设置首行单元格赋值标题
		foreach($fieldArray as $back =>$bacv){
				$objActSheet->setCellValue ($ABC[$back].'1',$bacv["info"]);
		}	
		
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
		//设置首行标题单元格的样式
		$objStyleA1 = $objActSheet->getStyle($ABC[0].'1:'.$ABC[$fieldCount].'1');
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName($Typeface);  //选择字体
		$objFontA1->setSize($fontSize);   //字体大小
		$objFontA1->setBold(true); 	
		//$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  //文本加下横线
		$objFontA1->getColor()->setARGB($fontColor);  //字体色	

		//获取设置字段
	    $fields=array();
		foreach($fieldArray as $k=>$v){
			$fields[$v['field']]['field']=$v['field'];
			$fields[$v['field']]['info']=$v['info'];
			$fields[$v['field']]['margin_top']=$v['margin_top'];
			$fields[$v['field']]['margin_left']=$v['margin_left'];
			$fields[$v['field']]['height']=$v['height'];
			$fields[$v['field']]['width']=$v['width'];
			$fields[$v['field']]['type']=$v['type'];
		}
		//编历单元格赋值
		$i=2;
		
		$setField='';
		foreach($dataArray as $k=>$v){
			$j=0;
			foreach($fields as $k1=>$v2){
				$setField=$v2; 
				//设置行高
				if($v2['height']){
                     $objExcel->getActiveSheet()->getRowDimension($i)->setRowHeight($v2['height']); 
				}else{
					$objActSheet->getColumnDimension($ABC[$j].$i)->setAutoSize(true);
				}
				//字段符值
				if($v2['type']=='txt'){ //文本字段
				    $objActSheet->setCellValue ($ABC[$j].$i, $v[$v2['field']]);
				}else if($v2['type']=='date'){ //时间字段
				    $objActSheet->setCellValue ($ABC[$j].$i, date('Y-m-d',$v[$v2['field']]));
				}else if($v2['type']=='img'){ //图片字段
					//处理图片
					$objDrawing = new PHPExcel_Worksheet_Drawing(); 					
					if($v[$v2['field']]){
						if(file_exists('./'.$v[$v2['field']])){
							$userImg='./'.$v[$v2['field']];
						}else{
							$userImg=$defaultImg;
						}				
					}else{
						$userImg=$defaultImg;
					}		
					$objDrawing->setName('ZealImg'.$i);  //图片名称
					$objDrawing->setDescription('Image inserted byZeal'.$i); //图片描述
					$objDrawing->setPath($userImg); //图片路径
					$objDrawing->setHeight($v2['height']-4);   //图片高度
					$objDrawing->setCoordinates($ABC[$j].$i);
					$objDrawing->setOffsetX(($v2['width']/2) + 15);  //margin-left:20px;
					$objDrawing->setOffsetY(($v2['height'] - ($v2['height']-20)) / 2);  //margin-top:20px;
					$objDrawing->setRotation(20); 
					$objDrawing->getShadow()->setVisible(true); 
					$objDrawing->getShadow()->setDirection(26); 
					$objDrawing->setWorksheet($objActSheet); 					
				}else if($v2['type']=='address'){ //地址字段
				    if(strpos($v2['field'],',')!==false){
						$dataField=explode(',',$v2['field']);
						$dataMoer=$region['province'][$v[$dataField[1]]].$region['city'][$v[$dataField[2]]].$region['district'][$v[$dataField[3]]].$v[$dataField[4]]; 
					}else{
						$dataMoer=$v2['field'];
					}
					$objActSheet->setCellValue ($ABC[$j].$i, $dataMoer);
				}
				$j++;
			}
			$textCenter=$objActSheet->getStyle($ABC[$j].$i.':'.$ABC[$fieldCount].$i); //向中间对齐
			$textCenter=$textCenter->getAlignment(); 
			$textCenter->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //向中间对齐		
			$i++;
			
		}
		//文件直接输出到浏览器
		header ( 'Pragma:public');
		header ( 'Expires:0');
		header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header ( 'Content-Type:application/force-download');
		header ( 'Content-Type:application/vnd.ms-excel');
		header ( 'Content-Type:application/octet-stream');
		header ( 'Content-Type:application/download');
		header ( 'Content-Disposition:attachment;filename='. $fileName .'.xls' );
		header ( 'Content-Transfer-Encoding:binary');
		$objWriter->save ( 'php://output');		
}

/*
*检索条件URL
*$_URL_        GET参数数组
*$get_arr       二维数组，可同时设置多个GET请求参数
*$get_str        GET字符串
*$get_val       GET字符串字符串值
*$is_page     上下分页使用
*condition_url($_GET['_URL_'],array('min'=>300,'max'=>600),$is_page);
*/
function condition_url($_URL_,$get_arr=array(),$is_page=false){
		if(__GROUP__ !=__ROOT__) {
			$group=__GROUP__.'/';
			unset($_URL_[0]);
			$j=0;
			$temp=$_URL_;
			$_URL_=array();
			foreach($temp as $k=>$v){
				$_URL_[$j]=$v;
				$j++;
			}
			$root='';
		}else{
			$root=__ROOT__.'/';
			$group='';
		}
		
		foreach($_URL_ as $k=>$v){
			if($k==0) {
				$arr[$_URL_[0]]=$_URL_[1];
				$i=2;
			}else{
				if($_URL_[$i] !=C('VAR_PAGE')){
					$arr[$_URL_[$i]]=$_URL_[$i+1];
				}
				$i=$i + 2;
			}		
		}
		$url_str='';
		if($get_arr) $arr=array_merge($arr,$get_arr);
		foreach($arr as $k=>$v){
			if($k !='' && $v !=''){
				$url_str.=$k.'/'.$v.'/';
			}
		}
		if($is_page){
		    return  $root.$group.rtrim($url_str,'/');
		}else{
			if($_GET[C('VAR_PAGE')]) $page='/'.C('VAR_PAGE').'/'.$_GET[C('VAR_PAGE')];
			return  $root.$group.rtrim($url_str,'/').$page.'.'.C('URL_HTML_SUFFIX');					
		}
 
}

/**
 * 商品列表条件检索选中当前条件样式
 * $original          原来值
 * $getDate        GET参数值
 * $className   样式类名称
 * <a href="{:condition_url($_URL_,array('state'=>''))}" class="{:selecta('',$_GET['state'],'now')}">全部</a>
 * <a href="{:condition_url($_URL_,array('state'=>1))}" class="{:selecta(1,$_GET['state'],'now')}">投标中</a>
 * */
function selecta($original,$getDate,$className){
	if($original==$getDate)
    return $className;
}

/**
*双战点分页；上页、下页 URL
*$_URL_  GET参数数组
*$totalPages  总页数
*$type  上一页 或下一页 +或 -
*go_page(12,'+');
*/
function go_page($_URL_,$totalPages,$type='+'){
		$now_page=$_GET[C('VAR_PAGE')] ? $_GET[C('VAR_PAGE')] : 1;	
        $url= condition_url($_URL_,array(),true);
		if($type=='+'){
			if(($now_page + 1) > $totalPages){
				return '';
			}else{
				return  $url.'/'.C('VAR_PAGE').'/'.($now_page+1).'.'.C('URL_HTML_SUFFIX');
			}			
		}else if($type=='-'){
			if(($now_page - 1) < 1){
				return '';
			}else{
				return  $url.'/'.C('VAR_PAGE').'/'.($now_page -1).'.'.C('URL_HTML_SUFFIX');
			}				
		}else if(is_numeric($type)){
		   return  $url.'/'.C('VAR_PAGE').'/'.$type.'.'.C('URL_HTML_SUFFIX');
		}
}

/*
*自动获取数据库表字段名多语言
*$defaultField  表字段名
*$asField     改变别名
*getLangField('name','my_name') 打印效果;name AS my_name
*getLangField('a.name','my_name') 打印效果;a.name AS my_name
*/
function getLangField($defaultField,$asField=''){
	   $lang='';	        
	   $language=getLang();
	   $language=explode('-',$language);
	   if(strpos($defaultField,'.') !==false){
		   $defaultField=explode('.',$defaultField);
		   if($asField){
			   $field=$asField;
		   }else{
			   $field=$defaultField[1];
		   }
		   $lang=$defaultField[0].'.'.$language[1].'_'.$defaultField[1].' AS '.$field;
	   }else{
		   if($asField){
			   $field=$asField;
		   }else{
			   $field=$defaultField;
		   }		   
	       $lang=$language[1].'_'.$defaultField.' AS '.$field;
	   }
	   return $lang;		
}

/**
 * 多语言获取SystemConfig.php配置文件信息
 *echo L_C('WEBTITLE');
**/
function L_C($data){
	$lang=getLang();
	if($lang){
		$fieldSuffix =explode('-',$lang);
		$fieldSuffix=strtoupper($fieldSuffix[1].'_');
	}
	return C($fieldSuffix.$data);
}

/*取字符串长度,英文是中文的两倍*/
function show_str($str,$num){
	if(C('LANG_SWITCH_ON')){
		$lang=getLang();
		if($lang =='zh-cn'){
			$new_str=sub_str($str,$num);
		}else{
			$new_str=sub_str($str,$num + 2);
		}		
	}else{
	   $new_str=sub_str($str,$num);
	}
	return $new_str;
}

 //获取多语言
function getLang(){   	     
   if(C('LANG_SWITCH_ON')){
	   $language=$_GET[C('VAR_LANGUAGE')] ? $_GET[C('VAR_LANGUAGE')] : $_COOKIE['think_language'];
	   $langArr=C('LANG_LIST');
	   if(!in_array(strtolower($language), $langArr)) return C('DEFAULT_LANG');	   
	   return strtolower($language);   
   }else{
       return strtolower(C('DEFAULT_LANG'));
   }
}

/*
*列表显示默认语言
*$filed  字段名称不带语言前缀，如果为空值时直接返回语言前缀
*pfix('files_name'); 打印后cn_files_name
*/
function pfix($filed=''){
   $f=explode('-',getLang());
   if($filed){
	   return $f[1].'_'.$filed;
   }else{
	   return $f[1].'_';
   }
}

/**
*$field 表字段 name 也可以加子字段前缀 c.name
*返回$data['lang_str']数据表选择字段 cn_name,us_name 如果有字段前缀 c.cn_name,c.us_name
*返回$data['lang_arr']数组付值 array(0=>'cn_name',2=>'us_name');
*返回$data['lang_pfix']数组付值 array(0=>'cn_',2=>'us_');
*/
function langAllField($field){
	 $aname='';
	 $moerpfix=array();
	 if(!C('LANG_SWITCH_ON')){
		  $langFpx=explode('-',C('DEFAULT_LANG'));
		  $langFpx=$langFpx[1];
		  if(strpos($field,'.') !==false){
			  $fieldMoer=explode('.',$field);
			  $aname.=$fieldMoer[0].'.'.$langFpx.'_'.$fieldMoer[1];	  
		  }else{
			  $aname.=$langFpx.'_'.$field;	  
		  }
		  $moerpfix[]=$langFpx.'_';
	 }else{
		  foreach(C('LANG_LIST') as $k=>$v){
			  $langFpx=explode('-',$v);
			  $langFpx=$langFpx[1];
			  if(strpos($field,'.') !==false){
				  $fieldMoer=explode('.',$field);
				  $aname.=$fieldMoer[0].'.'.$langFpx.'_'.$fieldMoer[1].',';	  
			  }else{
				  $aname.=$langFpx.'_'.$field.',';	  			  
			  }
			  $moerpfix[]=$langFpx.'_';
		  }
	 }	
	 $data=array();
	 $aname=rtrim($aname,',');	  
	 if(strpos($field,'.') !==false) {
		 $emptys=explode('.',$field);
		 $emptys=$emptys[0].'.';
		 $returnArr = explode(',',str_replace($emptys,'',$aname));
	 }else{
		$returnArr = explode(',',$aname);
	 }
	 $data['lang_str']=$aname;
     $data['lang_arr']=$returnArr;
	 $data['lang_pfix']=$moerpfix;
	 return $data;
}

/*返回语言前缀如 
 * 返回数组 array(0=>'cn',1=>'us') 
 * */
function returnPrefx(){
	$returnFx=array();
	$lang=C('LANG_LIST_INFO');
	 if(!C('LANG_SWITCH_ON')){
		  $langFpx=explode('-',C('DEFAULT_LANG'));
		  $returnFx[0]['lang']=$langFpx[1];
		  $returnFx[0]['info']=$lang[0];
	 }else{
		  foreach(C('LANG_LIST') as $k=>$v){
			  $langFpx=explode('-',$v);
			  $returnFx[$k]['lang']=$langFpx[1];
			  $returnFx[$k]['info']=$lang[$k];
		  }
	 }
	 return 	$returnFx;	
}

/*跟据当前语言自动获取货币消息*/
function CUR_INFO(){
    $cur=C('CUR_INFO');
	return $cur[getLang()];
}

/*跟据当前语言自动获取货币符号*/
function CUR($lang=''){
	if(!$lang) $lang=getLang();
    $cur=C('CUR');
	return $cur[$lang];
}

/*跟据当前语言自动获取货币字母代号*/
function CUR_REP($lang=''){
	if(!$lang) $lang=getLang();
    $cur=C('CUR_REP');
	return $cur[$lang];
}

/*
*跟据当前语言货币汇率计算出价格，参照货币为人民币
*以1元为公式，跟据传进来的价格 X 汇率，保留下两位小数点
*系统数据库配置表my_web_config的货币汇率命名规则(en_us_cur)
*$amount   金额
*$isCUR 是否带货币符号
*/
function getPrice($amount,$isCUR=true){
	$lang=getLang();
	$curRep=C('CUR_REP');
    $cur=$curRep[$lang];
	$data=C($cur.'_CUR');
	$frefix='';
	if($isCUR) $frefix=CUR($lang);
	if($data > 0){ //如果有外汇
	    return $frefix.number_format($amount * $data,2, '.', '');
	}else{
		return $frefix.number_format($amount,2,'.','');
	}
}

/**
*外汇转换人民币
*$amount  金额
*$CUR      指定要转换货币英文代号，如果为空自动获取英文货代号
*conversio(1000,'USD')
*/
function conversio($amount,$CUR=''){
	if(!$CUR){
		$lang=getLang();
		$curRep=C('CUR_REP');
		$cur=$curRep[$lang];
		$data=C($cur.'_CUR');	
	}else{
	    $data=C(strtoupper($CUR).'_CUR');	
	}
	if($data > 0){ //如果有外汇
	    return number_format($amount / $data,2, '.', '');
	}else{
		return $amount;
	}	
}
?>