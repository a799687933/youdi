<?php 
// 本类由系统自动生成，仅供测试用途
class UserAction extends CommonAction {

    public function index(){
        //nonlicet_url();//URL检测
        if(isset($_REQUEST['username']) && $_REQUEST['username'] !=''){
        $where['levle']=array('neq',1);
        $where['username']=array('like','%'.$_REQUEST['username'].'%');
        }else{
            $where['levle']=array('neq',1);
        }
        $counts=D('UserRelation')->relation(true)->where($where)->count();
        $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
        $user=D('UserRelation')->relation(true)->where($where)->limit($GLOBALS['limit'])->order("addtime ASC")->select();//关联模型
        //p($user);
        $this->user=$user;//分配数组	
		$this->display();
    }

    //添加用户界面
    public function userAdd(){
         $this->role=M('role')->select();
		 //栏目权限
		 $files=M('files_sort')->field('files_id,'.pfix('files_name').' AS files_name,files_pid,files_padding')->where("display=1")->select();
		 $this->files=unlimitedForLayer($files,'files_pid','files_id','child',0);		
		
         $this->display();        
    }
    
    //添加用户表单处理  
    public function userAddForm(){
        $db=M('user');  
            if(empty($_POST['role_id'])){
               if(IS_AJAX){
                   return_json(300,'你还没有分派权限');
               }else{
                   $this->error('你还没有分派权限');
               }
            }
            //判断管理员重复
            if(M('user')->where(array('username'=>$_POST['name']))->select()){
                if(IS_AJAX){
                    return_json(300,'管理员重复请重新添加！');
                }else{
                    $this->error('管理员重复请重新添加！');
                }
            }
            $userArr=pwd_sha1($_POST['password']);//处理密码和加密随机码
            $userRole=array(
                            'username'=>$_POST['name'],
                            'userpic'=>$_POST['userpic'],
                            'userpic_big'=>$_POST['userpic_big'],
                            'entrydate'=>$_POST['entrydate'],
                            'remarks'=>$_POST['remarks'],
                            'password'=>$userArr['SHA1'],
                            'sha1_random'=>$userArr['RANDOM'],
                            'addtime'=>time(),
                            'lock'=>$_POST['lock']
                            );			
            if($id=M('user')->add($userRole)){//添加用户信息   
			    //RBAC权限
                $db=M('role_user');
                foreach($_POST['role_id'] as $v){
                    $role=array(
                      'role_id'=>$v,
                      'user_id'=>$id
                    );
                    $db->add($role);//写入角色中间表
                }
				//栏目权限
				$this->setColumn($id,0);
                //处理图片
                import('Class.DisposeImg',APP_PATH);
                 DisposeImg::addPictures('user',$id,array($_POST['userpic'],$_POST['userpic_big']),'','','');
                 return_json(200,'操作成功！','index','','closeCurrent');
            }       
    }  


    //修改用户界面
    public function userSave(){
        //p($_GET);die;
        $id=I('id',0,'intval');
        $user=M('user')->where(array('id'=>$id))->find();

        if($user){
            $roleset=M('role_user')->field(array('role_id'))->where(array('user_id'=>$id))->select();//获取原有角色
            if($roleset){
                $arrId=array();
                foreach($roleset as $v){
                    $arrId[]=$v['role_id'];//原有角色ID集
                }
            }
            $roleAll=$this->role=M('role')->select();
            if($roleAll){
                foreach($roleAll as $k=>$v){
                    if(array_intersect(array($v['id']),$arrId)){
                        $roleAll[$k]['checked']='checked="checked"';
                    }
                }
            }
            $this->user=$user;
            $this->role=$roleAll;
			$this->P=$_GET[C('VAR_PAGE')];
		   //栏目权限
			$files=M('files_sort')->field('files_id,'.pfix('files_name').' AS files_name,files_pid,files_padding')->where("display=1")->select();
		    $this->files=unlimitedForLayer($files,'files_pid','files_id','child',0);		
			//获取会员栏目权限
		     $jurChannel=M('files_jurisdiction')->where(array('user_id'=>$id))->select();
			 $chTemp=array();
			 foreach($jurChannel as $k=>$v){
				$chTemp[$v['files_id']]['files_id']=$v['files_id']; 
			    $chTemp[$v['files_id']]['groups']=explode(',',$v['groups']);
			 }
			 unset($jurChannel);
			 $this->chTemp=$chTemp;			
            $this->display();  
        }else{
          return_json(300,'Error!');  
        }   
    }
    
