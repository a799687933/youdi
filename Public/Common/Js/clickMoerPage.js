/**
*加载更多分页
*_this   本节点
*pageCount  总页数
*url   请求地址
*addNode   增加内容节点
<input value="Load More" type="button" onClick="clickMoerPage(this,'{$pageNumShown},{:U(is_m().'/'.$modelAction,'','')}?{:C('VAR_PAGE')}={0}','#moer-page')">
*/
var page=2;
function clickMoerPage(_this,pageCount,url,addNode){
    if(page > pageCount){
		$(_this).parent('a').hide();
		$(_this).val('Loading Completed');
		return;
	}else if(page == pageCount){
		$(_this).parent('a').hide();
	}
	url=url.replace('{0}',page);
	$.ajax({
		 type:'get',
		 dataType: "text",
		 url:url,
		 success:function(response,status,xhr){
			 page++;
			 $(addNode).append(response);
		  },
		  complete:function(){
			   $(_this).val('Load More').attr('disabled',false);
		  },
		   beforeSend:function(){
			  $(_this).val('Loading...').attr('disabled',true);
		  },         
		 error:function(xhr,errorText,errorType){
			 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
		 }
	});    	
}

var page2=2;
function clickMoerPage2(_this,pageCount,url,addNode){
    if(page2 > pageCount){
		$(_this).parent('a').hide();
		$(_this).val('Loading Completed');
		return;
	}else if(page2 == pageCount){
		$(_this).parent('a').hide();
	}
	url=url.replace('{0}',page2);
	$.ajax({
		 type:'get',
		 dataType: "text",
		 url:url,
		 success:function(response,status,xhr){
			 page2++;
			 $(addNode).append(response);
		  },
		  complete:function(){
			   $(_this).val('Load More').attr('disabled',false);
		  },
		   beforeSend:function(){
			  $(_this).val('Loading...').attr('disabled',true);
		  },         
		 error:function(xhr,errorText,errorType){
			 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
		 }
	});    	
}
