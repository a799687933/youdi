<?php
// 本类由系统自动生成，仅供测试用途

class ClearImgAction extends CommonAction {
	//当前文件夹下的所有文件夹
    public function index(){
		$directory=C('IMG_UPLOADS');		
		$mydir = dir($directory);
		$dirArr=array();
		$i=0;
        while($file = $mydir->read()){
	         if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!="..")){	
	            $dirArr['dir'][$i]['files']=$directory.'/'.$file.'/';	
				 if(date('Y-m',time())==$file){
				 	$mgs='你正在处理（'.$directory.'/'.$file.'）本月的图片文件夹这可能会影响其它登录的用户,确定执行扫描指令？';
				 }else{
				 	$mgs='确定执行处理（'.$directory.'/'.$file.'）图片文件夹指令？';
				 }
				 $dirArr['dir'][$i]['msg']=$mgs;
				 $i++;
	         }
        }
		$dirArr['count']=$i;
        $mydir->close();
        rsort($dirArr['dir']);
		$this->dir=$dirArr;
        $this->display();	   
    }
	
	//删除当前文件夹下的所有残留图片文件
	public function delImg(){		
		$getDir=trim(urldecode($_GET['dir']));
		clear_img($getDir);
	}

}