    //修改管理员表单处理
    public function userSaveForm(){

      if($_GET['one']=='ok'){
      	  $this->saveOen();
		  die();
      }else if($_GET['yes_no']=='ok'){
      	  $this->saveSwitch();
		  die();
      }	
      $db=M('user');  
        if(IS_AJAX){          
            $password=trim($_POST['password']);
            $id=I('id',0,'intval');
            if(empty($_POST['role_id'])) return_json(300,'你还没有分派用户角绝！');
            $role_id=$_POST['role_id'];
            $db=M('role_user');
            $db->where(array('user_id'=>$id))->delete();//清空原有角色
            foreach($role_id as $v){
                $data=array(
                    'role_id'=>$v,
                    'user_id'=>$id
                    );
                $db->add($data)? $trues=true : $trues=false; //重新写入角色   
            }
			//栏目权限
			$this->setColumn($id,$id);             
              if(!empty($password)){
                  $userArr=pwd_sha1($password);//处理密码和加密随机码
                  $save=array(
                              'id'=>$id,
                              'username'=>$_POST['name'],
                              'userpic'=>$_POST['userpic'],
                              'userpic_big'=>$_POST['userpic_big'],
                              'entrydate'=>$_POST['entrydate'],
                              'remarks'=>$_POST['remarks'],                          
                              'password'=>$userArr['SHA1'],
                              'sha1_random'=>$userArr['RANDOM'],
                              'lock'=>$_POST['lock']
                              );
                  if(M('user')->where(array('id'=>$id))->save($save)){//密修改密码
                     //处理图片
                     import('Class.DisposeImg',APP_PATH);
                   //  DisposeImg::updatePictures($id,array($_POST['userpic'],$_POST['userpic_big']),'','','','','user');
                     DisposeImg::updatePictures('user',$id,array($_POST['userpic'],$_POST['userpic_big']));
                     return_json(200,'操作成功！','index','','closeCurrent');
					// modify('user','index','','id',array($_POST['userpic'],$_POST['userpic_big']));
                     
                  }else{
                     
                     $trues ? return_json(200,'操作成功！','index','','closeCurrent') : return_json(300,'操作失败！'); 
                  }
               }else{
                  $save=array(
                              'id'=>$id,
                              'username'=>$_POST['name'],
                              'userpic'=>$_POST['userpic'],
                              'userpic_big'=>$_POST['userpic_big'],
                              'entrydate'=>$_POST['entrydate'],
                              'remarks'=>$_POST['remarks'],                          
                              'lock'=>$_POST['lock']
                              );   
                 if(M('user')->where(array('id'=>$id))->save($save)){//不修改密码
                     //处理图片
                     import('Class.DisposeImg',APP_PATH);   
					 DisposeImg::updatePictures('user',$id,array($_POST['userpic'],$_POST['userpic_big']));   
                     return_json(200,'操作成功！','index','','closeCurrent');
                 }else{
                     $trues ? return_json(200,'操作成功！','index','','closeCurrent') : return_json(300,'操作失败！');
                 }                
              } 
         } 
    }
    
    //删除管理员
    public function userDelete(){
    	
    	$p=$_REQUEST[C('VAR_PAGE')]; //删除后取得返回的当前页
		unset($_REQUEST[C('VAR_PAGE')]);
		//p(U('User/index',array('p'=>$p)));die;
         $where['id']=array('in',$_REQUEST['ids']);
         $role_where['user_id']=array('in',$_REQUEST['ids']);
         //$img=M('user')->field('userpic')->$where(array('id'=>$_REQUEST['ids']))->select(); 
        if(M('user')->where($where)->delete()){
            
            //删照片
            D('Curd')->del_img($_REQUEST['ids'],'user');
			
            //删角色关联
            M('role_user')->where($role_where)->delete();
            if(IS_AJAX){
                return_json(200,'删除成功！','index','','forward',U('User/index',array(C('VAR_PAGE')=>$p)));
            }else{
                return_json(300,'操作失败！');
                
            }
        }else{
            return_json(300,'操作失败！');
        }
    }
  
