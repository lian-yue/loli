<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-09 04:55:14
/*
/* ************************************************************************** */
namespace App\Auth;
use Loli\Model;
//  2020000

// type = 0 public  能允许继承
// type = 1 protected  只允许直接继承不允许递归继承
// type = 2 private 不能允许继承权限只能自己给予


class Role extends Model{
	protected static $table = 'auth_roles';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'string', 'length' => 32],
		'type' => ['type' => 'integer', 'length' => 1, 'key' => ['type' => 0]],
		'include' => ['type' => 'array'],
		'exclude' => ['type' => 'array'],
		'level' => ['type' => 'integer', 'length' => 2, 'key' => ['level' => 0]],
		'status' => ['type' => 'integer', 'length' => 1, 'key' => ['status' => 0]],
		'created' => ['type' => 'datetime', 'hidden' => true, 'key' => ['created' => 0]],
		'updated' => ['type' => 'timestamp', 'hidden' => true],
	];

	protected static $primary = ['id'];

	protected static $primaryCache = 1800;

	protected static $insertId = 'id';
}
