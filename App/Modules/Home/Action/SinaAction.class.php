<?php
// 本类由系统自动生成，仅供测试用途
class SinaAction extends SinaBasicAction {

	//自动跳到新浪授权页面
    public function login(){	    
        $this->basiclogin();
    }
	
	//登录成功与失败回调方法
	public function callback(){
		$this->basicCallback();
	}

}