/**
*切换
*/
function setTab(m,n){
	var tli=document.getElementById("menu"+m).getElementsByTagName("li");
	var mli=document.getElementById("main"+m).getElementsByTagName("blockquote");
	for(i=0;i<tli.length;i++){
		tli[i].className=i==n?"hover":"";
		mli[i].style.display=i==n?"block":"none";
	}
}

//动态设置ID
function setMbileAttr(_this,tokenName,inutName,url){
	$(function(){
	  var fieldId=inutName+'__'+$(_this).val()+'__'+tokenName+'__'+$('input[name="'+tokenName+'"]').val();	
	  $('input[name="'+inutName+'"]').attr('id',fieldId);
	  if(url){
		  $.ajax({
			 type:'post',
			 url:url+'?fieldId='+fieldId,
			 success:function(response,status,xhr){
				 showInfo(response.status,'#'+fieldId,response.info);
				 hideInfo();
			  }
		   });				  
	   }
	});
}