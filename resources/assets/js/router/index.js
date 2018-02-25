import Vue from 'vue'
import Router from 'vue-router'
import QuestionPage from '../components/QuestionPage'
import AllPage from '../components/AllPage'
import Tree from '../components/Tree'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/tree',
      name: 'Treeview',
      component: Tree
    },
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
