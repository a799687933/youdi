//多语言
window.langs={
    'surplus':'剩余',
    'hour':'小时',
    'minute':'分',
    'seconds':'秒'
};    
(function($) {
    $.extend({
            promoClock: function(class_id, end_time, now_time) {
                var leave = end_time - now_time;
                var last_time = '';
                if (leave <= 0) {
                    var last_time = "抢购已结束";
                } else {
                    var hour = Math.floor(leave / 3600);
                    var minute = Math.floor(leave / 60) - (hour * 60);
                    var second = leave - (hour * 3600) - (minute * 60);
                    hour = hour < 10 ? "0" + hour : hour;
                    minute = minute < 10 ? "0" + minute : minute;
                    second = second < 10 ? "0" + second : second;
                    //last_time = "剩余：" + hour + " 小时" + minute + " 分" + second + " 秒"; //原始的
                    last_time = "剩余：<font>" + hour + "</font> "+langs.hour+"<font>" + minute + " </font>"+langs.minute+"<font>" + second + " </font>"+langs.seconds;
                    $('#' + class_id).html(last_time);
                    end_time--;
                    setTimeout("$.promoClock('" + class_id + "', '" + end_time + "', '" + now_time + "')", 1000);
                }
            }
        });
})(jQuery);

/**
 * timited(id,end_time,now_tme)
 * @param {Object} id  
 * @param {Object} end_time
 * @param {Object} now_tme
 */
function timited(id,end_time,now_tme){
$(function(){
    $.promoClock(id,end_time, now_tme);
});
}