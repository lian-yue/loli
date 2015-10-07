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
use Loli\Table, Loli\Cache, Loli\DB\Iterator, Loli\DB\Param;
class_exists('Loli\Table') || exit;
class Profile extends Table{
	protected $tables = ['user_profiles'];

	protected $columns = [
		'ID' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'userID' => ['type' => 'integer', 'unsigned' => true, 'key' => ['userStatusName' => 0]],
		'name' => ['type' => 'binary', 'length' => 32, 'key' => ['userStatusName' => 1]],
		'status' => ['type' => 'integer', 'length' => 1, 'key' => ['userStatusName' => 2]],
		'level' => ['type' => 'integer', 'length' => 1],
		'value' => ['type' => 'string', 'length' => 65535],
		'created' => ['type' => 'timestamp'],
	];

	protected $primary = ['ID'];

	protected $results = [];

	public function getAll($userID) {
		$userID = (int) $userID;
		if (isset($this->results[$userID])) {
		} elseif (is_array($results = Cache::get($userID, __CLASS__))) {
			$this->results[$userID] = $results;
		} else {
			$results = [];
			foreach($this->fields(['name', 'value', 'level'])->query('userID', $userID, '=')->query('status', 1, '=')->group('name')->order('ID', 'DESC')->select() as $profile) {
				$results[$select->name] = $profile;
			}
			$this->results[$userID] = $results;
			Cache::set($results,$userID, __CLASS__, 1800);
		}
		return $this->results[$userID];
	}

	public function getValue($userID, $name, $default = NULL, $level = true) {
		if (($results = $this->getAll($userID)) && isset($results[$name])) {
			$result = $results[$name];
			if ($level && $result->level > 0) {
				switch ($result->level) {
					case 9:
						// 保密
						if ($userID != $this->table['RBAC.Token']->userID()) {
							return false;
						}
						break;
					case 1:
						// 会员
						if (!$this->table['RBAC.Token']->userID()) {
							return false;
						}
				}
			}
			$value = $result->value;
			if ($default !== NULL) {
				settype($value, gettype($default));
			}
			return $value;
		}
		return $default;
	}

	protected function success($name, Iterator $iterator = NULL) {
		$this->results = [];

		foreach ($iterator ? $iterator : $this->documentsInsert() as $profile) {
			$profile->userID && Cache::delete($profile->userID, __CLASS__);
		}


		if ($user = $this['User']->selectRow()) {
			$arrays = [];
			switch ($name) {
				case 'insert':
					foreach ($this->documentsInsert() as $profile) {
						if ($profile->status > 0 && $profile->name && isset($user->{$profile->name})) {
							$arrays[$profile->ID] = [$profile->userID, $profile->name, $profile->value];
						}
					}
					break;
				case 'update':
					if ($this->documentUpdate()->status > 0) {
						foreach ($iterator as $profile) {
							if (isset($user->{$profile->name})) {
								$arrays[$profile->ID] = [$profile->userID, $profile->name, $profile->value];
							}
						}
					}
					break;
				case 'delete':
					$class = __CLASS__;
					$class = new $class($this->route);
					foreach ($this->documentsInsert() as $profile) {
						if ($profile->status > 0 && isset($user->{$profile->name}) && ($profile = $class->flush()->querys(['userID' => $profile->userID, 'status' => 1, 'name' => $profile->name])->selectRow())) {
							$arrays[$profile->ID] = [$profile->userID, $profile->name, $profile->value];
						}
					}

			}
			krsort($arrays, SORT_NUMERIC);

			$updates = [];
			foreach ($arrays as $value) {
				$updates[$value[0]][$value[1]] = $value[2];
			}

			foreach ($updates as $userID => $values) {
				$this['User']->values($values)->update($userID);
			}
		}
	}
}