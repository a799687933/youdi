$(function(){
    //选择城市
     $(".icon2").click(function(){
        $(this).toggleClass("icon2now");
        $(".bg,.nav").toggle();
     });
    $(".icon1").click(function(){
        $(".bgcity,.city").toggle();
    }) ; 
    $(".bgcity").click(function(){
        if(!$(this).attr('is-select')){     
           $(".bgcity,.city").toggle();
        }
    }) ; 
    $(".city li span").click(function(){
        $(this).addClass("now");
     });   
});
