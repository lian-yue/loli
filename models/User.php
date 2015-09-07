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
use Loli\Table, Loli\DB\Iterator, Loli\Code;
class_exists('Loli\Table') || exit;
class User extends Table{
	protected $tables = ['users'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'displayName' => ['type' => 'string', 'length' => 32],
		'email' => ['type' => 'string', 'length' => 64, 'unique' => ['email' => 0]],
		'username' => ['type' => 'string', 'length' => 32, 'unique' => ['username' => 0]],
		'password' => ['type' => 'string', 'length' => 64],
		'timezone' => ['type' => 'string', 'length' => 32],
		'language' => ['type' => 'string', 'length' => 16],
		'IP' => ['type' => 'string', 'length' => 40],
		'created' => ['type' => 'datetime'],
	];

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;

	protected $insertID = 'ID';

	protected $form = [
		['name' => 'displayName', 'type' => 'string', 'maxlength' => 32],
		['name' => 'email', 'type' => 'email', 'required' => true],
		['name' => 'username', 'type' => 'text', 'pattern' => '^[0-9a-zA-Z_-]*[a-zA-Z][0-9a-zA-Z_-]*$', 'required' => true],
		['name' => 'password', 'type' => 'password', 'required' => true],
	];

	protected function write(Iterator $value = NULL) {
		parent::write($value);
		foreach ($this->documents as &$document) {
			foreach ($document as $name => &$value) {
				if ($value->name === 'password' && $value->value && !is_object($value->value)) {
					$value->value = Code::passwordHash($value->value);
				}
			}
		}
	}
}