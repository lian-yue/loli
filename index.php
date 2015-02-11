<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-01-03 10:27:12
/*	Updated: UTC 2015-02-11 06:54:12
/*
/* ************************************************************************** */
namespace Loli;
// 如果是网页 ICO 结束查询 或者 Flash 请求
if (!empty($_SERVER['REQUEST_URI']) && in_array(strtolower($_SERVER['REQUEST_URI']), ['/favicon.ico', '/crossdomain.xml', '/robots.txt'])) {
	exit;
}
require __DIR__ . '/vendor/autoload.php';




/*


//print_r($_SERVER);
//die;

Request::start();
//$_SERVER = [];
Request::end();

print_r($_SERVER);

// 采用注册机制 绑定入口
// add('hostname'， ‘/路径’, '根方法')
// add('hostname', '根方法')
// add('hostname', '根方法')

/*

function qq(){

	Router::add('/', function() {
		return ['qweqw'];
	});
	Router::group('/12312/312', function(){
		Router::add('/qweqwe', function(){
			echo 33;
		});
	}, '(?<$user_id>\w+)\.loli\.dev');



	Router::run(new Request);
	$request = Router::request();
	$request->setHeader('Range', 'bytes=-100');
	$response = Router::response();
	$response->send();

}
qq();
*//*
$fp = fopen(__DIR__ .'/index.php', 'rb');
echo fseek($fp, 22);
die;
$data = fread($fp, 2);
fseek($fp, 0);

echo $data;
die;*/

/*
function qq(){
	$response = Router::run(new Request);
	$response->send();
}
qq();
*/