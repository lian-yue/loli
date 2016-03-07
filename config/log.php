<?php
return [
	'default' => [
		'type' => 'File',
		'path' => dirname(__DIR__) . '/storage/logs/{level}/{group}/{date}/{time}.log',
		'filters' => [],
	],

	// 'memory' => [
	// 	'type' => 'Memory',
	// 	'filters' => [],
	// ],
];
