/**
*加载更多分页
*/
function loadMoerPage(){
    var page=2;
    this.options={
        'url':'', //请求地址
        'totalPages':0, //总页数
        'addEnd':'', //追加内容节点
        'addEndLoadImg':'',//显示生成加载图片父节点
        'addEndLoadImgPath':'', //加载图标路径
    };
    var _this=this;
    this.run=function(){
      $(window).scroll(function(){
        if  ($(window).scrollTop() + 1 >= $(document).height() - $(window).height()){ 
             if(page <=_this.options.totalPages){  
                if($('#my-s-add-end-loads').length > 0) return;              
                 _this.getPage();
             }else{
                 if($('#my-s-add-end-loads').length==0){
                    var str='<p style="height:40px;line-height:40px;text-align:center;clear: both" id="my-s-add-end-loads">';
                    str+='已全部加载完毕';
                    str+='</p>';
                    $(_this.options.addEndLoadImg).append(str);
                 }
             }        
        } 
      });           
    };
    this.getPage=function(){
          $.ajax({
             type:'get',
             dataType: "text",
             url:_this.options.url+page,
             success:function(response,status,xhr){
                 page++;
                 $(_this.options.addEnd).append(response);
              },
              complete:function(){
                 $('#my-s-add-end-loads').remove();
              },
               beforeSend:function(){
                   var str='<p style="height:40px;line-height:40px;text-align:center;clear: both;" id="my-s-add-end-loads">';
                   str+='<img src="'+_this.options.addEndLoadImgPath+'" style="position: relative;top:5px;right:2px;">正在加载 . . .';
                   str+='</p>';
                  $(_this.options.addEndLoadImg).append(str);
              },         
             error:function(xhr,errorText,errorType){
                 alert('错误代码：' + xhr.status + ' : ' + xhr.statusText);
             }
         });              
    }
}