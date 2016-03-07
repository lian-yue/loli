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
namespace App\Controllers;

use DateTimeZone;

use Psr\Http\Message\UploadedFileInterface;

use App\Auth;
use App\User;
use App\User\Log;
use App\User\Code;
use App\User\Profile;


use App\Services\Mail;

use Loli\Log as Logger;
use Loli\Uri;
use Loli\View;
use Loli\Controller;
use Loli\Route;
use Loli\Cache;
use Loli\Locale;
use Loli\Session;
use Loli\Message;
use Loli\DateTime;
use Loli\Crypt\Int;
use Loli\Crypt\Code as CryptCode;
use Loli\Crypt\Password;
use Loli\Database\QueryException;

use Intervention\Image\ImageManagerStatic as Image;




class Account extends Controller{


	public $middleware = [
		'postLogin' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 60, 'reset' => 900],
		],

		'postCreate' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 60, 'reset' => 900],
		],
		'exists' => [
			'Csrf' => [],
			'RateLimit' => ['limit' => 60, 'reset' => 900],
		],

		'signOut' => [
			'Csrf' => [],
			'Auth' => ['login' => null, 'node' => false],
		],
		'selectSendPassword' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 30, 'reset' => 900],
		],
		'sendPassword' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 30, 'reset' => 900],
		],
		'resetPassword' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 30, 'reset' => 900],
		],
		'postResetPassword' => [
			'Csrf' => [],
			'Auth' => ['login' => false, 'node' => false],
			'RateLimit' => ['limit' => 30, 'reset' => 900],
		],

		'OAuth2' => [
			'Csrf' => [],
			'Auth' => ['login' => null, 'node' => false],
			'RateLimit' => ['limit' => 120, 'reset' => 900],
		],

		'OAuth2Callback' => [
			'Auth' => ['login' => null, 'node' => false],
			'RateLimit' => ['limit' => 60, 'reset' => 900],
		],
	];

	public $defaultMiddleware = [
		'Auth' => ['login' => false, 'node' => false],
	];



	protected $captcha = [
		'login' => null,
		'create' => null,
		'lostPassword' => null,
	];

	protected $OAuth2Types = [
		'google' => 'Google',
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
		'qq' => 'QQ',
		'baidu' => 'Baidu',
		'weibo' => 'Weibo',
	];



	public function index(array $params) {
		throw new Message('redirect', 302, [], new Uri(['Account', 'login']), 0);
	}

	public function login(array $params) {
		if ($this->isCaptcha()) {
			$captcha = new Captcha;
		} else {
			$captcha = false;
		}

		$rules = [
			'account' => ['value' => ''],
			'password' => ['value' => '', 'minlength' => null],
		];

		foreach ($rules as $key => $rule) {
			if (isset($params[$key]) && isset($rule['value'])) {
				$value = gettype($params[$key]);
				settype($value, gettype($rule['value']));
				$rules[$key]['value'] = $value;
			}
		}

		$rules += [
			'remember' => ['value' => 86400 * 31, 'title' => Locale::translate('Remember me'), 'type' => 'checkbox', 'checked' => !empty($params['remember'])],
		];

		if ($captcha) {
			$rules += $captcha->rules;
		}

		$rules = User::validator()->rules($rules, true);
		return new View(['account/login'], ['results' => $rules]);
	}


	public function postLogin(array $params) {
		if ($this->isCaptcha('login')) {
			$captcha = new Captcha;
		} else {
			$captcha = false;
		}

		$rules = $this->getUserRule($params);
		$column = key($rules);
		$rules += [
			'password' => ['value' => '', 'minlength' => null],
			'remember' => ['value' => 0],
		];

		if ($captcha) {
			$rules += $captcha->rules;
		}

		$params = array_map('trim', array_map('to_string', $params));
		$params = User::validator($params, $rules, true);

		// Captcha verify
		$captcha && $captcha->verify($params);

		$this->setIncrement();

		$user = $this->select($column, $params);

		// Password  verify
		if (!Password::verify($params['password'], $user->password)) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Password'), 'name' => 'password'], 50);
		}

		// login
		$this->loginAuth($user, $params['remember']);

		throw new Message('success', 200, ['results' => [$user]]);
	}





	public function exists(array $params) {
		$rules = [
			'username' => ['unique' => 'self'],
			'email' => ['unique' => Profile::validatorQuery('email')],
			'phone' => ['unique' => Profile::validatorQuery('phone')],
		];
		User::validator($params, $rules, true);
		throw new Message('success');
	}

	public function signOut(array $params) {
		Route::auth()->merge(['user_id' => 0, 'expired' => null])->update();
		throw new Message('success');
	}

	public function create(array $params) {
		if ($this->isCaptcha()) {
			$captcha = new Captcha;
		} else {
			$captcha = false;
		}

		$rules = [
			'username' => ['value' => '', 'unique' => 'self'],
			'password' => ['value' => ''],
			'password_again' => ['value' => ''],
			'nickname' => ['value' => ''],
			'gender' => ['value' => 2],
			'birthday' => [],
			'timezone' => ['value' => date_default_timezone_get(), 'option' => Locale::getTimezoneLists()],
			'language' => ['value' => Locale::getLanguage(), 'option' => Locale::getLanguageLists()],
			'email' => ['unique' => Profile::validatorQuery('email')],
			// 'phone' => ['unique' => User::validatorProfileQuery('phone')],
		];

		foreach ($rules as $key => $rule) {
			if (isset($params[$key]) && isset($rule['value'])) {
				$value = gettype($params[$key]);
				settype($value, gettype($rule['value']));
				$rules[$key]['value'] = $value;
			}
		}

		if ($captcha) {
			$rules += $captcha->rules;
		}

		$rules = User::validator()->rules($rules, true);
		return new View(['account/create'], ['results' => $rules]);
	}


	public function postCreate(array $params) {
		$rules = $this->create($params)->results;

		$mimeType = ['image/png', 'image/jpeg', 'image/bmp', 'image/webp', 'image/gif'];
		if (empty($params['avatar'])) {

		} elseif ($params['avatar'] instanceof UploadedFileInterface || (is_array($params['avatar']) && reset($params['avatar']) instanceof UploadedFileInterface)) {
			$rules['avatar'] = ['type' => 'file', 'accept' => implode(',', $mimeType), 'required' => true, 'size' => '4 MB'];
		} elseif (filter_var($params['avatar'], FILTER_VALIDATE_URL)) {
			$rules['avatar'] = ['type' =>  'url', 'pattern' => '^https?\://([0-9a-zA-Z_-]+\.)+[a-z]+[?|/].+'];
		} else {
			$rules['avatar'] = ['type' => 'text', 'value' => ''];
		}

		$params = User::validator($params, $rules, true);
		if ($this->isCaptcha('create')) {
			(new Captcha)->verify($params);
		}
		unset($params['_captcha'], $params['_captcha_id']);

		$this->setIncrement();


		$userParams = array_intersect_key($params, ['username' => '', 'password' => '']);
		$profileParams = array_diff_key($params, ['username' => '', 'password' => '']);

		if (!$profileParams['nickname']) {
			$profileParams['nickname'] = $userParams['username'];
		}

		//  Insert user
		$user = new User($userParams);
		$user->ip = Route::ip();
		$user->registered = new DateTime('now');
		$user->insert();

		// Insert profile
		foreach ($profileParams as $type => $value) {
			if ($type === 'avatar') {
				if (!$value) {
					continue;
				}
				if (!$value = $this->getAvatar($value)) {
					continue;
				}
			}
			$profile = new Profile(['user_id' => $user->id, 'type' => $type,  'value' => $value, 'status' => 0]);
			$profile->insert();
		}

		$user->select();

		// Login auth
		$this->loginAuth($user);

		throw new Message('success', 200, ['results' => [$user]]);
	}



	public function lostPassword(array $params) {
		if ($this->isCaptcha()) {
			$captcha = new Captcha;
		} else {
			$captcha = false;
		}

		$rules = [
			'account' => ['value' => ''],
		];

		foreach ($rules as $key => $rule) {
			if (isset($params[$key]) && isset($rule['value'])) {
				$value = gettype($params[$key]);
				settype($value, gettype($rule['value']));
				$rules[$key]['value'] = $value;
			}
		}

		if ($captcha) {
			$rules += $captcha->rules;
		}
		$rules = User::validator()->rules($rules, true);
		return new View(['account/lostPassword'], ['results' => $rules]);
	}


	public function selectSendPassword(array $params) {
		if ($this->isCaptcha('lostPassword')) {
			$captcha = new Captcha;
		} else {
			$captcha = false;
		}

		$rules = $this->getUserRule($params);
		$column = key($rules);
		if ($captcha) {
			$rules += $captcha->rules;
		}
		$params = array_map('trim', array_map('to_string', $params));

		$params = User::validator($params, $rules, true);

		// Captcha
		$captcha && $captcha->verify($params);

		$this->setIncrement('lostPassword');

		$user = $this->select($column, $params);
		$profiles = Profile::query('deleted', null, '=')->query('user_id', $user->id, '=')->query('type', ['email', 'phone'], 'IN')->query('status', 1, '=')->select();
		foreach ($profiles as $profile) {
			switch ($profile->type) {
				case 'email':
						$value = explode('@', $profile->value, 2);
						if (substr($value[0]) <= 3) {
							$value[0] = '***';
						} elseif (substr($value[0]) <= 6) {
							$value[0] = '***' . substr($value[0], -2, 2);
						} elseif ($value[0] <= 8) {
							$value[0] = substr($value[0], 0, 2) . '***' . substr($value[0], -2, 2);
						} else {
							$value[0] = substr($value[0], 0, 3) . '***' . substr($value[0], -3, 3);
						}
						$profile->value =  implode('@', $profile->value);
					break;
				default:
					$profile->value = substr($profile->value, 0, 3) . '***' . substr($profile->value, -3, 3);
			}
		}

		return new View(['account/selectSendPassword'], ['results' => $profiles]);
	}




	public function sendPassword(array $params) {
		$data = ['id' => 0, 'user_id' => 0];
		$params = array_intersect_key($params, $data) + $data;
		$params = array_map('intval', $params);

		if (!($profile = Profile::query('id', $params['id'], '=')->selectRow()) || $profile->user_id != $params['user_id'] || $profile->deleted || $profile->status != 1 || in_array($profile->type, ['email', 'phone'], true)) {
			throw new Message(['message' => 'validator_exists',  'title' => Locale::translate('ID'), 'name' => 'id'], 50);
		}

		$user = new User(['id' => $profile->user_id]);
		$user->select();

		$created = new DateTime('now');
		$expired = new DateTime('now');
		$expired->modify('+15 minutes');

		$code = new Code(['type' => 'password', 'code' => CryptCode::random(6, '123456789QWERTYUIPASDFGHJKLZXCVBNM'), 'user_id' => $user->id, 'args' => ['token' => Route::request()->getToken(), 'id' => $profile->id], 'created' => $created, 'expired' => $expired]);
		$code->insert();

		switch ($profile->type) {
			case 'email':
				try {
					$mail = new Mail();
					$mail->addAddress($profile->value, $user->nickname);
					$mail->isHTML(true);
					$mail->Subject = Locale::translate(['account_password_subject', 'nickname' => $user->nickname, 'username' => $user->username], ['mail', 'default'], 'Authentication Code');
					$mail->Body = Locale::translate(['account_password_body_html', 'nickname' => $user->nickname, 'username' => $user->username, 'code' => $code->code], ['mail', 'default'], 'Authentication Code');
					$mail->AltBody = Locale::translate(['account_password_body_text', 'nickname' => $user->nickname, 'username' => $user->username, 'code' => $code->code], ['mail', 'default'], 'Authentication Code');
					$mail->send();
				} catch (\Exception $e) {
					Logger::controller()->error($e->getMessage(), ['exception' => $e, 'address' => $profile->value]);
					throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
				}
				break;
			case 'phone':
				try {
					$sms = new SMS($profile->value);
					$sms->send();
				} catch (\Exception $e) {
					Logger::controller()->error($e->getMessage(), ['exception' => $e, 'phone' => $profile->value]);
					throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
				}
				break;
		}
		throw new Message('success', 200, ['results' => [$code]]);
	}


	public function resetPassword(array $params) {
		$rules = [
			'id' => ['value' => 0, 'unique' => 'self'],
			'code' => ['value' => '', 'type' => 'hidden', 'required' => true],
		];
		$params = array_map('trim', array_map('to_string', $params));

		User::validator($params, $rules, true);

		$now = new DateTime('now');
		$user = new User(['id' => $params['id']]);
		$user->select();

		foreach (Code::query('user_id', $user->id, '=')->query('type', 'password', '=')->query('deleted', null, '=')->order('expired', 'DESC')->limit(10)->select() as $value) {
			if (empty($value->args['token']) || $value->args['token'] !== Route::request()->getToken()) {
				continue;
			}
			if ($value->expired < $now) {
				continue;
			}
			if (strtoupper($value->code) !== $params['code']) {
				continue;
			}
			$break = true;
			break;
		}
		if (empty($break)) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Code'), 'name' => 'code'], 50);
		}

		return new View(['account/resetPassword'], ['results' => [$user]]);
	}




	public function postResetPassword(array $params) {
		$user = $this->resetPassword($params)->results[0];

		$rules = [
			'password' => ['value' => ''],
			'password_again' => ['value' => ''],
		];
		$params = array_map('trim', array_map('to_string', $params));
		$params = User::validator($params, $rules, true);

		// Update password
		$password = $user->password;
		$user->password = $params['password'];
		$user->update();

		$log = new Log(['user_id' => $user->id, 'type' => 'reset_password', 'value' => $password]);
		$log->insert();

		// Update code delete
		Code::query('user_id', $user->id, '=')->query('type', 'password', '=')->query('deleted', null, '=')->value('deleted', $now)->update();

		throw new Message('success');
	}



	public function captcha(array $params) {
		throw new Message('success', 200, ['captcha' => $this->isCaptcha(empty($params['method']) ? 'login' : (string) $params['method'], empty($params['id']) ? 0 : intval($params['id'])) ? new Uri(['Captcha', 'index']) : false]);
	}




	// 重定向到登陆页面
	public function OAuth2(array $params) {
		$class = $this->getOAuth2Class($params);
		$item = Session::getItem('OAuth2_' . $class->getType());
		$params = to_array($params);
		$item->set($params)->expiresAfter(3600);
		Session::save($item);
		throw new Message('redirect', 302, ['redirect' => $class->getRedirect()], true, 0);
	}


	public function OAuth2Callback(array $params) {
		$class = $this->getOAuth2Class($params);

		// 附加参数
		$item = Session::getItem('OAuth2_' . $class->getType());
		if (is_array($item->get())) {
			$params += $item->get();
		}

		if (Route::csrf()) {
			if (!$class->isAuthorize()) {
				// 验证失败 取消登陆等
				if (empty($params['redirect'])) {
					$redirect = new Uri(['Profile', 'index']);
				} else {
					$redirect = $params['redirect'];
				}
				throw new Message('redirect', 302, [], $redirect, 0);
			}
		} else {
			$item = Session::getItem('OAuth2_ID_' . $class->getType());
			if (is_array($item->get())) {
				$class->setAccessToken($item->get());
			} else {
				throw new Message('redirect', 302, ['redirect' => $class->getRedirect()], true, 0);
			}
		}

		$accessToken = $class->getAccessToken();
		if (!$userInfo = $class->getUserInfo()) {
			throw new \RuntimeException('User info is empty');
		}
		if (empty($userInfo['id'])) {
			throw new \RuntimeException('User id is empty');
		}

		if (empty($params['redirect'])) {
			$redirect = new Uri(['Profile', 'index']);
		} else {
			$redirect = $params['redirect'];
		}


		$type = 'oauth_' . $class->getType();


		// 已登陆添加进绑定
		if ($userId = Route::auth()->user_id) {

			// 有绑定了
			if (Profile::query('deleted', null, '=')->query('type', $type, '=')->query('status', 1, '=')->query('value', $userInfo['id'])->selectRow()) {
				throw new Message(['message' => 'oauth2_unique', 'name' => ($class->getName())], 80);
			}

			// 数量太多了
			$profiles = Profile::query('deleted', null, '=')->query('type', $type, '=')->query('status', 1, '=')->query('value', $userInfo['id'])->limit(11)->select();
			if ($profiles->count() > 10) {
				throw new Message(['message' => 'oauth2_max_count', 'name' => ($class->getName())], 80);
			}

			// 插入
			$profile = new Profile(['user_id' => $userId, 'type' => $type, 'value' => $userInfo['id'], 'status' => 1, 'args' => ['user_info' => $userInfo, 'access_token' => $accessToken]]);
			$profile->insert();


			throw new Message('redirect', 302, [], $redirect, 0);
		}


		// 已存在登陆
		if ($profile = Profile::query('deleted', null, '=')->query('type', $type, '=')->query('status', 1, '=')->query('value', $userInfo['id'])->selectRow()) {
			// 更新字段
			$profile->args = ['user_info' => $userInfo, 'access_token' => $accessToken];
			$profile->update();

			// 创建
			$user = Route::user();
			$user->id = $profile->user_id;
			$user->select();
			$this->loginAuth($user, 86400 * 7, implode(',', 'OAuth2-' . $profile->id));

			throw new Message('redirect', 302, [], $redirect, 0);
		}


		// 不存在 自动创建的
		if (!empty($params['create'])) {

			// 用户名
			if (!empty($userInfo['username'])) {
				$username = $userInfo['username'];
			} elseif (!empty($userInfo['nickname'])) {
				$username = $userInfo['nickname'];
			} elseif (!empty($userInfo['email'])) {
				$username = explode('@', $userInfo['email'])[0];
			}
			if (mb_strlen($username) > 32) {
				$username = mb_substr($username, 0, 32);
			}

			// 唯一用户名
			if (User::query('username', $username, '=')->selectRow()) {
				$username = $profile->getType() . '_' . $username;
				$username = mb_substr($username, 0, 32);
				if (User::query('username', $username, '=')->selectRow()) {
					$random = '_' . CryptCode::random(5, '0123456789qwertyuiopafghklzxcvbnm');
					$username = mb_substr($username, 0, 32 - mb_strlen($random));
					if (User::query('username', $username, '=')->selectRow()) {
						throw new \RuntimeException('User name already exists');
					}
				}
			}

			// 插入用户
			$user = new User([
				'username' => $username,
				'ip' => Route::ip(),
				'registered' => new DateTime('now'),
			]);
			$user->insert();


			// 插入绑定信息
			$oauth2Profile = new Profile(['user_id' => $user->id, 'type' => $type,  'value' => $userInfo['id'], 'status' => 1, 'args' => ['user_info' => $userInfo, 'access_token' => $accessToken]]);
			$oauth2Profile->insert();

			//  自动注册设置字段
			$profile = new Profile(['user_id' => $user->id, 'type' => 'oauth2_create',  'value' => 1, 'status' => 1]);
			$profile->insert();


			// 普通字段
			$rules = User::validator()->rules();
			foreach ($userInfo as $key => $value) {
				if ($value === null || isset($rules[$key])) {
					continue;
				}
				if (!isset(User::$profiles[$key])) {
					continue;
				}


				if ($key === 'avatar') {
					if (!$value) {
						continue;
					}
					if (!$value = $this->getAvatar($value)) {
						continue;
					}
				} else {
					try {

						if ($key === 'timezone') {
							$rules = [$key => ['option' => Locale::getTimezoneLists()]];
						} elseif  ($key === 'language') {
							$rules = [$key => ['option' => Locale::getLanguageLists()]];
						} else {
							$rules = [$key => []];
						}

						$value = User::validator([$key => $value], $rules, true)[$key];
						if (empty($rules[$key]['examine'])) {
							$status = 1;
						} elseif (in_array($key, ['email', 'phone'], true)) {
							if (empty($userInfo['verified_' . $key])) {
								$status = 0;
							} elseif (Profile::query('deleted', null, '=')->query('type', $key, '=')->query('status', 1, '=')->query('value', $value, '=')->selectRow()) {
								$status = 0;
							} else {
								$status = 1;
							}
						} else {
							$status = 0;
						}
					} catch (Message $e) {
						// 验证器 失败
						continue;
					}
				}
				$profile = new Profile(['user_id' => $user->id, 'type' => $key,  'value' => $value, 'status' => $status]);
				$profile->insert();
			}

			// 登陆用户
			$user->select();
			$this->loginAuth($user, 86400 * 7, implode(',', 'OAuth2-' . $oauth2Profile->id));

			// 登陆后
			throw new Message('redirect', 302, [], $redirect, 0);
		}



		$item = Session::getItem('OAuth2_ID_' . $class->getType());
		$item->set($class->getAccessToken())->expiresAfter(3600);
		Session::save($item);

		$redirectOAuth2 = new Uri(['Account', 'OAuth2Callback'], ['type' => $class->getType(), '_token' => Route::token()->get(), 'redirect' => $redirect]);
		$redirectCreate = new Uri(['Account', 'create'],  ['redirect' => $redirectOAuth2] + $userInfo);

		// 转到
		throw new Message('redirect', 302, [], $redirectCreate, 0);
	}


	protected function getAvatar($value) {
		try {
			if ($value instanceof UploadedFileInterface) {
				$image = Image::make($value->getStream()->getContents());
			} elseif (filter_var($value, FILTER_VALIDATE_URL)) {
				$image = Image::make($value);
			} elseif ($binary = base64_decode($value)) {
				$image = Image::make(base64_encode($binary));
			} else {
				throw new InvalidArgumentException('The unknown data type');
			}
			$image->fit(256, 256);


			$value = gmdate('Y/m/d/h/i/') . Int::encode($user->id);
			$storage = 'storage://avatars/' . $value . '.png';
			if (!is_dir($dir = dirname($storage)) && !mkdir($dir, true)) {
				throw new \RuntimeException(__METHOD__. '() Failed to create directory');
			}
			$image->save($storage);
			return $storage;
		} catch (\Exception $e) {
			Logger::controller()->error($e->getMessage(), ['exception' => $e]);
		}
		return false;
	}


	protected function getUserRule(array &$params) {
		if (isset($params['id'])) {
			$rules['id'] = ['value' => 0, 'exists' => 'self'];
		} elseif (isset($params['username'])) {
			$rules['username'] = ['value' => '', 'exists' => 'self'];
		} elseif (isset($params['email'])) {
			$rules['email'] = ['value' => '', 'exists' => Profile::validatorQuery('email')];
		} elseif (isset($params['phone'])) {
			$rules['phone'] = ['value' => '', 'exists' => Profile::validatorQuery('phone')];
		} elseif (isset($params['account'])) {
			/*if (is_numeric($params['account']) && preg_match('/^[1-9][0-9]*$/', $params['account'])) {
				$rules['id'] = 0;
			} else*/if (filter_var($params['account'], FILTER_VALIDATE_EMAIL)) {
				$rules['email'] = ['value' => '', 'exists' => Profile::validatorQuery('email')];
			} else {
				$rules['username'] = ['value' => '', 'exists' => 'self'];
			}
			$params[key($rules)] = $params['account'];
		} else {
			throw new Message(['message' => 'validator_required', 'title' => Locale::translate('Account'), 'name' => 'account'], 50);
		}
		return $rules;
	}




	protected function select($column, &$params) {
		if (in_array($column, ['email', 'phone'], true)) {
			if (!$profile = Profile::query('deleted', null, '=')->query('type', $column, '=')->query('status', 1, '=')->query('value', $params[$column], '=')->selectRow()) {
				throw new QueryException(__METHOD__  . '()', $params);
			}
			$user = new User(['id' => $profile->user_id]);
			$user->select();
		} elseif (!$user = User::query($column, $params[$column], '=')->selectRow()) {
			throw new QueryException(__METHOD__  . '()', $params);
		}
		return $user;
	}


	protected function isCaptcha($method  = __FUNCTION__, $id = 0) {
		if (isset($this->captcha[$method]) && (empty($this->captcha[$method]) || $this->getIncrement($method) > $this->captcha[$method] || ($id && $this->getIncrement($method .'-'. $id) > $this->captcha[$method]))) {
			return true;
		}
		return false;
	}

	protected function loginAuth(User $user, $remember = 0, $value = '') {
		if (!$remember) {
			$remember = 86400 * 2;
		} elseif ($remember < 3600) {
			$remember = 3600;
		} elseif ($remember > 86400 * 365) {
			$remember = 86400 * 365;
		} else {
			$remember = intval($remember);
		}

		$request = Route::request();

		$created = new DateTime('now');
		$expired = new DateTime('now');
		$expired->modify('+'. $remember .' seconds');

		$auth = Route::auth();
		$auth->merge(['user_id' => $user->id, 'user_agent' => substr($request->getHeaderLine('User-Agent'), 0, 255), 'created' => $created, 'expired' => $expired]);
		$auth->update();

		$log = new Log(['user_id' => $user->id, 'type' => 'login', 'value' => $value]);
		$log->insert();
	}


	protected function getIncrement($type = __FUNCTION__) {
		$cache = Cache::controller();
		$items = $cache->getItems([Route::ip(), Route::token()->get()]);
		$count = 0;
		foreach ($items as $key => $item) {
			if ($item->get() > $count) {
				$count = $item->get();
			}
		}
		return $count;
	}


	protected function setIncrement($type = __FUNCTION__) {
		$cache = Cache::controller();
		$items = $cache->getItems([Route::ip(), Route::token()->get()]);
		foreach($items as $item) {
			if (!$item->isHit()) {
				$item->set(1);
				$item->expiresAfter(1800);
			} elseif (method_exists($item, 'incr')) {
				$item->incr(1);
			} else {
				$item->set($item->get() + 1);
			}
			$cache->save($item);
		}
	}



	protected function getOAuth2Class(array &$params) {
		if (empty($params['type']) || !is_string($params['type']) || empty($this->OAuth2Types[$params['type']])) {
			throw new Message(['message' => 'validator_exists', 'title' => Locale::translate('Type'), 'name' => 'type'], 50);
		}
		$class = 'App\OAuth2\\' . $this->OAuth2Types[$params['type']];
		return new $class($params);
	}
}
