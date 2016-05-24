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






use Loli\Http\Message\ServerRequestInput;
use Loli\Http\Message\ResponseOutput;

if (!empty($_SERVER['REQUEST_URI']) && in_array(strtolower($_SERVER['REQUEST_URI']), ['/favicon.ico', '/crossdomain.xml', '/robots.txt'], true)) {
	exit;
}
require dirname(__DIR__) . '/vendor/autoload.php';





$route = new Route();
$route->run();
$responseOutput = new ResponseOutput($route->response, $route->request);
// usleep(mt_rand(1, 1000000));
$responseOutput->send();
