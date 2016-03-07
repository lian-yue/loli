<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-04 08:40:07
/*
/* ************************************************************************** */
namespace App\Controllers;
use Loli\Route;
use Loli\Message;
use Loli\Session;
use Loli\Validator;
use Loli\Controller;
use Loli\Locale;
use Loli\Crypt\Code;
use Loli\Captcha\GD as LoliCaptcha;

use Loli\Http\Message\Header;

class Captcha extends Controller{

	public $rules = [
		['name' => '_captcha', 'type' => 'text', 'required' => true],
		['name' => '_captcha_id', 'type' => 'hidden', 'value' => ''],
	];

	public function index(array $params) {
		$captcha = new LoliCaptcha(Code::random(4, '0123456789QWERTYUIOPASDFGHJKLZXCVBNM'));
		$captcha->font = __DIR__ .'/fonts';
		$captcha->dirBackground = __DIR__ .'/backgrounds';
		$captcha->angle = [-15, 15];
		$captcha->width = empty($params['width']) || $params['width'] < 100 || $params['width'] > 450 ? 150 : intval($params['width']);
		$captcha->height = intval($captcha->width / 3);
		$captcha->color = $this->rbg($params, 'color', ['red' => 0, 'green' => mt_rand(50, 100),  'blue' => 150]);

		if (!$captcha->background = $this->rbg($params, 'background', [])) {
			$random = mt_rand(220, 255);
			$captcha->background = ['red' => $random, 'green' => $random,  'blue' => $random];
		}

		$captchaId = $this->id($params);
		$item = Session::getItem($captchaId);
		Session::save($item->set($captcha->code)->expiresAfter(1800));
		Route::response(
			Route::response()
			->withHeader('Content-Type', $captcha->mime())
			->withHeader('Cache-Control', Header::cacheControl(['no-cache' => true, 'max-age' => 0]))
		);
		return $captcha->__toString();
	}

	public function test(array $params) {
		$params = (new Validator($this->rules))->make($params, ['_captcha' => '', '_captcha_id' => ''], true);
		$captchaId = $this->id($params);
		$item = Session::getItem($captchaId);
		if (strtoupper(trim($params['_captcha'])) !== $item->get()) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Captcha'), 'name' => '_captcha']);
		}
		throw new Message('success');
	}

	public function verify(array $params) {
		$params = (new Validator($this->rules))->make($params, ['_captcha' => '', '_captcha_id' => ''], true);
		$captchaId = $this->id($params);
		$code = Session::getItem($captchaId)->get();
		Session::deleteItem($captchaId);
		if (strtoupper(trim($params['_captcha'])) !== $code) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Captcha'), 'name' => '_captcha']);
		}
	}

	protected function id(array &$params) {
		return __CLASS__ . (empty($params['_captcha_id']) || !is_scalar($params['_captcha_id']) ? '' : $params['_captcha_id']);
	}


	protected function rbg(array &$params, $key, array $default) {
		if (empty($params[$key]) || !is_string($params[$key])) {
			return $default;
		}
		$rgb = $params[$key];
		if ($rgb{0} === '#') {
			$rgb =  substr($rgb, 1);
		}
		if (strlen($rgb) !== 6) {
			return $default;
		}
		return [
			'red' => hexdec(substr($rgb, 0, 2)),
			'green' => hexdec(substr($rgb, 2, 2)),
			'blue' => hexdec(substr($rgb, 4, 2)),
		];
	}
}
