<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-09 13:59:34
/*
/* ************************************************************************** */
namespace Model;
use Loli\Model, Loli\Message, Loli\Crypt\Code, Loli\Crypt\Password, Loli\Cache, Loli\DB\Row, Loli\Email;
class_exists('Loli\Model') || exit;
class User extends Model{



	protected $form = [
		['name' => 'user', 'type' => 'text', 'maxlength' => 64, 'required' => true, 'errorMessage' => 11000],
		['name' => 'nicename', 'type' => 'text', 'maxlength' => 32, 'errorMessage' => 11010],
		['name' => 'ID', 'type' => 'number', 'required' => true, 'min' => 1, 'errorMessage' => 11020],
		['name' => 'email', 'type' => 'email', 'required' => true, 'maxlength' => 64, 'errorMessage' => 11030],
		['name' => 'username', 'type' => 'text', 'required' => true, 'pattern' => '^[0-9a-zA-Z_-]*[a-zA-Z][0-9a-zA-Z_-]*$', 'minlength' => 3, 'maxlength' => 32, 'errorMessage' => 11040],
		['name' => 'password', 'type' => 'password', 'minlength' => 6, 'required' => true, 'errorMessage' => 11050],
	];

	protected $tokens = ['login', 'signUp', 'getPasswordSend', 'getPasswordReset', 'settings', 'password'];


	protected $rbacs = [
		'login' => false,


		'getPasswordView' => false,
		'getPasswordSendView' => false,
		'getPasswordResetView' => false,

		'getPasswordSend' => false,
		'getPasswordReset' => false,


		'usernameExists' => false,
		'emailExists' => false,



	];

	protected $logins = [
		'login' => -1,
		'signUp'=> -1,

		'getPasswordView' => -1,
		'getPasswordSendView' => -1,
		'getPasswordResetView' => -1,

		'getPasswordSend' => -1,
		'getPasswordReset' => -1,



		'profilesView'=> 1,
		'settingsView'=> 1,
		'passwordView'=> 1,
		'profiles'=> 1,
		'settings'=> 1,
		'password'=> 1,
	];






	protected $profiles = [
		'ID' => ['readonly' => true],
		'email' => ['readonly' => true],
		'username' => ['readonly' => true],
		'nicename' => [],
		'description' => [],
		'birthday' => [],
		'registered' => ['readonly' => true],
	];



	protected $settings = [





	];


	public function loginView(array $params) {
		$defaultValue = [
			'user' => '',
			'password' => '',
		];
		return $this->getFormView('user.login', $defaultValue, $params, 'login', 3);
	}


	public function signUpView(array $params) {
		$defaultValue = [
			'username' => '',
			'email' => '',
			'nicename' => '',
			'password' => '',
			'timezone' => '',
			'language' => '',
		];

		return $this->getFormView('user.signup', $defaultValue, $params, 'signup', 0);
	}


	public function getPasswordView(array $params) {
		$defaultValue = [
			'user' => '',
		];
		return $this->getFormView('user.getpassword', $defaultValue, $params);
	}


	public function getPasswordSendView(array $params) {
		$message = NULL;
		$querys = $this->getUserQuerys($params, $message);
		$this->formVerify($querys, $message);
		if (!$user = $this->tables['User']->querys($querys)->selectRow()) {
			throw new Message([11006, $this->localize->translate('User'), 'user'], Message::ERROR);
		}

		$sends = [];
		if ($email = $user->email) {
			if (substr($email) > 10) {
				$email = substr($email, 0, 3) . '***' . substr($email, -6, 6);
			} elseif (substr($email) > 8) {
				$email = substr($email, 0, 2) . '***' . substr($email, -5, 5);
			} elseif (substr($email) > 5) {
				$email = substr($email, 0, 1) . '***' . substr($email, -3, 3);
			} else {
				$email = substr($email, 0, 2) . '***';
			}
			$sends['email'] = $email;
		}


		$username = $user->username;
		if (substr($username) > 4) {
			$username = substr($username, 0, 2) . '***' . substr($username, -2, 2);
		} elseif (substr($username) > 2) {
			$username = substr($username, 0, 1) . '***' . substr($username, -1, 1);
		} else {
			$username = substr($username, 0, 1) . '***';
		}

		return $this->view('user.getpasswordsend', ['username' => $username, 'userCode' => Code::encode($user->ID, __CLASS__), 'sends' => $sends]);
	}


