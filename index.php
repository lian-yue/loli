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

$_SERVER['LOLI'] = require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';



// 注释 。。 为了更方便的阅读代码
// 更方便的了解当前代码的用途 比如下面这样

// "xxxxx"
// 'xxxxxxxxxxx'
// 1231
// 12312.12321
// true 和 false



// =  // 储存值
// >  // 大于符号
// >= // 大于或等于
// <  // 小于
// <= // 小于和等于
// == // 等于
// != // 不等于

// // 弱类型语言还有
// === // 绝对等于  包括数据类型
// !== // 绝对不等于 只要有一点不相同 数据类型



// if (1 == "1") {
// 	echo "这是要运行的";
// }

// if (1 === "1") {
// 	echo "这是不会运行的";
// }



// $时间 = $_GET['time'];

// if ($时间 > "20:00") {
// 	echo "晚上了";
// 	if ($时间 == "21:00") {
// 		echo "刚好晚上9点";
// 	} elseif (这是函数($时间, "22:00")) {
// 		echo "大于晚上10点";
// 	} else {
// 		echo "小于晚上10点";
// 	}
// } elseif ($时间 > "14:00") {
// 	echo "下午了";
// } elseif ($时间 > "11:30") {
// 	echo "中午了";
// } elseif ($时间 >" 7:30") {
// 	echo "早上了";
// } else {
// 	echo "深夜了";
// }



// //  判断哪个时间更大的函数
// function 这是函数($参数1, $参数2) {
// 	if ($参数1 > $参数2) {
// 		return true;
// 	}
// 	return false;
// }


/*
class 这是类也tmd的是对象嗯{
	function 这是方法($参数1, $参数2) {
		return false;

		if ($参数1 > $参数2) {
			return true;
		}
		return false;
	}
}*/











/*
// 布尔类型变量   是   不是
bool $整数类型变量 = true;


// 整数类型变量
int $整数类型变量 = 100;

// 有符号的
xx $整数类型变量 =  (-2^7) 到  2^7 - 1;

// 1 字节
xx unsigned $整数类型变量 =  2^8 - 1;

// 2 字节
xx unsigned $整数类型变量 =  2^16 - 1;

// 4 字节
int unsigned $整数类型变量 =  2^32 - 1;

// 8 字节
bigint unsigned $整数类型变量 = 2^64 -1;

// 浮点类型变量 4字节
float $浮点类型变量 = 100.111111111111111111111111111;

// 还有个浮点类型变量 8 字节
xxxx $浮点类型变量 = 100.111111111111111111111111111;

// 字符串类型变量
string $字符串类型变量 = "嗷嗷嗷嗷嗷";

// 数组类型变量
array $数组类型变量 = [1231,12321,312];


if ($整数类型变量) {
	// xxxxxxxx
} elseif  {
	// xxx
} else {
	// xxx
}



$吃饭的地方 = "成都";
$上班的地方 = "上海";


echo "今天要去$吃饭的地方 吃饭 然后去 $上班的地方 上班";




*/

























// die;





/*

$a = Loli\Crypt\Code::rand(1000, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz');
$a = str_split($a);
shuffle($a);

// print_r($a);die;
echo '<div style="font-size:2.3em; font-family: \'\' ">';
echo implode('  ', $a);
die;
//*/
$route = new Loli\Route();
$route();
$route->response->send();
