   //AJAX获取区域
function getPlace(_ths,append,fn){
	if(!arguments[2]) fn='';
   var urls=$(_ths).attr('url');
    if($(_ths).val() > 0){
      $.ajax({
         type:'post',
         dataType: "json",
         url:urls+"/region_id/"+$(_ths).val(),
         success:function(response,status,xhr){
            var str='<option value="">请选择</option>';
			var li='<li class="active-result result-selected" style="" data-option-array-index="0">Please select</li>';
			var j=1;
            for(var i in response){
                str+='<option value="'+ response[i].region_id +'">'+ response[i].region_name +'</option>';
				li+='<li class="active-result" style="" data-option-array-index="'+(j++)+'">'+ response[i].region_name +'</li>';
            }  
			//alert(append);
			$(append).html(str);
			setTimeout(function(){
				if(fn) eval(fn)(append,li);
			},50);
          }
       });    
   }                              
}

