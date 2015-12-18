<?php
//修改文章
$essay=new essay_server($system);
$type=new type_server($system);
if(isset($_POST['getid'])){
	$res=$essay->get_by_id($_POST['getid']);
	$res['types']=$type->get_types_by_eid($_POST['getid']);
	$res['content']=str_replace('%','%25',html_entity_decode($res['content'],ENT_NOQUOTES));
	$system->show_json($res);
}elseif(isset($_POST['upid'])){
	if(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['add_type'])&&isset($_POST['remove_type'])){
		$eid=$_POST['upid']+0;
		$essay->update($eid,$_POST['title'],$_POST['content']);
		$add_type=explode(',',$_POST['add_type']);
		var_dump($add_type);
		foreach ($add_type as $v) {
			$v+=0;
			if($v)$type->set_essay_type($eid,$v);
		}
		$remove_type=explode(',',$_POST['remove_type']);
		var_dump($remove_type);
		foreach ($remove_type as $v) {
			$v+=0;
			if($v)$type->remove_essay_type($eid,$v);
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
		$all_types=$type->get_list(100);
		include $system->get_view('admin/essay_list');
	}
}