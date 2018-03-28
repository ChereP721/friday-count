<?php
require_once __DIR__ . '/../CacherInterface.php';

/**
 * класс кеширования в ФС
 *
 * Class Session
 */
class FileCacher implements CacherInterface {

	const BASE_CACHE_DIR = 'cache';
	private $cacheFile;
	private $baseCacheName = 'filesystem_cache';

	public function __construct($baseCacheName = false) {

		if ($baseCacheName) {
			$this->baseCacheName = $baseCacheName;
		}

		$this->cacheFile = $this->getCacheFile();
	}

	protected function getCacheDir() {

		$aDir = __DIR__ . DIRECTORY_SEPARATOR . self::BASE_CACHE_DIR;
		if (is_dir($aDir)) {
			return $aDir;
		}
		if (mkdir($aDir)) {
			return $aDir;
		}

		return false;
	}

	protected function getCacheFile() {

		if (!$cacheDir = $this->getCacheDir()) {
			return false;
		}
		$aFile = $cacheDir . DIRECTORY_SEPARATOR . $this->baseCacheName . '.cache';
		if (file_exists($aFile)) {
			return $aFile;
		}
		if ($fh = fopen($aFile, 'w')) {
			fputs($fh, serialize([]));
			fclose($fh);

			return $aFile;
		}

		return false;
	}

	public function set($key, $value) {

		$arCache = unserialize(file_get_contents($this->cacheFile));
		$arCache[$key] = $value;
		file_put_contents($this->cacheFile, serialize($arCache));

	}

	public function get($key) {

		$arCache = unserialize(file_get_contents($this->cacheFile));

		return $arCache[$key] ?? false;

	}

	public function isAvailable() : bool {

		return !empty($this->cacheFile);
	}

	public function reset() {

		file_put_contents($this->cacheFile, serialize([]));
	}

}