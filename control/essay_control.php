<?php
class essay_control{
	public function __call($id,$c){
		$id+=0;
		header("HTTP/1.1 307 Temporary Redirect"); 
		header('location: '.URLROOT.'essay/id/'.$id);
	}
	public function id_page($system,$id){
		$server=new essay_server($system);
		$essay=$server->get_by_id($id);
		if(!$essay){
			echo '404';return;
		}
		if($essay['display']){
			$system->show_head($essay['title'].'——星星站点博客',array(),$essay['keywords']);
			$type=new type_server($system);
			$types=$type->get_types_by_eid($id);
			$remark=new remark_server($system);
			$remarks=$remark->get($id);
			$ob=[];//array('img_url'=>$system->ini_get('imgs_url'));
			// if(strpos($_SERVER['HTTP_USER_AGENT'], 'baidu.com')) $essay['content']='<p style="color:#F00;">识别为蜘蛛抓取，markdowm在服务器端快速解析，部分格式可能不能完全显示。</p>'.marked_tool::marked($essay['content'],$ob);
			$cache=new \cache_tool($system->ini_get("cache_dir").'essay.'.$essay['id'],$essay['update_time']);
			$essay['content_marked']=$cache->fun_cache([$server,"marked_essay"],[$essay['content']]);
			include $system->get_view('essay');
			$system->show_foot();
		}else{
			$error_info='帖子已被隐藏，可能正在编辑';
			//echo '<div class="left_div"><div class="h">访问错误</div>此文章已被隐藏，可能正在编辑中</div>';
			include $system->get_view('error');
		}
	}
	public function list_page($system,$c){
		$c+=0;
		$essay=new essay_server($system);
		$fenye=new fenye_tool($essay->get_page(),$c,URLROOT.'essay/list/');
		if($ye=$fenye->get()){
			$list=$essay->get_list($c);
			$system->show_head('文章列表——星星站点博客');
			include $system->get_view('essay_list');
			$system->show_foot();
		}
	}
	public function send_remark_page($system,$eid){
		if(!isset($_POST['key'])&&!isset($_POST['content'])&&!$_POST['content']) return;
		//var_dump($eid,$_POST);
		if(($uid=$system->succ->is_login($_POST['key'],$_SERVER['HTTP_USER_AGENT']))===false) return;
		$remark=new remark_server($system);
		//var_dump($uid);exit;
		$remark->set($eid,$uid,$_POST['content']);
		echo 1;
	}
}