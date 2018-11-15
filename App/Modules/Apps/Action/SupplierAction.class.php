<?php
class SupplierAction extends CommonAction{
		
		//供应商列表
		private function suppList($num){
			$curd=D('Curd');		
			$M=new SupplierRelationModel();
			$where=$curd->search('name'); //询查条件返回数组
			$order=$this->orderBy();//临时排序，返回数组
	    	$order=$order ? $order : array('id'=>'DESC');			
			$counts=$M->where($where['searchArr'])->relation(true)->count();
			$this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
			$where['searchArr']['is_vetted']=$num;
			$this->supplier=$M->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order($order)->select();
			$this->url=$where['url']; //查询条件分页URL
			$this->searchVal=$where['keyword']; //保存输入框的关键字  
			
		}
		
		//已审核供应商列表
		public function isSuppList(){
			$this->suppList(1);
			$this->action='isSuppList';
			$this->display('Supplier/suppList');
		}
		
		//未审核供应商列表
		public function notSuppList(){
			$this->suppList(0);
			$this->action='notSuppList';
			$this->display('Supplier/suppList');			
		}
				
		//添加/修改供应商
		public function suppAddSave(){
			if($_GET['id']){
                $save=M('goods_supplier')->where(array('id'=>$_GET['id']))->find();
				$save['goods_category_id']=explode(',', $save['goods_category_id']);
			}	
			$this->save=$save;
			//默认区域
			$model=new CommonModel();
			$this->region=$model->getRegion($save['country'],$save['province'],$save['city'],$save['district']);	
			//服务分类
			$this->cates=M('goods_category')->field('id,'.pfix('name').' AS name')->where('pid=0 AND is_show=1')->order("reorder ASC")->select();			
			$this->display();
		}
		
		//供应商操作表单处理
		public function suppForm(){
			//直接取消限制登录
			if($_GET['cancelLimit']=='ok'){
				$id=$_GET['id'];
				$rel=$_GET['rel'];
				if(M('goods_supplier')->where(array('id'=>$id))->save(array('temp_login_count'=>0))){
					return_json(200,'操作成功！',$rel,'','closeCurrent');
				}else{
					return_json(300,'操作失败！');
				}
			}
			
		 	if($_GET['one']=='ok'){
		 		$this->saveOen();
		 	}else if($_GET['yes_no']=='ok'){
		 		$this->saveSwitch();
		 	}else{
		 		if(empty($_POST['name'])) return_json(300,'企业名称不可空');
				if(empty($_POST['people_num'])) return_json(300,'企业人数不可空');
				if(empty($_POST['people_num'])) return_json(300,'必须上传营业执照图');
				if(empty($_POST['country'])) return_json(300,'请选择国家');
				if(empty($_POST['province'])) return_json(300,'请选择省份');
				if(empty($_POST['city'])) return_json(300,'请选择城市');
				if(empty($_POST['address'])) return_json(300,'详细街不可空');
				if(empty($_POST['contacts'])) return_json(300,'联系人不可空');
				if(!num_line($_POST['tel'])) return_json(300,'电话错误');
				$inAarrayImg=array($_POST['license'],$_POST['circulation']);				 	  
				$this->requestArray('goods_supplier'); //重组POST数据
				$_POST['goods_category_id']=$_POST['goods_category_id'] ? implode(',', $_POST['goods_category_id']) : return_json(300,'请选择服务项目');
			 	if($_POST['id']){  //修改
			 	    $where="login_name='{$_POST['login_name']}' AND id <> '{$_POST['id']}'";
				    if(M('goods_supplier')->field('id')->where($where)->find()) return_json(300,'登录用户名重复请检查后再提交');
					
			 	    if(!empty($_POST['password'])){
						$pwd=pwd_sha1($_POST['password']);
						$_POST['password']=$pwd['SHA1'];
						$_POST['sha1_random']=$pwd['RANDOM'];		
						 	
			 	    }else{
			 	    	
			 	    	unset($_POST['password']);
						unset($_POST['sha1_random']);
			 	    }
					D('Curd')->modify('goods_supplier',$_REQUEST['action'],'','id',$inAarrayImg); //修改数据	 		
			 	}else{ //新增
			 	   if(M('goods_supplier')->field('id')->where(array('login_name'=>$_POST['login_name']))->find()) return_json(300,'登录用户名重复请检查后再提交');
			 	    if($_POST['password']){
						$pwd=pwd_sha1($_POST['password']);
						$_POST['password']=$pwd['SHA1'];
						$_POST['sha1_random']=$pwd['RANDOM'];			 	    	
			 	    }else{
			 	    	return_json(300,'请设置供应商密码');
			 	    }			 	    
			 		$_POST['addtime']=time();
			 		D('Curd')->insert('goods_supplier',$_REQUEST['action'],$inAarrayImg); //修改数据	 
			 	}	
			} 		
		}
		
		//删除供应商
		public function suppDel(){
			$ids=$_REQUEST['ids'];
			$goods=M('goods')->field('id')->where(array('goods_supplier'=>array('in',$ids)))->select();
			$goodsIds=in_id($goods, 'id');
			M('album')->where("tableId IN({$goodsIds}) AND table_name='goods'")->delete(); //删除商品相册数据
			$imgs=M('entire_img')->field('img')->where("tableId IN({$goodsIds}) AND table_name='goods'")->select(); //获取商品所有图片
			$imgs2=M('entire_img')->field('img')->where("tableId IN({$ids}) AND table_name='goods_supplier'")->select();//获取供应商执照图和通行证图
			M('entire_img')->where("tableId IN({$goodsIds}) AND table_name='goods'")->delete();//删除商品所有图片数据
			M('entire_img')->where("tableId IN({$ids}) AND table_name='goods_supplier'")->delete();///删除供应商所有图片数据
			foreach($imgs as $k=>$v){
				@unlink('./'.$v['img']);
			}
			foreach($imgs2 as $k=>$v){
				@unlink('./'.$v['img']);
			}			
			M('goods')->where("id IN ({$goodsIds})")->delete();
			M('goods_supplier')->where("id IN ({$ids})")->delete();
			$url=U(APP_APPS.'/Supplier/'.$_REQUEST['action']);
			return_json(200,'操作成功！','','','forward',$url);
		}

		//会员导出EXCEL
		public function exportExcel(){
			set_time_limit(0);		
			$fieldArray=array(
				    0=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'用户ID','field'=>'id'),
					1=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'供应商名称','field'=>'name'),
					2=>array('type'=>'img','width'=>'13','height'=>'50','info'=>'营业执照','field'=>'license'),
					3=>array('type'=>'img','width'=>'18','height'=>'50','info'=>'食品许可证','field'=>'circulation'),
					4=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'供应商余额','field'=>'bankroll'),
					5=>array('type'=>'txt','width'=>'13','height'=>'50','info'=>'企业人数','field'=>'people_num'),
					6=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'联系人','field'=>'contacts'),
					7=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'联系电话','field'=>'tel'),					
					8=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'联系QQ','field'=>'qq'),
					9=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'电子邮箱','field'=>'email'),					
					10=>array('type'=>'address','width'=>'50','height'=>'50','info'=>'详细地址','field'=>'country,province,city,district,address'),
					11=>array('type'=>'txt','width'=>'80','height'=>'50','info'=>'供货商描述','field'=>'bewrite'),
				   12=>array('type'=>'date','width'=>'20','height'=>'50','info'=>'加入时间','field'=>'addtime')
			);
			$dataArray=M('goods_supplier')->order('id DESC')->select();
			exportExcel($dataArray,$fieldArray,'./'.C('USER_IMG'));
		}	

}
?>