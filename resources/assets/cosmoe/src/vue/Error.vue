<template>
    <div id="error" v-show="message">
        <p>{{message}}</p>
    </div>
</template>


<style>
    #error {
        z-index: 4095;
        background: #fff;
        position: absolute;
        top: 0;
        text-align: center;
        font-size: 18px;
        line-height: 3.4em;
        border-bottom: 1px solid #d9d9d9;
        left: 0;
        right: 0;
        width: 100%;
    }
    #error p {
        margin: 0;
    }
</style>

<script>
export default {
    data() {
        return {
            message: '',
        }
    },
    watch: {
        message(message) {
            if (message) {
                var _self = this;
                setTimeout(function() {
                    _self.message = '';
                }, 4000);
            }
        }
    },
    events: {
        error(message) {
            if (message === null) {
                this.message = '请求超时请重新尝试';
            } else if (message === false) {
                this.message = '请求失败请刷新重试';
            } else {
                this.message = message;
            }
        },
    },
}
</script>
