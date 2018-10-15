import Vue from 'vue'
import global from './global'
import config from '../config'
/***
  Be sure to name the helper properly inroder to avoid conflicting on methods in Vue Modules and Components
*/
Vue.mixin({
  methods: {
    latestData: function(apiLink, latestData){
      this.APIRequest(apiLink, {}, (response) => {
        console.log(response)
        latestData['data'] = response['data']
        latestData['request_timestamp'] = response['request_timestamp']
      })
    }

  }
})
