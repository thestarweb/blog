<?php
class admin_server{
	/**
		string 管理员可访问节点表
	*/
	const admin_table='@%_admins';
	/**
		string 管理页面表
	*/
	const page_table='@%_admin_pages';
	/**
		存放system类
	*/
	private $system;
	/**
		构造函数，初始化保存system对象
	*/
	public function __construct($system){
		$this->system=$system;
	}
	/**
		获取某菜单下可访问内容的列表
		@id int 节点id
		@uid int 管理员uid
		return array 包含节点连接、是否为菜单、节点名称信息的二维数组
	*/
	public function get_list($id,$uid=0){
		$id+=0;
		if($uid==0){
			$res=$this->system->db()->exec('SELECT `id`,`title`,`is_menu`,`src` FROM `'.self::page_table.'` WHERE `pid`='.$id);
		}else{
			$uid+=0;//echo $uid.'--';exit;
			$res=$this->system->db()->exec('SELECT `page`.`id`,`page`.`title`,`page`.`is_menu`,`page`.`src` FROM `'.self::page_table.'` AS `page` JOIN `'.self::admin_table.'` AS `admin` ON `page`.`id`=`admin`.`pid` WHERE `page`.`pid`='.$id.' AND `admin`.`uid`='.$uid);
		}
		foreach($res as &$v){
			if(!$v['src']){
				$uroot=($_SERVER['SERVER_PROTOCOL']=='HTTP/1.1'?'http://':'https://').$_SERVER['SERVER_NAME'].URLROOT.'myadmin/view?id=';
				$v['src']=$uroot.$v['id'];
			}
		}
		return $res;
	}
	/**
		用于检测管理员（或用户）是否能访问指定的节点（即菜单和页面）。
		@uid int 访问者uid
		@id int 需要访问的节点
		return bool（int） false表示无权限访问，true表示有权限（1表示是页面，2表示是菜单）
	*/
	public function can_visit($uid,$id){
		if($uid===false) return false;
		$uid+=0;$id+=0;
		if($uid===0){
			if($id==0)return 1;
			$res=$this->system->db()->exec('SELECT `is_menu` FROM `'.self::page_table.'` AS `p` WHERE `id`='.$id);
			if($res){
				return $res[0]['is_menu']+1;
			}
		}else{
			if($id==0){
				return $this->system->db()->exec('SELECT `id` FROM `'.self::admin_table.'` WHERE `uid`='.$uid.' LIMIT 1')?2:false;
			}
			$res=$this->system->db()->exec('SELECT `p`.`is_menu` FROM `'.self::admin_table.'` AS `a` JOIN `'.self::page_table.'` AS `p` ON `a`.`pid`=`p`.`id` WHERE `a`.`uid`='.$uid.' AND `p`.`id`='.$id);
			if($res){
				return $res[0]['is_menu']+1;
			}
		}
		return false;
	}
	/**
		用于刷新权限（会删除除管理员赋予的节点以外所有的权限信息并从新获取节点的父节点及其所有子节点的权限）
		@uid int 需要被刷新的uid
		return void
	//权限 1管理员赋予 2继承得到 3连接作用
	*/
	public function flash($uid){
		if($uid==0) return true;
		$uid+=0;
		$db=$this->system->db();
		//清除旧记录
		$db->exec('UPDATE `'.self::admin_table.'` SET `type`=0 WHERE `type`!=1 AND `uid`='.$uid);
		//查询已经授权的列
		$res=$db->exec('SELECT `a`.`pid` AS `id`,`p`.`is_menu` FROM `'.self::admin_table.'` AS `a` LEFT JOIN `'.self::page_table.'` AS `p` ON `a`.`pid`=`p`.`id` WHERE `a`.`type`=1 AND `a`.`uid`='.$uid);
		$list=array();$new=array();
		//需要的连接
		foreach($res as $v){
			//$this->add($uid,$v['pid'],3);
			if($v['is_menu']||$v['id']==0) array_push($list,$v);
		}
		//遍历所以子项
		while($item=array_pop($list)){
			$res=$db->exec('SELECT `id`,`is_menu` FROM `'.self::page_table.'` WHERE `pid`='.$item['id']);
			foreach($res as $v){
				//$this->add($uid,$v['id'],2);
				array_push($new,array($v['id'],2));
				if($v['is_menu']) array_push($list,$v);
			}
		}
		//写入新记录
		$res=$db->exec('SELECT `id` FROM `'.self::admin_table.'` WHERE `type`=0 LIMIT '.count($new));
		while($id=array_pop($res)){
			$insert=array_pop($new);
			$db->exec('UPDATE `'.self::admin_table.'` SET `uid`='.$uid.',`pid`='.$insert[0].',`type`='.$insert[1].' WHERE `id`='.$id['id']);
		}
		$str='INSERT INTO `'.self::admin_table.'`(`uid`,`pid`,`type`) VALUE';
		if($new){
			$f=false;
			foreach($new as $v){
				if($f){
					$str.=',';
				}else{
					$f=true;
				}
				$str.='('.$uid.','.$v[0].','.$v[1].')';
			}
			$db->exec($str);
		}
	}
	/**
		返回所有存在于管理员表中的uid
		return array
	*/
	public function admin_list(){
		return $this->system->db()->exec('SELECT distinct `uid` FROM `'.self::admin_table.'`');
	}
	/**
		获取所有的管理页面
		return array
	*/
	public function get_all_page(){
		return $this->system->db()->exec('SELECT `id`,`pid`,`title` FROM `'.self::page_table.'`');
	}
	/**
		获取一个管理员能访问的节点
		@uid int 管理员uid
		return array
	*/
	public function get_admin_rank($uid){
		$uid+=0;
		return $this->system->db()->exec('SELECT `pid` FROM `'.self::admin_table.'` WHERE `uid`='.$uid);
	}
	/**
		用于增加一个管理员（用户）可访问的节点
		@uid int 管理员id
		@pid int/int array 管理页面
		return null
	*/
	public function add($uid,$pid,$type=1){
		$uid+=0;$type+=0;
		$sth=$this->system->db()->prepare('INSERT INTO `'.self::admin_table.'`(`uid`,`pid`,`type`) VALUE(?,?,?)');
		if(is_array($pid)){
			foreach ($pid as $key => $value) {
				$value+=0;
				$sth->execute([$uid,$value,$type]);
			}
		}else{
			$sth->execute([$uid,$pid,type]);
		}
	}

	/**
		用于移除一个管理员（用户）可访问的节点
		@uid int 管理员id
		@pid int/int array 管理页面
		return null
	*/
	public function remove($uid,$pid){
		$uid+=0;
		$sth=$this->system->db()->prepare('DELETE FROM `'.self::admin_table.'` WHERE `uid`=? AND `pid`=?');
		if(is_array($pid)){
			foreach ($pid as $key => $value) {
				$value+=0;
				$sth->execute([$uid,$value]);
			}
		}else{
			$sth->execute([$uid,$pid]);
		}
	}
}