/**
* copyNode('事件添加的类名称','事件删除节点的类名称')
*
**/
function copyNode(no,del){
	$(document).ready(function() {		   
		$("."+no).click(function() {  // no复制节点
			var divTest = $(this).parent();  //获取div
			var newDiv = divTest.clone(true);
			divTest.after(newDiv);
			});
		$("."+del).click(function() { // del为删除节点
			if($("."+del).length>1){						 
				 $(this).parent().remove()();
			}
		});
	});   
}

/**
 *returnCallAjaxDo()
 *  teturn json   
 * }
*/
function returnCallAjaxDo(toUrl,returnType){
      $.ajax({
         async: false,//同步提交 
         type:'post',
         dataType: returnType,
         url:toUrl,
         //data:$('#'+formId).serialize(),
         success:function(response,status,xhr){
            data=response;//把反回结果return 出去
          },
         error:function(xhr,errorText,errorType){
             alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
          }
       });                        
 }
 
/**
 * 
 *处理AJAX返回的数据return 出去
 *returnCallAjaxDo(date);
 *
 */
function callAjaxDo(toUrl,returnType){
    window.data='';
     returnCallAjaxDo(toUrl,returnType);
     return data;
}

/**
 *js动态显示年月日星期时分秒 
 */
function showDT() {  
  var days=new  Array ("日", "一", "二", "三", "四", "五", "六");  
  var currentDT = new Date();  
  var y,m,date,day,hs,ms,ss,theDateStr;  
  y = currentDT.getFullYear(); //四位整数表示的年份  
  m = currentDT.getMonth()+1; //月  
  date = currentDT.getDate(); //日  
  day = currentDT.getDay(); //星期  
  hs = currentDT.getHours(); //时  
  if(hs<10){hs='0'+hs;}
  ms = currentDT.getMinutes(); //分  
  if(ms<10){ms='0'+ms;}
  ss = currentDT.getSeconds(); //秒  
  if(ss<10){ss='0'+ss;}
  if(days[day]=='日'){
      sunday='<span style="color:red;">星期'+days[day]+'</span>';
  }else{
      sunday='<span>星期'+days[day]+'</span>';
  }
  y='<span style="font-family:Tahoma;">'+y+'</span>';
  m='<span style="font-family:Tahoma;">'+m+'</span>';
  date='<span style="font-family:Tahoma;">'+date+'</span>';
  sunday='<span style="font-family:Tahoma;">'+sunday+'</span>';
  hs='<span style="font-family:Tahoma;">'+hs+'</span>';
  ms='<span style="font-family:Tahoma;">'+ms+'</span>';
  ss='<span style="font-family:Tahoma;">'+ss+'</span>';
  theDateStr = y+"年"+  m +"月"+date+"日 （"+sunday+"） "+hs+":"+ms+":"+ss;  
  document.getElementById('theClock'). innerHTML =theDateStr;  
  window.setTimeout( showDT, 1000);  
}  
//后台首页调用
$(function(){
    showDT();
});

/**
 *提示对话框 确认（是/否） 
 * url  请求URL
 * data 如果loca参数则location直接跳转，如果ajax参数则用ajax请求
 */
function testConfirmMsg(url, data,mgs){
    alertMsg.confirm(mgs, {
        okCall: function(){
            if(data=='loca'){
                window.location.href=url;
            }else if(data=='ajax'){
                $.post(url,data, callbackMsg, "json");
            }       
        }
    });
}

/**
*提示对话框回调（是/否） 
*/
function callbackMsg(json){
      DWZ.ajaxDone(json);
      if (json.statusCode == DWZ.statusCode.ok){
            if (json.navTabId){
                  navTab.reload(json.forwardUrl, {}, json.navTabId);
            }
      }
}

//倒计时
(function($) {
    $.extend({
        promoClock: function(class_id, end_time, now_time) {
            var leave = end_time - now_time;
            var last_time = '';
            if (leave <= 0) {
                var last_time = "抢购已结束";
            } else {
                var hour = Math.floor(leave / 3600);
                var minute = Math.floor(leave / 60) - (hour * 60);
                var second = leave - (hour * 3600) - (minute * 60);
                hour = hour < 10 ? "0" + hour : hour;
                minute = minute < 10 ? "0" + minute : minute;
                second = second < 10 ? "0" + second : second;
                last_time = "<span>剩余：</span><em style='color:red;'>" + hour + "</em><span> 时</span><em style='color:red;'>" + minute + "</em><span> 分</span><em style='color:red;'>" + second + "</em><span> 秒</span>";
                $('#' + class_id).html(last_time);
                end_time--;
                setTimeout("$.promoClock('" + class_id + "', '" + end_time + "', '" + now_time + "')", 1000);
            }
        }
    });
})(jQuery);

/**
 * 调用倒计时
 * timited(id,end_time,now_tme)
 * @param {Object} id  
 * @param {Object} end_time
 * @param {Object} now_tme
 */
function timited(id,end_time,now_tme){
$(function(){
    $.promoClock(id,end_time, now_tme);
});
}

/*获取下级城市*/
function getPlace(_ths,node){
   if(!arguments[1]) node = "";	
   var urls=$(_ths).attr('to-url');
    if($(_ths).val() > 0){
      $.ajax({
         type:'post',
         dataType: "json",
         url:urls+"/region_id/"+$(_ths).val()+'/city/ajax',
         success:function(response,status,xhr){
            var str='<option value="">请选择</option>';
            for(var i in response){
                str+='<option value="'+ response[i].region_id +'">'+ response[i].region_name +'</option>';
            }
            if($(_ths).next('select').length >0){
                $(_ths).next().html(str);
            }    
			if(node){
			      var htmlString=$(_ths).children('option:selected').text();
				  $(node).val(htmlString);			
			}
          }
       });    
   }                              
}  

