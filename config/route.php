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

// $anyString = '[\x{0020}-\x{002E}\x{0030}-\x{007F}\x{0080}-\x{02FA1F}]+';



return [

	/*
	[
		'controller' => ['Model', 'Method'],
		'scheme' => ['http', 'https'],
		'method' => ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'PURGE', 'OPTIONS', 'TRACE'],
		'host' => 'Host:name',
		'path' => 'install',
		'match' => [1 => '\w+'],
		'default' => [],
	],*/




	//  首页
	[
		'controller' => ['Home', 'index'],
		'path' => '/',
	],

	// 安装
	[
		'controller' => ['Install', '{1}'],
		'path' => '/install{/{1}?}/',
	],


	// 验证码
	[
		'controller' => ['Captcha', '{1}'],
		'path' => '/captcha{/{1}?}/',
	],



	// 账号 登录注册 找回密码等
	[
		'controller' => ['Account', '{1}'],
		'path' => '/account{/{1}?}/',
	],
	[
		'controller' => ['Account/OAuth2', '{1}'],
		'path' => '/account/OAuth2/{type}{/{1}?}/',
	],


	// 个人中心
	[
		'controller' => ['Profile', '{1}'],
		'path' => '/profile{/{1}?}/',
	],






    // 存储
	[
		'controller' => ['Folder', 'temporary'],
		'path' => '/folder/temporary',
	],


    [
		'controller' => ['Folder', 'get'],
		'path' => '{username}/folder{/{id_code}?}',
        'method' => ['GET'],
        'match' => ['username' => '[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]{3,32}'],
	],
	[
		'controller' => ['Folder', 'post'],
		'path' => '{username}/folder{/{id_code}?}',
        'method' => ['POST'],
        'match' => ['username' => '[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]{3,32}'],
	],

    [
		'controller' => ['Folder', 'put'],
		'path' => '/{username}/folder{/{id_code}?}',
        'method' => ['PUT'],
        'match' => ['username' => '[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]{3,32}'],
	],
    [
		'controller' => ['Folder', 'patch'],
		'path' => '/{username}/folder{/{id_code}?}',
        'method' => ['PATCH'],
        'match' => ['username' => '[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]{3,32}'],
	],
    [
		'controller' => ['Folder', 'delete'],
		'path' => '/{username}/folder{/{id_code}?}',
        'method' => ['DELETE'],
        'match' => ['username' => '[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]{3,32}'],
	],





	/*// 安装
	[
		'controller' => ['Install', '{1}'],
		'path' => '/install{/{1}?}/',
	],


	// 验证码
	[
		'controller' => ['Captcha', '{1}'],
		'path' => '/captcha{/{1}?}/',
	],

	// 用户
	[
		'controller' => ['User', '{1}'],
		'path' => '/user{/{1}?}/',
	],





	// 储存
	/*[
		'controller' => ['Folder', 'index'],
		'path' => '/folder/',
	],

	[
		'controller' => ['Folder', 'item'],
		'path' => '/folder/{userID}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'controller' => ['Folder', '{1}'],
		'path' => '/folder/{1}{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'controller' => ['Folder', 'insert'],
		'method' => ['PUT'],
		'path' => '/folder{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

	[
		'controller' => ['Folder', 'update'],
		'method' => ['PATCH'],
		'path' => '/folder{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],
	[
		'controller' => ['Folder', 'delete'],
		'method' => ['DELETE'],
		'path' => '/folder{/{userID}?}{/{path}?}',
		'match' => ['userID' => '\d+', 'path' => '.+'],
	],

















	// 百科   动漫 漫画 游戏 小说 音乐 电视剧 电影 角色 经验 教程
	[
		'controller' => ['Wiki', 'index'],
		'path' => '/wiki/',
	],
	[
		'controller' => ['Wiki', 'search'],
		'path' => '/wiki/search/{search?}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'controller' => ['Wiki', 'lists'],
		'path' => '/wiki/category/{category}{/{page}?}/',
		'match' => ['category' => &$anyString],
	],
	[
		'controller' => ['Wiki', 'item'],
		'path' => '/wiki/item/{item}{/{page}?}',
		'match' => ['item' => &$anyString, 'page' => '\d+'],
	],

	[
		'controller' => ['Wiki', '{1}'],
		'path' => '/wiki{/{1}?}/',
	],

	[
		'controller' => ['Wiki', 'insert'],
		'method' => ['PUT'],
		'path' => '/wiki/',
	],

	[
		'controller' => ['Wiki', 'update'],
		'method' => ['PATCH'],
		'path' => '/wiki/',
	],
	[
		'controller' => ['Wiki', 'delete'],
		'method' => ['DELETE'],
		'path' => '/wiki/',
	],








	// 新闻
	[
		'controller' => ['News', 'index'],
		'path' => '/news/',
	],
	[
		'controller' => ['News', 'search'],
		'path' => '/news/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'controller' => ['News', 'lists'],
		'path' => '/news/category/{category}{/{page}?}/',
		'match' => ['category' => &$anyString, 'page' => '\d+'],
	],
	[
		'controller' => ['News', 'item'],
		'path' => '/news/item/{item}{/{page}?}/',
		'match' => ['page' => '\d+'],
	],

	[
		'controller' => ['News', '{1}'],
		'path' => '/news/{1}/',
	],

	[
		'controller' => ['News', 'insert'],
		'method' => ['PUT'],
		'path' => '/news/',
	],

	[
		'controller' => ['News', 'update'],
		'method' => ['PATCH'],
		'path' => '/news/',
	],
	[
		'controller' => ['News', 'delete'],
		'method' => ['DELETE'],
		'path' => '/news/',
	],






	// 专题
	[
		'controller' => ['Topic', 'index'],
		'path' => '/topic/',
	],
	[
		'controller' => ['Topic', 'search'],
		'path' => '/topic/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString],
	],
	[
		'controller' => ['Topic', 'item'],
		'path' => '/topic/item/{item}/',
	],



	[
		'controller' => ['Topic', '{1}'],
		'path' => '/topic/{1}/',
	],

	[
		'controller' => ['Topic', 'insert'],
		'method' => ['PUT'],
		'path' => '/topic/',
	],

	[
		'controller' => ['Topic', 'update'],
		'method' => ['PATCH'],
		'path' => '/topic/',
	],
	[
		'controller' => ['Topic', 'delete'],
		'method' => ['DELETE'],
		'path' => '/topic/',
	],













	// 视频
	[
		'controller' => ['Video', 'index'],
		'path' => '/video/',
	],
	[
		'controller' => ['Video', 'search'],
		'path' => '/video/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'controller' => ['Video', 'item'],
		'path' => '/video/item/{item}/',
	],


	[
		'controller' => ['Video', '{1}'],
		'path' => '/video/{1}/',
	],

	[
		'controller' => ['Video', 'insert'],
		'method' => ['PUT'],
		'path' => '/video/',
	],

	[
		'controller' => ['Video', 'update'],
		'method' => ['PATCH'],
		'path' => '/video/',
	],
	[
		'controller' => ['Video', 'delete'],
		'method' => ['DELETE'],
		'path' => '/video/',
	],









	// 文章
	[
		'controller' => ['Text', 'index'],
		'path' => '/text/',
	],
	[
		'controller' => ['Text', 'search'],
		'path' => '/text/search/{search}{/{page}?}/',
		'match' => ['search' => &$anyString, 'page' => '\d+'],
	],
	[
		'controller' => ['Text', 'item'],
		'path' => '/text/item/{item}/',
	],


	[
		'controller' => ['Text', '{1}'],
		'path' => '/text/{1}/',
	],

	[
		'controller' => ['Text', 'insert'],
		'method' => ['PUT'],
		'path' => '/text/',
	],

	[
		'controller' => ['Text', 'update'],
		'method' => ['PATCH'],
		'path' => '/text/',
	],
	[
		'controller' => ['Text', 'delete'],
		'method' => ['DELETE'],
		'path' => '/text/',
	],














	// 漫展
	[
		'controller' => ['Event', 'index'],
		'path' => '/{{location}/?}event/',
	],
	[
		'controller' => ['Event', 'lists'],
		'path' => '/{{location}/?}event/type/{type}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'controller' => ['Event', 'item'],
		'path' => '/{{location}/?}event/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'controller' => ['Event', '{1}'],
		'path' => '/{{location}/?}event/{1}/',
	],

	[
		'controller' => ['Event', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}event/',
	],
	[
		'controller' => ['Event', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}event/',
	],
	[
		'controller' => ['Event', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}event/',
	],









	// 交易
	[
		'controller' => ['Sale', 'index'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'controller' => ['Sale', 'search'],
		'path' => '/{{location}/?}sale/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'controller' => ['Sale', 'lists'],
		'path' => '/{{location}/?}sale/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'controller' => ['Sale', 'item'],
		'path' => '/{{location}/?}sale/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],


	[
		'controller' => ['Sale', '{1}'],
		'path' => '/{{location}/?}sale/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'controller' => ['Sale', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Sale', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Sale', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}sale/',
		'match' => ['location' => '[a-z]+'],
	],







	// 招募 招聘 等
	[
		'controller' => ['Job', 'index'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Job', 'search'],
		'path' => '/{{location}/?}job/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'controller' => ['Job', 'lists'],
		'path' => '/{{location}/?}job/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'controller' => ['Job', 'item'],
		'path' => '/{{location}/?}job/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'controller' => ['Job', '{1}'],
		'path' => '/{{location}/?}job/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'controller' => ['Job', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Job', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Job', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}job/',
		'match' => ['location' => '[a-z]+'],
	],




	// 图片 cosplay 图片  漫展反图
	[
		'controller' => ['Image', 'index'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Image', 'search'],
		'path' => '/{{location}/?}image/search/{search}/',
		'match' => ['location' => '[a-z]+', 'search' => &$anyString],
	],
	[
		'controller' => ['Image', 'lists'],
		'path' => '/{{location}/?}image/category/{category}/{page?}/',
		'match' => ['location' => '[a-z]+', 'page' => '\d+'],
	],
	[
		'controller' => ['Image', 'item'],
		'path' => '/{{location}/?}image/item/{item}/',
		'match' => ['location' => '[a-z]+', 'item' => '\d+'],
	],

	[
		'controller' => ['Image', '{1}'],
		'path' => '/{{location}/?}image/{1}/',
		'match' => ['location' => '[a-z]+'],
	],

	[
		'controller' => ['Image', 'insert'],
		'method' => ['PUT'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Image', 'update'],
		'method' => ['PATCH'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],
	[
		'controller' => ['Image', 'delete'],
		'method' => ['DELETE'],
		'path' => '/{{location}/?}image/',
		'match' => ['location' => '[a-z]+'],
	],











	// 图片



	// 社团
	[
		'controller' => ['Group', 'id'],
		'path' => '/{{location}/?}group/id{id}/',
		'match' => ['location' => '[a-z]+', 'id' => '\d+'],
	],
	[
		'controller' => ['Group', 'index'],
		'path' => '{{location}/?}group/{type?}/{page?}/',
		'match' => ['location' => '[a-z]+', 'type' => '[a-z]+', 'page' => '\d+'],
	],




	// feed

	// 圈子 (动态)
	[
		'controller' => ['Circle', 'id'],
		'path' => '/{{location}/?}circle/id{id}/',
		'match' => ['location' => '[a-z]+', 'id' => '\d+'],
	],
	[
		'controller' => ['Circle', 'index'],
		'path' => '{{location}/?}circle/{type?}/{page?}/',
		'match' => ['location' => '[a-z]+', 'type' => '[a-z]+', 'page' => '\d+'],
	],





	// 个人空间
	[
		'controller' => ['Space', '{type?}'],
		'path' => '/space/id{id}/{type?}/',
		'match' => ['id' => '\d+', 'type' => 'a-z+'],
	],


	*/


	// 管理员
	/*[
		'controller' => ['Admin{/{1}?}', '{2}'],
		'path' => 'Admin{/{1}?}{/{2}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?',2 => '[a-z][A-Za-z0-9]*'],
	],
	[
		'controller' => ['Admin{/{1}?}', 'insert'],
		'method' => ['PUT'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'controller' => ['Admin{/{1}?}', 'update'],
		'method' => ['PATCH'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],
	[
		'controller' => ['Admin{/{1}?}', 'delete'],
		'method' => ['DELETE'],
		'path' => 'Admin{/{1}?}/',
		'match' => [1 => '[A-Z][0-9A-Za-z_/]*?'],
	],*/
];
