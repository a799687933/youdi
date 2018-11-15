<?php
class RbacAction extends CommonAction{
   
    //角色列表
    public function role(){
        nonlicet_url();//URL检测
        $this->role=M('role')->select();
        $this->display();
    }
    //添加角色
    public function roleAdd(){
        nonlicet_url();//URL检测
        $this->display();
    }
    
    //添加角色表单处理
    public function roleaddForm(){
        nonlicet_url();//URL检测
        $db=M('role');
          if($db->where("name='".$_POST['name']."' OR remark='".$_POST['remark']."'")->select()){
             return_json(300,'角色名称重复请重新添加！');
          }
          if($db->add($_POST)){
             return_json(200,'操作成功！','role','','closeCurrent');
          }else{
             return_json(300,'操作失败！');
         }
    }
	
	//修改角色
	public function roleSave(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
	}
    
    //删除角色
    public function roleDel(){
      if(IS_AJAX){
        if($_GET['type']){
          $ids=$_GET['id'];  
        }else{
          $ids=$_POST['ids'];    
        }  
        $where['id']=array('in',$ids);  
        if(M('role')->where($where)->delete()){
            $amp['role_id']=array('in',$ids);
            M('role_user')->where($amp)->delete(); //删除关联表角色和用户
            M('access')->where($amp)->delete();//删除权限表          
            return_json(200,'操作成功！','role','','forward',U( APP_APPS.'/Rbac/role'));
        }else{
            return_json(300,'操作失败！');
        }
      }  
    }
    
    //添加节点
    public function nodeAdd(){
        
        //nonlicet_url();//URL检测
        $this->pid=I('pid',0,'intval');
        $this->level=I('level',1,'intval');
         $id=I('pid',0,'intval');
         if(is_numeric($id) && $id > 0){
             $field=array('path');
             $path=M('node')->field($field)->where(array('id'=>$id))->select();
             $this->path=$path[0]['path'];
         }else{
             $this->path=0;
         }

         switch($this->level){
            case 1:
                 $this->type='添加应用';
                 break;
            case 2:
                 $this->type='添加控制器';
                 break;
            case 3:
                  $this->type='添加方法';
                  break;
        }
        $this->display();

    }
    
    //添加节点表单处理
    public function nodeAddHanlde(){
        //nonlicet_url();//URL检测
        $db=M('node'); 
        $id=getNextId(PREFIX.'node');//获得表的新增时的ID;
        path_padding($id,'pid','path','padding'); //无限级分类路径和左缩进处理
        if($db->add($_POST)){
            return_json(200,'操作成功！','node','','closeCurrent');
        }else{
            return_json(300,'操作失败！');
        }
    }
    
    //节点列表
    public function node(){
        nonlicet_url();//URL检测
         $nodeArr=M('node')->order(array('sort'=>'ASC'))->select();
         import('Class.Category',APP_PATH);
         $this->nodeArr=Category::unlimitedForLevel($nodeArr);
        $this->display();
    }  
   
   //修改节点
   public function saveNode(){
      	  $this->saveOen();
   }
   
    //删除节点
    public function nodeDel(){
        
        nonlicet_url();//URL检测
        $del=M('node');
        $where['id']=array('in',$_POST['ids']);
        $where['pid']=array('in',$_POST['ids']); 
        $where['_logic']='OR';
        
        if($del->where($where)->delete()){
            $nodeId['node_id']=array('in',$_POST['ids']);
            M('access')->where($nodeId)->delete();
            return_json(200,'操作成功！','node','','forward',U(APP_APPS.'/Rbac/node'));
        }else{
            return_json(300,'操作失败！');
        }        
    }
    
    //配置权限列表
    public function access(){
		
     nonlicet_url();//URL检测
      if(IS_AJAX){  
        $id=I('id',0,'intval');
       // $sql="select id,name,title,status,remark,sort,pid,level,padding,concat(path,'-',id) as newpath from ".PREFIX.'node' ." order by newpath asc";
       $sql="select id,name,title,status,remark,sort,pid,level,padding from ".PREFIX.'node' ." order by path asc";
        $node=M();
        $nodeArr=$node->query($sql);
        $this->nodeArr=$nodeArr;
        //读取原有权限
        $access=M('access')->where(array('role_id'=>$id))->getField('node_id',true);//读取一个字段
        foreach($nodeArr as $k=>$v){
            if(array_intersect($access,array($v['id']))){
                $nodeArr[$k]['checked']='checked="checked"';
            }
        }
        $this->nodeArr=$nodeArr;
        $this->id=$id;
        $this->display();
      }  
    }
    
    //修改权限
    public function setAccess(){
        $db=M('access');
            if(empty($_POST['access'])) return_json(300,'请分派权限!');
            $id=I('id',0,'intval');           
            $db->where("role_id=".$id)->delete();          
            foreach($_POST['access'] as $v){
                $tmp=explode('_',$v);
                $data=array(
                    'role_id'=>$id,
                    'node_id'=>$tmp[0],
                    'level'=>$tmp[1]
                 );
                 $db->add($data);
            }           
            return_json(200,'操作成功！','role','','closeCurrent');
    }    
	
	
}
?>