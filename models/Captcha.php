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
use Loli\Model, Loli\Cache, Loli\Captcha\GD as _Captcha, Loli\Code;
class_exists('Loli\Model') || exit;
class Captcha extends Model{

	public function display() {

		$captcha = new _Captcha(Code::rand(4, '0123456789QWERTYUIOPASDFGHJKLZXCVBNM'));
		$captcha->font = __DIR__ .'/fonts';
		$captcha->dirBackground = __DIR__ .'/backgrounds';
		$captcha->angle = [-15, 15];

		$captcha->width = ($width = $this->request->getparam('width', 150)) < 100 || $width > 450 ? 150 : $width;
		$captcha->height = intval($captcha->width / 3);
		$captcha->color = ($color = $this->request->getparam('color', '')) && ($rgb = $captcha->rgb($color)) ? $rgb : ['red' => 0, 'green' => mt_rand(50, 100),  'blue' => 150];

		if (($background = $this->request->getparam('background', '')) && ($rgb = $captcha->rgb($background))) {
			$captcha->background = $rgb;
		} else {
			$rand = mt_rand(220, 255);
			$captcha->background = ['red' => $rand, 'green' => $rand,  'blue' => $rand];
		}

		$ID = __CLASS__ . $this->request->getparam('ID', '');
		$this->session->set($ID, $captcha->code);
		$this->response->setHeader('Content-Type', $captcha->mime())->setCache('no-cache', 0)->setCache('max-age', 0);
		return [$captcha, 'display'];
	}



	public function verify() {
		if (!$this->request->getparam('_captcha', '')) {
			throw new Message(1011, Message::ERROR);
		}

		$ID = __CLASS__ . $this->request->getparam('_captchaID', '');
		$captcha = $this->session->get($ID);
		$this->session->delete($ID);
		if (strtoupper($this->request->getparam('_captcha', '')) !== $captcha) {
			throw new Message(1010, Message::ERROR);
		}
	}
}