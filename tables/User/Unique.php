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
class Unique extends Table{

	protected $tables = ['user_uniques'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'userID' => ['type' => 'integer', 'unsigned' => true, 'key' => ['userType' => 0]],
		'name' => ['type' => 'binary', 'length' => 32, 'key' => ['userType' => 1], 'unique' => ['typeValue' => 0]],
		'value' => ['type' => 'string', 'length' => 128, 'unique' => ['typeValue' => 1]],
		'extra' => ['type' => 'array', 'length' => 65535],
	];
}