<?php
class remark_server{
	private $system;
	const table='@%_remark';
	//private $link_table='type_link';
	public function __construct($system){
		$this->system=$system;
	}
	public function set($eid,$uid,$content){
		return $this->system->db()->u_do_SQL('INSERT INTO `'.self::table.'`(`eid`,`uid`,`time`,`content`) VALUE(?,?,?,?)',array($eid,$uid,time(),htmlentities($content,ENT_NOQUOTES,'utf-8')));
	}
	public function get($eid,$page=1,$limit=10){
		$eid+=0;$limit+=0;
		return $this->system->db()->do_SQL('SELECT `uid`,`time`,`content` FROM `'.self::table.'` WHERE `eid`='.$eid.' ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
	public function get_all($page=1,$limit=10){
		$limit+=0;
		return $this->system->db()->do_SQL('SELECT `uid`,`time`,`content` FROM `'.self::table.'` ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
}