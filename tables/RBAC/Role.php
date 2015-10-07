<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 13:50:26
/*
/* ************************************************************************** */
namespace Table\RBAC;
use Loli\Table, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Table') || exit;
class Role extends Table{
	protected $tables = ['rbac_roles'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'string', 'length' => 64],
		'sort' => ['type' => 'integer', 'length' => 3, 'key' => ['sort' => 0]],
		'status' => ['type' => 'boolean', 'key' => ['status' => 0]],
		'description' => ['type' => 'string', 'length' => 65535],
	];

	/*protected $form = [
		['name' => 'name', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'description', 'type' => 'text', 'maxlength' => 65535],
	];*/

	protected $primary = ['ID'];

	protected $primaryTTL = 1800;

	protected $insertID = 'ID';



	public function get($userID) {
		$userID = intval($userID);
		$uniqid = $this->uniqid();
		$userCache = $uniqid . $userID;

		// 读取所有角色
		if (!($expired = Cache::get($userCache, __CLASS__)) || !($roles = Cache::get($rolesCache = $uniqid . '.' . implode('.', array_keys($expired)), __CLASS__))) {
			$sorts = [];
			foreach($this['RBAC.Relationship']->query('userID', $userID, '=')->select() as $relationship) {
				// 角色不存在
				if (!$result = $this->flush()->selectRow($relationship->roleID)) {
					$this['RBAC.Relationship']->delete($relationship->userID, $relationship->roleID);
					continue;
				}

				// 角色被禁用
				if (!$result->status) {
					continue;
				}

				$sorts[$result->sort][$result->ID] = $relationship->expired;
			}

			ksort($sorts);

			$expired = [];
			foreach ($sorts as $sort) {
				foreach ($sort as $key => $value) {
					$expired[$key] = $value;
				}
			}
			Cache::set($expired, $userCache, __CLASS__, $this->primaryTTL);
		}



		//  角色继承
		if (empty($roles)) {


			$rolesParent = [];
			$exists = $expired;
			$querysRoleParents = array_keys($exists);

			while ($querysRoleParents) {
				$_querysRoleParents = [];
				foreach ($this['RBAC.RoleRelationship']->query('parent', $querysRoleParents, 'IN')->order('priority', 'ASC')->order('type', 'DESC')->select() as $relationship) {

					// 角色不存在
					if (!$result = $this->flush()->selectRow($relationship->roleID)) {
						$this['RBAC.RoleRelationship']->delete($relationship->parent, $relationship->roleID);
						continue;
					}

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
			Cache::set($roles, $uniqid . '.' . implode('.', array_keys($expired)), __CLASS__, $this->primaryTTL);
		}




		// 删除过期角色
		$deleteExpires = [];
		foreach ($expired as $key => $value) {
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
				$roles[$key] = isset($expired[$key]) ? $expired[$key] : true;
			}
		}


		// 没有角色找到优先级为0 的角色作为默认角色
		if (!$roles && !($select = $this->flush()->query('sort', 0, '>=')->order('sort', 'ID')->selectRow())) {
			$roleID = $select->ID;
			$roles[$roleID] = '0000-00-00 00:00:00';
			try {
				$this['RBAC.Relationship']->value('expired', '0000-00-00 00:00:00')->update($userID, $roleID) || $this['RBAC.Relationship']->values(['userID' => $userID, 'roleID' => $roleID, 'expired' => '0000-00-00 00:00:00'])->insert();
			} catch (\Exception $e) {
			}
			Cache::delete($userCache, __CLASS__);
		}
		return $roles;
	}


	public function uniqid($delete = false) {
		if ($delete) {
			$uniqid = uniqid();
			Cache::set($uniqid, 'uniqid', __CLASS__, -1);
		} elseif (!$uniqid = Cache::get('uniqid', __CLASS__)) {
			$uniqid = uniqid();
			Cache::add($uniqid, 'uniqid', __CLASS__, -1);
		}
		return $uniqid;
	}

	protected function success($name, Iterator $iterator = NULL) {
		$this->uniqid(true);
	}
}