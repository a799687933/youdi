<?php
//全部单页模块  
class ModularAction extends BasicAction {
	
	//界面相同的单页
	public function pubarticle(){
		  $id=$_GET['id'] > 0 ? $_GET['id'] :  $_GET['html'] > 0 ? $_GET['html'] : 0;
          $this->result=$this->getData($id);
	      $this->display();
	}
	
     //单品推荐
	 public function singleProduct(){
		  $result=$this->getData(C('A_5'));
		  $this->result=$result;
		  $this->ads=$this->getAd(36);
		  //是否已点赞过
		  $where['article_id']=$_GET['html'] > 0 ? $_GET['html'] : 0;
		  $where['ip']=get_ip();
		  $this->clicks=M('article_click')->field('id')->where($where)->find();
	      $this->display();
	 }	
	
	//关于我们
	public function about(){
		  $result=$this->getData(C('A_1'));
		  $this->result=$result;	
		  $this->display(); 			
	}
	
	//品牌型像
	public function brandImage(){
		  $result=$this->getData(C('A_8'));
		  $this->result=$result;			
	      $this->display();
	}
	
	//订单与寄送
	public function shipping(){
		  $result=$this->getData(C('B_2'));
		  $this->result=$result;	
		  $this->display(); 
	}
	
	//退货与退款
	public function refund(){
		  $result=$this->getData(C('B_3'));
		  $this->result=$result;	
		  $this->display(); 	
	}
	
	//常见问题
	public function problem(){
		  $result=$this->getData(C('B_4'));
		  $this->result=$result;	
		  $this->display(); 		
	}
	
	//隐私与Cookie政策
	public function privacy(){
		  $result=$this->getData(C('B_6'));
		  $this->result=$result;	
		  $this->display(); 			
	}
	
	//需要帮助
	public function needHelp(){
		  $result=$this->getData(C('B_7'));
		  $this->result=$result;	
		  $this->display('Modular/needHelp/'.pfix('needHelp')); 			
	}
	
	//品牌理念
	public function idea(){
	 	  $result['titletext']=isL(L('BrandIdea'),'品牌理念');
		  $this->result=$result;
		  $this->display('Modular/idea/'.pfix('idea')); 		
	}	
	 
	 //	客户尊享
	 public function enjoy(){

	 	$result['titletext']=isL(L('CustomerRespect'),'客户尊享');
		$this->result=$result;
		//p($this->getData(C('A_6')));die;
		$this->display('Modular/enjoy/'.pfix('enjoy'));
	}
	
	//职业机会
	public function opportunity(){
		$this->result=$this->getData(C('A_2'));
	    $this->display('Modular/opportunity/'.pfix('opportunity'));
	}
	
