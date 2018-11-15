<?php
// 本类由系统自动生成，仅供测试用途

class OrderInfoAction extends CommonAction {

    public function _initialize(){
	    parent::_initialize();
	    $this->orderStatus=C('ORDER_START'); //订单状态
		$this->shippingStatus=C('ORDER_SHIPPING'); //订单配送状态
	    $this->payStatus=C('ORDER_PAY');	  //订单支付状态	   
	}

	//今天订单列表
	public function orderToday(){
		$this->today=true;
		$_REQUEST['add_time']=date('Y-m-d',time());
	    $this->orderList('OrderInfo/orderList');
	}
	
	//订单列表
    public function orderList($tpl=''){
    	$db=M();	   
	   $order=$this->orderBy('o');//临时排序，返回数组
	   $order=$order ? $order : ' ORDER BY  o.order_id DESC ';	 
	   //扩展查询
	   $where=" WHERE 1=1 ";
	   //订单号
	   if($_REQUEST['order_sn']) $where.=" AND o.order_sn ='".trim($_REQUEST['order_sn'])."'"; 
	   if($_REQUEST['order_status'] || $_REQUEST['order_status']=='0') $where.=" AND o.order_status=".$_REQUEST['order_status']." "; //订单状态
	   if($_REQUEST['shipping_status'] || $_REQUEST['shipping_status']=='0') $where.=" AND o.shipping_status=".$_REQUEST['shipping_status']." "; //发货状态
	   if($_REQUEST['pay_status'] || $_REQUEST['pay_status']=='0') $where.=" AND o.pay_status=".$_REQUEST['pay_status']." "; //支付状态
	   if($_REQUEST['add_time']){ //按时间查询
		   $startTime= strtotime($_REQUEST['add_time']);
		   $endTime=$startTime + 86400;	   
		   $where.=" AND (o.add_time >={$startTime} AND o.add_time <={$endTime}) ";
	    }
	   $counts=$db->query("SELECT count(o.order_id) AS counts FROM ".PREFIX."order_info AS o  {$where}");
	   $this->callAjaxPage(C('BGPAGENUM'),$counts[0]['counts']);//分页
	   $sql.="SELECT o.order_id,o.order_sn,o.order_status,o.shipping_status,o.pay_status,o.is_inv,o.goods_amount,o.logistics_sn,o.logistics_id,o.pay_type,";
	   $sql.="o.freight,o.order_amount,o.add_time,o.tax,o.taxes,m.mobile_phone,m.user_name FROM ".PREFIX."order_info AS o LEFT JOIN ".PREFIX."member AS m ";
	   $sql.="ON (o.user_id=m.id)  {$where} ORDER BY o.order_id DESC ";
	   $result=$db->query($sql);
	   $this->list=$result;
	   $this->searchVal=$amp['keyword']; //保存输入的搜索关键字
	   $this->url=$amp['url']; //保存查询条件*/
       $this->display($tpl);	   
    }
	
