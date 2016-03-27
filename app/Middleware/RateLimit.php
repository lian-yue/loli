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

	protected $limit = 60;

	protected $reset = 1800;

	protected $bind = ['ip', 'token'];

	protected $success = false;

	private $ip;

	private $token;

	private $cache;

	private $items = [];

	public function __construct(array $config) {
		parent::__construct($config);

		$this->ip = Route::ip();
		$this->token = Route::token()->get();
		$this->cache = Cache::rateLimit();
	}

	public function request(array &$params) {
        return;
		$key = strtolower(implode('/', Route::controller())) . '.' . $this->limit . '.' . $this->reset;


		$keys = [];
		foreach ($this->bind as $bind) {
			if (in_array($bind, ['ip', 'token'], true)) {
				$keys[] = $this->$bind . $key;
			}
		}
		$this->items = $this->cache->getItems($keys);

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
			throw new Message(['message' => 'rate_limit', 'diff' => $datetime->formatDiff($datetime2)], 403);
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
			$this->cache->save($item);
		}
	}

}
