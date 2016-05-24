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
namespace App\Folder;
use Loli\Model;
use Loli\Storage;

class File extends Model{
	protected static $table = 'folder_files';

	protected static $columns = [
		'id' => ['type' => 'integer', 'unsigned' => true, 'increment' => true, 'primary' => 0],
        'path' => ['type' => 'string', 'length' => 64, 'hidden' => true],
        'status' => ['type' => 'integer', 'length' => 1, 'key' => ['status' => 0]],
		'name' => ['type' => 'string', 'length' => 255],
		'mime' => ['type' => 'string', 'length' => 128, 'readonly' => true],
		'size' => ['type' => 'integer', 'length' => 4, 'unsigned' => true, 'readonly' => true, 'md5_sha1_chr32_size' => 3],
		'md5' => ['type' => 'string', 'length' => 32, 'readonly' => true, 'unique' => ['md5_sha1_chr32_size' => 0]],
		'sha1' => ['type' => 'string', 'length' => 40, 'readonly' => true, 'unique' => ['md5_sha1_chr32_size' => 1]],
		'crc32' => ['type' => 'integer', 'unsigned' => true, 'readonly' => true, 'unique' => ['md5_sha1_chr32_size' => 2]],
        'length' => ['type' => 'float', 'unsigned' => true],
        'width' => ['type' => 'integer', 'length' => 3, 'unsigned' => true],
        'height' => ['type' => 'integer', 'length' => 3, 'unsigned' => true],
        'bitrate' => ['type' => 'float', 'unsigned' => true],
        'meta' => ['type' => 'array'],
        'created' => ['type' => 'datetime', 'readonly' => true],
	];

	protected static $primaryCache = 1800;


    public function getAttributeUri() {
        return Storage::uri('storage://folders/' . $file->path);
    }
}
