<?php
namespace App\OAuth2;
use Loli\Session;

use Abraham\TwitterOAuth\TwitterOAuth;
class Twitter extends AbstractOAuth2{

	protected $accessToken =[];

	public function getRedirectUri() {
		$requestToken = $this->client->oauth('oauth/request_token', ['oauth_callback' => $this->getCallbackUri()]);
		$item = Session::getItem('oauth2_twitter_request_token');
		$item->set($requestToken)->expiresAfter(3600);
		Session::save($item);
		return $this->client->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
	}

	public function isAuthorize() {
		if (empty($this->params['oauth_verifier'])) {
			return false;
		}
		if (empty($this->params['oauth_token'])) {
			return false;
		}
		if (!empty($this->params['denied'])) {
			return false;
		}
		return true;
	}

	public function getAccessToken() {
		if (!$this->accessToken) {
			if (empty($this->params['oauth_token'])) {
				throw new \InvalidArgumentException('The oauth token is empty');
			}
			if (empty($this->params['oauth_verifier'])) {
				throw new \InvalidArgumentException('The oauth verifier is empty');
			}
			$item = Session::getItem('oauth2_twitter_request_token');
			if (!is_array($requestToken = $item->get())) {
				throw new \InvalidArgumentException('The Request token is empty');
			}
			Session::deleteItem('oauth2_twitter_request_token');

			if ($requestToken['oauth_token'] !== $this->params['oauth_token']) {
				throw new \InvalidArgumentException('The Request token is incorrect');
			}
			$this->client->setOauthToken($requestToken['oauth_token'], $requestToken['oauth_token_secret']);

			$accessToken = $this->client->oauth('oauth/access_token', ['oauth_verifier' => $this->params['oauth_verifier']]);
			unset($accessToken['screen_name'], $accessToken['user_id']);
			$this->setAccessToken($accessToken);
			$this->accessToken = $accessToken;
		}
		return $this->accessToken;
	}

	public function setAccessToken(array $accessToken) {
		if (empty($accessToken['oauth_token'])) {
			throw new \InvalidArgumentException('The oauth token is empty');
		}
		if (empty($accessToken['oauth_token_secret'])) {
			throw new \InvalidArgumentException('The oauth token secret is empty');
		}
		$this->accessToken = $accessToken;
		$this->client->setOauthToken($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
		return $this;
	}

	public function getUserInfo() {
		$this->getAccessToken();
		$user = $this->client->get('account/verify_credentials');
		$user = to_array($user);
		$results = [];
		foreach([
			'id_str' => 'id',
			'name' => 'nickname',
			'screen_name' => 'username',
			'description' => 'description',
			'time_zone' => 'timezone',
			'lang' => 'language',
			'verified' => 'verified',
			'avatar' => 'avatar',
			'profile_image_url' => 'avatar',
			'profile_image_url_https' => 'avatar',
		] as $key1 => $key2) {
			if (!empty($user[$key1])) {
				$results[$key2] = $user[$key1];
			}
		}
		return $results;
	}


	protected function client() {
		$option = $this->getOption([
			'consumer_key' => '',
			'consumer_secret' => '',
		]);
		return new TwitterOAuth($option['consumer_key'], $option['consumer_secret']);
	}
}
