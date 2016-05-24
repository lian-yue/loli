<template>
<div id="wrapper" :class="{'show-sidebar':sidebar,'hide-sidebar':!sidebar}">
    <header id="header" role="banner">
        <h1 id="logo">
            <a href="/" v-link="'/'" title="标题 - 描述" rel="home">
                标题
            </a>
        </h1>
        <div id="sidebar-toggle" class="toggle">
            <button @click="sidebar = !sidebar"><i class="fa fa-bars"></i><span>Sidebar</span></button>
        </div>


        <nav id="navigation-bar" class="navigation" role="navigation">
            <ul>
                <li is="bar-messages"></li>
                <li is="bar-notifications"></li>
                <li is="bar-user-menu"></li>
                <li class="open-app">
                    <a href="#" @click="openApp"><i class="fa fa-mobile fa-lg"></i><span>打开 App</span></a>
                </li>
                <li is="bar-settings"></li>

                <!-- <li class="dropdown messages">
                    <a href="#" @click="openApp" title="短消息"><i class="fa fa-envelope-o fa-lg"></i><span>短消息</span></a>
                </li> -->

                <!-- <li class="dropdown notifications"><a href="#" @click="openApp">
                    <i class="fa fa-bell-o fa-lg"></i><span>通知</span></a>
                </li> -->

                <!-- <li class="dropdown user-menu">
                    <a href="#" @click="openApp"><span>用户菜单</span></a>
                </li> -->


                <!-- <li class="dropdown settings" v-if="!user.id">
                    <a href="#" @click="openApp"><i class="fa fa-gears fa-lg"></i><span>选项</span></a>
                </li> -->
            </ul>
        </nav>
    </header>

    <aside id="sidebar" role="complementary" transition="sidebar">
        <sidebar-user-menu></sidebar-user-menu>
        <sidebar-menu></sidebar-menu>
    </aside>


    <div id="main" role="main">
        <div class="container-fluid">
            <router-view></router-view>
            <loading></loading>
            <error v-ref:error></error>
        </div>
    </div>
    <footer id="footer" role="contentinfo">
        <div class="info">
            1
        </div>
    </footer>

    <div class="sidebar-backdrop" transition="sidebar-backdrop" v-show="sidebar" @click="sidebar =false"></div>
</div>


</template>


<style>
.sidebar-backdrop-enter {
    animation:sidebar-backdrop-in .3s;
}
.sidebar-backdrop-leave {
    animation:sidebar-backdrop-out .3s;
}
@keyframes sidebar-backdrop-in {
    0% {
        opacity: 0;
    }
    100% {
        opacity: .3;
    }
}
@keyframes sidebar-backdrop-out {
    0% {
        opacity: .3;
    }
    100% {
        opacity: 0;
    }
}






</style>


<script>
import BarMessages from './barMessages.vue';
import BarNotifications from './barNotifications.vue';
import BarUserMenu from './barUserMenu.vue';
import BarSettings from './barSettings.vue';

import SidebarUserMenu from './sidebarUserMenu.vue';
import SidebarMenu from './sidebarMenu.vue';

import Loading from './loading.vue';
import Error from './error.vue';

export default {
    components: {
        BarMessages,
        BarNotifications,
        BarUserMenu,
        BarSettings,

        SidebarUserMenu,
        SidebarMenu,

        Loading,
        Error,
    },
    data() {
        return {
            sidebar: false,
            user: {profiles:{}},
        };
    },

    watch: {
        sidebar(value) {
            if (value) {
                var _self = this;
                setTimeout(function() {
                    _self.$el.querySelector('#sidebar').focus();
                }, 50);
            }
        },
    },

    methods: {
        openApp(e) {
            e.preventDefault();
            return;
        },
    },

    ready() {
        var main = this.$el.querySelector('#main');
        var footer = this.$el.querySelector('#footer');
        var resize = function(e) {
            main.style.minHeight =  (document.documentElement.clientHeight - footer.clientHeight).toString() + 'px';
        };
        window.addEventListener('resize', resize, true);
        resize();
    }
}
</script>
