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
use Loli\Model, Loli\Cache, Loli\DB\Iterator;
class_exists('Loli\Model') || exit;
class Relationship extends Model{
	protected $tables = ['rbac_relationships'];

	protected $columns = [
		'userID' => ['type' => 'int', 'unsigned' => true, 'primary' => 0],
		'roleID' => ['type' => 'int', 'unsigned' => true, 'primary' => 1],
		'expires' => ['type' => 'datetime'],
	];

	protected $primary = ['userID', 'roleID'];

	protected $primaryTTL = 1800;

	public function getRoles($userID) {
		$userID = intval($userID);
		$cache = Role::uniqid(). $userID;
		if (!$array = Cache::get($cache, __CLASS__)) {
			$role = new Role($this->route);


			// 读取所有角色
			$roles = [];
			foreach($this->flush()->query('userID', $userID, '=')->select() as $relationship) {
				// 角色不存在
				if (!count($select = $role->flush()->select($relationship->roleID))) {
					$this->flush()->query($relationship->userID, $relationship->roleID);
					continue;
				}

				$result = reset($select);

				// 角色被禁用
				if (!$result->status) {
					continue;
				}
				$roles[$result->ID] = [$result->sort, $relationship->expires];
			}

			// 读取继承角色
			$roleRelationship = new Role\Relationship($this->route);

			// 多层角色关系
			$rolesParent = [];

			$querysRoleID = array_keys($roles);
			while ($querysRoleID) {
				$_querysRoleID = [];
				foreach ($roleRelationship->flush()->query('roleID', $querysRoleID, 'IN')->order('priority', 'ASC')->order('type', 'DESC')->select() as $relationship) {
					// 排斥掉
					if ($relationship->type === 1) {
						$roles[$relationship->relationship] = false;
						continue;
					}

					// 已经存在的
					if (isset($roles[$relationship->relationship])) {
						continue;
					}

					// 已经排斥掉的
					if (empty($roles[$relationship->roleID])) {
						continue;
					}

					// 角色不存在
					if (!count($select = $role->flush()->select($relationship->relationship))) {
						$this->flush()->delete($relationship->relationship);
						continue;
					}

					$result = reset($select);

					// 角色被禁用
					if (!$result->status) {
						continue;
					}

					// 写入角色
					$roles[$result->ID] = true;

					// 写入Root
					$rolesParent[$result->ID] = isset($rolesParent[$relationship->roleID]) ? $rolesParent[$relationship->roleID] : $relationship->roleID;

					// 写入下一级
					if (!isset($roles[$relationship->relationship])) {
						$_querysRoleID[] = $relationship->relationship;
					}
				}
				$querysRoleID = $_querysRoleID;
			}
			$roles = array_filter($roles);
			Cache::set([$roles, $rolesParent], $cache, __CLASS__, $this->primaryTTL);
		} else {
			list($roles, $rolesParent) = $array;
		}

		foreach ($roles as $roleID => $expires) {
			if (!$expires || $expires === true || $expires !== '0000-00-00 00:00:00' || $relationship->expires > (isset($gmdate) ? $gmdate : ($gmdate = gmdate('Y-m-d H:i:s')))) {
				continue;
			}
			$roles[$roleID] = false;
			foreach($rolesParent as $key => $value) {
				if ($value === $roleID && !empty($roles[$key]) && !is_string($roles[$key])) {
					$roles[$key] = false;
				}
			}
		}

		if (!$roles = array_filter($roles)) {
			if (count($select = (new Role($this->route))->order('sort', 'ASC')->limit(1)->select())) {
				$roleID = reset($select)->ID;
				$roles[$roleID] = '0000-00-00 00:00:00';

				try {
					$this->flush()->value('expires', '0000-00-00 00:00:00')->limit(1)->update($userID, $roleID) || $this->flush()->values(['userID' => $userID, 'roleID' => $roleID, 'expires' => '0000-00-00 00:00:00'])->insert();
				} catch (\Exception $e) {
				}
				Cache::delete($cache, __CLASS__);
			}
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
