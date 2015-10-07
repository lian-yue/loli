<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:43:02
/*
/* ************************************************************************** */
namespace Table\RBAC;
use Loli\Table, Loli\Cache, Loli\DB\Iterator;
class_exists('Loli\Table') || exit;
class Relationship extends Table{
	protected $tables = ['rbac_relationships'];

	protected $columns = [
		'userID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'roleID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'expired' => ['type' => 'datetime'],
	];

	protected $primary = ['userID', 'roleID'];

	protected function success($name, Iterator $iterator = NULL) {
		$uniqid = $this['RBAC.Role']->uniqid();
		foreach ($iterator ? $iterator : $this->documentsInsert() as $values) {
			if (isset($values->userID) && is_scalar($values->userID)) {
				Cache::get($uniqid . $values->userID, __CLASS__);
			}
		}
	}
}
