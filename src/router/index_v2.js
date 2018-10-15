
import Vue from 'vue'
import Router from 'vue-router'
import CONFIG from '../config'
import AUTH from 'services/auth/index'
import Helpers from './helpers'
import Services from './services'
import BootstrapVue from 'bootstrap-vue'
global.Tether = require('tether')
global.jQuery = require('jquery')
global.$ = global.jQuery
// require('bootstrap/dist/css/bootstrap.min.css')
// require('bootstrap')
// require('font-awesome/css/font-awesome.css')
require('assets/style/bootstrap.min.css')
require('assets/style/bootstrap-grid.min.css')
require('assets/style/bootstrap-reboot.min.css')
global.Popper = require('assets/js/min/popper.min.js').default
require('assets/js/min/bootstrap.min.js')
require('assets/style/theme.css')
require('assets/style/select2.min.css')
require('assets/js/min/select2.full.min.js')
import 'font-awesome/css/font-awesome.css'
AUTH.checkAuthentication()
// import AUTH from 'services/auth'
// let CONFIG = require('config.js')
let beforeEnter = (to, from, next) => {
  // TODO Redirect if no token when token is required in meta.tokenRequired
  AUTH.currentPath = to.path
  if(AUTH.user.userID || to.meta.tokenRequired !== true){
    next()
  }else{
    if(!AUTH.tokenData.verifyingToken){
      next({
        path: '/'
      })
    }else{
      next()
    }
  }
}
var devRoutes = []
let canales = require('./dev_routes/canales.js')
let magUsara = require('./dev_routes/mag_usara.js')
let espina = require('./dev_routes/espina.js')
let estella = require('./dev_routes/estella.js')
devRoutes = devRoutes.concat(canales.default.routes)
devRoutes = devRoutes.concat(magUsara.default.routes)
devRoutes = devRoutes.concat(espina.default.routes)
devRoutes = devRoutes.concat(estella.default.routes)
for(let x = 0; x < devRoutes.length; x++){
  devRoutes[x]['beforeEnter'] = beforeEnter
}
let routes = [
  {
    path: '/',
    name: 'home',
    component: resolve => require(['modules/home/LogIn.vue'], resolve),
    beforeEnter: beforeEnter
  }
]
routes = routes.concat(devRoutes)
Vue.use(Router)
Vue.use(BootstrapVue)
export default new Router({
  routes: routes
})
