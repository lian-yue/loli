<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 15:14:43
/*
/* ************************************************************************** */
namespace Model;
use Loli\Model, Loli\Cache;
class_exists('Loli\Model') || exit;
class RBAC extends Model{

	protected $userID;

	protected $results = [];


	public function has() {
		return call_user_func_array([$this, 'get'], func_get_args()) !== false;
	}

	public function get($nodeKeys, $method) {
		if ($this->userID === NULL) {
			$this->userID = $this['RBAC/Token']->currentUserID();
		}

		$cache = $this['RBAC/Node']->uniqid() . $this['RBAC/Role']->uniqid() . $this->userID . '.' . (is_array($nodeKeys) ? implode('/', $nodeKeys) : $nodeKeys) . '//' . $method;

		if (isset($this->results[$cache])) {

		} elseif (($params = Cache::get($cache, __CLASS__)) !== false) {
			$this->results[$cache] = is_array($params) ? $params : false;
		} elseif (!$data = ($this['RBAC/Node']->getKeys($nodeKeys, $method)) {
			$this->results[$cache] = false;
		} else {
			if ($this->roles === NULL) {
				$this->roles = $this['RBAC/Relationship']->getRoles($this->userID);
			}

			foreach ($this->roles as $roleID  => $expires) {
				// 方法
				if (!count($select = $this['RBAC/Permission']->select($roleID, $data[1]))) {
					$methodStatus = 0;
					$methodPrivate = false;
				} else {
					$method = reset($select);
					$methodStatus = $method->status;
					$methodPrivate = $method->private;
				}

				// 不允许方法
				if ($methodStatus < 0) {
					continue;
				}

				// 私有方法 不允许继承角色的
				if ($methodPrivate && $expires !== true) {
					continue;
				}

				// 权限递归
				$continue = false;
				foreach(array_reverse($data[0]) as $nodeID) {
					// 节点
					if (!count($select = $this['RBAC/Permission']->select($roleID, $nodeID))) {
						$nodeStatus = 0;
						$nodePrivate = false;
					} else {
						$node = reset($select);
						$nodeStatus = $node->status;
						$nodePrivate = $node->private;
					}

					// 不允许的节点
					if ($nodeStatus < 0) {
						$continue = true;
						break;
					}

					// 私有节点跳出
					if ($nodePrivate && $expires !== true) {
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
					if (!count($select = $this['RBAC/Permission']->select($roleID, $value))) {
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
}