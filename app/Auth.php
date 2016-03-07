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
namespace App;
use App\Auth\Node;
use App\Auth\Role;
use App\Auth\Permission;
use App\Auth\Pelationship;

use Loli\Model;
use Loli\DateTime;
use Loli\Database\Results;


class Auth extends Model{
	protected static $table = 'auths';

	protected static $columns = [
		'token' => ['type' => 'string', 'length' => 16, 'primary' => 0],
		'profiles' => ['type' => 'array'],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'key' => ['user_id' => 0]],
		'user_agent' => ['type' => 'string', 'length' => 255],
		'ip' => ['type' => 'string', 'length' => 40, 'readonly' => true, 'hidden' => true],
		'created' => ['type' => 'datetime', 'readonly' => true, 'hidden' => true, 'key' => ['created' => 0]],
		'expired' => ['type' => 'datetime', 'null' => true, 'key' => ['expired' => 0]],
	];

	protected static $primaryCache = 1800;

	protected $cache = [];

	protected $roles = [];

	protected $now;

	public function can($name, ...$args) {
		switch ($name) {
			case 'node':
				if (!$args || !is_array($args[0])) {
					return false;
				}
				$key = implode('/', $args[0]);
				if (isset($this->cache[$key])) {
					return $this->cache[$key];
				}

				// 当前节点
				$parent = 0;
				$nodes = [];
				foreach ($args[0] as $slug) {
					if (!$slug || !($node = Node::slug($slug, $parent)) || $node->status != 1) {
						return $this->cache[$key] = false;
					}
					$parent = $node->parent;
					$nodes[] = $node;
				}

				// 所有可用角色
				$this->roles || $this->roles();

				$result = false;


				// 权限判断
				return $this->cache[$key] = $result;
				break;
			default:
				return false;
		}
	}


	public function roles() {
		if ($this->roles) {
			return $this->roles;
		}
		if (!$this->now) {
			$this->now = new DateTime('now');
		}

		$roles = [];
		foreach (Pelationship::roles($this->user_id) as $role) {
			if ($role->status != 1 || ($role->expired && $role->expired < $this->now)) {
				continue;
			}
			if ($role = Role::selectRow($role->role_id)) {
				$roles[] = $role;
			} else {
				$role->delete();
			}
		}
		if (!$roles) {
			$roles[] = Role::selectRow(1);
		}

		return $this->roles = new Results(self::_roles($roles));
	}



	private static function _roles(array $roles, array $exclude = [], array $exists = []) {
		if (!$roles) {
			return [];
		}

		usort($roles, function($a, $b) {
		 	if ($a->level == $b->level) {
				return 0;
			}
			return ($a->level < $b->level) ? 1 : -1;
		});

		$results = $include = [];
		foreach ($roles as $role) {
			if ($role->status != 1 || isset($exists[$role->id]) || in_array($role->id, $exclude)) {
				continue;
			}
			$exists[$role->id] = true;
			$results[] = $role;
			if ($role->include) {
				$include = array_merge($include, $role->include);
			}
			if ($role->exclude) {
				$exclude = array_merge($exclude, $role->exclude);
			}
		}

		if ($include) {
			$roles = [];
			foreach($include as $id) {
				if (!$id || isset($exists[$id]) || !($role = Role::selectRow($id))) {
					continue;
				}
				$roles[] = $role;
			}
			$results = array_merge($results, self::_roles($roles, $exclude, $exists));
		}

		return $results;
	}
}
