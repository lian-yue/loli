<?php
use Loli\Uri;
use Loli\Locale;
?>
<menu id="oauth2">
    <h2><?=Locale::translate('You can use the following account login')?></h2>
    <ul>
        <?php foreach ($this->results as $key => $result): ?>
            <li>
                <a href="<?=$result['uri']?>" class="oauth2-<?=$key?>" title="<?=Locale::translate(['Log in using {name} account', 'name' => $result['name']])?>"><i class="fa fa-<?=$key == 'baidu' ? 'paw' : $key?>"></i><?=Locale::translate(['Log in using {name} account', 'name' => $result['name']])?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</menu>
