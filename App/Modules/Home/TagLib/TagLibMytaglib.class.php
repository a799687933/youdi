<?php
/**
*自定义标签库类
*/
class TagLibMytaglib extends TagLib{
    protected $tags=array(//声明自定义标签
					'nav' =>array('attr'=>'limit,order','close'=>1),//导航标签
                    'list' =>array('attr'=>'table,field,limit,order,where,page,pagetype,cont','close'=>1), //列表标签
					);
	////导航标签自定义标签方法				
	public function _nav($attr,$content){ //nav标签方法  
	  
	   $attr=$this->parseXmlAttr($attr);//获取属性数组	   
	   if(!array_key_exists('limit',$attr)) $attr['limit']=0;//如果$attr['limit']不存在	   
	   if(!array_key_exists('order',$attr)) $attr['order']='ASC';//如果$attr['order']不存在	  
	   if(!empty($attr['order']) && strtoupper($attr['order'])!='ASC' && strtoupper($attr['order']) !='DESC'){
	     $attr['order']='ASC';
	   } 
	  $order=strtoupper($attr['order']);
	  $limit=$attr['limit'];
      $str.='<?php ';
      $str.='if('.$limit.' && is_numeric('.$limit.')):';
      $str.='$_nav_cate=M("nav")->field("nav_id,nav_pid,nav_name,nav_on,nav_url,nav_blank")->order("nav_sort '.$order.'")->limit('.$limit.')->select();';
      $str.='else:';
      $str.='$_nav_cate=M("nav")->field("nav_id,nav_pid,nav_name,nav_on,nav_url,nav_blank")->order("nav_sort '.$order.'")->select();';
      $str.='endif;';
      $str.='import("Class.Category",APP_PATH);';
      $str.='$_nav_cate=Category::unlimitedForLayer($_nav_cate);';
      $str.='foreach($_nav_cate as $_nav_v):';
      $str.='extract($_nav_v); ?>';
      $str.=$content."\n";     
      $str.='<?php endforeach; ?>';     
      return $str;   
	}

	
   /**
     * @自定义列表标签 list 以下属性均为可选项
     * @access public
     * @param string  table  属性：表名称
     * @param string  field  属性：要读取的表字段名多个字段用半角豆号隔开
     * @param numeric  limit  属性：要读取的条数
     * @param string  order  属性：排序条件
     * @param string  where  属性：查找的条件
     * @param numeric  page  属性：每页显示多少条数据
     * @param numeric  pagetype  属性：可选的分页类型
     * @param string  cont  属性：当前运行的控制器名称
     */
	public function _list($attr,$content){
	   $attr=$this->parseXmlAttr($attr);
	   if(empty($attr['table'])) $attr['table']='';//表名称
	   if(empty($attr['field'])) $attr['field']='';//选择字段
	   if(empty($attr['limit'])) $attr['limit']='';//获取条数  
	   if(empty($attr['order'])) $attr['order']='';//排序 
	   if(empty($attr['where'])) $attr['where']='';//要查找的条件   
	   if(empty($attr['page'])) $attr['page']='';//分页 
	   if(empty($attr['pagetype'])) $attr['pagetype']='';//分页 类型
	   if(empty($attr['cont'])) $attr['cont']='';//当前控制器，必须输入分页才能正确

	   $table=trim($attr['table']);
	   $field=trim($attr['field']);
	   $limit=trim($attr['limit']);
	   $order=trim($attr['order']);
	   $where=trim($attr['where']);
	   $page=trim($attr['page']);
	   $pagetype=trim($attr['pagetype']);
	   $cont=trim($attr['cont']);

	   
       $str.='<?php ';
       
       if($limit && !is_numeric($limit)){
         $str.='echo "<br/>list 标签(limit属性)必须是数字！";exit();';
       }
       
	   if($page && !is_numeric($page)){
         $str.='echo "<br/>list 标签(page属性)必须是数字！";exit();';
       }      
       
       if($table && $field && $limit && $order && $where && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->where("'.$where.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');';  
       }     

       
       else if($table && $field && $limit && $order && $where && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }            
       
       else if($table && $field && $limit && $order && $where && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }               
       
        
        else if($table && $field && $order && $where && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->where("'.$where.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }     
       
      else if($table && $field && $order && $where && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $field && $order && $where && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }              
       
       
       
       
        
        else if($table && $order && $where && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->where("'.$where.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->where("'.$where.'")->order("'.$order.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }            
       

       else if($table && $order && $where && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $order && $where && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }              
             
       
       
        
        else if($table && $where && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->where("'.$where.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->where("'.$where.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }                


      else if($table && $where && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $where && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }              
       

        

       

        
      else if($table && $order && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->order("'.$order.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }                  
       
      else if($table && $order && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $order && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }                    
       
       
       else if($table && $limit && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }                


      else if($table && $limit && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $limit && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }                     
       
       
       else if($table && $field && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }                


      else if($table && $field && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && $field && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }                            
       
         else if($table && $page && $cont){
         $str.='import("Class.Page",APP_PATH);';
         $str.='$_list_count=M("'.$table.'")->count();';
         $str.='$_list_page=new Page($_list_count,'.$page.');';
         $str.='$_list_limit=$_list_page->firstRow . "," . $_list_page->listRows;';
         $str.='$_list_page->controller='.$cont.';';
         $str.='$_list_result=M("'.$table.'")->limit($_list_limit)->select();';
         $str.='$page=$_list_page->show('.$pagetype.');'; 
       }                     
       
      else if($table && $page && !$cont){
         $str.='echo "<br/>list 标签缺少(cont属性)当前运行控制器！";exit();';
       }                
       
      else if($table && !$page && $cont){
         $str.='echo "<br/>list 标签缺少(page属性)分页显示数目！";exit();';
       }                        
       
       
       else if($table && $field && $limit && $order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit('.$limit.')->select();';
       }          
       
       else if($table && $field && $limit && $order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->order("'.$order.'")->limit('.$limit.')->select();';
       }        
       
       else if($table && $field && $limit && !$order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->where("'.$where.'")->limit('.$limit.')->select();';
       }            
       
      else if($table && $field && !$limit && $order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->order("'.$order.'")->where("'.$where.'")->select();';
       }            
       
       else if($table && !$field && $limit && $order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->order("'.$order.'")->where("'.$where.'")->limit('.$limit.')->select();';
       }         
       
       
       
       
       
       
       else if($table && $field && $limit && !$order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->limit('.$limit.')->select();';
       }           
       
       else if($table && $field && !$limit && $order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->order("'.$order.'")->select();';
       }           
       
       else if($table && $field && !$limit && !$order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->where("'.$where.'")->select();';
       }            
       
       else if($table && !$field && $limit && $order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->order("'.$order.'")->limit('.$limit.')->select();';
       }    
       
       else if($table && !$field && $limit && !$order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->where("'.$where.'")->limit('.$limit.')->select();';
       }          
       
       else if($table && !$field && !$limit && $order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->where("'.$where.'")->order("'.$order.'")->select();';
       }        
       
       else if($table && $field && !$limit && !$order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->field("'.$field.'")->select();';
       }          
       
       else if($table && !$field && $limit && !$order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->limit('.$limit.')->select();';
       }         
       
       else if($table && !$field && !$limit && $order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->order("'.$order.'")->select();';
       }          
       
       else if($table && !$field && !$limit && !$order && $where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->where("'.$where.'")->select();';
       }         
       
       else if($table && !$field && !$limit && !$order && !$where && !$page && !$cont){
         $str.='$_list_result=M("'.$table.'")->select();';
       }       
       
       else if(!$table && !$field && !$limit && !$order && !$where && !$page && !$cont){
         $str.='echo "<br/>list 标签缺少(table属性)至少传一个表名称！";exit();';
       }         
       
       
        $str.='$list_k=0;';
        $str.='foreach($_list_result as $_list_result_v): ';
        $str.='extract($_list_result_v);';
        $str.='$list_k++; ?>';
        $str.=$content."\n";        
        $str.='<?php endforeach; ?>';       
        $str.='<?php ?>';
      
       return $str;
	}
	
	
}
?>