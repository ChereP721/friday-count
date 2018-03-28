<?php
require_once __DIR__ . '/../CacherInterface.php';

/**
 * класс кеширования в memcache
 *
 * Class Session
 */
class MemcacheCacher implements CacherInterface {

	private $available;
	private $baseCacheName = 'memcache_cache';
	/**
	 * @var Memcached
	 */
	private $memcache;
	protected $server = '127.0.0.1';
	protected $port = '11211';
	protected $expire = 3600;

	public function __construct($baseCacheName = false) {

		if (!class_exists('Memcached')) {
			return $this->available = false;
		}

		$this->memcache = new Memcached;
		$this->memcache->setOption(Memcached::OPT_BINARY_PROTOCOL, true);

		$this->available = @$this->memcache->addServer($this->server, $this->port);

		if ($baseCacheName) {
			$this->baseCacheName = $baseCacheName;
		}

	}

	public function set($key, $value) {

		$this->memcache->set($key, $value, $this->expire);

	}

	public function get($key) {

		return $this->memcache->get($key);
	}

	public function isAvailable() : bool {

		return $this->available;
	}

	public function reset() {

		$this->memcache->flush();
	}

}