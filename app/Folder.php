<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-26 14:37:30
/*
/* ************************************************************************** */
namespace App;
use Loli\Route;
use Loli\Model;
use Loli\Crypt\Code;
use Loli\Crypt\Int;
use App\Folder\File;

class Folder extends Model
{
	protected static $table = 'folders';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
        'salt' => ['type' => 'integer', 'length' => 2, 'unsigned'=> true, 'hidden' => true, 'readonly' => true],
		'user_id' => ['type' => 'integer', 'unsigned' => true, 'unique' => ['user_deleted_parent_name' => 0]],
		'parent' => ['type' => 'integer', 'unsigned' => true, 'unique' => ['user_deleted_parent_name' => 2]],
		'name' => ['type' => 'string', 'length' => 170, 'unique' => ['user_deleted_parent_name' => 3]],
		'mime' => ['type' => 'string', 'length' => 128],
		'mode' => ['type' => 'integer', 'length' => 2, 'unsigned' => true, 'default' => 0444],
		'size' => ['type' => 'integer', 'unsigned' => true],
		'value' => ['type' => 'integer', 'unsigned' => true],
		'sort' => ['type' => 'integer', 'length' => 1, 'readonly' => true],
        'created' => ['type' => 'datetime', 'readonly' => true],
        'updated' => ['type' => 'datetime'],
        'deleted' => ['type' => 'datetime', 'null' => true, 'unique' => ['user_deleted_parent_name' => 1], 'hidden' => true],
	];

    protected static $rules = [
        ['name' => 'name', 'type' => 'text', 'required' => true, 'maxlength' => 170],
        ['name' => 'mime', 'type' => 'text', 'required' => true, 'maxlength' => 128],
        ['name' => 'mode', 'type' => 'number', 'required' => true, 'min'=> 0, 'max' => 0777],
        ['name' => 'file', 'type' => 'file', 'required' => true, 'accept' => '
            text/directory,

            image/png,
            image/jpeg,
            image/webp,
            image/bmp,
            image/gif,
            image/vnd.adobe.photoshop,

            application/zip,
            application/x-rar-compressed,
            application/x-7z-compressed,

            application/pdf,

            application/vnd.ms-excel,
            application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,

            application/msword,
            application/vnd.openxmlformats-officedocument.wordprocessingml.document,
        ', 'maxlength' => '50 MB'],
    ];


    protected static $primaryCache = 1800;


    public function __set($name, $value)
    {
        switch ($name) {
            case 'mime':
                parent::__set('sort', $value === 'text/directory' ? -32 : 0);
                parent::__set($name, $value);
                break;
            default:
                parent::__set($name, $value);
        }
    }

    public function can($name, ...$args)
    {
        $user = Route::user();
		switch ($name) {
		    case 'read':
		        return  !$this->deleted && ($this->user_id === $user->id || $this->mode & ($user->id ? 0040 : 0004));
		        break;
		    case 'write':
                return !$this->deleted && ($this->user_id === $user->id || $this->mode & ($user->id ? 0020 : 0002));
                break;
		    default:
		        return false;
		        break;
		}
	}

    public function getAttributeIdCode() {
        return Int::encode($this->id, $this->salt);
    }

    public static function selectIdCode($idCode) {
        if (!$idCode || !is_string($idCode)) {
            return false;
        }
        if (!$id = Int::decode($idCode, $salt)) {
            return false;
        }
        if (!($folder = static::selectOne($id)) || $parent->deleted || $parent->salt !== $salt) {
            return false;
        }
        return $folder;
    }
}
