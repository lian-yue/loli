<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-25 05:46:30
/*
/* ************************************************************************** */
namespace Controller\User;
use Loli\Controller;
class Login extends Controller{

	public function index($params) {
		// $this->token();
		$user = $this('User')->values(['name' => mt_rand(), 'price' => mt_rand()])->document(['name' => mt_rand(), 'price' => mt_rand()])->select();
		return $this->view('user/login', ['qq' => $user], true);
	}
}