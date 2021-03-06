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
				echo '你没有权限。如果你本来已经有了权限的，请刷新下权限';
				exit();
			}
			//允许全局后台跨域请求
			header('Access-Control-Allow-Origin: *');
		}else{
			require $system->get_view('admin/login',false);
			exit;
		}
	}
	public function index_page($system){
		//$admin=new admin_server($system);
		$menus=$this->admin->get_list(0,$this->uid);
		$postkey=$_POST['key'];
		require $system->get_view('admin/index',false);
	}
	public function list_page($system){
		$system->show_json(array('list'=>$this->admin->get_list($_GET['id'],$this->uid)));
	}
	public function view_page($system){
		//var_dump($this);
		if($this->type==1){
			$apath=$system->ini_get('controls_dir').'admin_file/';
			if(file_exists($file=$apath.$_GET['id'].'.php')){
				if(!isset($_SERVER["isajax"])) include_once $apath.'head.html';
				include_once $file;
				if(!isset($_SERVER["isajax"])) include_once $apath.'foot.html';
			}else{
				echo '未找到页面（页面丢失？错误的连接？）';
			}
		}else{
			$this->list_page($system);
		}
	}
	public function flash_page($system){
		$this->admin->flash($this->uid);
	}
}