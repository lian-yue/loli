<?php
namespace App\OAuth2;

use Loli\Uri;
use Loli\Route;

abstract class AbstractOAuth2{

	protected $params = [];

	protected $client;

	protected $name;

	protected $type;

	public function __construct(array $params) {
		$this->params = $params;
		$this->client = $this->client();
	}



	abstract public function getRedirect();

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


	protected function getOption(array $default = []) {
		return configure(['oauth2', $this->getType()], []) + $default;
	}

	protected function getCallbackUri() {
		return (string) (new Uri(['Account', 'OAuth2Callback'], ['type' => $this->getType()]))->withScheme(Route::request()->getUri()->getScheme());
	}
}
