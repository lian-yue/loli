<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-11 08:11:34
/*
/* ************************************************************************** */
namespace Table\User;
use Loli\Table, Loli\Cache, Loli\DB\Iterator;
class_exists('Loli\Table') || exit;
class Setting extends Table{
	protected $tables = ['user_settings'];

	protected $columns = [
		'userID' => ['type' => 'integer', 'unsigned' => true, 'primary' => 0],
		'name' => ['type' => 'binary', 'length' => 32, 'primary' => 1],
		'value' => ['type' => 'string', 'length' => 16777215],
	];

	protected $primary = ['userID', 'name'];

	protected $results = [];

	public function getAll($userID) {
		$userID = (int) $userID;
		if (isset($this->results[$userID])) {
		} elseif (is_array($results = Cache::get($userID, __CLASS__))) {
			$this->results[$userID] = $results;
		} else {
			$results = [];
			foreach($this->flush()->field(['name', 'value'])->query('userID', $userID, '=')->select() as $setting) {
				$results[$setting->name] = $setting;
			}
			$this->results[$userID] = $results;
			Cache::set($results, $userID, __CLASS__, 1800);
		}
		return $this->results[$userID];
	}

	public function getValue($userID, $name, $default = NULL) {
		if (($results = $this->getAll($userID)) && isset($results[$name])) {
			if ($default !== NULL) {
				settype($results[$name], gettype($default));
			}
			return $results[$name];
		}
		return $default;
	}

	protected function success($name, Iterator $iterator = NULL) {
		$this->results = [];
		$user = $this['User']->selectRow();

		$updates = [];
		foreach ($iterator ? $iterator : $this->documentsInsert() as $setting) {
			Cache::delete($setting->userID, __CLASS__);
			if ($user && isset($user->{$setting->name})) {
				$updates[$setting->userID][$setting->name] = $setting->value;
			}
		}

		foreach ($updates as $userID => $values) {
			$this['User']->values($values)->update($userID);
		}
	}
}