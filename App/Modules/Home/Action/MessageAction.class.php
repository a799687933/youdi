<?php
// 本类由系统自动生成，仅供测试用途 
class MessageAction extends BasicAction {
	
    //客户留言
    public function words(){
			foreach($_POST as $k=>$v) $_POST[$k]=strip_tags($v);
			$_POST['user_id']=$_POST['user_id'] > 0 ? $_POST['user_id'] : 0;
			$_POST['addtiem']=time();
			$_POST['content']=$_POST['content'] ? emptyHtml($_POST['content']) : $this->myInfos(false,$submits,isL(L('ContentEmptys'),'内容不可空'),'','','');
			$tool=new ToolModel();
			$tool->requestArrayModel();
			$submits=$_REQUEST['submit'] ? $_REQUEST['submit'] : 'submits';
			if(M('words')->add($_POST)){
				//$str="<img src='".__ROOT__."/Public/Plugins/formValidation/icon102.png' style='position:relative;top:3px;width:16px;'/> ";
				$str.="<span style='color:#ffffff;'>".isL(L('SuccessSubmit'),'恭喜您提交成功')."</span>";
				$this->myInfos(true,'content-text',$str,U('Index/index'),'tcopen()','');
			}else{
				$this->myInfos(false,'content-text',isL(L('Failure'),'操作失败'),'','','');
			}
    }
}