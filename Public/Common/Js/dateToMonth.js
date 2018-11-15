/**
*JS 月份档期
*/

/**
*N个月内的日期
*dtstr  日期格式 2016-1-28
*n        数字几个月
*return  array
* addmulMonth('2016-1-28', 3) 三个月内的日期
*/
function addmulMonth(dtstr, n){      
    var s = dtstr.split("-");
    var yy = parseInt(s[0]);
    var mm = parseInt(s[1])-1 ; 
    var dd = parseInt(s[2]); 
    var dt = new Date(yy, mm, dd); 
	var dates=new Array();
	for(var i=0;i < n;i++){
		if(i==0) dt.setMonth(dt.getMonth());
		else dt.setMonth(dt.getMonth() +1);
		var month = parseInt(dt.getMonth()) + 1;
		dates[i] =dt.getFullYear() + "-" + month  + "-" + dd;		
	}
    return dates;
} 

//月视图 
function showMonth(dtstr, n,obj) { 
    var days='';
	var startweek='';
	var table='';
	var yyMmDd='';
	var tdClass='states0';
	var inputVal='0';
	var dateArr=addmulMonth(dtstr, n);
	for(var j=0;j < dateArr.length;j++){
		var s = dateArr[j].split("-");
		var yy = parseInt(s[0]);
		var mm = parseInt(s[1])-1 ; 
		var dd = parseInt(s[2]); 		
		days = getNowMonthDays(yy, mm + 1); 
		var date = new Date(yy,mm,dd);
		startweek = date.getDay() + 1; 
		var html='<table class="tc_calendar">';
		html+="<tr ><td colspan='7' style='border:none;'>"+yy+"-"+(mm +1)+"</td></tr>";
		html+= "<tr class='tc_week'><td>星期一</td><td>星期二</td><td>星期三</td><td>星期四</td><td>星期五</td><td>星期六</td><td>星期日</td></tr>";
		html+="<tr class='even'>"; 
		var index = 0; 
		for (var i = 1; i < startweek; i++) { 
			html += "<td> </td>"; index++; 
		} 
		for (var i = 1; i <= days; i++) { 
			if (index % 7 == 0) html += "</tr><tr class='even'>"; 
			yyMmDd=yy+"-"+(mm +1)+"-"+i;
			if(obj){
				for(var t in obj){
					if(obj[t].dayecho==yyMmDd){
						if(parseInt(obj[t].states)==1){
							tdClass='states1';
							inputVal=1;
						}else if(parseInt(obj[t].states)==2){
							tdClass='states2';
							inputVal=2;
						}else{
							tdClass='states0';
							inputVal=0;
						}
						break;
					}						
				}	
				html+= "<td class='"+tdClass+"' onclick=\"showDates(this,"+i+")\">" + i;
				html+="<input type='hidden' value='"+yyMmDd+"' name='dayecho[]'/>"; 
				html+="<input type='hidden' value='"+inputVal+"' name='states[]' />";		
				tdClass='states0';
				inputVal=0;				
			}else{
				html+= "<td class='states0' onclick=\"showDates(this,"+i+")\">" + i;
				html+="<input type='hidden' value='"+yyMmDd+"' name='dayecho[]'/>"; 
				html+="<input type='hidden' value='0' name='states[]' />";			
			}
			html+="<div class='td-divs'>";
			html+="<span class='span-shows1' onclick='spanClick(this,0)'>&nbsp</span>";
			html+="<span class='span-shows2' onclick='spanClick(this,1)'>&nbsp</span>";
			html+="<span class='span-shows3' onclick='spanClick(this,2)'>&nbsp</span>";
			html+="</td>";
			html+="</div>";
			index++; 
		} 
		for (var i = 0; i < 7; i++) { 
			if (index % 7 == 0) { 
				break; 
			} 
			html += "<td> </td>"; index++; 
		} 
		html += "</tr>"; 
		if(days < 30) html += "<tr class='even'><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"; 
		html += "</table>"; 
		i=0;
		table+=html;
    }
    $('#xmhx').html(table);
} 
//获取当月的阳历天数 
function getNowMonthDays(year, month) { 
	var isy = false; 
	if (year % 400 == 0 || (year % 4 == 0 && year % 100 != 0)) isy = true; 
	switch (month) { 
		case 1: 
		case 3: 
		case 5: 
		case 7: 
		case 8: 
		case 10: 
		case 12: 
			return 31; 
		case 4: 
		case 6: 
		case 9: 
		case 11: 
			return 30; 
		case 2: 
			return isy ? 28 : 29; 
	} 
} 

/*弹出选择框*/
function showDates(_this,value){
   if(value !=''){
	   $('.td-divs').hide();
	   $(_this).find('.td-divs').show();
   }	   
}

/*选择框付值*/
function spanClick(_this,num){
    //event.stopPropagation();
	if (event.stopPropagation) { 
	      // this code is for Mozilla and Opera 
	      event.stopPropagation(); 
	}else if (window.event) { 
	     // this code is for IE 
	      window.event.cancelBubble = true; 
	}	
	var tdDivs=$(_this).parent().parent();
	$(tdDivs).attr('class','states'+num);
	$(tdDivs).find('input[name="states[]"]').val(num);
	$(_this).parent().hide();
}
