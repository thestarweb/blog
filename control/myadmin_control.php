<?php
class myadmin_control{
	public function __construct($system){
		$key=isset($_POST['key'])?$_POST['key']:(isset($_GET['key'])?$_GET['key']:null);
		//var_dump($key,$_POST,$_GET);exit;
		if($key&&($this->uid=$system->succ->is_login($key,$_SERVER['HTTP_USER_AGENT']))!==false){
			//$this->uid=0;//$login->is_login();
			//var_dump($this);exit;
			$this->admin=new admin_server($system);
			//$this->admin->flash(1);exit;
			if(!$this->type=$this->admin->can_visit($this->uid,isset($_GET['id'])?$_GET['id']:0)){
				echo '你没有权限。如果你本来已经有了权限的，请刷新下裂地权限';
				exit();
			}
		}else{
			require $system->get_view('admin/login');
			exit;
		}
	}
	public function index_page($system){
		//$admin=new admin_server($system);
		$menus=$this->admin->get_list(0,$this->uid);
		require $system->get_view('admin/index');
	}
	public function list_page($system){
			$system->show_json(array('list'=>$this->admin->get_list($_GET['id'],$this->uid)));
	}
	public function view_page($system){
		//var_dump($this);
		if($this->type==1){
		if(file_exists($file='./admin_file/'.$_GET['id'].'.php')){
			include_once $file;
		}else{
			echo '页面丢失';
		}
		}else{
			$this->list_page($system);
		}
	}
	public function flash_page($system){
		$this->admin->flash($this->uid);
	}
}