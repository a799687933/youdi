///////////////////////////////////////////////////////////////////////////////////
//关联文章

//文章从左边过右边
function leftValueOen(selectClass,selectRightCla,flag){
    if(!arguments[2]) flag = "";
	var fong=true;
    var yes=null;
    var html=null;	
    var id=null;
	var option=$('.'+selectClass).children('option::selected');
	var optionVal= $('.'+selectRightCla).children('option');   
	//$(optionVal).attr('selected',true);
	for(var i=0;i<option.length;i++){
		if(optionVal.length){
			for(var j=0;j<optionVal.length;j++){
				if(option[i].value ==optionVal[j].value){
					fong=false;
				}
			}
		}
		if(fong){
		    if(flag){
    			if($('.radios').length > 0){
        			for(var k=0;k<$('.radios').length;k++){
        			    if($($('.radios')[k]).attr('checked')){
        			        yes=$($('.radios')[k]).val();
        			    }
        			}
        			if(yes==1){
        			    html=$(option[i]).html()+'<---->双向关联';
        			    id=$(option[i]).val()+'-1'; //双向关联1
        			}else{
        			    html=$(option[i]).html()+'---->单向关联';
        			     id=$(option[i]).val()+'-0';//单向关联0
        			}
        			$(option[i]).clone(true).appendTo('.'+selectRightCla).attr('selected',true).html(html).val(id);		
        		}	 
    		 }else{
			      $(option[i]).clone(true).appendTo('.'+selectRightCla).attr('selected',true);			
			 }
		}
		fong=true;
	}
}

//从边移除文章
function rightValueOen(selectClass){
	var option=$('.'+selectClass).children('option::selected');
	 $(option).remove();
}

/**
 * searchArticle(Url,searchTable,searchNanme,searchCatTableName)
 * @param {Object} Url  请求的URL
 * @param {Object} searchTable  需要检索的表名称不带前缀
 * @param {Object} returnId  表字段名称ID名称，如id
 * @param {Object} searchNanme  需要检索的表字段名称用ID替换
 * @param {Object} searchCatId 可选，如果要进行分类检索，自动获取所选定的分类ID，select ID属性也要 id='searchCatId'
 * @param {Object} appNode 要生成检索结果的select标签类名称
 * returnId和searchNanme将会成为检索表所获取的两个字段名称
 */
//Ajax搜索关联内容
function searchArticle(Url,searchTable,returnId,searchNanme,searchCatId,appNode){
    var CatTableUrl='';
    var keyData=''; 
     if(!arguments[4]) {
         searchCatId="";
         var CatTableUrl='';
     }else{
         CatTableUrl='/catIdName/'+searchCatId+'/catIdVal/'+$('#'+searchCatId).val();
     }   
    var str=null;
    var inpuVal=$('#'+searchNanme).val();

    if(inpuVal){
        var keyData='/Keywords/'+inpuVal;
    }
    var doUrl=Url+'/tableName/'+searchTable+'/'+'fieName/'+searchNanme+'/fieId/'+returnId+keyData+CatTableUrl;
      $.ajax({
         //timeout:3000, //请求时超过3秒，就自动结束     
         async: false,//同步提交 
         type:'get',
         dataType: "json",//返回json格式的数据
         url:doUrl,
         success:function(response,status,xhr){
            var id=null;
             for(var i in response){
                 for(var j in response[i]){                  
                   if(!id) id=response[i][j];
                    
                 }                
                 str+='<option value="'+id+'">'+response[i][j]+'</option>';
                 id=null;
             }
             $('.'+appNode).children('option').remove();
             $('.'+appNode).append(str);
          },       
         error:function(xhr,errorText,errorType){
             alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
          }
       });                        
}

 //商品关联和配件关联检索 url,s_name,cart-ajax,bra-ajax,is_general,selectLeftProList
 function retrieval(url,name_ajax,cart_ajax,bra_ajax,is_general,append_class){     
        var cart_id_url='';
        var bra_id_url='';
        var s_name_url='';
        var str='';
        var s_name=$('input[name="'+name_ajax+'"]').val();
        var cart_id=$('.'+cart_ajax+' option:selected').val();
        var bra_id=$('.'+bra_ajax+' option:selected').val();
        if(parseInt(cart_id) >0) cart_id_url="goods_category_id/"+cart_id+"/";
        if(parseInt(bra_id) >0) bra_id_url="goods_brand_id/"+bra_id+"/";
        if(s_name) s_name_url="goods_name/"+s_name+"/";
        var doUrl=url+"/is_general/"+is_general+"/"+cart_id_url+bra_id_url+s_name_url;
        var product=callAjaxDo(doUrl,'json');
        for(var i in product){        
            str+='<option value="'+product[i].id+'">'+product[i].goods_name+'</option>';
        }
        $('.'+append_class).children('option').remove();
        $('.'+append_class).append(str);    
 }
