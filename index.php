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
/*	Updated: UTC 2015-02-25 14:22:09
/*
/* ************************************************************************** */
namespace Loli;
// 如果是网页 ICO 结束查询 或者 Flash 请求
if (!empty($_SERVER['REQUEST_URI']) && in_array(strtolower($_SERVER['REQUEST_URI']), ['/favicon.ico', '/crossdomain.xml', '/robots.txt'])) {
	exit;
}

require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';


$request = new Request();
$router = new Router($request, $response);
$response->send();
unset($router);

/*
echo load_time() ."\n";
echo load_file() ."\n";
echo load_ram() ."\n";
//echo $URL;

/*
print_r((new Request('GET', '/qe/1s/adas/d/we'))->setScheme('https'));
print_r($_SERVER);
=======


print_r($_SERVER);
die;
//setcookie(mb_rand(mt_rand(1, 20), '   """"""""[][][][][][][]'), mb_rand(rand(1,20), '12345678==========-------======;;;;;;;=====----------90-qwertyuioasdfghjklz;;;;[][][][][][][]./.,./<>?<":L{PO>?,{Pl":l./,?1>,xcvbnmQWER""""""""""""""""""""""\'\'\'\'\'\'\'\'\'\'\'\'\'ZXCVBNMjkheh大家完全恶化委屈都会饿花花世界和v玩儿过i哦了i为u了看说得好sd'), time() + 11111, '/');
//new Request;

print_r(new Request('GET', '/qe/1s/adas/d/we'));
>>>>>>> 5d903d4d1c03102a108125aedd22e4bd647ef23c
echo "\n\n\n";
echo load_time() ."\n";
echo load_file() ."\n";
echo load_ram() ."\n";
die;

print_r();
*/
/*new Request;
print_r($_SERVER);
/*
print_r(Request::defaultURL());
print_r(Request::defaultHeaders());
print_r(Request::defaultFiles());
print_r(Request::defaultContent());
print_r(new Request('GET', 'http://www.qq.com'));
>>>>>>> 9cb784031a1e1feadcbbea5404c12e37f2de1fca
echo "\n\n\n";
print_r(load_time());
print_r(load_file());
print_r(load_ram());
die;

//=,; \t\r\n\013\014
//echo load_ram();

/*
  if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
  } else {
    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
  }

  print_r($_SERVER);
//ob_start();
//header_remove();
//header('xx-xx: eeee');
//header_remove();
//header('xx-xx: wqeqw');

//echo '111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111';
header('xx-xx: wqeqw');

print_r(headers_list());
	print_r(headers_sent());
header_register_callback(function(){

	print_r(headers_list());
	print_r(func_get_args());
});
//print_r($_SERVER);

//header_remove();
=======
print_r($_SERVER);die;
>>>>>>> 0f811f47d27ae7a22c6a44e8adc6c839524a59af
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