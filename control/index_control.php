<?php
class index_control{
	public function index_page($system){
		$system->show_head('星星站点博客——首页');
		$essay=new essay_server($system);//array(array('title'=>'测试','time'=>0,'content'=>'Android GPRS happy logo hello nice class iPhonealways................'));
		$main_push=$essay->get_push(0);
		$essay_list=$essay->get_list_with_content(5);
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