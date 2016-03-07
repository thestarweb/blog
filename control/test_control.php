<?php
class test_control{
	public function index_page($system){
		var_dump($system->db()->do_SQL("show variables like '%char%'; "));
	}
	public function hello_page($system){
		$c=new cache_tool('./test.cache');
		echo '你好';
		if($c->start_page_cache()){
			echo "\n<h1>huo\ni</h1>";
			$c->end_page_cache();
		}
		echo 'dni';
		$c=new cache_tool('./test1.cache');
		if($c->start_page_cache()){
			echo '<h1>abc</h1>';
			$c->end_page_cache();
		}
	}
}