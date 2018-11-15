<?php
//更换语言
class LanguageAction extends Action{		
	//请求URL格式 U('/Language/index',array('lang'=>'zh-cn'));
	public function index(){
		nonlicet_url();
		$lang=strtolower($_GET[C('VAR_LANGUAGE')]);
		if(!in_array($lang,C('LANG_LIST'))) $lang=C('DEFAULT_LANG'); //如果语言不存在，则用默认语言
		cookie('think_language',$lang);
		if(C('HTML_CACHE_ON')){ //开启静态缓存
		    header('location:'.U('Index/index',array(C('VAR_LANGUAGE')=>$lang)));
		}else{
			$httpReferer=$_SERVER["HTTP_REFERER"];//请求来原地址
			$localhost=$_SERVER['HTTP_HOST']; //站点域名
			if(strpos($httpReferer,$localhost) !==false){
				$url=$httpReferer;
			}else{
				$url=U('/Index');
			}
			header("location:".$url);
		}
	}
}