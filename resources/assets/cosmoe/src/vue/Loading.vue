<template>
    <div id="loading" v-show="show">
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: false,
        }
    },
    events: {
        loading(show) {
            this.show = show;
        },
    },
}
</script>


<style>
    #loading {
        z-index: 4097;
        background: #fff;
        opacity: .3;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
    }
</style>
