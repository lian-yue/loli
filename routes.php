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

$anyString = '[\x{0020}-\x{002E}\x{0030}-\x{007F}\x{0080}-\x{02FA1F}]+';

return [

	/*
	[
		'model' => ['Model', 'Method'],
		'scheme' => ['http', 'https'],
		'method' => ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'PURGE', 'OPTIONS', 'TRACE'],
		'host' => 'Host:name',
		'path' => 'install',
		'match' => [1 => '\w+'],
		'default' => [],
		'filter' => function(array $params, Loli\Route $route){},
	],*/



	// 安装
	[
		'model' => ['Install', '{1}'],
		'path' => '/install{/{1}?}/',
	],


	// 验证码
	[
		'model' => ['Captcha', '{1}'],
		'path' => '/captcha{/{1}?}/',
	],

	// 用户
	[
		'model' => ['User', '{1}'],
		'path' => '/user{/{1}?}/',
	],





	// 储存
	/*[
		'model' => ['Storage', 'index'],
		'path' => '/storage/',
	],

	[
		'model' => ['Storage', 'item'],
		'path' => '/storage/{userID}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'model' => ['Storage', '{1}'],
		'path' => '/storage/{1}{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'model' => ['Storage', 'insert'],
		'method' => ['PUT'],
		'path' => '/storage{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'model' => ['Storage', 'update'],
		'method' => ['PATCH'],
		'path' => '/storage{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],
	[
		'model' => ['Storage', 'delete'],
		'method' => ['DELETE'],
		'path' => '/storage{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

















	// 百科   动漫 漫画 游戏 小说 音乐 电视剧 电影 角色 经验 教程
	[
		'model' => ['Wiki', 'index'],
		'path' => '/wiki/',
	],
	[
		'model' => ['Wiki', 'search'],
		'path' => '/wiki/search/{search?}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'model' => ['Wiki', 'lists'],
		'path' => '/wiki/category/{category}{/{page}?}/',
		'match' => ['category' => &$anyString],
	],
	[
		'model' => ['Wiki', 'item'],
		'path' => '/wiki/item/{item}{/{page}?}',
		'match' => ['item' => &$anyString, 'page' => '\d+'],
	],

	[
		'model' => ['Wiki', '{1}'],
		'path' => '/wiki{/{1}?}/',
	],

	[
		'model' => ['Wiki', 'insert'],
		'method' => ['PUT'],
		'path' => '/wiki/',
	],

	[
		'model' => ['Wiki', 'update'],
		'method' => ['PATCH'],
		'path' => '/wiki/',
	],
	[
		'model' => ['Wiki', 'delete'],
		'method' => ['DELETE'],
		'path' => '/wiki/',
	],








	// 新闻
	[
		'model' => ['News', 'index'],
		'path' => '/news/',
	],
	[
		'model' => ['News', 'search'],
		'path' => '/news/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'model' => ['News', 'lists'],
		'path' => '/news/category/{category}{/{page}?}/',
		'match' => ['category' => &$anyString, 'page' => '\d+'],
	],
	[
		'model' => ['News', 'item'],
		'path' => '/news/item/{item}{/{page}?}/',
		'match' => ['page' => '\d+'],
	],

	[
		'model' => ['News', '{1}'],
		'path' => '/news/{1}/',
	],

	[
		'model' => ['News', 'insert'],
		'method' => ['PUT'],
		'path' => '/news/',
	],

	[
		'model' => ['News', 'update'],
		'method' => ['PATCH'],
		'path' => '/news/',
	],
	[
		'model' => ['News', 'delete'],
		'method' => ['DELETE'],
		'path' => '/news/',
	],






	// 专题
	[
		'model' => ['Topic', 'index'],
		'path' => '/topic/',
	],
	[
		'model' => ['Topic', 'search'],
		'path' => '/topic/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString],
	],
	[
		'model' => ['Topic', 'item'],
		'path' => '/topic/item/{item}/',
	],



	[
		'model' => ['Topic', '{1}'],
		'path' => '/topic/{1}/',
	],

	[
		'model' => ['Topic', 'insert'],
		'method' => ['PUT'],
		'path' => '/topic/',
	],

	[
		'model' => ['Topic', 'update'],
		'method' => ['PATCH'],
		'path' => '/topic/',
	],
	[
		'model' => ['Topic', 'delete'],
		'method' => ['DELETE'],
		'path' => '/topic/',
	],













	// 视频
	[
		'model' => ['Video', 'index'],
		'path' => '/video/',
	],
	[
		'model' => ['Video', 'search'],
		'path' => '/video/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'model' => ['Video', 'item'],
		'path' => '/video/item/{item}/',
	],


	[
		'model' => ['Video', '{1}'],
		'path' => '/video/{1}/',
	],

	[
		'model' => ['Video', 'insert'],
		'method' => ['PUT'],
		'path' => '/video/',
	],

	[
		'model' => ['Video', 'update'],
		'method' => ['PATCH'],
		'path' => '/video/',
	],
	[
		'model' => ['Video', 'delete'],
		'method' => ['DELETE'],
		'path' => '/video/',
	],









	// 文章
	[
		'model' => ['Text', 'index'],
		'path' => '/text/',
	],
	[
		'model' => ['Text', 'search'],
		'path' => '/text/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'model' => ['Text', 'item'],
		'path' => '/text/item/{item}/',
	],


	[
		'model' => ['Text', '{1}'],
		'path' => '/text/{1}/',
	],

	[
		'model' => ['Text', 'insert'],
		'method' => ['PUT'],
		'path' => '/text/',
	],

	[
		'model' => ['Text', 'update'],
		'method' => ['PATCH'],
		'path' => '/text/',
	],
	[
		'model' => ['Text', 'delete'],
		'method' => ['DELETE'],
		'path' => '/text/',
	],














	// 漫展
	[
		'model' => ['Event', 'index'],
		'path' => '/{{location}/?}event/',
	],
	[
		'model' => ['Event', 'lists'],
		'path' => '/{{location}/?}event/type/{type}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'model' => ['Event', 'item'],
		'path' => '/{{location}/?}event/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'model' => ['Event', '{1}'],
		'path' => '/{{location}/?}event/{1}/',
	],

	[
		'model' => ['Event', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}event/',
	],
	[
		'model' => ['Event', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}event/',
	],
	[
		'model' => ['Event', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}event/',
	],









	// 交易
	[
		'model' => ['Sale', 'index'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'model' => ['Sale', 'search'],
		'path' => '/{{location}/?}sale/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'model' => ['Sale', 'lists'],
		'path' => '/{{location}/?}sale/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'model' => ['Sale', 'item'],
		'path' => '/{{location}/?}sale/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],


	[
		'model' => ['Sale', '{1}'],
		'path' => '/{{location}/?}sale/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'model' => ['Sale', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Sale', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Sale', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],







	// 招募 招聘 等
	[
		'model' => ['Job', 'index'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Job', 'search'],
		'path' => '/{{location}/?}job/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'model' => ['Job', 'lists'],
		'path' => '/{{location}/?}job/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'model' => ['Job', 'item'],
		'path' => '/{{location}/?}job/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'model' => ['Job', '{1}'],
		'path' => '/{{location}/?}job/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'model' => ['Job', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Job', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Job', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],




	// 图片 cosplay 图片  漫展反图
	[
		'model' => ['Image', 'index'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Image', 'search'],
		'path' => '/{{location}/?}image/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'model' => ['Image', 'lists'],
		'path' => '/{{location}/?}image/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'model' => ['Image', 'item'],
		'path' => '/{{location}/?}image/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'model' => ['Image', '{1}'],
		'path' => '/{{location}/?}image/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'model' => ['Image', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Image', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'model' => ['Image', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],











	// 图片



	// 社团
	[
		'model' => ['Group', 'id'],
		'path' => '/{{location}/?}group/id{id}/',
		'match' => ['location' => '[a-z]+', 'id' => '\d+'],
	],
	[
		'model' => ['Group', 'index'],
		'path' => '{{location}/?}group/{type?}/{page?}/',
		'match' => ['location' => '[a-z]+', 'type' => '[a-z]+', 'page' => '\d+'],
	],




	// feed

	// 圈子 (动态)
	[
		'model' => ['Circle', 'id'],
		'path' => '/{{location}/?}circle/id{id}/',
		'match' => ['location' => '[a-z]+', 'id' => '\d+'],
	],
	[
		'model' => ['Circle', 'index'],
		'path' => '{{location}/?}circle/{type?}/{page?}/',
		'match' => ['location' => '[a-z]+', 'type' => '[a-z]+', 'page' => '\d+'],
	],





	// 个人空间
	[
		'model' => ['Space', '{type?}'],
		'path' => '/space/id{id}/{type?}/',
		'match' => ['id' => '\d+', 'type' => 'a-z+'],
	],


	*/


	// 管理员
	[
		'model' => ['Admin{/{1}?}', '{2}'],
		'path' => 'Admin{/{1}?}{/{2}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?',2 => '[a-z][A-Za-z0-9]*'],
	],
	[
		'model' => ['Admin{/{1}?}', 'insert'],
		'method' => ['PUT'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'model' => ['Admin{/{1}?}', 'update'],
		'method' => ['PATCH'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'model' => ['Admin{/{1}?}', 'delete'],
		'method' => ['DELETE'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
];