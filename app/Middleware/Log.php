<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-09 13:59:34
/*
/* ************************************************************************** */
namespace App\Middleware;

use Loli\Log;
use Loli\Route;
use Loli\AbstractMiddleware;

class Log extends AbstractMiddleware{

	public function request(array &$params) {
		$request = Route::request();

		$array = [$request->getMethod()];
		if ($ip = Route::ip()) {
			$array[] = $ip;
		}
		$array[] = Route::token()->get();

		if ($userAgent = $request->getHeaderLine('User-Agent')) {
			$array[] = $userAgent;
		}

		$array[] = $request->getUri();
		Log::access()->info(implode(' ', $array));
	}

	public function response(&$view) {

	}
}
