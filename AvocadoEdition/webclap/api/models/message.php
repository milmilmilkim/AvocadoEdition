<?php
if (!defined('_WEBCLAP_')) exit;

include_once BASEDIR . 'models/model.php';

class Message extends Model
{
	private $table_name = 'wc_message';
	public $fields = array('id', 'type', 'content', 'ip', 'date');

	public function __construct()
	{
		parent::__construct($this->table_name, $this->fields);
	}

	public function createTable()
	{
		$query = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			type INT(1) NOT NULL DEFAULT 0,
			content TEXT NOT NULL,
			ip INT UNSIGNED NOT NULL,
			date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
			) DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci";
		$this->conn->exec($query);
	}

	/**
	 * 년월로 찾아서 일별 박수(+메시지) 존재하는 날 체크
	 * @return enable
	 */
	public function getDaysByYearAndMonth($date)
	{
		$year = substr($date, 0, 4);
		$month = substr($date, 4, 2);
		$query = "SELECT CAST(DATE_FORMAT(date, '%d') AS UNSIGNED) day
		FROM {$this->table_name}
		WHERE YEAR(date) = :year AND MONTH(date) = :month
		GROUP BY day
		ORDER BY day";
		$field = array('year' => $year, 'month' => $month);
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);

		$result = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($result, intval($row['day']));
		}
		return $result;
	}

	/**
	 * 날짜기준 시간별 박수(+메시지) 개수
	 * @return count
	 */
	public function getHoursByDate($date)
	{
		$query = "SELECT CAST(DATE_FORMAT(date, '%H') AS UNSIGNED) hour, type, COUNT(*) AS cnt
		FROM {$this->table_name}
		WHERE DATE(date) = :date
		GROUP BY hour, type
		ORDER BY hour";
		$field = array('date' => $date);
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);

		$result0 = array();
		$result1 = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if (intval($row['type']) == 0)
				$result0[$row['hour']] = intval($row['cnt']);
			else
				$result1[$row['hour']] = intval($row['cnt']);
		}
		$result = array('clap' => $result0, 'message' => $result1);
		return $result;
	}

	/**
	 * 날짜기준 메시지(박수x) 목록
	 * @return messages
	 */
	public function getDaysByDate($date)
	{
		$query = "SELECT id, content, date
		FROM {$this->table_name}
		WHERE type = true AND DATE(date) = :date
		ORDER BY id";
		$field = array('date' => $date);
		$stmt = $this->conn->prepare($query);
		$stmt->execute($field);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}
