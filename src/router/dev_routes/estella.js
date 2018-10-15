
export default{
  routes: [{
    path: '/attendances/ft/:enrolment_code',
    name: 'attendancesFt',
    component: resolve => require(['modules/attendance/Attendances.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }]
}
