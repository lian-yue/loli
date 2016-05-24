<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-09-04 08:40:07
/*
/* ************************************************************************** */
namespace App\Controllers;
use Psr\Http\Message\UploadedFileInterface;

use Loli\Uri;
use Loli\View;
use Loli\Route;
use Loli\Message;
use Loli\Controller;
use Loli\Locale;
use Loli\Storage;
use Loli\MimeType;
use Loli\Paginator;
use Loli\Crypt\Code;
use Loli\Crypt\Int;


use App\User;
use App\Folder as AppFolder;
use App\Folder\File as AppFolderFile;


class Folder extends Controller{

    public $middleware = [
		'temporary' => [
			'Csrf' => [],
		],
    ];

    public $defaultMiddleware = [
		'Csrf' => [],
		'Username' => [],
        'Auth' => ['login' => true, 'node' => false],
	];



    protected $rules = [
		['name' => 'file', 'type' => 'file', 'required' => true, 'maxlength' => '50 MB'],
		['name' => 'name', 'type' => 'text', 'required' => true, 'maxlength' => 180],
		['name' => 'mode', 'type' => 'number', 'required' => true],
	];



    protected function folder($folder, $user)
    {
        $folder->cans = ['read' => $folder->can('read'), 'write' => $folder->can('write')];
        $folder->uri = new Uri(['Folder','get'], ['username' => $user->username, 'id_code' => $folder->id_code]);
        if ($folder->cans['read'] && $folder->mime !== 'text/directory' && $folder->value && ($file = AppFolderFile::selectOne($folder->value))) {
            $folder->file = $file;
        }
    }

    public function get(array $params)
    {
        if (!$user = User::selectOne($params['user_id'])) {
            throw new Message('user_exists', 404);
        }

        if (empty($params['id_code'])) {
            $data = [
                'title' => [],
                'id' => 0,
                'parent' => 0,
                'name' => '',
                'mime' => 'text/directory',
                'mode' => 0444,
                'size' => 0,
                'created' => $user->registered,
                'updated' => $user->registered,
                'id_code' => '',
                'cans' => ['read' => true, 'write' => true],
                'uri' => new Uri(['Folder','get'], ['username' => $user->username, 'id_code' => '']),
            ];
        } else {
            if (!($folder = AppFolder::selectIdCode($params['id_code'])) || $folder->user_id !== $params['user_id']) {
                throw new Message('folder_exists', 404);
            }
            $this->folder($folder);
            $data = $folder->jsonSerialize();
            $data['title'] = $folder->file;
        }


        // 权限监测
        if (!$data['cans']['read']) {
            throw new Message(['message' => 'permission_denied', 'code' => 'Permission'], 403);
        }

        $data['title'][] = Locale::translate(['{nickname} the folder', 'nickname' => $user->profiles['nickname']], ['title', 'default']);
        $data['title'][] = Locale::translate('title', ['title', 'default']);



        if ($data['mime'] === 'text/directory') {
            $paginator = new Paginator(
                $data['uri'],
                max(1, empty($params['page']) ? 1 : $params['page']),
                empty($params['limit']) || $params['limit'] < 1 || $params['limit'] > 200 ? 50: intval($params['limit'])
            );

            $order['sort'] = 'ASC';
            if (!empty($params['order']) && in_array($params['order'], ['name', 'mime', 'size', 'created', 'updated'], true)) {
                $order[$params['order']] = 'DESC';
            } else {
                $order['id'] = 'DESC';
            }
            $folders = AppFolder::query('user_id', $params['user_id'], '=')->
                query('parent', empty($folder) ? 0 : $folder->id,  '=')->
                query('deleted', null, '=')->
                offset($paginator->offset)->
                limit($paginator->limit)->
                order($order)->
                option('rows', true);

            $paginator->total = $folders->count();
            $data['results'] = $folders->select();
            $data['paginator'] =  $paginator;
            if (!$data['results']->count() && $paginator->current > 1) {
                throw new Message('paginator_exists', 404);
            }

            foreach ($data['results'] as $folder) {
                $this->folder($folder);
            }
        } else {
            $data['results'] = [];
            if (!empty($params['page'])) {
                throw new Message('paginator_exists', 404);
            }
        }
        return new View(['user/folder'], $data);
    }

