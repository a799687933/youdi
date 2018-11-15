<?php
// 本类由系统自动生成，仅供测试用途
class UploadRarAction extends CommonAction {

    public function upload(){
		die('当前不可用');
    	//ini_set("upload_max_filesize", "8M");
 		 import('ORG.Net.UploadFile');
		 $upload=new UploadFile();
		 $upload->config['maxSize']=8096000; // 上传文件的最大值
		 $upload->config['allowExts']=array('rar','xls','doc');
         $upload->config['allowTypes']=array('rar','xls','doc'); // 允许上传的文件类型 留空不做检查		         
		 $upload->config['autoSub']=true;// 启用子目录保存文件
		 $upload->config['subType']='date';// 子目录创建方式 可以使用hash date custom 
		 $upload->config['dateFormat']='Y-m'; // hash的目录层次
		 $upload->config['savePath']=C(FILE_UPLOADS).'/';// 上传文件保存路径
		 
		 if($upload->upload()){
				 $info=$upload->getUploadFileInfo();//如果上传成功，获到图片信息 
				 $file=$info[0]['savepath'].$info[0]['savename'];
				 echo json_encode(array(
							'termarkurl'=>$file, //文件路径
							'state'=>'SUCCESS'
						  ));		  
		  }else{
				  echo json_encode(array(
							'state'=>$upload->getErrorMsg()
				 ));
		  }   	
	}
	
}