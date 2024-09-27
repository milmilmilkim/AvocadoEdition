<?php
if (!defined('_WEBCLAP_')) exit;

try {
	// form 데이터 가져오기
	$data = json_decode(file_get_contents("php://input"));
	$username = trim($data->username);
	$password = trim($data->password);
	$wc_host = trim($data->wc_host);
	$wc_user = trim($data->wc_user);
	$wc_password = trim($data->wc_password);
	$wc_db = trim($data->wc_db);

	// DB 접속정보 파일 생성
	$f = @fopen(DBCONFIG, 'wa+');
	fwrite($f, "<?php\n");
	fwrite($f, "if (!defined('_WEBCLAP_')) exit;\n");
	fwrite($f, "define('WC_HOST', '{$wc_host}');\n");
	fwrite($f, "define('WC_USER', '{$wc_user}');\n");
	fwrite($f, "define('WC_PASSWORD', '{$wc_password}');\n");
	fwrite($f, "define('WC_DB', '{$wc_db}');\n");
	fwrite($f, "?>");
	fclose($f);
	@chmod(DBCONFIG, 0644);

	// 생성한 파일 include
	include_once(DBCONFIG);

	// DB 변수 선언 후 가져와야함
	include_once BASEDIR . 'models/config.php';
	include_once BASEDIR . 'models/message.php';

	// 데이터베이스 접속 & 객체 정의
	$config = new Config();
	$message = new Message();

	// 아이디 유효성 체크
	if ($username == '') throw new Exception('아이디를 입력하세요.');
	if (preg_match("/[^A-Za-z0-9]+/i", $username)) throw new Exception('아이디는 영문자, 숫자만 입력하세요.');
	if (strlen($username) < 4 || strlen($username) > 20) throw new Exception('아이디는 4~20자 내로 입력하세요.');

	// 비밀번호 유효성 체크
	if ($password == '') throw new Exception('비밀번호를 입력하세요.');
	if (preg_match("/[^A-Za-z0-9]+/i", $password)) throw new Exception('비밀번호는 영문자, 숫자만 입력하세요.');
	if (strlen($password) < 4 || strlen($password) > 20) throw new Exception('비밀번호는 4~20자 내로 입력하세요.');
	$password = $config->encrypt($password);

	// 설정 테이블 생성
	$config->createTable();

	// 메시지 테이블 생성
	$message->createTable();

	// 설치여부 확인
	$result = $config->findById(1);
	if (!$result) {
		// 설정 테이블 기본 데이터 생성
		$field = array(
			'username' => $username,
			'password' => $password,
			'wc_title' => '웹박수',
			'wc_main_msg' => '박수 감사합니다!',
			'wc_return_msg' => '박수를 전송했습니다.',
		);
		$config->insert($field);
	} else {
		// 기존 설치자라면
		// 1. 아이디 & 비번 변경
		$field = array(
			'username' => $username,
			'password' => $password,
		);
		$config->updateById(1, $field);

		// 2. charset 변경
		$config->convertTable();
		$message->convertTable();

		// 3. img 컬럼 추가
		if (!$result['wc_main_img']) {
			$config->addImgColumn();
		}
	}

	echo parseJsonSuccess();
} catch (PDOException $e) {
	@unlink(DBCONFIG);
	echo parseJsonError(503, $e->getMessage());
} catch (Exception $e) {
	@unlink(DBCONFIG);
	echo parseJsonError(400, $e->getMessage());
}
