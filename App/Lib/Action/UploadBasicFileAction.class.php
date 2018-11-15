<?php 

// 本类由系统自动生成，仅供测试用途
class UploadBasicFileAction extends Action {
	//图上传方法
	//如果有$_GET['thum']='ok'明证是缩略图并且是系统指定的宽和高。
	//如果$_GET['thum']='ok' && $_GET['width'] && $_GET['height'] 证明是程序里写死的缩略图宽和高，不受系统配置宽和高限际
	//如果$_GET['remove']=='ok' 明证缩略图后要移除原图	
    protected function basicUpload(){
		 if(!M()->autoCheckToken($_REQUEST)) die('Error0!'); //验证令牌
		 
		 if (!in_array($_GET['up'],array('Uploads','UploadUser','UploadFile'))) die('Error1!'); //选择保存目录
		 $uploadPath='';		
		 $Y=date('Y',time());
		 $M=date('m',time());
		 if($_GET['up']=='Upload'){
			 $uploadPath=C('IMG_UPLOADS').'/';
			 $uploadPathThum=C('IMG_UPLOADS').'/'.$Y.'-'.$M.'/';
			 $autoSub=true;
		 }else{
			 $uploadPath=$_GET['up'].'/';
			 $uploadPathThum=$_GET['up'].'/'.$Y.'-'.$M.'/';
			 $autoSub=true;
		 }																   
		// if(!is_login()) die('Error!');
 		 import('ORG.Net.UploadFile');
		 //文件类型
		 if($_REQUEST['file_type']){
			 $allowExts=explode(',',$_REQUEST['file_type']);
		 }else{
			 $allowExts=array('jpg','png','gif','jpeg','doc','docx','xls','xlsx'); 
		 }
		 $upload=new UploadFile();
		 $upload->config['maxSize']=4096000; // 上传文件的最大值
		 $upload->config['allowExts']=$allowExts; // 允许上传的文件后缀 留空不作后缀检查
		/* $upload->config['allowTypes']=array(
								     'image/png','image/jpg',
									 'image/jpeg',
									 'image/gif',
									 'application/octet-stream','image/pjpeg',
									 'application/msword', //xxx.doc			
									 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //xxx.xlsx	 
									 'image/jpeg','application/vnd.openxmlformats-officedocument.wordprocessingml.document', //xxx.docx
									 'application/vnd.ms-excel'  //xxx.xls									
									); // 允许上传的文件类型 留空不做检查
		 */
		 if($_GET['thum']=='ok'){//如果是上传缩略图
				 if($_GET['width'] && $_GET['height']) {
					 $thumbMaxWidth=$_GET['width'].'px';
					 $thumbMaxHeight=$_GET['height'].'px';
				 }else{
					 $thumbMaxWidth=C(THUMB_WIDTH).'px';
					 $thumbMaxHeight=C(THUMB_HEIGHT).'px';
				 }
				 if($_GET['remove']=='ok'){
					 $upload->config['thumbRemoveOrigin']=true; // 移除原图
				  }else{
					  $upload->config['thumbRemoveOrigin']=false; // 不移除原图
				  }
				 $upload->config['thumb']=true;// 使用对上传图片进行缩略图处理
				 $upload->config['thumbMaxWidth']=$thumbMaxWidth;// 缩略图最大宽度
				 $upload->config['thumbMaxHeight']=$thumbMaxHeight;// 缩略图最大高度
				 $upload->config['thumbPath']=$uploadPathThum;// 缩略图保存路径
				 $upload->config['thumbType']=1; // 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
		 }
		 
		 $upload->config['thumbFile']=date('YmdHsi');// 缩略图文件名
		 $upload->config['thumbExt']='png'; // 缩略图扩展名   
		 $upload->config['autoSub']=$autoSub;// 启用子目录保存文件
		 $upload->config['subType']='date';// 子目录创建方式 可以使用hash date custom 
		 $upload->config['dateFormat']='Y-m'; // hash的目录层次
		 $upload->config['savePath']=$uploadPath;// 上传文件保存路径
		 
		 if($upload->upload()){
			 $info=$upload->getUploadFileInfo();//如果上传成功，获到图片信息
			 $watermarkNale=$upload->config['savePath'].$info[0]['savename'];//大图路径
			 if($autoSub){
			     $termark=$upload->config['savePath'].'/'.$Y.'-'.$M.'/'.$upload->config['thumbFile'].'.'.$upload->config['thumbExt'];//缩略图路径
			 }else{
			     $termark=$upload->config['savePath'].'/'.$upload->config['thumbFile'].'.'.$upload->config['thumbExt'];//缩略图路径
			 }
			 

			 //跟据文件资源句柄判断大图片文件合法性
			/* $watermarkNaleHandle= str_replace('//','/',$watermarkNale);
			 if(file_exists('./'.$watermarkNaleHandle)){
				 if(!file_type('./'.$watermarkNaleHandle,$upload->config['allowExts'])){
					 @unlink('./'.$watermarkNaleHandle);
			           echo json_encode(array(
						    'state'=>'禁止上传非法文件'
						));			
					   die();
				 }
			 }
			
			 //跟据文件资源句柄判断缩略图文件合法性
			 $termarkHandle= str_replace('//','/',$termark);
			 if(file_exists('./'.$termarkHandle)){
				 if(!file_type('./'.$termarkHandle,$upload->config['allowExts'])){
					 @unlink('./'.$termarkHandle);
			           echo json_encode(array(
						    'state'=>'禁止上传非法文件'
						));			
					   die();
				 }
			 }	*/	

			// 如果缩略图不存在返回大图路径
			 if(!file_exists($termark)) $termark = $watermarkNale;
			 $termark=str_replace('//','/',$termark);
			 //如果有配置加水印
			 if(C(WATERMARK)  && !$_GET['width'] && !$_GET['height']){
				 if(file_exists(C(THUMBNAIL_PATH))){
					 import('ORG.Util.Image');//调用图片水印类
					 Image::water($watermarkNale,C(THUMBNAIL_PATH));//大图加水印图路径
					 Image::water($termark,C(THUMBNAIL_PATH));//缩略图加水印图路径
				 }
			 }
			 
			 if($_GET['biadu']=='up'){
				 $imgurl=$info[0]['savename']; //百度编辑器上传的图片
			 }else{
				 $imgurl=$uploadPath.$info[0]['savename'];	//普通大图路径	
				
			 }
            if($_REQUEST['dwz']=='ok'){
            	 die('{"err":"","msg":"'.__ROOT__.'/'.$watermarkNale.'"}'); 
            }else{			  
			 echo json_encode(array(
						'url'=>$imgurl,	//大图路径	
						'termarkurl'=>$termark, //缩略图路径	
						'title'=>htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
						'original'=>$info[0]['name'],
						'state'=>'SUCCESS'
					  ));
			}			  
		  }else{
			  echo json_encode(array(
						'state'=>$upload->getErrorMsg()
						));
		  }   	
	}
	
}