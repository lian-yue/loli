<?php
use Loli\Uri;
use Loli\Route;
use Loli\Locale;

$this->layout('meta', function($parent) { ?>
    <?php $parent()?>
    <?php if (Route::request()->getQueryParams()): ?>
        <meta name="robots" content="none" />
    <?php endif; ?>
    <?php
});

$this->layout('vue', function($parent) {
    ?>

    <template>
        <section id="c-account">
            <div class="box">
                <div class="box-title">
                    <h1><?=Locale::translate('Account login', ['title', 'default'])?></h1>
                </div>
                <div class="box-content">
                    <messages></messages>
                    <form action="<?=new Uri(['Account', 'postLogin'])?>" role="form" method="post"  @submit="submit">
                        <div class="form-group">
                            <?=htmlInput($this->results['account']); ?>
                        </div>
                        <div class="form-group">
                            <?=htmlInput($this->results['password']); ?>
                        </div>
                        <div class="form-group" v-if="results._captcha">
                            <captcha title="<?=Locale::translate(['{value}:', 'value' => Locale::translate('Captcha')]) ?>" src="<?=new Uri(['Captcha', 'index']) ?>" refresh-title="<?=Locale::translate('Refresh captcha')?>" height="44px"></captcha>
                        </div>
                        <div class="checkbox">
                            <?=htmlInput($this->results['remember']); ?>
                        </div>
                        <div class="form-group">
                            <button class="btn-primary btn-lg btn-block" type="submit"><?=Locale::translate('Log in')?></button>
                            <input type="hidden" name="_csrf" :value.sync="csrf" />
                        </div>
                    </form>
                    <menu id="menu">
                        <ul>
                            <li><a href="<?=($uri = new Uri(['Account', 'lostPassword'])); ?>" v-link="{path:'<?=$uri->getPath()?>', query:$route.query}"><?=Locale::translate('Forgot your password?')?></a></li>
                            <li><a href="<?=($uri = new Uri(['Account', 'create'])); ?>" v-link="{path:'<?=$uri->getPath()?>', query:$route.query}"><?=Locale::translate('Create an Account')?></a></li>
                        </ul>
                    </menu>
                    <load-html url="<?=new Uri(['Account', 'oauth2Types'], ['create' => true])?>"></load-html>
                </div>
            </div>
        </section>
    </template>

    <script type="application/vue">
    return {
        data: function() {
            return {
                redirect_uri: '/',
                results: {
                    account: {value: ''},
                    password: {value: ''},
                    remember: {value: 86400 * 7},
                },
            };
        },
        methods: {
            submit: function(e) {
                e.preventDefault();
                var el = e.target;
                var submit = el.querySelector('.btn-primary[type="submit"]');
                var nodeValue = submit.firstChild.nodeValue;
                submit.firstChild.nodeValue = 'Loading';
                submit.disabled = true;
                var callback = function(response) {
                    submit.firstChild.nodeValue = nodeValue;
                    submit.disabled = false;
                    if (response.status && response.status < 400) {
                        router.go(this.redirect_uri);
                        return;
                    }
                    if (response && response.data && typeof response.data == 'object' && response.data.messages) {
                        for (var i = 0; i < response.data.messages.length; i++) {
                            var message = response.data.messages[i];
                            if (!message.args || !message.args.name) {
                                continue;
                            }
                            if (message.args.name == '_captcha' || message.args.name == 'password') {
                                this.$broadcast('captcha');
                                if (message.args.name == 'password') {
                                    this.$el.querySelector('[name="password"]').value = '';
                                }
                            }
                        }
                    }
                    this.$broadcast('messages', response);
                    this.$routeData();
                }
                this.$http({url: el.action, method: 'POST', timeout: 15000, data: Vue.formData(el, {json:true})}).then(callback, callback);
            }
        },
        route: {
            data: function() {
                if (this.$route.query._message) {
                    this.$broadcast('messages', {success:false, messages:[{message:this.$route.query._message}]});
                    this.$route.query._message = '';
                }
            },
            activate: function(transition) {
                var el = document.querySelector("head meta[name='robots']");
                if (window.location.search) {
                    if (!el) {
                        el = document.createElement('meta');
                        el.name = 'robots';
                        el.content = 'none';
                        document.querySelector('head').appendChild(el);
                    }
                } else if (el) {
                    document.querySelector('head').appendChild(el);
                }
                transition.next();
            },

            deactivate: function(transition) {
                var el = document.querySelector("head meta[name='robots']");
                if (el) {
                    document.querySelector('head').appendChild(el);
                }
                transition.next();
            },
        }
    };
    </script>
<?php $parent(); });
$this->load('layout');
