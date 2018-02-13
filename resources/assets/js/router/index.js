import Vue from 'vue'
import Router from 'vue-router'
import QuestionPage from '../components/QuestionPage'
import AllPage from '../components/AllPage'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/all',
      name: 'AllPage',
      component: AllPage
    },
    {
      path: '/:question?',
      name: 'QuestionPage',
      component: QuestionPage
    }
  ]
})
