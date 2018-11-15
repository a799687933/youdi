<?php
/**
 * 两个表
 * --
-- 表的结构 `my_web_visit`
--
CREATE TABLE IF NOT EXISTS `my_web_visit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '//表ID',
  `city` varchar(20) NOT NULL COMMENT '//所属城市',
  `ip` varchar(30) NOT NULL COMMENT '//来访IP',
  `os` varchar(100) NOT NULL COMMENT '//来访操作系',
  `browser` varchar(100) NOT NULL COMMENT '//来访浏览器版本',
  `web_page` varchar(250) NOT NULL COMMENT '//访问的页面',
  `engine` varchar(50) NOT NULL COMMENT '//从搜索引擎而来',
  `engine_key` varchar(200) NOT NULL COMMENT '//搜索关键字',
  `visit_time` int(11) NOT NULL COMMENT '//来访时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;
--
-- 表的结构 `my_spider`
--

CREATE TABLE IF NOT EXISTS `my_spider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '//表ID',
  `spider_name` varchar(100) NOT NULL COMMENT '//搜索引擎名称',
  `get_url` varchar(200) NOT NULL COMMENT '//搜索引擎抓取页面',
  `visit_time` int(11) NOT NULL COMMENT '//来访时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 */
class VisitRecordModel extends Model{
	
