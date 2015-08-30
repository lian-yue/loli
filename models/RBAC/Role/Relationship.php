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
use Loli\Model;
class_exists('Loli\Model') || exit;
class Relationship extends Model{
	protected $tables = ['rbac_role_relationships'];

	protected $columns = [
		'roleID' => ['type' => 'int', 'unsigned' => true, 'primary' => 0],
		'relationship' => ['type' => 'int', 'unsigned' => true, 'primary' => 1],
		'type' => ['type' => 'bool'],
		'priority' => ['type' => 'int', 'length' => 1],
	];

	protected $primary = ['roleID', 'inherit'];
}