	//查看订单
	public function orderShow($tpl=''){
		//查看物流
		if($_GET['logistics'] == 'ok') {
		     $this->baiduKuaidi100();
			 exit();
		}
	    $db=M();
		$sql="SELECT o.*,IF(m.nickname <> '',m.nickname,m.user_name) AS user_name,IF(m.mobile_phone <>'',m.mobile_phone,m.user_name) AS mobile_phone ";
		$sql.="FROM ".PREFIX."order_info AS o LEFT JOIN ".PREFIX."member AS m ON(m.id=o.user_id) ";
		$sql.="WHERE o.order_id='".getNum($_GET['order_id'])."' LIMIT 1";
		$orders=$db->query($sql);
		//获取会员收货地址
		/*if($orders[0]['district'] > 0) $district=','.$orders[0]['district'];
		$userAddress=$orders[0]['province'].','.$orders[0]['city'].$district;
		$region=M('region')->field('region_name')->where(array('region_id'=>array('in',$userAddress)))->order('region_type ASC')->select();
		$orders[0]['region_name_1']=$region[0]['region_name'];
		$orders[0]['region_name_2']=$region[1]['region_name'];
		$orders[0]['region_name_3']=$region[2]['region_name'];
		$orders[0]['discount_data']=$orders[0]['discount_data'] ? json_decode($orders[0]['discount_data'],true) : array();
		$orders[0]['bonus_data']=$orders[0]['bonus_data'] ? json_decode($orders[0]['bonus_data'],true) : array();*/
		$this->orders=$orders[0];
		if($orders[0]['is_inv'] >0){ //需要发票
		     if($orders[0]['is_inv']==1){//普通发票
			     $invs=M('member_pu_tong_inv')->where(array('member_id'=>$orders[0]['user_id']))->find();
			 }else if($orders[0]['is_inv']==2){//增值税发票
			     $invs=M('member_zeng_zhi_inv')->where(array('member_id'=>$orders[0]['user_id']))->find();
			 }
		}
		$this->invs=$invs;

		//订单商品列表
		$goods=M('order_goods')->where("order_id='{$orders[0]['order_id']}'")->select();
		$goodsList=array();
		$subtotal=0;
		$total =0;
		foreach($goods as $k=>$v){
			$goodsList[$k]['rec_id']=$v['rec_id'];
			$goodsList[$k]['goods_id']=$v['goods_id'];
		    $goodsList[$k]['goods_sn']=$v['goods_sn'];
			$goodsList[$k]['goods_name']=$v[pfix('goods_name')];
			$goodsList[$k]['goods_thumb']=$v['goods_thumb'];
			$goodsList[$k]['goods_number']=$v['goods_number'];
			$goodsList[$k]['goods_price']=$v['goods_price'];
			$goodsList[$k]['goods_attr']=$v['goods_attr'] ? json_decode($v['goods_attr'],true) : array();
			$subtotal=$v['goods_price'] * $v['goods_number'];
			$goodsList[$k]['subtotal']=number_format($subtotal,2, '.', '');
			$goodsList[$k]['rec_type']=$v['rec_type'];
			$goodsList[$k]['rec_info']=$v['rec_info'] ? json_decode($v['rec_info'],true) : array();
			$goodsList[$k]['integral_trade']=$v['integral_trade'];
			$total+=$subtotal;
		}
	   $this->goodsList=$goodsList;
	   $this->goodsTotal=$total;
	   //订单操作日志
	   $this->logs=M('order_action')->where(array('order_id'=>$_GET['order_id']))->order(array('action_id'=>'DESC'))->select();
	   //物流公司
	   $this->logistics=M('shipping')->field('shipping_id,shipping_name')->where("enabled=1")->order("orders ASC")->select();
	   //用户身份证列表
	   $this->identity=M('member_identity')->where(array('user_id'=>$orders[0]['user_id']))->select();
	   //支付方式
	   $this->paymentType=C('PAYMENT_METHOD');	   	   
	   //退货订单
	   if($orders[0]['order_status'] >= 3){
		    $retreat_data=M('retreat_data')->where("order_id='{$orders[0]['order_id']}'")->find();
			$retreat_data['goods_data']=$retreat_data['goods_data'] ? json_decode($retreat_data['goods_data'],true) : array();
			/*$region_ids=$retreat_data['country'].','.$retreat_data['province'].','.$retreat_data['city'].','.$retreat_data['district'];
			$region=M('region')->field("region_id,region_name")->where("region_id IN ({$region_ids})")->select();
			$reg=array();
			foreach($region as $k=>$v) $reg[$v['region_id']]=$v['region_name'];
			$retreat_data['country']=$reg[$retreat_data['country']];
			$retreat_data['province']=$reg[$retreat_data['province']];
			$retreat_data['city']=$reg[$retreat_data['city']];
			$retreat_data['district']=$reg[$retreat_data['district']];*/
			$this->retreat_data=$retreat_data;
			//退货理由只获取中文
			$this->RETURN_RREAS=C('CN_RETURN_RREAS');
			if($tpl) $this->display($tpl);
		    else $this->display($_REQUEST['tpl'] ? $_REQUEST['tpl'] : 'orderShow'); 	        
	   }else{
			if($tpl) $this->display($tpl);
		    else $this->display(); 
	   } 
	}

   //物流信息
   public function apiKuaidi100(){
	   $sql="SELECT o.order_sn,o.logistics_sn,s.shipping_code,s.shipping_name FROM ".PREFIX."order_info AS o JOIN ".PREFIX."shipping AS s ON(s.shipping_id=o.logistics_id) ";
	   $sql.="WHERE o.order_id='{$_GET['order_id']}' LIMIT 1";
	   $result=M()->query($sql);
	   $reslut=get_kuaidi100($result[0]['shipping_code'],$result[0]['logistics_sn']);
	   $this->orders=$result[0]['order_sn'];
	   $this->iframeUrl=$reslut;
       $this->display();      
   }

	 //百度 Kuaidi100物流跟踪接口
	 public function baiduKuaidi100(){
		 if(!verify_req_sign($_GET,array('_'),86400)) return_json(300,'操作越时，请关闭页面然后再试一次！');
	     $order_sn=$_REQUEST['order_sn'];
		 $logistics_sn=$_REQUEST['logistics_sn'];
		 $shipping_code=$_REQUEST['shipping_code'];
		 $result=baiduKuaidi100($logistics_sn,$shipping_code);
		 $this->result=$result['data'];
		 $this->order_sn=$order_sn;
		 $this->shipping_name=$shipping['shipping_name'];
		 $this->logistics_sn=$logistics_sn;
		 $this->display('baiduKuaidi100');
	 } 
   
	//打印订单
	public function printOrder(){
	    $this->orderShow('OrderInfo/printOrder');
	}
	
