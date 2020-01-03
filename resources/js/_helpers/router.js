import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from '../components/Login.vue'
import Home from '../components/Home.vue'

import StoreVotation from '../components/votations/StoreVotation.vue'
import Votations from '../components/votations/Votations.vue'
import Votation from '../components/votations/Votation.vue'

Vue.use(VueRouter)

const routes = [
  {'path': '/', 'component': Votations},
  {'path': '/votations/create', 'component': StoreVotation},
  {
    path: '/votations/:votationId',
    name: 'votation', 
    component: Votation,
    props: (route) => ({
      'votationId': route.params.votationId,
      'votation': route.params.votation
    })
  },
  {'path': '/login', 'component': Login, 'beforeEnter': (to, from, next)=>{
    if( localStorage.getItem('user') ){
      return next(false)
    }
    return next()
  }}
]

export const router = new VueRouter({mode: 'history', routes})
router.beforeEach((to, from, next) => {
  // redirect to login page if not logged in and trying to access a restricted page
  const publicPages   = ['/']
  const adminPages    = []
  const authRequired  = !publicPages.includes(to.path)
  const adminRequired = adminPages.includes(to.path)
  const loggedIn      = localStorage.getItem('user')
  const isAdmin       = loggedIn && loggedIn.role == 'admin'

  // if (authRequired && !loggedIn) {
  //   return next('/login')
  // }else if (adminRequired && !isAdmin) {
  //   next(false)
  // }
  
  next();
})