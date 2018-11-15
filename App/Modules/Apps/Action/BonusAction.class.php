<?php
class BonusAction extends CommonAction {
	//现金券类型列表
    public function bonusList(){
    	$order=$this->orderBy();//临时排序，返回数组
    	$order=$order ? 'ORDER BY '.$order : '';
		$this->callAjaxPage(C('BGPAGENUM'),M('bonus_type')->count());
		$countNum="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND order_id >0"; //使用数量
        $_ZERO="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_ZERO'); //用户发放个数  0
	    $_ONE="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_ONE'); //商品发放个数 1
	    $_TWO="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_TWO'); //订单发放个数 2
	    $_THREE="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_THREE'); //订单发放个数 3
	    $_FOUR="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_FOUR'); //推荐会员者发放个数 4
	    $_FIVE="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_FIVE'); //新注册用户自动发放 5
	    $_SIX="SELECT count(id) FROM ".PREFIX."member_bonus WHERE bonus_type_id=b.id AND send_type =".C('_SIX'); //会员点击领取6
        $sql="SELECT id,".pfix('bonus_name')." AS bonus_name,bonus_money,send_type,min_amount,use_end_date,is_show,orders,";
        $sql.="({$countNum}) AS countNum,({$_ZERO}) AS _zero,({$_ONE}) AS _one,({$_TWO}) AS _two,({$_THREE}) AS _three,({$_FOUR}) AS _four,({$_FIVE}) AS _five,({$_SIX}) AS _six ";
	    $sql.=" FROM ".PREFIX."bonus_type AS b WHERE is_show=1 ".$order.' LIMIT '.$GLOBALS['limit'];
	    $this->result=M()->query($sql);
	    $this->url=__ACTION__;
        $this->display();	   
    }
    
   //添加现金券类型界面
   public function bonusAdd(){
   	    $this->bonusName=$this->langIsMore('bonus_type','bonus_name'); //现金卷名称多语言
   	    if($_GET['type']){
   	    	$this->display('Bonus/'.$_GET['type']);
   	    }else{
   	    	$this->display('Bonus/bonusAddSave');
   	    }
   	    
   }
   
   //修改现金券类型界面
   public function bonusSave(){
   	    $this->result=M('bonus_type')->where(array('id'=>$_GET['id']))->find();
   	    $this->bonusName=$this->langIsMore('bonus_type','bonus_name'); //现金卷名称多语言
   	    if($_GET['type']){
   	    	$this->display('Bonus/'.$_GET['type']);
   	    }else{
   	    	$this->display('Bonus/bonusAddSave');
   	    }
   	    
   }   
   
   //添加/修改现金券类型表单处理
   public function addSaveForm(){
	    //修改单个字段
		if($_GET['one']=='ok'){
			$this->saveOen();
		}else if($_GET['yes_no']=='ok'){
			$this->saveSwitch();
		}   	
   	  $start=sqlTime($_POST['send_start_date']);
	  $_POST['send_start_date']=$start['startTime']; //发放起始日期
	  
	  $end=sqlTime($_POST['send_end_date']);
	  $_POST['send_end_date']=$end['endTime']; //发放结束日期

   	  $start=sqlTime($_POST['use_start_date']);
	  $_POST['use_start_date']=$start['startTime']; //使用起始日期	  
	  
	  $end=sqlTime($_POST['use_end_date']);
	  $_POST['use_end_date']=$end['endTime']; //发放结束日期	
	  if($_POST['send']=='add'){ //新增
	     $this->requestArray('bonus_type');
	  	 D('Curd')->insert('bonus_type','bonusList',array($_POST['picture']));
	  }else if($_POST['send']=='update'){ //修改
	  	 D('Curd')->modify('bonus_type','bonusList','','id',array($_POST['picture']));
	  }  
   	      
   }
	
	//删除现金券类型
	public function bonusDelete(){
	    $url=U(APP_APPS.'/Bonus/bonusList');
	    D('Curd')->del('bonus_type','bonusList',$url);//删除	
	}
	
	//现金券列表
	public function ticketList(){
        $order=$this->orderBy();//临时排序，返回数组
    	$order=$order ? 'ORDER BY '.$order : '';		
		$model=D('Bonus');
		$this->callAjaxPage(C('BGPAGENUM'),$model->ticketListM());
        $this->list=$model->ticketListM('list',$order,$GLOBALS['limit']);
		$this->display();
	}
	
