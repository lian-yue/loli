<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-09 04:57:29
/*
/* ************************************************************************** */
namespace App\Auth;
use Loli\Model;
//  2010000
class Node extends Model{
	protected static $table = 'auth_nodes';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'string', 'length' => 32]	,
		'parent' => ['type' => 'integer', 'unsigned' => true, 'unique' => ['parent_slug' => 0]],
		'slug' => ['type' => 'string', 'length' => 64, 'binary' => true, 'unique' => ['parent_slug' => 1]],
		'status' => ['type' => 'integer', 'length' => 1, 'key' => ['status' => 0]],
		'created' => ['type' => 'datetime', 'key' => ['created' => 0]],
		'updated' => ['type' => 'timestamp'],
	];

	protected static $primary = ['id'];

	protected static $primaryCache = 1800;

	protected static $insertId = 'id';

	public static function slug($slug, $parent = 0) {
		if (!$slug) {
			return false;
		}
		if (!$node = static::database()->query('slug', $slug, '=')->query('parent', $parent, '=')->selectOne()) {
			return false;
		}
		return $node;
	}
}
