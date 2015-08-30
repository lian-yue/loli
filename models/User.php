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
namespace Model;
use Loli\Model;
class User extends Model{
	protected $tables = ['users'];

	protected $columns = [
		'ID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'int', 'length' => 1],
		'email' => ['type' => 'text', 'length' => 32, 'unique' => ['email' => 0]],
		'username' => ['type' => 'text', 'length' => 32, 'unique' => ['username' => 0]],
		'password' => ['type' => 'text', 'length' => 64],
		'timezone' => ['type' => 'text', 'length' => 32],
		'language' => ['type' => 'text', 'length' => 16],
		'IP' => ['type' => 'text', 'length' => 40],
		'dateline' => ['type' => 'int', 'length' => 4],
	];

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;
}