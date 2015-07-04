<?php
class test_control{
	public function index_page($system){
		var_dump($system->db()->do_SQL("show variables like '%char%'; "));
	}
}