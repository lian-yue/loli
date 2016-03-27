<?php
namespace App\OAuth2;

use LianYue\BaiduApi\OAuth2;

class Baidu extends AbstractOAuth2 {

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
		$userInfo = $this->client->getUserInfo()->getJson();

		$result = [
			'id' => $userInfo->userid,
			'username' => $userInfo->username,
		];

        if (!empty($userInfo->birthday) && $userInfo->birthday !== '0000-00-00') {
            $result['birthday'] = $userInfo->birthday;
        }
        if (!empty($userInfo->portrait)) {
            $result['avatar'] = 'http://tb.himg.baidu.com/sys/portrait/item/' .  $userInfo->portrait;
        }

        if (!empty($userInfo->userdetail)) {
            $result['description'] = $userInfo->userdetail;
        }
        if (isset($userInfo->sex) && $userInfo->sex !== "") {
            switch ($userInfo->sex) {
                case '0':
                case '女':
                case 'female':
                case 'Female':
                    $result['gender'] = 'female';
                    break;
                default:
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
