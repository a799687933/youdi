<?php
// 本类由系统自动生成，仅供测试用途
class QQAction extends QQBasicAction {
	
	//自动跳到QQ登录
    public function login(){
	   $this->basiclogin();
    }
	
	//登录成功与失败回调方法
	public function callback(){
	      $this->basicCallback();
	}

}