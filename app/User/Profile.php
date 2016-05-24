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
namespace App\User;

use App\User;

use Loli\Model;
use Loli\DateTime;


class Profile extends Model{
	protected static $table = 'user_profiles';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'key' => ['user_id' => 0]],
		'type' => ['type' => 'string', 'length' => 32, 'key' => ['type_status_value' => 0]],
		'status' => ['type' => 'integer', 'length' => 1, 'key' => ['type_status_value' => 1]],
		'value' => ['type' => 'string', 'length' => 128, 'key' => ['type_status_value' => 2]],
		'level' => ['type' => 'integer','length' => 1, 'key' => ['level' => 0]],
		'args' => ['type' => 'array', 'hidden' => true],
		'created' => ['type' => 'datetime', 'hidden' => true],
		'deleted' => ['type' => 'datetime', 'hidden' => true, 'null' => true, 'key' => ['deleted' => 0]],
	];

	public static $multiple = [
		'email' => 10,
		'phone' => 10,
	];

	public function insert() {
		if (!$this->created) {
			$this->created = new DateTime('now');
		}
		$insert = parent::insert();
		if ($this->status != -1 && !$this->deleted) {
			if (isset(User::$profiles[$this->type])) {
				$user = new User(['id' => $this->user_id]);
				$user->select();
				$user->profiles = [$this->type => $this->value] + $user->profiles;
				$user->update();
			}
			if (empty(static::$multiple[$this->type]) || static::$multiple[$this->type] === 1) {
				static::database()->query('deleted', null, '=')->query('id', $this->id, '!=')->query('user_id', $this->user_id)->query('type', $this->type, '=')->value('deleted', new DateTime('now'))->update();
			}
		}
		return $insert;
	}

	public static function validatorQuery($column, $status = 1) {
		return 'User/Profile|value|' . json_encode(['deleted' => null, 'type' => $column, 'status' => $status]);
	}
}
