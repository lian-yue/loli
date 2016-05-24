<template>
    <div class="messages" v-show="messages && messages.length">
        <alert :type="success ? 'success' : 'danger'" dismissable v-ref:alert><p v-for="message in messages">{{message.message}}</p></alert>
    </div>
</template>
<script>
export default {
    props: {
        scroll: {
            type: Number,
            default: 100,
        },
    },

    data() {
        return {
            success: false,
            messages: [],
            response: '',
        };
    },

    events: {
        messages(data) {
            if (typeof data.status != "undefined" && typeof data.headers != "undefined"  && typeof data.request != "undefined")  {
                this.response = data;
            } else {
                if (typeof data.success == 'undefined') {
                    data.success =  data.messages == 0;
                }
                this.success = data.success;
                this.messages = data.messages;
            }
        },
    },

    watch: {
        messages(messages) {
            if (this.$refs.alert) {
                if (!this.$refs.alert.show) {
                    this.$refs.alert.show = true;
                }
            }
            if (messages.length && this.scroll > 0) {
                var top = 0;
                var el = this.$el;
                while (el) {
                    top = top + el.offsetTop;
                    el = el.offsetParent;
                }

                if (window.scrollY > top || (window.scrollY + window.innerHeight - 40) < top) {
                    var bottom = window.scrollY <= top;
                    var Y = window.scrollY;
                    var duration = this.scroll;
                    var timems = Date.now() + duration;

                    var offset = top - 20;
                    if (bottom) {
                        offset -= window.innerHeight - this.$el.offsetHeight - 40;
                    }


                    var scrollX = window.scrollX;
                    var scrollY = window.scrollY;
                    var clear = setInterval(function() {

                        // 被改动过了
                        if (scrollX != window.scrollX || scrollY != window.scrollY) {
                            clearInterval(clear);
                            return;
                        }

                        // 动画结束
                        var now = Date.now();
                        if (timems < now) {
                            clearInterval(clear);
                            window.scrollTo(scrollX, offset);
                            return;
                        }

                        var progress = (timems - now) / duration;

                        scrollY = parseInt(offset + (Y - offset) * progress);

                        // 移动
                        window.scrollTo(scrollX, scrollY);
                    }, 16);
                }
            }
        },

        response(response) {
            if (!response) {
                return;
            }
            if (!response.status) {
                this.success = false;
                this.messages = [{message:'Timeout', code:'timeout'}];
                return;
            }

            var data = response.data;
            if (!data) {
                this.success = false;
                this.messages = [{message:'Response empty', code:'error'}];
                return;
            }

            if (typeof data == 'string') {
                var element = document.createElement('div');
                element.innerHTML = data;
                var selectorAll = element.querySelectorAll('#messages p');
                var messages = [];
                for (var i = 0; i < selectorAll.length; i++) {
                    messages.push({message: selectorAll[i].innerText});
                }
                this.success = response.status < 400;
                this.messages = messages;
                return;
            }



            this.success = data.success;
            this.messages = data.messages;
        },
    },
}
</script>
