<?php
require_once 'Cacher.php';

/**
 * класс для расчета количества дней в году
 *
 * Class FridaysCalc
 */
class FridaysCalc {

	/**
	 * количество дней в году
	 * @var int
	 */
	private $daysInYear = 365;
	/**
	 * количество дней в неделю
	 * @var int
	 */
	private $daysInWeek = 7;
	/**
	 * количество секунд в дне
	 * @var int
	 */
	private $secondsInDay = 24 * 3600;
	/**
	 * статическая переменная для кеширования
	 * @var array
	 */
	static private $arFridayCount = [];

	/**
	 * метод расчета количества дней недели в году
	 *
	 * @param int $year
	 * @param int $dayOfWeek
	 *
	 * @return float
	 */
	public function calc(int $year = 0, int $dayOfWeek = 5) {

		if (!$year) {
			$year = (int)date('Y');
		}

		if ($countFriday = $this->getCachedCount($year)) {
			return $countFriday;
		}

		$daysInYear = $this->daysInYear + date('L', mktime(0,0,0,1,1, $year));
		$lastDaysCount = $daysInYear % $this->daysInWeek;
		$countFriday = floor($daysInYear / $this->daysInWeek);

		$timeEnd = strtotime(($year + 1) . '-01-01 00:00:00');
		$timeBegin = $timeEnd - $lastDaysCount * $this->secondsInDay;

		if (date('w', $timeBegin) <= $dayOfWeek && (date('w', $timeEnd) > $dayOfWeek || date('w', $timeEnd) < date('w', $timeBegin))) {
			$countFriday++;
		}

		$this->cacheCount($year, $countFriday);

		return $countFriday;

	}

	/**
	 * проверка статического кеша
	 *
	 * @param $year
	 *
	 * @return bool|int
	 */
	protected function getCachedCount(int $year) {

		if (isset(self::$arFridayCount[$year])) {
			echo "NOTE: сработал статический кеш!\r\n";
			return self::$arFridayCount[$year];
		}

		if ($count = Cacher::Get($year)) {
			echo "NOTE: сработал внешний кеш!\r\n";
			return self::$arFridayCount[$year] = $count;
		}

		return false;

	}

	/**
	 * кеширование рассчитанного количества
	 *
	 * @param int $year
	 * @param int $count
	 */
	protected function cacheCount(int $year, int $count) {

		self::$arFridayCount[$year] = $count;
		Cacher::Set($year, $count);

	}

	/**
	 * @param int $baseDaysInYear
	 *
	 * @return FridaysCalc
	 */
	public function setBaseDaysInYear($baseDaysInYear) {

		$this->baseDaysInYear = $baseDaysInYear;

		return $this;
	}

	/**
	 * @param int $addDayEveryYear
	 *
	 * @return FridaysCalc
	 */
	public function setAddDayEveryYear($addDayEveryYear) {

		$this->addDayEveryYear = $addDayEveryYear;

		return $this;
	}

	/**
	 * @param int $daysInWeek
	 *
	 * @return FridaysCalc
	 */
	public function setDaysInWeek($daysInWeek) {

		$this->daysInWeek = $daysInWeek;

		return $this;
	}

	/**
	 * @param int $secondsInDay
	 *
	 * @return FridaysCalc
	 */
	public function setSecondsInDay($secondsInDay) {

		$this->secondsInDay = $secondsInDay;

		return $this;
	}

}

