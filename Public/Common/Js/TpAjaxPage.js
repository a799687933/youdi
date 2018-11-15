/**
*@基于ThinkPHP分页类固定分页URL处理的AJAX碎片请求
*/

/*处理碎片文件的分页URL
*showPageNode  显示列表内容节点
*aClassArr  array  有URL的a标签class名称，数组
*使用 setClicks('#list',['.a_show','.a_page']);
*/
function setClicks(showPageNode,aClassArr){
	$(function(){
	   for(var i in aClassArr){ 
			var aShow=$(showPageNode).find(aClassArr[i]);
			for(var j=0;j < aShow.length;j++){
				$(aShow).eq(j).attr('onclick',"ajaxPage('"+aShow.eq(j).attr('href')+"','"+showPageNode+"')").attr('href',"jsavscript:void(0)");
			}
	   }	
	});
}

/*AJAX请求
*url  请求URL
*showPageNode 显示列表内容节点
*ajaxPage('http://www.baidu.com?p=2','#list');
*/
function ajaxPage(url,showPageNode){
  $.ajax({
     type:'post',
     url:url,
     dataType:'TEXT',
     success:function(response,status,xhr){
         $(showPageNode).html(response);
      }
   });  
}