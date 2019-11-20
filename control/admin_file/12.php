<?php
//修改文章
$essay=new essay_server($system);
$type=new type_server($system);
if(isset($_GET['getid'])){
	$res=$essay->get_by_id($_GET['getid']);
	$selected_type=$type->get_types_by_eid($_GET['getid']);
	$title=html_entity_decode($res['title'],ENT_NOQUOTES);
	$content=html_entity_decode($res['content'],ENT_NOQUOTES);
	//$system->show_json($res);
	$types=$type->get_list(100);
	$keywords=$res['keywords'];
	include $system->get_view('admin/editor',false);
}elseif(isset($_GET['upid'])){
	if(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['add_type'])&&isset($_POST['remove_type'])){
		$eid=$_GET['upid']+0;
		$essay->update($eid,$_POST['title'],$_POST['content'],$_POST['keywords'],$this->uid);
		$add_type=explode(',',$_POST['add_type']);
		foreach ($add_type as $v) {
			if(!$v) continue;
			if($v)$type->set_essay_type($eid,(int)$v);
		}
		$remove_type=explode(',',$_POST['remove_type']);
		foreach ($remove_type as $v) {
			if(!$v) continue;
			if($v)$type->remove_essay_type($eid,(int)$v);
		}
		echo 1;
	}else{
		$system->show_json(array('error'=>1));
	}
}else{
	$page=isset($_GET['page'])?$_GET['page']+0:1;
	$essay=new essay_server($system);
	$fenye=new fenye_tool($essay->get_page(10),$page,'?id=12&page=');
	if($ye=$fenye->get()){
		$list=$essay->get_list($page,10);
		$types=$type->get_list(100);
		include $system->get_view('admin/essay_list',false);
	}
}