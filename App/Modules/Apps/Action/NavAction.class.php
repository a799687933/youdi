<?php
class NavAction extends CommonAction{
    
    //导航列表
    public function index(){
        $cate=M('nav')->order("nav_sort ASC")->select();
        import('Class.Category',APP_PATH);//无限级分类类
        $this->navArr=\Category::unlimitedForLevel($cate,'nav_id','nav_pid');

        $this->display();
    }
    
    //添加导航界面
    public function navAdd(){
        $this->nav_pid=I('nav_pid',0,'intval');
        $id=I('nav_pid',0,'intval');
         if(is_numeric($id) && $id > 0){
             $field=array('nav_path');
             $path=M('nav')->field($field)->where(array('nav_id'=>$id))->select();
             if($path){
                 $this->nav_path=$path[0]['nav_path'];
              }else{
                  $this->nav_path=I('nav_path',0,'intval');
              }
         }else{
             $this->nav_path=0;
         }
        $this->display();
    }
    
    //添加导航表单处理
    public function navAddForm(){
        
        $db=M('nav');
 
            $id=getNextId(PREFIX.'nav');//获得表的新增时的ID
            
            if($_POST['nav_pid']){
               $nav_sort=$db->field(array('nav_sort'))->where("nav_id='".$_POST['nav_pid']."'")->select();
               $_POST['nav_sort']=$nav_sort[0]['nav_sort'];
            }
            path_padding($id,'nav_pid','nav_path','nav_padding'); //无限级分类路径和左缩进处理          
            $_POST['nav_addtime']=time();
			D('Curd')->insert('nav','index');//插入 
    }
    
    //修改导航界面
    public function navSave(){
    	
        $save=M('nav')->where(array('nav_id'=>$_GET['nav_id']))->find();
        if($save){
            $this->save=$save;
            $this->display(); 
        }else{
            return_json(300,L('InfoError'));
        }
        
    }
    
    //修改导航表单处理
    public function navSaveForm(){
           if($_GET['one']=='ok'){
           	   $this->saveOen();
           }else if($_GET['yes_no']=='ok'){
           	   $this->saveSwitch();
           }else{
	           D('Curd')->modify('nav','index'); //修改数据
		   }
    }
    
    //删除导航
    public function navDelete(){
    	
        if(IS_AJAX){
            $where['nav_path']=array('LIKE','%-'.$_REQUEST['ids'].'-%');
			$where['_logic']='OR';
			$where['nav_id']=$_REQUEST['ids'];
            if(M('nav')->where($where)->delete()){
               return_json(200,'删除成功！','index','','forward',U(APP_APPS.'/Nav/index'));
            }else{
               return_json(300,'删除失败！');  
            }
        }
    }
}
?>