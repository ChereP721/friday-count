<?php
$arr1 = [
	'String1',
	'Key1' => 'String2',
	'Key2' => [
		'Subkey1' => 'String3',
		[
			1000,
			'Subsubkey1' => 10000,
			'Subsubkey2' => -1,
			10 => [
				2,
				2,
				3,
			],
		],
		'Subkey2' => 'String4',
	],
	12 => 'dasdasdas'
];

$arr2 = [
	'String22',
	'Key11' => 'String222',
	'Key2' => [
		'Subkey111' => 'String33',
		[
			100,
			'Subsubkey1111' => 100,
			'Subsubkey2' => -100,
			100 => [
				5,
				6,
				7,
			],
		],
		'Subkey22' => 'String44',
		1
	],
	-1,
];

//NOTE: что-то тут не так
function custom_array_merge($array1, $array2 = null) {

	foreach ($array2 as $key => $value) {
		if (is_numeric($key)) {
			$array1[] = $value;
			continue;
		}
		if (!isset($array1[$key])) {
			$array1[$key] = $value;
			continue;
		}
		if (is_array($array1[$key]) && is_array($value)) {
			$array1[$key] = custom_array_merge($array1[$key], $value);
			continue;
		}
		$array1[$key] = $value;
	}

	return $array1;

}
echo '<br>';
print_r(custom_array_merge($arr1, $arr2));

echo '<br>';
print_r(array_merge_recursive($arr1, $arr2));