	public function getPasswordResetView(array $params) {
		$user = $this->codeVerify($params, 'getPassword');
		return $this->view('user.getpasswordreset', ['results'=> [$user]]);
	}




	public function login(array $params) {
		if ($this->getIncrement('login') > 120) {
			throw new Message(93, Message::ERROR);
		}
		$message = NULL;
		$querys = $this->getUserQuerys($params, $message);

		try {
			$this->formVerify($querys, $message);
		} catch (Message $e) {
			$message = $e;
		}

		$password = isset($params['password']) ? $params['password'] : '';
		if (empty($password)) {
			$message = new Message([11051, $this->localize->translate('Password'), 'password']);
		}

		if ($this->request->getClientAddr() !== '127.0.0.1' && $this->getIncrement('login') >= 3) {
			// 需要检查验证码
			try {
				$this->model('Captcha')->formVerify($params, $message);
			} catch (Message $e) {
				$message = $e;
			}

			if (!$message) {
				try {
					$this->model('Captcha')->verify($params);
				} catch (Message $e) {
					$message = $e;
				}
			}
		}

		$this->setIncrement('login');

		if ($message) {
			$message->setData(['captcha' => $this->getIncrement('login') >= 3]);
			throw $message;
		}

		// 用户不存在
		if (!$user = $this->table['User']->querys($querys)->selectRow()) {
			throw new Message([11006, $this->localize->translate('User'), 'user'], Message::ERROR, ['captcha' => $this->getIncrement('login') >= 3]);
		}

		// 密码错误
		if (!Password::verify($password, $user->password)) {
			$this->tables['User.log']->values(['userID' => $user->ID, 'type' => 'passwordError', 'IP' => $this->request->getClientAddr()])->insert();
			throw new Message([11059, $this->localize->translate('Password'), 'password'], Message::ERROR, ['captcha' => $this->getIncrement('login') >= 3]);
		}

		// 登录成功
		$user = clone $user;
		unset($user->password);
		throw new Message(200, Message::NOTICE, ['results' => [$user]]);
	}



	public function signUp(array $params) {
		$defaultValue = [
			'username' => '',
			'email' => '',
			'nicename' => '',
			'password' => '',
			'timezone' => '',
			'language' => '',
		];

		$values = array_intersect_key($params, $defaultValue) + $defaultValue;

		$message = NULL;

		try {
			$this->formVerify($values, $message);
		} catch (Message $e) {
			$message = $e;
		}

		// 两次输入的密码不正确
		if (empty($params['passwordAgain']) || $params['passwordAgain'] !== $values['password']) {
			$message = new Message([11519, $this->localize->translate('Password again'), 'passwordAgain'], Message::ERROR, $message);
		}


		if ($this->request->getClientAddr() !== '127.0.0.1') {
			// 需要检查验证码
			try {
				$this->model('Captcha')->formVerify($params, $message);
			} catch (Message $e) {
				$message = $e;
			}

			if (!$message) {
				try {
					$this->model('Captcha')->verify($params);
				} catch (Message $e) {
					$message = $e;
				}
			}
		}

		// 有错误
		if ($message) {
			throw $message;
		}

		// 用户名存在
		if ($this->tables['User']->query('username', $values['username'], '=')->selectRow()) {
			$message = new Message([11047, $this->localize->translate('Username'), 'username'], Message::ERROR);
		}

		// 邮箱存在
		if ($this->tables['User']->query('email', $values['email'], '=')->selectRow()) {
			$message = new Message([11037, $this->localize->translate('Email'), 'email'], Message::ERROR);
		}


		if ($message) {
			throw $message;
		}

		$this->tables['User']->values($values)->insert();
		$userID = $this->tables['User']->lastInsertID();
		if (!$userID || !($user = $this->tables['User']->selectRow($userID))) {
			throw new Message(500, Message::ERROR);
		}

		$user = clone $user;
		unset($user->password);
		$params = ['results' => [$user]];


		// 注册角色 ID
		if ($roleID = $this->table['Setting']->getValue('signUpRoleID', 0)) {
			$this->tables['RBAC.Relationship']->values(['userID' => $user->ID, 'roleID' => $roleID])->insert();
		}

		// 和默认角色 ID 不同 发送邮箱验证
		if ($roleID && ($defaultroleID = $this->table['Setting']->getValue('defaultRoleID', 0)) && $defaultroleID !== $roleID) {
			$params += $this->codeSend($user->ID, 'emailVerify', 'email', $user->email);
		}
		throw new Message(200, Message::NOTICE, $params);
	}


