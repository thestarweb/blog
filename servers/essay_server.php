<?php
class essay_server{
	const sh='div:;p:;br:;a:href;img:src,width,height';
	private $system;
	const table='@%_essay';
	const push_table='@%_push';
	private $table;
	public static $_display=array('隐藏','公开');
	public static function display($i){
		return self::$_display[$i];
//		var_dump(self::$_display);
	}
	public function __construct($system){
		$this->system=$system;
	}
	/**
		获取最新的文章（带正文）
		@limit int 最大获取条数
		return array 包含文章id、标题、发表时间、正文、显示方式信息的二维数组
	*/
	public function get_list_with_content($limit){
		$limit+=0;
		return $this->system->db()->exec('SELECT `id`,`title`,`time`,`content`,`display` FROM `'.self::table.'` ORDER BY `time` DESC limit '.$limit);
	}
	/**
		获取文章列表
		@page int 页码
		@limit int 每页条数
		return array 包含文章id、标题、发表时间、发布者uid、显示方式信息的二维数组
	*/
	public function get_list($page,$limit=20){
		$limit+=0;
		return $this->system->db()->exec('SELECT `id`,`title`,`time`,`sender`,`display` FROM `'.self::table.'` ORDER BY `time` DESC limit '.($page-1)*$limit.','.$limit);
	}
	/**
		获取推送的文章
		@type int 推送类型（区分标识）
		@limit int 最大获取条数
		return array 包含文章id、标题、发表时间、正文、显示方式信息的二维数组
	*/
	public function get_push($type,$limit=10){
		$type+=0;$limit+=0;
		return $this->system->db()->exec('SELECT `e`.`id`,`e`.`title`,`e`.`time`,`e`.`content`,`e`.`display` FROM `'.self::table.'` as `e` JOIN `'.self::push_table.'` ON `e`.`id`=`'.self::push_table.'`.`essay` WHERE `'.self::push_table.'`.`type`='.$type.' LIMIT '.$limit);
	}
	/**
		计算文章页数
		@limit int 每页文章数量
		return int 页数
	*/
	public function get_page($limit=20){
		$limit+=0;
		$res=$this->system->db()->exec('SELECT COUNT(`id`) AS `nu` FROM `'.self::table.'`');
		return ceil($res[0]['nu']/$limit);
	}
	/**
		发布文章
		@title string 文章标题
		@content string 正文
		@sender int 发表人uid
		@display int 显示方式
		@keywords string 关键词（用英文逗号分隔一遍搜索引擎收录）
		return void
	*/
	public function add($title,$content,$sender,$display=1,$keywords=''){
		$title=htmlentities($title,ENT_HTML5,'utf-8');
		$content=htmlentities($content,ENT_HTML5,'utf-8');
		$this->system->db()->u_exec('INSERT INTO `'.self::table.'`(`title`,`time`,`content`,`sender`,`display`,`keywords`) VALUE(?,?,?,?,?,?)',array($title,time(),$content,$sender,$display,$keywords));
	}
	/**
		推送文章
		@eid int 文章id
		@type int 推送类型
		return void
	*/
	public function add_push($eid,$type){
		$eid+=0;$type+=0;
		$this->system->db()->exec('INSERT INTO `'.self::push_table.'`(`type`,`essay`) VALUE('.$type.','.$eid.')');
	}
	/**
	  为文章导入而增加的文章发布方法
	*/
	public function in($title,$content,$uid,$time,$display,$hot){
		//$title=htmlentities($title,ENT_NOQUOTES,'utf-8');
		//$content=htmlentities($content,ENT_NOQUOTES,'utf-8');
		$this->system->db()->u_exec('INSERT INTO `'.self::table.'`(`title`,`time`,`content`,`sender`,`display`) VALUE(?,?,?,?,?)',array($title,$time,$content,$uid,$display));
	}
	/**
		修改一篇文章
		@id int 文章id
		@title string 修改后标题
		@content string 修改后正文
		@keywords string 修改后关键字
		return void
	*/
	public function update($id,$title,$content,$keywords,$uid){
		$this->system->db()->u_exec('UPDATE `'.self::table.'` SET `title`=?,`content`=?,`update_time`=?,`update_by`=?,`keywords`=? WHERE `id`=?',array($title,htmlentities($content,ENT_NOQUOTES,'utf-8'),time(),$uid,$keywords,$id));
	}
	/**
		移除推送
		@eid int 文章id
		@type int 推送类型
		return void
	*/
	public function del_push($eid,$type){
		$this->system->db()->exec('DELETE FROM `'.self::push_table.'` WHERE `essay`='.$eid.' && `type`='.$type);
	}
	/**
		通过文章id获取文章
		@id int 文章id
		return array 包含文章id、标题、发表时间、正文、显示方式信息的一维数组
	*/
	public function get_by_id($id){
		$id+=0;
		$this->system->db()->exec('UPDATE `'.self::table.'` SET `hot`=`hot`+1 WHERE `id`='.$id);//访问增加热度
		$res=$this->system->db()->exec('SELECT `id`,`display`,`title`,`content`,`time`,`sender`,`update_time`,`update_by`,`keywords` FROM `'.self::table.'` WHERE `id`='.$id.' LIMIT 1');
		return $res?$res[0]:null;
	}
	public function marked_essay($body){
		$body=str_replace('{$img_url}', $this->system->ini_get('imgs_url'), $body);
		return \marked_tool::marked_all($body);
	}
	/**
		获取所有文章中最大的id
		return int 最大id
	*/
	public function get_max_id(){
		$res=$this->system->db()->exec('SELECT max(`id`) AS `max` FROM `'.self::table.'`');
		return $res[0]['max'];
	}
	/**
		获取某一段内文章全部信息（为导出准备）
		@start int 起始位置
		@limit int 最大获取条数
		return array 包含所有信息的二维数组
	*/
	public function get_for_out($start,$limit){
		$start+=0;$limit+=0;
		return $this->system->db()->exec('SELECT `id`,`title`,`time`,`content`,`display`,`sender`,`hot` FROM `'.self::table.'` ORDER BY `time` DESC limit '.$start.','.$limit);
	}
	/**
		获取最热门（访问次数最多，未做严格判断仅供参考）的文章
		@limit int 最大获取条数
		return array 包含文章id、标题信息的二维数组
	*/
	public function get_hot_essay($limit=5){
		$limit+=0;
		return $this->system->db()->exec('SELECT `id`,`title` FROM `'.self::table.'` ORDER BY `hot` DESC LIMIT '.$limit);
	}
}