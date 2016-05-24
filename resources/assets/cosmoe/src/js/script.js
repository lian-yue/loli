"strict mode";
import vuex from 'vuex'
import vueStrap from 'vue-strap'
import VueRouter from 'vue-router'

import FileUpload from 'vue-upload-component'
import Messages from '../vue/Messages.vue'
import Captcha from '../vue/Captcha.vue'
import LoadHtml from '../vue/LoadHtml.vue'

import Layout from '../vue/Layout.vue'









/*!
 * Version: 0.1.0
 * Author : LianYue
 * URL    : http://www.otamoe.com
 */


(function(Vue, Window, Document) {
    Vue.config.debug = true;
    Vue.config.async = false;
    Vue.config.devtools = true




    // starp 组件
    for (var id in vueStrap) {
        if (vueStrap.hasOwnProperty(id)) {
            Vue.component(id == 'option' || id == 'aside' || id == 'select' ? 'v-' + id : (id == 'tabset' ? 'tabs' : id), vueStrap[id]);
        }
    }


    var components = {
        FileUpload,
        Messages,
        Captcha,
        LoadHtml,
    };

    for (var id in components) {
        if (components.hasOwnProperty(id)) {
            Vue.component(id, components[id]);
        }
    }

    // 首字母大写
    Vue.filter('ucfirst', (value) => {
        if (value) {
            value = value.replace(/_/g, ' ');
            value = value.substr(0, 1).toUpperCase() + value.substr(1);
        }
        return value;
    });


    Vue.use({
        install: function(Vue, options) {
            // 表单数据
            Vue.formData = function(form, data) {
                data = data || {};
                for (var i = 0; i < form.length; i++) {
                    var input = form[i];
                    if (input.name && (input.type != 'checkbox' || input.checked)) {
                        data[input.name] = input.value;
                    }
                }
                return data;
            };
        },
    });




    // 路由配置
    Window.router = new VueRouter({history: true, transitionOnLoad: true, linkActiveClass: 'active'});
    if (router.mode == 'html5') {
        if (Window.location.pathname == '/' && Window.location.hash.substr(0, 3) == '#!/') {
            var pathname = Window.location.hash.substr(2);
            Window.location.hash = '';
            Window.location.pathname = pathname;
            return;
        }
    } else {
        if (Window.location.pathname != '/' && Window.location.hash.substr(0, 3) != '#!/') {
            Window.location = '/#!' + Window.location.pathname + window.location.search
            return;
        }
    }


    Vue.use({
        install: function(Vue, options) {
            var components = {};

            var parseComponent = function(contents) {
                try {
                    return (new Function('var module = {exports: {}}; var exports = module.exports;   var result = (function(){ '+ contents +'; })();  return typeof result == "object" ? result : module.exports;'))();
                } catch (e) {
                    console.error(e);
                    return false;
                }
            }

            var parseJavascript = function(contents) {
                try {
                    return (new Function(contents + ';')).call(Window);
                } catch (e) {
                    console.error(e);
                    return false;
                }
            }

            var parseComponentData = function(component, callback) {
                var keywordElement = document.querySelector("head meta[name='keyword']");
                var descriptionElement = document.querySelector("head meta[name='description']");
                if (!keywordElement) {
                    keywordElement = document.createElement('meta');
                    keywordElement.name = 'keyword';
                }
                if (!descriptionElement) {
                    descriptionElement = document.createElement('meta');
                    descriptionElement.name = 'description';
                }

                component.route = component.route || {};
                var data = function(transition) {
                    callback.call(this, function(data) {
                        transition && transition.next();
                        if (!data) {
                            this.$router.app.$broadcast('error', data === null ? 'Timeout' : 'Content');
                            return;
                        }

                        // 错误消息
                        if (data.messages) {
                            this.$broadcast('messages', data);
                            return;
                        }

                        if (data.title) {
                            Document.title = data.title.join(' - ');
                        }


                        if (data.keyword) {
                            if (typeof data.keyword == 'string') {
                                keywordElement.content = data.keyword;
                            } else {
                                keywordElement.content = data.keyword.join(',');
                            }
                            document.querySelector('head').appendChild(keywordElement);
                        } else if (keywordElement.parentNode) {
                            keywordElement.parentNode.removeChild(keywordElement);
                        }

                        if (data.description) {
                            descriptionElement.content = data.description;
                            document.querySelector('head').appendChild(descriptionElement);
                        } else if (descriptionElement.parentNode) {
                            descriptionElement.parentNode.removeChild(descriptionElement);
                        }

                        if (!(data.results instanceof Array)) {
                            for (var key in data.results) {
                                if (!this.results || !this.results[key] || typeof this.results[key] != 'object' || typeof this.results[key].value == 'undefined') {
                                    continue;
                                }

                                if (!data.results[key] || typeof this.results[key] != 'object' || typeof data.results[key].value != 'undefined') {
                                    continue;
                                }
                                data.results[key].value = this.results[key].value;
                            }
                        }

                        data.csrf = data.csrf || this.csrf;

                        if (this.redirect_uri) {
                            data.redirect_uri =  this.$route.query.redirect_uri || '/';
                        }
                        this.$data = data;
                    });
                }

                if (!component.route.data) {
                    component.route.data = data;
                } else if (component.route.data instanceof Array) {
                    component.route.data = [data].concat(component.route.data);
                } else {
                    component.route.data = [data, component.route.data];
                }
                component.routeData = data;
                return component;
            }


            var parseComponentStyle = function(component, value) {
                component.route = component.route || {};
                var style = Document.createElement('style');
                style.type = 'text/css';
                style.textContent = value;
                var activate = function(transition) {
                    Document.querySelector('head').appendChild(style);
                    transition.next();
                };

                if (!component.route.activate) {
                    component.route.activate = activate;
                } else if (component.route.activate instanceof Array) {
                    component.route.activate.push(activate);
                } else {
                    component.route.activate = [component.route.activate, activate];
                }

                var deactivate = function(transition) {
                    style.parentNode && style.parentNode.removeChild(style);
                    transition.next();
                };

                if (!component.route.deactivate) {
                    component.route.deactivate = deactivate;
                } else if (component.route.deactivate instanceof Array) {
                    component.route.deactivate.push(deactivate);
                } else {
                    component.route.deactivate = [component.route.deactivate, deactivate];
                }
                return component;
            }

            var getHttp = function(uri, json, callback) {
                Vue.http.get(uri, {pjax:true, json:json ? true : undefined}, {timeout: 15000}).then(callback, callback);
            }


            var useSelf = false;

            Vue.getComponent = function(route, complete) {
                var data = {messages:[{message:'Response data'}], success: false};
                var name = route.name ? route.name : (route.view ? route.view : route.path.split('?')[0]);

                var dataFunction = function(callback) {
                    if (data !== null) {
                        var data2 = data;
                        data = null;
                        callback.call(this, data2);
                        return;
                    }


                    var _self = this;
                    getHttp(route.path, true, function(response) {
                        if (!response.status) {
                            callback.call(_self, null);
                            return;
                        }

                        if (typeof response.data == 'string' || (response.status >= 300 && response.status < 400)) {
                            callback.call(_self, false);
                            return;
                        }

                        callback.call(_self, response.data);
                    });
                };


                if (!components[name] && Window.localStorage['vue-' + name]) {
                    var storage = JSON.parse(Window.localStorage['vue-' + name]);
                    var component = {};
                    if (storage[0]) {
                        component = parseComponent(storage[0]);
                    }
                    if (storage[1]) {
                        component.template = storage[1];
                    }
                    if (storage[2]) {
                        component = parseComponentStyle(component, storage[2]);
                    }
                    if (storage[3]) {
                        parseJavascript(storage[3]);
                    }
                    if (storage[4]) {
                        component = parseComponentData(component, dataFunction);
                    }
                    components[name] = component;
                }

                var parseBody = function(contents) {
                    if (typeof contents != 'string' && !(contents instanceof Node)) {
                        complete(false);
                        return;
                    }

                    if (typeof contents == 'string') {
                        var element = Document.createElement('div');
                        element.innerHTML = contents;
                    } else {
                        var element = contents;
                    }

                    var el = element.querySelector('#vue');
                    if (!el) {
                        el = element;
                    }

                    var component = {};

                    var componentElement = el.querySelector('[type="application/vue"]');
                    var templateElement = el.querySelector('template');
                    var styleElement = el.querySelector('style');
                    var javascriptElement = el.querySelector('[type="text/javascript"]');
                    var dataElement = el.querySelector('[type="application/json"]');
                    el.parentNode && el.parentNode.removeChild(el);

                    if (componentElement) {
                        component = parseComponent(componentElement.textContent);
                        if (!component) {
                            complete(false);
                            return;
                        }
                    }

                    if (templateElement) {
                        component.template = templateElement.innerHTML;
                    }

                    if (!component.template) {
                        complete(false);
                        return;
                    }

                    if (styleElement) {
                        component = parseComponentStyle(component, styleElement.textContent);
                    }
                    if (javascriptElement) {
                        parseJavascript(javascriptElement.textContent);
                    }

                    if (dataElement) {
                        component = parseComponentData(component, dataFunction);
                        data = JSON.parse(dataElement.textContent);
                    }

                    Window.localStorage['vue-' + name] = JSON.stringify([
                        componentElement ? componentElement.textContent : '',
                        templateElement ? templateElement.innerHTML : '',
                        styleElement ? styleElement.textContent : '',
                        javascriptElement ? javascriptElement.textContent : '',
                        dataElement ? true : '',
                    ]);

                    components[name] = component;

                    complete(components[name]);
                    element = undefined;
                    el = undefined;
                    templateElement = undefined;
                    componentElement = undefined;
                }


                if (!useSelf && route.cache !== false && route.router.mode == 'html5' && !route.uri && Document.querySelector('#vue template')) {
                    useSelf = true;
                    parseBody(Document);
                    return;
                }

                if (useSelf && components[name] && route.cache !== false) {
                    data = null;
                    complete(components[name]);
                    return;
                }

                if (!route.uri) {
                    useSelf = true;
                }

                getHttp(route.uri || route.path, false, function(response) {
                    if (!response.status) {
                        data = false;
                        complete(null);
                        return;
                    }
                    // 重定向
                    parseBody(response.data);
                });
            }


            Vue.prototype.$routeData = function() {
                this.$options.routeData.call(this);
            }
        }
    });



    (function(router) {

        // 重写 uelencode 防止 uri 被encode
        var stringifyPath = router.stringifyPath;
        var a = Document.createElement('a');
        router.stringifyPath = function(path) {
            let generatedPath = ''
            if (path && typeof path === 'object') {
                if (path.name) {
                    const extend = Vue.util.extend
                    const currentParams =
                        this._currentTransition &&
                        this._currentTransition.to.params
                    const targetParams = path.params || {}
                    const params = currentParams
                        ? extend(extend({}, currentParams), targetParams)
                        : targetParams
                    for (let key in params) {
                        params[key] = encodeURI(params[key]);
                    }
                    generatedPath = this._recognizer.generate(path.name, params)
                } else if (path.path) {
                    generatedPath = path.path
                }
                if (path.query) {
                    // note: the generated query string is pre-URL-encoded by the recognizer
                    const query = this._recognizer.generateQueryString(path.query)
                    if (generatedPath.indexOf('?') > -1) {
                        generatedPath += '&' + query.slice(1)
                    } else {
                        generatedPath += query
                    }
                }
            } else {
                if (path.substr(0, 2) == '//' || path.indexOf(':') != -1) {
                    a.href = path;
                    path = a.pathname + a.search  + a.hash;
                }
                generatedPath = path;
            }
            return generatedPath
        };



        router.beforeEach(function(transition) {
            var next = function() {
                window.scrollTo(0, 0);
                transition.to.router.app.$refs.error.message = '';
                transition.to.router.app.$broadcast('loading', true);
                transition.next();
            };

            // 异步加载
            if (transition.to.async) {
                if (!transition.to.async.component) {
                    transition.to.router.app.$broadcast('loading', true);
                    Vue.getComponent(transition.to, function(component) {
                        if (!component) {
                            transition.from.router.app.$broadcast('error', component === null ? 'Timeout' : 'Content');
                            transition.from.router.app.$broadcast('loading', false);
                            transition.abort(component === null ? 'Timeout' : 'Content');
                            return;
                        }
                        transition.to.async.component = component;
                        next();
                    });
                } else {
                    next();
                }
            } else if (!transition.to.matched) {
                // 404
                next();
            } else {
                next();
            }
        });

        router.afterEach(function(transition) {
            transition.to.router.app.$broadcast('loading', false);
        });
    })(router);



    Document.addEventListener('DOMContentLoaded', function() {
        var maps = {
            '/account': {
                component: {
                    template:'<div class="row"><router-view></router-view></div>',
                },
                subRoutes: {
                    '/login/': {
                    },
                    '/create/': {
                    },
                    '/lostPassword/': {
                    },
                },
            },


            '/profile': {
                component: {
                    template:'<router-view></router-view>',
                },
                subRoutes: {
                    '/': {

                    },
                    '/setting/': {

                    },
                    '/avatar/': {

                    },
                    '/password/': {

                    },
                    '/log/': {

                    },
                },
            },
        };

        var routeFunction = function(maps) {
            for (var path in maps) {
                if (maps.hasOwnProperty(path)) {
                    var map = maps[path];
                    map.path = path;
                    (function(map) {
                        if (!map.component) {
                            map.async = {};
                            map.component = function(resolve) {
                                resolve(map.async.component);
                            };
                        }
                        if (map.subRoutes) {
                            routeFunction(map.subRoutes);
                        }
                    })(map);
                }
            }
        }
        routeFunction(maps);

        router.map(maps);

        router.start(Layout, '#app');
    }, false);
})(Vue, window, document);
