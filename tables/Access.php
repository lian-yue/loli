<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-28 06:28:19
/*
/* ************************************************************************** */
namespace Table;
use Loli\Table, Loli\DB\Iterator;
class_exists('Loli\Table') || exit;
class Access extends Table{
	protected $tables = ['access'];

	protected $columns = [
		'token' => ['type' => 'string', 'length' => 16, 'primary' => 0],
		'userID' => ['type' => 'integer', 'length' => 4, 'unsigned' => true, 'key' => ['userID' => true]],
		'IP' => ['type' => 'string', 'length' => 40],
		'userAgent' => ['type'=> 'string', 'length' => 255],
		'created' => ['type' => 'timestamp'],
		'expired' => ['type' => 'datetime'],
	];

	protected $primary = ['token'];

	protected $primaryTTL = 1800;

	protected $userID;

	public function hasPermission() {
		return call_user_func_array([$this, 'getPermission'], func_get_args()) !== false;
	}

	public function getPermission(array $nodes, $keys = false) {
		return true;
	}



	public function userID() {
		if ($this->userID === NULL) {
			$this->userID = 0;
			$token = $this->route->request->getToken();
			if (!$result = $this->selectRow($token)) {
				$this->flush()->values(['token' => $token, 'IP' => $this->route->request->getClientAddr(), 'userAgent' => $this->route->request->getHeader('User-Agent')])->insert();
				return 0;
			}

			if ($result->expired && $result->expired !== '0000-00-00 00:00:00' && $result->expired < gmdate('Y-m-d H:i:s')) {
				$this->flush()->values(['userID' => 0, 'expired' => '0000-00-00 00:00:00'])->update($result->token);
				return 0;
			}
			$this->userID = $result->userID;
		}
		return $this->userID;
	}

	protected function success($name, Iterator $iterator = NULL) {
		$this->userID = NULL;
	}
}