<?php
	$s=new essay_server($system);
	if(isset($_POST['doing'])&&isset($_POST['eid'])&&isset($_POST['type'])){
		switch ($_POST['doing']) {
			case 'add':
				$s->add_push($_POST['eid'],$_POST['type']);
				break;
			case 'del':
				$s->del_push($_POST['eid'],$_POST['type']);
				break;
			
			default:
				echo '参数错误';
				break;
		}
	}else{
		$e=$s->get_push(0);
		include $system->get_view('admin/push',false);
	}