<?php
namespace App\Services;
use Loli\Service;
class Mail extends Service{
	protected static $configure = 'mail';

	protected static $reuse = true;

	protected static function register(array $config, $group = null) {
		$mail = new \PHPMailer(true);
		$array = [
			'host' > 'Host',
			'hostname' > 'Host',
			'user' => 'Username',
			'username' => 'Username',
			'pass' => 'Password',
			'password' => 'Password',
			'secure' => 'SMTPSecure',
			'port' => 'Port',
		];
		if (!empty($config['host']) || !empty($config['hostname'])) {
			$mail->isSMTP();
		}
		foreach ($config as $key => $value) {
			if ($value && !empty($array[$key])) {
				$mail->$array[$key] = $value;
			}
		}
		if ($mail->Username || $mail->Password) {
			$mail->SMTPAuth = true;
		}

		if (empty($config['from'])) {
			if (empty($config['address'])) {
				$address = $mail->Username;
			} else {
				$address = $config['address'];
			}
		} else {
			$address = $config['from'];
		}
		$address && $mail->setFrom($address, empty($config['name']) ? $mail->Username : $config['name']);
		return $mail;
	}
}
