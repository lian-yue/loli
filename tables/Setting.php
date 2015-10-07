<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-28 06:28:19
/*
/* ************************************************************************** */
namespace Table;
use Loli\Table;
class_exists('Loli\Table') || exit;
class Setting extends Table{
	protected $tables = ['settings'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'name' => ['type' => 'binary', 'length' => 32, 'key' => ['name' => 0]],
		'value' => ['type' => 'string', 'length' => 16777215],
		'auto' => ['type' => 'boolean'],
	];

	protected $primary = ['userID', 'name'];

	protected $results = NULL;

	public function getValue($name, $default = NULL, $auto = true) {
		if ($this->results === NULL && !is_array($this->results = Cache::get('get', __CLASS__))) {
			$this->results = [];
			foreach ($this->flush()->query('auto', true)->group('name')->order('ID', 'DESC')->select() as $setting) {
				$this->results[$setting->name] = $setting->value;
			}
			Cache::set($this->results, 'get', __CLASS__, 3600);
		}

		if (isset($this->results[$name])) {
			$value = $this->results[$name];
		} elseif ($auto) {
			if (($value = Cache::get('get.' . $name, __CLASS__)) !== false) {

			} elseif ($setting = $this->flush()->query('name', $name)->order('ID', 'DESC')->selectRow()) {
				Cache::set($setting->value, 'get.' . $setting->name, __CLASS__, 3600);
				$value = $setting->value;
			} else {
				return $default;
			}
			$this->results[$name] = $value;
		} else {
			return $default;
		}

		if ($default !== NULL) {
			settype($value, gettype($default));
		}
		return $value;
	}

	protected function success($name, Iterator $iterator = NULL) {
		$this->results = NULL;
		Cache::delete('get', __CLASS__);
		if ($iterator) {
			foreach ($iterator as $setting) {
				delete::Cache('get.' . $setting->name, __CLASS__);
			}
		}
	}
}