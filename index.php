<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-21 14:07:44
/*
/* ************************************************************************** */
if (!empty($_SERVER['REQUEST_URI']) && in_array(strtolower($_SERVER['REQUEST_URI']), ['/favicon.ico', '/crossdomain.xml', '/robots.txt'], true)) {
	exit;
}


require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';



$route = new Loli\Route;
$route();
$route->response->send();
// $route->run();