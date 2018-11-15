<?php
// 本类由系统自动生成，仅供测试用途

class MemberAction extends CommonAction {
	
	 //会员列表
    public function memberList(){
       $where=D('Curd')->search('nickname','','city'); //询查条件返回数组
       $counts=D('MemberRelation')->where($where['searchArr'])->relation(true)->count();
	   $this->callAjaxPage(C('BGPAGENUM'),$counts);//分页
	   $this->Member=D('MemberRelation')->where($where['searchArr'])->relation(true)->limit($GLOBALS['limit'])->order(array('reg_time'=>'DESC'))->select();
	   $this->url=$where['url']; //查询条件分页URL
	   $this->searchVal=$where['keyword']; //保存输入框的关键字 
	   
	   //会员等级表
	   $this->level=M('member_grade')->field(array('grade_name','min_points','max_points,special_rank'))->select();
	   //会员等级最高分组
	   $this->max=M('member_grade')->field('grade_name,max_points,special_rank')->order(array('max_points'=>'DESC'))->find();

       $this->display();
    }
	
	//添加会员界面
	public function memberAdd(){
		$this->City=D('Curd')->citySelect(1); //城市列表
		$this->display();
	}
	
	//添加会员表单处理
	public function memberAddForm(){
		  $userArr=pwd_sha1($_POST['password']);//处理密码和加密随机码
		  $_POST['password']=$userArr['SHA1'];
		  $_POST['sha1_random']=$userArr['RANDOM'];
		  $_POST['activation_num']=sha1(uniqid());
		  $_POST['reg_time']=time();
		  $me=M('member');
		  
		  //用户名是否重复
		 /* $member=array();
		  $member['user_name']=$_POST['user_name'];
		  $member['reg_email']=$_POST['user_name'];
		  $member['mobile_phone']=$_POST['user_name'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');*/

		  //电子邮件是否重复
		/*  $member=array();
		  $member['user_name']=$_POST['mobile_phone'];
		  $member['reg_email']=$_POST['mobile_phone'];
		  $member['mobile_phone']=$_POST['mobile_phone'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');*/

		  //手机号是否重复
		  $member=array();
		  $member['user_name']=$_POST['reg_email'];
		  $member['reg_email']=$_POST['reg_email'];
		  $member['mobile_phone']=$_POST['reg_email'];
		  $member['_logic']='OR';		  
		  if($me->where($member)->find()) return_json(300,'手机号重复请检查后再提交？');
		  		  
		  if($id=$me->add($_POST)){
			   import('Class.DisposeImg',APP_PATH);
			   DisposeImg::addPictures('member',$id,array($_POST['head_pic']),'','','');		  
		       M('member_account')->add(array('member_id'=>$id,'rank_points'=>1,'change_time'=>time(),'change_desc'=>'登录积分','change_type'=>4)); //默认等级
			   return_json(200,'添加成功！','memberList','','closeCurrent');
		  }else{
		      return_json(300,'操作失败！');
		  }
	}
	
	//修改会员界面
	public function memberSave(){
		$save=M('member')->where(array('id'=>$_GET['id']))->find();
		$this->save=$save;
		//默认区域
		$model=new CommonModel();
		$this->region=$model->getRegion($save['country'],$save['province'],$save['city'],$save['district']);	
		
		//获取会员相册专辑
		$this->photo=M('album')->where(array('tableId'=>$_GET['id'],'table_name'=>'member'))->select();
		$this->display();
	}
	
	//修改会员表单处理
	public function memberSaveForm(){
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}
		if($_POST['password']){
			$pwd=pwd_sha1($_POST['password']);
			$_POST['password']=$pwd['SHA1'];
			$_POST['sha1_random']=$pwd['RANDOM'];
		}else{
			unset($_POST['password']);
		}
	   $me=M('member');
	   //p($_POST);die;
		  //用户名是否重复
		  $member='';
		  $member="user_name='{$_POST['user_name']}' AND id <> '{$_POST['id']}'";  
		  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');

		  //手机号是否重复
		//  $member='';
		//  $member="(reg_email='{$_POST['mobile_phone']}' OR mobile_phone='{$_POST['mobile_phone']}') AND (id <> '{$_POST['id']}') ";  
		//  if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');

