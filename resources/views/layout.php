<?php
use Loli\Route;
use Loli\Assets;
use Loli\Uri;
use Loli\Locale;
use Loli\Message;

function htmlAttributes($attributes, $ignores = []) {
    $ignores[] = 'title';
    $ignores[] = 'order';
    $ignores[] = 'option';
    $ignores[] = 'rewrite';
    if (empty($attributes['class']) || !trim($attributes['class'])) {
        unset($attributes['class']);
    } else {
        $attributes['class'] = trim($attributes['class']);
    }
    foreach ($attributes as $key => &$value) {
        if (!in_array($key, $ignores, true)) {
            $value = $key .'="' . htmlencode($value) . '"';
        } else {
            unset($attributes[$key]);
        }
    }
    return implode(' ', $attributes);
}


function htmlInput($input, $label = [], $replaceLabel = '{value}', $replaceInput = '{value}') {
    if (empty($input['placeholder']) && !empty($input['title']) && (empty($input['type']) || !in_array($input['type'], ['select', 'checkbox', 'radio'], true))) {
        $input['placeholder'] = Locale::translate(['Please enter your {value}', 'value' => strtolower($input['title'])]);
    }


    if (empty($input['type'])) {
        $input['type'] = 'text';
    }
    if (empty($input['id'])) {
        $input['id'] = $input['name'];
    }

    if (empty($input['class'])) {
        $input['class'] = '';
    }



    if (isset($input['value'])) {
        $input['v-model'] = 'results.'. $input['name'] .'.value';
        unset($input['value']);
    }

    switch($input['type']) {
        case 'checkbox':
            return '<label for="' . $input['id'] .'"><input ' . htmlAttributes($input) . ' />' . $input['title'] . '</label>';
        case 'radio':
            if (!empty($input['primary'])) {
                $input['type'] = 'primary';
            } else if (!empty($input['success'])) {
                $input['type'] = 'success';
            } else {
                unset($input['type']);
            }

            unset($input['v-model']);
            $input[':value.sync'] = 'results.'. $input['name'] .'.value';

            $inputString = '<radio-group '. htmlAttributes($input, ['name', 'value']) .'>';
            foreach($input['option'] as $key => $value) {
                $inputString .= '<radio-btn value="'.htmlencode($key).'">'. htmlencode($value). '</radio-btn>';
            }
            $inputString .= '</radio-group>';
            $inputString .= '<input name="'.$input['name'].'" v-model="results.'.$input['name'].'.value" type="hidden" />';
            break;
        case 'date':
            if (empty($input['rewrite'])) {
                $input['class'] .= ' form-control';
                $inputString = '<input '. htmlAttributes($input, ['value']) . ' />';
            } else {

                unset($input['v-model']);
                $input[':value.sync'] = 'results.'. $input['name'] .'.value';
                if (empty($input['required'])) {
                    $input[':show-reset-button'] = 'true';
                }
                $input['class'] .= ' datepicker-birthday';
                $input['format'] = empty($input['format']) ? 'yyyy-MM-dd' : $input['format'];
                $inputString = '<datepicker '. htmlAttributes($input, ['name', 'value']) .'></datepicker>';
                $inputString .= '<input name="'. $input['name'].'" v-model="results.'. $input['name'] .'.value" type="hidden" />';
            }
            break;
        case 'select':
            if (empty($input['rewrite'])) {
                $input['class'] .= ' form-control';
                $inputString = '<select '. htmlAttributes($input, ['value']) .'>';
                foreach ($input['option'] as $key => $option) {
                    $inputString .= '<option value="'. $key .'">'.htmlencode($option).'</option>';
                }
                $inputString .= '</select>';
            } else {
                unset($input['v-model']);
                $input[':value.sync'] = 'results.'. $input['name'] .'.value || \'\'';
                $inputString = '<v-select '. htmlAttributes($input, ['name', 'value']) .'>';
                foreach ($input['option'] as $key => $option) {
                    $inputString .= '<v-option value="'. $key .'">'.htmlencode($option).'</v-option>';
                }
                $inputString .= '</v-select>';
                $inputString .= '<input name="'.$input['name'].'" v-model="results.'.$input['name'].'.value" type="hidden" />';
            }
            break;
        default:
            $input['class'] .= ' form-control';
            $inputString = '<input '. htmlAttributes($input) . ' />';
    }

    //control-label
    $label['for'] = $input['id'];

    $label = str_replace('{value}', empty($input['title']) ? '' : '<label ' .htmlAttributes($label) . '>' . Locale::translate(['{value}:', 'value' => $input['title']]) . '</label>', $replaceLabel);
    $input = str_replace('{value}', $inputString, $replaceInput);
    return $label . $input;
}






?><!DOCTYPE html>
<html lang="<?=Locale::getLanguage()?>" class="<?=Locale::getLanguage()?>">
<head>
    <?php $this->block("head", function() { ?>
        <meta charset="utf-8" />
        <title><?php $this->block("title", function() { echo is_array($this['title']) ? implode(' - ', $this['title']) : $this['title']; }); ?></title>
        <?php $this->block("meta", function() { ?>
            <meta http-equiv="Cache-Control" content="no-siteapp" />
            <meta http-equiv="Cache-Control" content="no-transform" />
            <meta http-equiv="X-UA-Compatible" content="IE=Edge, chrome=1" />
            <meta name="renderer" content="webkit" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
            <meta name="msapplication-tap-highlight" content="no">
            <meta name="fragment" content="!" />
            <meta name="applicable-device" content="pc, mobile" />
            <meta name="csrf-token" content="<?=Route::token()?>" />
            <?php if ($this['keywords']): ?>
                <meta name="keywords" content="<?=htmlencode(preg_replace("/[\t\n\r\0\x0B<>\"'\\]+/", ',', is_array($this['keywords']) ? implode(',', $this['keywords']) :$this['keywords']))?>" />
            <?php endif; ?>
            <?php if ($this['description']): ?>
                <meta name="description" content="<?=htmlencode(mb_strlen($description = preg_replace("/[ \t\n\r\0\x0B<>\"'\\]+/", ' ', $this['description'])) > 200 ? mb_substr($description, 0, 200) . '...' : $description)?>" />
            <?php endif; ?>
        <?php }); ?>
            <!-- <script type="text/javascript" src="//loli.dev/assets/cosmoe/bundle.js"></script>
            <link rel="stylesheet" href="//loli.dev/assets/cosmoe/bundle.css" /> -->
            <script type="text/javascript" src="http://loli.dev:8090/webpack-dev-server.js"></script>
            <link rel="stylesheet" href="http://loli.dev:8090/bundle.css" />
            <script type="text/javascript" src="http://loli.dev:8090/bundle.js?x"></script>
    <?php }); ?>
</head>
<body>
    <div id="app"></div>
    <div id="vue">
        <?php $this->block("vue", function($parent) {
            $parent();
            $this->block("vue-data", function($parent) {
            ?>
            <script type="application/json"><?=json_encode(['csrf' => Route::token()->get()] + $this->toArray());?></script>
            <?php
            });
        }); ?>
    </div>
</body>
</html>
