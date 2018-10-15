<template>
  <div>
    <button class="btn btn-primary pull-right" style="margin-bottom: 20px; margin-left: 10px;" data-toggle="modal" data-target="#newTestModal">
      <i class="fa fa-plus"></i> New Test
    </button>
    <button class="btn btn-warning pull-right" style="margin-bottom: 20px;">
          <i class="fa fa-line-chart"></i> Generate Reports
        </button>
    <table class="table table-bordered">
      <thead class="text-center">
        <td>Type</td>
        <td>Description</td>
        <td>Available On</td>
        <td>Settings</td>
        <td>Action</td>
      </thead>
      <tbody>
        <tr v-for="item, index in data" v-if="data !== null">
          <td>{{item.type.substr(0,10)}}</td>
          <td>{{item.description}}</td>
          <td>
            {{item.available_date + '@' + item.d_available_time}}
          </td>
          <td>
            <b>Questions per Page: </b>{{item.questions_per_page}} Question(s)
            <br>
            <b>Time per Question: </b>{{item.time_per_question}} Minute(s)
            <br>
            <b>Questions Order Settings: </b>{{item.orders_setting}}
            <br>
            <b>Questions Choices Settings: </b>{{item.choices_setting}}
          </td>
          <td>
            <i class="fa fa-pencil text-warning" v-on:click="editTest(index)" data-hover="tooltip" data-placement="top" title="Edit Test"></i>
            <i class="fa fa-trash text-danger" v-on:click="$parent.testConfirmation(item.id)" data-toggle="modal" data-target="#confirmationModal" data-hover="tooltip" data-placement="top" title="Delete Test"></i>
            <i class="fa fa-plus text-primary"  v-on:click="redirect('/questions/ft/' + item.code)" data-hover="tooltip" data-placement="top" title="Add Questions"></i>
          </td>
        </tr>
        <tr v-if="data === null">
          <td colspan="5" class="text-center text-danger">No Tests Posted!</td>
        </tr>
      </tbody>
    </table>
    <test-create :id="id"></test-create>
    <test-edit :test="editTestData" v-if="editTestData !== null"></test-edit>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'test-edit': require('modules/test/Edit.vue'),
    'test-create': require('modules/test/Create.vue')
  },
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      editTestData: null,
      data: null
    }
  },
  props: ['id'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieve(){
      let parameter = {
        condition: [{
          column: 'course_id',
          clause: '=',
          value: this.id
        }]
      }
      this.APIRequest('tests/retrieve_by_course', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    editTest(index){
      this.editTestData = this.data[index]
      setTimeout(() => {
        $('#editTestModal').modal('show')
      }, 500)
    }
  }
}
</script>
<style scoped>
</style>
