<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-03 07:14:12
/*
/* ************************************************************************** */
namespace Controller;
use Loli\Controller, Loli\DB\Cursor;
class Install extends Controller{
	public function index($params) {
		$vendorDir = dirname(__DIR__) . '/models';
		$this->callInstall($vendorDir);
	}

	protected function callInstall($dir, $namespace = 'Model') {
		$opendir = opendir($dir);
		while ($name = readdir($opendir)) {
			if (in_array($name, ['.', '..'], true)) {
				continue;
			}
			if (is_dir($path = $dir . '/' . $name)) {
				$this->callInstall($path, $namespace . '\\' . $name);
				continue;
			}
			if (substr($name, -4, 4) !== '.php') {
				continue;
			}
			$class = $namespace . '\\' . substr($name, 0, -4);
			$model = new $class($this->route);
			if ($model instanceof Cursor) {
				// $model->option('exists', true)->drop();
				$model->option('exists', true)->create();
			}
		}
		closedir($opendir);
		return true;
	}

	public function __RBAC() {
		return true;
	}
}