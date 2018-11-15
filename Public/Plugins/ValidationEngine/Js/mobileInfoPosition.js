//手机版验证信息提示定位
var container=window;
var ww=$(container).width();
var wh=$(container).height();
ww=(ww - 250) /2;
var style='<style type="text/css">';
style+='.parentFormundefined{position:fixed !important;top:3% !important;left:'+ww+'px !important;z-index: 20008;}';
//style+='.parentFormundefined{position:fixed !important;top:2% !important;left:20% !important;z-index: 20008;}';
style+='.formErrorContent{width:250px !important;height:30px !important;line-height:30px !important;';
style+='background:#000 !important;font-size:0.6rem!important;color:#fff !important;}';
style+='.formErrorArrow,.formErrorArrowBottom{display:none !important;}';
style+='</style>';
document.write(style);