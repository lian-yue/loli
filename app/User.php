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
namespace App;
use Loli\Model;
use Loli\Crypt\Password;
//  3000000

class User extends Model{
	protected static $table = 'users';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
		'username' => ['type' => 'string', 'length' => 32, 'unique' => ['username' => 0]],
		'password' => ['type' => 'string', 'length' => 64, 'hidden' => true],

		'profiles' => ['type' => 'array', 'value' => []],
		'ip' => ['type' => 'string', 'length' => 40, 'readonly' => true, 'hidden' => true],
		'registered' => ['type' => 'datetime', 'readonly' => true, 'hidden' => true],
	];

	protected static $primaryCache = 1800;

	protected static $rules = [
		['name' => 'id', 'type' => 'number', 'min' => 1, 'required' => true],
		['name' => 'username', 'type' => 'text', 'trim' => true, 'required' => true, 'minlength' => 3, 'maxlength' => 32, 'pattern' => '^[0-9a-zA-Z_\-. \p{Han}\p{Hiragana}\p{Katakana}\{Hangul}]+$'],
		['name' => 'password', 'type' => 'password', 'required' => true, 'minlength' => 6, 'maxlength' => 32],


		['name' => 'account', 'type' => 'text', 'required' => true, 'maxlength' => 128, 'placeholder' => 'Please enter your username or email'],
		['name' => 'password_again', 'type' => 'password', 'required' => true, 'maxlength' => 32],

		['name' => 'old_password', 'type' => 'password', 'required' => true, 'maxlength' => 32],
		['name' => 'new_password', 'type' => 'password', 'required' => true, 'minlength' => 6, 'maxlength' => 32],
		['name' => 'new_password_again', 'type' => 'password', 'required' => true, 'maxlength' => 32],


		['name' => 'avatar', 'type' => 'file', 'accept' => 'image/png,image/jpeg,image/bmp,image/webp,image/gif', 'size' => '4 MB', 'required' => true],

		['name' => 'nickname', 'type' => 'text', 'maxlength' => 32],
		['name' => 'description', 'type' => 'text', 'maxlength' => 128],
        ['name' => 'birthday', 'type' => 'date', 'min' => '1900-01-01', 'max' => '2016-01-01'],
		['name' => 'gender', 'type' => 'radio', 'option' => ['' => 'Secrecy', 'male' => 'Male', 'female' => 'Female']],


		['name' => 'timezone', 'type' => 'select'],
		['name' => 'language', 'type' => 'select'],


		['name' => 'email', 'type' => 'email', 'required' => false, 'maxlength' => 64, 'examine' => true],
		['name' => 'phone', 'type' => 'tel', 'required' => false, 'examine' => true],
	];


 	public static $profiles = [
		'nickname' => '',
		'description' => '',
		'gender' => '',
		'birthday' => '',
		'language' => '',
		'timezone' => '',
		'avatar' => 'default',
	];


	public function __set($name, $value) {
		switch ($name) {
			case 'password':
				if ($value && $value{0} !== '$' && strlen($value) < 60) {
					$value = Password::hash($value);
				}
				parent::__set($name, $value);
				break;
			case 'profiles':
				parent::__set($name, (is_array($value) ? $value : static::parsedType($name, $value)) + static::$profiles);
				break;
			default:
				parent::__set($name, $value);
		}
	}
}
