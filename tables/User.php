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
namespace Table;
use Loli\Table, Loli\DB\Iterator, Loli\Crypt\Password;
class_exists('Loli\Table') || exit;
class User extends Table{
	protected $tables = ['users'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'username' => ['type' => 'string', 'length' => 32, 'unique' => ['username' => 0]],
		'password' => ['type' => 'string', 'length' => 64],
		'nicename' => ['type' => 'string', 'length' => 32],
		'description' => ['type' => 'string', 'length' => 255],
		'gender' => ['type' => 'integer'],
		'birthday' => ['type' => 'date'],
		'timezone' => ['type' => 'string', 'length' => 32],
		'language' => ['type' => 'string', 'length' => 16],
		'IP' => ['type' => 'string', 'length' => 40],
		'registered' => ['type' => 'timestamp'],
	];

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;

	protected $insertID = 'ID';

	protected function write($name, Iterator $iterator = NULL) {
		foreach ($this->documents as &$document) {
			foreach ($document as &$column) {
				if ($column->name === 'password' && $column->value && !is_object($column->value)) {
					$column->value = Password::hash($column->value);
				}
			}
		}
	}
}