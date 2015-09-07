<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:49:21
/*
/* ************************************************************************** */
namespace Model\RBAC\Role;
use Loli\Model, Model\RBAC\Role, Loli\DB\Iterator;
class_exists('Loli\Model') || exit;
class Relationship extends Model{
	protected $tables = ['rbac_role_relationships'];

	protected $columns = [
		'parent' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'roleID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'type' => ['type' => 'boolean'],
		'priority' => ['type' => 'integer', 'length' => 1],
	];

	protected $primary = ['parent', 'roleID'];

	protected function success($name, Iterator $value = NULL) {
		Role::uniqid(true);
	}
}