     public function visitStats(){
	      $browser  = $this->getUserBrowser();  //浏览器版本
	      $os       = $this->getOs();  //操作系统
	      $ip       =get_ip();    //IP
	      $area     = city_ip($ip);  //所在城市
	      // 语言
	     if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	        $pos  = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';');
	        $lang = addslashes(($pos !== false) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $pos) : $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	     }else{
	        $lang = '';
	     }	  
		 
	    //来源 
	    if (!empty($_SERVER['HTTP_REFERER']) && strlen($_SERVER['HTTP_REFERER']) > 9){
	        $pos = strpos($_SERVER['HTTP_REFERER'], '/', 9);
	        if ($pos !== false){
	            $domain = substr($_SERVER['HTTP_REFERER'], 0, $pos);
	            $path   = substr($_SERVER['HTTP_REFERER'], $pos);
	
	            // 来源关键字 
	            if (!empty($domain) && !empty($path)){
	                $shteed=$this->saveSearchengineKeyword($domain, $path);
	            }
	        }else{
	            $domain = $path = '';
	        }
	    }else{
	        $domain = $path = '';
	    }		
		$dataAll['city'] = $area ? $area : '未知';
		$dataAll['ip'] = $ip ? $ip : '未知';
		$dataAll['os'] = $os ? $os : '未知';
		$dataAll['browser'] = $browser ? $browser : '未知';
		$dataAll['web_page'] = __SELF__;
		$dataAll['engine'] = $shteed[0] ? $shteed[0] : '';
		$dataAll['engine_key'] = $shteed[1] ? $shteed[1] : '';
		$dataAll['visit_time'] = time();
		$dataAll['free_url']=$_SERVER["HTTP_REFERER"];
		 M('web_visit')->add($dataAll);
		              	
    }

	/**
	 * 获取搜索引擎关键字
	 *
	 * @access  private
	 * @return  array
	 */
	private function saveSearchengineKeyword($domain, $path){
	    if (strpos($domain, 'google.com.tw') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'GOOGLE TAIWAN';
	        $keywords = urldecode($regs[1]); // google taiwan
	    }
	    if (strpos($domain, 'google.cn') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'GOOGLE CHINA';
	        $keywords = urldecode($regs[1]); // google china
	    }
	    if (strpos($domain, 'google.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'GOOGLE';
	        $keywords = urldecode($regs[1]); // google
	    }elseif (strpos($domain, 'baidu.') !== false && preg_match('/wd=([^&]*)/i', $path, $regs)){
	        $searchengine = 'BAIDU';
	        $keywords = urldecode($regs[1]); // baidu
	    }elseif (strpos($domain, 'baidu.') !== false && preg_match('/word=([^&]*)/i', $path, $regs)){
	        $searchengine = 'BAIDU';
	        $keywords = urldecode($regs[1]); // baidu
	    }elseif (strpos($domain, '114.vnet.cn') !== false && preg_match('/kw=([^&]*)/i', $path, $regs)){
	        $searchengine = 'CT114';
	        $keywords = urldecode($regs[1]); // ct114
	    }elseif (strpos($domain, 'iask.com') !== false && preg_match('/k=([^&]*)/i', $path, $regs)){
	        $searchengine = 'IASK';
	        $keywords = urldecode($regs[1]); // iask
	    }elseif (strpos($domain, 'soso.com') !== false && preg_match('/w=([^&]*)/i', $path, $regs)){
	        $searchengine = 'SOSO';
	        $keywords = urldecode($regs[1]); // soso
	    }elseif (strpos($domain, 'sogou.com') !== false && preg_match('/query=([^&]*)/i', $path, $regs)){
	        $searchengine = 'SOGOU';
	        $keywords = urldecode($regs[1]); // sogou
	    }elseif (strpos($domain, 'so.163.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'NETEASE';
	        $keywords = urldecode($regs[1]); // netease
	    }elseif (strpos($domain, 'yodao.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'YODAO';
	        $keywords = urldecode($regs[1]); // yodao
	    }elseif (strpos($domain, 'zhongsou.com') !== false && preg_match('/word=([^&]*)/i', $path, $regs)){
	        $searchengine = 'ZHONGSOU';
	        $keywords = urldecode($regs[1]); // zhongsou
	    }elseif (strpos($domain, 'search.tom.com') !== false && preg_match('/w=([^&]*)/i', $path, $regs)){
	        $searchengine = 'TOM';
	        $keywords = urldecode($regs[1]); // tom
	    }elseif (strpos($domain, 'live.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'MSLIVE';
	        $keywords = urldecode($regs[1]); // MSLIVE
	    }elseif (strpos($domain, 'tw.search.yahoo.com') !== false && preg_match('/p=([^&]*)/i', $path, $regs)){
	        $searchengine = 'YAHOO TAIWAN';
	        $keywords = urldecode($regs[1]); // yahoo taiwan
	    }elseif (strpos($domain, 'cn.yahoo.') !== false && preg_match('/p=([^&]*)/i', $path, $regs)){
	        $searchengine = 'YAHOO CHINA';
	        $keywords = urldecode($regs[1]); // yahoo china
	    }elseif (strpos($domain, 'yahoo.') !== false && preg_match('/p=([^&]*)/i', $path, $regs)){
	        $searchengine = 'YAHOO';
	        $keywords = urldecode($regs[1]); // yahoo
	    }elseif (strpos($domain, 'msn.com.tw') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'MSN TAIWAN';
	        $keywords = urldecode($regs[1]); // msn taiwan
	    }elseif (strpos($domain, 'msn.com.cn') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'MSN CHINA';
	        $keywords = urldecode($regs[1]); // msn china
	    }elseif (strpos($domain, 'msn.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)){
	        $searchengine = 'MSN';
	        $keywords = urldecode($regs[1]); // msn
	    }
	
	    if (!empty($keywords)){
	        $gb_search = array('YAHOO CHINA', 'TOM', 'ZHONGSOU', 'NETEASE', 'SOGOU', 'SOSO', 'IASK', 'CT114', 'BAIDU');
	        if (EC_CHARSET == 'utf-8' && in_array($searchengine, $gb_search)){
	            $keywords = ecs_iconv('GBK', 'UTF8', $keywords);
	        }
	        if (EC_CHARSET == 'gbk' && !in_array($searchengine, $gb_search)){
	            $keywords = ecs_iconv('UTF8', 'GBK', $keywords);
	        }
	        return array($searchengine,addslashes($keywords));
	    }
	}

	/**
	 * 判断是否为搜索引擎蜘蛛
	 *
	 * @access  public
	 * @return  string
	 */
	public function isSpider($record = true){
	    static $spider = NULL;	
	    if ($spider !== NULL){
	        return $spider;
	    }	
	    if (empty($_SERVER['HTTP_USER_AGENT'])){
	        $spider = '';
	
	        return '';
	    }	
	    $searchengine_bot = array(
	        'googlebot',
	        'mediapartners-google',
	        'baiduspider+',
	        'msnbot',
	        'yodaobot',
	        'yahoo! slurp;',
	        'yahoo! slurp china;',
	        'iaskspider',
	        'sogou web spider',
	        'sogou push spider'
	    );	
	    $searchengine_name = array(
	        'GOOGLE',
	        'GOOGLE ADSENSE',
	        'BAIDU',
	        'MSN',
	        'YODAO',
	        'YAHOO',
	        'Yahoo China',
	        'IASK',
	        'SOGOU',
	        'SOGOU'
	    );	
	    $spider = strtolower($_SERVER['HTTP_USER_AGENT']);	
	    foreach ($searchengine_bot AS $key => $value){
	        if (strpos($spider, $value) !== false){
	            $spider = $searchengine_name[$key];	
	            if ($record === true){
	                //记录搜索引擎来访
	                $datas=array(
					   'spider_name'=>$spider,
					   'get_url'=>__SELF__,
					   'visit_time'=>time(),
					);
	                M('spider')->add($datas);
	            }	
	            return $spider;
	        }
	    }	
	    $spider = '';	
	    return '';
	}

	/**
	 * 获得浏览器名称和版本
	 *
	 * @access  public
	 * @return  string
	 */
	public function getUserBrowser(){
	    if (empty($_SERVER['HTTP_USER_AGENT'])) {
	        return '';
	    }	
	    $agent       = $_SERVER['HTTP_USER_AGENT'];
	    $browser     = '';
	    $browser_ver = '';	
	    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
	        $browser     = 'Internet Explorer';
	        $browser_ver = $regs[1];
	    }elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
	        $browser     = 'FireFox';
	        $browser_ver = $regs[1];
	    }elseif (preg_match('/Maxthon/i', $agent, $regs)) {
	        $browser     = '(Internet Explorer ' .$browser_ver. ') Maxthon';
	        $browser_ver = '';
	    } elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
	        $browser     = 'Opera';
	        $browser_ver = $regs[1];
	    }elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
	        $browser     = 'OmniWeb';
	        $browser_ver = $regs[2];
	    }elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)){
	        $browser     = 'Netscape';
	        $browser_ver = $regs[2];
	    }elseif (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
	        $browser     = 'Safari';
	        $browser_ver = $regs[1];
	    }
	    elseif (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
	        $browser     = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
	        $browser_ver = $regs[1];
	    }elseif (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)){
	        $browser     = 'Lynx';
	        $browser_ver = $regs[1];
	    }
	
	    if (!empty($browser)) {
	       return addslashes($browser . ' ' . $browser_ver);
	    }else {
	        return 'Unknow browser';
	    }
	}

		/**
		 * 获得客户端的操作系统
		 *
		 * @access  private
		 * @return  void
		 */
		public function getOs(){
		    if (empty($_SERVER['HTTP_USER_AGENT'])){
		        return 'Unknown';
		    }		
		    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		    $os    = '';		
		    if (strpos($agent, 'win') !== false){
		        if (strpos($agent, 'nt 5.1') !== false){
		            $os = 'Windows XP';
		        }elseif (strpos($agent, 'nt 5.2') !== false){
		            $os = 'Windows 2003';
		        }elseif (strpos($agent, 'nt 5.0') !== false){
		            $os = 'Windows 2000';
		        }elseif (strpos($agent, 'nt 6.0') !== false){
		            $os = 'Windows Vista';
		        }elseif (strpos($agent, 'nt') !== false){
		            $os = 'Windows NT';
		        }elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false){
		            $os = 'Windows ME';
		        }elseif (strpos($agent, '98') !== false){
		            $os = 'Windows 98';
		        }elseif (strpos($agent, '95') !== false){
		            $os = 'Windows 95';
		        }elseif (strpos($agent, '32') !== false){
		            $os = 'Windows 32';
		        }elseif (strpos($agent, 'ce') !== false){
		            $os = 'Windows CE';
		        }
		    }
		    elseif (strpos($agent, 'linux') !== false){
		        $os = 'Linux';
		    }elseif (strpos($agent, 'unix') !== false){
		        $os = 'Unix';
		    }elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false){
		        $os = 'SunOS';
		    }elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false){
		        $os = 'IBM OS/2';
		    }elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false){
		        $os = 'Macintosh';
		    }elseif (strpos($agent, 'powerpc') !== false){
		        $os = 'PowerPC';
		    }elseif (strpos($agent, 'aix') !== false){
		        $os = 'AIX';
		    }elseif (strpos($agent, 'hpux') !== false){
		        $os = 'HPUX';
		    }elseif (strpos($agent, 'netbsd') !== false){
		        $os = 'NetBSD';
		    }elseif (strpos($agent, 'bsd') !== false){
		        $os = 'BSD';
		    }elseif (strpos($agent, 'osf1') !== false){
		        $os = 'OSF1';
		    }elseif (strpos($agent, 'irix') !== false){
		        $os = 'IRIX';
		    }elseif (strpos($agent, 'freebsd') !== false){
		        $os = 'FreeBSD';
		    }elseif (strpos($agent, 'teleport') !== false){
		        $os = 'teleport';
		    }elseif (strpos($agent, 'flashget') !== false){
		        $os = 'flashget';
		    }elseif (strpos($agent, 'webzip') !== false){
		        $os = 'webzip';
		    }elseif (strpos($agent, 'offline') !== false){
		        $os = 'offline';
		    }else{
		        $os = 'Unknown';
		    }		
		    return $os;
		}

}
?>