<?php
class type_server{
	private $system;
	const table='@%_type';
	const link_table='@%_type_link';
	//private $link_table='type_link';
	public function __construct($system){
		$this->system=$system;
	}
	/**
		获取分类列表
		@limit int 每页条数
		@page int 页码
		return array 包含分类id和分类名称的二维数组
	*/
	public function get_list($limit=5,$page=1){
		$limit+=0;$page+=0;
		return $this->system->db()->exec('SELECT `id`,`name` FROM `'.self::table.'` limit '.$limit*($page-1).','.$limit);
	}
	/**
		获取有多少个分类
		return int 分类数
	*/
	public function how_many(){
		$res=$this->system->db()->exec('SELECT COUNT(`id`) AS `nu` FROM `'.self::table.'`');
		return $res[0]['nu'];
	}
	/**
		获取子分类
		@id int 父分类
		return array 包含分类id和分类名称的二维数组
	*/
	public function get_clist($id){
		$id+=0;
		return $this->system->db()->exec('SELECT `id`,`name` FROM `'.self::table.'` WHERE `pid`='.$id);
	}
	/**
		获取自身及父分类信息？
	*/
	public function get_list_p($limit=5,$page=1){
		$limit+=0;
		return $this->system->db()->exec('SELECT `s`.`id`,`s`.`name`,`p`.`id` AS `p_id`,`p`.`name` AS `p_name` FROM `'.self::table.'` AS `s`LEFT JOIN `'.self::table.'` AS `p` ON `s`.`pid`=`p`.`id` limit '.$limit*($page-1).','.$limit);
	}
	/**
		获取分类下的文章
		@tid int 分类id
		@page int 页码
		@limit int 每页文章数
		return array
	*/
	public function get_essay($tid,$page=1,$limit=20){
		$limit+=0;
		$tid+=0;
		return $this->system->db()->exec('SELECT * FROM `'.self::link_table.'` AS `l` JOIN `'.essay_server::table.'` AS `e` ON `l`.`eid`=`e`.`id` WHERE `l`.`tid`='.$tid.' ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
	/**
		获取一个分类下的文章页数
		@id int 分类id
		@limit int 每页文章数
		return int
	*/
	public function get_essay_page($id,$limit=20){
		$id+=0;$limit+=0;
		$res=$this->system->db()->exec('SELECT COUNT(`tid`) AS `nu` FROM `'.self::link_table.'` WHERE `tid`='.$id);
		//var_dump($res);exit;
		return ceil($res[0]['nu']/$limit);
	}
	/**
		通过分类的id获取分类信息
		@id分类id
		return array
	*/
	public function get_info_by_id($id){
		$id+=0;
		$res=$this->system->db()->exec('SELECT `t`.`pid`,`t`.`name`,`t`.`id`,`t`.`info` FROM `'.self::table.'` as `t` WHERE `id`='.$id);
		return $res[0];
	}
	/**
		通过分类名称获取分类
		@name string 分类名称
		return array 分类信息数组
	*/
	public function get_info_by_ename($name){
		$res=$this->system->db()->u_exec('SELECT `t`.`pid`,`t`.`name`,`t`.`id`,`t`.`info` FROM `'.self::table.'` as `t` WHERE `ename`=?',array($name));
		return $res?$res[0]:array();
	}
	/**
		获取文章的所有分类
		@eid int 文章id
		return array 所有的分类的所有信息二维数组
	*/
	public function get_types_by_eid($eid){
		$eid+=0;
		return $this->system->db()->exec('SELECT * FROM `'.self::link_table.'` AS `l` JOIN `'.self::table.'` AS `t` ON `l`.`tid`=`t`.`id` WHERE `l`.`eid`='.$eid);
	}
	/**
		增加一个分类
		@name string 分类名
		@info string 分类介绍
		@pid int 父分类id
		return void
	*/
	public function add($name,$pid=0,$info=null){
		$this->system->db()->u_exec('INSERT INTO `'.self::table.'`(`name`,`pid`,`info`) VALUE(?,?,?)',array($name,$pid,$info));
	}
	/**
		删除一个分类（慎重使用）
		@id int 需要删除的分类的id
		return void
	*/
	public function remove($id){
		$id+=0;
		$this->system->db()->exec('UPDATE `'.self::table.'` SET `pid`=0 WHERE `pid`='.$id);//把所有子分类设置为无父分类
		$this->system->db()->exec('DELETE FROM `'.self::link_table.'` WHERE `tid`='.$id);
		$this->system->db()->exec('DELETE FROM `'.self::table.'` WHERE `id`='.$id);
	}
	/**
		设置文章的分类（严格来说是增加，或者是说将文章和分类联系起来）
		@eid int 文章id
		@tid int 分类id
		return void
	*/
	public function set_essay_type($eid,$tid){
		$eid+=0;$tid+=0;
		$this->system->db()->exec('INSERT INTO `'.self::link_table.'`(`eid`,`tid`) VALUE('.$eid.','.$tid.')');
	}
	/**
		移除文章的分类（取消关联）
		@eid int 文章id
		@tid int 分类id
		return void
	*/
	public function remove_essay_type($eid,$tid){
		$eid+=0;$tid+=0;
		$this->system->db()->exec('DELETE FROM `'.self::link_table.'` WHERE `eid`='.$eid.' AND `tid`='.$tid);
	}
	/**
		更新分类信息
		@id int 需要更新信息的分类id
		@name string 修改后分类名
		@info string 修改后的分类介绍
		@pid int 修改后的父分类id
		return void
	*/
	public function update($id,$name,$info,$pid){
		$this->system->db()->u_exec('UPDATE `'.self::table.'` SET `name`=?,`info`=?,pid=? WHERE `id`=?',array($name,$info,$pid,$id));
	}
}