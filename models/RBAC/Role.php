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
use Loli\Model;
class_exists('Loli\Model') || exit;
class Role extends Model{
	protected $tables = ['rbac_roles'];

	protected $columns = [
		'ID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'text', 'length' => 64],
		'status' => ['type' => 'bool', 'key' => ['status' => 0]],
		'description' => ['type' => 'text', 'length' => 65535],
	];

	protected $primary = ['ID'];

	protected $primaryCache = 900;
}