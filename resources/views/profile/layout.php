<?php
use Loli\Uri;
use Loli\Route;
use Loli\Locale;

$id = 'profile';

$navs = [
    'profile' => [
        'title' => 'User Profile',
        'name' => 'Profile',
        'uri' => ['Profile', 'index'],
        'tabs' => [
            'index' => [
                'name' => 'Home',
                'uri' => ['Profile', 'index'],
            ],

            'setting' => [
                'name' => 'Setting',
                'uri' => ['Profile', 'setting'],
            ],

            'avatar' => [
                'name' => 'Avatar',
                'uri' => ['Profile', 'avatar'],
            ],

            'password' => [
                'name' => 'Password',
                'uri' => ['Profile', 'password'],
            ],
            'log' => [
                'name' => 'Log',
                'uri' => ['Profile', 'log'],
            ],
        ],
    ],
];




$this->layout('vue', function($parent) use ($navs, $id) {
    ?>
    <template>
        <div id="c-profile">
            <h1><?=Locale::translate($navs[$id]['title'])?></h1>
            <ol class="breadcrumb">
                <li><a href="<?=($uri = new Uri(['Home', 'index']))?>" v-link="'<?=$uri->getPath();?>'"><i class="fa fa-home"></i> <?=Locale::translate('Home')?></a></li>
                <?php if (Route::controller()[1] == 'index'): ?>
                    <li><?=Locale::translate($navs[$id]['name'])?></li>
                <?php else: ?>
                    <li><a href="<?=($uri = new Uri($navs[$id]['uri']))?>" v-link="'<?=$uri->getPath();?>'"><?=Locale::translate($navs[$id]['name'])?></a></li>
                    <li><?=Locale::translate($navs[$id]['tabs'][Route::controller()[1]]['name'])?></li>
                <?php endif; ?>
            </ol>
            <section>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="box">
                            <nav id="navigation-profile">
                                <ul>
                                    <?php foreach ($navs as $key => $value): ?>
                                        <li class="<?=$key?>">
                                            <a href="<?=($uri = new Uri($value['uri']))?>" v-link="'<?=$uri->getPath()?>'">
                                                <?php if (!empty($value['icon'])): ?>
                                                    <i class="fa fa-<?=$value['icon']?>"></i>
                                                <?php endif; ?>
                                                <span><?=Locale::translate($value['name'])?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <?php foreach ($navs[$id]['tabs'] as $key => $value):
                                    $uri = new Uri($value['uri']);
                                    ?>
                                    <li class="<?=$value['uri'] == Route::controller() ? 'active' : ''; ?>">
                                        <a href="<?=$uri?>" v-link="'<?=$uri->getPath()?>'">
                                            <?php if (!empty($value['icon'])): ?>
                                                <i class="fa fa-<?=$value['icon']?>"></i>
                                            <?php endif; ?>
                                            <span><?=Locale::translate($value['name'])?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <?php $this->block('vue-template'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </template>
<?php $parent();  });
$this->load('layout');
