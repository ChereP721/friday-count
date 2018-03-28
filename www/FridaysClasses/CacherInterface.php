<?php

/**
 * интерфейс для разных видов кеша
 *
 * Interface ICacher
 */
interface CacherInterface {

	public function __construct($baseCacheName = false);
	
	public function set($key, $value);

	public function get($key);

	public function isAvailable() : bool;

	public function reset();

}