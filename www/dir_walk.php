<?php
header("Content-Type: text/plain; charset=utf-8");

function dirWalk($dir, array &$arConfigFiles) {

	static $denyExt = ['jpg', 'jpeg', 'png'];
	static $configName = 'config';

	$files = scandir($dir);
	if (!$files) {
		return;
	}

	$files = array_diff($files, ['.', '..']);
	if (empty($files)) {
		return;
	}

	foreach ($files as $file) {

		$absFile = $dir . DIRECTORY_SEPARATOR . $file;

		if (is_dir($absFile)) {
			dirWalk($absFile, $arConfigFiles);
			continue;
		}

		if (stripos($file, $configName) === false) {
			continue;
		}

		$ext = pathinfo($absFile, PATHINFO_EXTENSION);
		if (in_array($ext, $denyExt)) {
			continue;
		}

		$arConfigFiles[] = [
			'name'     => $file,
			'path'     => $dir,
			'abs_path' => $absFile,
			'ext'      => $ext,
			'size'     => filesize($absFile),
		];

	}

}

$arConfigFiles = [];
dirWalk("E:\\webserver\\www\\php6\\btk66.ru\\www", $arConfigFiles);
var_dump($arConfigFiles);