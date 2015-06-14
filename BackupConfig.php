<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-06-13 14:16:38
/*	Updated: UTC 2015-06-14 04:50:30
/*
/* ************************************************************************** */






define('AUTH_KEY', '读取wp-config.php 的 AUTH_KEY 在wordpress 使用记得删除这行');




class BackupConfig {

	private $_config = array();

	/**
	 * __construct 自动执行
	 * @param mixed   $config  配置信息
	 * @param boolean $encrypt 是否已加密
	 */
	public function __construct($config, $encrypt = false) {
		if ($encrypt) {
			$this->_config = $this->_encrypt($config);
		} else {
			$this->_config = $config;
		}
	}

	/**
	 * [_encrypt 解码 数据
	 * @param  mixed $config 需要解密的数据
	 * @return mixed
	 */
	private function _encrypt($config) {
		// 不是字符串 or 字符串 小于 32  字节
		if(!is_string($config) || strlen($config) < 32) {
			return false;
		}

		// 数据不完整 or 数据被修改
		$md5 = substr($config, 0, 32);
		$base64 = substr($config, 32);
		if ($md5 !== md5(AUTH_KEY . $base64)) {
			return false;
		}

		// base 64 解密失败
		if (($serialize = base64_decode($base64)) === false) {
			return false;
		}
		// unserialize 解密失败
		if (($config = @unserialize($serialize)) === false) {
			return false;
		}
		return $config;
	}


	/**
	 * encode 输出加密信息
	 * @return string
	 */
	public function encode() {
		if ($this->_config === false) {
			return false;
		}
		$config = trim(base64_encode(serialize($this->_config)), '=');
		return md5(AUTH_KEY . $config) . $config;
	}
	/**
	 * decode 输出解密信息
	 * @return mixed  false  解密失败
	 */
	public function decode() {
		return $this->_config;
	}
}



$config = ['配置文件信息'];

// 加密数据
$BackupConfig = new BackupConfig($config);

echo $config2 = $BackupConfig->encode();


echo "\n\n<br/><br/><br/>\n\n";



// 解密数据
$config3 = new BackupConfig($config2, true);
print_r($config3->decode());
die;
preg_replace('/[^0-9a-z+\/-]/i', '', $config);
