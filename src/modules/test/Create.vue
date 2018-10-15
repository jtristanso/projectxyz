<template>
  <div class="modal fade" id="newTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>New Test</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
          <br v-if="errorMessage !== null">
          <br>
          <div class="input-group">
            <span class="input-group-addon">Severity of Test</span>
            <input type="text" class="form-control" v-model="newTest.type" placeholder="Eq. Minor, Major and etc.">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Description</span>
            <input type="text" class="form-control" v-model="newTest.description" placeholder="Test Description">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Available Date</span>
            <input type="date" class="form-control" v-model="newTest.available_date">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Available Time</span>
            <input type="time" class="form-control" v-model="newTest.available_time">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Question per Page</span>
            <select class="form-control" v-model="newTest.questions_per_page">
              <option v-bind:value="i"v-for="i in 5">{{i}} Question(s)</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Questions Orders Settings</span>
            <select class="form-control" v-model="newTest.orders_setting">
              <option value="INORDER">INORDER</option>
              <option value="SHUFFLE">SHUFFLE</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Choices Orders Settings</span>
            <select class="form-control" v-model="newTest.choices_setting">
              <option value="INORDER">INORDER</option>
              <option value="SHUFFLE">SHUFFLE</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time Per Question</span>
            <select class="form-control" v-model="newTest.time_per_question">
              <option v-bind:value="i"v-for="i in 60">{{i}} Minute(s)</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#newTestModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="test()">Submit</button>
        </div>
      </div>
    </div>
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
      tokenData: AUTH.tokenData,
      errorMessage: null,
      newTest: {
        course_id: null,
        type: null,
        description: null,
        available_date: null,
        available_time: null,
        time_per_question: null,
        questions_per_page: null,
        orders_setting: null,
        choices_setting: null
      }
    }
  },
  props: ['id'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    test(){
      let test = this.newTest
      if(test.type === null || test.description === null || test.available_date === null || test.available_time === null || test.time_per_question === null || test.questions_per_page === null || test.orders_setting === null || test.choices_setting === null){
        this.errorMessage = 'Please fill in all required fields.'
      }else{
        this.newTest.course_id = this.id
        this.APIRequest('tests/create', this.newTest).then(response => {
          if(response.data > 0){
            this.$parent.retrieve()
            $('#newTestModal').modal('hide')
          }
        })
      }
    }
  }
}
</script>
<style scoped>

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

.input-group{
  margin-top: 5px;
  font-size: 13px !important;
}
.input-group-addon{
  width: 175px !important;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
  text-align: right !important;
}
</style>
