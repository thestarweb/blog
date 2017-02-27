<?php
class remark_server{
	private $system;
	const table='@%_remark';
	//private $link_table='type_link';
	public function __construct($system){
		$this->system=$system;
	}
	/**
		增加一条评论
		@eid int 评论的文章
		@uid int 评论者uid
		@content string 评论内容
		return 执行结果
	*/
	public function set($eid,$uid,$content){
		return $this->system->db()->u_exec('INSERT INTO `'.self::table.'`(`eid`,`uid`,`time`,`content`) VALUE(?,?,?,?)',array($eid,$uid,time(),htmlentities($content,ENT_NOQUOTES,'utf-8')));
	}
	/**
		获取文章的评论
		@eid int 文章id
		@page int 评论页数
		@limit int 每页条数
		return array 包含评论者uid、评论时间、评论内容信息的二维数组
	*/
	public function get($eid,$page=1,$limit=10){
		$eid+=0;$limit+=0;
		return $this->system->db()->exec('SELECT `uid`,`time`,`content` FROM `'.self::table.'` WHERE `eid`='.$eid.' ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
	/**
		获取所用文章的评论
		@page int 评论页数
		@limit int 每页条数
		return array 包含评论者uid、评论时间、评论内容信息的二维数组
	*/
	public function get_all($page=1,$limit=10){
		$limit+=0;
		return $this->system->db()->exec('SELECT `uid`,`time`,`content` FROM `'.self::table.'` ORDER BY `time` DESC LIMIT '.($page-1)*$limit.','.$limit);
	}
}