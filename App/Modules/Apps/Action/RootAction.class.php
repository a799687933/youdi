<?php
// 本类由系统自动生成，仅供测试用途

class RootAction extends CommonAction {
	//删除Runtime目录
    public function delRuntime(){
          rmdirs(RUNTIME_PATH);  
		  return_json(200,'操作成功！');
    }
    	
}