/*最后更新时间 2016-11-1*/
/*获得当前文件路径*/
var js=document.scripts;
var jsPath;
for(var i=js.length;i>0;i--){
 if(js[i-1].src.indexOf("formValidation.js")>-1){
   jsPath=js[i-1].src.substring(0,js[i-1].src.lastIndexOf("/")+1);
 }
}
var imgPath=jsPath;
var position=1;
//标记其它验证是否成功，如果有值则其它验证不成功，不可提交
var ajaxChecks='';
//清除计时器
var clearTime=0,clearTime2=0;

/**启动初始化																					 
*obj    表单对像(form)节点 class或ID
*isAjax  是否AJAX提交 
*showAll  是否显示全部错误
*msgType 提示类型 0浏览器默认对话框；1节点提示层
*pos   0头显示消息，1底部显示消息
*isBlur       是否使用光标焦点 ，默认不使用
*/
function formInit(obj,isAjax,showAll,msgType,pos,isBlur){
	 if(!arguments[5]) isBlur = false;
	//动态邦定
	$(obj).on('click','input[type="submit"],input[type="Submit"],input[type="SUBMIT"]',function(e){
	    formValidation($(obj),isAjax,showAll,msgType,pos,e);
	});
	/*if(isBlur) onBlurInipts(obj);
	else onBlurInipts('');*/
}
/**表单验证 (required必填）、(email电子邮件)、(digits整数）、(number浮点数)、(alphanumeric字母、数字、下划线)、(url网址)、(equal-input 两个相等值)、(min最小值，max最大值)、
*(letter只能输入字母) 																							 
*obj    表单对像
*isAjax  是否AJAX提交 
*showAll  是否显示全部错误
*msgType 提示类型 0浏览器默认对话框；1节点提示层
*pos   0头显示消息，1底部显示消息
*e       阻止默认值对像
*<input type="text" name="full_name" class="required" info-text="user-text" error-info="请输入您的姓名" error-other="姓名在5到15个字符内"  min="5" max="15"/>
*<p class="user-text">这里是提示错误信息的节点</p>
*/
function formValidation(obj,isAjax,showAll,msgType,pos,e){
	 if(!arguments[1]) isAjax = false;
	 if(!arguments[2]) showAll = false;
	 if(!arguments[3]) msgType = 0;
	 if(!arguments[4]) pos = 0;
	 if(!arguments[5]) pos = '';
	 if(pos) position=1;
	 var form=$(obj);
	 var action=form.attr('action');
	 var method=form.attr('method');		
	 var submit=form.find('input[type="submit"]');
	 var submitVal=submit.val();
	 var submitText=submit.attr('info-text');
	 var errorString='';
	 var errorArr=new Array();
	 var formFind=form.find('input[type="text"],input[type="password"],textarea,select');	 
	  for(var i=0;i < formFind.length;i++){
		  var tempArr=new Array();
		   var textClass= formFind.eq(i).attr('class');
		   textClass=textClass ? textClass.toUpperCase() : '';
		   var values=formFind.eq(i).val();		
		   values=values.replace(/^(\s|\u00A0)+/,'').replace(/(\s|\u00A0)+$/,''); 
		   var errorMsg=formFind.eq(i).attr('error-info');		 //必填项错误提示
		   var errorOther=formFind.eq(i).attr('error-other'); //非必填项错误提示		 
		   var reqinfo=''; //强制显示
		   var infoNode=formFind.eq(i).attr('info-text');		
		   //最少字符
		   var min=formFind.eq(i).attr('min');	
		   min=!isNaN(min) ? parseInt(min) : 0;
		   //最多字符
		   var max=formFind.eq(i).attr('max');	
		   max=!isNaN(max) ? parseInt(max) : 0;
		   //两个值相等
		   var equalId=formFind.eq(i).attr('equal-input');
		   
		   //必填项
		   if(textClass.indexOf('REQUIRED') > -1){
			   if(values==''){
					   var errorInfo='';//errorMsg ? errorMsg : '必填项';
					   reqinfo='';//errorInfo;
					   if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;
						   errorString+=errorInfo+"\n\n";
						   error(errorArr[i]);
						   if(e) e.preventDefault();
					   }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				 }           	   
		   }	
		 //相等input值
		 if(equalId){
			 if(values !=$(equalId).val()){ 
				   var errorInfo='';//errorOther ? errorOther : errorMsg;
				   errorInfo='';//reqinfo ? reqinfo : errorInfo;
				   if(showAll){
					   tempArr[0]= formFind.eq(i);
					   tempArr[1]=  formFind.eq(i).width();
					   tempArr[2]=  formFind.eq(i).height();
					   tempArr[3]=  errorInfo;
					   tempArr[4]=  infoNode;
					   errorArr[i]= tempArr;
					   errorString+=errorInfo+"\n\n";
				   }else{
					   alert(errorInfo);
					   formFind.eq(i).focus();
					   if(e) e.preventDefault();
					   return false;						   
				   }
			 }
		 }		   
		   //最少字符和最多字符
		   if((min > 0 || max > 0) && values){
			   if(min > 0){
			       if(values.length < min){
					   var errorInfo='';//errorOther ? errorOther : errorMsg;
					   errorInfo='';//reqinfo ? reqinfo : errorInfo;
					   if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;
						   errorString+=errorInfo+"\n\n";
					   }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }					   
				   }
			   }
			   if(max > 0){
			       if(values.length > max){
					   var errorInfo='';//errorOther ? errorOther : errorMsg;
					   errorInfo='';//reqinfo ? reqinfo : errorInfo;
					   if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;
						   errorString+=errorInfo+"\n\n";
					   }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }					   
				   }				   
			   }
		   }
		   
		 //其它综合验证
		 if(values){
			 var errorInfo='';//errorOther ? errorOther : errorMsg;
			 errorInfo='';//reqinfo ? reqinfo : errorInfo;
			 //电子邮件email
			 if(textClass.indexOf('EMAIL') > -1){
				 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
				  if(!myreg.test(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				 }					 
			}
			//整数digits
		   if(textClass.indexOf('DIGITS') > -1){
				var reg = /^\d+$/;
				 if(!reg.test(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				 }					
		   }
		   //浮点数 number
		   if(textClass.indexOf('NUMBER') > -1){
			   if(isNaN(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
			   }
		   }
		   //字母、数字、下划线 alphanumeric 
		   if(textClass.indexOf('ALPHANUMERIC') > -1){
				 var reg = /^[A-Za-z0-9\-\_]+$/; 
				 if(!reg.test(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				 }					
		   }
		   //只能输入字母 letter LETTER
		   if(textClass.indexOf('LETTER') > -1){
				 var reg = /^[A-Za-z]+$/; 
				 if(!reg.test(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				 }					
		   }		   
		   //网址url
		   if(textClass.indexOf('URL') > -1){
				var urlExp=/http:\/\/.+/; 
				if(!urlExp.test(values)){
					    if(showAll){
						   tempArr[0]= formFind.eq(i);
						   tempArr[1]=  formFind.eq(i).width();
						   tempArr[2]=  formFind.eq(i).height();
						   tempArr[3]=  errorInfo;
						   tempArr[4]=  infoNode;
						   errorArr[i]= tempArr;							
						   errorString+=errorInfo+"\n\n";
					    }else{
						   alert(errorInfo);
						   formFind.eq(i).focus();
						   if(e) e.preventDefault();
						   return false;						   
					   }
				}
		   }
		}		   
	  }
	  if(errorString){
		  if(msgType) {
			  //全部提示错误信息
			  var f=0;
			   for(var j in errorArr) {
				   if(f==0) errorArr[j][0].focus();
				   error(errorArr[j]);
				   f++;
			   }

			   celarOut();
			   clearTime=setTimeout(function(){
					 for(var t in errorArr) $('.'+errorArr[t][4]).hide();
			  },3000);			   
		  }else{
			  alert(errorString);
		 }
		 if(e) e.preventDefault();
		  return false;
	  }
	  //附加验证未成功
	  if(ajaxChecks){
		  if(msgType) error(ajaxChecks,'brown');
		  else alert(ajaxChecks[3]);
		  if(e) e.preventDefault();
		  return false;
	  }
	  //非AJAX提交
	  if(!isAjax) {
		  return true;
	  }else{ 
	      if(e) e.preventDefault();
	  }
	 //AJAX提交表单
	 $.ajax({
			 type:method,
			 dataType: "json",
			 url:action,
			 data:form.serialize(),
			 success:function(response,status,xhr){
				 //后台指显示提示节点 class 名称
				if(response[0][1]){
					if(response[0][2]) {
						if(msgType){
							submitText=response[0][0] ? response[0][0] : submitText;
							sendReturn(submitText,true,response[0][2],response[0][3],response[0][4]);
						}else{
						    alert(response[0][2]);
							if(response[0][4]){
								eval(response[0][4]);
							}else{
								if(response[0][3]) window.location.href=response[0][3];
							}
						}
					}else{
						if(response[0][4]){
							eval(response[0][4]);
						}else{
							if(response[0][3]) window.location.href=response[0][3];
						}
					}
				}else{
					if(msgType){
						var hideClass=response[0][0] ? response[0][0] : submitText;
						$('.'+submitText).hide();
						if(response[0][2]) {
							sendReturn(hideClass,false,response[0][2],response[0][3],response[0][4]);
						}else{
							if(response[0][4]){
								eval(response[0][4]);
							}else{
								if(response[0][3]) window.location.href=response[0][3];
							}
						}
					}else{
						if(response[0][4]){
						    eval(response[0][4]);
						}else{
							if(response[0][2]) alert(response[0][2]);
							if(response[0][3]) window.location.href=response[0][3];						
						}
					}
					if(msgType==0){
						submit.attr('disabled',false).val(submitVal);
					}else{
						submit.attr('disabled',false);
					}
				}
			  },
			  beforeSend:function(){
				 var qt= submit.attr('load') ? submit.attr('load') : 'Loading...';
				 if(msgType==0){
					 submit.attr('disabled',true).val(qt);
				 }else{
					 submit.attr('disabled',true);
					// loads(submitText,qt);					 
				 }
			 }
	   });	
	  return false;	 
}

/**光标焦点
*<input type="text" name="full_name" class="required" info-text="user-text" error-info="请输入您的姓名" error-other="姓名在5到15个字符内"  min="5" max="15"/>
*<p class="user-text">这里是提示错误信息的节点</p>
*/
function onBlurInipts(obj){
	if(!obj) return;
	blurNode='input[type="text"],input[type="password"],textarea,select,';
	blurNode+='input[type="Text"],input[type="Password"],Textarea,Select,';
	blurNode+='input[type="TEXT"],input[type="PASSWORD"],TEXTAREA,SELECT';
	//动态邦定
	$(obj).on('blur',blurNode,function(){																		 
			 var _this=$(this);
			 var values=_this.val().replace(/^(\s|\u00A0)+/,'').replace(/(\s|\u00A0)+$/,''); 
			 var class_name=_this.attr('class');
			 class_name=class_name ? class_name.toUpperCase() : '';
			 var infoText=_this.attr('info-text');
			 //必填项错误提示
			 var errorInfo=_this.attr('error-info'); 
			  //非必填项错误提示
			 var errorOther=_this.attr('error-other');
			 var min=parseInt(_this.attr('min'));
			 var max=parseInt(_this.attr('max'));
			  //两个值相等
			 var equalId=_this.attr('equal-input');
			 //必填
			 if(class_name.indexOf('REQUIRED') > -1){
				 if(values==''){
					 focusError(infoText,errorInfo);
					 return;
				 }else{
					 $('.'+infoText).html('').hide();
				 }
			 }
			 
			 //相等input值
			 if(equalId){
				 errorInfo=errorOther ? errorOther :  errorInfo;
				 if(values !=$(equalId).val()){
					 focusError(infoText,errorInfo);
					 return;				 
				 }else{
					 $('.'+infoText).html('').hide();
				 }
			 }
			
		   //最少字符和最多字符
		   if(min > 0 || max > 0){ 
			   errorInfo=errorOther ? errorOther :  errorInfo;
			   if(min > 0){
				   if(values.length < min){
						 focusError(infoText,errorInfo);
						 return;				   
				   }else{
						 $('.'+infoText).html('').hide();
				   }
			   }
			   if(max > 0){
				   if(values.length > max){
						 focusError(infoText,errorInfo);
						 return;						
				   }else{
						 $('.'+infoText).html('').hide();
				   }				   
			   }
		   }
		   
		 //其它综合验证
		 if(values){
			 errorInfo=errorOther ? errorOther :  errorInfo;
			 //电子邮件email
			 if(class_name.indexOf('EMAIL') > -1){
				 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
				  if(!myreg.test(values)){
					   focusError(infoText,errorInfo);
					   return;						
				 }else{
					  $('.'+infoText).html('').hide();
				 }					 
			}
			//整数digits
		   if(class_name.indexOf('DIGITS') > -1){
				var reg = /^\d+$/;
				 if(!reg.test(values)){
					 focusError(infoText,errorInfo);
					 return;	
				 }else{
					 $('.'+infoText).html('').hide();
				}					
		   }
		   //浮点数 number
		   if(class_name.indexOf('NUMBER') > -1){
			   if(isNaN(values)){
					 focusError(infoText,errorInfo);
					 return;	
			   }else{
					$('.'+infoText).html('').hide();
			   }
		   }
		   //字母、数字、下划线 alphanumeric 
		   if(class_name.indexOf('ALPHANUMERIC') > -1){
				 var reg = /^[A-Za-z0-9\-\_]+$/; 
				 if(!reg.test(values)){
					 focusError(infoText,errorInfo);
					 return;	
				 }else{
					 $('.'+infoText).html('').hide();
				 }					
		   }
		   if(class_name.indexOf('LETTER') > -1){
				 var reg = /^[A-Za-z]+$/; 
				 if(!reg.test(values)){
					 focusError(infoText,errorInfo);
					 return;	
				 }else{
					 $('.'+infoText).html('').hide();
				 }					
		   }		   
		   //网址url
		   if(class_name.indexOf('URL') > -1){
				var urlExp=/http:\/\/.+/; 
				if(!urlExp.test(values)){
					 focusError(infoText,errorInfo);
					 return;					
				}else{
					 $('.'+infoText).html('').hide();
				}
		   }
		}								
	});
}

/*点击提交时，生成错误节点*/
function error(arr){
	$('.'+arr[4]).html(arr[3]).show();
}

/*光标失去焦点时，生成错误节点*/
function focusError(infoText,errorInfo){
	 if(!$('.'+infoText).css('display').toUpperCase()=='NONE') return;
    $('.'+infoText).html(errorInfo).show();
	//clearTime2=setTimeout(function(){
	//    $('.'+infoText).html('').hide();
	//},3000);
}

/*清除计时器*/
function celarOut(){
   clearTimeout(clearTime);
   clearTimeout(clearTime2);
}

/*提交成功或失败显示*/
function sendReturn(obj,success,msg,url,fn){
	  if(success) $('.'+obj).html(msg).css('color','green').css('text-left','center').show();
	  else $('.'+obj).html(msg).show().css('text-left','center');
	  setTimeout(function(){
			$('.'+obj).hide();
			if(fn){
				eval(fn);
			}else{
				if(url) window.location.href=url;
			}
	  },3000);	
}

/*加载图片*/
function loads(obj,loadText,align){
	var str='<p style="color:#666 !important;text-align:left;"><img src="'+imgPath+'/load.gif" style="position:relative;top:5px;right:3px;" />'+loadText+'</p>';
    $('.'+obj).html(str).show();
}

/*
*检测数据库字段
*_this   this
*msgType 提示类型 0浏览器默认对话框；1节点提示层
*pos   0头显示消息，1底部显示消息
*type  验证类型；0验证不为空，1 验证电子邮件,2验证整数
*
*input 节点直接使用
*<input type="text" onBlur="ajaxCheck(this,1,1,1)" url="http://www.baidu.com/checkUser" check-to="必填项" check-error="此用户已被其它人占用" check-succ="此用户可注册" />
*a 标签指向input 节点
*<input type="text" name="user_name" url="http://www.baidu.com/checkUser?user_name=" check-to="必填项" check-error="此用户已被其它人占用" check-succ="此用户可注册"/>
*<a href="javascript:void(0);" onclick="ajaxCheck($('input[name=\'user_name\']'),1,1,1)" />
*/
function ajaxCheck(_this,msgType,pos,type){	
   if(!arguments[1]) msgType = 0;
   if(!arguments[2]) pos = 0;
   if(!arguments[3]) type = 0;
   if(pos) position=1;
   var values=$(_this).val();	
  // values=values.replace(/^(\s|\u00A0)+/,'').replace(/(\s|\u00A0)+$/,''); 
   var url=$(_this).attr('url');
   //未请求前验证提示
   var checkTo=$(_this).attr('check-to') ? $(_this).attr('check-to') : '必填'; 
   //请求返回错误提示
   var checkError=$(_this).attr('check-error') ? $(_this).attr('check-error') : ''; 
   //请求返回成功提示
   var checkSucc=$(_this).attr('check-succ') ? $(_this).attr('check-succ') : ''; 
   var tempArr=new Array();
   tempArr[0]= $(_this);
   tempArr[1]= $(_this).width();
   tempArr[2]= $(_this).height();
   tempArr[3]= checkTo;     
   if(!values) {
	    if(msgType==0){
			alert(checkTo);
		}else{
			error(tempArr,'brown');
		}
		ajaxChecks=tempArr;
	   return;
   }
   if(type==1){ //验证电子邮件
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if(!myreg.test(values)){
			error(tempArr,'brown');
			ajaxChecks=tempArr;
			return;
		}
   }else if(type==2){ //验证整数(手机号)
		var reg = /^\d+$/;
	    if(!reg.test(values)){   
		    error(tempArr,'brown');
			ajaxChecks=tempArr;
			return;
		}
   }
   //AJAX提交表单
   $.ajax({
		 async :false,
		 type:'get',
		 dataType: "json",
		 url:url+values,
		 success:function(response,status,xhr){
			if(msgType==0){
				if(response[1]) {
					    ajaxChecks=false;
					    alert(checkSucc);
					}else {
						tempArr[3]=checkError;
						ajaxChecks=tempArr;
						alert(checkError);	
					}				
			}else{
				if(response[1]) {
					sendReturn($(_this),true,checkSucc,'',true);
					ajaxChecks=false;
				}else{
					tempArr[3]=checkError;
					ajaxChecks=tempArr;
					sendReturn($(_this),false,checkError,'',true);
				}				
			} 
		  },
		  beforeSend:function(){
			  if(msgType==0){
			  }else{
				 if($('.form-validation-text-infos').length > 0) $('.form-validation-text-infos').remove();
				 loads($(_this),'Being Loaded...',true);				  
			  }
		 }
	 });	   
}

/**
*发送手机号码
*<input type="text" name="mobile" check-to="手机号格式错误" check-error="此手机号被其它人占用" check-succ="此手机号可注册" url="json.html?mobile=">
*<input type="button" value="免费获取验证码" class="idbtn" onclick="sendMobile($('input[name=\'mobile\']'),this)"/>
**/
function sendMobile($mobileInput,_this,msgType,pos,url){
      if(!arguments[0]) msgType = 0;
      if(!arguments[1]) pos = 0;	
      ajaxCheck($mobileInput,msgType,pos,2);
	 // return;
	  //验证是否通过
	  if(ajaxChecks) return;
	  //控制按钮不可点
	  countdown(_this);
	  //发送手机号
     //AJAX提交表单
     $.ajax({
		 async :false,
		 type:'get',
		 dataType: "json",
		 url:url+$mobileInput.val(),
		 success:function(response,status,xhr){
			if(msgType==0){
				if(response[1]) {
					    ajaxChecks=false;
					    alert(checkSucc);
					}else {
						tempArr[3]=checkError;
						ajaxChecks=tempArr;
						alert(checkError);	
					}				
			}else{
				if(response[1]) {
					sendReturn($(_this),true,checkSucc,'',true);
					ajaxChecks=false;
				}else{
					tempArr[3]=checkError;
					ajaxChecks=tempArr;
					sendReturn($(_this),false,checkError,'',true);
				}				
			} 
		  },
		  beforeSend:function(){
			  if(msgType==0){
			  }else{
				 if($('.form-validation-text-infos').length > 0) $('.form-validation-text-infos').remove();
				 loads($($mobileInput),'Being Loaded...',true);				  
			  }
		 }
	  });	   	  
}

/**
*倒计时
*/
var ponecodesend=180; //隔180秒重发
var wait=180;  //时间初始值
function countdown(_this){
	    _this=$(_this);
		if (wait == 0) {
			_this.attr("disabled",false);          
			_this.val("获取验证码");
			wait = ponecodesend;
		} else {
			_this.attr("disabled", true);
			_this.val("重新发送(" +wait + ")");
			wait--;
			setTimeout(function() {
				countdown(_this);
			},1000);   
		}  
}

/**找回密码 发送手机验证码/邮箱验证码公共
*发送邮件
*_this   this
*url  发送地址
*msgInputNode  发送提示节点
*/
function sendForgetMsg(_this,url,msgInputNode){
   msgInputNode=$(msgInputNode);	
   countdown(_this);
    loads(msgInputNode,'Load in...',1); 
   //AJAX提交
   $.ajax({
		 async :false,
		 type:'get',
		 dataType: "json",
		 url:url,
		 success:function(response,status,xhr){
		   sendReturn(msgInputNode,response[0][1],response[0][2],'',1);
		 }
   });	
}