	//职业机会表单处理
	public function opportunityForm(){
		if(!verify_req_sign($_GET,array(),86400)) $this->myInfos(false,'','',$_SERVER['HTTP_ACCEPT']);
	    $data['type']=intval($_REQUEST['type']);
		$data['user_name']=$_REQUEST['user_name'] ? emptyHtml($_REQUEST['user_name']) : $this->myInfos(false,'','error!');
		$data['sex']=$_REQUEST['sex'] ? emptyHtml($_REQUEST['sex']) : $this->myInfos(false,'','error!');
		$data['birthday']=$_REQUEST['birthday'] ? emptyHtml($_REQUEST['birthday']) : $this->myInfos(false,'','error!');
		$data['mobile']=$_REQUEST['mobile'] ? emptyHtml($_REQUEST['mobile']) : $this->myInfos(false,'','error!');
		$data['start_year']=$_REQUEST['start_year'] ? emptyHtml($_REQUEST['start_year']) : $this->myInfos(false,'','error!');
		$data['address']=$_REQUEST['address'] ? emptyHtml($_REQUEST['address']) : $this->myInfos(false,'','error!');
		$data['email']=$_REQUEST['email'] ? emptyHtml($_REQUEST['email']) : $this->myInfos(false,'','error!');
		$data['annual_income']=$_REQUEST['annual_income'] ? emptyHtml($_REQUEST['annual_income']) : $this->myInfos(false,'','error!');
		$data['expe_date']=$_REQUEST['expe_date'] ? emptyHtml($_REQUEST['expe_date']) : $this->myInfos(false,'','error!');
		$data['expe_company']=$_REQUEST['expe_company'] ? emptyHtml($_REQUEST['expe_company']) : $this->myInfos(false,'','error!');
		$data['comp_nature']=$_REQUEST['comp_nature'] ? emptyHtml($_REQUEST['comp_nature']) : $this->myInfos(false,'','error!');
		$data['ability']=$_REQUEST['ability'] ? emptyHtml($_REQUEST['ability']) : $this->myInfos(false,'','error!');
		$data['industry']=$_REQUEST['industry'] ? emptyHtml($_REQUEST['industry']) : $this->myInfos(false,'','error!');
		$data['positions']=$_REQUEST['positions'] ? emptyHtml($_REQUEST['positions']) : $this->myInfos(false,'','error!');
		$data['department']=$_REQUEST['department'] ? emptyHtml($_REQUEST['department']) : $this->myInfos(false,'','error!');
		$data['describe']=$_REQUEST['describe'] ? emptyHtml($_REQUEST['describe']) : $this->myInfos(false,'','error!');
		$data['education_date']=$_REQUEST['education_date'] ? emptyHtml($_REQUEST['education_date']) : $this->myInfos(false,'','error!');
		$data['school']=$_REQUEST['school'] ? emptyHtml($_REQUEST['school']) : $this->myInfos(false,'','error!');
		$data['education']=$_REQUEST['education'] ? emptyHtml($_REQUEST['education']) : $this->myInfos(false,'','error!');
		$data['major']=$_REQUEST['major'] ? emptyHtml($_REQUEST['major']) : $this->myInfos(false,'','error!');
		$data['expect']=$_REQUEST['expect'] ? emptyHtml($_REQUEST['expect']) : $this->myInfos(false,'','error!');
		$data['self_evaluation']=$_REQUEST['self_evaluation'] ? emptyHtml($_REQUEST['self_evaluation']) : $this->myInfos(false,'','error!');
		$data['file1']=$_REQUEST['file1'] ? emptyHtml($_REQUEST['file1']) : '';
		$data['file2']=$_REQUEST['file2'] ? emptyHtml($_REQUEST['file2']) : '';
		$data['add_time']=time();
		if(M('recruit')->add($data)){
			//$this->myInfos(false,'','',U('/Index/index'));
			//header('location:'.U('/Index/index'));
			$str="<span style='color:#333;'>".isL(L('SubmitSuccessfully'),'提交成功')."</span>";
			$this->myInfos(true,'',$str,$_SERVER['HTTP_REFERER']);
		}else{
			$this->myInfos(false,'','',$_SERVER['HTTP_REFERER']);
		}
	}
	
	//技术支持
	public function technology(){

		$this->result=$this->getData(C('A_4'));
	    $this->display();	
	}
	 
	 //LOOKBOOK
	 public function lookbook(){
		  $this->ads=$this->getAd(41);		  
	      $this->display();
	 }
	 
	 //下载APP
	 public function download(){
	      $this->display();
	}
	
	//显示微信公众号页面
	public function weiXin(){
	    $this->display();
	} 
	
	//检索不到商品时显示页面
	public function serchnone(){
	     //最近浏览
	    $this->recentVisit= $this->recentVisit(0,24,0);			
	    $this->display();
	}
	
	//获得数据
	private function getData($id){
		  $f="id,{$this->langfx}titletext AS titletext,thumb,{$this->langfx}keywords AS keywords,{$this->langfx}bewrite AS bewrite,{$this->langfx}right_content AS right_content,";
		  $f.="{$this->langfx}content AS content,addtime,{$this->langfx}author AS author,like_count";
		  $result=M('article')->field($f)->where(array('id'=>$id,'is_show'=>1))->find();
		  return $result;	
	}

}