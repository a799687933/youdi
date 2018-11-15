<?php
class ReportModel extends Model{
	/**
	*获取月报 getMonthReport('2014-10','表名称不带前缀','时间字段')，注（保存时间必须是时间截）
	* $dates 一个日期格式月份（2014-10）
	 * $tableName  表名称
	 * $whereFiled  时间字段名称
	 * $whereArr  数组，其它查找条件array('is_show'=>1,'count'=>1,'data'=>1,'logic'=>'AND');
	**/
	function getMonthReport($dates,$tableName,$whereFiled,$whereArr=array()){
		//其它条件
		if($whereArr){
			$logic=$whereArr['logic'] ? $whereArr['logic'] : 'AND';
			unset($whereArr['logic']);
			foreach($whereArr as $k=>$v){
				$whereStr.=$logic.' (`'.$k.'` = '.$v.') ';
			}
		}
		$dates=rtrim($dates,'/');
		$dates=ltrim($dates,'/');	
		$dates=str_replace('/','-',$dates);
		$dateArr=explode('-',$dates);
		$year=intval($dateArr[0]);
		$month=intval($dateArr[1]);
		$days=$this->getMonthDay($month,$year);
		$daysStr='';
		$cuntArr=array();
		$daySun= $days-1;
		$dayNum='';
		$j=0;
		for($i=1; $i<=$days; $i++){
		  if($i<10) $i='0'.$i;	
		  $daysStr.='"'.$i.'",';	  
		  $dayNum.=$j.',';
		  $j++;
		  //这里执行SQL
		  $dateSat=strtotime($dates.'-'.$i);
		  $dateEnd=strtotime($dates.'-'.$i)+86400;
		  $cuntSql.="(SELECT count(`".$whereFiled."`) FROM `".PREFIX.$tableName."` WHERE((`".$whereFiled."` >= '".$dateSat."') AND (`".$whereFiled."` <= '".$dateEnd."')". $whereStr ."))AS count_".$i.",";
		  
		  //订单表处理
		   if($tableName=='order_info'){
			    //订单已支付并且有效总金额
		        $orderSuccess.="(SELECT sum(`order_amount`)  FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$dateSat."') AND (`".$whereFiled."` <= '".$dateEnd."')) AND (`order_status` =0 OR `order_status` =1 OR `order_status` =7) AND `pay_status` = 2) AS sum_success_".$i.", ";
				
				//订单已退款总金额
				$orderRefund.="(SELECT sum(`order_amount`) FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$dateSat."') AND (`".$whereFiled."` <= '".$dateEnd."')) AND (`order_status` =4 OR `order_status` =5 OR `order_status` =6) AND `pay_status` = 2) AS sum_refund_".$i.", ";
		   }		  
		  
		}
		
		//订单表
		if($tableName=='order_info'){
			$orders=$orderSuccess.$orderRefund;
		}		
		
		$monthSat=strtotime($dates);
		$monthEnd=strtotime($dates)+($days*86400);
		$sql="SELECT ".$cuntSql.$orders."count(`".$whereFiled."`) AS monthCount FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$monthSat."') AND (`".$whereFiled."` <= '".$monthEnd."') ". $whereStr .")";
		$result=M()->query($sql);
		$monthCount=$result[0]['monthCount'];//月总数
		unset($result[0]['monthCount']);
		$result=$result[0];
		$i=1;
		foreach($result as $k=>$v){
			$monthStr.=$v ? $v : 0;
			$monthStr.=',';
			unset($result[$k]);
			if($i==$days) break;
			$i++;
		}
		$monthStr=rtrim($monthStr,','); //月数量
		
		$i=1;
		foreach($result as $k=>$v){
			$monthSumSuccess.=$v ? $v : 0;
			$monthSumSuccess.=',';
			unset($result[$k]);
			if($i==$days) break;
			$i++;
		}
		$monthSumSuccess=rtrim($monthSumSuccess,','); //月订单有效总金额		

		$i=1;
		foreach($result as $k=>$v){
			$monthSumRefund.=$v ? $v : 0;
			$monthSumRefund.=',';
			unset($result[$k]);
			if($i==$days) break;
			$i++;
		}
		$monthSumRefund=rtrim($monthSumRefund,','); //月订单退款总金额	
        
		//JS数组
		$jsArray='['.$monthStr.']';
		if($monthSumSuccess) $jsArray=$jsArray.',['.$monthSumSuccess.']';
		if($monthSumRefund) $jsArray=$jsArray.',['.$monthSumRefund.']';		
		
		$monthStr=$jsArray;
		$report['axisCount']=$daySun;
		$report['axisStr']=rtrim($daysStr,',');
		$report['linecDayStr']=rtrim($dayNum,',');
		$report['linecDataStr']=$monthStr;
		$report['dateTime']=$dates.' 月当前总数';
		$report['count']=$monthCount;//月总数
		if($monthCount){
			$info=intval($monthCount / $days) >=1 ? intval($monthCount / $days) : '不足1';
		}else{
			$info=0;
		}
		$report['average']='日均量(<b style="color:red;">'.$info.'</b>)';
		return $report;
	}
	
	
	
