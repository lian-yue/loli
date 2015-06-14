<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-06-12 03:58:18
/*	Updated: UTC 2015-06-13 04:31:07
/*
/* ************************************************************************** */
namespace Loli;

// 异常库
class Exception extends \Exception{

}



require __DIR__ . '/Image/Exception.php';
require __DIR__ . '/Image/Base.php';
require __DIR__ . '/Image/GD.php';
require __DIR__ . '/Image/Imagick.php';




$font = '/Library/Fonts/Songti.ttc';

$image = new Image\GD(__DIR__ . '/Image.jpg');

// 需要 Imagick 也支持 下面的偏移      -x  x  x%  -x% 的偏移
//
//$image = new Image\Imagick(__DIR__ . '/Image.jpg');

//die;brew install ImageMagick --with-ghostscript



//$image->text('顶部居中0度的', $font, 32, '#000000', 200, 10, 0, 1);
//$image->text('顶部居中0度的', $font, 32, '#000000', 12, 12, 45, 0.9);

// $image->text('顶部居中90度的', $font, 32, '#000000', '0%', '50%', 90, 0.6);
// $image->text('顶部居中180度的', $font, 32, '#000000', '0%', '50%', 180, 0.6);


// $image->text('全局居中0度的', $font, 32, '#000000', '50%', '50%', 0, 0.6);
// $image->text('全局居中45度的', $font, 32, '#000000', '50%', '50%', 45, 0.6);
// $image->text('全局居中90度的', $font, 32, '#000000', '50%', '50%', 90, 0.6);
// $image->text('全局居中180度的', $font, 32, '#000000', '50%', '50%', 180, 0.6);




// $image->text('底部居中0度的', $font, 32, '#000000', '100%', '50%', 0, 0.6);
// $image->text('底部居中45度的', $font, 32, '#000000', '100%', '50%', 45, 0.6);
// $image->text('底部居中90度的', $font, 32, '#000000', '100%', '50%', 90, 0.6);
// $image->text('底部居中180度的', $font, 32, '#000000', '100%', '50%', 180, 0.6);




// $image->text('底部居中0度的', $font, 32, '#000000', '-0', '50%', 0, 0.6);
// $image->text('底部居中45度的', $font, 32, '#000000', '-0', '50%', 45, 0.6);
// $image->text('底部居中90度的', $font, 32, '#000000', '-0', '50%', 90, 0.6);
// $image->text('底部居中180度的', $font, 32, '#000000', '-0', '50%', 180, 0.6);



// $image->text('右下角0度的', $font, 32, '#000000', '-0', '-0', 0, 0.6);
// $image->text('右下角45度的', $font, 32, '#000000', '-0', '-0', 45, 0.6);
// ..... 等等



// $image->text('右下角0度的', $font, 32, '#000000', '100%', '100%', 0, 0.6);
// $image->text('右下角0度的', $font, 32, '#000000', '100%', '100%', 0, 0.6);


// 输出
$image->show();