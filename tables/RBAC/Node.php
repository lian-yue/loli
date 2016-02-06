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
namespace Table\RBAC;
use Loli\Table, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Table') || exit;
class Node extends Table{
	protected $tables = ['rbac_nodes'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'parent' => ['type' => 'integer', 'unsigned' => true, 'unique' => ['parent_key' => 0]],
		'key' => ['type' => 'binary', 'length' => 64, 'unique' => ['parent_key' => 0]],
		'type' => ['type' => 'integer', 'length' => 1],			// 0 = node, 1 = method, 2 = param
		'name' => ['type' => 'string', 'length' => 64],
		'sort' => ['type' => 'integer', 'length' => 2],
		'description' => ['type' => 'string', 'length' => 65535],
	];

	/*protected $form = [
		['name' => 'key', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'type', 'type' => 'select', 'option' => [0 => 'Node', 1 => 'Method', 2 => 'Parameter']],
		['name' => 'name', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'description', 'type' => 'text', 'maxlength' => 65535],
	];*/


	protected $primary = ['ID'];

	protected $insertID = 'ID';

	public function getKeys($nodeKeys, $method) {
		if (!is_array($nodeKeys)) {
			$nodeKeys = array_filter(explode('/', strtr($nodeKeys, '\\.', '//')));
		}

		if (!$nodeKeys || !$method) {
			return false;
		}
		$uniqid = $this->uniqid();

		$nodeKeys = array_map('strtolower', $nodeKeys);
		$method = strtolower($method);
		$cacheKey = $uniqid . implode('/', $nodeKeys) . '//' . $method;


		if (!$results = Cache::get($cacheKey, __CLASS__)) {

			// 清空选项
			$this->options = [];


			// 读取节点
			$nodes = [];
			$parent = 0;
			foreach ($nodeKeys as $key) {
				if (!$node = $this->flush()->fields(['ID'])->query('parent', $parent, '=')->query('key', $key, '=')->query('type', 0, '=')->selectRow()) {
					return false;
				}
				$parent = $node->ID;
				$nodes[] = $node->ID;
			}



			// 读方法
			if (!$select = $this->flush()->fields(['ID'])->query('parent', end($nodes), '=')->query('key', $method, '=')->query('type', 0, '=')->selectRow()) {
				return false;
			}
			$method = $select->ID;


			// 读取参数
			$params = [];
			foreach($this->flush()->fields(['ID', 'key'])->query('parent', $method, '=')->query('type', 3, '=')->select() as $param) {
				$params[$param->key] = $param->ID;
			}

			$results = [$nodes, $method, $params];
			Cache::set($results, $cacheKey, __CLASS__, 3600);
		}
		return $results;
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