    // 创建 or 写入 默认替换
    public function post(array $params) {
        return $this->put($params);
    }

    // 创建 or 写入 默认跳过
    public function put(array $params)
    {
        // 父级
        if (isset($params['parent'])) {
            if (empty($params['parent'])) {
                if (Route::user()->id !== $params['user_id']) {
                    throw new Message(['message' => 'permission_denied', 'code' => 'Permission'], 403);
                }
            } else {
                if (!($parent = AppFolder::selectIdCode($params['parent'])) || $parent->user_id !== $params['user_id'] || $parent->mime !== 'text/directory') {
                    throw new Message('folder_exists', 404);
                }
                $parent->throwCan('write');
            }
        }

        $now = new DateTime('now');

        if (empty($params['id_code'])) {
            $folder = new AppFolder([
                'salt' => mt_rand(0, 65535),
                'user_id' => Route::user()->id,
                'parent' => empty($parent) ? 0 : $parent->id,
                'name' => !empty($params['file']) && $params['file'] instanceof UploadedFileInterface ? mb_substr($params['file']->getClientFilename(), 0, 170) : '',
                'mime' => !empty($params['file']) && $params['file'] instanceof UploadedFileInterface ? $params['file']->getClientMediaType() : '',
                'mode' => empty($parent) ? 0222 : $parent->mode,
                'created' => $now,
            ]);
        } else {
            if (!($folder = AppFolder::selectIdCode($params['id_code'])) || $folder->user_id !== $params['user_id']) {
                throw new Message(['message' => 'validator_exists', 'title' => Locale::translate('Id Code'), 'name' => 'id_code'], 400);
            }
            $folder->throwCan('write');

            // 需要移动的
            if (isset($params['parent'])) {
                $oldParent = $folder->parent;
                $newParent = $folder->parent = empty($parent) ? 0 : $parent->id;

                // 递归检查
                $recursion = 0;
                while ($newParent && $recursion < 32) {
                    $recursion++;
                    if (!($newParentFolder = AppFolder::selectOne($newParent)) || $newParentFolder->deleted || $newParentFolder->user_id !== $params['user_id'] || $newParentFolder->parent === $folder->id) {
                        break;
                    }
                    $newParent = $newParentFolder->parent;
                }
                if ($newParent) {
                    throw new Message(['message' => 'validator', 'title' => Locale::translate('Parent'), 'name' => 'parent'], 400);
                }
            }
        }



        $params = AppFolder::validator($params, ['name' => $folder->name, 'mime' => $folder->mime, 'mode' => $folder->mode, 'file' => []], true);


        if ($params['mime'] === 'text/directory') {
            // 其他类型不能改成目录
            if ($params['mime'] !== $folder->mime && $folder->id) {
                throw new Message(['message' => 'validator', 'title' => Locale::translate('Mime'), 'name' => 'mime'], 400);
            }

            // 目录不能上传文件
            if (!empty($params['file'])) {
                throw new Message(['message' => 'validator', 'title' => Locale::translate('File'), 'name' => 'file'], 400);
            }
            $folder->mime =  'text/directory';
        } else {
            // 不是目录没有上传文件
            if (empty($params['file'])) {
                if (!$folder->value) {
                    throw new Message(['message' => 'validator_required', 'title' => Locale::translate('File'), 'name' => 'file'], 400);
                }
            } else {
                $uploadedFile = $params['file'];
                $stream = $uploadedFile->getStream();
                $md5Resource = hash_init('md5');
                $sha1Resource = hash_init('sha1');
                $crc32Resource = hash_init('crc32');

                $stream = $uploadedFile->getStream();
                $startStream = '';
                while (($read = $stream->read(1024 * 1024)) !== '') {
                    if (!strlen($startStream) < 4096 * 1024) {
                        $startStream .= $read;
                    }
                    hash_update($md5Resource, $read);
                    hash_update($sha1Resource, $read);
                    hash_update($crc32Resource, $read);
                }
                $md5 = hash_final($md5Resource);
                $sha1 = hash_final($sha1Resource);
                $crc32 = hash_final($crc32Resource);
                $size = $stream->getSize();




                if (!$file = AppFolderFile::selectOne($md5, $sha1, $crc32, $size)) {
                    $name = $uploadedFile->getClientFilename();
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->buffer($startStream);


                    if (!$extensions = MimeType::get($mime, true)) {
                        throw new Message(['message' => 'validator_accept', 'title' => Locale::translate('File'), 'name' => 'file'], 400);
                    }

                    $extension =  strtolower(pathinfo($folder->name, PATHINFO_EXTENSION));
                    if (!$extension || !in_array($extension, $extensions, true)) {
                        $extension = reset($extensions);
                    }
                    $path = $now->format('Y/m/d/H/i/') . Int::encode($folder->user_id) . '_' . Code::random(4) .'.' . $extension;


                    file_put_contents($tempnam = tempnam('', 'getID3'));

                    $getID3 = new getID3;
                    $info = $getID3->analyze($tempnam);
                    @unlink($tempnam);

                    $length = empty($info['playtime_seconds']) ? 0 : $info['playtime_seconds'];
                    $width = empty($info['video']['resolution_x']) ? 0 : $info['video']['resolution_x'];
                    $height = empty($info['video']['resolution_y']) ? 0 : $info['video']['resolution_y'];
                    $bitrate = empty($info['bitrate']) ? 0 : $info['bitrate'];

                    $meta = [];

                    $tiff = [];
                    $exif = [];
                    $id3 = [];
                    if (!empty($info['fileformat']) && !empty($info[$info['fileformat']]['exif'])) {
                        // http://www.awaresystems.be/imaging/tiff/tifftags/baseline.html
                        if (!empty($info[$info['fileformat']]['exif']['IFD0'])) {
                            foreach ($info[$info['fileformat']]['exif']['IFD0'] as $key => $value) {
                                if (in_array($key, [
                                    'ImageDescription',
                                    'Make',
                                    'Model',
                                    'Orientation',
                                    'XResolution',
                                    'YResolution',
                                    'ResolutionUnit',
                                    'Software',
                                    'Artist',
                                    'Copyright',
                                ], true)) {
                                    $tiff[$key] = $value;
                                }
                            }
                        }


                        // http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html
                        if (!empty($info[$info['fileformat']]['exif']['EXIF'])) {
                            foreach ($info[$info['fileformat']]['exif']['EXIF'] as $key => $value) {
                                if (in_array($key, [
                                    'ExposureTime',
                                    'FNumber',
                                    'ExposureProgram',
                                    'SpectralSensitivity',
                                    'ISOSpeedRatings',
                                    'DateTimeOriginal',
                                    'DateTimeDigitized',
                                    'ShutterSpeedValue',
                                    'ApertureValue',
                                    'BrightnessValue',
                                    'ExposureBiasValue',
                                    'MaxApertureValue',
                                    'SubjectDistance',
                                    'MeteringMode',
                                    'LightSource',
                                    'Flash',
                                    'FocalLength',
                                    'SubjectArea',
                                    'UserComment',
                                    'FlashpixVersion',
                                    'ColorSpace',
                                    'SubjectLocation',
                                    'WhiteBalance',
                                    'DigitalZoomRatio',
                                    'FocalLengthIn35mmFilm',
                                    'SceneCaptureType',
                                    'SubjectDistanceRange',
                                ], true)) {
                                    $exif[$key] = $value;
                                }
                            }
                        }
                    }



                    if (!empty($info['tags']['id3v2'])) {
                        foreach (['artist', 'album', 'title', 'publisher'] as $key) {
                            if (!empty($info['tags']['id3v2'][$key])) {
                                $id3[$key] = is_array($info['tags']['id3v2'][$key]) ? implode(',', $info['tags']['id3v2'][$key]) : $info['tags']['id3v2'][$key];
                            }
                        }
                    }

                    if (!empty($id3['title'])) {
                        $name = $id3['title'];
                        if (!empty($id3['artist'])) {
                            $name .= ' - ' . $id3['artist'];
                        }
                    }


                    if ($tiff) {
                        $meta['tiff'] = $tiff;
                    }
                    if ($exif) {
                        $meta['exif'] = $exif;
                    }
                    if ($id3) {
                        $meta['id3'] = $id3;
                    }

                    $file = new AppFolderFile([
                        'md5' => $md5,
                        'sha1' => $sha1,
                        'crc32' => $crc32,
                        'size' => $size,
                        'mime' => $finfo->buffer($mimeStream),
                        'path' => $path,
                        'name' => mb_substr($name, 0, 255),
                        'length' => $length,
                        'width' => $width,
                        'height' => $height,
                        'bitrate' => $bitrate,
                        'meta' => $meta,
                        'created' => $now,
                    ]);
                    $file->insert();
                }


                $folder->mime = $file->mime;
                $folder->size = $file->size;
                $folder->value = $file->id;
            }
        }

        $folder->mode = $params['mode'];
        $folder->name = $params['name'];
        $folder->updated = $now;


        // if ($params['mime']) && !in_array($params['mime'], [''])) {
        //     throw new Message(['message' => 'validator', 'title' => Locale::translate('Mime'), 'name' => 'mime'], 400);
        // }
        //
        //
        // $uploadedFile = $params['file'];
        // $stream = $uploadedFile->getStream();
        // $finfo = new \finfo(FILEINFO_MIME_TYPE);
        // $mime = $finfo->buffer($stream->read(1024 * 16));
        // $stream->rewind();
        // unset($stream);

    }

