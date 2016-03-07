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
$_SERVER['LOLI'] = require dirname(__DIR__) . '/config.php';


$route = new Route();
$route->run();
$responseOutput = new ResponseOutput($route->response, $route->request);
$responseOutput->send();









die;






$item = Cache::getItem('xxx');
print_r($item);
$item->add('ww');
echo $item->save();
die;
die;

$logger = new \Loli\Log\MemoryLogger;
$pool = new CacheItemPool(['servers' => [['127.0.0.1']]], $logger);





// Basic set/get operations.
$item = $pool->getItem('foo');
$item->set('foo value', '300');
$pool->save($item);
// die;
$item = $pool->getItem('bar');
$item->set('bar value', new \DateTime('now + 5min'))->expiresAt(time() + 111);
$pool->save($item);
foreach ($pool->getItems(['foo', 'bar']) as $key => $item) {
    if ($key == 'foo') {
        assert($item->get() == 'foo value');
    }
    if ($key == 'bar') {
        assert($item->get() == 'bar value');
    }
	print_r($item);
}
// Update an existing item.
$items = $pool->getItems(['foo', 'bar']);
$items['bar']->set('new bar value');
array_map([$pool, 'save'], $items);
foreach ($pool->getItems(['foo', 'bar']) as $item) {
    if ($item->getKey() == 'foo') {
        assert($item->get() == 'foo value');
    }
    if ($item->getKey() == 'bar') {
        assert($item->get() == 'new bar value');
    }
}
// Defer saving to a later operation.
$item = $pool->getItem('baz')->set('baz value', '100');
$pool->saveDeferred($item);
$item = $pool->getItem('foo')->set('new foo value', new \DateTime('now + 1min'));
$pool->saveDeferred($item);
$pool->commit();
$items = $pool->getItems(['foo', 'bar', 'baz']);
assert($items['foo']->get() == 'new foo value');
assert($items['bar']->get() == 'new bar value');
assert($items['baz']->get() == 'baz value');


$item = $pool->getItem('baz')->add('baz value2', '100');
$pool->saveDeferred($item);
$item = $pool->getItem('foo')->add('new foo value2', new \DateTime('now + 2min'));
$pool->saveDeferred($item);
$pool->commit();

$items = $pool->getItems(['foo', 'bar', 'baz']);
assert($items['foo']->get() == 'new foo value');
assert($items['bar']->get() == 'new bar value');
assert($items['baz']->get() == 'baz value');


print_r($logger);

die;

die;

use Loli\Http\Message\ServerRequest;

new ServerRequest('GET', '/');
die;

$route = new Route();
$route();
$route->response->send();
