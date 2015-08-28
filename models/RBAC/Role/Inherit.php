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
class Inherit extends Model{
	protected $tables = ['rbac_role_inherits'];

	protected $columns = [
		'roleID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'inherit' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 1],
	];

	protected $primary = ['roleID', 'inherit'];

	protected $primaryCache = 900;

	public function getRoles($roles) {

	}
}
