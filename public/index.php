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
namespace Loli;
if (!empty($_SERVER['REQUEST_URI']) && in_array(strtolower($_SERVER['REQUEST_URI']), ['/favicon.ico', '/crossdomain.xml', '/robots.txt'], true)) {
	exit;
}


$_SERVER['LOLI'] = require dirname(__DIR__) . '/config.php';
require dirname(__DIR__) . '/vendor/autoload.php';


// \App\User::option('exists', true)->:
// \App\Auth::drop();
// \App\Auth::option('exists', true)->create();

$route = new Route();
$route();
$route->response->send();