	public function getPasswordSend(array $params) {
		$message = NULL;
		$querys = $this->getUserQuerys($params, $message);
		$this->formVerify($querys, $message);
		if (!$user = $this->tables['User']->querys($querys)->selectRow()) {
			throw new Message([11006, $this->localize->translate('User'), 'user'], Message::ERROR);
		}
		switch (isset($params['send']) && is_string($params['send']) ? $params['send'] : '') {
			case 'email':
				$send = 'email'
				$value = $user->email;
				break;
			default:
				$send = 'email'
				$value = $user->email;
		}
		$params = $this->codeSend($user->ID, 'getPassword', $send, $value);
		throw new Message(200, Message::NOTICE, $params);
	}

	public function getPasswordReset(array $params) {
		$user = $this->codeVerify($params, 'getPassword');

		$message = NULL;
		$values['password'] = isset($params['password']) ? $params['password'] : '';

		try {
			$this->formVerify($values, $message);
		} catch (Message $e) {
			$message = $e;
		}

		// 两次输入的密码不正确
		if (empty($params['passwordAgain']) || $params['passwordAgain'] !== $values['password']) {
			$message = new Message([11519, $this->localize->translate('Password again'), 'passwordAgain'], Message::ERROR, $message);
		}

		if ($message) {
			throw $message;
		}
		if (!$this->tables['User']->values($values)->update($args['user']->userID)) {
			throw new Message(500, Message::ERROR);
		}
		throw new Message(200, Message::NOTICE, ['results' => [$user]]);
	}


	public function profiles(array $params) {

	}

	public function settings(array $params) {

	}

	public function password(array $params) {

	}


	public function usernameExists(array $params) {
		if ($this->getIncrement('exists') > 30) {
			throw new Message(93, Message::ERROR);
		}
		$this->setIncrement('exists', 120);
		if (empty($params['username']) || !is_string($params['username']) || !$this->tables['User']->query('username', $params['username'], '=')->selectRow()) {
			throw new Message([11016, $this->localize->translate('Username'), 'username'], Message::ERROR, ['exists' => false]);
		}
		throw new Message(200, Message::NOTICE, ['exists' => true]);
	}


	public function emailExists(array $params) {
		if ($this->getIncrement('exists') > 30) {
			throw new Message(93, Message::ERROR);
		}
		$this->setIncrement('exists', 120);
		if (empty($params['email']) || !is_string($params['email']) || !$this->tables['User']->query('email', $params['email'], '=')->selectRow()) {
			throw new Message([11036, $this->localize->translate('Email'), 'email'], Message::ERROR, ['exists' => false]);
		}
		throw new Message(200, Message::NOTICE, ['exists' => true]);
	}




	protected function codeSend($userID, $type, $send, $value, $length = 4, $string = '0123456789QWERTYUIOPASDFGHJKLZXCVBNM') {
		$this->tables['User.Code']->values(['userID' => $userID, 'type' => $type, 'code'=> $code = Code::rand($length, $string)])->insert();
		switch ($send) {
			case 'email':
				$subject = $this->localize->translate('User code email subject ' . $type);
				$message = $this->localize->translate(['User code email message ' . $type, 'code' => $code]);
				$email = new Email($subject, $message);
				$email->addTo($value, $this->tables['User']->selectRow($userID)->nicename)->send();
				break;
			default:
				throw new Message(500, Message::ERROR);
		}
		$userCode = Code::encode($userID, __CLASS__);
		return ['userCode' => $userCode];
	}



