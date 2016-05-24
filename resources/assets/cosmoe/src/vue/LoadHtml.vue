<template>
    {{{data}}}
</template>

<script>
export default {
    props: ['url'],
    data() {
        return {data:''};
    },
    watch: {
        url(value, oldValue) {
            if (value != oldValue) {
                this.load();
            }
        }
    },

    methods: {
        load() {
            var _self = this;
            var url = _self.url;
            if (!url) {
                _self.data = '';
            }
            url += url.indexOf('?') == -1 ? '?' : '&';
            url += 'pjax=true';
            this.$http({url: url, headers: {'X-Pjax': true}, timeout: 15000}).then(function(response) {
                _self.data = response.data;
            }, function() {

            });
        }
    },

    created() {
        this.load();
    },
}
</script>
