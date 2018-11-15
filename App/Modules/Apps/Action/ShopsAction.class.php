<?php
// 本类由系统自动生成，仅供测试用途

class ShopsAction extends CommonAction {
    private $shopsWebAll;  //前端商铺网站总目录(shopStyle)
	private $shopApp;  //系统模板
	public function __construct (){
	     parent::__construct();
		 $this->shopsWebAll=C('SHOPS_SET_TPL');
		 $this->shopApp=C('SHOPS_TPL');
	}
	
	//已审商铺列表
	public function shopsList(){
		$this->allList(1);
		$this->display();
	}	
	//未审商铺列表
	public function shopNotAudit(){
		$this->allList(0);
		$this->display('Shops/shopNotAudit');
	}	
	
	//审核未通过
	public function shopsNotPass(){
		$this->allList(-1);
		$this->display('Shops/shopsNotPass');	
	}
	
	 //公共列表
    private function allList($type){
       $curd=D('Curd');	
       $where=$curd->search('shop_name','city'); //询查条件返回数组
       $order=$this->orderBy();//临时排序，返回数组
       $order=$order ? $order : array('id'=>'DESC');    
	   if($type==1){
	   	  $where['searchArr']['display']=1;
	   }else if($type==0){
	   	  $where['searchArr']['display']=0;
	   } else if($type==-1){
		   $where['searchArr']['display']=-1;
	   } 
       $counts=D('ShopsRelation')->where($where['searchArr'])->relation(true)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $field="id,city,catalog,shop_name,is_ls,member_id,display,shop_close,"
	   ."shop_time,shops_type,recommend,auth,bail,is_verify,is_edit_tpl,agent_shops_id,shop_style";
	   $this->shops=D('ShopsRelation')->field($field)->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
       $this->City=$curd->citySelect(1); //城市列表
       //$cage=$curd->cage_select('files_sort','files_id,files_name,files_pid','files_pid = 2','files_sort asc','files_id','files_pid'); //检索分类
       //$this->cage=M('files_sort')->where(array('files_pid'=>1))->field('files_id,files_pid,files_name')->order(array('files_sort'=>'ASC'))->select();
	   $this->url=$where['url']; //查询条件分页URL
	   $this->searchVal=$where['keyword']; //保存输入框的关键字       
    }
	
	//添加/修改商铺
	public function shopAddSave(){
		$this->City=D('Curd')->citySelect(1); //城市列表
		$this->cates=M('goods_category')->where(array('pid'=>0))->order(array('reorder'=>'ASC'))->select();
		
		//选择商铺模板		
		$this->tpls=getDir($this->shopApp); //选择模板
         if($_GET['id']){
			 $save=M('shops')->where(array('id'=>$_GET['id']))->find();
			 $save['cate_id']=explode(',',$save['cate_id']);
			 $this->save=$save;
		 }
		 $model=new CommonModel();
		//国家、省、城市、区县   
		$this->region=$model->getRegion($save['country'],$save['province'],$save['city'],$save['district']); 		 
		$this->display();
	}
	
