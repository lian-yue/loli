<?php
namespace App\OAuth2;

use LianYue\WeiboApi\OAuth2;
use LianYue\WeiboApi\WeiboApiException;

class Weibo extends AbstractOAuth2 {

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
		$userInfo = $this->client->getUsersShow()->getJson();

        $result = [];
        foreach([
            'idstr' => 'id',
            'screen_name' => 'username',
            'name' => 'nickname',
            'description' => 'description',
            'profile_image_url' => 'avatar',
            'avatar_large' => 'avatar',
            'avatar_hd' => 'avatar',
            'gender' => 'gender',
            'verified' => 'verified',
        ] as $key => $value) {
            if (!empty($userInfo->$key)) {
                $result[$value] = $userInfo->$key;
            }
        }
        if (!empty($result['gender'])) {
            switch ($result['gender']) {
                case 'f':
                    $result['gender'] = 'female';
                    break;
                case 'm':
                    $result['gender'] = 'male';
                    break;
                default:
                    unset($result['gender']);
            }
        }


        try {
            $email = $this->client->api('GET', '/2/account/profile/email.json')->getJson(true);
            if ($email) {
                $email = reset($email);
            }
            if (!empty($email['email'])) {
                $result['email'] = $email['email'];
            }
        } catch (WeiboApiException $e) {

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
