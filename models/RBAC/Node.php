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
namespace Model\RBAC;
use Loli\Model, Loli\Code, Loli\DB\Iterator, Loli\Cache;
class_exists('Loli\Model') || exit;
class Node extends Model{
	protected $tables = ['rbac_nodes'];

	protected $columns = [
		'ID' => ['type' => 'int', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'parent' => ['type' => 'int', 'unsigned' => true, 'unique' => ['parent_key' => 0]],
		'key' => ['type' => 'text', 'length' => 64, 'unique' => ['parent_key' => 0]],
		'type' => ['type' => 'int', 'length' => 1],			// 0 = node, 1 = method, 2 = param
		'name' => ['type' => 'text', 'length' => 64],
		'sort' => ['type' => 'int', 'length' => 2],
		'description' => ['type' => 'text', 'length' => 65535],
	];

	protected $form = [
		['name' => 'key', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'type', 'type' => 'select', 'option' => [0 => 'Node', 1 => 'Method', 2 => 'Parameter']],
		['name' => 'name', 'type' => 'text', 'maxlength' => 64, 'required' => true],
		['name' => 'description', 'type' => 'text', 'maxlength' => 65535],
	];


	protected $primary = ['ID'];

	protected $primaryTTL = 1800;


	public function getKeys($nodeKeys, $method) {
		if (!is_array($nodeKeys)) {
			$nodeKeys = array_filter(explode('/', str_replace('\\', '/', $nodeKeys)));
		}

		if (!$nodeKeys || !$method) {
			return false;
		}
		$nodeKeys = array_map('strtolower', $nodeKeys);
		$method = strtolower($method);

		if (!$uniqid = Cache::get('uniqid', __CLASS__)) {
			$uniqid = uniqid();
			Cache::add($uniqid, 'uniqid', __CLASS__, -1);
		}
		$cacheKey = $uniqid . implode('/', $nodeKeys) . '//' . $method;


		if (!($results = Cache::get($cacheKey, __CLASS__)) || 1 == 1) {

			// 清空选项
			$this->options = [];


			// 读取节点
			$nodes = [];
			$parent = 0;
			foreach ($nodeKeys as $key) {
				$select = $this->flush()->fields(['ID'])->query('parent', $parent, '=')->query('key', $key, '=')->query('type', 0, '=')->limit(1)->select();
				if (!count($select)) {
					return false;
				}
				$node = reset($select);
				$parent = $node->ID;
				$nodes[] = $node->ID;
			}



			// 读方法
			$this->querys = [];
			$select = $this->flush()->fields(['ID'])->query('parent', end($nodes), '=')->query('key', $method, '=')->query('type', 0, '=')->limit(1)->select();
			if (count($select)) {
				return false;
			}
			$method = reset($select)->ID;


			// 读取参数
			$params = [];
			$this->fields = $this->querys = [];
			foreach($this->flush()->fields(['ID', 'key'])->query('parent', $method, '=')->query('type', 3, '=')->select() as $param) {
				$params[$param->key] = $param->ID;
			}

			$results = [$nodes, $method, $params];
			Cache::set($results, $cacheKey, __CLASS__, $this->primaryTTL);
		}
		return $results;
	}

	protected function success($name, Iterator $value = NULL) {
		Cache::delete('uniqid', __CLASS__);
	}
}