	//订单批量导出EXCEL
	public function exportExcelList(){
		set_time_limit(0);		
		$fieldArray=array(
			    0=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'订单号','field'=>'order_sn'),
			    1=>array('type'=>'txt','width'=>'40','height'=>'50','info'=>'下单会员','field'=>'m_user_name'),
				2=>array('type'=>'txt','width'=>'50','height'=>'50','info'=>'供应商名称','field'=>'s_name'),
				3=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'物流费','field'=>'freight'),
				4=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'发票税金','field'=>'taxes'),
				5=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'订单金额','field'=>'order_amount'),
				6=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'订单状态','field'=>'order_status'),
				7=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'支付状态','field'=>'pay_status'),
				8=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'配送状态','field'=>'shipping_status'),
				9=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'支付方式','field'=>'pay_type'),
				10=>array('type'=>'txt','width'=>'30','height'=>'50','info'=>'收货人','field'=>'consignee'),				
				11=>array('type'=>'txt','width'=>'20','height'=>'50','info'=>'手机','field'=>'mobile'),									
			   12=>array('type'=>'address','width'=>'50','height'=>'50','info'=>'详细地址','field'=>'country,province,city,district,address'),
			   13=>array('type'=>'date','width'=>'20','height'=>'50','info'=>'订单时间','field'=>'add_time')
		);
		$sql="SELECT o.order_sn,o.order_status,o.pay_status,o.shipping_status,o.goods_number,o.goods_amount,o.freight,o.order_amount,";
		$sql.="o.add_time,o.tax,o.taxes,o.consignee,o.country,o.province,o.city,o.district,o.address,o.zipcode,o.tel,o.mobile,o.email,o.pay_type,";
		$sql.="IF(m.nickname <>'',m.nickname,m.user_name) AS m_user_name,s.name AS s_name FROM ".PREFIX."order_info AS o JOIN ";
		$sql.=PREFIX."member AS m ON (o.user_id=m.id) JOIN ".PREFIX."goods_supplier AS s ON (s.id=o.goods_supplier) ORDER BY o.order_id DESC";
		$result=M()->query($sql);
	    $orderStatus=array(0=>'已取消',1=>'未确认',2=>'交易完成',3=>'退款',4=>'退款完成');
		$shippingStatus=array(0=>'未发货',1=>'已发货',2=>'已收货',3=>'已收货');
	    $payStatus=array(0=>'未付款',1=>'付款中',2=>'已付款');	
		$payType=array(0=>'在线支付',1=>'到店自提',2=>'货到付款',3=>'余额支付');
		foreach($result as $k=>$v){
			if($v['order_amount']==0){
				$result[$k]['order_status']=$orderStatus[$v['order_status']];
				$result[$k]['pay_status']='积分对换';
				$result[$k]['shipping_status']=$shippingStatus[$v['shipping_status']];
				$result[$k]['pay_type']=$payType[$v['pay_type']];				
			}else{
				$result[$k]['order_status']=$orderStatus[$v['order_status']];
				$result[$k]['pay_status']=$payStatus[$v['pay_status']];
				$result[$k]['shipping_status']=$shippingStatus[$v['shipping_status']];
				$result[$k]['pay_type']=$payType[$v['pay_type']];				
			}
		}
		exportExcel($result,$fieldArray,'./'.C('USER_IMG'));
	}		
	
	//导出单个订单
	public function exportEXCEL(){
	    $orderStatus=array(0=>'未确认',1=>'已确认',2=>'交易完成',3=>'退款',4=>'退款完成');
		$shippingStatus=array(0=>'未发货',1=>'已发货',2=>'已收货',3=>'已收货');
	    $payStatus=array(0=>'未付款',1=>'付款中',2=>'已付款');	
        $model=new OrderInfoModel();
		$res=$model->orderShow($this,$_GET['order_id'],true);	
		p(1);die;
		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel.php");    
		require(APP_PATH."Plugins/PHPExce 1.7.4/PHPExcel/Writer/Excel5.php");  
		//require 'PHPExce 1.7.4/Classes/PHPExcel/Writer/Excel2007.php'; 
		
		// 创建一个处理对象实例 
		$objExcel = new PHPExcel(); 
		
		// 创建文件格式写入对象实例, uncomment 
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式  
		
		// 创建文件格式写入对象实例, uncomment 
		//$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于2007 格式 
		
		//创建文件对像
		$objProps = $objExcel->getProperties ();
		
		//设置创建者
		$objProps->setCreator ( 'AAAAA');
		
		//设置最后修改者
		$objProps->setLastModifiedBy("BBBBB");
		
		//描述
		$objProps->setDescription("CCCCC");
		
		//设置标题
		$objProps->setTitle ( '第一个工作表' );
		
		//设置题目
		$objProps->setSubject("ffice 2007 XLSX Test Document");
		
		
		//设置关键字
		$objProps->setKeywords ( 'office 2007 openxml php' );
		
		//设置分类
		$objProps->setCategory ( "Test result file");
		
		//工作表设置
		$objExcel->setActiveSheetIndex( 0 );
		$objExcel->getSheet(0)->setTitle('第一个工作表'); //worksheet工作表标题
		$objActSheet = $objExcel->getActiveSheet ();
		
		$objExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	//全局上下居中
		$objExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30); //全局设置行高
		
		/******订单信息*************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A1', '订单信息');
		$top1 = $objActSheet->getStyle('A1:L1'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A1:L1'); 
		$objStyleA1 = $objActSheet->getStyle('A1:L1');	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(16);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('FF999999');  //字体色
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A2', '订单号：');
		$top1 = $objActSheet->getStyle('A2:C2'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A2:C2'); 
		$objStyleA1 = $objActSheet->getStyle('A2:C2');	
		$objActSheet->setCellValue ( 'D2',' '.$res['order_sn']); //订单号{$orders['order_sn']}
		$top1 = $objActSheet->getStyle('D2:F2'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D2:F2'); 
		$objStyleA1 = $objActSheet->getStyle('D2:F2');	
		
		$objActSheet->setCellValue ( 'G2', '订单状态：');
		$top1 = $objActSheet->getStyle('G2:I2'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G2:I2'); 
		$objStyleA1 = $objActSheet->getStyle('J2:L2');	
		$objActSheet->setCellValue ( 'J2', '['.$orderStatus[$res['order_status']].'] ['.$shippingStatus[$res['shipping_status']].'] ['.$payStatus[$res['pay_status']].']'); //订单状态[退货] [未发货] [未付款]
		$top1 = $objActSheet->getStyle('J2:L2'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J2:L2'); 
		$objStyleA1 = $objActSheet->getStyle('J2:L2');	
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A3', '购货人：'); 
		$top1 = $objActSheet->getStyle('A3:C3'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A3:C3'); 
		$objStyleA1 = $objActSheet->getStyle('A3:C3');	
		$objActSheet->setCellValue ( 'D3', $res['user_name']); //购货人{$orders.user_name} 
		$top1 = $objActSheet->getStyle('D3:F3'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D3:F3'); 
		$objStyleA1 = $objActSheet->getStyle('D3:F3');	
		
		$objActSheet->setCellValue ( 'G3', '下单时间：');
		$top1 = $objActSheet->getStyle('G3:I3'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G3:I3'); 
		$objStyleA1 = $objActSheet->getStyle('J3:L3');	
		$objActSheet->setCellValue ( 'J3', date('Y-m-d H:i:s',$res['add_time'])); //下单时间{$orders.add_time|date='Y-m-d H:i:s',###}
		$top1 = $objActSheet->getStyle('J3:L3'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J3:L3'); 
		$objStyleA1 = $objActSheet->getStyle('J3:L3');	
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A4', '支付方式：'); 
		$top1 = $objActSheet->getStyle('A4:C4'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A4:C4'); 
		$objStyleA1 = $objActSheet->getStyle('A4:C4');	
		$objActSheet->setCellValue ( 'D4',$res['pay_way']); //支付方式{$orders.pay_way}
		$top1 = $objActSheet->getStyle('D4:F4'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D4:F4'); 
		$objStyleA1 = $objActSheet->getStyle('D4:F4');	
		
		$objActSheet->setCellValue ( 'G4', '是否发货：');
		$top1 = $objActSheet->getStyle('G4:I4'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G4:I4'); 
		$objStyleA1 = $objActSheet->getStyle('J4:L4');	
		$tempVal=$res['shipping_time'] > 0 ? date('Y-m-d H:i:s',$res['shipping_time']) : '未发货';
		$objActSheet->setCellValue ( 'J4',$tempVal); //发货时间{$orders.shipping_time}
		$top1 = $objActSheet->getStyle('J4:L4'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J4:L4'); 
		$objStyleA1 = $objActSheet->getStyle('J4:L4');	
		
		/********收货人信息***********************************************/
		$objExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(30); //全局设置行高
		
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A5', '收货人信息');
		$top1 = $objActSheet->getStyle('A5:L5'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A5:L5'); 
		$objStyleA1 = $objActSheet->getStyle('A5:L5');	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(16);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('FF999999');  //字体色
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A6', '收货人：'); 
		$top1 = $objActSheet->getStyle('A6:C6'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A6:C6'); 
		$objStyleA1 = $objActSheet->getStyle('A6:C6');	
		$objActSheet->setCellValue ( 'D6', $res['consignee']); //收货人{$orders.consignee}
		$top1 = $objActSheet->getStyle('D6:F6'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D6:F6'); 
		$objStyleA1 = $objActSheet->getStyle('D6:F6');	
		
		$objActSheet->setCellValue ( 'G6', '电子邮件：');
		$top1 = $objActSheet->getStyle('G6:I6'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G6:I6'); 
		$objStyleA1 = $objActSheet->getStyle('J6:L6');	
		$objActSheet->setCellValue ( 'J6', $res['email']); //电子邮件{$orders.email}
		$top1 = $objActSheet->getStyle('J6:L6'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J6:L6'); 
		$objStyleA1 = $objActSheet->getStyle('J6:L6');	
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A7', '地址：'); 
		$top1 = $objActSheet->getStyle('A7:C7'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A7:C7'); 
		$objStyleA1 = $objActSheet->getStyle('A7:C7');	
		$objActSheet->setCellValue ( 'D7', $res['region_country'].$res['region_province'].$res['region_city'].$res['region_district'].$res['address']); //地址
		$top1 = $objActSheet->getStyle('D7:F7'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D7:F7'); 
		$objStyleA1 = $objActSheet->getStyle('D7:F7');	
		
		$objActSheet->setCellValue ( 'G7', '邮编：');
		$top1 = $objActSheet->getStyle('G7:I7'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G7:I7'); 
		$objStyleA1 = $objActSheet->getStyle('J7:L7');	
		$objActSheet->setCellValue ( 'J7', $res['zipcode']); //邮编{$orders.zipcode}
		$top1 = $objActSheet->getStyle('J7:L7'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J7:L7'); 
		$objStyleA1 = $objActSheet->getStyle('J7:L7');	
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A8', '电话：'); 
		$top1 = $objActSheet->getStyle('A8:C8'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A8:C8'); 
		$objStyleA1 = $objActSheet->getStyle('A8:C8');	
		$objActSheet->setCellValue ( 'D8',$res['tel']); //电话{$orders.tel}
		$top1 = $objActSheet->getStyle('D8:F8'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D8:F8'); 
		$objStyleA1 = $objActSheet->getStyle('D8:F8');	
		
		$objActSheet->setCellValue ( 'G8', '手机：');
		$top1 = $objActSheet->getStyle('G8:I8'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G8:I8'); 
		$objStyleA1 = $objActSheet->getStyle('J8:L8');	
		$objActSheet->setCellValue ( 'J8', $res['mobile']); //手机{$orders.mobile}
		$top1 = $objActSheet->getStyle('J8:L8'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J8:L8'); 
		$objStyleA1 = $objActSheet->getStyle('J8:L8');	
		
		/********发票信息信息***********************************************/
		$objExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(30); //全局设置行高
		
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A9', '发票信息');
		$top1 = $objActSheet->getStyle('A9:L9'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A9:L9'); 
		$objStyleA1 = $objActSheet->getStyle('A9:L9');	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(16);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('FF999999');  //字体色
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A10', '发票类型：'); 
		$top1 = $objActSheet->getStyle('A10:C8'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A10:C10'); 
		$objStyleA1 = $objActSheet->getStyle('A10:C10');	
		$objActSheet->setCellValue ( 'D10',$res['inv_type']); //发票类型{$orders.inv_type}
		$top1 = $objActSheet->getStyle('D10:F10'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D10:F10'); 
		$objStyleA1 = $objActSheet->getStyle('D10:F10');	
		
		$objActSheet->setCellValue ( 'G10', '发票抬头：');
		$top1 = $objActSheet->getStyle('G10:I10'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('G10:I10'); 
		$objStyleA1 = $objActSheet->getStyle('J10:L10');	
		$objActSheet->setCellValue ( 'J10', $res['inv_payee']); //发票抬头{$orders.inv_payee}
		$top1 = $objActSheet->getStyle('J10:L10'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('J10:L10'); 
		$objStyleA1 = $objActSheet->getStyle('J10:L10');	
		
		/*******************************************************/
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A11', '发票内容：'); 
		$top1 = $objActSheet->getStyle('A11:C1'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objActSheet->mergeCells('A11:C11'); 
		$objStyleA1 = $objActSheet->getStyle('A11:C11');	
		$objActSheet->setCellValue ( 'D11',$res['inv_content']); //发票类型{$orders.inv_content}
		$top1 = $objActSheet->getStyle('D11:L11'); //向中间对齐
		$top1=$top1->getAlignment(); 
		$objActSheet->mergeCells('D11:L11'); 
		$objStyleA1 = $objActSheet->getStyle('D11:L11');	
		
		/********商品信息***********************************************/
		$objExcel->getActiveSheet()->getRowDimension(12)->setRowHeight(30); //全局设置行高
		
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A12', '商品信息');
		$top1 = $objActSheet->getStyle('A12:L12'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A12:L12'); 
		$objStyleA1 = $objActSheet->getStyle('A12:L12');	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(16);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('FF999999');  //字体色
		
		$objActSheet->setCellValue ( 'A13', '商品名称');//商品名称
		$top1 = $objActSheet->getStyle('A13:B13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A13:B13'); 
		$objStyleA1 = $objActSheet->getStyle('A13:B13');	
		
		$objActSheet->setCellValue ( 'C13', '商品货号');//商品货号
		$top1 = $objActSheet->getStyle('C13:D13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('C13:D13'); 
		$objStyleA1 = $objActSheet->getStyle('C13:D13');	
		
		$objActSheet->setCellValue ( 'E13', '市场价');//
		$top1 = $objActSheet->getStyle('E13:F13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('E13:F13'); 
		$objStyleA1 = $objActSheet->getStyle('E13:F13');	
		
		$objActSheet->setCellValue ( 'G13', '本店价');//本店价
		$top1 = $objActSheet->getStyle('G13:H13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('G13:H13'); 
		$objStyleA1 = $objActSheet->getStyle('G13:H13');	
		
		$objActSheet->setCellValue ( 'I13', '商品数量');//商品数量
		$top1 = $objActSheet->getStyle('G13:J13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('I13:J13'); 
		$objStyleA1 = $objActSheet->getStyle('I13:G13');	
		
		$objActSheet->setCellValue ( 'K13', '商品小计');//商品小计
		$top1 = $objActSheet->getStyle('K13:L13'); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('K13:L13'); 
		$objStyleA1 = $objActSheet->getStyle('K13:L13');	
		
		
		/*这里要遍历*/
		$i=13;
		foreach($res['goodsList'] as $k=>$v){
		    $i++;
			$objActSheet->setCellValue ( 'A'.$i, $v['goods_name']);//商品名称{$gl.goods_name}
			$top1 = $objActSheet->getStyle('A'.$i.':B'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('A'.$i.':B'.$i); 
			$objStyleA1 = $objActSheet->getStyle('A'.$i.':B'.$i);	
			
			$objActSheet->setCellValue ( 'C'.$i,$v['goods_sn']);//商品货号{$gl.goods_sn}
			$top1 = $objActSheet->getStyle('C'.$i.':D'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('C'.$i.':D'.$i); 
			$objStyleA1 = $objActSheet->getStyle('C'.$i.':D'.$i);	
			
			$objActSheet->setCellValue ( 'E'.$i, '￥'.$v['market_price']);//市场价{$gl.market_price}
			$top1 = $objActSheet->getStyle('E'.$i.':F'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('E'.$i.':F'.$i); 
			$objStyleA1 = $objActSheet->getStyle('E'.$i.':F'.$i);	
			
			$objActSheet->setCellValue ( 'G'.$i,'￥'.$v['goods_price']);//本店价{$gl.goods_price}
			$top1 = $objActSheet->getStyle('G'.$i.':H'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('G'.$i.':H'.$i); 
			$objStyleA1 = $objActSheet->getStyle('G'.$i.':H'.$i);	
			
			$objActSheet->setCellValue ( 'I'.$i,$v['goods_number']);//商品数量{$gl.goods_number}
			$top1 = $objActSheet->getStyle('G'.$i.':J'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('I'.$i.':J'.$i); 
			$objStyleA1 = $objActSheet->getStyle('I'.$i.':G'.$i);	
			
			$objActSheet->setCellValue ( 'K'.$i, '￥'.$v['goods_price'] * $v['goods_number']);//商品小计
			$top1 = $objActSheet->getStyle('K'.$i.':L'.$i); //向中间对齐
			$top1=$top1->getAlignment(); 
			//合并单元格  
			$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->mergeCells('K'.$i.':L'.$i); 
			$objStyleA1 = $objActSheet->getStyle('K'.$i.':L'.$i);	
		}
		/*要遍历结束*/
		/********费用信息************************************************/
		$letter=$i+1;
		$objExcel->getActiveSheet()->getRowDimension($letter)->setRowHeight(30); //全局设置行高
		
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A'.$letter, '费用信息');
		$top1 = $objActSheet->getStyle('A'.$letter.':L'.$letter); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A'.$letter.':L'.$letter); 
		$objStyleA1 = $objActSheet->getStyle('A'.$letter.':L'.$letter);	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(16);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('FF999999');  //字体色
		
		/******************************************/
		$letter=$i+2;
		$objExcel->getActiveSheet()->getRowDimension($letter)->setRowHeight(20); //全局设置行高
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A'.$letter, '商品总金额：￥'.$res['goods_amount']);
		$top1 = $objActSheet->getStyle('A'.$letter.':L'.$letter); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A'.$letter.':L'.$letter); 
		$objStyleA1 = $objActSheet->getStyle('A'.$letter.':L'.$letter);	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(14);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('#FF0000');  //字体色
		
		/******************************************/
		$letter=$i+3;
		$objExcel->getActiveSheet()->getRowDimension($letter)->setRowHeight(20); //全局设置行高
		//单元格赋值   例：
		$objActSheet->setCellValue ( 'A'.$letter, '订单总金额：￥'.$res['order_amount']);
		$top1 = $objActSheet->getStyle('A'.$letter.':L'.$letter); //向中间对齐
		$top1=$top1->getAlignment(); 
		//合并单元格  
		$objExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objActSheet->mergeCells('A'.$letter.':L'.$letter); 
		$objStyleA1 = $objActSheet->getStyle('A'.$letter.':L'.$letter);	
		//设置字体 
		$objFontA1 = $objStyleA1->getFont(); 
		$objFontA1->setName('微软黑体');  //选择字体
		$objFontA1->setSize(14);   //字体大小
		$objFontA1->setBold(true); 
		$objFontA1->getColor()->setARGB('#FF0000');  //字体色

		//文件直接输出到浏览器
		header ( 'Pragma:public');
		header ( 'Expires:0');
		header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header ( 'Content-Type:application/force-download');
		header ( 'Content-Type:application/vnd.ms-excel');
		header ( 'Content-Type:application/octet-stream');
		header ( 'Content-Type:application/download');
		header ( 'Content-Disposition:attachment;filename='. $res['order_sn'] .'.xls' );
		header ( 'Content-Transfer-Encoding:binary');
		$objWriter->save ( 'php://output');	
	}	

	
	//设置订单状态操作
	public function changeState(){
	      $M=M('order_info');
		  $status=$M->field('order_id,order_sn,user_id,order_status,pay_status,shipping_status,order_amount,use_integral,pay_type,pay_points,rank_points,bonus_sn,bonus_type_id')
		  ->where(array('order_id'=>$_POST['id']))->find();
		  if($status['order_status']==2) return_json(300,'交易完成的订单不可再操作！');
	      if($status['order_status']==0 || $status['order_status']==4) return_json(300,'已取消或已退货的不可再回修改1！'); //已取消或已退货的不能再操作
		  if($status['pay_status'] ==2 &&  $_POST['pay_status'] < 2) return_json(300,'不可回修未付款状态！'); //已付款的不能回修改成未付款
		  $save['order_status']=$_POST['order_status'];
		  $save['pay_status']=$_POST['pay_status'];
		  $save['shipping_status']=$_POST['shipping_status'];
		  //修改发货时间		
		  if($_POST['shipping_status']==2) {
			  //if(C('IS_LOGISTICS')){
				  if(!is_abc_num($_POST['logistics_sn'])) return_json(300,'你正在发货，请输入物流单号');
				 // if(!is_nums($_POST['logistics_id'])) return_json(300,'你正在发货，请选择物流公司');
				  $save['logistics_sn']=$_POST['logistics_sn'];
				 // $save['logistics_id']=$_POST['logistics_id'];			  
			 // }
              $save['shipping_time']=time();		 
		  } 
		  $save['shipping_code']=trim($_POST['shipping_code']);
		  //启动事务
	      $M->startTrans(); 
	      if(!$M->where(array('order_id'=>$_POST['id']))->save($save)) {
              $M->rollback();	
		  	  return_json(300,'处理订单状态失败！');
		  }
		  $action['order_id']=$_POST['id'];
		  $action['action_user']=$_SESSION[C('SESSION_NAME_VAL')];
		  $action['order_status']=$_POST['order_status'];
		  $action['shipping_status']=$_POST['shipping_status'] ? $_POST['shipping_status'] : 0;
		  $action['pay_status']=$_POST['pay_status'] ? $_POST['pay_status'] : 0;
		  $action['action_note']=$_POST['content'] ? emptyHtml($_POST['content']) : '';
		  $action['log_time']=time();
		  if(!M('order_action')->add($action)) {
		  	  $M->rollback();	
		  	  return_json(300,'写入操作日志失败！');
		  }
		  
	      //退货中需发邮件通知
	     if($_POST['order_status']==3 && ($_POST['shipping_status'] > 1)){
				 $member=M('member')->field('user_name,full_name,surname')->where("id = '{$status['user_id']}'")->find();
				 $tpl='./Public/EmailTpl/AppsRefund/'.pfix('thtz').'.html';
				 $contents=file_get_contents($tpl);
				 $contents=str_replace('{$localhost}',$_SERVER['HTTP_HOST'],$contents);
				 //$contents=str_replace('{$str1}',gmstrftime(" %d %b, %X "),$contents);
				 $contents=str_replace('{$str1}',date('Y.m.d H:i',time()),$contents);
				 $contents=str_replace('{$str2}',replace_isl('DearHowHouDo','尊敬的%d先生，您好',$member['full_name'].' '.$member['surname']),$contents);			
				 //$result=send_mail($member['user_name'],isL(L('MerchandiseReturn'),'退货通知'),$contents,false);	
				 $result=send_mail2($member['user_name'],isL(L('MerchandiseReturn'),'退货通知'),$contents);
				 if(!$result){
					 $M->rollback();
					 return_json(300,'发送退货邮件通知失败');
				 }
		  }		  
		
		  if($_POST['order_status']==0 || $_POST['order_status']==4){ //取消或退货完成
		       //退回用户所使用优惠政策
		       $os[0]['user_id']=$status['user_id'];
		       $os[0]['order_id']=$status['order_id'];
			   $os[0]['use_integral']=$status['use_integral'];
			   $os[0]['bonus_sn']=$status['bonus_sn'];
			   $model=new CommonModel();
			   $returnu=$model->returnUser($os,false);
			   if(!$returnu['success']) {
			   	   $M->rollback();
				   return_json(300,$returnu['msg']);
			   }
			   //退款完成
			   if($_POST['order_status']==4 && ($status['pay_type']==0 || $status['pay_type']==3) && $status['pay_status'] >=2){
				   //返回用户资金
				 /*  if(!M('member')->where(array('id'=>$status['user_id']))->setInc('member_funds',$status['order_amount'])) {
				   	    $M->rollback();
					    return_json(300,'退回会员资金失败！');
				   }					    
				   //用户帐户清单
				   $langs=langAllField('change_desc');	
				   $account['member_id']=$status['user_id'];
				   $account['transaction']=0;
				   $account['return_mark']=0;
				   $account['pay_code']='';
				   $account['member_money']=$status['order_amount'];
				   $account['frozen_money']=0;
				   $account['rank_points']=0;
				   $account['pay_points']=0;
				   $account['change_time']=time();
				   foreach($langs['lang_arr'] as $k=>$v) $account[$v]=C($langs['lang_pfix'][$k].'ORD_COM_MONEY').'('.$status['order_sn'].')';
				   $account['change_type']=99;
				   if(!M('member_account')->add($account)) {
				   	    $M->rollback();
					    return_json(300,'写入资金退回清单失败！');
				   }	*/	
			   }
		  }else if($_POST['order_status']==2){//交易完成
		       $os[0]['order_id']=$status['order_id'];
		       $os[0]['user_id']=$status['user_id'];		       
			   $os[0]['pay_points']=$status['pay_points'];
			   $os[0]['rank_points']=$status['rank_points'];		
			   $os[0]['bonus_type_id']=$status['bonus_type_id'];  
		  	   $model=new CommonModel();
			   $div=$model->deliveryUser($os);
			   if(empty($div['success'])){
			   	   $M->rollback();
				   return_json(300,$div['msg']);
			   }
		  }
          $M->commit();	
		  $action= 'orderShow';
		  return_json(200,'操作成功！',$action,'','forward',U(APP_APPS.'/OrderInfo/'.$action,array('order_id'=>$_POST['id'],'tpl'=>$_REQUEST['tpl'])));
	}
	
	//支付日志列表
	public function payLogs(){
	       $M=M('pay_log');
		   $counts=$M->count();
		   $this->callAjaxPage(20,$counts);//分页
	       $sql="SELECT p.*,m.mobile_phone AS user_name FROM ".PREFIX."pay_log AS p LEFT JOIN ".PREFIX."member AS m ON (p.user_id=m.id) ORDER BY p.log_id DESC LIMIT ".$GLOBALS['limit'];
		   $this->res=$M->query($sql);
	      $this->display();
	}
	
	//删除支付日志
	public function delPayLogs(){
	      $url=U('OrderInfo/payLogs',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
	      M('pay_log')->where(array('log_id'=>array('IN',$_REQUEST['ids'])))->delete();
		  return_json(200,'操作成功！','payLogs','','forward',$url);
	}
	
	//删除订单
	public function orderDel(){
		$where="order_id IN ({$_REQUEST['ids']})";
		$url=U('OrderInfo/orderList',array(C('VAR_PAGE')=>$_REQUEST[C('VAR_PAGE')]));
		M('order_info')->where($where)->delete();
		M('order_goods')->where(array('order_id'=>array('IN',$_REQUEST['ids'])))->delete();//删除订单商品表
		M('order_action')->where(array('order_id'=>array('IN',$_REQUEST['ids'])))->delete(); //删除订单操作日志
		M('order_discount')->where(array('order_id'=>array('IN',$_REQUEST['ids'])))->delete(); //活动优惠满即减记录表
		return_json(200,'操作成功！','orderList','','forward',$url);
	}
	
	//修改收货人信息界面
	public function saveAddress(){
		$field='order_id,consignee,country,province,city,district,address,zipcode,tel,mobile,email';
		$where=array('order_id'=>$_REQUEST['order_id'],'shipping_status'=>array('neq',1));
		$res=M('order_info')->field($field)->where($where)->find();
		$model=new CommonModel();		
		$res['region']=$model->getRegion($res['country'],$res['province'],$res['city'],$res['district']);
		$this->address=$res;
		$this->display();
	}
	
	//AJAX检索区域
	public function searchRegion(){
		$model=new CommonModel();
		return $model->searchRegion();
	}	
	
	//修改地址表单处理
   public function saveAddressForm(){
 		   if(!is_numeric($_POST['country'])) $this->error('请选择国家!');
		   if(!is_numeric($_POST['province'])) $this->error('请选择省份!');
		   if(!is_numeric($_POST['city'])) $this->error('请选择城市!');
		   if(!is_numeric($_POST['district'])) $this->error('请选择区/县!');
		   if(!$_POST['consignee']) $this->error('收货人姓名不能为空!');
		   if(!$_POST['email']) $this->error('电子邮件不能为空!');
		   if(!$_POST['address']) $this->error('祥细地址不能为空!');
		   if(!is_numeric($_POST['mobile'])) $this->error('手机格式错误!');
           D('Curd')->modify('order_info','orderShow','','order_id');	
   }	
 
}