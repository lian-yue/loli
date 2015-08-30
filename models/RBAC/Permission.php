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
		'roleID' => ['type' => 'int', 'unsigned' => true, 'primary' => 0],
		'nodeID' => ['type' => 'int', 'unsigned' => true, 'primary' => 1],
		'status' => ['type' => 'int'],
		'private' => ['type' => 'bool'],
	];

	protected $primary = ['roleID', 'nodeID'];

	protected $primaryTTL = 1800;
}