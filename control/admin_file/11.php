<?php
if(isset($_POST['title'])&&isset($_POST['content'])&&isset($_POST['add_type'])){
	$essay=new essay_server($system);
	$essay->add($_POST['title'],$_POST['content'],$this->uid,1,$_POST['keywords']);
	$eid=$essay->get_max_id();
	$types=explode(',',$_POST['add_type']);
	$type=new type_server($system);
	foreach ($types as $v){
		if($v==="") continue;
		$v+=0;
		if($v)$type->set_essay_type($eid,$v);
	}
	echo 'ok';
}else{
	$type=new type_server($system);
	$types=$type->get_list(100);
	include $system->get_view('admin/add_essay',false);
}