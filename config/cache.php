<?php
return [
	'default' => [

		// 'type' => 'Memory',


		// 'type' => 'File',
		// 'dir' => __DIR__ .'/storage/cache'


		'type' => 'Memcache',
		'servers' => ['127.0.0.1:11211'],

		// 'type' => 'Redis',
		// 'servers' => ['127.0.0.1:6379'],
	],

	// 'memory' => [
	// 	'type' => 'Memory',
	// ],

	// 'file' => [
	// 	'type' => 'File',
	// 	'dir' => __DIR__ .'/storage/cache',
	// ],

	// 'memcache' => [
	// 	'type' => 'Memcache',
	// 	'servers' => ['127.0.0.1:11211'],
	// ],

	// 'redis' => [
	// 	'type' => 'Redis',
	// 	'servers' => ['127.0.0.1:6379'],
	// ],
];
