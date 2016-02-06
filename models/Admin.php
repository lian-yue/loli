<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-11-26 13:27:09
/*
/* ************************************************************************** */
namespace Model;
class_exists('AdminModel') || exit;
class Admin extends AdminModel{

	protected $tokens = ['login', 'renew', 'logout'];

	public function index() {
		return $this->view('admin/index');
	}

	public function loginGet() {
		return $this->view('admin/login');
	}

	public function login() {
		return $this->view('admin/login');
	}


	public function nav() {

	}

	public function renew() {
		$this->route->table['Admin.User']->value('expired', gmdate('Y-m-d H:i:s', time() + 300))->update($this->route->table['Access']->userID());
		throw new Message(200);
	}

	public function logout() {
		$this->route->table['Admin.User']->delete($this->route->table['Access']->userID());
		throw new Message(200, Route::URL(['Admin', 'loginGet']));
	}
}