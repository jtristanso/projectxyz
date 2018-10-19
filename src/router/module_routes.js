import AUTH from 'services/auth'
let CONFIG = require('config.js')
let beforeEnter = (to, from, next) => {
  // TODO Redirect if no token when token is required in meta.tokenRequired
  AUTH.currentPath = to.path
  let userID = localStorage.getItem('account_id')
  let token = localStorage.getItem('usertoken')
  if(userID > 0 || token !== null || to.meta.tokenRequired !== true){
    if(to.path === '/' && userID > 0 && token !== null){
      next({path: '/'})
    }else{
      next()
    }
  }else{
    next({path: '/'})
    // if(userID <= 0){
    //   next({
    //     path: '/'
    //   })
    // }else{
    //   next()
    // }
  }
}
var devRoutes = []
let canales = require('./dev_routes/canales.js')
let magUsara = require('./dev_routes/mag_usara.js')
let espina = require('./dev_routes/espina.js')
let estella = require('./dev_routes/estella.js')
let messenger = require('./dev_routes/messenger.js')
let marketplace = require('./dev_routes/marketplace.js')
devRoutes = devRoutes.concat(canales.default.routes)
devRoutes = devRoutes.concat(magUsara.default.routes)
devRoutes = devRoutes.concat(espina.default.routes)
devRoutes = devRoutes.concat(estella.default.routes)
devRoutes = devRoutes.concat(messenger.default.routes)
devRoutes = devRoutes.concat(marketplace.default.routes)
for(let x = 0; x < devRoutes.length; x++){
  devRoutes[x]['beforeEnter'] = beforeEnter
}
let routes = [
  {
    path: '/',
    name: 'home',
    component: resolve => require(['modules/home/Landing.vue'], resolve),
    beforeEnter: beforeEnter
  }
]
// if(CONFIG.default.IS_DEV){
routes = routes.concat(devRoutes)
// }
export default{
  routes: routes
}
