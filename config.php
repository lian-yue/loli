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
return [
	'DEBUG' => [
		'is' => true,
		'display' => E_ALL,
	],

	'VIEW' => [
		'dir' => __DIR__ . '/views',
	],

	'MODEL' => [
		//'RBAC' => '',
		//'token' => '',
		//'login' => '',
		//'notLogged' => '',
	],


	'MESSAGE' => [
		'hosts' => ['loli.dev'],
	],

	'LOG' => [
		'type' => 'File',
		'path' => __DIR__ . '/data/logs/$level/$date/$time.log',
		'writes' => [0, 1, 2, 3, 4, 5 ,9],
	],

	'CACHE' => [
		'mode' => 'Memcache',
		'args' => ['127.0.0.1:11211'],

		// 'mode' => 'File',
		// 'args' => ['dir' => __DIR__ .'/data/cache']

		// 'mode' => 'Redis',
		// 'args' => ['127.0.0.1:6379'],
	],

	'STORAGE' => [
		'' => ['dir' => __DIR__ . '/data/'],
	],

	'LOCALIZE' => [
		'file' => __DIR__ . '/localizes/%1$s/%2$s.php',
		'allLanguage' => ['zh-CN' => '简体中文'],
		'language' => 'zh-CN',
		'allTimezone' => ['Asia/Shanghai'],
		'timezone' => 'Asia/Shanghai',
	],

	'ROUTE' => [
		'file' => __DIR__ . '/routes.php',
	],

	'DB' => [
		['protocol' => 'mysql', 'username' => 'root', 'password' => '874654621', 'database' => 'loli'],
	],
	'CURL' => [
		'cookie' => __DIR__. '/data/curl',
	],
	'MEMORY' => [
		'limit' => '512M',
	],

];