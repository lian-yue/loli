<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:37:30
/*
/* ************************************************************************** */
namespace App\User;

use Loli\Model;
use Loli\Route;
use Loli\DateTime;
// 3030000
class Log extends Model{
	protected static $table = 'user_logs';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'type' => ['type' => 'string', 'length' => 32, 'key' => ['user_type' => 1]],
		'value' => ['type' => 'string', 'length' => 255, 'hidden' => true],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'key' => ['user_type' => 0]],
		'user_agent' => ['type' => 'string', 'length' => 255],
		'ip' => ['type' => 'string', 'length' => 40],
		'token' => ['type' => 'string', 'length' => 16, 'hidden' => true],
		'created' => ['type' => 'datetime', 'key' => ['created' => 0]],
	];

	public function insert() {
		if (!$this->user_id) {
			$this->user_id = Route::auth()->user_id;
		}
		if (!$this->created) {
			$this->created = new DateTime('now');
		}
		if (!$this->ip) {
			$this->ip = Route::ip();
		}
		if (!$this->token) {
			$this->token = Route::token()->get();
		}
		if (!$this->user_agent) {
			$this->user_agent = substr(Route::request()->getHeaderLine('User-Agent'), 0, 255);
		}
		return parent::insert();
	}
}
