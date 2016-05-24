<?php
use Loli\Uri;
use Loli\Route;
use Loli\Locale;



$descriptions = [



];


$this->layout('vue-template', function($parent) use($descriptions) {
?>
<messages></messages>
<form action="<?=new Uri(['Profile', 'postSetting'])?>" role="form" method="post" @submit="submit" autocomplete="off" class="form-horizontal">
    <?php
    $dataResults = [];
    foreach ($this->results as $result):
        $dataResults[$result['name']]['value'] = '';
    ?>
        <div class="form-group">
            <?=htmlInput($result + ['rewrite'=> $result['name'] === 'birthday'], [], '<div class="col-sm-2 control-label">{value}</div>', '<div class="col-sm-10">{value}</div>'); ?>
            <?php if (!empty($descriptions[$result['name']])): ?>
                <p><?=$descriptions[$result['name']]?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <div class="form-group">
        <div class="col-md-offset-2 col-sm-10">
            <button class="btn-primary btn" type="submit"><?=Locale::translate('Submit')?></button>
            <input type="hidden" name="_csrf" :value.sync="csrf" />
        </div>
    </div>
</form>
<?php
});




$this->layout('vue', function($parent) {
    $parent();


    $results = [];
    foreach ($this->results as $result){
        $results[$result['name']]['value'] = '';
    }
?>
<script type="application/vue">
return {
    data: function() {
        return {
            results: <?=json_encode($results)?>,
        };
    },
    methods: {
        submit: function(e) {
            e.preventDefault();
            var el = e.target;
            var _self = this;
            var submit = el.querySelector('.btn-primary[type="submit"]');
            var nodeValue = submit.firstChild.nodeValue;
            submit.firstChild.nodeValue = 'Loading';
            submit.disabled = true;
            var callback = function(response) {
                submit.firstChild.nodeValue = nodeValue;
                submit.disabled = false;
                if (response.status && response.status < 400) {
                    this.$broadcast('messages', {messages: [{message:'<?=Locale::translate('Saved')?>'}], success:true});
                    return;
                }
                this.$broadcast('messages', response);
            }
            this.$http({url: el.action, method: 'POST', timeout: 15000, data: Vue.formData(el, {json:true})}).then(callback, callback);
        }
    }
};
</script>

<?php
});




$this->load('profile/layout');
