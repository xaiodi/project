<?php
	header('content-type:text/html;charset=utf-8');

class DB
{
	private $host;
	private $pass;
	private $port;
	private $user;
	private $charset;
	private $dbname;
	static private $instance = null;
	private $link;
	static public function getInstance($conf)
	{
		if (!self::$instance instanceof self) {
			self::$instance = new self($conf);
		}
		return self::$instance;
	}
	private function __clone(){}
	private function __construct($conf=[])
	{
		$this->init($conf);
		$this->my_content();
		$this->my_charset();
		$this->my_dbname();
	}
	private function init($conf)
	{
		$this->host = isset($conf['host']) ? $conf['host'] :"localhost";
		$this->user = isset($conf['user']) ? $conf['user'] :"root";
		$this->pass = isset($conf['pass']) ? $conf['pass'] :"user";
		$this->charset = isset($conf['charset']) ? $conf['charset'] :"utf8";
		$this->dbname = isset($conf['dbname']) ? $conf['dbname'] :"hn61";
		$this->port = isset($conf['port']) ? $conf['port'] :"3306";
	}
	private function my_content()
	{
		$link = @mysql_connect("$this->host:$this->port",$this->user,$this->pass) ;
		if (!$link) {
			echo '错误信息为:'.mysql_error();
			echo '错误编号为:'.mysql_errno();
			return false;
		}else{
			$this->link = $link;
		}
	}
	public function my_query($sql = '')
	{
		$res = mysql_query($sql);
		if (!$res) {
			echo '错误信息为:'.mysql_error();
			echo '错误编号为:'.mysql_errno();
			return false;
		}
		return $res;
	}
	public function fetchAll($sql)
	{
		$rows = [];
		if ($res = $this->my_query($sql)) {
			while ($row = mysql_fetch_assoc($res)) {
				$rows[] = $row;
			}
			mysql_free_result($res);
			return $rows;
		}else{
			return false;
		}
	}
	public function fetchrow($sql)
	{
		if ($res = $this->my_query($sql)) {
			$row = mysql_fetch_assoc($res);
			mysql_free_result($res);
			return $row;
		}else{
			return false;
		}
	}
	public function fetchClumn($sql)
	{
		if ($res = $this->my_query($sql)) {
			$row = mysql_fetch_row($res);
			mysql_free_result($res);
			return isset($row[0]) ? $row[0] : false;
		}else{
			return false;
		}
	}
	private function my_charset()
	{
		$sql = "set names ".$this->charset;
		$this->my_query($sql);
	}
	private function my_dbname()
	{
		$sql = "use ".$this->dbname;
		$this->my_query($sql);
	}
	public function __destruct()
	{
		mysql_close($this->link);
	}
}









$conf = [
	'host' =>'localhost',
	'pass' =>'user',
	'user' =>'root',
	'dbname'=>'hn61',
	'charset'=>'utf8',
	'port'=>'3306'
];
$db = DB::getInstance($conf);

//var_dump($db);

$sql = "select * from student where id=50";
var_dump($db->fetchClumn($sql));


