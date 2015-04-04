<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-03-25 09:14:34
/*	Updated: UTC 2015-04-02 13:51:04
/*
/* ************************************************************************** */
namespace Loli;



require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';


$CURL = new CURL();

$CURL->add('', ['url' => 'http://www.loli.dev/?x=1', CURLOPT_POSTFIELDS => "['qq' => 'qq']"]);
echo $CURL->get();