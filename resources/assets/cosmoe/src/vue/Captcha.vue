<template>
    <div class="row captcha">
        <div class="col-sm-6">
            <label for="_captcha" v-if="title">{{title}}</label>
            <input type="text" name="_captcha" required autocomplete="off" :placeholder="placeholder" id="_captcha" class="form-control" />
            <input type="hidden" name="_captcha_id" :value="captchaId">
        </div>
        <div class="col-sm-6">
            <img :title.sync="refreshTitle" :src.sync="src" :alt.sync="refreshTitle" :style.sync="{width:width, height:height}" @click="refresh"/>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        src: {
            type: String,
        },
        captchaId: {
            type: String,
            default: '',
        },
        title: {
            type: String,
            default: 'Captcha: ',
        },
        refreshTitle: {
            type: String,
            default: 'Refresh captcha',
        },
        placeholder: {
            type: String,
            default: '',
        },
        width: {
            type: String,
            default: 'auto',
        },
        height: {
            type: String,
            default: 'auto',
        },
    },


    methods: {
        refresh(e) {
            if (this.$el) {
                this.$el.querySelector('[name="_captcha"]').value = '';
            }
            var src = this.src;
            var r = 'r=' +  Math.random().toString();
            this.src = this.src.replace(/([&?])r\=[^&?]+/, '$1' + r);
            if (this.src == src) {
                this.src += (this.src.indexOf('?') == -1 ? '?' : '&')  + '_captcha_id='+ this.captchaId +'&' + r;
            }
        },
    },
    events: {
        captcha() {
            this.refresh();
        },
    },
    created() {
        this.refresh();
    }
}
</script>


<style>
    .captcha img {
        margin-top:25px;
        cursor:pointer;
    }
</style>
