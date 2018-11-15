
/*
修改数据库单一内容
onclick=edit(this,"{:U('Admin/Rbac/updateNode','','')}","/id/{$app.id}/name/")
id 是查找条件，主键
name为变量就是表字段名称，根据需要传值
*/
function edit(obj,url,content){
	 window.deitVal=false;
	 var tag = obj.firstChild.tagName;
     var org = obj.innerHTML;
	 var val =  obj.innerText || obj.textContent;
	  if (typeof(tag) != "undefined" && tag.toLowerCase() == "textarea"){
          return;
     }
	 /* 创建一个输入框 */
	var txt = document.createElement("textarea");
	 txt.value = (val == 'N/A') ? '' : val;
	 txt.style.width = (obj.offsetWidth + 20) + "px" ;
	 txt.style.height = (obj.offsetHeight * 1.5) + "px" ;
	// txt.style.resize ='none';
	 /* 隐藏对象中的内容，并将输入框加入到对象中 */
     obj.innerHTML = "";
    obj.appendChild(txt);
    txt.focus();
	/* 编辑区输入事件处理函数 */
	 txt.onkeypress = function(e){
		 if(e.keyCode=='13') {
			 return false;
		 }
	};
     /* 编辑区失去焦点的处理函数 */
	txt.onblur=function(){
		if(txt.value=='') {
		  obj.innerHTML = org;	
		   return;
		}
		editAjax(url+content+txt.value);
		if(deitVal){
			deitVal=false;
			obj.innerHTML = txt.value;
		}else{
			obj.innerHTML = org;	
		}
	};
}
function editAjax(urlContent){
   $.ajax({
		 type:'get',
		 url:encodeURI(urlContent),
		 async: false,//同步提交
		 dataType:'TEXT',
		// data:{id:ids,name:value},
		 success:function(response,status,xhr){
		   if(response==1){
			   deitVal=true;
		   }
		 }
	});
}
//////////////////////////////////////////////////////////
/*
修改数据库单一内容,切换状态改数据库0或1
onclick=cutover(this,"{:U('Admin/Rbac/updateNode','','')}","/id/{$app.id}/sort/")
id 是查找条件，主键
name为变量就是表字段名称，根据需要传值
*/
function cutover(obj, url,paert){
  var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;
   var contentUrl=url+paert+val;
   var imgPath=obj.src.substring(0,obj.src.lastIndexOf('/'));
   $.ajax({
		 type:'get',
		 url:encodeURI(contentUrl),
		 async: false,//同步提交
		 dataType:'TEXT',
		// data:{id:ids,name:value},
		 success:function(response,status,xhr){
		    if(response==1){
			    obj.src =imgPath+'/yes.gif';
		    }else if(response==0){
				obj.src = imgPath+'/no.gif';
		    }
		 }
	});
}
