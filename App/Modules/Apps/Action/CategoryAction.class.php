<?php
class CategoryAction extends CommonAction {
	
	//分类列表
    public function cateList(){
       $nodeArr=M('files_sort')->order(array('files_sort'=>'ASC'))->select();
       import('Class.Category',APP_PATH);
       $this->Cate=Category::unlimitedForLevel($nodeArr,'files_id','files_pid');	   
       $this->display();
    }
	
	//添加和修改分类
	public function cateAdd(){
		if($_GET['cate_id']){//修改
			     $res=M('files_sort')->where(array('files_id'=>$_GET['cate_id']))->find();
	             $this->files_path=$res['files_path'];
				 $this->files_pid=$res['files_pid'];
				 $this->road=$res['virtual_table'];
				 $this->files_type=$res['files_type'];
				 $this->articleFns=$res['article_fns'] ? explode(',',$res['article_fns']) : array();
		}else{ //新增
	        $this->pid=I('files_pid',0,'intval');
	         $id=I('files_pid',0,'intval');
	         if(is_numeric($id) && $id > 0){
	             $field=array('files_id','files_path','files_type,article_fns');
	             $path=M('files_sort')->field($field)->where(array('files_id'=>$id))->find();
				 $this->articleFns=$path['article_fns'] ? explode(',',$path['article_fns']) : array();
	             $this->files_path=$path['files_path'];
				 $this->files_pid=$id;
				 $this->road=$path['virtual_table'];
				 $this->files_type=$path['files_type'];
	         }else{
	             $this->files_path=0;
				 $this->files_pid=0;
	         }
		}
        $this->nameArr=$this->langIsMore('files_sort','files_name'); //分类名称多语言
        $this->res=$res;
		$this->display();
	}
	
	//添加分类表单处理
	public function cateAddForm(){
	/*	if($_POST['files_pid']==0){
			if(M('files_sort')->where(array('virtual_table'=>$_POST['virtual_table']))->find()) return_json(300,'类别重复，请检查后再提交？');
		}*/
		
		if($_POST['article_fns'][0]){
			$_POST['article_fns']=implode(',',$_POST['article_fns']);
		}else{
			$_POST['article_fns']=0;
		}
		if($_POST['files_id']){ //修改
			D('Curd')->modify('files_sort','cateList','','files_id',array($_POST['files_pic']));//修改	
		}else{//新增
		    $this->requestArray('files_sort');
			$_POST['f_url']='Category/index';
	        $id=getNextId(PREFIX.'files_sort');//获得表的新增时的ID;
	        path_padding($id,'files_pid','files_path','files_padding'); //无限级分类路径和左缩进处理
	        D('Curd')->insert('files_sort','cateList',array($_POST['files_pic']));//插入	
		}
	}
	
	
	public function cateSave(){
	    //修改单个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
	}
	
	//删除分类
	public function cageDelete(){
		$where['files_path']=array('LIKE','%-'.$_GET['ids'].'-%');
		$where['_logic']='OR';
		$where['files_id']=$_GET['ids'];
		
		//检查分类是否已使用
		$catId=M('files_sort')->field('files_id')->where($where)->select();
		import('Class.Category',APP_PATH);
		$catIds=Category::unlimitedForInId($catId,'files_id');
		if(M('cms_content')->where(array('files_id'=>array('in',$catIds)))->find()) return_json(300,'你要删除的分类已被文档使用，你不能进行删除！');

		if(M('files_sort')->where($where)->delete()){
			return_json(200,'操作成功！','cateList','','forward',U(APP_APPS.'/Category/cateList'));
		}else{
			return_json(300,'操作失败！');
		}
		
	}
}