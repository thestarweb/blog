<?php
class index_control{
	public function index_page($system){
		$system->show_head('星星站点博客——首页');
		$essay=new essay_server($system);//array(array('title'=>'测试','time'=>0,'content'=>'Android GPRS happy logo hello nice class iPhonealways................'));
		$main_push=$essay->get_push(0);
		foreach ($main_push as &$v) {
			if($v['display']){
				$cache=new \cache_tool($system->ini_get("cache_dir").'essay.'.$v['id'],$v['update_time']);
				$v['content_marked']=$cache->fun_cache([$essay,"marked_essay"],[$v['content']]);
			}
		}
		$essay_list=[];
		$temp=$essay->get_list_with_content(5);
		foreach ($temp as $v) {
			if($v['display']){
				$cache=new \cache_tool($system->ini_get("cache_dir").'essay.'.$v['id'],$v['update_time']);
				$v['content_marked']=$cache->fun_cache([$essay,"marked_essay"],[$v['content']]);
				$essay_list[]=$v;
			}
		}
		$type=new type_server($system);
		$type_list=$type->get_list();
		require $system->get_view('index');
		$system->show_foot();
	}
	public function about_page($system,$c){
		$system->show_head('关于——星星站点博客');
		include $system->get_view('about');
		$system->show_foot();
	}
}