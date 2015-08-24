<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-21 14:41:21
/*
/* ************************************************************************** */
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-02-25 05:40:48
/*	Updated: UTC 2015-02-27 12:52:10
/*
/* ************************************************************************** */
$_SERVER['LOLI'] = [
	'DEBUG' => [
		'is' => true,
		'display' => E_ALL,
	],

	'VIEW' => [
		'dir' => __DIR__ . '/views',
	],

	'MESSAGE' => [
		'hosts' => ['loli.dev'],
	],

	'LOG' => [
		'type' => 'File',
		'path' => __DIR__ . '/data/logs/$date/$level-$time.log',
	],

	'CACHE' => [
		'type' => 'Memcache',
		'args' => ['127.0.0.1:11211'],
	],

	'LOCALIZE' => [
		'allLanguage' => ['zh-CN' => '简体中文'],
		'language' => 'zh-CN',
		'allTimezone' => ['Asia/Shanghai'],
		'timezone' => 'Asia/Shanghai',
	],

	'ROUTE' => [
		'file' => __DIR__ . '/routes.php',
		'host' => ['qq'],
	],

	'DB' => [

	],
	'MEMORY' => [
		'limit' => '512M',
	],
];