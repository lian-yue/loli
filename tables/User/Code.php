<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-28 01:28:53
/*
/* ************************************************************************** */
namespace Table\User;
use Loli\Table;
class Code extends Table{

	protected $tables = ['user_codes'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'userID' => ['type' => 'integer', 'unsigned' => true, 'key' => ['userIDUsedType' => 0]],
		'used' => ['type' => 'boolean', 'length' => 1, 'key' => ['userIDUsedType' => 1]],
		'type' => ['type' => 'binary', 'length' => 16, 'key' => ['userIDUsedType' => 2]],
		'code' => ['type' => 'string', 'length' => 16],
		'created' => ['type' => 'timestamp'],
	];
}