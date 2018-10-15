// src/auth/index.js
import {router} from '../../router/index'
import ROUTER from '../../router'
import {Howl} from 'howler'
import Vue from 'vue'
export default {
  user: {
    userID: 0,
    username: '',
    type: null,
    status: null,
    profile: null,
    schools: null,
    semesters: null,
    selectedSemester: null,
    schoolSemesters: null,
    organization: null,
    course: null,
    notifications: {
      data: null,
      current: null,
      prevCurrent: null
    },
    events: {
      data: null,
      current: null
    }
  },
  timer: {
    interval: null,
    speed: 1000
  },
  notifTimer: {
    timer: null,
    speed: 1000
  },
  reports: {
    description: null,
    course_id: null
  },
  google: {
    code: null
  },
  tokenData: {
    token: null,
    tokenTimer: false,
    verifyingToken: false
  },
  currentPath: false,
  setUser(userID, username, type, status, profile, schools, semesters, schoolSemesters, activeSemester, organization, course){
    if(userID === null){
      username = null
      type = null
      status = null
      profile = null
      schools = null
      semesters = null
      schoolSemesters = null
      activeSemester = null
      organization = null
      course = null
    }
    this.user.userID = userID * 1
    this.user.username = username
    this.user.type = type
    this.user.status = status
    this.user.profile = profile
    this.user.schools = schools
    this.user.semesters = semesters
    this.user.selectedSemester = activeSemester
    this.user.schoolSemesters = schoolSemesters
    this.user.organization = organization
    this.user.course = course
    localStorage.setItem('account_id', this.user.userID)
  },
  setToken(token){
    this.tokenData.token = token
    localStorage.setItem('usertoken', token)
    if(token){
      setTimeout(() => {
        let vue = new Vue()
        vue.APIRequest('authenticate/refresh', {}, (response) => {
          this.setToken(response['token'])
        }, (response) => {
          this.deaunthenticate()
        })
      }, 1000 * 60 * 60) // 50min
    }
  },
  authenticate(username, password, callback, errorCallback){
    let vue = new Vue()
    let credentials = {
      username: username,
      password: password,
      status: 'VERIFIED'
    }
    vue.APIRequest('authenticate', credentials, (response) => {
      this.setToken(response.token)
      vue.APIRequest('authenticate/user', {}, (userInfo) => {
        let parameter = {
          'condition': [{
            'value': userInfo.id,
            'clause': '=',
            'column': 'id'
          }]
        }
        vue.APIRequest('accounts/retrieve', parameter).then(response => {
          let profile = response.data[0].account_profile
          let schools = response.data[0].schools
          let semesters = response.data[0].my_semesters
          let schoolSemesters = response.data[0].school_semesters
          let activeSemester = response.data[0].active_semester_details
          let organization = response.data[0].active_organization
          let course = response.data[0].active_course
          this.setUser(userInfo.id, userInfo.username, userInfo.account_type, userInfo.status, profile, schools, semesters, schoolSemesters, activeSemester, organization, course)
          if(response.data[0].account_information === null || response.data[0].account_degree === null){
            ROUTER.push('account_settings')
          }else{
            ROUTER.push('/discussions')
            // if(userInfo.account_type === 'STUDENT'){
            //   ROUTER.push('/guide/fs')
            // }else{
            //   ROUTER.push('/guide/ft')
            // }
          }
        })
        this.retrieveNotifications(userInfo.id)
        this.retrieveEvents(userInfo.id)
        if(callback){
          callback(userInfo)
        }
      })
    }, (response, status) => {
      if(errorCallback){
        errorCallback(response, status)
      }
    })
  },
  checkAuthentication(callback){
    this.tokenData.verifyingToken = true
    let token = localStorage.getItem('usertoken')
    if(token){
      this.setToken(token)
      let vue = new Vue()
      vue.APIRequest('authenticate/user', {}, (userInfo) => {
        let parameter = {
          'condition': [{
            'value': userInfo.id,
            'clause': '=',
            'column': 'id'
          }]
        }
        vue.APIRequest('accounts/retrieve', parameter).then(response => {
          let profile = response.data[0].account_profile
          let schools = response.data[0].schools
          let semesters = response.data[0].my_semesters
          let schoolSemesters = response.data[0].school_semesters
          let activeSemester = response.data[0].active_semester_details
          let organization = response.data[0].active_organization
          let course = response.data[0].active_course
          this.setUser(userInfo.id, userInfo.username, userInfo.account_type, userInfo.status, profile, schools, semesters, schoolSemesters, activeSemester, organization, course)
        }).done(response => {
          this.tokenData.verifyingToken = false
          let location = window.location.href
          if(this.currentPath){
            // ROUTER.push(this.currentPath)
          }else{
            window.location.href = location
          }
        })
        this.retrieveNotifications(userInfo.id)
        this.retrieveEvents(userInfo.id)
      }, (response) => {
        this.setToken(null)
        this.tokenData.verifyingToken = false
        ROUTER.push({
          path: this.currentPath
        })
      })
      return true
    }else{
      this.tokenData.verifyingToken = false
      this.setUser(null)
      return false
    }

  },
  deaunthenticate(){
    localStorage.removeItem('usertoken')
    localStorage.removeItem('account_id')
    this.setUser(null)
    let vue = new Vue()
    vue.APIRequest('authenticate/invalidate')
    this.clearNotifTimer()
    ROUTER.push('/')
    ROUTER.go('/')
  },
  retrieveNotifications(accountId){
    let vue = new Vue()
    let parameter = {
      'account_id': accountId
    }
    vue.APIRequest('notifications/retrieve', parameter).then(response => {
      if(response.data.length > 0){
        this.user.notifications.data = response.data
        this.user.notifications.current = response.notification_current
        if(this.user.notifications.current > 0){
          // this.playNotificationSound()
        }
      }else{
        this.user.notifications.data = null
        this.user.notifications.current = null
      }
    })
  },
  retrieveEvents(accountId){
    let vue = new Vue()
    let parameter = {
      condition: [{
        value: accountId,
        column: 'account_id',
        clause: '='
      }],
      sort: {
        created_at: 'DESC'
      }
    }
    vue.APIRequest('event_tickets/retrieve_my_tickets', parameter).then(response => {
      if(response.data.length > 0){
        this.user.events.data = response.data
        this.user.events.current = response.event_current
      }else{
        this.user.events.data = null
        this.user.events.current = null
      }
    })
  },
  startNotifTimer(accountId){
    if(this.notifTimer.timer === null){
      this.notifTimer.timer = window.setInterval(() => {
        if(accountId !== null){
          this.retrieveNotifications(accountId)
        }
      }, this.notifTimer.speed)
    }
  },
  clearNotifTimer(){
    if(this.notifTimer.timer !== null){
      window.clearInterval(this.notifTimer.timer)
      this.notifTimer.timer = null
    }
  },
  playNotificationSound(){
    let audio = require('../../assets/audio/notification.mp3')
    let sound = new Howl({
      src: [audio]
    })
    if(this.user.notifications.prevCurrent === null){
      sound.play()
      this.user.notifications.prevCurrent = this.user.notifications.current
    }else if(this.user.notifications.prevCurrent < this.user.notifications.current){
      sound.play()
      this.user.notifications.prevCurrent = this.user.notifications.current
    }
  },
  setReports(description, courseId){
    this.reports.description = description
    this.reports.course_id = courseId
  }
}
