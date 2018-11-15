<?php 

// 公共上传控制器
class UploadFileAction extends UploadBasicFileAction {
	  public function upload(){
		  $this->basicUpload();
	  }	
}