	//线下现金券列表导出EXCEL
	public function exportExcel(){
		     set_time_limit(0);  //设置脚本运行的最大时间，0为无限际
		     
		    $type=M('bonus_type')->field(pfix('bonus_name').' AS bonus_name,bonus_money,min_amount,send_end_date')->where(array('id'=>$_GET['bonus_type_id']))->find();
			$result=M('member_bonus')->where(array('bonus_type_id'=>$_GET['bonus_type_id'],'member_id'=>0))->select();

	        require(APP_PATH."/PHPExce 1.7.4/PHPExcel.php");    
			require(APP_PATH."/PHPExce 1.7.4/PHPExcel/Writer/Excel5.php");  
			//require 'PHPExce 1.7.4/Classes/PHPExcel/Writer/Excel2007.php'; 
			
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
			$objExcel->getSheet(0)->setTitle($type['bonus_name']); //worksheet工作表标题
			$objActSheet = $objExcel->getActiveSheet ();			
			//单元格赋值   例：
			$objActSheet->setCellValue ( 'A1', '现金券名称');
			$objActSheet->setCellValue ( 'B1', '现金券号');
			$objActSheet->setCellValue ( 'C1', '现金券金额');
			$objActSheet->setCellValue ( 'D1', '订单金额下限');
			$objActSheet->setCellValue ( 'E1', '使用结束日期');
			$top1 = $objActSheet->getStyle('A1:E1'); //向中间对齐
			$top1=$top1->getAlignment(); 
			$top1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //向中间对齐			
			
			//得到单元格的样式///////////////////////////////////////////////////////////////////////
            $objStyleA1 = $objActSheet->getStyle('A1:E1');
			$objFontA1 = $objStyleA1->getFont(); 
			$objFontA1->setName('微软黑体');  //选择字体
			$objFontA1->setSize(14);   //字体大小
			$objFontA1->setBold(true); 		
			//$objFontA1->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  //文本加下横线
			$objFontA1->getColor()->setARGB('FF999999');  //字体色	
			
			//单元格赋值
			$i=2;
			foreach($result as $k=>$v){
				$objActSheet->setCellValue('A'.$i,$type['bonus_name']);
				$objActSheet->setCellValue('B'.$i,$v['bonus_sn']);
				$objActSheet->setCellValue('C'.$i,$type['bonus_money']);
				$objActSheet->setCellValue('D'.$i,$type['min_amount']);
				$objActSheet->setCellValue('E'.$i,date('Y-m-d H:i:s',$type['send_end_date']));
				$textCenter=$objActSheet->getStyle('A'.$i.':E1'.$i); //向中间对齐
				$textCenter=$textCenter->getAlignment(); 
				$textCenter->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //向中间对齐		
				$i++;
			}
			//手动设置单元格的宽度
			$objActSheet->getColumnDimension('A')->setWidth(15);
			$objActSheet->getColumnDimension('B')->setWidth(40);
			$objActSheet->getColumnDimension('C')->setWidth(15);
			$objActSheet->getColumnDimension('D')->setWidth(15);			
			$objActSheet->getColumnDimension('E')->setWidth(40);		

			//文件直接输出到浏览器
			header ( 'Pragma:public');
			header ( 'Expires:0');
			header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
			header ( 'Content-Type:application/force-download');
			header ( 'Content-Type:application/vnd.ms-excel');
			header ( 'Content-Type:application/octet-stream');
			header ( 'Content-Type:application/download');
			header ( 'Content-Disposition:attachment;filename='. $type['bonus_name'].'.xls' );
			header ( 'Content-Transfer-Encoding:binary');
			$objWriter->save ( 'php://output');		

        }
	
	//删除已发放现金券
	public function ticketDelete(){
		$url=U(APP_APPS.'/Bonus/ticketList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')],'bonus_type_id'=>$_REQUEST['bonus_type_id']),'');
		D('Curd')->del('member_bonus','ticketList',$url);
	}
	
	//发放现金券
	//根据type参数(0-4)控制显示不同界面
	public function bonusSend(){
		$this->type=$_GET['type'];
		$this->id=$_GET['id'];
		if($_GET['type']==C('_ZERO')){
			$this->grade=M('member_grade')->field('id,'.pfix('grade_name').' AS grade_name,min_points,max_points')->select();//用户等级
		}else if($_GET['type']==C('_ONE')){
			$this->goodCat=D('Curd')->cage_select('goods_category','id,'.pfix('name').' AS name,pid','','reorder asc');//商品分类
			$this->brand=M('goods_brand')->field('id,name_cn')->select();//获取品牌		
			$this->rightSelect=M('goods')->field('id,'.pfix('goods_name').' AS goods_name')->where(array('bonus_type_id'=>$_GET['id']))->select();	
		}else if($_GET['type']==C('_TWO')){
			/*****/
		}else if($_GET['type']==C('_THREE')){
			$this->result=M('bonus_type')->field('id,'.pfix('bonus_name').' AS bonus_name,bonus_money,min_amount,send_type')->where(array('send_type'=>C('_THREE')))->select();
		}

		$this->display();
		
	}
	
	//发放现金券表单提交
	public function bonusSendForm(){
		$type=$_POST['type'];
		$model=D('Bonus'); //实例化模型
		if($type==C('_ZERO')){//按用户
			$model->userSend();
		}else if($type==C('_ONE')){//按商品
			$model->goodsSend();
		}else if($type==C('_TWO')){//按订单
			
		}else if($type==C('_THREE')){//线下
			$model->offlineSend();
		}else if($type==C('_FOUR')){//推荐会员者
			
		}
	}

    //按用户发放，Ajax获取用户
    public function userLeve(){
    	$name=$_GET['name'];  //按用户名检索
    	if($_GET['act']=='all'){ //按所有用户
    		$name ? $amp=array('user_name'=>array('LIKE',"%{$name}%")) :$amp='';
    		die(json_encode(M('member')->field('id AS member_id,user_name')->where($amp)->select()));
    	}else if($_GET['act']=='leve'){ //按用户等级
	    	$min=$_GET['min'];//最小等级分数
	    	$max=$_GET['max'];//最大等级分数
			$where.=" (rank_points >={$min} AND rank_points <={$max}) ";
	    	$where.=$name ? " AND user_name LIKE '%{$name}%' " : '';
			$member=M('member')->field('id AS member_id,user_name')->where($where)->select();
			die(json_encode($member));
		}
    }
	
	
	
}