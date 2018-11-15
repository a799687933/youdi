function whereCity(_ths,addNode){
   var urls=$(_ths).attr('url');
    if($(_ths).val() > 0){
      $.ajax({
         type:'post',
         dataType: "json",
         url:urls+"/id/"+$(_ths).val(),
         success:function(response,status,xhr){
            var str='<option value="">请选择</option>';
            for(var i in response){
                str+='<option value="'+ response[i].city_id+'">'+ response[i].city_name +'</option>';
            }
            if($(addNode).length >0){
                $(addNode).html(str);
            }                                        
          }
       });    
   }  
}