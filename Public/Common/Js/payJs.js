/**
*支付支向
* buUrl 余额支付地址
* suUrl 在线支付地址
*/
function setPay(buUrl,suUrl){
    //支付方式
    $('form').attr('action',suUrl);
    $('input[name="pay_type"]').click(function(){
        var payType=parseInt($(this).val());
        var bu='<input type="button"value="立即支付" name="submits" onClick="edcfsAed(\'#publishs\',true,60,\'\');" '; 
        bu+='style="width:160px;height:38px;line-height: 38px;background:#893a9a;border:none;color:#fff;';
        bu+='font-family: Microsoft Yahei;font-weight: bold;font-size:17px;cursor: pointer;" /> ';
        var su='<input type="submit"value="立即支付" name="submits"'; 
        su+='style="width:160px;height:38px;line-height: 38px;background:#893a9a;border:none;color:#fff;';
        su+='font-family: Microsoft Yahei;font-weight: bold;font-size:17px;cursor: pointer;" /> ';        
        if(payType==1){
            $('.pay-divs').hide();
            $('.vid-submit').html('');
            $('.vid-submit').html(bu);
            valinit('.form1','post','',true,'bottomLeft');
            $('form').attr('action',buUrl);
        }else{
            $('.pay-divs').show();
            $('.vid-submit').html('');
            $('.vid-submit').html(su);
            valinit('.form1','post','',false,'bottomLeft');
            $('form').attr('action',suUrl);
        }
    });
}