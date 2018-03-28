<?php

class Cacher {

	/**
	 * базовое имя кеша
	 */
	const BASE_CACHE_NAME = 'days_count';
	/**
	 * @var ICacher
	 */
	private $driver;
	/**
	 * @var Cacher
	 */
	protected static $instance;

	public function __construct($forceInit = false) {

		if (!$forceInit) {
			throw new Exception('Создание объекта напряму невозможно!');
		}

		$this->initAdapter();

	}

	public static function getInstance() {

		if (is_null(self::$instance)) {
			self::$instance = new Cacher(true);
		}

		return self::$instance;

	}

	protected function makeKey($key) {

		return $key;

	}

	protected function initAdapter() {

		require_once  'Cachers/MemcacheCacher.php';
		$this->driver = new MemcacheCacher(self::BASE_CACHE_NAME);
		if ($this->driver->isAvailable()) {
			return;
		}

		require_once  'Cachers/FileCacher.php';
		$this->driver = new FileCacher(self::BASE_CACHE_NAME);
		if ($this->driver->isAvailable()) {
			return;
		}

		require_once  'Cachers/SessionCacher.php';
		$this->driver = new SessionCacher(self::BASE_CACHE_NAME);
		if ($this->driver->isAvailable()) {
			return;
		}

	}

	public static function Set($key, $value) {

		$cache = Cacher::getInstance();
		if (!$cache->driver->isAvailable()) {
			return;
		}

		$cache->driver->set($cache->makeKey($key), $value);

	}

	public static function Get($key) {

		$cache = self::getInstance();
		if (!$cache->driver->isAvailable()) {
			return false;
		}

		return $cache->driver->get($cache->makeKey($key));

	}

	public static function Reset() {

		$cache = self::getInstance();
		if (!$cache->driver->isAvailable()) {
			return false;
		}

		return $cache->driver->reset();

	}

}