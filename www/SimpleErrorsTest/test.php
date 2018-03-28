<?
//дамп БД для теста
//-- ----------------------------
//-- Table structure for `admins`
//					   -- ----------------------------
//DROP TABLE IF EXISTS `admins`;
//CREATE TABLE `admins` (
//`login` varchar(20) NOT NULL DEFAULT '',
//  `password` varchar(32) DEFAULT NULL,
//  PRIMARY KEY (`login`)
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;
//
//-- ----------------------------
//-- Records of admins
//-- ----------------------------
//INSERT INTO `admins` VALUES ('test2', '123');
//
//
//-- ----------------------------
//-- Table structure for `priviliges`
//					   -- ----------------------------
//DROP TABLE IF EXISTS `priviliges`;
//CREATE TABLE `priviliges` (
//`name` varchar(20) NOT NULL,
//  `type` int(4) NOT NULL,
//  PRIMARY KEY (`name`,`type`)
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;
//
//-- ----------------------------
//-- Records of priviliges
//-- ----------------------------
//INSERT INTO `priviliges` VALUES ('', '1');
//INSERT INTO `priviliges` VALUES ('', '2');
//INSERT INTO `priviliges` VALUES ('exec', '2');
//
//
//-- ----------------------------
//-- Table structure for `users`
//					   -- ----------------------------
//DROP TABLE IF EXISTS `users`;
//CREATE TABLE `users` (
//`login` varchar(20) NOT NULL DEFAULT '',
//  `password` varchar(32) DEFAULT NULL,
//  `usr_deleted` tinyint(1) NOT NULL DEFAULT '0',
//  `usr_system` tinyint(1) unsigned NOT NULL DEFAULT '0',
//  PRIMARY KEY (`login`)
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;
//
//-- ----------------------------
//-- Records of users
//-- ----------------------------
//INSERT INTO `users` VALUES ('test1', '123', '1', '0');
//INSERT INTO `users` VALUES ('test2', '123', '0', '1');
//INSERT INTO `users` VALUES ('test3', '123', '1', '1');
//INSERT INTO `users` VALUES ('test4', '123', '0', '0');



/**
 * проанализировать корректность представленных классов и их использования, посоветовать пути решения проблем синтаксис php5
 *
 * ed - функция вывода переменных на экран
 * AdminParent - класс, дающий возможность использовать protected свойство adapter - адаптер к БД (считаем все его методы корректными)
 * DI - класс контейнера зависимостей
 *
 * Class systemUtils
 */
class SystemUtils extends AdminParent {

	private $type = 1;

	function __construct($type) {
	
		parent::__construct();

		$this->type = (int)$type;
	}

	function getPriviliges() {

		$query = "SELECT * FROM priviliges WHERE type = '" . $this->type . "'";
		$priviliges = $this->adapter->getSqlResultArray($query);

		return $priviliges;

	}

}

class SystemTest extends AdminParent {

	private $sysUsers = [];
	private $priviliges = [];
	private $adminAccess = false;

	function __construct($DI) {

		parent::__construct();

		$this->sysUsers = $this->getUserList(true);
		$this->DI = $DI;
		$this->DI->set('priviliges', function() {
			return SystemUtils::getPriviliges();
		});

	}

	function getUserList($systemOnly = false) {

		static $list;

		if (!empty($list)) return $list;

		$query = "
			SELECT *
			FROM users
			WHERE usr_deleted = 0
		";
		if ($systemOnly) $query .= " AND usr_system = 1";

		$list = $this->adapter->getSqlResultArray($query);

		return $list;

	}

	function checkAdminAuth() {

		$query = "
			SELECT *
			FROM admins
			WHERE login = '" . $_GET["login"] . "' AND password = '" . $_GET["password"] . "'
			LIMIT 1
		";
		$admin = $this->adapter->getSqlResult($query);

		if (!empty($admin)) {

			return $this->adminAccess = true;

		}

		return false;

	}
	
	public function __get($name) {
		
		if ($this->DI != null && $this->DI->exists($name)) {
			return $this->DI->get($name);
		}

		return null;
	}


}

$DI = new DI();

$testObj = new SystemTest($DI);

$userList = $testObj->getUserList();
$admin = $testObj->checkAdminAuth();

ed($userList);
ed((int)$admin);
ed($testObj->priviliges);