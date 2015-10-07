<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-29 02:46:35
/*
/* ************************************************************************** */
namespace Table\User;
use Loli\Table;
class Log extends Table{

	protected $tables = ['user_logs'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'userID' => ['type' => 'integer', 'unsigned' => true, 'key' => ['userIDType' => 0]],
		'type' => ['type' => 'binary', 'length' => 16, 'key' => ['userIDType' => 1]],
		'IP' => ['type' => 'string', 'length' => 40],
		'value' => ['type' => 'string', 'length' => 255],
		'created' => ['type' => 'timestamp'],
	];
}