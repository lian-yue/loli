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
/*	Updated: UTC 2015-04-01 04:38:39
/*
/* ************************************************************************** */

/*
$n=20;
$d=7; //公钥
$e=3; //私钥
$M=12; //进行加密
$MM = pow($M, $d) % $n; //(M的d次方，然后除以n取余数)


$Q = pow($MM,$e)%$n;


echo $Q .'<br/>';
echo $MM;die;
*/


namespace Loli;
require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';


/*
$format = new HTML\Style;

/*
$format('
.qq10 {sxsa:dsa;}

.qq11 {sxsa:dsa;}

@media only screen and (min-width : 500px) {
	.qq20 {sxsa:dsa;}
	.qq21 {sxsa:dsa;}
	.qq22 {sxsa:dsa;}
	.qq23 {sxsa:dsa;}
	@media only screen and (min-width : 500px) {
		.qq30 {sxsa:dsa;}
		@media only screen and (min-width : 500px) {
			@media only screen and (min-width : 500px) {
				.qq40 {sxsa:dsa;}
				.qq41 {sxsa:dsa;}
				.qq42 {sxsa:dsa;}
				.qq43 {sxsa:dsa;}
				@media only screen and (min-width : 500px) {
					.qq50 {sxsa:dsa;}
				}
				.qq49 {sxsa:dsa;}
			}
		}
		@media {
			.qq1110 {sxsa:dsa;}
		}
	}
}
.qq19 {sxsa:dsa;}


');
*/
/*
$format = new HTML\Filter;
echo $format(file_get_contents(__DIR__ . '/1.html'));
echo "\n\n\n\n";
echo load_time();

/*
$style = new HTML\Style;
echo $style->values('
  background: rgb(208, 208, 208);
  color: rgba(85, 85, 85 ,.94);
  box-shadow:0px 1px 2px 2px rgb(130, 130, 130), 0px 0px 0px 7px rgb(203, 203, 203), 0px 0px 0px 9px white, 0px 0px 0px 11px rgb(102, 102, 102),0px 35px 0px -6px rgb(226, 226, 226) inset;
   background-image: url("http://www.qq.com/paper.gif");


  ');

/*
$style->style('

.mobile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
@media only screen and (min-width : 500px) {
	.mobile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
	.mobile .lists li .title{font-size:1.2em;height:3.8em;}
	.mobile .lists li .title{font-size:1.2em;height:3.8em;}
	.mobile .lists li .title{font-size:1.2em;height:3.8em;}
	.mobile .lists li .title{font-size:1.2em;height:3.8em;}
	@media only screen and (min-width : 500px) {
		.mobile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
		.mobile .lists li .title{font-size:1.2em;height:3.8em;}
		.mobile .lists li .title{font-size:1.2em;height:3.8em;}
		.mobile .lists li .title{font-size:1.2em;height:3.8em;}
		.mobile .lists li .title{font-size:1.2em;height:3.8em;}
		@media only screen and (min-width : 500px) {
			@media only screen and (min-width : 500px) {
				.mobile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
				.mobile .lists li .title{font-size:1.2em;height:3.8em;}
				.mobile .lists li .title{font-size:1.2em;height:3.8em;}
				.mobile .lists li .title{font-size:1.2em;height:3.8em;}
				.mobile .lists li .title{font-size:1.2em;height:3.8em;}
				@media only screen and (min-width : 500px) {
					.mobile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
					.mobile .lists li .title{font-size:1.2em;height:3.8em;}
					.mobile .lists li .title{font-size:1.2em;height:3.8em;}
					.mobile .lists li .title{font-size:1.2em;height:3.8em;}
					.mobile .lists li .title{font-size:1.2em;height:3.8em;}
				}
			}
		}
	}
}
.obile .lists li{width:30%;height: auto; margin:1% 1.666%;background-color:inherit;border:0;border-radius:0;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
.mobile .lists li .title{font-size:1.2em;height:3.8em;}
');

//$tag(file_get_contents(__DIR__ . '/1.html'));

//echo $username;
/*
header('WWW-Authenticate: Basic realm="Autn"');
header('HTTP/1.0 401 Unauthorized');

  // $strAuthUser= $_SERVER['PHP_AUTH_USER'];           
  // $strAuthPass= $_SERVER['PHP_AUTH_PW'];
/*
if (! ($strAuthUser == "用户" &&  $strAuthPass == "密码")) {
header('WWW-Authenticate: Basic realm="Autn"');
header('HTTP/1.0 401 Unauthorized');
echo "用户验证";
exit;
} else {
echo "验证通过";
}

/*
$array = str_split(strtoupper(dechex(93399)), 2);
print_r($array);
?>


<html>
<body>

<form action="upload_file.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file[ww]" id="file" /> 
<input type="file" name="file[qq]" id="file" /> 
<input type="file" name="file[ee]" id="file" /> 
<input type="file" name="file[ee][ff]" id="file" /> 
<input type="file" name="file[ee][ff][cc" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>

</body>
</html>


<?php
//print_r($_SERVER);
/*

print_r(\PDO::getAvailableDrivers());
die;


$qq = new \MongoId;
print_r($qq);die;
$subject = 'SELECT column tabl FROM SQL_CALC_FOUND_ROWS  FROM  table
WHERE column operator value';
echo preg_match('/^\s*SELECT\s+(?:(?!\s+FROM\s+).)+SQL_CALC_FOUND_ROWS/i', $subject, $matches);

print_r($matches);die;
for ($i = 0; $i < 100000; ++$i) {
	//(object) ['column' => 'qq', 'value' => 'ee', 'options' => []];
	//$query[] = (object) ['column' => 'qq', 'value' => 'ee', 'options' => []];
	//new Query\Query('qqq', 'eee') instanceof Query\Query;
	//$query[] = new Query\Query('qqq', 'eee');
}
echo load_ram() . "<br>";
echo load_time() * 1000;
//die;



echo Cache::add('qwe', 'qwe', 'asd', 60);
echo Cache::get('qwe', 'asd');
echo Cache::set('qwe', 'qwe', 'asd', 60);

$mongo = new DB\Mongo([
'host' => '127.0.0.1',
'name' => 'loli',
]);




foreach($mongo->tables() as $value) {
  $mongo->drop($value);
}

$mongo->create(['create' => 'test', 'indexes' => [[
        'key' => [
            'item' => true,
            'supplier' => true,
            'model' => true,
        ],
        'name' => "item_supplier_model",
        'unique' => true
 ]]]);


$qq = new \stdclass;
$qq->qq = new \stdclass;
$qq->qq->ee = 2;

$mongo->insert(['collection' => 'test', 'documents' => [['item' => mt_rand(), 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123], ['item' => mt_rand(), 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123], ['item' => mt_rand(), 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123]]]);
$mongo->insert(['collection' => 'test',
	'documents' => [
		['item' => $qq, 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123],
		['item' => [1,3,5], 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123],
		['item' => [6,9,7], 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123],
		['item' => [0,9,7], 'supplier' => 'mt_rand()', 'model' => mb_rand(20), mb_rand(4) => 123],
	]
]);


$results = $mongo->select(['collection' => 'test', 'query' => []]);
print_r($results);


$results = $mongo->select(['collection' => 'test', 'query' => ['item.0' => 1]]);
print_r($results);


foreach($mongo->tables() as $value) {
	$mongo->drop($value);
}

/*
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

