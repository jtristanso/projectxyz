
export default{
  routes: [{
    path: '/resources/ft/:code',
    name: 'resourcesFt',
    component: resolve => require(['modules/resource/ResourceManager.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/resources_viewer/:code',
    name: 'resources_viewer',
    component: resolve => require(['modules/resource/ResourceViewer.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/resources/fs',
    name: 'resourcesFs',
    component: resolve => require(['modules/resource/MyResourceManager.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }]
}