	/*获取年报表	getYearReport('2014','表名称不带前缀','时间字段')，注（保存时间必须是时间截）
	 * $dates 一个日期格式年份（2014）
	 * $tableName  表名称
	 * $whereFiled  时间字段名称
	 * $whereArr  数组，其它查找条件array('is_show'=>1,'count'=>1,'data'=>1,'logic'=>'AND');
	 * */
	function getYearReport($dates,$tableName,$whereFiled,$whereArr=array()){
		//其它条件
		if($whereArr){
			$logic=$whereArr['logic'] ? $whereArr['logic'] : 'AND';
			unset($whereArr['logic']);
			foreach($whereArr as $k=>$v){
				$whereStr.=$logic.' (`'.$k.'` = '.$v.') ';
			}
		}		
		$countArr=array();
		$monthStr='';
		$monthNum=0;
		$yearToTime=0;
		$dates=rtrim($dates,'/');
		$dates=ltrim($dates,'/');	
		$dates=str_replace('/','-',$dates);
		if(preg_match('/-/',$dates)){
			$dates=explode('-',$dates);
			$dates=$dates[0];
		}
		for($i=1;$i<=12;$i++){
		   $days=$this->getMonthDay($i,$dates);
		   $timeStart=strtotime($dates.'-'.$i);
		   $timeEnd=strtotime($dates.'-'.$i)+($days*86400);
		   $yearToTime+=$days*86400;
		   $monthStr.='"'.$i.'月",';
		   if($i<12) $monthNum.=','.$i;
		   //这里执行SQL
		   $cuntSql.="(SELECT count(`".$whereFiled."`) FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$timeStart."') AND (`".$whereFiled."` <= '".$timeEnd."') " .$whereStr. ")) AS count_".$i.", ";
		   
		  //订单表处理
		   if($tableName=='order_info'){
			    //订单已支付并且有效总金额
		        $orderSuccess.="(SELECT sum(`order_amount`)  FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$timeStart."') AND (`".$whereFiled."` <= '".$timeEnd."')) AND (`order_status` =0 OR `order_status` =1 OR `order_status` =7) AND `pay_status` = 2) AS sum_success_".$i.", ";
				
				//订单已退款总金额
				$orderRefund.="(SELECT sum(`order_amount`) FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$timeStart."') AND (`".$whereFiled."` <= '".$timeEnd."')) AND (`order_status` =4 OR `order_status` =5 OR `order_status` =6) AND `pay_status` = 2) AS sum_refund_".$i.", ";
		   }
		   
		}		
		$yearSat=strtotime($dates.'-1-1 00:00:00'); //1月1日0时0分0钞
		
		//订单表
		if($tableName=='order_info'){
			$orders=$orderSuccess.$orderRefund;
		}
		
		
		$yearEnd=$yearSat+$yearToTime;
		$sql="SELECT ".$cuntSql.$orders."count(`".$whereFiled."`) AS yearCount FROM `".PREFIX.$tableName."` WHERE ((`".$whereFiled."` >= '".$yearSat."') AND (`".$whereFiled."` <= '".$yearEnd."') ". $whereStr .")";
		$result=M()->query($sql);
		
		$yearCount=$result[0]['yearCount'];//年总数量
		unset($result[0]['yearCount']);
		$result=$result[0];
		$i=1;
		foreach($result as $k=>$v){
			$yearStr.=$v ? $v : 0;
			$yearStr.=',';
			unset($result[$k]);
			if($i==12) break;
			$i++;
		}
		$yearStr=rtrim($yearStr,','); //月数量
		
		$i=1;
		foreach($result as $k=>$v){
			$yearSumSuccess.=$v ? $v : 0;
			$yearSumSuccess.=',';
			unset($result[$k]);
			if($i==12) break;
			$i++;
		}
		$yearSumSuccess=rtrim($yearSumSuccess,','); //月订单有效总金额		

		$i=1;
		foreach($result as $k=>$v){
			$yearSumRefund.=$v ? $v : 0;
			$yearSumRefund.=',';
			unset($result[$k]);
			if($i==12) break;
			$i++;
		}
		$yearSumRefund=rtrim($yearSumRefund,','); //月订单退款总金额	
        
		//JS数组
		$jsArray='['.$yearStr.']';
		if($yearSumSuccess) $jsArray=$jsArray.',['.$yearSumSuccess.']';
		if($yearSumRefund) $jsArray=$jsArray.',['.$yearSumRefund.']';
		$report['axisCount']=11;
		$report['axisStr']=rtrim($monthStr,',');
		$report['linecDayStr']=$monthNum;
		$report['linecDataStr']=$jsArray;
		$report['dateTime']=$dates.' 年当前总数';
		$report['count']=$yearCount;//月总数
		if($yearCount){
			$info=intval($yearCount / 12) >=1? intval($yearCount / 12) : '不足1';
		}else{
			$info=0;
		}
		$report['average']='月均量(<b style="color:red;">'.$info.'</b>)';
		return $report;	
	}
	
