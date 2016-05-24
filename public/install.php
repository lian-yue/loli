<?php
namespace App;
require dirname(__DIR__) . '/vendor/autoload.php';


//
// echo Auth::option('exists', true)->drop();
// echo Auth\Node::option('exists', true)->drop();
// echo Auth\Role::option('exists', true)->drop();
// echo Auth\Permission::option('exists', true)->drop();
// echo Auth\Pelationship::option('exists', true)->drop();
//
//
// echo User::option('exists', true)->drop();
// echo User\Log::option('exists', true)->drop();
// echo User\Code::option('exists', true)->drop();
// echo User\Profile::option('exists', true)->drop();
//
//
// echo Folder::option('exists', true)->drop();
// echo Folder\File::option('exists', true)->drop();
//







echo Auth::option('exists', true)->create();
echo Auth\Node::option('exists', true)->create();
echo Auth\Role::option('exists', true)->create();
echo Auth\Permission::option('exists', true)->create();
echo Auth\Pelationship::option('exists', true)->create();


echo User::option('exists', true)->create();
echo User\Log::option('exists', true)->create();
echo User\Code::option('exists', true)->create();
echo User\Profile::option('exists', true)->create();


echo Folder::option('exists', true)->create();
echo Folder\File::option('exists', true)->create();
