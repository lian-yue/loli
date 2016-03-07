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

use Loli\Route;
use Loli\Message;
use Loli\AbstractMiddleware;

class Csrf extends AbstractMiddleware{
	public function request(array &$params) {
		if (!Route::csrf()) {
			return;
		}
		// throw new Message(['message' => 'permission_denied', 'code' => 'CSRF'], 403);
	}
	public function response(&$view) {

	}
}
