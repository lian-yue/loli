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
namespace Model\RBAC;
use Loli\Model;
class_exists('Loli\Model') || exit;
class Node extends Model{
	protected $tables = ['rbac_nodes'];

	protected $columns = [
		'ID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'parent' => ['type' => 'int', 'unsigned' => true, 'unique' => ['parent_key' => 0]],
		'key' => ['type' => 'text', 'length' => 64, 'unique' => ['parent_key' => 0]],
		'type' => ['type' => 'int', 'length' => 1],
		'name' => ['type' => 'text', 'length' => 32],
		'sort' => ['type' => 'int', 'length' => 2],
		'description' => ['type' => 'text', 'length' => 65535],
	];

	protected $primary = ['ID'];

	protected $primaryCache = 900;
}