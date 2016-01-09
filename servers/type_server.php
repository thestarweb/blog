<?php
class type_server{
	private $system;
	const table='@%_type';
	const link_table='@%_type_link';
	//private $link_table='type_link';
	public function __construct($system){
		$this->system=$system;
	}
	public function get_list($limit=5,$page=1){
		$limit+=0;$page+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`name` FROM `'.self::table.'` limit '.$limit*($page-1).','.$limit);
	}
	public function how_many(){
		$res=$this->system->db()->do_SQL('SELECT COUNT(`id`) AS `nu` FROM `'.self::table.'`');
		return $res[0]['nu'];
	}
	public function get_clist($id){
		$id+=0;
		return $this->system->db()->do_SQL('SELECT `id`,`name` FROM `'.self::table.'` WHERE `pid`='.$id);
	}
	public function get_list_p($limit=5,$page=1){
		$limit+=0;$page+=0;
		return $this->system->db()->do_SQL('SELECT `s`.`id`,`s`.`name`,`p`.`id` AS `p_id`,`p`.`name` AS `p_name` FROM `'.self::table.'` AS `s`LEFT JOIN `'.self::table.'` AS `p` ON `s`.`pid`=`p`.`id` limit '.$limit*($page-1).','.$limit);
	}
	public function get_essay($tid,$page=1,$limit=20){
		$limit+=0;$page+=0;
		$tid+=0;
		return $this->system->db()->do_SQL('SELECT * FROM `'.self::link_table.'` AS `l` JOIN `'.essay_server::table.'` AS `e` ON `l`.`eid`=`e`.`id` WHERE `l`.`tid`='.$tid.' ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
	public function get_essay_page($id,$limit=20){
		$id+=0;$limit+=0;
		$res=$this->system->db()->do_SQL('SELECT COUNT(`tid`) AS `nu` FROM `'.self::link_table.'` WHERE `tid`='.$id);
		//var_dump($res);exit;
		return ceil($res[0]['nu']/$limit);
	}
	public function get_info_by_id($id){
		$id+=0;
		$res=$this->system->db()->do_SQL('SELECT `t`.`pid`,`t`.`name`,`t`.`id`,`t`.`info` FROM `'.self::table.'` as `t` WHERE `id`='.$id);
		return $res[0];
	}
	public function get_info_by_ename($name){
		$res=$this->system->db()->u_do_SQL('SELECT `t`.`pid`,`t`.`name`,`t`.`id`,`t`.`info` FROM `'.self::table.'` as `t` WHERE `ename`=?',array($name));
		return $res?$res[0]:array();
	}
	public function get_types_by_eid($eid){
		$eid+=0;
		return $this->system->db()->do_SQL('SELECT * FROM `'.self::link_table.'` AS `l` JOIN `'.self::table.'` AS `t` ON `l`.`tid`=`t`.`id` WHERE `l`.`eid`='.$eid);
	}
	public function add($name,$pid=0,$info=null){
		$this->system->db()->u_do_SQL('INSERT INTO `'.self::table.'`(`name`,`pid`,`info`) VALUE(?,?,?)',array($name,$pid,$info));
	}
	public function remove($id){
		$id+=0;
		$this->system->db()->do_SQL('UPDATE `'.self::table.'` SET `pid`=0 WHERE `pid`='.$id);
		$this->system->db()->do_SQL('DELETE FROM `'.self::link_table.'` WHERE `tid`='.$id);
		$this->system->db()->do_SQL('DELETE FROM `'.self::table.'` WHERE `id`='.$id);
	}
	public function set_essay_type($eid,$tid){
		$eid+=0;$tid+=0;
		$this->system->db()->do_SQL('INSERT INTO `'.self::link_table.'`(`eid`,`tid`) VALUE('.$eid.','.$tid.')');
	}
	public function remove_essay_type($eid,$tid){
		$eid+=0;$tid+=0;
		$this->system->db()->do_SQL('DELETE FROM `'.self::link_table.'` WHERE `eid`='.$eid.' AND `tid`='.$tid);
	}
	public function update($id,$name,$info,$pid){
		$this->system->db()->u_do_SQL('UPDATE `'.self::table.'` SET `name`=?,`info`=?,pid=? WHERE `id`=?',array($name,$info,$pid,$id));
	}
}