<template>
  <div>
    <button class="btn btn-primary pull-right" style="margin-bottom: 5px;" v-on:click="redirect('/attendances/ft/' + enrolmentCode)"><i class="fa fa-plus" style="padding-right: 5px;"></i>Attendance</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td>Date</td>
          <td>Time</td>
          <td>Present</td>
          <td>Late</td>
          <td>Absent</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item, index in attendance" v-if="attendance !== null">
          <td>{{item.view_date}}</td>
          <td>{{item.d_time}}</td>
          <td>{{item.present}}%</td>
          <td>{{item.late}}%</td>
          <td>{{item.absent}}%</td>
        </tr>
        <tr v-else>
          <td colspan="5" class="text-center text-danger">No Tests Posted!</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData
    }
  },
  props: ['enrolmentCode', 'attendance'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
</style>
