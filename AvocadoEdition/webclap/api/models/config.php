<?php
if (!defined('_WEBCLAP_')) exit;

include_once BASEDIR . 'models/model.php';

class Config extends Model
{
	private $table_name = 'wc_config';
	public $fields = array('id', 'username', 'password', 'wc_title', 'wc_main_msg', 'wc_return_msg', 'wc_total_cnt', 'wc_cnt_limit', 'wc_main_img');

	public function __construct()
	{
		parent::__construct($this->table_name, $this->fields);
	}

	public function createTable()
	{
		$query = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(255) NOT NULL default '',
			password VARCHAR(255) NOT NULL default '',
			wc_title VARCHAR(255) NOT NULL default '',
			wc_main_msg varchar(255) NOT NULL default '',
			wc_return_msg varchar(255) NOT NULL default '',
			wc_total_cnt INT(11) NOT NULL DEFAULT '0',
			wc_cnt_limit TINYINT(4) NOT NULL DEFAULT '10',
			wc_main_img varchar(255) NOT NULL default 'main.jpg'
			) DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci";
		$this->conn->exec($query);
	}

	public function addImgColumn()
	{
		$query = "ALTER TABLE {$this->table_name} ADD wc_main_img VARCHAR(255) NOT NULL default 'main.jpg'";
		$this->conn->exec($query);
	}

	public function encrypt($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function verify($password, $encrypted)
	{
		return password_verify($password, $encrypted);
	}

	public function increaseCount($id)
	{
		$query = "UPDATE {$this->table_name} SET wc_total_cnt = wc_total_cnt + 1 WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute(array('id' => $id));
		return $stmt->rowCount();
	}
}
