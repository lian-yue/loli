<?php

return [
	// 区域代码
	'region' => 'ZH',

	// 语言
	'language' => 'zh-CN',

	// 替换的语言
	'language_replace' => [
		'zh' => 'zh-CN',
	],

	// 语言列表
	'language_lists' => [
		'en' => 'English',
		'en-US' => 'English (United States)',
		'zh-CN' => '中文简体',
		// 'zh-TW' => '中文繁體',
	],

	'language_file' => dirname(__DIR__) . '/resources/languages/{language}/{group}.php',

	'language_timezone' => [
		'zh-CN' => ['Asia/Shanghai'],
	],

	// 时区
	'timezone' => 'Asia/Shanghai',
];
