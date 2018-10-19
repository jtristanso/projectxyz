
export default{
  routes: [{
    path: '/messenger',
    name: 'messenger',
    component: resolve => require(['modules/messenger/Messenger.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  }
  ]
}
