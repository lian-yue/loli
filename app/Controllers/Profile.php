<?php
namespace App\Controllers;
use DateTimeZone;

use App\User;
use App\User\Log;
use App\User\Profile as UserProfile;

use Loli\Uri;
use Loli\View;
use Loli\Route;
use Loli\Message;
use Loli\DateTime;
use Loli\Locale;
use Loli\Paginator;
use Loli\Validator;
use Loli\Controller;
use Loli\Crypt\Int;
use Loli\Crypt\Code as CryptCode;
use Loli\Crypt\Password;

class Profile extends Controller {

	public $middleware = [
		'postSetting' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
		],

		'postAvatar' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
		],

		'postPassword' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
		],

		'addBind' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
		],
	];

	public $defaultMiddleware = [
		'Auth' => ['login' => true, 'node' => false],
	];




	public function index(array $params) {
		return new View(['profile/index']);
	}

	public function setting(array $params) {
		$user = Route::user();

		$profiles = [];
		foreach(UserProfile::query('deleted', null, '=')->query('user_id', $user->id, '=')->select() as $profile) {
			$profiles[$profile->type][] = $profile;
		}

		$rules = [
			'id' => ['disabled' => true, 'value' => $user->id],
			'username' => ['disabled' => true, 'value' => $user->username],
		];


		$timezoneOption = [];
		foreach (DateTimeZone::listIdentifiers() as $value) {
			$timezoneOption[$value] = DateTime::translate($value);
		}
		foreach ([
				'nickname' => [],
				'description' => [],
				'gender' => [],
				'birthday' => [],
				'timezone' => ['option' => $timezoneOption],
				'language' => ['value'=> Locale::getLanguage(), 'option' => Locale::getLanguageLists()],
			] as $key => $rule) {
			if (isset($profiles[$key])) {
				$profile = end($profiles[$key]);
				$rule = ['value' => $profile->value, 'level' => $profile->level, 'profile-id' => $profile->id, 'status' => $profile->status] + $rule;
			} elseif (isset(User::$profiles[$key])) {
				$rule = ['value' => User::$profiles[$key], 'level' => 0, 'profile-id' => 0, 'status' => 1] + $rule;
			} else {
				$rule = ['level' => 0, 'profile-id' => 0, 'status' => 1] + $rule;
			}
			$rules[$key] = $rule;
		}
		$rules = User::validator()->rules($rules, true);
		return new View(['profile/setting'], ['results' => $rules]);
	}


	public function postSetting(array $params) {
		$rules = $this->setting($params)->results;

		$params = User::validator($params, $rules, true);

		$user = Route::user();
		foreach ($params as $key => $value) {
			if (!isset($rules[$key]['value']) || !isset($rules[$key]['profile-id']) || $rules[$key]['value'] !== $value) {
				$profile = new UserProfile(['user_id' => $user->id, 'type' => $key, 'value' => $value, 'status' => empty($rules[$key]['examine']) ? 1 : 0]);
				$profile->insert();
			}
		}
		throw new Message(200);
	}


	public function bind(array $params) {
		$user = Route::user();
		$profiles = [];
		foreach(UserProfile::query('deleted', null, '=')->query('user_id', $user->id, '=')->select() as $profile) {
			$profiles[$profile->type][] = $profile;
		}

		$types = [
			'email',
			'phone',
			'oauth2_google',
			'oauth2_facebook',
			'oauth2_twitter',
			'oauth2_qq',
			'oauth2_baidu',
			'oauth2_weibo',
		];
		$profiles = UserProfile::query('deleted', null, '=')->query('user_id', $user->id, '=')->query('type', $types, 'IN')->query('status', [0, 1], 'IN')->select();
		return new View(['profile/bind'], ['results' => $profiles, 'types' => $types]);
	}

	public function addBind(array $params) {
		$user = Route::user();
		if (empty($params['type']) || !is_scalar($params['type'])) {
			throw new Message('Type can not be empty', 404);
		}

		$params['type'] = (string) $params['type'];

		if (isset($params['value'])) {
			$params[$params['type']] = $params['value'];
		}

		switch ($params['type']) {
			case 'email':
				$params = User::validator($params, [['name' => 'email', 'type' => 'email', 'length' => 64, 'required' => true, 'value' => '', 'unique' => UserProfile::validatorQuery('email')]], true);
				$profile = new UserProfile(['user_id' => $user->id, 'type' => 'email', 'value' => $params['phone'], 'status' => 0]);
				$profile->insert();
				break;
			case 'phone':
				$params = User::validator($params, [['name' => 'phone', 'type' => 'tel', 'required' => true, 'value' => '', 'unique' => UserProfile::validatorQuery('phone')]], true);
				$profile = new UserProfile(['user_id' => $user->id, 'type' => 'phone', 'value' => $params['phone'], 'status' => 0]);
				$profile->insert();
				break;
			default:
				throw new Message('redirect', 302, [], new Uri(['Account/OAuth2', 'login'], ['type' => substr($params['type'], 0, -7), '_token' => Route::token()->get(), 'redirect' => new Uri(['Profile', 'bind'])]), 0);
		}
	}

	public function activateBind(array $params) {


	}


	public function deleteBind(array $params) {


	}


	public function avatar(array $params) {
		$avatar = Route::user()->profiles['avatar'];
		$results = [$avatar];

		return new View(['profile/avatar'], ['results' => $results]);
	}


	public function postAvatar(array $params) {

		$rules = [
			'x1' => ['type' => 'number', 'value' => 0, 'min' => 0],
			'x2' => ['type' => 'number', 'value' => 0, 'min' => 0],
			'y1' => ['type' => 'number', 'value' => 0, 'min' => 0],
			'y2' => ['type' => 'number', 'value' => 0, 'min' => 0],
		];


		$mimeType = ['image/png', 'image/jpeg', 'image/bmp', 'image/webp', 'image/gif'];
		if (empty($params['avatar']) || $params['avatar'] instanceof UploadedFileInterface || (is_array($params['avatar']) && reset($params['avatar']) instanceof UploadedFileInterface)) {
			$rules['avatar'] = ['type' => 'file', 'accept' => implode(',', $mimeType), 'required' => true, 'size' => '4 MB'];
		} elseif (filter_var($params['avatar'], FILTER_VALIDATE_URL)) {
			$rules['avatar'] = ['type' =>  'url', 'pattern' => '^https?\://([0-9a-zA-Z_-]+\.)+[a-z]+[?|/].+'];
		} else {
			$rules['avatar'] = ['type' => 'text', 'value' => ''];
		}


		$user = Route::user();
		$params = User::validator($params, $rules, true);

		$value = gmdate('Y/m/d/h/i/') . Int::encode($user->id);
		$storage = 'storage://avatars/' . $value . '.png';

		try {
			if ($params['avatar'] instanceof UploadedFileInterface) {
				$image = Image::make($params['avatar']->getStream()->getContents());
			} elseif (filter_var($params['avatar'], FILTER_VALIDATE_URL)) {
				$image = Image::make($params['avatar']);
			} elseif ($binary = base64_decode($params['avatar'])) {
				$image = Image::make(base64_encode($binary));
			} else {
				throw new InvalidArgumentException('The unknown data type');
			}


			// 剪切小点
			if ($params['x1'] || $params['x2'] || $params['y1'] || $params['y2']) {
				$minX = min($params['x1'], $params['x2']);
				$maxX = max($params['x1'], $params['x2']);

				// 最小偏移要比图片小一字节
				if ($minX >= $image->width()) {
					$minX = $image->width() - 1;
				}

				$width = $maxX - $minX;

				if ($width < 1) {
					// 宽度最少一字节
					$width = 1;
				} elseif (($width + $minX) > $image->width()) {
					// 宽度总不能大于原图片宽度
					$width = $image->width() - $minX;
				}

				$minY = min($params['y1'], $params['y2']);
				$maxY = max($params['y1'], $params['y2']);
				if ($minY >= $image->height()) {
					$minY = $image->height() - 1;
				}

				$height = $maxX - $minX;
				if ($height < 1) {
					$height = 1;
				} elseif (($height + $minY) > $image->height()) {
					$height = $image->height() - $minY;
				}

				$image = $image->crop($width, $height, $minX, $minY);
			}

			$image->fit(256, 256);
			if (!is_dir($dir = dirname($storage)) && !mkdir($dir, true)) {
				throw new \RuntimeException(__METHOD__. '() Failed to create directory');
			}
			$image->save($storage);
		} catch (\Exception $e) {
			Logger::controller()->error($e->getMessage(), ['exception' => $e]);
			throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
		}

		// insert 用户头像
		$profile = new UserProfile(['user_id' => $user->id, 'type' => 'avatar', 'value' => $value, 'status' => 0]);
		$profile->insert();

		throw new Message(200);
	}



	public function password(array $params) {
		$rules = [
			'old_password' => ['value' => '', 'minlength' => null],
			'new_password' => ['value' => ''],
			'new_password_again' => ['value' => ''],
		];
		$rules = User::validator()->rules($rules, true);
		return new View(['profile/password'], ['results' => $rules]);
	}



	public function postPassword(array $params) {
		$rules = $this->password($params)->results;
		User::validator($params, $rules, true);
		$user = Route::user();

		// Password  verify
		if (!Password::verify($params['old_password'], $user->password)) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Old Password'), 'name' => 'old_password'], 50);
		}

		// Update password
		$password = $user->password;
		$user->password = $params['new_password'];
		$user->update();

		// Insert log
		$log = new Log(['type' => 'password', 'value' => $password]);
		$log->insert();

		throw new Message(200);
	}




	public function log(array $params) {
		$paginator = new Paginator(new Uri(['Profile', 'log']), max(1, empty($params['page']) ? 1 : $params['page']));

		$logs = Log::query('user_id', Route::auth()->user_id, '=')->offset($paginator->offset)->limit($paginator->limit)->option('rows', true);
		if (!empty($params['type'])) {
			$logs->query('type', $params['type'], '=');
		}
		$results = $logs->select();
		foreach ($results as $result) {
			$pos = strpos($result->ip, ':') === false ? '.' : ':';
			$ip = explode($pos, $result->ip);
			end($ip);
			$ip[key($ip)] = '*';
			$result->ip = implode($pos, $ip);
		}
		$paginator->total = $logs->count();
		return new View(['profile/log'], ['results' => $results, 'paginator' => $paginator]);
	}
}
