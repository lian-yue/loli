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
namespace Model;
use Loli\Model, Loli\Captcha\GD as _Captcha, Loli\Crypt\Code, Loli\Message;
class_exists('Loli\Model') || exit;
class Captcha extends Model{

	protected $form = [
		['name' => '_captcha', 'title' => 'Captcha', 'type' => 'text', 'required' => true, 'errorMessage' => 10010],
	];

	public function index(array $params) {
		$captcha = new _Captcha(Code::rand(4, '0123456789QWERTYUIOPASDFGHJKLZXCVBNM'));
		$captcha->font = __DIR__ .'/fonts';
		$captcha->dirBackground = __DIR__ .'/backgrounds';
		$captcha->angle = [-15, 15];
		$captcha->width = empty($params['width']) || $params['width'] < 100 || $params['width'] > 450 ? 150 : intval($params['width']);
		$captcha->height = intval($captcha->width / 3);
		$captcha->color = $this->rbg($params, 'color', ['red' => 0, 'green' => mt_rand(50, 100),  'blue' => 150]);

		if (!$captcha->background = $this->rbg($params, 'background', [])) {
			$rand = mt_rand(220, 255);
			$captcha->background = ['red' => $rand, 'green' => $rand,  'blue' => $rand];
		}

		$ID = __CLASS__ . (empty($params['ID']) || !is_scalar($params['ID']) ? '' : $params['ID']);
		$this->session->set($captcha->code, $ID);
		$this->response->setHeader('Content-Type', $captcha->mime())->setCache('no-cache', 0)->setCache('max-age', 0);
		return $captcha;
	}

	public function test(array $params) {
		$params += ['_captcha' => ''];
		$this->formVerify($params);
		$ID = __CLASS__ . (empty($params['_captchaID']) || !is_scalar($params['_captchaID']) ? '' : $params['_captchaID']);
		$captcha = $this->session->get($ID);
		if (!is_scalar($params['_captcha']) || strtoupper(trim($params['_captcha'])) !== $captcha) {
			throw new Message([10019, $this->localize->translate('Captcha'), '_captcha'], Message::ERROR);
		}
		throw new Message(200);
	}

	public function verify(array $params) {
		$params += ['_captcha' => ''];
		$this->formVerify($params);
		$ID = __CLASS__ . (empty($params['_captchaID']) || !is_scalar($params['_captchaID']) ? '' : $params['_captchaID']);
		$captcha = $this->session->get($ID);
		$this->session->delete($ID);
		if (!is_scalar($params['_captcha']) || strtoupper(trim($params['_captcha'])) !== $captcha) {
			throw new Message([10019, $this->localize->translate('Captcha'), '_captcha'], Message::ERROR);
		}
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