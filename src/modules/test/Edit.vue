<template>
  <div class="modal fade" id="editTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="test !== null">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Edit Test</h5>
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
            <input type="text" class="form-control" v-model="test.type" placeholder="Eq. Minor, Major and etc.">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Description</span>
            <input type="text" class="form-control" v-model="test.description" placeholder="Test Description">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Available Date</span>
            <input type="date" class="form-control" v-model="test.available_date">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Available Time</span>
            <input type="time" class="form-control" v-model="test.available_time">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Question per Page</span>
            <select class="form-control" v-model="test.questions_per_page">
              <option v-bind:value="i"v-for="i in 5">{{i}} Question(s)</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Questions Orders Settings</span>
            <select class="form-control" v-model="test.orders_setting">
              <option value="INORDER">INORDER</option>
              <option value="SHUFFLE">SHUFFLE</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Choices Orders Settings</span>
            <select class="form-control" v-model="test.choices_setting">
              <option value="INORDER">INORDER</option>
              <option value="SHUFFLE">SHUFFLE</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time Per Question</span>
            <select class="form-control" v-model="test.time_per_question">
              <option v-bind:value="i"v-for="i in 60">{{i}} Minute(s)</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editTestModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="updateTest()">Update</button>
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
      errorMessage: null
    }
  },
  props: {
    test: Object
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    updateTest(){
      if(this.test.type === null || this.test.description === null || this.test.available_date === null || this.test.available_time === null || this.test.time_per_question === null || this.test.questions_per_page === null || this.test.orders_setting === null || this.test.choices_setting === null){
        this.errorMessage = 'Please fill in all required fields.'
      }else{
        this.APIRequest('tests/update', this.test).then(response => {
          if(response.data === true){
            this.$parent.retrieve()
            $('#editTestModal').modal('hide')
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
