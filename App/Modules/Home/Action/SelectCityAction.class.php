<?php
// 选择城市 
class SelectCityAction extends Action {

    public function index(){
         $url=$_SERVER["HTTP_REFERER"];
		 if(strpos($_SERVER["HTTP_REFERER"],$_SERVER['HTTP_HOST'])!==false){
		     echo 1;
		 }
    }
	
}