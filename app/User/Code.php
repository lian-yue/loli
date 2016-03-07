<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-06 12:08:17
/*
/* ************************************************************************** */
namespace App\User;
use Loli\Model;
// 3020000
class Code extends Model{
	protected static $table = 'user_codes';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'key' => ['user_id' => 0]],
		'type' => ['type' => 'string', 'length' => 32, 'key' => ['type' => 0]],
		'code' => ['type' => 'string', 'length' => 8, 'hidden' => true],
		'args' => ['type' => 'array', 'hidden' => true],
		'created' => ['type' => 'datetime', 'hidden' => true],
		'expired' => ['type' => 'datetime', 'hidden' => true, 'null' => true],
		'deleted' => ['type' => 'datetime', 'hidden' => true, 'null' => true, 'key' => ['deleted' => 0]],
	];
}
