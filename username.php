<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-02-14 14:09:58
/*	Updated: UTC 2015-02-15 13:51:48
/*
/* ************************************************************************** */
namespace Loli;
require __DIR__ . '/vendor/autoload.php';






$curl = new Curl([
	CURLOPT_USERAGENT => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53',
	CURLOPT_REFERER => 'http://wappass.baidu.com/passport/fillname?tpl=tb&tn=bdIndex&u=http%3A%2F%2Ftieba.baidu.com%2Fmo%2Fq%2Fopenitb%3Fu%3Dhttp%253A%252F%252Ftieba.baidu.com%252Fmo%252Fq%252Fm%253Ftn%253DbdIndex%2526',
	CURLOPT_HTTPHEADER => ['Accept-Language: zh,en;q=0.8,en-GB;q=0.6', 'Content-Type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest', 'Origin: http://wappass.baidu.com', 'Accept: application/json'],
	CURLOPT_COOKIE => 'BAIDUID=BAE4892E17FCF94143B962926E875782:FG=1; BDUSS=2xnY2NNSEhYci1uSkp4TFJmaFVUTi04VGxNUm5BazMzSEhQb1dYbGg2OEw0UVpWQVFBQUFBJCQAAAAAAAAAAAEAAADj7Z8xAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAtU31QLVN9UU; BAIDUPSID=BAE4892E17FCF94143B962926E875782; BDRCVFR[feWj1Vr5u3D]=I67x6TjHwwYf0; H_PS_PSSID=12521_1435_8498_10634; BAIDU_WISE_UID=wapp_1423922900481_278; Hm_lvt_294dbbdeb1fabbed8433533a3564560e=1423922922; Hm_lpvt_294dbbdeb1fabbed8433533a3564560e=1423922922',
]);













//131072
$echo = '';
$code = r('code');

do {
	for ($i = $code; $i < $code + 5;++$i) {
		$curl->add($i, 'http://wappass.baidu.com/wp/api/ucenter/fillname?v=1423922923990', [CURLOPT_POSTFIELDS => merge_string(['username' => html_entity_decode('&#'.$i.';', ENT_HTML5), 'action' => '', 'u' => 'http%3A%2F%2Ftieba.baidu.com%2Fmo%2Fq%2Fopenitb%3Fu%3Dhttp%253A%252F%252Ftieba.baidu.com%252Fmo%252Fq%252Fm%253Ftn%253DbdIndex%2526%26bd_page_type%3D2%26from%3D%26uid%3D1423922919264_272%26pu%3D%26ssid%3D%26v%3Dv2', 'tpl' => 'tb', 'tn' => 'bdIndex', 'pu' => '', 'ssid' => '', 'from' => '', 'bd_page_type' => '', 'uid' => '', 'type' => '', 'regtype' => '', 'subpro' => '', 'adapter' => '', 'skin' => '', 'regist_mode' => '', 'login_share_strategy' => '', 'client' => '', 'clientfrom' => '', 'connect' => ''])]);
	}
	$code = $i;

	foreach($curl->get(true) as $i => $value) {
		$username = html_entity_decode('&#'.$i.';', ENT_HTML5);
		$echo .= "$i    $username  <br>\n";
		if (!($json = json_decode($value))) {

			echo $echo;
			var_dump($value);
			print_r($curl);
			die;
		}
		if (empty($json->errInfo->no)) {

			echo $echo;
			print_r($json);
			die;
		}
		if ($json->errInfo->no == '200002' || $json->errInfo->no == '230049'|| $json->errInfo->no == '230053' || $json->errInfo->no == '230054' || $json->errInfo->no == '230048'|| $json->errInfo->no == '400001') {
			continue;
		}

		echo $echo;
		print_r($json);
		die;
	}
	///sleep(1);
} while(($_SERVER['REQUEST_TIME'] + 20) > time());
header("location: /username.php?code=" . $code);