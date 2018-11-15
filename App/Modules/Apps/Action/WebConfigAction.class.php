<?php
class WebConfigAction extends CommonAction{
    
    //显示系统参数设置
    public function index(){
        //$pid=M('web_config')->field('parent_id')->where(array('parent_id'=>0,'display'=>1))->select();
        $this->pid=M('web_config')->where(array('parent_id'=>0,'dispay'=>1))->order('sort_order ASC')->select();
        $this->valueAll=M('web_config')->where(array('dispay'=>1))->order("sort_order ASC")->select();
        
        $this->display();
  
    }
    
    //系统参数设置表单处理
    public function configForm(){
           $db=M('web_config');
               $Allid=$_POST['id'];//获取ID集
               $arr=$_POST;
               unset($arr['id']); //
               unset($arr[TOKEN_NAME]); //
               
               $i=0;
               $imgAarr=array();
               foreach($arr as $k=>$v){
               	   //提取上传的图片
               	   if($k=='webLogo'){
               	   	  $imgAarr[$i]['id']=$Allid[$i];
					   $imgAarr[$i]['img']=$v;
               	   }
               	   //修改数居库
                   $str.="\t\t"."'".strtoupper($k)."'=>'".escape_str($v)."',"."\r\n";
                   $db->where('id='.$Allid[$i])->save(array('value'=>$v));
                   $i++;
               }
			   
			   //修改图片
			   import('Class.DisposeImg',APP_PATH);
			   if($imgAarr){
			   	  foreach($imgAarr as $imgK=>$imgV){
			   	  	 if($imgV['img']){
			   	  	 	DisposeImg::updatePictures('web_config', $imgV['id'], array($imgV['img']));
			   	  	 }
			   	  }
			   }

               //生成配置文件Common/Conf/SystemConfig.php
               $phpTop="<?php"."\r\n";
               $arrContent="\t"."return array("."\r\n";
               $arrContentEnd="\t);"."\r\n";                         
               $phpBotton="?>";
               $configStr=$phpTop.$arrContent.$str.$arrContentEnd.$phpBotton;
               file_put_contents(APP_PATH.'/Conf/SystemConfig.php',$configStr);//生成配置文件
              // return_json(200,L('InfoSuccess')); 
              echo '<script type="text/javascript">window.location.href="'.U(APP_APPS.'/Index/index','','').'";</script>';
    }
    
}
?>