<?php
class ArticleAction extends CommonAction{
		
		//已审核文档列表
		public function articleList(){
            $this->rootArticleList(1);
			$this->display();
		}

		//未审核文档列表
		public function notArticleList(){
            $this->rootArticleList(0);
			$this->display('Article/articleList');
		}
				
		//添加和修改文档界面
		public function addSave(){
		//	$catWhere['virtual_table']=array('in',C('GET_FILES_CLASS'));//指定文档分类
			$this->cage=D('Curd')->cage_select('files_sort','files_id,'.pfix('files_name').' AS files_name,files_pid','files_type=3 OR files_type=4 OR files_type=5','files_sort asc','files_id','files_pid'); //分类
			$this->attr=M('article_attr')->order(array('order_sort'=>'asc'))->select();//获取属性
			if($_GET['id']){ //修改
			    //获取表内容	
			    $save=M('article')->where(array('id'=>$_GET['id']))->find();
			    $save['attr']=explode('-', $save['attr']);//属性
				
				//获取关联文章
				$where=array();
				$where['id']=array('in',$save['take']);
				$this->take=M('article')->where($where)->select();
				
				//获取相关相册
				$this->photo=M('album')->where(array('tableId'=>$_GET['id'],'table_name'=>'article'))->select();	
				
				$this->save=$save;
						
			}
			$this->titletexts=$this->langIsMore('article','titletext'); //标题多语言
			$this->keywordss=$this->langIsMore('article','keywords'); //关键字多语言
			$this->authors=$this->langIsMore('article','author'); //作者多语言
			$this->bewrites=$this->langIsMore('article','bewrite'); //描述多语言
			$this->contents=$this->langIsMore('article','content'); //文档内容多语言
			$this->display();
		}
		
		//添加和修改文档表单处理
		public function addSaveForm(){
				if($_GET['one']=='ok'){
					$this->saveOen();
				}else if($_GET['yes_no']=='ok'){
					$this->saveSwitch();
				}						
			  $_POST['addtime']=time();
			  $_POST['scan_time']=time();
			  $this->PublicMethod();//处理提交的数据
			  $action=$_POST['is_show'] ? 'articleList' : 'notArticleList';
			 // p($_POST);die;
			  if($_POST['id']){  //修改处理
			  	  D('Curd')->modify('article',$action,'','id',array($_POST['thumb']),'content');
			  }else{   //新增处理
			     $this->requestArray('article');
			  	 D('Curd')->insert('article',$action,array($_POST['thumb']),'content');//插入
			  }		  
		}
				
		//删除文档
		public function articleDelete(){
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
		   
		   $action=$_REQUEST['is_show'] ? 'articleList' : 'notArticleList';
		   $url=U(APP_APPS.'/Article/'.$action,array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
		   $obj->del('article',$action,$url);//删除		
		}
		
		//公共方法,添加修改
		private function PublicMethod(){
			//处理属性
			if($_POST['attr']){
				$arr1='-';
				foreach($_POST['attr'] as $v){
					$arr2.=$v.'-';
				}
				$_POST['attr']=$arr1.$arr2;
			}
			
			//关联文章ID
			if($_POST['selectRight']){
				$take=join(',',$_POST['selectRight']);
				$_POST['take']=$take;
			}
			
			if(empty($_POST['thum'])){
				$_POST['thum']=C(GOODS_IMG);
				$_POST['thumSave']='';
			}else{
				$_POST['thumSave']=$_POST['thum'];
			}	
			
			//这里添加更多的其它图片
			$_POST['imgArray']=array($_POST['thumSave']);
		}
		
		//文档列表，审核和待审核公共
	  private function rootArticleList($isShow){
	  	    $curd=D('Curd'); //CURD类
			$Relations=D('ArticleRelation'); //关联类
			$where=$curd->search(pfix('titletext')); //询查条件返回数组
	    	$order=$this->orderBy();//临时排序，返回数组
	    	$order=$order ? $order : array('addtime'=>'DESC');			

		    //获取文档分类的ID集合
		   // $catWhere['virtual_table']=array('in',C('GET_FILES_CLASS'));//指定要显示的分类		
		    if($_REQUEST['files_id']){
		    	 $fielsWhere['files_path']=array('LIKE',"%-{$_REQUEST['files_id']}-%");
				 $fielsWhere['_logic']='OR';
				 $fielsWhere['files_id']=$_REQUEST['files_id'];
		    	 $catId=M('files_sort')->field('files_id')->where($fielsWhere)->select();
				 $ids=$curd->id_set($catId,'files_id');
				 $catUrl="/files_id/".$_REQUEST['files_id'];
		    }else{
		    	 $catId=M('files_sort')->field('files_id')->where($catWhere)->select();
				 $ids=$curd->id_set($catId,'files_id');	    	
		    }
			$where['searchArr']['files_id']=array('IN',$ids);
			$where['searchArr']['is_show']=$isShow; 
			
			$counts=$Relations->where($where['searchArr'])->relation(true)->count();
			$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
			$this->Article=$Relations->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
		
            $this->cage=$curd->cage_select('files_sort','files_id,'.pfix('files_name').' AS files_name,files_pid','files_type=3 OR files_type=4 OR files_type=5','files_sort asc','files_id','files_pid'); //检索分类
			$this->url=$where['url'].$catUrl; //查询条件分页URL
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
			$this->is_show=	$isShow;			
	 }
}
?>