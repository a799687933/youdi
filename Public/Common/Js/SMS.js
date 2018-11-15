/**
*@发手机短信息
*@window.SMS=new SMS();
*@SMS.inputClick='#sendBottom'; //发短信息按钮input节点
*@SMS.mobileVal='#mobile'; //手机号码input节点
*@SMS.mobileErrorNode='';  //手机号码错误提示节点
*@SMS.PONECODESEND='{:C("PONECODESEND")}'; //发信息相隔时间
*@SMS.wait='{:C("PONECODESEND")}'; //时间初始值
*@SMS.TOKEN="{:C('TOKEN_NAME')}"; //动态令牌名称
*@SMS.url="{:U('Common/a')}"; //AJAX提交URL
*@SMS.smClick(); //运行
*/
function SMS(){
    this.inputClick=''; //发短信息input节点ID节点带#号
    this.mobileVal=''; //手机号码input节点ID节点名称不带#号
    this.mobileErrorNode=''; //手机号码错误提示节点
    this.PONECODESEND=''; //发信息相隔时间
    this.wait=0;  //时间初始值
    this.TOKEN=''; //动态令牌
    this.returnNode=''; //显示消信息节点
    this.url=''; //ajax提交URL
    this.type=''; //如果为true验证手机是否存在，false验证手机是否重复
    this.info=''; //提示信息
    this.infoPositionTop=108; //提示层头距离
    this.infoPositionLeft=105; //提示层左距离 
    var _this=this;
    this.times=function(){
        if (parseInt(this.wait) == 0) {
            $(this.inputClick).attr("disabled",false);          
            $(this.inputClick).val("免费获取验证码");
            this.wait = this.PONECODESEND;
        } else {    
            $(this.inputClick).attr("disabled", true);
            $(this.inputClick).val("重新发送(" + this.wait + ")");
            this.wait--;
            setTimeout(function() {
                _this.times();
            },1000);   
        }   
    };
    this.smClick=function(){ 
         var obj=this;
         $(obj.inputClick).click(function(){
                json='{"filed":"'+obj.mobileVal+'","'+obj.mobileVal+'":"'+$('#'+obj.mobileVal).val()+'","'+obj.TOKEN+'":"'+$("input[name='"+obj.TOKEN+"']").val()+'"}';
                obj.json=JSON.parse(json);                                                    
                if($('#'+obj.mobileVal).val()=='' || isNaN($('#'+obj.mobileVal).val()) || $('#'+obj.mobileVal).val().length !=11){      
                        obj.showInfo('#'+obj.mobileVal,'手机号码格式错误'); //提示错误
                        setTimeout(function() {
                            obj.removeInfo(); //移除提示框                   
                        },3000);                            
                        return;
                }   
                var config=true;
                if(_this.type){ //验证手机是否存在
                    var chkJson='{"filed":"'+obj.mobileVal+'","'+obj.mobileVal+'":"'+$('#'+obj.mobileVal).val()+'","'+obj.TOKEN;
                    chkJson+='":"'+$("input[name='"+obj.TOKEN+"']").val()+'","chks":"2"}';
                }else{ //验证手机是否重复
                    var chkJson='{"filed":"'+obj.mobileVal+'","'+obj.mobileVal+'":"'+$('#'+obj.mobileVal).val()+'","'+obj.TOKEN;
                    chkJson+='":"'+$("input[name='"+obj.TOKEN+"']").val()+'","chks":"1"}';
                }
               
                topChk=JSON.parse(chkJson);
               $.ajax({
                     async:false,  
                     type:'post',
                     url:obj.url,
                     data:topChk,
                     success:function(response,status,xhr){
                         if(response==1){
                             config=false;
                         }else{
                             config=true;
                         }
                      }
               });  

              if(!config) {
                    var newInfo='';
                    if(_this.info) {newInfo=_this.info;}else{newInfo=' 此号码已被其他人注册';} 
                    obj.showInfo('#'+obj.mobileVal,newInfo); //提示错误
                    setTimeout(function() {
                          obj.removeInfo(); //移除提示框 
                    },3000);                  
                    return;
               }
               obj.times();
               $.ajax({
                     type:'post',
                     url:obj.url,
                     data:obj.json,
                     success:function(response,status,xhr){
                         if(obj.returnNode){
                             $(obj.returnNode).html(response);
                         }
                      }
               });                      
         });
    };
    //显示ValidationEngine插件错误提示层,这个地方position需要实际情况修改
    this.showInfo=function(node,info){
        var str='<div class="user_nameformError parentFormundefined formError"'; 
        str+='style="opacity: 0.87; position: absolute; top: '+this.infoPositionTop+'px; left: '+this.infoPositionLeft+'px; ';
        str+='margin-top: 0px;" id="remove-error-validationEngine">';
        str+='<div class="formErrorArrow formErrorArrowBottom">';
        str+'<div class="line1"><!-- --></div>';
        str+='<div class="line2"><!-- --></div>';
        str+='<div class="line3"><!-- --></div>';
        str+='<div class="line4"><!-- --></div>';
        str+='<div class="line5"><!-- --></div>';
        str+='<div class="line6"><!-- --></div>';
        str+='<div class="line7"><!-- --></div>';
        str+='<div class="line8"><!-- --></div>';
        str+='<div class="line9"><!-- --></div>';
        str+='<div class="line10"><!-- --></div>';
        str+='</div>';
        str+='<div class="formErrorContent"> '+info+'<br></div>';
        str+='</div>';
        $(node).before(str);
    };
    //移除ValidationEngine插件错误提示层
    this.removeInfo=function(){
        $('#remove-error-validationEngine').remove();
    };
}