	//添加/修改商铺表单处理
	public function shopAddSaveForm(){
			if($_GET['one']=='ok'){
				$this->saveOen();
			}else if($_GET['yes_no']=='ok'){
				$this->saveSwitch();
			}	
			//检索下级城市
			if($_GET['city']=='ajax'){
				$model=new CommonModel();
				return $model->searchRegion();			
				die();
			}  			
		  $this->chkMemberId(); //检测用户是否有效
		  
		  $imgArr=array($_POST['shop_logo'],$_POST['license'],$_POST['image'],$_POST['id_positive'],$_POST['id_behind']);
		  $ditorContent=array($_POST['synopsis'],$_POST['contact']);
		  $_POST['cate_id']=implode(',',$_POST['cate_id']);
		  if(!$_POST['shop_size']) $_POST['shop_size']=100;
          if(!$_POST['id']){  //新增
		      $_POST['shop_time']=time();
		      $id=getNextId(PREFIX.'shops'); //获取新增商户的ID
			  
			  //生成商铺
			  recurse_copy($this->shopApp.$_POST['shop_style'],$this->shopsWebAll.($_POST['catalog']));
			  	
			  //生成默认广告
			  defaultDa($_POST['catalog'],$id);
			  
			  if($_POST['launched_files']){
				   $this->runDfault($id);  //生成默认分类				  
			   }
			   
			   //把普通会员改为商家
			   M('member')->where(array('id'=>$_POST['member_id']))->save(array('user_type'=>1));
			   //M('shops')->add($_POST); //新增商户表
			  // return_json(200,'操作成功！',$_REQUEST['action'],'','closeCurrent');
		      D('Curd')->insert('shops',$_REQUEST['action'],$imgArr,$ditorContent);//插入
			  
		  }else{  //修改
		      //如果还没有生成商铺
		      if(!is_dir($this->shopsWebAll.($_POST['catalog']))){
			      recurse_copy($this->shopApp.$_POST['shop_style'],$this->shopsWebAll.($_POST['catalog']));	
			  }else{
				  //如果商户没有修改模板权限，则重新生成商铺
				  if(!$_POST['is_edit_tpl']) recurse_copy($this->shopApp.$_POST['shop_style'],$this->shopsWebAll.($_POST['catalog']));				  
			  }
			  
			   //如果商户没有广告则生成默认广告
			   if(!M('shops_ad')->field('ad_id')->where(array('shops_id'=>$_POST['id']))->find()){
			       defaultDa($_POST['catalog'],$_POST['id']);
			   }

			  if($_POST['launched_files']){
				  if(!M('shops')->field('id')->where(array('shops_id'=>$_POST['id'],'launched_files'=>1))->find()){
					   $this->runDfault($_POST['id']);  //生成默认分类
				  }else{
					  if(!M('shops_files_sort')->where(array('shops_id'=>$_POST['id']))->find()){
					      $this->runDfault($_POST['id']);  //生成默认分类
					  }else{
						  M('shops_files_sort')->where(array('shops_id'=>$_POST['id']))->save(array('disables'=>1));
						  M('shops_article')->where(array('shops_id'=>$_POST['id']))->save(array('disables'=>1));					  
					  }
				  }
			  }else{
			      M('shops_files_sort')->where(array('shops_id'=>$_POST['id']))->save(array('disables'=>0));
				  M('shops_article')->where(array('shops_id'=>$_POST['id']))->save(array('disables'=>0));
			  }
			 // M('shops')->where(array('id'=>$_POST['id']))->save($_POST); //新增商户表
			//  return_json(200,'操作成功！',$_REQUEST['action'],'','closeCurrent');			  
		      D('Curd')->modify('shops',$_REQUEST['action'],'','id',$imgArr,$ditorContent);
		  }
	}
	
	//批量更新商户模板
	public function updateShopsTpl(){
		set_time_limit(0);
		$shopIds=M('shops')->field('catalog')->where(array('is_edit_tpl'=>0,'display'=>1,'shop_close'=>1,'shop_style'=>'Default'))->select();
		if($shopIds && is_dir($this->shopApp.'Default')){
			$info=true;
			foreach($shopIds as $k=>$v){
				recurse_copy($this->shopApp.'Default',$this->shopsWebAll.$v['catalog']);		   
			}			
		}else{
			$info=false;
		}
		if($info){
			return_json(200,'批量更的成功');
		}else{
			return_json(300,'批量更的失败');
	    }
	}
	
