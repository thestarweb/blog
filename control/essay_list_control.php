<?php
class essay_list_control{
	public function __call($page,$can){
		$system=$can[0];
		$page+=0;
		if($page<1) $page=1;
		header("HTTP/1.1 301 Moved Permanently"); 
		header('location: '.URLROOT.'essay/list/'.$page);
	}
}