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
namespace Model\RBAC;
use Loli\Model, Loli\Cache;
class_exists('Loli\Model') || exit;
class Relationship extends Model{
	protected $tables = ['rbac_relationships'];

	protected $columns = [
		'userID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'roleID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 1],
		'expires' => ['type' => 'datetime'],
	];

	protected $primary = ['userID', 'roleID'];

	protected $primaryCache = 900;

	public function getRoles($userID) {
		$userID = intval($userID);
		if (($roles = Cache::get($userID, __CLASS__)) === false) {
			$roles = $this->query('userID', $userID, '=')->select();
			Cache::set($roles, $userID, __CLASS__, $this->primaryCache);
		}
		return $roles;
	}

	protected function success($name, Iterator $value = NULL) {
		if ($value) {
			foreach($value as $row) {
				 Cache::delete($row->userID, __CLASS__);
			}
		}
	}
}
