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
use Loli\Table;
class_exists('Loli\Table') || exit;
class Token extends Table{
	protected $tables = ['rbac_tokens'];

	protected $columns = [
		'token' => ['type' => 'string', 'length' => 16, 'primary' => 0],
		'userID' => ['type' => 'integer', 'length' => 4, 'unsigned' => true],
		'IP' => ['type' => 'string', 'length' => 40],
		'userAgent' => ['type'=> 'string', 'length' => 255],
		'created' => ['type' => 'datetime'],
		'expires' => ['type' => 'datetime'],
	];

	protected $primary = ['token'];

	protected $primaryTTL = 1800;

	public function currentUserID() {
		$token = $this->route->request->getToken();
		if (!count($results = $this->select($token))) {
			$this->values(['token' => $token, 'IP' => $this->route->request->getIP(), 'userAgent' => $this->route->request->getHeader('User-Agent'), 'created' => gmdate('Y-m-d H:i:s')])->insert();
			return 0;
		}

		$result = reset($results);
		if ($result->expires && $result->expires !== '0000-00-00 00:00:00' && $result->expires < gmdate('Y-m-d H:i:s')) {
			$this->values(['userID' => 0, 'expires' => '0000-00-00 00:00:00'])->update($result->token);
			return 0;
		}
		return $result->userID;
	}
}