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
use Loli\Table;
class_exists('Loli\Table') || exit;
class Permission extends Table{
	protected $tables = ['rbac_permissions'];

	protected $columns = [
		'roleID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'nodeID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'status' => ['type' => 'integer', 'length' => 2], 	// -1 ＝ 拒绝 0 = 向上继承  1 = 继承节点 2 = 继承方法
		'private' => ['type' => 'boolean'],
	];

	protected $form = [
		['name' => 'status', 'type' => 'select', 'option' => [0 => 'Inherit', -1 => 'Allowed', 1 => 'Allow node', 2 => 'Allow Methods', 4 => 'Allow All']],
	];

	protected $primary = ['roleID', 'nodeID'];

	protected $primaryTTL = 1800;

	protected function success($name, Iterator $value = NULL) {
		Node::uniqid(true);
	}
}