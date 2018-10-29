
export default{
  routes: [{
    path: '/webrtc',
    name: 'webrtc',
    component: resolve => require(['modules/webrtc/Webrtc.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  }
  ]
}