	/**
	*获取月份天数包括闰年
	*$month 月份
	*$year  年份
	*return int 月份天数
	**/
	function getMonthDay($month,$year) {
		$year=intval($year);
		$month=intval($month);
		switch ($month) {
			case 4 :
			case 6 :
			case 9 :
			case 11 :
				$days = 30;
				break;
			case 2 :
				if ($year % 4 == 0) {
					if ($year % 100 == 0) {
						$days = $year % 400 == 0 ? 29 : 28;
					} else {
						$days = 29;
					}
				} else {
					$days = 28;
				}
				break;
	
			default :
				$days = 31;
				break;
		}
		return intval($days);
	}
	
	/**
	*获取订单表的金额数据
	*$year  年份，如果带月份则当月的数据，否则是一年的数据；
	*/
	function geTyearToTime($year){
		$monthStr=explode('-',$year);
		if($monthStr[1]){
			$monthCount=$this->getMonthDay($monthStr[1],$monthStr[0]); //获取当月的天数
			$timeStart=strtotime($monthStr[0].'-'.$monthStr[1].'-1 00:00:00'); //开始时间
			$timeEnd=strtotime($monthStr[0].'-'.$monthStr[1].'-'.$monthCount.' 23:59:59'); //结束时间		
			
		}else{
			$timeStart=strtotime($year.'-1-1 00:00:00'); //开始时间
			$timeEnd=strtotime($year.'-12-31 23:59:59'); //结束时间		
		}
		
		if($_REQUEST['shops_id'] > 0) $shops=" AND shops_id='{$_REQUEST['shops_id']}' ";
		
		//未确定订单
		$notSure="(SELECT sum(order_amount) AS notSure FROM ".PREFIX."order_info WHERE add_time >='{$timeStart}' AND add_time <='{$timeEnd}' AND order_status=0 {$shops}) AS notSure,";

		//已确定订单(成功交易订单)
		$established ="(SELECT sum(order_amount) AS established  FROM ".PREFIX."order_info WHERE (add_time >='{$timeStart}' AND add_time <='{$timeEnd}') AND (order_status=1 OR order_status=7) AND pay_status=2 {$shops}) AS established,";

		//已取消订单
		$cancelled ="(SELECT sum(order_amount) AS cancelled  FROM ".PREFIX."order_info WHERE add_time >='{$timeStart}' AND add_time <='{$timeEnd}' AND order_status=2 {$shops}) AS cancelled,";

		//无效订单
		$invalid ="(SELECT sum(order_amount) AS invalid  FROM ".PREFIX."order_info WHERE add_time >='{$timeStart}' AND add_time <='{$timeEnd}' AND order_status=3 {$shops}) AS invalid,";

		//要处理退款订单
		$refund ="(SELECT sum(order_amount) AS refund  FROM ".PREFIX."order_info WHERE (add_time >='{$timeStart}' AND add_time <='{$timeEnd}') AND  order_status=4 AND pay_status=2 {$shops}) AS refund,";

		//退款商议中订单
		$consult ="(SELECT sum(order_amount) AS consult  FROM ".PREFIX."order_info WHERE (add_time >='{$timeStart}' AND add_time <='{$timeEnd}') AND  order_status=5 AND pay_status=2 {$shops}) AS consult,";

		//已退款订单
		$refundOK ="(SELECT sum(order_amount) AS refundOK  FROM ".PREFIX."order_info WHERE (add_time >='{$timeStart}' AND add_time <='{$timeEnd}') AND  order_status=6 AND pay_status=2 {$shops}) AS refundOK,";

		//不予退款订单
		$gotGrant ="(SELECT sum(order_amount) AS gotGrant  FROM ".PREFIX."order_info WHERE (add_time >='{$timeStart}' AND add_time <='{$timeEnd}') AND  order_status=7 AND pay_status=2 {$shops}) AS gotGrant,";
		
		$alls=$notSure.$established.$cancelled.$invalid.$refund.$consult.$refundOK.$gotGrant;
		//订单总金额
		$sql="SELECT {$alls} sum(order_amount)  AS order_amount  FROM ".PREFIX."order_info WHERE add_time >='{$timeStart}' AND add_time <='{$timeEnd}' {$shops}	"; 
		$res=M()->query($sql);
		return $res[0];
	}	
		
}
?>