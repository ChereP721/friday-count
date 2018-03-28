<?php
header("Content-Type: text/plain; charset=utf-8");

ini_set('log_errors', 'on');
$errorReporting = E_ALL;
error_reporting($errorReporting);

require_once 'FridaysClasses/FridaysCalc.php';

$fc = new FridaysCalc();

echo 'Пятниц в текущем году: ' . $fc->calc() . "\r\n";

for ($year = 2000; $year <= 2020; $year++) {
	echo 'Пятниц в ' . $year . ' году: ' . $fc->calc($year) . "\r\n";
}
