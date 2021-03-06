<?php
class type_control{
	public function id_page($system,$c){
		$cs=explode('/',$c);
		$id=$cs[0]+0;
		if(isset($cs[1])){
			$page=$cs[1]+0;
			if($page>0){
				$type=new type_server($system);
				$fenye=new fenye_tool($type->get_essay_page($id),$page,URLROOT.'type/id/'.$id.'/');
				if($ye=$fenye->get()){
					$type_info=$type->get_info_by_id($id);
					$ctypes=$type->get_clist($id);
					$list=$type->get_essay($id,$page);
					$system->show_head('分类 '.$type_info['name'].' 的文章——星星站点博客');
					include $system->get_view('type');
					$system->show_foot();
					exit;
				}
			}
		}else{
			header("HTTP/1.1 307 Temporary Redirect"); 
			header('location: '.URLROOT.'type/id/'.$id.'/1');
			exit;
		}
		$error_info='<h1>:) 真的什么都没发现啊</h1><br/>请确认您输入的地址是否有误（分类下没有文章也会出现此错误）';
		include $system->get_view('error');
	}
	public function name_page($system,$c){
		$cs=explode('/',$c);
		$name=$cs[0];
		if(isset($cs[1])){
			$page=$cs[1]+0;
			if($page>0){
				$type=new type_server($system);
				$type_info=$type->get_info_by_ename($name);if(!$type_info)exit;
				$fenye=new fenye_tool($type->get_essay_page($type_info['id']),$page,URLROOT.'type/name/'.$name.'/');
				if($ye=$fenye->get()){
					$ctypes=$type->get_clist($type_info['id']);
					$list=$type->get_essay($type_info['id'],$page);
					$system->show_head('分类 '.$type_info['name'].' 的文章——星星站点博客');
					include $system->get_view('type');
					$system->show_foot();
					exit;
				}
			}
		}else{
			header("HTTP/1.1 301 Moved Permanently"); 
			header('location: '.URLROOT.'type/name/'.$name.'/1');
			exit;
		}
		$error_info='<h1>:) 真的什么都没发现啊</h1><br/>请确认您输入的地址是否有误（分类下没有文章也会出现此错误）';
		include $system->get_view('error');
	}
	public function list_page($system,$page){
		if(!$page){
			header("HTTP/1.1 301 Moved Permanently"); 
			header('location: '.URLROOT.'type/list/1');
			exit;
		}
		$type=new type_server($system);
		$fenye=new fenye_tool($type->how_many(),$page,URLROOT.'type/list/',10);
		$system->show_head('分类——星星站点博客');
		if($ye=$fenye->get()){
			$list=$type->get_list(10,$page);
		}else{
			$list=[];
		}
		include $system->get_view('type_list');
		$system->show_foot();
	}
}