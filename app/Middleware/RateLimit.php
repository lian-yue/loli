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
use Loli\Cache;
use Loli\Message;
use Loli\DateTime;
use Loli\AbstractMiddleware;

class RateLimit extends AbstractMiddleware{

    protected $ip = false;

    protected $token = false;

    protected $user = false;

    protected $params = [];

	protected $limit = 60;

	protected $reset = 1800;

	protected $success = false;

	private $items = [];

	public function request(array &$params) {
		$key = strtolower(implode('/', Route::controller())) . '.' . $this->limit . '.' . $this->reset;

		$keys = [];
		foreach (['ip' => [Route::ip(), 'IP'], 'token' => [Route::token()->get(), 'Token'], 'user' => [Route::auth()->user_id, 'User']] as $name => $value) {
			if ($this->$name) {
				$keys[md5(json_encode([$name, $value[0], $key]))] =  $value[1];
			}
		}

        foreach($this->params as $name => $value) {
            if (!isset($params[$name])) {
                $params[$name] = '';
            }
            foreach ((array) $value as $callback) {
                $params[$name] = call_user_func($callback, $params[$name]);
            }
            $keys[md5(json_encode(['_'. $name, $params[$name], $key]))] =  $name;
        }

		$this->items = $this->cache()->getItems(array_keys($keys));

		$current = false;
		foreach ($this->items as $item) {
			if (!$current || $item->get() > $current->get()) {
				$current = $item;
			}
		}

		if (!$current) {
			return;
		}

		Route::response(
			Route::response()->withHeader('X-RateLimit-Limit', $this->limit)
			->withHeader('X-RateLimit-Remaining', max(0, $this->limit - $current->get()))
			->withHeader('X-RateLimit-Reset', $current->getExpiresAt())
		);


		if ($current->get() > $this->limit) {
			$datetime = new DateTime('now');
			$datetime->setTimestamp($current->getExpiresAt());
			$datetime2 = new DateTime('now');
			throw new Message(['message' => 'rate_limit', 'diff' => $datetime->formatDiff($datetime2), 'name' => $keys[$current->getKey()]], 403);
		}

		if (!$this->success) {
			$this->incr();
		}
	}

	public function response(&$view) {
		if ($this->success && (!$view instanceof Message || !$view->getErrors())) {
			$this->incr();
		}
	}

	protected function cache() {
        return Cache::rateLimit();
    }

	protected function incr() {
		foreach($this->items as $item) {
			if (!$item->isHit()) {
				$item->set(1);
				$item->expiresAfter($this->reset);
			} elseif (method_exists($item, 'incr')) {
				$item->incr(1);
			} else {
				$item->set($item->get() + 1);
			}
			$this->cache()->save($item);
		}
	}
}
