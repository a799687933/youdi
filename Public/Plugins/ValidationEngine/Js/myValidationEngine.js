/**
*动态生成AJAX请求方法数据库验证
*action               AJAX调用的方法名称validate[requiredajax[action]]
*url                     请求URL
*extraData         URL后加的参数a=b
*alertText           返回false的提示信息
*alertTextOk      成功返回时的提示信息
*alertTextLoad  请求加载时的信息
*callBack           回调函数
* 注：input  控件ID值就是数据表字段名时自动验证
*/
function myValidationEngine(action,url,extraData,alertText,alertTextOk,alertTextLoad,callBack){
	if(!arguments[6]) callBack = "";
	var data='{"url":"'+url+'","extraData":"'+extraData+'","alertText":"'+alertText+'","alertTextOk":"'+alertTextOk+'","alertTextLoad":"'+alertTextLoad+'","callBack":"'+callBack+'"}';
	var dataObj=eval("("+data+")");
	$.validationEngineLanguage.allRules[action]=dataObj;	
}

var myPotions={};
/*配置验证*/
function startover(){
		jQuery(myPotions.form).validationEngine("attach",{ 
			promptPosition:myPotions.position,  
			scroll:false ,
			maxErrorsPerField: 1, 
			showOneMessage: myPotions.showOneMessage, //是否只显示一个提示运
			ajaxFormValidationMethod: myPotions.type,
			autoHidePrompt: true, 
			autoHideDelay: 5000,
			ajaxFormValidation:myPotions.ajaxToForm,	
			success :  false,
            onAjaxFormComplete:successCallBack
			/*onFieldSuccess:function(node){
			      alert(0);
			}*/
	  });
}

/*表单提交请求完成后回调*/
function successCallBack(status, form, json, options){
	var jsarr=eval(json);
	var url='';
	if(jsarr[0][3]){
		url=jsarr[0][3];
	}else{
		url=myPotions.url;
	}
    if(status === true){
		if(url){
			setTimeout(function(){
				  window.location.href=url;
			 },2000);		
		}
	}else{
		if(url){
			setTimeout(function(){
				  window.location.href=url;
			 },2000);		
		}
	}
}

/**
*启动
*formName      验证表单节点的Class或ID
*type                 提交类型；GET或POST
*url                    提交返回时默认跳转的URL
*ajaxToForm   表单是否使用ALAX提交
*position          提示信息位置；topRight 头右显示；topLeft 头左显示；默认(底左) ；
* showOneMessage  是否只显示一个提示层
*/
function valinit(formName,type,url,ajaxToForm,position,showOneMessage){
	    if(!arguments[4]) position = "bottomLeft";
	    if(!arguments[5]) showOneMessage = false;
		myPotions.form=formName;
		myPotions.type=type;
		myPotions.url=url;
		myPotions.ajaxToForm=ajaxToForm;
		myPotions.position=position;
		myPotions.showOneMessage=showOneMessage;
		startover();
}