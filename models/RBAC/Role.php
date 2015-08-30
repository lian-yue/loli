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
use Loli\Model, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Model') || exit;
class Role extends Model{
	protected $tables = ['rbac_roles'];

	protected $columns = [
		'ID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'text', 'length' => 64],
		'sort' => ['type' => 'int', 'length' => 3, 'key' => ['sort' => 0]],
		'status' => ['type' => 'bool', 'key' => ['status' => 0]],
		'description' => ['type' => 'text', 'length' => 65535],
	];

	protected $form = [
		['name' => 'name', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'description', 'type' => 'text', 'maxlength' => 65535],
	];

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;

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
		Role::uniqid(true);
	}
}