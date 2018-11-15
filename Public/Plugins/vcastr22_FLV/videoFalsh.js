/**
*SWF(nodeId,swfWidth,swfHeight,files,title,swf)
*nodeId     生成播放器的节点ID
*swfWidth   播放器的宽度
*swfHeight  播放器的高度
*files      要放播放的视频路径
*title      视频标题
*swf        使用播放器的路径
*/
function SWF(nodeId,swfWidth,swfHeight,files,title,swf){
   var id=document.getElementById(nodeId); 
   var str='';
   str+='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"';
   str+='codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ swfWidth +'" height="'+ swfHeight +'">';
   str+='<param name="movie" value="'+swf+'"><param name="quality" value="high">';
   str+='<param name="menu" value="false"><param name="allowFullScreen" value="true" />';
   str+='<param name="FlashVars" value="vcastr_file='+files+'&vcastr_title='+title+'">';
   str+='<embed src="'+swf+'" allowFullScreen="true" FlashVars="vcastr_file='+files+'&vcastr_title=';
   str+=title+'menu="false" quality="high" width="'+ swfWidth +'" height="'+ swfHeight;
   str+='type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
   str+='</object>';
   if(id) id.innerHTML='<h2 style="height:30pxline-height:30px;color:red;font-size:30px;float:right;padding-right:8px; cursor:pointer" onclick="closeEdcfsAed(\'#Falsh\')">×</h2>'+str;
   edcfsAed('#'+nodeId,true,60,'');
}