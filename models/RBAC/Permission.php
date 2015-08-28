<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:37:02
/*
/* ************************************************************************** */
namespace Model\RBAC;
use Loli\Model;
class_exists('Loli\Model') || exit;
class Permission extends Model{
	protected $tables = ['rbac_permissions'];

	protected $columns = [
		'roleID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'nodeID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 1],
		'chmod' => ['type' => 'int'],
		'private' => ['type' => 'bool'],
		'args' => ['type' => 'array'],
	];

	protected $primary = ['roleID', 'nodeID'];

	protected $primaryCache = 900;
}