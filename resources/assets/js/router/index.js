import Vue from 'vue'
import Router from 'vue-router'
import AllPage from '../components/AllPage'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/all',
      name: 'AllPage',
      component: AllPage
    }
  ]
})
