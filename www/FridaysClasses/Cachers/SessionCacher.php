<?php
require_once __DIR__ . '/../CacherInterface.php';

/**
 * класс кеширования в сессию
 *
 * Class Session
 */
class SessionCacher implements CacherInterface {

	private $available;
	private $baseCacheName = 'session_cache';

	public function __construct($baseCacheName = false) {

		if ($baseCacheName) {
			$this->baseCacheName = $baseCacheName;
		}
		$this->available = session_start();
	}

	public function set($key, $value) {

		$_SESSION[$this->baseCacheName][$key] = $value;
	}

	public function get($key) {

		return $_SESSION[$this->baseCacheName][$key];
	}

	public function isAvailable() : bool {

		return $this->available;
	}

	public function reset() {

		unset($_SESSION[$this->baseCacheName]);
	}

}