	protected function codeVerify(array &$params, $type) {
		if (empty($params['userCode']) || !($userID = (int) Code::decode($params['userCode'], __CLASS__))) {
			throw new Message([11500, $this->localize->translate('User code'), 'userCode'], Message::ERROR);
		}

		if (empty($params['code']) || !is_string($params['code'])) {
			throw new Message([11500, $this->localize->translate('Code'), 'code'], Message::ERROR);
		}

		if (Cache::get($params['userCode'], __METHOD__) > 5 || $this->getIncrement('code' . $type) > 10 || Cache::get($userID, __METHOD__) > 30) {
			throw new Message(93, Message::ERROR);
		}

		$codes = $this->tables['User.Code']->querys(['userID' => $userID, 'used' => false, 'type' => $type])->limit(5)->select();


		$codeValue = strtoupper($params['code']);
		$expired = gmdate('Y-m-d H:i:s', time() - 1800);
		foreach ($codes as $code) {
			if ($code->created < $expired && strtoupper($code->value) === $codeValue) {
				$user = clone $user;
				unset($user->password);
				return $user;
			}
		}

		Cache::add(0, $params['userCode'], __METHOD__, 900);
		Cache::incr(1, $params['userCode'], __METHOD__);

		Cache::add(0, $userID, __METHOD__, 1800);
		Cache::incr(1, $userID, __METHOD__);

		$this->setIncrement('code' . $type);
		throw new Message([11509, $this->localize->translate('Code'), 'code'], Message::ERROR);
	}




	protected function getUserQuerys(array &$params, Message &$message = NULL) {
		$querys = [];
		if (isset($params['userCode'])) {
			$querys['ID'] = Code::encode($params['userCode'], __CLASS__);
		} elseif (isset($params['ID'])) {
			$querys['ID'] = trim($params['ID']);
		} elseif (isset($params['email'])) {
			$querys['email'] = trim($params['email']);
		} elseif (isset($params['username'])) {
			$querys['username'] = trim($params['username']);
		} elseif (isset($params['user'])) {
			$user = trim($params['user']);
			if (preg_match('/\d+/', $user)) {
				$querys['ID'] = $params['ID'];
			} elseif (filter_var($user, FILTER_VALIDATE_EMAIL)) {
				$querys['email'] = $params['email'];
			} else {
				$querys['username'] = $params['username'];
			}
		} else {
			$message = new Message([11001, $this->localize->translate('User'), 'user'], Message::ERROR, $message);
		}
		return $querys;
	}




	protected function getFormView($view, array $defaultValue, array $params, $type = '', $limit = -1) {
		$params = array_intersect_key($params, $defaultValue) + $defaultValue;
		foreach ($params as $key => $value) {
			settype($params[$key], gettype($defaultValue[$key]));
		}
		$form = [];
		foreach ($this->getForm() as $input) {
			if (isset($params[$input['name']])) {
				$input['value'] = $params[$input['name']];
				$form[] = $input;
			}
		}

		if ($limit >= 0) {
			$captcha = $this->getIncrement($type) >= $limit;
			if ($captcha) {
				$form = array_merge($form, $this->model('Captcha')->getForm());
			}
			$params['captcha'] = $captcha;
		}
		$params['form'] = $form;
		return $this->view($view, $params);
	}

	protected function getIncrement($type) {
		return Cache::get($type. $this->request->getClientAddr(), __CLASS__);
	}

	protected function setIncrement($type, $ttl = 1800) {
		Cache::add(0, $type . $this->request->getClientAddr(), __CLASS__, $ttl);
		Cache::incr(1, $type . $this->request->getClientAddr(), __CLASS__);
	}
}