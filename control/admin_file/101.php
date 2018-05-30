<?php
if(isset($_GET['uid'])&&$_GET['uid']){
	if(isset($_POST['add'])&&isset($_POST['del'])){
		$add=explode(',',$_POST['add']);
		$del=explode(',',$_POST['del']);
		if($add)$this->admin->add($_GET['uid'],$add);
		if($del)$this->admin->remove($_GET['uid'],$del);
		$system->show_json(['error'=>0]);
	}else{
		$pages=$this->admin->get_all_page();
		$rank=$this->admin->get_admin_rank($_GET['uid']);
		include $system->get_view('admin/admin_rank');
	}
}else{
	$list=$this->admin->admin_list();
	include $system->get_view('admin/admin_list') ;
}