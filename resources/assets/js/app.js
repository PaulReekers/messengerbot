import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import App from './components/App'
import router from './router'
import store from './store';

Vue.use(ElementUI)

Vue.config.productionTip = false

Vue.component('TreeItem', function (resolve) {
  require(['./components/TreeItem'], resolve)
})

Vue.component('TreeOption', function (resolve) {
  require(['./components/TreeOption'], resolve)
})

/* eslint-disable no-new */
new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})
