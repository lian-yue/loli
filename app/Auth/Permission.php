<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-09 04:58:06
/*
/* ************************************************************************** */
namespace App\Auth;
use Loli\Model;
//  2030000
class Permission extends Model{
	protected static $table = 'auth_permissions';

	protected static $columns = [
		'role_id' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'node_id' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'permission' => ['type' => 'integer', 'length' => 1],
		'settings' => ['type' => 'array'],
	];

	protected static $primary = ['role_id', 'node_id'];

	protected static $primaryCache = 1800;
}
