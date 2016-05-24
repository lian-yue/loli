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
    <file-upload action="/storage/?json=1" name="file" accept="image/png,image/x-png,image/webp,image/gif,image/jpeg,image/jpg,image/pjpeg,image/icon,image/x-icon,image/bmp,image/x-windows-bmp" v-ref:upload></file-upload>
    <table class="table table-bordered" v-if="$refs.upload && $refs.upload.files">
        <thead>
            <tr>
                <th>Index</th>
                <th>Name</th>
                <th>Size</th>
                <th>Progress</th>
                <th>Active</th>
                <th>Error</th>
                <th>Errno</th>
                <th>Success</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="file in $refs.upload.files">
                <td>{{$index}}</td>
                <td>{{file.name}}</td>
                <td>{{file.size}}</td>
                <td>{{file.progress}}</td>
                <td>{{file.active}}</td>
                <td>{{file.error}}</td>
                <td>{{file.errno}}</td>
                <td>{{file.success}}</td>
                <td @click="remove(file)">x</td>
            </tr>
        </tbody>
    </table>
    <div>
        active: {{$refs.upload.active}},
        uploaded: {{$refs.upload.uploaded}}
    </div>

    <button class="btn-primary btn" type="submit" @click="click"><?=Locale::translate('上传')?></button>
    <button class="btn-primary btn" type="submit" @click="click2"><?=Locale::translate('撤销')?></button>
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
?>
<script type="application/vue">
return {
    data: function() {
        return {
            results: [],
        };
    },
    compiled: function() {
        this.$refs.upload.request = {
            headers: {

            },
            data: {
                "_csrf_token": "xxxxxx",
            },
        };
    },
    methods: {
        remove: function(file) {
            this.$refs.upload.files.$remove(file);
        },
        click: function(e) {
            e.preventDefault();
            this.$refs.upload.active = true;
        },
        click2: function(e) {
            e.preventDefault();
            this.$refs.upload.active = false;
        },
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
