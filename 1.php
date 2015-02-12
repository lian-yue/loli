<?php
/*require __DIR__ . '/vendor/autoload.php';
use Loli\Curl;




$file = __DIR__.'/1.json';


$curl = new Curl;
$curl->add('', 'http://www.loli.dev/?qqww', [CURLOPT_PUT => true, CURLOPT_CUSTOMREQUEST => 'DELETE', CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'], CURLOPT_INFILESIZE => filesize($file), CURLOPT_INFILE => fopen($file, 'rb')]);
echo $curl->get();

*/
?>
<html>
<body>

<form action="/" method="post">
<input type="text" name="name"><br>
<input type="text" name="email"><br>
<input type="submit">
</form>


<form action="/" method="post"  enctype="multipart/form-data">
    <input type="text" name="username" value="yyy"/><br/>
    <input type="text" name="age" value="zzz"/><br/>
    <input type="file" name="q[][]" /><br/>
    <input type="file" name="q[][]" /><br/>
    <input type="file" name="q[][]" /><br/>
    <input type="submit" /><br/>
</form>

</body>
</html>


<?php


/*
foreach ($_SERVER as $name => $value) {
	if (substr($name, 0, 5) === 'HTTP_' || in_array($name, ['UNENCODED_URL', 'X_ORIGINAL_URL', 'HTTP_X_ORIGINAL_URL', 'IIS_WasUrlRewritten'])) {
		unset($_SERVER[$name]);
	}
}
print_r($_SERVER);
die;
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/';



// ORIG_PATH_INFO   = 文件路径 可能有 1234.php/123
// SERVER_PROTOCOL  = HTTP 版本
// SERVER_PORT_SECURE  = SSL 0 1
// SERVER_PORT  = 服务器端口 80
// REQUEST_URI  = url地址 有query的
// REQUEST_METHOD  = HTTP 方法
// REMOTE_ADDR = 请求的 IP
// REMOTE_PORT = 发起请求的 端口
// QUERY_STRING = 查询字符串
// HTTPS = 是否 是ssl on
// CONTENT_TYPE = 发送的内容类型
// CONTENT_LENGTH = 发送的内容长度
// REQUEST_TIME_FLOAT = 请求时间戳毫秒
// REQUEST_TIME = 请求时间 秒

/*
print_r($_SERVER);







$head[] = "POST /post.php HTTP/1.1";
$head[] = "Host: Loli.dev";
$head = implode( "\r\n", $head ). "\r\n\r\n";

$function = function_exists('fsockopen') ? 'fsockopen' : 'pfsockopen';
if ( !$fp = @$function( 'loli.dev', '80', $errno, $errstr, 5 )) {
	return false;
}

if( !fputs( $fp, $head ) ) {
	return false;
}
$content = '';
while ( !feof( $fp ) ) {
	$content .= fgets( $fp, 1024 );
}

fclose( $fp );
print_r($content);
/*
if ( $gzip = $this->gzip( $content ) ) {
	$content = $gzip;
}

$content = str_replace( "\r\n", "\n", $content );
$content = explode( "\n\n", $content, 2 );

if ( !empty( $content[1] ) && !strpos( $content[0], "\nContent-Length:" ) ) {
	$content[1] = preg_replace( '/^[0-9a-z\r\n]*(.+?)[0-9\r\n]*$/i', '$1', $content[1] );
}
$content = implode( "\n\n", $content );






*/









/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-02-09 06:33:20
/*	Updated: UTC 2015-02-12 06:50:22
/*
/* ************************************************************************** */
/*
header('Content-Type: text/plain');

function foo() {
	//print_r(headers_list());
/* foreach (headers_list() as $header) {
   if (strpos($header, 'X-Powered-By:') !== false) {
     header_remove('X-Powered-By');
   }
   header_remove('X-Test');
 }*/
