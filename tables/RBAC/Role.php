<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 13:50:26
/*
/* ************************************************************************** */
namespace Model\RBAC;
use Loli\Table, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Table') || exit;
class Role extends Table{
	protected $tables = ['rbac_roles'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'string', 'length' => 64],
		'sort' => ['type' => 'integer', 'length' => 3, 'key' => ['sort' => 0]],
		'status' => ['type' => 'boolean', 'key' => ['status' => 0]],
		'description' => ['type' => 'string', 'length' => 65535],
	];

	protected $form = [
		['name' => 'name', 'type' => 'string', 'maxlength' => 64, 'required' => true],
		['name' => 'description', 'type' => 'string', 'maxlength' => 65535],
	];

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;

	protected $insertID = 'ID';

	public static function uniqid($delete = false) {
		if ($delete) {
			$uniqid = uniqid();
			Cache::set($uniqid, 'uniqid', __CLASS__, -1);
		} elseif (!$uniqid = Cache::get('uniqid', __CLASS__)) {
			$uniqid = uniqid();
			Cache::add($uniqid, 'uniqid', __CLASS__, -1);
		}
		return $uniqid;
	}

	protected function success($name, Iterator $value = NULL) {
		self::uniqid(true);
	}
}