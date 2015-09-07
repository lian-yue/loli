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
class Captcha extends Controller{
	public function index() {
		return $this->model('Captcha')->display();
	}

	public function __RBAC() {
		return true;
	}
}