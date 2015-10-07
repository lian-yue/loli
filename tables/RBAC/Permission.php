<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:37:02
/*
/* ************************************************************************** */
namespace Table\RBAC;
use Loli\Table, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Table') || exit;
class Permission extends Table{

	protected $tables = ['rbac_permissions'];

	protected $columns = [
		'roleID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'nodeID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 1],
		'status' => ['type' => 'integer', 'length' => 2], 	// -1 ＝ 拒绝 0 = 向上继承  1 = 继承节点 2 = 继承方法
		'private' => ['type' => 'boolean'],
	];

	/*protected $form = [
		['name' => 'status', 'type' => 'select', 'option' => [0 => 'Inherit', -1 => 'Allowed', 1 => 'Allow node', 2 => 'Allow Methods', 4 => 'Allow All']],
	];*/

	protected $primary = ['roleID', 'nodeID'];

	protected $primaryTTL = 1800;

	protected $roles;

	protected $results = [];

	public function has() {
		return call_user_func_array([$this, 'get'], func_get_args()) !== false;
	}

	public function get($nodeKeys, $method) {
		$userID = $this['RBAC.Token']->userID();
		$cache = $this['RBAC.Node']->uniqid() . $this['RBAC.Role']->uniqid() . $userID . '.' . (is_array($nodeKeys) ? implode('/', $nodeKeys) : $nodeKeys) . '//' . $method;

		if (isset($this->results[$cache])) {

		} elseif (($params = Cache::get($cache, __CLASS__)) !== false) {
			$this->results[$cache] = is_array($params) ? $params : false;
		} elseif (!$data = $this['RBAC.Node']->getKeys($nodeKeys, $method)) {
			$this->results[$cache] = false;
		} else {
			if ($this->roles === NULL) {
				$this->roles = $this['RBAC.Relationship']->getRoles($userID);
			}

			foreach ($this->roles as $roleID  => $expired) {
				// 方法
				if (!$method = $this->flush()->selectRow($roleID, $data[1])) {
					$methodStatus = 0;
					$methodPrivate = false;
				} else {
					$methodStatus = $method->status;
					$methodPrivate = $method->private;
				}

				// 不允许方法
				if ($methodStatus < 0) {
					continue;
				}

				// 私有方法 不允许继承角色的
				if ($methodPrivate && $expired !== true) {
					continue;
				}

				// 权限递归
				$continue = false;
				foreach(array_reverse($data[0]) as $nodeID) {
					// 节点
					if (!$node = $this->flush()->selectRow($roleID, $nodeID)) {
						$nodeStatus = 0;
						$nodePrivate = false;
					} else {
						$nodeStatus = $node->status;
						$nodePrivate = $node->private;
					}

					// 不允许的节点
					if ($nodeStatus < 0) {
						$continue = true;
						break;
					}

					// 私有节点跳出
					if ($nodePrivate && $expired !== true) {
						$continue = true;
						break;
					}

					// 继承
					if ($nodeStatus > 0) {
						// 继承节点判断   继承方法判断
						$continue = !($nodeStatus & 1) || ($methodStatus == 0 && !($nodeStatus & 2));
						break;
					}
				}

				if ($continue) {
					continue;
				}

				$params = [];
				foreach ($data[2] as $key => $value) {
					if (!$select = $this->flush()->selectRow($roleID, $value)) {
						$params[$key] = 0;
					} else {
						$params[$key] = reset($select)->status;
					}
				}
				$this->results[$cache] = $params;
				break;
			}
			$this->results[$cache] = isset($this->results[$cache]) ? $this->results[$cache] : false;
			Cache::set(is_array($this->results[$cache]) ? $this->results[$cache] : 0, $cache, __CLASS__, 1800);
		}
		return $this->results[$cache];
	}



	protected function success($name, Iterator $iterator = NULL) {
		$this['RBAC.Node']->uniqid(true);
	}
}