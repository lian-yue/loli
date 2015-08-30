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
use Loli\Model, Loli\Route;
class RBAC{

	protected $userID;

	protected $roles;

	protected $results = [];

	public function __construct(Route &$route) {
		$this->route = &$route;
	}

	public function has() {
		return call_user_func_array([$this, 'get'], func_get_args()) !== false;
	}

	public function get($nodeKeys, $method) {
		$cache = (is_array($nodeKeys) ? implode('/', $nodeKeys) : $nodeKeys) . '//' . $method;
		if ($this->userID === NULL) {
			// 从 Token 读到用户 ID
			if ($this->userID === NULL) {
				$token = new RBAC\Token($this->route);
				$this->userID = $token->currentUserID();

				// 读取所有角色
				$relationship = new RBAC\Relationship($this->route);
				$this->roles = $relationship->getRoles($this->userID);
			}
		}

		if (!isset($this->results[$cache])) {
			$this->results[$cache] = false;
			$node = new RBAC\Node($this->route);
			if (!$data = $node->getKeys($nodeKeys, $method)) {
				return false;
			}
		}

		return $this->results[$cache];
	}
}