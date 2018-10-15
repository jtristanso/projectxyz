import Vue from 'vue'
import global from './global'
import config from '../config'
/***
  Be sure to name the helper properly inroder to avoid conflicting on methods in Vue Modules and Components
*/
Vue.mixin({
  data(){
    return {
      IMAGE_URL: config.IMAGE_URL
    }
  },
  methods: {
    modal: function(action){
      if(action === 'show'){
        global.hasShownModal = true
      }else if(action === 'close'){
        global.hasShownModal = false
      }
    },
    sampleHelper(){
      return 'hello world'
    },
    monthWord(monthIndex, isThreeLetter){
      let month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
      return isThreeLetter ? (month[monthIndex]).substring(0, 2) : month[monthIndex]
    },
    formatTime(datetimeObject){
      console.log(datetimeObject)
      console.log(datetimeObject.getHours())
      var hours = datetimeObject.getHours()
      var minutes = datetimeObject.getMinutes()
      var ampm = hours >= 12 ? 'pm' : 'am'
      hours = hours % 12
      hours = hours || 12 // the hour '0' should be '12'
      minutes = minutes < 10 ? '0' + minutes : minutes
      var strTime = hours + ':' + minutes + ' ' + ampm
      return strTime
    }
  }
})