		  //电子邮件是否重复  
		 // $member='';
		 // $member="(reg_email='{$_POST['mobile_phone']}' OR mobile_phone='{$_POST['reg_email']}') AND (id <> '{$_POST['id']}') ";  
		 // if($me->where($member)->find()) return_json(300,'用户名或手机号或电子邮件重复请检查后再提交？');		  
		 D('Curd')->modify('member','memberList','','id',array($_POST['head_pic'])); //修改数据
	}
	
	//取消会员登录限制
	public function cancelLimit(){
	   $id=$_GET['id'];
	   if(M('member')->where(array('id'=>$id))->save(array('temp_login_count'=>0,'temp_login_time'=>1))){
	       return_json(200,'操作成功！','memberList','','closeCurrent');
	   }else{
	       return_json(300,'操作失败！');
	   }
	}
	
	//删除会员
	public function memberDelete(){
		   import('Class.Category',APP_PATH);
		   $curd=D('Curd');
		   $url=U(APP_APPS.'/Member/memberList',array(C('VAR_PAGE')=>$_GET[C('VAR_PAGE')]));
           $ids['id']=array('IN',$_REQUEST['ids']);
		  if(M('member')->where($ids)->delete()){
		  	 $curd->del_img($_REQUEST['ids'],'member'); //删除会员照片
		  	 M('member_account')->where(array('member_id'=>array('in',$_REQUEST['ids'])))->delete();  //删除会员明细帐
		  	 M('comment_reply')->where(array('user_id'=>array('in',$_REQUEST['ids'])))->delete();  //删除会员论坛贴子回复
		  	 $commentId=Category::unlimitedForInId(M('comment')->field("id")->where(array('user_id'=>$_REQUEST['ids']))->select());//获取贴子评述表ID
		  	 $curd->del_img($commentId,'comment'); //删除贴子评述时上传的图片
		  	 M('comment')->where(array('user_id'=>$_REQUEST['ids']))->delete(); //删除贴子评述表
		  	 $articleId=Category::unlimitedForInId(M('article')->field("id")->where(array('author_id'=>$_REQUEST['ids']))->select());//获取贴子表ID
		  	 $curd->del_img($articleId,'article'); //删除贴子上传的图片
		  	 M('article')->where(array('author_id'=>$_REQUEST['ids']))->delete();//删除贴子表
			 return_json(200,'删除成功！','memberList','','forward',$url);
		  }	
	}
	
	//会员商家导出EXCEL
	function exportExcel(){
		set_time_limit(0);  //设置脚本运行的最大时间，0为无限际
		//城市表数据放入内容
        $model=new CommonModel();
		$region=$model->regionToArray();
		$fileName=date('Y-m-d'); //文件名称
		$config=1;  //是否导出所有数据；1按分页导出；0导出所有数据；手动设置

		$db=M('member');
		$limit='';
		if($config){
			$this->callAjaxPage(C('BGPAGENUM'),$db->count());//分页
			$limit=$GLOBALS['limit'];
		 }
		$result=$db->order('id DESC')->limit($limit)->select();

		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel.php");    
		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel/Writer/Excel5.php");  
		
		$objExcel = new PHPExcel(); 		// 创建一个处理对象实例 	
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式  			
		//$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于2007 格式 					
		$objProps = $objExcel->getProperties ();//创建文件对像						
		//$objProps->setCreator ( 'AAAAA');//设置创建者						
		//$objProps->setLastModifiedBy("BBBBB");//设置最后修改者						
		//$objProps->setDescription("CCCCC");//描述						
		//$objProps->setTitle ( '第一个工作表' );//设置标题						
		//$objProps->setSubject("ffice 2007 XLSX Test Document");//设置题目
		//$objProps->setKeywords ( 'office 2007 openxml php' );//设置关键字
		//$objProps->setCategory ( "Test result file");		//设置分类	
		$objExcel->setActiveSheetIndex( 0 );//工作表设置
		$objExcel->getSheet(0)->setTitle($fileName); //worksheet工作表标题
		$objActSheet = $objExcel->getActiveSheet ();			
		
		//自动设置单元格宽度  
        $objActSheet->getColumnDimension('A')->setAutoSize(true);
		$objActSheet->getColumnDimension('B')->setWidth(15);
		$objActSheet->getColumnDimension('C')->setWidth(30);
		$objActSheet->getColumnDimension('D')->setWidth(30);
		$objActSheet->getColumnDimension('E')->setWidth(20);
		$objActSheet->getColumnDimension('F')->setWidth(30);
		$objActSheet->getColumnDimension('G')->setWidth(30);
		$objActSheet->getColumnDimension('H')->setWidth(40);
		$objActSheet->getColumnDimension('I')->setWidth(20);
		$objActSheet->getColumnDimension('J')->setWidth(15);
		$objActSheet->getColumnDimension('K')->setWidth(15);
		$objActSheet->getColumnDimension('L')->setWidth(18);
		
		/**暂时不用这些选项
		$objActSheet->getColumnDimension('M')->setWidth(15);
		$objActSheet->getColumnDimension('N')->setWidth(15);
		$objActSheet->getColumnDimension('O')->setWidth(15);
		$objActSheet->getColumnDimension('P')->setWidth(15);
		*/
				
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A1', '注册时间');
		$objActSheet->setCellValue ( 'B1', '用户ID');
		$objActSheet->setCellValue ( 'C1', '用户名称');
		$objActSheet->setCellValue ( 'D1', '用户昵称');
		$objActSheet->setCellValue ( 'E1', '用户头像');
		$objActSheet->setCellValue ( 'F1', '电子邮件');
		$objActSheet->setCellValue ( 'G1', '手机号');
		$objActSheet->setCellValue ( 'H1', '详细地址');
		$objActSheet->setCellValue ( 'I1', '邮政编码');
		$objActSheet->setCellValue ( 'J1', '固定电话');
		$objActSheet->setCellValue ( 'K1', 'QQ号');
		$objActSheet->setCellValue ( 'L1', '微信号');
		
		/**暂时不用这些选项
		$objActSheet->setCellValue ( 'M1', '资金余额');
		$objActSheet->setCellValue ( 'N1', '冻结的资金');
		$objActSheet->setCellValue ( 'O1', '等级积分');
		$objActSheet->setCellValue ( 'P1', '消费积分');
		*/
	/*	$top1 = $objActSheet->getStyle('A1:P1'); //向中间对齐
		$top1=$top1->getAlignment(); */
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
		
		//得到单元格的样式///////////////////////////////////////////////////////////////////////
		$objStyleA1 = $objActSheet->getStyle('A1:P1');
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(14);   //字体大小
		$objFontA1->setBold(true); 		
		//$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  //文本加下横线
		$objFontA1->getColor()->setARGB('FF999999');  //字体色	
				
		//单元格赋值
		$i=2;
		foreach($result as $k=>$v){
		    $objExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(120); //设置行高
			$objActSheet->setCellValue ( 'A'.$i, date('Y-m-d',$v['reg_time']));
			$objActSheet->setCellValue ( 'B'.$i, $v['id']);
			$objActSheet->setCellValue ( 'C'.$i,$v['user_name']);
			$objActSheet->setCellValue ( 'D'.$i,$v['nickname']);
			//会员头像图片
			$objDrawing = new PHPExcel_Worksheet_Drawing(); 	
			//会员头像是否存在
			if($v['head_pic']){
				if(file_exists('./'.$v['head_pic'])){
					$userImg='./'.$v['head_pic'];
				}else{
					$userImg='./'.C('USER_IMG');
				}				
			}else{
				$userImg='./'.C('USER_IMG');
			}
			
			$objDrawing->setName('ZealImg'.$i);  //图片名称
			$objDrawing->setDescription('Image inserted byZeal'.$i); //图片描述
			$objDrawing->setPath($userImg); //图片路径
			$objDrawing->setHeight(100);   //图片高度
			$objDrawing->setCoordinates('E'.$i);
			$objDrawing->setOffsetX(20);  //margin-left:20px;
			$objDrawing->setOffsetY(20);  //margin-top:20px;
			$objDrawing->setRotation(20); 
			$objDrawing->getShadow()->setVisible(true); 
			$objDrawing->getShadow()->setDirection(26); 
			$objDrawing->setWorksheet($objActSheet); 						
			//$objActSheet->setCellValue ( 'E'.$i, '用户头像');
			
			$objActSheet->setCellValue ( 'F'.$i, $v['reg_email']);
			$objActSheet->setCellValue ( 'G'.$i, $v['mobile_phone']);
			$objActSheet->setCellValue ( 'H'.$i, $region['province'][$v['province']].$region['city'][$v['city']].$region['district'][$v['district']].$v['address']);
			
			$objActSheet->setCellValue ( 'I'.$i, $v['zip']);
			$objActSheet->setCellValue ( 'J'.$i, $v['telephone']);
			$objActSheet->setCellValue ( 'K'.$i, $v['qq']);
			$objActSheet->setCellValue ( 'L'.$i, (string)$v['wechat']);
			
			/**暂时不用这些选项
			$objActSheet->setCellValue ( 'M'.$i, $v['member_funds']);
			$objActSheet->setCellValue ( 'N'.$i, $v['frozen_funds']);
			$objActSheet->setCellValue ( 'O'.$i, $v['rank_points']);
			$objActSheet->setCellValue ( 'P'.$i, $v['pay_points']);
			$textCenter=$objActSheet->getStyle('A'.$i.':X'.$i); //向中间对齐
			*/
			$textCenter=$objActSheet->getStyle('A'.$i.':L'.$i); //向中间对齐
			$textCenter=$textCenter->getAlignment(); 
			$textCenter->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //向中间对齐		
			$i++;
		}
		
		//文件直接输出到浏览器
		header ( 'Pragma:public');
		header ( 'Expires:0');
		header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header ( 'Content-Type:application/force-download');
		header ( 'Content-Type:application/vnd.ms-excel');
		header ( 'Content-Type:application/octet-stream');
		header ( 'Content-Type:application/download');
		header ( 'Content-Disposition:attachment;filename='. $fileName .'.xls' );
		header ( 'Content-Transfer-Encoding:binary');
		$objWriter->save ( 'php://output');		
	}	

}