//}
/*
print_r(headers_list());
header_register_callback('foo');

echo 22;

unset($_SERVER, $_GET, $_POST, $_COOKIE, $_SESSION, $_FILES, $_ENV);
print_r($GLOBALS);

/*
print_r($GLOBALS);
die;
$_SERVER;




print_r($_SERVER);


//$_SERVER
//$_GET
//$_POST
//$_FILES
//$_COOKIE
//$_SESSION
//$_REQUEST
//$_ENV
die;

// ORIG_PATH_INFO   = 文件路径 可能有 1234.php/123
// SERVER_PROTOCOL  = HTTP 版本
// SERVER_PORT_SECURE  = SSL 0 1
// SERVER_PORT  = 服务器端口 80
// REQUEST_URI  = url地址 有query的
// REQUEST_METHOD  = HTTP 方法
// REMOTE_ADDR = 请求的 IP
// REMOTE_PORT = 发起请求的 端口
// QUERY_STRING = 查询字符串
// HTTPS = 是否 是ssl on
// CONTENT_TYPE = 发送的内容类型
// CONTENT_LENGTH = 发送的内容长度
// REQUEST_TIME_FLOAT = 请求时间戳毫秒
// REQUEST_TIME = 请求时间 秒
//
//
//



Array
(
    [USER] => www
    [HOME] => /home/www
    [FCGI_ROLE] => RESPONDER
    [GATEWAY_INTERFACE] => CGI/1.1
    [SERVER_SOFTWARE] => nginx/1.0.15
    [QUERY_STRING] => asdas
    [REQUEST_METHOD] => GET
    [CONTENT_TYPE] =>
    [CONTENT_LENGTH] =>
    [SCRIPT_FILENAME] => /home/wwwroot/1.php
    [SCRIPT_NAME] => /1.php
    [REQUEST_URI] => /1.php?asdas
    [DOCUMENT_URI] => /1.php
    [DOCUMENT_ROOT] => /home/wwwroot
    [SERVER_PROTOCOL] => HTTP/1.1
    [REMOTE_ADDR] => 106.186.118.19
    [REMOTE_PORT] => 57587
    [SERVER_ADDR] => 106.187.95.35
    [SERVER_PORT] => 80
    [SERVER_NAME] => lianyue.org
    [REDIRECT_STATUS] => 200
    [HTTP_HOST] => www.lianyue.org
    [HTTP_CONNECTION] => keep-alive
    [HTTP_CACHE_CONTROL] => max-age=0
    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*//**;q=0.8
    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2292.0 Safari/537.36
    [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
    [HTTP_ACCEPT_LANGUAGE] => ja,en;q=0.8,en-GB;q=0.6
    [HTTP_COOKIE] => pgv_pvi=8259421184; pgv_si=s9036687360; wp-settings-1=m4%3Do%26m5%3Do%26m3%3Do%26m1%3Do%26galfile%3D1%26galcols%3D4%26m0%3Do%26editor%3Dtinymce%26m6%3Do%26m8%3Do%26m9%3Do%26galord%3Dtitle%26urlbutton%3Dpost%26imgsize%3Dfull%26hidetb%3D1%26m7%3Do%26wplink%3D1%26m2%3Do%26galdesc%3D1%26m10%3Do%26widgets_access%3Doff%26libraryContent%3Dbrowse%26align%3Dnone%26uploader%3D1%26dfw_width%3D822%26posts_list_mode%3Dexcerpt; wp-settings-time-1=1413184001; wordpress_test_cookie=WP+Cookie+check; wordpress_logged_in_9d84534ffce314cd8877d724f40b2c34=admin%7C1424256746%7COLGVY0gUjdgqYO1OQ2eEj6OMVxgWo7bsNunr4OWLqts%7C9f3d353950e9a67c0a65861009626d7be1511a9f3de70ae24ab9a1d4a991a38d; CNZZDATA1842933=cnzz_eid%3D1216660948-1413183651-%26ntime%3D1423586153; __utma=219260804.2105077203.1413183651.1423583182.1423586153.274; __utmc=219260804; __utmz=219260804.1423394350.270.8.utmcsr=loli.net|utmccn=(referral)|utmcmd=referral|utmcct=/
    [PHP_SELF] => /1.php
    [REQUEST_TIME_FLOAT] => 1423588902.9958
    [REQUEST_TIME] => 1423588902
)









Array
(
    [USER] => www
    [HOME] => /home/www
    [FCGI_ROLE] => RESPONDER
    [GATEWAY_INTERFACE] => CGI/1.1
    [SERVER_SOFTWARE] => nginx/1.0.15
    [QUERY_STRING] => asdas
    [REQUEST_METHOD] => GET
    [CONTENT_TYPE] =>
    [CONTENT_LENGTH] =>
    [SCRIPT_FILENAME] => /home/wwwroot/1.php
    [SCRIPT_NAME] => /1.php
    [REQUEST_URI] => /1.php?asdas
    [DOCUMENT_URI] => /1.php
    [DOCUMENT_ROOT] => /home/wwwroot
    [SERVER_PROTOCOL] => HTTP/1.1
    [REMOTE_ADDR] => 106.186.118.19
    [REMOTE_PORT] => 57711
    [SERVER_ADDR] => 106.187.95.35
    [SERVER_PORT] => 80
    [SERVER_NAME] => lianyue.org
    [REDIRECT_STATUS] => 200
    [HTTP_HOST] => www.lianyue.org
    [HTTP_CONNECTION] => keep-alive
    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*//**;q=0.8
    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2292.0 Safari/537.36
    [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
    [HTTP_ACCEPT_LANGUAGE] => ja,en;q=0.8,en-GB;q=0.6
    [PHP_SELF] => /1.php
    [REQUEST_TIME_FLOAT] => 1423589007.3636
    [REQUEST_TIME] => 1423589007
)




Array
(
    [USER] => www
    [HOME] => /home/www
    [FCGI_ROLE] => RESPONDER
    [GATEWAY_INTERFACE] => CGI/1.1
    [SERVER_SOFTWARE] => nginx/1.0.15
    [QUERY_STRING] => asdas
    [REQUEST_METHOD] => GET
    [CONTENT_TYPE] =>
    [CONTENT_LENGTH] =>
    [SCRIPT_FILENAME] => /home/wwwroot/1.php
    [SCRIPT_NAME] => /1.php
    [REQUEST_URI] => /1.php?asdas
    [DOCUMENT_URI] => /1.php
    [DOCUMENT_ROOT] => /home/wwwroot
    [SERVER_PROTOCOL] => HTTP/1.1
    [REMOTE_ADDR] => 106.186.118.19
    [REMOTE_PORT] => 57711
    [SERVER_ADDR] => 106.187.95.35
    [SERVER_PORT] => 80
    [SERVER_NAME] => lianyue.org
    [REDIRECT_STATUS] => 200
    [HTTP_HOST] => www.lianyue.org
    [HTTP_CONNECTION] => keep-alive
    [HTTP_CACHE_CONTROL] => max-age=0
    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*//**;q=0.8
    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2292.0 Safari/537.36
    [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
    [HTTP_ACCEPT_LANGUAGE] => ja,en;q=0.8,en-GB;q=0.6
    [PHP_SELF] => /1.php
    [REQUEST_TIME_FLOAT] => 1423589016.5565
    [REQUEST_TIME] => 1423589016
)
*/