	//删除商铺
	public function shopDelete(){
           set_time_limit(0); //脚本运行最长时间
		   
		   $url=U(APP_APPS.'/Shops/'.$_REQUEST['action'],array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
		   
		   //删除商品数据
		   $myGoods=M('goods');
		   $goods=$myGoods->field('id')->where(array('shops_id'=>array('in',$_REQUEST['ids'])))->select();
		   $goodsId=in_id($goods,'id');
		   $myGoods->where(array('shops_id'=>array('in',$_REQUEST['ids'])))->delete();
		   
		   //删除my_album数据和图片
		   $myAlbum=M('album');
		   $albnm=$myAlbum->field('img')->where(array('table_name'=>'goods','tableId'=>array('in',$goodsId)))->select();
		   $myAlbum->where(array('table_name'=>'goods','tableId'=>array('in',$goodsId)))->delete();
		   foreach($albnm as $k=>$v){
		       @unlink('./'.$v['img']);
		   }
		   
		   //删除my_entire_img数据和图片
		   $myEntireImg=M('entire_img');
		   $entireImg=$myEntireImg->field('img')->where(array('table_name'=>'goods','tableId'=>array('in',$goodsId)))->select();
		   $myEntireImg->where(array('table_name'=>'goods','tableId'=>array('in',$goodsId)))->delete();
		   foreach($entireImg as $k=>$v){
		       @unlink('./'.$v['img']);
		   }
		   
		   //删除文章分类表
		   M('shops_files_sort')->where(array('shops_id'=>array('in',$_REQUEST['ids'])))->delete(); 
		   
		   //删除商品关联表
		   M('goods_link')->where(array('goods_id'=>array('in',$inId)))->delete();			   
		   		   
		   //删除商铺网站内容
		   $myShops=M('shops');
		   $shopStyle=$this->shopsWebAll;
		   $res=$myShops->field('catalog,id_positive,id_behind,shop_logo,license,index_ad')->where(array('id'=>array('in',$_REQUEST['ids'])))->select();
		   foreach($res as $k=>$v){
			   rmdirs($shopStyle.$v['catalog']); 
			   @unlink('./'.$v['id_positive']);
			   @unlink('./'.$v['id_behind']);
			   @unlink('./'.$v['shop_logo']);
			   @unlink('./'.$v['license']);
			   @unlink('./'.$v['index_ad']);
		   }
		   $myEntireImg->where(array('table_name'=>'shops','tableId'=>array('in',$_REQUEST['ids'])))->delete();
		   $myShops->where(array('id'=>array('in',$_REQUEST['ids'])))->delete();
	       return_json(200,'操作成功！','','','forward',$url);   
	}
	
	//检测会员ID是否有效
	public function chkMemberId(){
		$m='';
		if(M('member')->field('id')->where(array('id'=>$_REQUEST['member_id']))->find()) $m=true;
		if($m){
			if($_REQUEST['id']){
				 if(M('shops')->field('id')->where(array('member_id'=>$_REQUEST['member_id'],'id'=>array('neq',$_REQUEST['id'])))->find()) {
					 return_json(300,'此用户ID号的商铺已存在');
				 }
				 if($_REQUEST['catalog']){
					 if(M('shops')->field('id')->where(array('catalog'=>$_REQUEST['catalog'],'id'=>array('neq',$_REQUEST['id'])))->find()) {
						 return_json(300,'此用户商铺地址已存在');
					 }
				 }
			}else{
				 if(M('shops')->field('id')->where(array('member_id'=>$_REQUEST['member_id']))->find()) return_json(300,'此用户ID号的商铺已存在');
				 if($_REQUEST['catalog']){
					  if(M('shops')->field('id')->where(array('catalog'=>$_REQUEST['catalog']))->find()) return_json(300,'此用户商铺地址已存在');			
				 }				 	
			}
		}else{
		    return_json(300,'不存在此用户ID号');
		}
	}
	
	//前端商铺模板文件
	public function shopTpls(){
	    $res=M('shops')->field('catalog')->where(array('id'=>$_GET['id']))->find();
		$dir=$this->shopsWebAll.$res['catalog'];//商铺网站根目录
		
		//获取商铺模板文件
		$publicFile=getDirFile($dir.'/Public'); //获取公共文件
		$htmlFile=getDirFile($dir); //获取HTML		
		$jsFile=getDirFile($dir.'/Js'); //获取Js文件
		$imagesFile=getDirFile($dir.'/Images'); //获取Images文件
		$cssFile=getDirFile($dir.'/Css'); //获取Css文件	
		$dir=ltrim($dir,'.');
		$publics=array();
		foreach($publicFile as $k=>$v){
			$publics[$k]['files']=$v;
			$publics[$k]['files_path']=$dir.'/Public'.'/'.$v;
		}
		
		$html=array();
		foreach($htmlFile as $k=>$v){
			$html[$k]['files']=$v;
			$html[$k]['files_path']=$dir.'/'.$v;
		}

		$js=array();
		foreach($jsFile as $k=>$v){
			$js[$k]['files']=$v;
			$js[$k]['files_path']=$dir.'/Js/'.$v;
		}

		$images=array();
		foreach($imagesFile as $k=>$v){
			$images[$k]['files']=$v;
			$images[$k]['files_path']=$dir.'/Images/'.$v;
		}

		$css=array();
		foreach($cssFile as $k=>$v){
			$css[$k]['files']=$v;
			$css[$k]['files_path']=$dir.'/Css/'.$v;
		}
		$this->publics=$publics;
		$this->html=$html;
		$this->js=$js;
		$this->images=$images;
		$this->css=$css;
		$this->id=$_GET['id'];
		$this->upPath='.'.$dir;
	    $this->display();
	}
	
	//修改前端商铺模板文件
	public function saveTpl(){
       $this->url=$_GET['url'];
	   $this->display();
	}
	
	//框架显示
	public function iframe(){
	   $url= base64_decode($_GET['url']);
	   $file=file_get_contents('.'.$url);
	   $file=str_ireplace(C('TOP_FOOTER_STR_JS'),'</body>',$file); //修改前先去掉强制包含内容
	   $file=str_ireplace(C('SHOPS_JQUERY'),'</title>',$file); //修改前先去掉强制引入jquery
	   $this->files=$file;
	   $this->fileName=$url;	
	   $this->display();
	}
	
	//修改修改前端商铺模板文件表单处理
	public function saveTplForm(){
		 $file=$_POST['files_name'];
		 $content=$_POST['files'];
		 if(    strpos(strtoupper($file),'/CSS') ===false   &&   strpos(strtoupper($file),'/JS') ===false      &&   strpos(strtoupper($file),'/PUBLIC') ===false    ){
			 if(!preg_match('/\<\/title\>/Uis',$content)) return_json(300,'板模&lt;/title&gt;标签错误'); //板模标签缺少</title>
			 if(!preg_match('/\<body(.*)\>/Uis',$content)) return_json(300,'板模&lt;body&gt;标签错误'); //板模标签缺少<body>
			 if(!preg_match('/\<\/body\>/Uis',$content)) return_json(300,'板模&lt;/body&gt;标签错误'); //板模标签缺少</body>		 
		 }		 
		 if(preg_match('/\<\?|\?\>|\<php(.*)\>|\<\/php(.*)\>/Uis',$content)) return_json(300,'请不要出现php敏感标签！');
		 $content=str_ireplace('</body>',C('TOP_FOOTER_STR_JS'),$content); //修改后强制包含内容
		 $content=str_ireplace('</title>',C('SHOPS_JQUERY'),$content); //修改后强制引入jquery		 
	     file_put_contents('.'.$file,$content);
		 $this->success('保存成功');
		// return_json(200,'操作成功！','','','closeCurrent');
	}
	
	//删除图片
	public function delImg(){
	   $url= base64_decode($_GET['url']);
	   if(unlink('.'.$url)){
		  return_json(200,'操作成功！','','','forward',U(APP_APPS.'/Shops/shopTpls',array('id'=>$_GET['id'])));
	   }else{
		  return_json(300,'删除失败！'); 
	   }
	   
	}
	
	//商户文章类别
	public function shopCateList(){
	   $shopsId=$_GET['id'];
       $nodeArr=M('shops_files_sort')->where(array('shops_id'=>$shopsId))->order(array('files_sort'=>'ASC'))->select();
       import('Class.Category',APP_PATH);
       $this->Cate=Category::unlimitedForLevel($nodeArr,'files_id','files_pid');	  	
	   $this->shopId=$shopsId;
	   $this->display();
	}	

	//添加和修改商户章类别
	public function shopCateAdd(){
		if($_GET['cate_id']){//修改
			$res=M('shops_files_sort')->where(array('files_id'=>$_GET['cate_id'],'shops_id'=>$_GET['shops_id']))->find();
	             $this->files_path=$res['files_path'];
				 $this->files_pid=$res['files_pid'];
				 $this->files_type=$res['files_type'];
		}else{ //新增
	        $this->pid=I('files_pid',0,'intval');
	         $id=I('files_pid',0,'intval');
	         if(is_numeric($id) && $id > 0){
	             $field=array('files_id','files_path','files_type');
	             $path=M('shops_files_sort')->field($field)->where(array('files_id'=>$id,'shops_id'=>$_GET['shops_id']))->find();
	             $this->files_path=$path['files_path'];
				 $this->files_pid=$id;
				 $this->files_type=$path['files_type'];
	         }else{
	             $this->files_path=0;
				 $this->files_pid=0;
	         }
		}
		$this->shopsId=$_GET['shops_id'];
        // $this->nameArr=$this->langIsMore('files_sort','files_name'); //分类名称多语言
         $this->res=$res;
		$this->display();
	}
	
	//添加/修改商户文章分类表单处理
	public function shopCateAddForm(){
	    //修改单个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
		
		if($_POST['files_id']){ //修改
			D('Curd')->modify('shops_files_sort','shopCateList','','files_id',array($_POST['files_pic']));//修改	
		}else{//新增
			$_POST['f_url']='Category/index';
	        $id=getNextId(PREFIX.'files_sort');//获得表的新增时的ID;
	        path_padding($id,'files_pid','files_path','files_padding'); //无限级分类路径和左缩进处理
	        D('Curd')->insert('shops_files_sort','shopCateList',array($_POST['files_pic']));//插入	
		}
	}	
	
	//删除商户文章分类
	public function cageDelete(){
		$M=M('shops_files_sort');
		$where="(files_path LIKE '%-{$_GET['ids']}-%' OR files_id='{$_GET['ids']}') AND shops_id='{$_GET['shops_id']}'  ";
		if($M->field('files_id')->where(array('files_id'=>$_GET['ids'],'files_pid'=>0))->find()) return_json(300,'根文档分类不能删除！');
		//检查分类是否已使用
		$catId=$M->field('files_id')->where($where)->select();
		import('Class.Category',APP_PATH);
		$catIds=Category::unlimitedForInId($catId,'files_id');
		if(M('shops_article')->where(array('files_id'=>array('in',$catIds)))->find()) return_json(300,'你要删除的分类已被文档使用，你不能进行删除！');

		if($M->where($where)->delete()){
			return_json(200,'操作成功！','shopCateList','','forward',U(APP_APPS.'/Shops/shopCateList',array('id'=>$_REQUEST['shops_id'])));
		}else{
			return_json(300,'操作失败！');
		}
		
	}	

	//调用生成默认分类
	private function  runDfault($id){
		  //默认文章分类
		  $classify=array(
			0=>array('files_name'=>'行业动态','files_name_en'=>'News'),
			1=>array('files_name'=>'集团新闻','files_name_en'=>'News')
			);
		  $busId=1; //商家ID
		  $type=0;  //文章类型
		  $typeName='网站文章分类';	
		  $this->defaultData($classify,$id,0,$typeName);
		  
		  //默认网店帮助
		$helpList=array(
			0=>array('files_name'=>'新手上路','files_name_en'=>'Novice'),
			1=>array('files_name'=>'配送与支付','files_name_en'=>'Payment'),
			2=>array('files_name'=>'服务保证','files_name_en'=>'Guarantee'),
			3=>array('files_name'=>'联系我们','files_name_en'=>'Contact us')
		);
		$helpType='网店帮助';
		$this->defaultData($helpList,$id,1,$helpType);		
	}	
	
	/**
	商户默认文章分类
	*$classify  分类数组
	*$busId      商户ID
	*$type        文章类型
	*$typeName  主分类名称
	*/
	private function defaultData($classify,$busId,$type,$typeName){
			$data=array();
			$nextId=getNextId(PREFIX.'shops_files_sort');
			$data['files_name']=$typeName;
			$data['files_name_en']='classify';
			$data['files_pid']='0';  
			$data['files_pic']=''; 
			$data['files_type']=$type; 
			$data['files_bewrite']=$typeName; 
			$data['display']=1; 
			$data['files_sort']=0; 
			$data['files_path']='0-'.$nextId; 
			$data['files_padding']='0'; 
			$data['shops_id']=$busId; 
			$nextId2=getNextId(PREFIX.'shops_files_sort');
			$M=M('shops_files_sort');
			$M->add($data);
			unset($data);
			foreach($classify as $k=>$v){
				$data[$k]['files_name']=$v['files_name'];
				$data[$k]['files_name_en']=$v['files_name_en'];
				$data[$k]['files_pid']=$nextId;  
				$data[$k]['files_pic']=''; 
				$data[$k]['files_type']=$type; 
				$data[$k]['files_bewrite']=$v['files_name'];; 
				$data[$k]['display']=1; 
				$data[$k]['files_sort']=0; 
				$data[$k]['files_path']='0-'.$nextId.'-'.$nextId2; 
				$data[$k]['files_padding']=30; 
				$data[$k]['shops_id']=$busId; 		
			}
			$M->addAll($data);
	}

}