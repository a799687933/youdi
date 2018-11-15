<?php
class ExtendsArticleAction extends ArticleAction{
		
		//已审核文档列表
		public function extendsArticleList(){
            $this->rootArticleList(2);
			$this->display();
		}
				
		//添加和修改文档界面
		public function extendsAddSave(){
			$filesId=getNum($_GET['files_id']);
           if(!is_channel($filesId,1)) return_json(300,'你没有添加或修改权限');
			$filed="files_id,".pfix('files_name')." AS files_name,files_pid,files_type,article_type,article_width,article_height,article_fns";
			$res=M('files_sort')->field($filed)->where(array('display'=>1))->order(array('files_sort'=>'ASC'))->select();
			
			//获得文档需要的信息
			$child=array();
            foreach($res as $k=>$v){
            	if($v['files_id'] ==$filesId) $filesData=$v;
				if($v['files_pid']==$filesId) {
					$child[$k]['files_id']=$v['files_id'];
					$child[$k]['files_name']=$v['files_name'];
				}
            }
            if(empty($filesData)) return_json(300,'非法操作');
			//支持子级分类
		    $this->	child=$child;
			$id=getNum($_GET['id']);
			//其它文档功能
			$filesData['article_fns']=$filesData['article_fns'] ? explode(',',$filesData['article_fns']) : array();
			//支持文章关联
			$f=array();
			if(in_array(1,$filesData['article_fns'])){
				foreach($res as $k=>$v) if($v['files_type'] ==5) $f[$k]=$v;
				import('Class.Category',APP_PATH);
				$this->relCate=Category::unlimitedForLevel($f,'files_id','files_pid');	
			}
			$this->REGION=C('REGION');//大区域
			$this->CHARACTERISTIC=C('CHARACTERISTIC');//特色
			$this->SHOW_TYPE=C('SHOW_TYPE');//类型
			$this->filesData=$filesData; //文档基本需求
			$this->titletexts=$this->langIsMore('article','titletext'); //标题多语言
			$this->keywordss=$this->langIsMore('article','keywords'); //关键字多语言
			$this->authors=$this->langIsMore('article','author'); //作者多语言
			$this->bewrites=$this->langIsMore('article','bewrite'); //描述多语言
			$this->contents=$this->langIsMore('article','content'); //文档内容多语言	
			$parameterss=$this->langIsMore('article','parameter'); //更多其它参数	
			$this->parameterss=$parameterss;
			$this->rightContent=$this->langIsMore('article','right_content'); //右边内容	
            if($id){ //修改
			    //获取表内容	
			    $save=M('article')->where(array('id'=>$id))->find();
				$save['show_type']=$save['show_type'] ? explode('-',trim($save['show_type'],'-')) : array();
				$save['characteristic']=$save['characteristic'] ? explode('-',trim($save['characteristic'],'-')) : array();
				foreach($parameterss as $v) $save[$v['field']]=$save[$v['field']] ? json_decode($save[$v['field']],true) : array();
				//获取关联文章(  需要时再打开)
				$where=array();
				$where['id']=array('in',$save['take']);
				$this->take=M('article')->where($where)->select();
				//获取相关相册
				$this->photo=M('album')->where(array('tableId'=>$id,'table_name'=>'article'))->select();	
				$this->save=$save;						
			}
			$this->display();
		}
		