    // 更新
    public function patch(array $params) {
        $this->put($params);
    }

    // 删除
    public function delete(array $params) {


    }


	public function temporary(array $params) {
        $params = AppFolder::validator($params, ['file' => []], true);
        if (empty($params['file'])) {
            throw new Message(['message' => 'validator_required', 'title' => Locale::translate('File'), 'name' => 'file'], 400);
        }

        $uploadedFile = $params['file'];
        $stream = $uploadedFile->getStream();
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($stream->read(1024 * 16));
        $stream->rewind();
        unset($stream);

        if (!$extensions = MimeType::get($mime, true)) {
            throw new Message(['message' => 'validator_accept', 'title' => Locale::translate('File'), 'name' => 'file'], 400);
        }

        $filenameParsed = pathinfo($uploadedFile->getClientFilename()) + ['filename' => '', 'extension' => ''];
        if (!$filenameParsed['extension'] || !in_array(strtolower($filenameParsed['extension']), $extensions, true)) {
            if (!empty($filenameParsed['extension'])) {
                $filenameParsed['filename'] .= '.' . $filenameParsed['extension'];
            }
            $filenameParsed['extension'] = reset($extensions);
        }


        $storage = 'storage://temporary/'. gmdate('Y/m/d/H/i/')  . Code::random(10) . '.' . $filenameParsed['extension'];
        $result = [
            'mime' => $mime,
            'basename' => $filenameParsed['filename'] . '.' . $filenameParsed['extension'],
            'filename' => $filenameParsed['filename'],
            'extension' => $filenameParsed['extension'],
            'storage' => $storage,
            'uri' => Storage::uri($storage),
            'key' => Code::key($storage),
        ];


        if (!is_dir($dir = dirname($storage))) {
            mkdir($dir, 0755, true);
        }

        $uploadedFile->moveTo($storage);

        return new View(['storage/temporary'], ['results' => [$result]]);
	}
}
