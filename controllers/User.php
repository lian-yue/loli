<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-04 14:19:10
/*
/* ************************************************************************** */
namespace Controller;
use Loli\Controller, Loli\Message;
class User extends Controller{

	public function logIn() {
		if ($this->model('RBAC/Token')->currentUserID()) {
			return new Message(2010, Message::NOTICE);
		}
		return $this->view('user/login', $data);
	}

	public function signUp() {
		if ($this->model('RBAC/Token')->currentUserID()) {
			return new Message(2010, Message::NOTICE);
		}
		return $this->view('user/login', $data);
	}

	public function lostPassword() {
		if ($this->model('RBAC/Token')->currentUserID()) {
			return new Message(2010, Message::NOTICE);
		}
		return $this->view('user/login', $data);
	}

	public function lostPassword() {
		if ($this->model('RBAC/Token')->currentUserID()) {
			return new Message(2010, Message::NOTICE);
		}
		return $this->view('user/login', $data);
	}


	public function __RBAC() {
		return true;
	}
}