		//添加和修改文档表单处理
		public function extendsAddSaveForm(){
                if(!is_channel($_POST['files_id'],1)) return_json(300,'你没有添加或修改权限');		
				//如果是审核权限
				if(intval($_POST['is_show'])==1){
					if(!is_channel($_POST['files_id'],2)) return_json(300,'你没有审核文档权限');		
				}
				//检索关联文章
				if($_GET['rel']){
					$this->relation();
					die();
				}
				
				if($_GET['one']=='ok'){
					$this->saveOen();
				}else if($_GET['yes_no']=='ok'){
					$this->saveSwitch();
				}						
			  $this->PublicMethod();//处理提交的数据
			  
			  //规格参数列表
			  $parameterss=$this->langIsMore('article','parameter');
			   foreach($parameterss as $k=>$v){
					if($_POST[$v['field']][0]){
						$par=array();
						$c=0;
						foreach($_POST[$v['field']] as $k1=>$v1){
							$parName=emptyHtml($v1);
							$parValue=emptyHtml($_POST[$v['field'].'_val'][$k1]);
							if($parName && $parValue){
								$par[$c]['par_name']=$parName;
								$par[$c]['par_value']=$parValue;
								$c++;					
							}
						}
						unset($_POST[$v['field']]);
						unset($_POST[$v['field'].'_val']);
						$_POST[$v['field']]=json_encode($par);
					}	   	
			   }			  
			  //p('extendsArticleList/files_id/'.$_POST['files_id'].'/files_type/'.$_POST['files_type']);die;
			  $rel='ExtendsArticle/extendsArticleList/files_id/'.$_POST['files_id'].'/article_type/'.$_POST['article_type'].'/files_type/'.$_REQUEST['files_type'];
			  //p($_POST);die;
			  if($_POST['id']){  //修改处理
			      //p($_POST);die;
			      //如果有子级分类
				  $_POST['files_id']=$_POST['cate_id'] > 0 ? $_POST['cate_id'] : $_POST['files_id'];				  
			  	  D('Curd')->modify('article',$rel,'','id',array(),'content');
			  }else{   //新增处理
				  $_POST['addtime']=time();
				  $_POST['scan_time']=time();			  
			      $this->requestArray('cms_content');
			      //如果有子级分类
				  $_POST['files_id']=$_POST['cate_id'] > 0 ? $_POST['cate_id'] : $_POST['files_id'];					 
			  	 D('Curd')->insert('article',$rel,array(),'content');//插入
			  }		  
		}
				
