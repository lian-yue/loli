<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-28 11:44:06
/*
/* ************************************************************************** */
namespace Model\RBAC;
use Loli\Model;
class Token extends Model{
	protected $tables = ['rbac_tokens'];

	protected $columns = [
		'token' => ['type' => 'text', 'length' => 16, 'primary' => 0],
		'userID' => ['type' => 'int', 'length' => 4, 'unsigned' => true],
		'IP' => ['type' => 'text', 'length' => 40],
		'created' => ['type' => 'datetime'],
		'expire' => ['type' => 'datetime'],
	];

	protected $primary = ['token'];

	protected $primaryTTL = 1800;

	public function currentUserID() {
		$token = $this->route->request->getToken();
		if (!count($results = $this->select($token))) {
			$this->values(['token' => $token, 'IP' => $this->route->request->getIP(), 'created' => gmdate('Y-m-d H:i:s')])->insert();
			return 0;
		}

		$result = reset($results);
		if ($result->expire && $result->expire !== '0000-00-00 00:00:00' && $result->expire < gmdate('Y-m-d H:i:s')) {
			$this->values(['userID' => 0, 'expire' => '0000-00-00 00:00:00'])->update($result->token);
			return 0;
		}
		return $result->userID;
	}
}