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
use Loli\Uri;
use Loli\Message;
use Loli\AbstractMiddleware;


class Auth extends AbstractMiddleware{

	// 检查登录
	protected $login = null;

	// 检查节点
	protected $node = true;


	public function request(array &$params) {
		if ($this->login && $this->login >= 0) {
			// 必须登录
			if (!Route::user()->id) {
				throw new Message(['message' => 'auth_login', 'name' => 'Auth'], 401, [], new Uri(['Account', 'login'], ['redirect_uri' => Route::request()->getUri()]), Route::json() ? 3 : 0);
			}
		} elseif ($this->login !== null) {
			// 必须未登录
			if (Route::user()->id) {
				throw new Message(['message' => 'permission_denied', 'name' => 'Guest'], 403, [], true, Route::json() ? 3 : 0);
			}
		}
		// 需要借点验证
		if ($this->node && Route::auth()->cant('node', Route::node())) {
			throw new Message(['message' => 'permission_denied', 'name' => 'Node'], 403);
		}
	}

	public function response(&$view) {


	}
}
