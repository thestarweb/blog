<?php
class frame_control{
	public function index_page($system){
		//require '../succ/succ.php';
		//$login=new succ();
		require $system->get_view('frame');
		//$login->out();
	}
	public function test_page($system){
		echo '<a href="'.URLROOT.'">dhnf</a>';
	}
}