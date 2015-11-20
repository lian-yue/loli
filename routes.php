<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-23 10:27:12
/*
/* ************************************************************************** */

return [

	/*
	[
		'model' => ['Model', 'Method'],
		'scheme' => ['http', 'https'],
		'method' => ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'PURGE', 'OPTIONS', 'TRACE'],
		'host' => 'Host:name',
		'path' => 'install',
		'match' => [1 => '\w+'],
	],*/



	// 安装
	[
		'model' => ['Install', '{1}'],
		'path' => 'install/{1?}',
	],


	// 验证码
	[
		'model' => ['Captcha', '{1}'],
		'path' => 'captcha/{1?}',
	],



	// 用户
	[
		'model' => ['User', '{1}Exists'],
		'path' => 'user/{1}Exists',
	],
	[
		'model' => ['User', 'loginView'],
		'method' => ['GET'],
		'path' => 'user',
	],
	[
		'model' => ['User', '{1}View'],
		'method' => ['GET'],
		'path' => 'user/{1}',
	],
	[
		'model' => ['User', '{1}'],
		'method' => ['POST'],
		'path' => 'user/{1}',
	],







	// 管理员
	[
		'model' => ['Admin.{1?}', '{2}'],
		'path' => 'Admin/{1?}/{2?}',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?',2 => '[a-z][A-Za-z]*'],
	],
	[
		'model' => ['Admin.{1?}', 'insert'],
		'method' => ['PUT'],
		'path' => 'Admin/{1?}/insert',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'model' => ['Admin.{1?}', 'update'],
		'method' => ['PATCH'],
		'path' => 'Admin/{1?}/update',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'model' => ['Admin.{1?}', 'delete'],
		'method' => ['DELETE'],
		'path' => 'Admin/{1?}/delete',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
];