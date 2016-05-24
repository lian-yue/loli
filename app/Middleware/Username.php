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

use Loli\Locale;
use Loli\Message;
use Loli\AbstractMiddleware;
use App\User;

class Username extends AbstractMiddleware{

	// 检查节点
	protected $id = 'user_id';

    protected $required= true;

	public function request(array &$params) {
		if (empty($params['username']) || !is_string($params['username'])) {
            if ($this->required) {
                throw new Message('user_exists', 404);
            }
            unset($params[$this->id]);
            return;
        }

        if (!$user = User::query('username', $params['username'], '=')->selectOne()) {
            throw new Message('user_exists', 404);
        }
        $params['username'] = $user->username;
        $params[$this->id] = $user->id;
	}

    public function response(&$view) {


	}
}
