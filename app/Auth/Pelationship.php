<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-09 04:58:06
/*
/* ************************************************************************** */
namespace App\Auth;
use Loli\Model;
class Pelationship extends Model{

	protected static $table = 'auth_pelationships';

	protected static $columns = [
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'role_id' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'status' => ['type' => 'integer', 'length' => 1],
		'created' => ['type' => 'datetime', 'hidden' => true],
		'expired' => ['type' => 'datetime', 'null' => true, 'hidden' => true],
	];

	protected static $primary = ['role_id', 'node_id'];

	protected static $primaryCache = 1800;

	protected static function roles($user_id) {
		return static::database()->query('$user_id', $user_id, '=')->select();
	}
}
