
export default{
  routes: [{
    path: '/marketplace',
    name: 'marketplace',
    component: resolve => require(['modules/marketplace/Marketplace.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  }
  ]
}
