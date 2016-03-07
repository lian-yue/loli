<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2016-02-03 05:29:29
/*
/* ************************************************************************** */
namespace App\Controllers;
use Loli\View;
use Loli\Controller;

class Home extends Controller{
	public function index() {
		return new View('index');
	}
}
