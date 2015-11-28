<?php
class type_control{
	public function __call($id,$c){
		/*$system=$c[0];
		$type=new type_server($system);
		$type_info=$type->get_info_by_id($id);
		$ctypes=$type->get_clist($id);
		$list=$type->get_essay($id);
		//$title_name='xx分类下的文章';
		$system->show_head('分类 '.$type_info['name'].' 的文章');
		require $system->get_view('type');
		$system->show_foot();*/
	}
	public function id_page($system,$c){
		$cs=explode('/',$c);
		$id=$cs[0]+0;
		if(isset($cs[1])){
			$page=$cs[1]+0;
			if($page>0){
				$type=new type_server($system);
				$fenye=new fenye_tool($type->get_essay_page($id),$page,URLROOT.'essay/list/');
				if($ye=$fenye->get()){
					$type_info=$type->get_info_by_id($id);
					$ctypes=$type->get_clist($id);
					$list=$type->get_essay($id,$page);
					$system->show_head('分类 '.$type_info['name'].' 的文章——星星站点博客');
					include $system->get_view('type');
					$system->show_foot();
				}
				/*$maxpage=$type->get_essay_page($id);
				if($page<=$maxpage){
					$type_info=$type->get_info_by_id($id);
					$list=$type->get_essay($id,$page);
					//$title_name='xx分类下的文章';
					$system->show_head('分类 '.$type_info['name'].' 的文章');
					require $system->get_view('type');
					$system->show_foot();*/
					exit;
				//}
			}
		}
		$error_info='<h1>:) 真的什么都没发现啊</h1><br/>请确认您输入的地址是否有误（分类下没有文章也会出现此错误）';
		include $system->get_view('error');
	}
}