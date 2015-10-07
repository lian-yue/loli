<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-23 10:27:12
/*
/* ************************************************************************** */
return [
	'GET/install/?' => 'Install.view',
	'POST/install/?' => 'Install.post',

	'/captcha/?' => 'Captcha.view',
	'/captcha/test/?' => 'Captcha.test',



	// 用户
	'GET/user/(\w+)/?' => 'User.$1View',
	'POST/user/(\w+)/?' => 'User.$1',

	'/user/emailExists/?' => 'User.emailExists',
	'/user/usernameExists/?' => 'User.usernameExists',
];