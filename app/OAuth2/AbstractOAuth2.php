<?php
namespace App\OAuth2;

use Loli\Uri;
use Loli\Route;
use Loli\Session;

abstract class AbstractOAuth2{

	protected $params = [];

	protected $client;

	protected $name;

	protected $type;

	public function __construct(array $params) {
		$this->params = $params;
		$this->client = $this->client();
	}



	abstract public function getRedirectUri();

	abstract public function isAuthorize();

	abstract public function getAccessToken();

	abstract public function setAccessToken(array $array);

	abstract public function getUserInfo();

	abstract protected function client();

	public function getType() {
		if ($this->type) {
			return $this->type;
		}
		$class = static::class;
		$class = explode('\\', $class);
		return snake(end($class));
	}

	public function getName() {
		if ($this->name) {
			return $this->name;
		}
		$class = static::class;
		$class = explode('\\', $class);
		return end($class);
	}


    protected function getState($new = false) {
        $item = Session::getItem('oauth2_state_' . $this->getType());
        if ($new) {
            $state = md5(uniqid(mt_rand(), true));
            $item->set($state)->expiresAfter(3600);
            Session::save($item);
            return $state;
        }
        return $item->get();
    }


	protected function getOption(array $default = []) {
		return configure(['oauth2', $this->getType()], []) + $default;
	}

	protected function getCallbackUri() {
		return (string) (new Uri(['Account', 'OAuth2Callback'], ['type' => $this->getType()]))->withScheme(Route::request()->getUri()->getScheme());
	}
}
