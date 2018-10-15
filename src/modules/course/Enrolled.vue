<template>
  <div>
    <button class="btn btn-warning pull-right" style="margin-bottom: 20px;">
      <i class="fa fa-line-chart"></i> Generate Grade
    </button>
    <table class="table table-bordered">
      <thead class="text-center">
        <td>ID Number</td>
        <td>Full Name</td>
        <td>Course</td>
        <td>School</td>
        <td>Action</td>
      </thead>
      <tbody>
        <tr v-for="item, index in enrollees" v-if="enrollees !== null">
          <td>
            <label v-if="item.account_details.degree !== null">{{item.account_details.degree[0].school_student_code}}</label>
            <label v-else>Null</label>
            <label class="text-warning" v-if="item.status === '0'">(Pending)</label>
            <label class="text-primary" v-if="item.status === '1'">(Approved)</label>
            <label class="text-danger" v-if="item.status === '2'">(Declined)</label>
          </td>
          <td>
            <label v-if="item.account_details.information !== null">
              {{item.account_details.information.first_name + ' ' + item.account_details.information.last_name}}
            </label>
            <label v-else>{{item.account_details.username}}</label>
          </td>
          <td>
            <label v-if="item.account_details.degree !== null">
              {{item.account_details.degree[0].course}}
            </label>
            <label v-else>Null</label>
          </td>
          <td>
            <label v-if="item.account_details.degree !== null">
              {{item.account_details.degree[0].school.school_abbreviation}}
            </label>
            <label v-else>Null</label>
          </td>
          <td>
            <i class="fa fa-check text-primary" data-hover="tooltip" data-placement="top" title="Approve" v-on:click="updateEnrolled(item.id, 1)"></i>
            <i class="fa fa-ban text-danger" data-hover="tooltip" data-placement="top" title="Decline" v-on:click="updateEnrolled(item.id, 2)"></i>
          </td>
        </tr>
      </tbody>
    </table>
    <span class="text-danger" v-if="enrollees === null">No student has enrolled yet</span>
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
  props: ['enrollees'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    updateEnrolled(id, status){
      let parameter = {
        'id': id,
        'status': status
      }
      this.APIRequest('enrolled_courses/update', parameter).then(response => {
        if(response.data === true){
          this.$parenet.retrieveRequest(true)
        }
      })
    }
  }
}
</script>
<style scoped>
</style>
