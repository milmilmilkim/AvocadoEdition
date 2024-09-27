<?php
if (!defined('_WEBCLAP_')) exit;

include_once BASEDIR . 'common/database.php';

class Model
{
	protected $conn;
	private $table_name;
	public $fields = array();

	public function __construct($table, $fields)
	{
		$this->conn = Database::getConnection();
		$this->table_name = $table;
		$this->fields = $fields;
	}

	public function convertTable()
	{
		$query = "ALTER TABLE {$this->table_name}
		CONVERT TO CHARACTER SET utf8mb4
		COLLATE utf8mb4_unicode_ci";
		$this->conn->exec($query);
		$query = "ALTER TABLE {$this->table_name} ENGINE=InnoDB";
		$this->conn->exec($query);
	}

	public function findById($id)
	{
		$query = "SELECT * FROM {$this->table_name} WHERE id = :id";
		$field = array('id' => $id);
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function findOne($field)
	{
		$query = "SELECT * FROM {$this->table_name} WHERE {$this->parameterizeDataForFind($field)} LIMIT 1";
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function insert($field)
	{
		$query = "INSERT INTO {$this->table_name} ({$this->parameterizeDataForInsertKey($field)}) VALUES ({$this->parameterizeDataForInsertValue($field)})";
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);
		return $stmt->rowCount();
	}

	public function updateById($id, $field)
	{
		$query = "UPDATE {$this->table_name} SET {$this->parameterizeDataForUpdate($field)} WHERE id = :id";
		$field['id'] = $id;
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);
		return $stmt->rowCount();
	}

	public function deleteAll()
	{
		$query = "TRUNCATE TABLE {$this->table_name}";
		$this->conn->exec($query);
	}

	// helper
	function parameterizeData($data)
	{
		$str = "";
		foreach ($data as $key => $value) {
			$str .= $key . " = :" . $key;
		}
		return $str;
	}

	function parameterizeDataForUpdate($data)
	{
		$str = "";
		foreach ($data as $key => $value) {
			$str .= $key . " = :" . $key . ", ";
		}
		$str = substr($str, 0, -2);
		return $str;
	}

	function parameterizeDataForFind($data)
	{
		$str = "";
		foreach ($data as $key => $value) {
			$str .= $key . " = :" . $key . " AND ";
		}
		$str = substr($str, 0, -5);
		return $str;
	}

	function parameterizeDataForInsertKey($data)
	{
		$str = "";
		foreach ($data as $key => $value) {
			$str .= $key . ", ";
		}
		$str = substr($str, 0, -2);
		return $str;
	}

	function parameterizeDataForInsertValue($data)
	{
		$str = "";
		foreach ($data as $key => $value) {
			$str .= ":" . $key . ", ";
		}
		$str = substr($str, 0, -2);
		return $str;
	}
}
