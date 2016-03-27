<?php
namespace App\OAuth2;

use LianYue\QQApi\OAuth2;
use LianYue\QQApi\QQApiException;

class QQ extends AbstractOAuth2 {

    protected $type = 'qq';

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
		$scope = array_map('trim', $scopes);
		return $this->client->getAuthorizeUri(['scope' => $scope, 'state' => $this->getState(true)]);
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
		return $this->client->getAccessToken($this->params);
	}


	public function setAccessToken(array $accessToken) {
        $this->client->setAccessToken($accessToken);
		return $this;
	}


	public function getUserInfo() {
		$openid = $this->client->getOpenid();
		$userInfo = $this->client->getUsersInfo()->getJson();

        $result = [
            'id' => $openid['openid'],
            'nickname' => $userInfo['nickname'],
            'aravar' => $userInfo[empty($userInfo['figureurl_qq_2']) ? 'figureurl_qq_1' : 'figureurl_qq_2'],
        ];

        if (!empty($userInfo['gender'])) {
            switch ($userInfo['gender']) {
                case 'female':
                case '女':
                    $result['gender'] = 'female';
                    break;
                case 'male':
                case '男':
                    $result['gender'] = 'male';
            }
        }
        return $result;
    }

    protected function client() {
        $option = $this->getOption([
            'client_id' => '',
            'client_secret' => '',
        ]);

        $oauth2 = new OAuth2($option['client_id'], $option['client_secret']);
        if ($state = $this->getState()) {
            $oauth2->setState($state);
        }
        $oauth2->setRedirectUri($this->getCallbackUri());
        return $oauth2;
	}
}
