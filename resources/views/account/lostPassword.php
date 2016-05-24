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
                    <h1 v-show="views[0] == 'account/lostPassword'"><?=Locale::translate('Account lost password', ['title', 'default'])?></h1>
                    <h1 v-show="views[0] == 'account/selectSendPassword'"><?=Locale::translate('Account send password', ['title', 'default'])?></h1>
                    <h1 v-show="views[0] == 'account/resetPassword'"><?=Locale::translate('Account reset password', ['title', 'default'])?></h1>
                </div>
                <div class="box-content">
                    <messages></messages>

                    <!-- 选择帐号 -->
                    <form action="<?=new Uri(['Account', 'selectSendPassword'])?>" role="form" method="post"  @submit="submit" v-if="views[0] == 'account/lostPassword'">
                        <div class="form-group">
                            <?=htmlInput($this->results['account']); ?>
                        </div>
                        <div class="form-group" v-if="results._captcha">
                            <captcha title="<?=Locale::translate(['{value}:', 'value' => Locale::translate('Captcha')]) ?>" src="<?=new Uri(['Captcha', 'index']) ?>" refresh-title="<?=Locale::translate('Refresh captcha')?>" height="44px"></captcha>
                        </div>
                        <div class="form-group">
                            <button class="btn-primary btn-lg btn-block" type="submit"><?=Locale::translate('Next step &raquo;')?></button>
                            <input type="hidden" name="_csrf" :value.sync="csrf" />
                        </div>
                    </form>

                    <!-- 发送验证码页面 -->
                    <form action="<?=new Uri(['Account', 'resetPassword'])?>" role="form" method="post"  @submit="submit" v-if="views[0] == 'account/selectSendPassword'">
                        <div class="form-group">
                            <label for="profile_id"><?=Locale::translate(['{value}:', 'value' => Locale::translate('Account')])?></label>
                            <select name="profile_id" id="profile_id" class="form-control" v-if="results &amp;&amp; results.length">
                                <option value=""><?=Locale::translate('Please choose')?></option>
                                <option :value.sync="result.id" v-for="result in results">{{result.value}} ({{result.type}})</option>
                            </select>
                            <div class="select-empty"  v-else>
                                <?=Locale::translate('Not choose')?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="code"><?=Locale::translate(['{value}:', 'value' => Locale::translate('Verification Code')])?></label>
                                    <?=htmlInput(['type' => 'text', 'name' => 'code', 'placeholder' => Locale::translate(['Please enter your {value}', 'value' => Locale::translate('verification code')])])?>
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn-default btn btn-block" type="submit", name="send_code_button", disable-value="<?=Locale::translate('You can resend after {value} seconds')?>"  formaction="<?=new Uri(['Account', 'sendPassword'])?>" @click="sendPassword"><?=Locale::translate('Send verification code')?></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn-primary btn-lg btn-block" type="submit"><?=Locale::translate('Next step &raquo;')?></button>
                            <input type="hidden" name="_csrf" :value.sync="csrf" />
                            <input type="hidden" name="id" :value.sync="results[0].user_id" v-if="results &amp;&amp; results.length"/>
                        </div>
                    </form>


                    <!-- 修改密码 -->
                    <form action="<?=new Uri(['Account', 'postResetPassword'])?>" role="form" method="post"  @submit="submit" v-if="views[0] == 'account/resetPassword'">
                        <div class="form-group">
                            <label for="new_password"><?=Locale::translate(['{value}:', 'value' => Locale::translate('New password')])?></label>
                            <input name="new_password" type="password" required="1" maxlength="128" placeholder="<?=Locale::translate(['Please enter your {value}', 'value' => Locale::translate('new password')])?>" id="new_password" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="new_password_again"><?=Locale::translate(['{value}:', 'value' => Locale::translate('New password again')])?></label>
                            <input name="new_password_again" type="password" required="1" maxlength="128" placeholder="<?=Locale::translate(['Please enter your {value}', 'value' => Locale::translate('new password again')])?>" id="new_password_again" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <button class="btn-primary btn-lg btn-block" type="submit"><?=Locale::translate('Submit')?></button>
                            <input type="hidden" name="_csrf" :value.sync="csrf" />
                            <input type="hidden" name="code" :value.sync="results.code.value"/>
                            <input type="hidden" name="id" :value.sync="results.id.value"/>
                        </div>
                    </form>

                    <menu id="menu">
                        <ul>
                            <li><a href="<?=($uri = new Uri(['Account', 'login'])); ?>" v-link="{path:'<?=$uri->getPath()?>', query:$route.query}"><?=Locale::translate('Log in account')?></a></li>
                            <li><a href="<?=($uri = new Uri(['Account', 'create'])); ?>" v-link="{path:'<?=$uri->getPath()?>', query:$route.query}"><?=Locale::translate('Create an Account')?></a></li>
                        </ul>
                    </menu>
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
                },
                views: ['account/lostPassword'],
            };
        },
        methods: {
            sendPassword: function(e) {
                e.preventDefault();

                var el = e.target;

                var nodeValue = el.firstChild.nodeValue;
                var disableValue = el.getAttribute('disable-value');
                var seconds = 60;

                el.disabled = true;
                el.firstChild.nodeValue = disableValue.replace('{value}', seconds);
                setTimeout(function() {
                    seconds--;
                    if (seconds <= 0) {
                        el.disabled = false;
                        el.firstChild.nodeValue = nodeValue;
                    } else {
                        setTimeout(arguments.callee, 1000);
                        el.firstChild.nodeValue = disableValue.replace('{value}', seconds);
                    }
                }, 1000);

                var callback = function(response) {
                    this.$broadcast('messages', response);
                }
                this.$http({url: el.formAction, method: 'POST', timeout: 15000, data: {profile_id: this.$el.querySelector('[name="profile_id"]').value, id: this.results.length ? this.results[0].user_id : '', _csrf: this.csrf, json:true}}).then(callback, callback);
            },
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
                        response.data.csrf = response.data.csrf || this.csrf;
                        if (this.views[0] == 'account/resetPassword') {
                            router.go(response.data.redirect_uri)
                            return;
                        }
                        this.$broadcast('messages', {messages:[]});
                        this.$data = response.data;
                        return;
                    }
                    if (response && response.data && typeof response.data == 'object' && response.data.messages) {
                        for (var i = 0; i < response.data.messages.length; i++) {
                            var message = response.data.messages[i];
                            if (!message.args || !message.args.name) {
                                continue;
                            }

                            if (message.args.name == '_captcha') {
                                this.$broadcast('captcha');
                            }
                        }
                    }
                    this.$broadcast('messages', response);
                }
                this.$http({url: el.action, method: 'POST', timeout: 15000, data: Vue.formData(el, {json:true})}).then(callback, callback);
            }
        },
        route: {
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

    <style>
        [name="send_code_button"] {
            margin-top: 25px;
        }
    </style>
<?php $parent(); });
$this->load('layout');
