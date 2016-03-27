<?php
namespace App\OAuth2;

use Facebook\PersistentData\FacebookCachePersistentDataHandler;
use Facebook\Authentication\AccessToken;

class Facebook extends AbstractOAuth2 {

	public function getRedirectUri() {
		$option = $this->getOption([
			'scope' => [],
			'scopes' => [],
		]);
        if ($option['scope']) {
            $scopes = $option['scope'];
        } else {
            $scopes = $option['scopes'];
		}
        if (!is_array($scopes)) {
            $scopes = explode(',', $scopes);
        }
		$scopes = array_map('trim', $scopes);

		return $this->client->getRedirectLoginHelper()->getLoginUrl($this->getCallbackUri(), $scopes);
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
		if (!$accessToken = $this->client->getDefaultAccessToken()) {
			$accessToken = $this->client->getRedirectLoginHelper()->getAccessToken($this->getCallbackUri());
			$this->client->setDefaultAccessToken($accessToken);
		}
		return ['access_token' => $accessToken->getValue(), 'expires_at' => $accessToken->getExpiresAt()];
	}



	public function setAccessToken(array $accessToken) {
		$this->client->setDefaultAccessToken(new AccessToken($accessToken['access_token'], empty($accessToken['expires_at']) ? 0 : $accessToken['expires_at']));
		return $this;
	}


	public function getUserInfo() {
		$this->getAccessToken();

		$response = $this->client->get('/me?fields=id,name,gender,locale,timezone,verified,email,birthday');
		$userNode = $response->getGraphUser();

		$response = $this->client->get('/me/picture?redirect=0&height=256&type=normal&width=256');
		$picture = $response->getGraphNode()->getField('url');
		$userInfo = [
			'id' => $userNode->getId(),
			'nickname' => $userNode->getName(),
			'gender' => $userNode->getGender(),
			'language' => strtr($userNode->getField('locale'), '_', '-'),
			'timezone' => $userNode->getField('timezone'),
			'birthday' => $userNode->getBirthday(),
			'avatar' => $picture,
			'email' => $userNode->getEmail(),
			'verified' => $userNode->getField('verified'),
		];
		if (!empty($userInfo['gender']) && $userInfo['gender'] === 'other') {
			unset($userInfo['gender']);
		}
		return array_filter($userInfo);
	}

	protected function client() {
		$option = $this->getOption([
			'app_id' => '',
			'app_secret' => '',
		]);
		$persistentDataHandler = FacebookCachePersistentDataHandler::class;
		return new \Facebook\Facebook([
			'app_id' => $option['app_id'],
			'app_secret' => $option['app_secret'],
			'default_graph_version' => 'v2.5',
			'persistent_data_handler' => new $persistentDataHandler,
		]);
	}
}






namespace Facebook\PersistentData;
use Facebook\Exceptions\FacebookSDKException;

use Loli\Session;

if (!class_exists(__NAMESPACE__ . '\\FacebookCachePersistentDataHandler')) {
	class FacebookCachePersistentDataHandler implements PersistentDataInterface {

		protected $sessionPrefix = 'FBRLH_';

	    public function get($key)
		{
			return Session::getItem($this->sessionPrefix . $key)->get();
	    }

	    /**
	     * @inheritdoc
	     */
	    public function set($key, $value)
	    {
			Session::save(Session::getItem($this->sessionPrefix . $key)->set($value)->expiresAfter(3600));
	    }
	}

}
