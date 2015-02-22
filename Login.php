<?php
namespace Controller\User;
use Loli\HMVC\Controller, Loli\Request, Loli\Response;

class Login extends Controller{

	// 默认
	public function __construct(Request &$request, Response &$response) {
		echo 3;
	}
}