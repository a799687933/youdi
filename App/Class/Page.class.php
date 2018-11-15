<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage =5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
	//指定控制器
	public $controller='';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    public $totalPages  ;
    // 总行数
    public $totalRows  ;
    // 当前页数
    public $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config; //数组 2014-4-12////////////////////////////////////////////////////
    // 默认分页变量名
    protected $varPage;
	
	//jquery Mobile 样式
	protected $pageLeft='';
	protected $pageRight='';
	protected $pageTop='';
	protected $pageBottom='';
	protected $center;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
        if(!empty($url))    $this->url  =   $url; 
    }
 
	
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
	 *show($data='Admin')
	 * $data  参数1为数字和文本分页,2文本分页,3带...数字分页，4仿百度分页,5手机版分页;6战头数字分页(<a href=""><<</a><a>1</a> <a>2</a> <a>3</a> <a> >> </a>)
     * @access public
     */
    public function show($data=1) {

		//多语言
		if(C('LANG_SWITCH_ON')){
			$pageTotal=L('pageTotal'); //总记录
			$pageFirst=L('pageFirst'); //第一页
			$pagePrevl=L('pagePrevl'); //上一页
			$pageNext=L('pageNext'); //下一页
			$pageLast=L('pageLast'); //最后一页
			$myPage=L('myPage'); //页
			$pageOn=L('pageOn'); //上
			$pageNe=L('pageNe'); //下
		}else{
			$pageTotal='条记录'; //总记录
			$pageFirst='第一页'; //第一页
			$pagePrevl='上一页'; //上一页
			$pageNext='下一页'; //下一页
			$pageLast='尾页'; //最后一页	
			$myPage='页'; //页
			$pageOn='上'; //上
			$pageNe='下'; //下
		}
		
        if(0 == $this->totalRows){
        	if($data==6){
        		return '<a class="icon33">&nbsp;</a><a style="color:#ccc;">1</a> <a class="icon33">&nbsp;</a>';
        	}else{
	 			//2014-4-12修改//////////////////////////////////////////////////////////////
				$this->	config=array('header'=>$pageTotal,'prev'=>$pagePrevl,'next'=>$pageNext,'first'=>$pageFirst,'last'=>$pageLast,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% '.$myPage.' %upPage% %downPage% %first%  %prePage%  %nextPage% %end%');
				return '<span class="conutNum">'.$this->totalRows.$this->config['header'].$this->totalPages.$myPage.'</span>'.'<span class="exitspan">'.$this->config['prev'].'</span> <span class="exitspan">'.$this->config['first'].'</span>';       		
        	}

		}
		if($data==1){  //2014-4-12////////////////////////////////////////////////////
		$this->	config=array('header'=>$pageTotal,'prev'=>$pagePrevl,'next'=>$pageNext,'first'=>$pageFirst,'last'=>$pageLast,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% '.$myPage.' %upPage% %downPage% %first%  %prePage%  %nextPage% %end%');
		}else if($data==2){
		$this->	config=    array('header'=>$pageTotal,'prev'=>$pagePrevl,'next'=>$pageNext,'first'=>$pageFirst,'last'=>$pageLast,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% '.$myPage.'  %linkPage% %upPage% %downPage% %first%  %prePage%  %nextPage% %end%');		
		}else if($data==3){
		$this->	config=    array('header'=>$pageTotal,'prev'=>$pagePrevl,'next'=>$pageNext,'last'=>$pageLast,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% '.$myPage.'  %linkPage% %upPage% %downPage% %end%');				
		}else if($data==4){
			$this->	config=    array('header'=>$pageTotal,'prev'=>$pagePrevl,'theme'=>'   %upPage% %linkPage%  %downPage% %end% %nowPage%/%totalPage% '.$myPage.' %totalRow% %header% ','next'=>$pageNext,'last'=>$pageLast);	
		}else if($data==5){
			$this->pageLeft='data-role="button" data-inline="true" data-icon="arrow-l" data-iconpos="left" data-mini="true"';
			$this->pageRight='data-role="button" data-inline="true" data-icon="arrow-r" data-iconpos="right" data-mini="true"';
			$this->pageTop='data-role="button" data-inline="true" data-icon="arrow-u" data-iconpos="left" data-mini="true"';
			$this->pageBottom='data-role="button" data-inline="true" data-icon="arrow-d" data-iconpos="right" data-mini="true"';
			//$this->	config=    array('first'=>'第一页','prev'=>'上一页','next'=>'下一页','last'=>'尾页','theme'=>' %first%  %upPage%  %downPage% %end% ');	
			$this->	config=    array('prev'=>$pagePrevl,'next'=>$pageNext,'theme'=>'   %upPage%  %downPage%  ');
		}else if($data==6){
			$this->	config=    array('prev'=>$pagePrevl,'next'=>$pageNext);		
		}else{
		$this->	config=array('header'=>$pageTotal,'prev'=>$pagePrevl,'next'=>$pageNext,'first'=>$pageFirst,'last'=>$myPage,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% '.$myPage.' %upPage% %downPage% %first%  %prePage%  %nextPage% %end%');
		}
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);

        // 分析分页参数
        if($this->url){
			if(C('URL_ROUTER_ON')){
				$url=$this->url.'__PAGE__'.C('TMPL_TEMPLATE_SUFFIX');
			}else{
				$depr       =   C('URL_PATHINFO_DEPR');
				$url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
			}
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  !empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            //$url            =   U('',$parameter);
			if($this->controller){
			     $url =U(GROUP_NAME."/".$this->controller."/".ACTION_NAME,array($this->varPage=>$parameter[$p]));
				 
			}else{
				$url            =   U('',$parameter);
			}
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
        	if($data==6){
        		$upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."' class='a_page icon33' ".$this->pageLeft."></a>";
        	}else{
        		$upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."' class='a_page' ".$this->pageLeft.">".$this->config['prev']."</a>";
        	}
            
        }else{
        	if($data==6){
        		$upPage     =   "<a class='currentno icon33' ".$this->pageLeft." ></a>"; //2014-4-12修改//////////////////////////////////////////////////////////////
        	}else{
        		$upPage     =   "<span class='currentno' ".$this->pageLeft." >".$this->config['prev']."</span>"; //2014-4-12修改//////////////////////////////////////////////////////////////
        	}
            
        }

        if ($downRow <= $this->totalPages){
        	if($data==6){
        		$downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."' class='a_page icon34'>&nbsp;</a>";
        	}else{
	            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."' class='a_page' ".$this->pageRight.">".$this->config['next']."</a>";
				$theEnd   =   "<a href='".str_replace('__PAGE__',$this->totalPages,$url)."' class='a_page'  ".$this->pageBottom.">".$this->config['last']."</a>";        		
        	}
        }else{
        	if($data==6){
        		$downPage   =   "<a style='color:#ccc;' class='icon34'>&nbsp;</a>";
        	}else{
	            $downPage   =   "<span class='currentno' ".$this->pageRight.">".$this->config['next']."</span>"; //2014-4-12修改/////////////////////////////////////////////////////////////////////
				$theEnd     =   '<span class="currentno" '.$this->pageBottom.' >'.$this->config['last'].'</span>';       		
        	}
        }

        // << < > >>
        if($nowCoolPage == 1){
            $theFirst   =   '';
            $prePage    =   '';
        }else{
            $preRow     =   $this->nowPage-$this->rollPage;
            $prePage    =   "<a href='".str_replace('__PAGE__',$preRow,$url)."' class='a_page' ".$this->pageLeft." >上".$this->rollPage."页</a>";
            $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' class='a_page' ".$this->pageBottom.">".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
        	if($data==6){
 	             $nextPage   =   "<a class='currentno icon34'>&nbsp;</a>"; 
	           // $theEnd     =   '<span class="currentno">'.$this->config['last'].'</span>'; //2014-4-12///////////////////////////////////////           		
        	}else{
	             $nextPage   =   '<span class="currentno">下'.$this->rollPage.'页</span>'; //2014-4-12///////////////////////////////////////
	           // $theEnd     =   '<span class="currentno">'.$this->config['last'].'</span>'; //2014-4-12///////////////////////////////////////       		
        	}

        }else{
            $nextRow    =   $this->nowPage+$this->rollPage;
            $theEndRow  =   $this->totalPages;
			if($data==6){
				$nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' class='currentno icon34'>&nbsp;</a>"; 
			}else{
	            $nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' class='a_page' ".$this->pageRight.">下".$this->rollPage."页</a>";
	            $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' class='a_page' ".$this->pageBottom.">".$this->config['last']."</a>";				
			}
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                      $linkPage .= "<a href='".str_replace('__PAGE__',$page,$url)."' class='a_show'>".$page."</a>";
                }else{
					if($data!=4 && $data!=6){
					   $linkPage= "<a href='".str_replace('__PAGE__',1,$url)."' class='a_show'>1</a> ... ".$linkPage;
					}
                    break;
                }
            }else{
                if($this->totalPages != 1){
                	if($data==6){
                		$linkPage .= "<a style='color:#ccc;'>".$page."</a>";
                	}else{
                		$linkPage .= "<span class='current_hide'>".$page."</span>";
                	}
                    
                }
            }
        }
		if($data==1){		
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%nextPage%','%end%'),
           array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$nextPage,$theEnd),$this->config['theme']);
		}else if($data==2){			
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%linkPage%','%upPage%','%downPage%','%first%','%prePage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$linkPage,$upPage,$downPage,$theFirst,$prePage,$nextPage,$theEnd),$this->config['theme']);
		}else if($data==3){
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%linkPage%','%upPage%','%downPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$linkPage,$upPage,$downPage,$theEnd),$this->config['theme']);			
		}else if($data==4){
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%linkPage%','%upPage%','%downPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$linkPage,$upPage,$downPage,$theEnd),$this->config['theme']);			

       }else if($data==5){
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%nextPage%','%end%'),
           array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$nextPage,$theEnd),$this->config['theme']);		

       }else if($data==6){
       	   if(!$linkPage) $linkPage='<a style="color:#ccc;">1</a>';
			$pageStr=$upPage.$linkPage. $downPage;    	
       }else{
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%nextPage%','%end%'),
           array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$nextPage,$theEnd),$this->config['theme']);
		}
		//echo $pageStr; die;
	    return $pageStr;
       
    }
	
	public function showCount(){
		
        return $this->totalPages;	
	}

}
