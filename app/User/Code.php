<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-06 12:08:17
/*
/* ************************************************************************** */
namespace App\User;
use Loli\Model;
use Loli\Route;
use Loli\Locale;
use Loli\Message;
use Loli\DateTime;

use Loli\Crypt\Code as CryptCode;
class Code extends Model{
	protected static $table = 'user_codes';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'key' => ['user_token_type' => 0]],
        'token' => ['type' => 'string', 'length' => 16, 'key' => ['user_token_type' => 1]],
		'type' => ['type' => 'string', 'length' => 32, 'key' => ['user_token_type' => 2]],
		'code' => ['type' => 'string', 'length' => 16, 'hidden' => true],
		'value' => ['type' => 'string', 'length' => 255, 'hidden' => true],
		'created' => ['type' => 'datetime', 'hidden' => true],
		'expired' => ['type' => 'datetime', 'hidden' => true, 'null' => true],
		'deleted' => ['type' => 'datetime', 'hidden' => true, 'null' => true, 'key' => ['deleted' => 0]],
	];

    protected static $rules = [
        'code' => ['type' => 'text', 'required' => true],
    ];

    public function insert() {
		if (!$this->created) {
			$this->created = new DateTime('now');
		}
		if (!$this->expired) {
            $this->expired = clone $this->created;
    		$this->expired->modify('+60 minutes');
		}

		if (!$this->token) {
			$this->token = Route::token()->get();
		}

        if ($this->user_id === null) {
			$this->user_id  = Route::auth()->user_id;
		}
        if (!$this->code) {
			$this->code  = CryptCode::random(6, '123456789QWERTYUIPASDFGHJKLZXCVBNM');
		}
		return parent::insert();
	}

    public static function verify($type, $code, $user_id = null, $token = null, $value = null, $delete = false) {
        if ($token === null) {
            $token = Route::token()->get();
        }
        if ($user_id === null) {
            $user_id = Route::auth()->user_id;
        }
        $now = new DateTime('now');
        foreach (static::database()->query('user_id', $user_id, '=')->query('token', $token, '=')->query('type', $type, '=')->query('deleted', null, '=')->order('expired', 'DESC')->limit(6)->select() as $result) {
			if ($result->expired < $now) {
				continue;
			}
            if (strtoupper($result->code) !== strtoupper(trim($code))) {
				continue;
			}

            if ($value !== null && to_string($value) !== $result->value) {
                continue;
            }

            $delete && statis::database()->query('user_id', $user_id, '=')->query('token', $token, '=')->query('type', $type, '=')->query('deleted', null, '=')->value('deleted', $now)->update();
            return $result;
		}

        throw new Message(['message' => 'validator', 'title' => Locale::translate('Code'), 'name' => 'code'], 400);
    }
}
