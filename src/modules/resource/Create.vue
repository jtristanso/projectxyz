<template>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Add New Course</h5>
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
            <span class="input-group-addon">Course Code</span>
            <input type="text" class="form-control" v-model="newCourse.code" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Description</span>
            <input type="text" class="form-control" v-model="newCourse.description" placeholder="Type description here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Units</span>
            <input type="text" class="form-control" v-model="newCourse.units" placeholder="Type number of units here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time Start</span>
            <input type="time" class="form-control" v-model="newCourse.time_start">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time End</span>
            <input type="time" class="form-control" v-model="newCourse.time_end">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Days</span>
            <input type="text" class="form-control" v-model="newCourse.days" placeholder="MTWTHFSATSUN">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
            <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
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
      newCourse: {
        account_semester_id: null,
        account_id: null,
        code: null,
        enrolment_code: null,
        description: null,
        units: null,
        time_start: null,
        time_end: null,
        days: null
      }
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(){
      if(isNaN(this.newCourse.units) === true){
        this.errorMessage = 'Field Units must be a number'
      }else if(this.newCourse.code !== null || this.newCourse.description !== null || this.newCourse.units !== null || isNaN(this.newCourse.units) === false || this.newCourse.time_start !== null || this.newCourse.time_end !== null || this.newCourse.days !== null){
        this.errorMessage = null
        this.createRequest()
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    createRequest(){
      this.newCourse.account_semester_id = this.user.selectedSemester.id
      this.newCourse.account_id = this.user.userID
      this.APIRequest('courses/create', this.newCourse).then(response => {
        if(response.data > 0){
          $('#myModal').modal('hide')
          this.$parent.selectedId = response.data
          this.$parent.updateActiveCourse()
          this.errorMessage = null
        }else{
          this.errorMessage = response.data.message
        }
      })
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
