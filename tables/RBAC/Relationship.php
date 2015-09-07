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
use Loli\Table, Loli\Cache, Loli\DB\Iterator;
class_exists('Loli\Table') || exit;
class Relationship extends Table{
	protected $tables = ['rbac_relationships'];

	protected $columns = [
		'userID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'roleID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'expires' => ['type' => 'datetime'],
	];

	protected $primary = ['userID', 'roleID'];

	protected $primaryTTL = 1800;

	public function getRoles($userID) {
		$userID = intval($userID);
		$uniqid = Role::uniqid();
		$userCache = $uniqid . $userID;

		// 读取所有角色
		if (!($expires = Cache::get($userCache, __CLASS__)) || !($roles = Cache::get($rolesCache = $uniqid . '.' . implode('.', array_keys($expires)), __CLASS__))) {
			$role = new Role($this->route);
			$sorts = [];
			foreach($this->flush()->query('userID', $userID, '=')->select() as $relationship) {
				// 角色不存在
				if (!count($select = $role->flush()->select($relationship->roleID))) {
					$this->flush()->delete($relationship->userID, $relationship->roleID);
					continue;
				}

				$result = reset($select);

				// 角色被禁用
				if (!$result->status) {
					continue;
				}

				$sorts[$result->sort][$result->ID] = $relationship->expires;
			}

			ksort($sorts);

			$expires = [];
			foreach ($sorts as $sort) {
				foreach ($sort as $key => $value) {
					$expires[$key] = $value;
				}
			}
			Cache::set($expires, $userCache, __CLASS__, $this->primaryTTL);
		}



		//  角色继承
		if (empty($roles)) {
			$roleRelationship = new Role\Relationship($this->route);


			$rolesParent = [];
			$exists = $expires;
			$querysRoleParents = array_keys($exists);

			while ($querysRoleParents) {
				$_querysRoleParents = [];
				foreach ($roleRelationship->flush()->query('parent', $querysRoleParents, 'IN')->order('priority', 'ASC')->order('type', 'DESC')->select() as $relationship) {

					// 角色不存在
					if (!count($select = $role->flush()->select($relationship->roleID))) {
						$roleRelationship->flush()->delete($relationship->parent, $relationship->roleID);
						continue;
					}

					$result = reset($select);

					// 角色被禁用
					if (!$result->status) {
						continue;
					}

					// 排斥掉
					if ($relationship->type) {
						$exists[$relationship->relationship] = false;
						continue;
					}

					// 已经存在的
					if (isset($exists[$relationship->roleID])) {
						continue;
					}

					// 已经排斥掉的
					if (empty($exists[$relationship->parent])) {
						continue;
					}

					$exists[$result->ID] = true;

					// 写入角色
					$sorts[$result->sort][$result->ID] = false;

					// 写入Root
					$rolesParent[$result->ID] = isset($rolesParent[$relationship->parent]) ? $rolesParent[$relationship->parent] : $relationship->parent;

					// 写入下一级
					if (!isset($exists[$relationship->roleID])) {
						$_querysRoleParents[] = $relationship->roleID;
					}
				}
				$querysRoleParents = $_querysRoleParents;
			}

			ksort($sorts);
			$roles = [];
			foreach ($sorts as $sort) {
				foreach ($sort as $key => $value) {
					if (empty($exists[$key])) {
						continue;
					}
					$roles[$key] = isset($rolesParent[$key]) ? $rolesParent[$key] : false;
				}
			}
			Cache::set($roles, $uniqid . '.' . implode('.', array_keys($expires)), __CLASS__, $this->primaryTTL);
		}




		// 删除过期角色
		$deleteExpires = [];
		foreach ($expires as $key => $value) {
			if (!isset($roles[$key])) {
				continue;
			}
			if ($value !== '0000-00-00 00:00:00' && $value < (isset($gmdate) ? $gmdate : ($gmdate = gmdate('Y-m-d H:i:s')))) {
				$deleteExpires[] = $key;
			}
		}
		foreach($roles as $key => $value) {
			if (in_array($key, $deleteExpires)|| in_array($value, $deleteExpires)) {
				unset($roles[$key]);
			} else {
				$roles[$key] = isset($expires[$key]) ? $expires[$key] : true;
			}
		}


		// 没有角色找到优先级为0 的角色作为默认角色
		if (!$roles && count($select = (new Role($this->route))->query('sort', 0, '>=')->order('sort', 'ID')->limit(1)->select())) {
			$roleID = reset($select)->ID;
			$roles[$roleID] = '0000-00-00 00:00:00';
			try {
				$this->flush()->value('expires', '0000-00-00 00:00:00')->limit(1)->update($userID, $roleID) || $this->flush()->values(['userID' => $userID, 'roleID' => $roleID, 'expires' => '0000-00-00 00:00:00'])->insert();
			} catch (\Exception $e) {
			}
			Cache::delete($userCache, __CLASS__);
		}
		return $roles;
	}

	protected function success($name, Iterator $value = NULL) {
		$uniqid = Role::uniqid();
		if ($value) {
			foreach($value as $row) {
				Cache::get($uniqid . $row->userID, __CLASS__);
			}
		} else {
			foreach ($this->documents as $documents) {
				foreach ($documents as $name => $value) {
					if ($value instanceof Param) {
						if ($value->name === 'userID' && is_scalar($value->value)) {
							Cache::get($uniqid . $value->value, __CLASS__);
						}
					} elseif ($name === 'userID' && is_scalar($value->value)) {
						Cache::get($uniqid . $value, __CLASS__);
					}
				}
			}
		}
	}
}
