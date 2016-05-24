<?php
namespace App\Controllers;
use DateTimeZone;

use App\User;
use App\User\Log;
use App\User\Code;
use App\User\Profile as UserProfile;

use App\Services\Mail;
use App\Services\Phone;


use Loli\Uri;
use Loli\View;
use Loli\Route;
use Loli\Storage;
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

		'removeOAuth2' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
		],

        'addBind' => [
            'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
            'RateLimit' => ['limit' => 60, 'reset' => 900, 'user' => true],
        ],

        'sendBind' => [
            'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
            'RateLimit' => ['limit' => 10, 'reset' => 900, 'user' => true],
        ],

        'authBind' => [
            'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
            'RateLimit' => ['limit' => 30, 'reset' => 900, 'user' => true],
        ],

        'sendRemoveBind' => [
            'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
            'RateLimit' => ['limit' => 10, 'reset' => 900, 'user' => true],
        ],

        'removeBind' => [
			'Csrf' => [],
			'Auth' => ['login' => true, 'node' => false],
            'RateLimit' => ['limit' => 30, 'reset' => 900, 'user' => true],
		],
	];

	public $defaultMiddleware = [
		'Auth' => ['login' => true, 'node' => false],
	];


	public function index(array $params)
    {

		return new View(['profile/index'], ['title' => [Locale::translate('Profile index', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
	}


	public function setting(array $params)
    {
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
		return new View(['profile/setting'], ['results' => $rules, 'title' => [Locale::translate('Profile setting', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
	}


	public function postSetting(array $params)
    {
		$rules = $this->setting($params)->results;
		$user = Route::user();

        $profiles = [];

        foreach (User::validator($params, $rules, true) as $key => $value) {
			if (isset($rules[$key]['value']) && isset($rules[$key]['profile-id']) && $rules[$key]['value'] === $value) {
                continue;
            }
            $profile = new UserProfile(['user_id' => $user->id, 'type' => $key, 'value' => $value, 'status' => empty($rules[$key]['examine']) ? 1 : 0]);
            $profile->insert();
		}
		throw new Message('success');
	}


    public function avatar(array $params)
    {
		$avatar = Route::user()->profiles['avatar'];
        $avatar = Storage::uri('/avatars/' . ($avatar ? $avatar : '/default.png'));
		$results = [$avatar];

		return new View(['profile/avatar'], ['results' => $results, 'title' => [Locale::translate('Profile avatar', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
	}

	public function postAvatar(array $params)
    {
        $rules = [
            'avatar_x1' => ['type' => 'number', 'value' => 0, 'min' => 0],
            'avatar_x2' => ['type' => 'number', 'value' => 0, 'min' => 0],
            'avatar_y1' => ['type' => 'number', 'value' => 0, 'min' => 0],
            'avatar_y2' => ['type' => 'number', 'value' => 0, 'min' => 0],
        ];
        if (empty($params['avatar']) || $params['avatar'] instanceof UploadedFileInterface || (is_array($params['avatar']) && reset($params['avatar']) instanceof UploadedFileInterface)) {
            $params['avatar'] = [];
		} elseif (filter_var($params['avatar'], FILTER_VALIDATE_URL)) {
			$rules['avatar'] = ['type' =>  'url', 'pattern' => 'https?\://([0-9a-zA-Z_-]+\.)+[a-z]+[?|/].+'];
		} elseif (!empty($params['avatar'])) {
			$rules['avatar'] = ['type' => 'text', 'value' => ''];
		}

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
            if ($avatar['avatar_x1'] || $avatar['avatar_x2'] || $avatar['avatar_y1'] || $avatar['avatar_y2']) {
                $minX = min($avatar['avatar_x1'], $avatar['avatar_x2']);
                $maxX = max($avatar['avatar_x1'], $avatar['avatar_x2']);

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

                $minY = min($avatar['avatar_y1'], $avatar['avatar_y2']);
                $maxY = max($avatar['avatar_y1'], $avatar['avatar_y2']);
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

        $profile = new UserProfile(['user_id' => $user->id, 'type' => 'avatar', 'value' => $value, 'status' => empty($rules['avatar']['examine']) ? 1 : 0]);
        $profile->insert();
        throw new Message('success');
	}


    public function oauth2(array $params)
    {
        $user = Route::user();
        $profiles = UserProfile::query('deleted', null, '=')->query('user_id', $user->id, '=')->query('status', [0, 1], 'IN')->select();

        foreach($profiles as $profile) {
            if (substr($profile->type, 0, 7) === 'oauth2_') {
                $args = $profile->args;

                if (empty($args['user_info'])) {
                    $nickname = 'Null';
                } elseif (!empty($args['user_info']['nickname'])) {
                    $nickname = $args['user_info']['nickname'];
                } elseif (!empty($args['user_info']['username'])) {
                    $nickname = $args['user_info']['username'];
                } elseif (!empty($args['user_info']['id'])) {
                    $nickname = $args['user_info']['id'];
                } else {
                    $nickname = 'Null';
                }

                $profiles[] = [
                    'id' => $profile->id,
                    'type' => substr($profile->type, 7),
                    'remove' => new Uri(['Profile', 'removeOAuth2'], ['id' => $profile->id, '_csrf' => Route::token()->get()]),
                    'nickname' => $nickname,
                ];
            }
        }
        return new View(['profile/oauth2'], ['results' => $profiles, 'title' => [Locale::translate('Profile oauth2', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
    }

    public function removeOAuth2(array $params)
    {
        $id = UserProfile::validator($params, [['name' => 'id', 'type' => 'number', 'min' => 1, 'value' => 0, 'required' => true]], true)['id'];

        $user = Route::user();
        $results = $this->oauth2($params)->results;

        foreach ($results as $result) {
            if ($result['id'] === $id) {
                if (count($results) <= 1 && !$user->password) {
                    throw new Message(['message' => 'oauth2_remove'], 403);
                }
        		Code::query('id', $id, '=')->query('deleted', null, '=')->value('deleted', new DateTime('now'))->limit(1)->update();
            }
        }
        throw new Message('success');
    }


    public function bind(array $params)
    {
        $user = Route::user();
		$profiles = UserProfile::query('deleted', null, '=')->query('user_id', $user->id, '=')->query('type', ['phone', 'email'], 'IN')->select();
        $results = [];
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
						$value = implode('@', $profile->value);
					break;
				default:
					$value = substr($profile->value, 0, 3) . '***' . substr($profile->value, -3, 3);
			}
            $args = $profile->args;
            $results[] = ['id' => $profile->id, 'user_id' => $profile->user_id, 'type' => $profile->type, 'value' => $value, 'deleted' => empty($args['deleted']) ? null: $args['deleted']];
		}
		return new View(['profile/bind'], ['results' => $results]);
    }


    public function addBind(array $params)
    {
        $type = isset($params['phone']) ? 'phone' : 'email';

        $rules = [
            'password' => ['value' => '', 'minlength' => null, 'required' => null],
            $type => ['value' => '', 'unique' => UserProfile::validatorQuery($type)],
        ];

        $params = UserProfile::validator($params, $rules, true);

        $user = Route::user();

        if ($user->password && !Password::verify($params['password'], $user->password)) {
            throw new Message(['message' => 'validator', 'title' => Locale::translate('Password'), 'name' => 'password'], 400);
        }
        $count = 0;
        foreach ($this->bind()->results as $result) {
            if ($type === $result['type']) {
                ++$count;
            }
        }
        if ($count >= 10) {
            throw new Message(['message' => 'profile_bind_max_count', 'title' => Locale::translate(ucfirst($type)), 'name' => $type, 'rule' => 10], 400);
        }

        $profile = new UserProfile(['user_id' => $user->id, 'type' => $type, 'value' => $params[$type], 'status' => 0]);
        $profile->insert();


        $this->sendBind(['id' => $profile->id]);

        throw new Message('success', 200, ['results' => [$profile]]);
    }


    public function sendBind(array $params)
    {
        $rules = [
            ['name' => 'id', 'type' => 'number', 'min' => 1, 'value' => 0, 'required' => true],
        ];

        $params = UserProfile::validator($params, $rules, true);

        $id = $params['id'];

        $user = Route::user();

        $profile = UserProfile::selectOne($id);

        if (!$profile || $profile->deleted || $profile->user_id !== $user->id || $profile->status != 0) {
            throw new Message(['message' => 'validator', 'title' => Locale::translate('Id'), 'name' => 'id'], 400);
        }

        $code = new Code(['type' => 'user_bind', 'user_id' => $user->id, 'value' => $profile->id]);
		$code->insert();

        try {
            switch ($profile->type) {
                case 'email':
                    $mail = new Mail();
                    $mail->addAddress($profile->value, $user->profiles['nickname']);
                    $mail->isHTML(true);
                    $mail->Subject = Locale::translate(['profile_bind_email_subject', 'nickname' => $user->profiles['nickname'], 'username' => $user->username], ['service', 'default'], 'E-mail Bind');
                    $mail->Body = Locale::translate(['profile_bind_email_html', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], '<p>Hello {nickname}. </p><p>Your verification code is ({code}). </p><p>It used to bind your email. </p>');
                    $mail->AltBody = Locale::translate(['profile_bind_email_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], "Hello {nickname}. \r\nYour verification code is ({code}). \r\nIt used to bind your email. ");
                    $mail->send();
                    break;
                case 'phone':
                    $text = Locale::translate(['profile_bind_phone_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], "Hello {nickname}. \r\nYour verification code is ({code}). \r\nIt used to bind your phone. ");
                    Phone::sms($profile->value, $text);
            }
        } catch (\Exception $e) {
            Logger::controller()->error($e->getMessage(), ['exception' => $e, 'value' => $profile->value]);
            throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }

        throw new Message('success');
    }


    public function authBind(array $params)
    {
        $rules = [
            ['name' => 'id', 'type' => 'number', 'min' => 1, 'value' => 0, 'required' => true],
            ['name' => 'code', 'type' => 'text', 'value' => '', 'required' => true],
        ];

        $params = UserProfile::validator($params, $rules, true);

        $id = $params['id'];

        $user = Route::user();

        $profile = UserProfile::selectOne($id);

        if (!$profile || $profile->deleted || $profile->user_id !== $user->id || $profile->status != 0) {
            throw new Message(['message' => 'validator', 'title' => Locale::translate('Id'), 'name' => 'id'], 400);
        }

        if (UserProfile::query('deleted', null, '=')->query('type', $profile->type, '=')->query('value', $profile->value, '=')->query('status', 1, '=')->selectOne()) {
            throw new Message(['message' => 'profile_bind_unique', 'title' => Locale::translate(ucfirst($profile->type))], 400);
        }

        Code::validator('user_bind', $params['code'], $user->id, Route::token()->get(), $result['id'], true);

        $profile->status = 1;
        $profile->update();

        throw new Message('success');
    }

    public function sendRemoveBind(array $params)
    {
        $rules = [
            ['name' => 'id', 'type' => 'number', 'min' => 1, 'value' => 0, 'required' => true],
        ];

        $params = UserProfile::validator($params, $rules, true);

        $id = $params['id'];

        $profile = UserProfile::selectOne($id);

        if (!$profile || $profile->deleted || $profile->user_id !== $user->id) {
            throw new Message(['message' => 'validator', 'title' => Locale::translate('Id'), 'name' => 'id'], 400);
        }

        $code = new Code(['type' => 'user_bind_remove', 'user_id' => $user->id, 'value' => $profile->id]);
		$code->insert();

        try {
            switch ($profile->type) {
                case 'email':
                    $mail = new Mail();
                    $mail->addAddress($profile->value, $user->profiles['nickname']);
                    $mail->isHTML(true);
                    $mail->Subject = Locale::translate(['profile_bind_remove_email_subject', 'nickname' => $user->profiles['nickname'], 'username' => $user->username], ['service', 'default'], 'E-mail Bind');
                    $mail->Body = Locale::translate(['profile_bind_remove_email_html', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], '<p>Hello {nickname}. </p><p>Your verification code is ({code}). </p><p>It used to remove your binding email. </p>');
                    $mail->AltBody = Locale::translate(['profile_bind_remove_email_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], "Hello {nickname}. \r\nYour verification code is ({code}). \r\nIt used to remove your binding email. ");
                    $mail->send();
                    break;
                case 'phone':
                    $text = Locale::translate(['profile_bind_remove_phone_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code], ['service', 'default'], "Hello {nickname}. \r\nYour verification code is ({code}). \r\nIt used to remove your binding phone. ");
                    Phone::sms($profile->value, $text);
            }
        } catch (\Exception $e) {
            Logger::controller()->error($e->getMessage(), ['exception' => $e, 'value' => $profile->value]);
            throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }


        throw new Message('success');
    }

    public function removeBind(array $params)
    {


        foreach ($this->bind($params)->results as $result) {
            if ($result['id'] !== $id) {
                continue;
            }
            $profile = new UserProfile(['id' => $id]);
            $profile->select();
            $args = $profile->args;

            $rules = [
                'password' => ['value' => '', 'minlength' => null, 'required' => null],
                $profile->type => ['value' => ''],
            ];
            $params = UserProfile::validator($params, $rules, true);

            if ($user->password && !Password::verify($params['password'], $user->password)) {
                throw new Message(['message' => 'validator', 'title' => Locale::translate('Password'), 'name' => 'password'], 400);
            }



            if (!empty($params['cancel'])) {
                // 撤销移除
                if (!empty($result['deleted'])) {
                    unset($args['deleted'], $args['token'], $args['ip']);
                    $profile->args = $args;
                    $profile->update();
                }
                throw new Message('success');
                // 撤销移除
            } elseif ($profile->status == 0 || (!empty($args['deleted']) && $args['deleted'] < gmdate('Y-m-d H:i:s'))) {
                // 未审核 或者 或者能直接强制删除的
                $profile->deleted = new DateTime('now');
                $profile->update();
                throw new Message('success');
            } elseif (!empty($params['forcibly'])) {
                if (empty($args['deleted'])) {;
                    $args = ['ip' => Route::ip(), 'token' => Route::token()->get(), 'deleted' => gmdate('Y-m-d H:i:s', time() + 86400 * 15)] + $args;
                    $profile->args = $args;
                    $profile->update();
                    try {
                        switch ($profile->type) {
                            case 'email':
                                $mail = new Mail();
                                $mail->addAddress($profile->value, $user->profiles['nickname']);
                                $mail->isHTML(true);
                                $mail->Subject = Locale::translate(['profile_bind_email_forcibly_remove_subject', 'nickname' => $user->profiles['nickname'], 'username' => $user->username], ['account_bind', 'default'], 'Remove e-mail notification');
                                $mail->Body = Locale::translate(['profile_bind_email_forcibly_remove_html', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], '<p>Hello {nickname}. </p><p>You are forced to remove the e-mail request</p>');
                                $mail->AltBody = Locale::translate(['profile_bind_email_forcibly_remove_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], "Hello {nickname}. \r\nYou are forced to remove the e-mail request\r\n");
                                $mail->send();
                                break;
                            case 'phone':
                                $text = Locale::translate(['profile_bind_email_forcibly_remove_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], "Hello {nickname}\r\nnYou are forced to remove the e-mail request\r\n");
                                Phone::sms($profile->value, $text);
                        }
                    } catch (\Exception $e) {
                        Logger::controller()->error($e->getMessage(), ['exception' => $e, 'value' => $profile->value]);
                        throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
                    }
                }

                throw new Message('success', 200, ['results' => [new DateTime($profile->args['deleted'])]]);
            } else {
                // 验证码删除
                $code = Code::validator($params, ['code' =>['value' => '']], true)['code'];

                // 验证
                Code::validator('user_bind_remove', $code, $user->id, Route::token()->get(), $profile->id, true);
                $profile->deleted = new DateTime('now');
                $profile->update();
                throw new Message('success');
            }
        }

        throw new Message('success');
    }


    /*public function removeBind(array $params) {
        $id = UserProfile::validator($params, [['name' => 'id', 'type' => 'number', 'min' => 1, 'value' => 0, 'required' => true]], true)['id'];
        $user = Route::user();

        $results = $this->bind($params)->results;
        foreach ($results as $result) {
            if ($result['id'] !== $id) {
                continue;
            }
            $profile = new UserProfile(['id' => $id]);
            $profile->select();
            $args = $profile->args;




            // 验证密码 和值
    		$rules = [
                'password' => ['value' => '', 'minlength' => null, 'required' => null],
                $profile->type => ['value' => ''],
            ];

            $params = UserProfile::validator($params, $rules, true);
            if ($user->password && !Password::verify($params['old_password'], $user->password)) {
    			throw new Message(['message' => 'validator', 'title' => Locale::translate('Password'), 'name' => 'password'], 400);
    		}

            if ($params[$profile->type] !== $profile->value) {
                throw new Message(['message' => 'validator', 'title' => Locale::translate($profile->type == 'email' ? 'Email' : 'Phone'), 'name' => $profile->type], 400);
            }


            if (!empty($params['cancel'])) {
                // 撤销移除
                if (!empty($result['forcibly'])) {
                    unset($args['forcibly'], $args['token'], $args['ip']);
                    $profile->args = $args;
                    $profile->update();
                }
                throw new Message('success');
                // 撤销移除
            } elseif ($profile->status == 0 || (!empty($args['deleted']) && $args['deleted'] < gmdate('Y-m-d H:i:s'))) {
                // 未审核 或者 或者能直接强制删除的
                $profile->deleted = new DateTime('now');
                $profile->update();
                throw new Message('success');
            } elseif (!empty($params['forcibly'])) {
                if (empty($params['value'])) {
                    throw new Message(['message' => 'validator_empty', 'title' => Locale::translate('Value'), 'name' => 'value'], 400);
                }

                // 强制删除
                if (empty($args['deleted'])) {
                    $deleted  = gmdate('Y-m-d H:i:s', time() + 86400 * 15);
                    $args = ['ip' => Route::ip(), 'token' => Route::token()->get(), 'deleted' => $deleted] + $args;
                    $profile->args = $args;
                    $profile->update();

                    $datetime = new DateTime($deleted);
                    if ($user->profiles['timezone']) {
                        $datetime->setTimeZone(new DateTimeZone($user->profiles['timezone']));
                    }

                    switch ($profile->type) {
            			case 'email':
            				try {
            					$mail = new Mail();
            					$mail->addAddress($profile->value, $user->profiles['nickname']);
            					$mail->isHTML(true);
            					$mail->Subject = Locale::translate(['forcibly_remove_account_bind_email_subject', 'nickname' => $user->profiles['nickname'], 'username' => $user->username], ['account_bind', 'default'], 'Remove the mailbox binding');
            					$mail->Body = Locale::translate(['forcibly_remove_account_bind_email_html', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], '<p>Hello {nickname}</p><p>You are trying to remove the mailbox binding</p>');
            					$mail->AltBody = Locale::translate(['forcibly_remove_account_bind_email_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'code' => $code->code, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], "Hello {nickname}\r\nYou are trying to remove the mailbox binding\r\n");
            					$mail->send();
            				} catch (\Exception $e) {
            					Logger::controller()->error($e->getMessage(), ['exception' => $e, 'address' => $profile->value]);
            					throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
            				}
            				break;
            			case 'phone':
            				try {
                                $text = Locale::translate(['forcibly_remove_account_bind_phone_text', 'nickname' => $user->profiles['nickname'], 'username' => $user->username, 'deleted' => $datetime->format('Y-m-d')], ['account_bind', 'default'], "Hello {nickname}\r\nYou are trying to remove the mailbox binding\r\n");
            					Phone::sms($profile->value, $text);
            				} catch (\Exception $e) {
            					Logger::controller()->error($e->getMessage(), ['exception' => $e, 'phone' => $profile->value]);
            					throw new Message(['message' => 'exception', 'value' => $e->getMessage(), 'code' => $e->getCode()], 500);
            				}
            		}
                }
                throw new Message('success', 200, ['deleted' => $profile->args['deleted']]);
            } else {
                // 验证码删除
                $code = Code::validator($params, ['code' =>['value' => '']], true)['code'];

                // 验证
                Code::validator($user->id, 'bind_remove', $params['code'], ['token' => Route::token()->get(), 'id' => $profile->id], true);

                // 修改
                $profile->deleted = new DateTime('now');
                $profile->update();
                throw new Message('success');
            }
        }

        throw new Message(['message' => 'validator_exists', 'title' => Locale::translate('Id'), 'name' => 'id'], 400);
    }*/








	public function password(array $params)
    {
		$rules = [
			'old_password' => ['value' => '', 'minlength' => null, 'required' => null],
			'new_password' => ['value' => ''],
			'new_password_again' => ['value' => ''],
		];
		$rules = User::validator()->rules($rules, true);
		return new View(['profile/password'], ['results' => $rules, 'title' => [Locale::translate('Profile passowrd', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
	}


	public function postPassword(array $params)
    {
		$rules = $this->password($params)->results;
		$params = User::validator($params, $rules, true);
		$user = Route::user();

		// Password  verify
		if ($user->password && !Password::verify($params['old_password'], $user->password)) {
			throw new Message(['message' => 'validator', 'title' => Locale::translate('Old Password'), 'name' => 'old_password'], 400);
		}

		// Update password
		$password = $user->password;
		$user->password = $params['new_password'];
		$user->update();

		// Insert log
		$log = new Log(['type' => 'password', 'value' => $password]);
		$log->insert();

		throw new Message('success');
	}


	public function log(array $params)
    {
		$paginator = new Paginator(new Uri(['Profile', 'log']), max(1, empty($params['page']) ? 1 : $params['page']));
		$logs = Log::query('user_id', Route::auth()->user_id, '=')->offset($paginator->offset)->limit($paginator->limit)->order('id', 'DESC')->option('rows', true);
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
            $result->created_diff = $result->created->formatDiff();
		}
		$paginator->total = $logs->count();
		return new View(['profile/log'], ['results' => $results, 'paginator' => $paginator, 'title' => [Locale::translate('Profile log', ['title', 'default']), Locale::translate('title', ['title', 'default'])]]);
	}
}
