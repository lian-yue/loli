<?php
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

	'LOG' => [
		'type' => 'File',
		'path' => __DIR__ . '/data/log/$date/$level-$time.log',
	],
];