		//删除文档
		public function extendsArticleDelete(){
			 nonlicet_url();
			 if(!is_channel($_GET['del_id'],3)) return_json(300,'你没有删除权限');	
			$obj=D('Curd');
			$wheres['article_id']=array('in',$_REQUEST['ids']);//公共ID集合
			
		   //删除评述表		   
		   $arrId=M('comment')->field('id')->where($wheres)->select();
		   M('comment')->where($wheres)->delete();
		   $obj->del_img($obj->id_set($arrId),'comment'); //删图片
		   
		   //删除回复表
		   $arrId2=M('comment_reply')->field('id')->where($wheres)->select();	
		   M('comment_reply')->where($wheres)->delete();	
		   $obj->del_img($obj->id_set($arrId2),'comment_reply'); //删图片
		   
		   $url=U(APP_APPS.'/ExtendsArticle/'.'extendsArticleList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')],'files_id'=>$_GET['files_id'],'article_type'=>$_GET['article_type'],'files_type'=>$_REQUEST['files_type']));
		   $obj->del('article',$action,$url);//删除		
		}
		
		//公共方法,添加修改
		private function PublicMethod(){
			//处理属性
			/*if($_POST['attr']){
				$arr1='-';
				foreach($_POST['attr'] as $v){
					$arr2.=$v.'-';
				}
				$_POST['attr']=$arr1.$arr2;
			}*/
			
			//关联文章ID
			if($_POST['selectRight']){
				$take=join(',',$_POST['selectRight']);
				$_POST['take']=$take;
			}
			
			if(empty($_POST['thumb'])){
				$_POST['thumb']=$_POST['imgAll'][0] ? $_POST['imgAll'][0] : '';
				$_POST['thumSave']='';
			}else{
				$_POST['thumSave']=$_POST['thum'];
			}	
			//这里添加更多的其它图片
			$_POST['imgArray']=array($_POST['thumSave']);
		}
		
		//文档列表，审核和待审核公共
	  private function rootArticleList(){
		    $filesId=getNum($_GET['files_id']);
		    $f='files_id,'.pfix('files_name').' AS files_name,files_pid,files_type,files_sort,article_type,article_fns';
		    $filesContent=M('files_sort')->field($f)->where(array('display'=>1))->order(array('files_sort'=>'ASC'))->select();
			$fn=array();
			foreach($filesContent as $k=>$v){
				if($v['files_id']==$filesId){
				   $fn=$v;
				   break;
				}
			}
			if(empty($fn)) return_json(300,'非法操作');
			$fn['article_fns']=$fn['article_fns'] ? explode(',',$fn['article_fns']) : array();
			//p($fn);
			$this->fn=$fn;	  
			//获取当前ID的子级分类
		    $child=array();
			foreach($filesContent as $k=>$v) {
				if($v['files_pid']==$fn['files_id']){
					$child[$k]['files_id']=$v['files_id'];
					$child[$k]['files_name']=$v['files_name'];
				}
			}
			//分类检索
			$this->fileChild=$child;
	  	    $curd=D('Curd'); //CURD类
			$Relations=D('ArticleRelation'); //关联类
			$where=$curd->search(pfix('titletext')); //询查条件返回数组
	    	$order=$this->orderBy();//临时排序，返回数组
			if($fn['article_type']==0) $order=$order ? $order : array('orders'=>'ASC');	
	    	else $order=$order ? $order : array('addtime'=>'DESC');			
            //查询条件 
			if($_REQUEST['cate_id'] > 0){
				$ids=$_REQUEST['cate_id'];
			}else{
				if($child) $ids=in_id($child,'files_id');
				else $ids=$fn['files_id'];
			}
			$where['searchArr']['files_id']=array('IN',$ids);
			$where['searchArr']['is_show_bg']=1;
			$counts=$Relations->where($where['searchArr'])->relation(true)->count();
			$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
			$Article=$Relations->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
			$this->Article=$Article;
			//p($Article);die;
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
	 }

	//分类列表
    public function extendsCateList(){
   	   $fielsWhere['files_path']=array('LIKE',"%-{$_GET['caet_id']}-%");
	   $fielsWhere['_logic']='OR';
	   $fielsWhere['files_id']=$_GET['caet_id'];	    	
       $nodeArr=M('files_sort')->where($fielsWhere)->order(array('files_sort'=>'ASC'))->select();
       import('Class.Category',APP_PATH);
       $this->Cate=Category::unlimitedForLevel($nodeArr,'files_id','files_pid');	   
	   $this->caet_id=$_GET['caet_id'];
       $this->display();
    }
	
	//添加分类
	public function extendsCateAdd(){
        $this->pid=I('files_pid',0,'intval');
         $id=I('files_pid',0,'intval');
         if(is_numeric($id) && $id > 0){
             $field=array('files_id','files_path','files_type');
             $path=M('files_sort')->field($field)->where(array('files_id'=>$id))->find();
             $this->files_path=$path['files_path'];
			 $this->files_pid=$id;
			 $this->files_type=$path['files_type'];
         }else{
             $this->files_path=0;
			 $this->files_pid=0;
         }


		$this->display();
	}	

	//添加分类表单处理
	public function extendsCateAddForm(){
		if($_POST['files_pid']==0){
			if(M('files_sort')->where(array('virtual_table'=>$_POST['virtual_table']))->find()) return_json(300,'类别重复，请检查后再提交？');
		}
        $id=getNextId(PREFIX.'files_sort');//获得表的新增时的ID;
        path_padding($id,'files_pid','files_path','files_padding'); //无限级分类路径和左缩进处理
        D('Curd')->insert('files_sort','extendsCateList/caet_id/'.$_POST['caet_id']);//插入	
	}
		
	public function extendsCateSave(){
	    //修改单个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
	}
	
	//删除分类
	public function extendsCageDelete(){
		$where['files_path']=array('LIKE','%-'.$_GET['ids'].'-%');
		$where['_logic']='OR';
		$where['files_id']=$_GET['ids'];
		
		//检查分类是否已使用
		$catId=M('files_sort')->field('files_id')->where($where)->select();
		import('Class.Category',APP_PATH);
		$catIds=Category::unlimitedForInId($catId,'files_id');
		if(M('cms_content')->where(array('files_id'=>array('in',$catIds)))->find()) return_json(300,'你要删除的分类已被文档使用，你不能进行删除！');

		if(M('files_sort')->where($where)->delete()){
			return_json(200,'操作成功！','cateList','','forward',U(APP_APPS.'/ExtendsArticle/extendsCateList',array('caet_id'=>$_GET['caet_id'])));
		}else{
			return_json(300,'操作失败！');
		}
		
	}    
	
	//检索关联文章
	private function relation(){
	   $filesId=$_GET['catIdVal'];
	   $Keywords=$_GET['Keywords'];
	   if(empty($filesId)) $fids=in_id(M('files_sort')->field('files_id')->where(array('files_type'=>5))->select(),'files_id');
	   else $fids=$filesId;
	   if($fids) $where="files_id IN({$fids})";
	   if($Keywords) $where.=" AND ".pfix('titletext')." LIKE '%{$Keywords}%'";
	   $result=M('article')->field('id,'.pfix('titletext').' AS titletext')->where($where)->select();
	   die(json_encode($result));
	}
}
?>