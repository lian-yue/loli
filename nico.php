<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-03-06 04:56:13
/*	Updated: UTC 2015-03-06 05:10:52
/*
/* ************************************************************************** */
$nico_id = 25724476;
$url = 'http://smile-';
if ( $nico_id < 17000000 ) {
	$url .= 'cll';
} elseif ($nico_id < 24336071) {
	$url .= 'cln';
} else {
	$url .= 'fnl';
}

if ($nico_id < 24336071) {
	$host = array( 10, 20, 30, 40, 50, 60, 11, 21, 31, 41, 51, 61, 12, 22, 32, 42, 52, 62, 13, 23, 33, 43, 53, 63, 14, 24, 34, 44, 54, 64, 15, 25, 35, 45, 55, 65, 16, 26, 36, 46, 56, 66, 17, 27, 37, 47, 57, 67, 18, 28, 38, 48, 58, 68, 19, 29, 39, 49, 59, 69 );
} else {
	$host = array( 10, 20, 30, 40, 50, 60, 11, 21, 31, 41, 51, 61);
}
$url .= $host[$nico_id%count($host)] .'.nicovideo.jp/smile?';
echo $url;die;








// http://smile-fnl11.nicovideo.jp/smile?m=25724466
// http://smile-fnl21.nicovideo.jp/smile?m=25724467
// http://smile-fnl31.nicovideo.jp/smile?m=25724468
// http://smile-fnl41.nicovideo.jp/smile?m=25724469
// http://smile-fnl51.nicovideo.jp/smile?m=25724470
// http://smile-fnl61.nicovideo.jp/smile?m=25724471
// http://smile-fnl10.nicovideo.jp/smile?m=25724472
// http://smile-fnl20.nicovideo.jp/smile?m=25724473
// http://smile-fnl30.nicovideo.jp/smile?m=25724474
// http://smile-fnl40.nicovideo.jp/smile?m=25724475
// http://smile-fnl50.nicovideo.jp/smile?m=25724476
// http://smile-fnl60.nicovideo.jp/smile?m=25724477


// http://smile-fnl11.nicovideo.jp/smile?m=25724478
// http://smile-fnl21.nicovideo.jp/smile?m=25724479
// http://smile-fnl31.nicovideo.jp/smile?m=25724480
// http://smile-fnl41.nicovideo.jp/smile?m=25724481
// http://smile-fnl51.nicovideo.jp/smile?m=25724482