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
namespace App\OAuth2;

class Google extends AbstractOAuth2{


	public function getRedirect() {
		return $this->client->createAuthUrl();
	}

	public function isAuthorize() {
		if (empty($this->params['code'])) {
			return false;
		}
		if (!empty($this->params['error'])) {
			return false;
		}
		return true;
	}

	public function getAccessToken() {
		if (!($accessToken = $this->client->getAccessToken()) || !($accessToken = json_decode($accessToken, true))) {
			if (empty($this->params['code'])) {
				throw new \InvalidArgumentException('The code is empty');
			}
			if (!is_string($this->params['code'])) {
				throw new \InvalidArgumentException('Code instead of a string');
			}
			$this->client->authenticate($this->params['code']);
			$accessToken = $this->client->getAccessToken();
		}
		if (!$accessToken) {
			throw new \InvalidArgumentException('The access token is empty');
		}
		return $accessToken;
	}

	public function setAccessToken(array $accessToken) {
		$this->client->setAccessToken(json_encode($accessToken));
		return $this;
	}

	public function getUserInfo() {
		$this->getAccessToken();
		$oauth2 = new \Google_Service_Oauth2($this->client);
		$userInfo = $oauth2->userinfo_v2_me->get();

		$results = [];
		foreach([
			'getId' => 'id',
			'getEmail' => 'email',
			'getVerifiedEmail' => 'verified_email',
			'getPicture' => 'avatar',
			'getName' => 'nickname',
			'getGender' => 'gender',
			'getLocale' => 'language',
		] as $method => $key) {
			if ($value = $userInfo->$method()) {
				$results[$key] = $value;
			}
		}
		if (!empty($results['gender']) && $results['gender'] === 'other') {
			unset($results['gender']);
		}
		return $results;
	}


	protected function client() {
		$option = $this->getOption([
			'client_id' => '',
			'client_secret' => '',
			'api_key' => '',
			'scopes' => ['https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'],
		]);
		if (!is_array($option['scopes'])) {
			$option['scopes'] = explode(',', $option['scopes']);
		}
		$option['scopes'] = implode(' ', array_map('trim', $option['scopes']));
		$client = new \Google_Client();
		$client->setClientId($option['client_id']);
		$client->setClientSecret($option['client_secret']);
		$client->setDeveloperKey($option['api_key']);
		$client->setScopes($option['scopes']);
		$client->setRedirectUri($this->getCallbackUri());
		return $client;
	}
}
