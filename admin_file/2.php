<?php
$type=new type_server($system);
switch (isset($_GET['doing'])?$_GET['doing']:'') {
	case 'add':
		# code...
		break;
	case 'select':
		$_GET['tid']=isset($_GET['tid'])?$_GET['tid']:0;
		$info=$_GET['tid']!=0?$type->get_info_by_id($_GET['tid']):array('name' =>'root','info'=>'这是根分类，便于管理使用','id'=>0);;
		if($info) $clist=$type->get_clist($_GET['tid']);
		include $system->get_view('admin/type_info');
		break;
	case 'delete':
		# code...
		break;
	default:
		$list=$type->get_list_p(20);
		$surl=$system->url_addget('doing','select');
		include $system->get_view('admin/type_list');
		//echo  $system->url_addget('doing','select');
		break;
}