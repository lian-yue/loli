<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 15:07:17
/*
/* ************************************************************************** */
namespace Model\RBAC\Role;
use Loli\Model;
class_exists('Loli\Model') || exit;
class Constraint extends Model{
	protected $tables = ['rbac_role_constraint'];

	protected $columns = [
		'roleID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'constraint' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 1],
		'priority' => ['type' => 'int', 'length' => 1],
	];

	protected $primary = ['roleID', 'constraint'];

	protected $primaryCache = 900;

	public function getRoles($roles) {

	}
}