    //修改密码界面
    public function password(){
        $this->display();
    }
    
    //修改密码表单处理
    public function passwordForm(){
      $db=M('user');  
            //对表单提交处理进行处理或者增加非表单数据
            if(md5($_POST['verify'])!= $_SESSION['verify']) {
                    return_json(300,'验证码错误！');
            }
            
            if(empty($_POST['oldpassword'])){
                   return_json(300,'原密码不能为空！');
            }
            
            if($_POST['password'] !=$_POST['repassword']){
                   return_json(300,'新密码两次输入不一致！');
            }
            $User=M('user')->where(array('username'=>$_SESSION[C(SESSION_NAME_VAL)]))->find();
            
            if(!$User || !compare_sha1($_POST['oldpassword'],$User['sha1_random'],$User['password'])){
                    return_json(300,'原密码错误！');
            }else{
               $newarray=pwd_sha1($_POST['password']);
               $map =   array(
                        'password'=>$newarray['SHA1'],
                        'sha1_random'=>$newarray['RANDOM'],
                        'logintime'=>time()
                        );
               $Users=M('user')->where(array('id'=>$User['id'],'username'=>$User['username']))->save($map);
               if($Users){
                       return_json(200,'修改成功！','','','closeCurrent');
               }else{
                       return_json(300,'修改失败！');
               }
            }
    }
	
	//处理栏目权限
	private function setColumn($userId,$saveId=0){

		//没有二级栏目的权限ID
		$notChildArr=array();
		$i=0;
		foreach($_POST['not_child_id'] as $k=>$v){
			$notChildArr[$i]['user_id']=$userId;
			$notChildArr[$i]['files_id']=$v;
			if($_POST['not_child_article_group1'][$k]) $groupId=$_POST['not_child_article_group1'][$k].',';
			if($_POST['not_child_article_group2'][$k]) $groupId.=$_POST['not_child_article_group2'][$k].',';
			if($_POST['not_child_article_group3'][$k]) $groupId.=$_POST['not_child_article_group3'][$k].',';
			$notChildArr[$i]['groups']=rtrim($groupId,',');
			$groupId='';
			$i++;
		}
		
		//有二级栏目的权限ID
		$topId=array();
		$articleGroup=array();
		$i=0;
		$j=0;
		foreach($_POST['top_id'] as $k=>$v){
			foreach($_POST['top_id_'.$v] as $k1=>$v2){
				if($v2){
					$articleGroup[$i]['user_id']=$userId;
					$articleGroup[$i]['files_id']=$v2;
					if($_POST['article_'.$v.'_group1'][$k1]) $groupId=$_POST['article_'.$v.'_group1'][$k1].',';
					if($_POST['article_'.$v.'_group2'][$k1]) $groupId.=$_POST['article_'.$v.'_group2'][$k1].',';
					if($_POST['article_'.$v.'_group3'][$k1]) $groupId.=$_POST['article_'.$v.'_group3'][$k1].',';
					$articleGroup[$i]['groups']=rtrim($groupId,',');
					$topString.=$groupId;
					$groupId='';
					$i++;
				}
			}
			$topId[$j]['user_id']=$userId;
			$topId[$j]['files_id']=$v;
			$topId[$j]['groups']=rtrim($topString,',');
			$topString='';
			$j++;
		}
		$allArray=array();
		if($notChildArr) $allArray=array_merge($notChildArr,$allArray);
		if($topId) $allArray=array_merge($topId,$allArray);
		if($articleGroup) $allArray=array_merge($articleGroup,$allArray);
		if($allArray){
			$filesJurisdiction=M('files_jurisdiction');
			if($saveId) $filesJurisdiction->where(array('user_id'=>$userId))->delete();
			$filesJurisdiction->addAll($allArray);
		}

	}
	
}