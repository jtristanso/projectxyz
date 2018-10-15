<template>
  <div>
      <div class="module-header">
        <div class="title" v-if="course !== null">
          <i class="fa fa-chevron-left text-warning action-link" style="padding-right: 10px;" v-on:click="redirect('/courses')"></i><label class="text-warning"><b>{{course.code}}</b>/Attendance</label>
        </div>
        <button class="btn btn-primary pull-right" @click="generateQRCode()" v-if="attendance === null"> Generate QR Code</button>
        <button class="btn btn-primary pull-right" v-else @click="showQR()">Show QR Code</button>
        <div class="course-info" v-if="course !== null">
          <i class="fa fa-info" style="padding-right: 10px;"></i><label>{{course.description + ' (' + course.units + ')'}}</label>
          <i class="fa fa-clock" style="padding-right: 10px;padding-left: 50px;"></i><label>{{course.time_start + ' - ' + course.time_end + ' (' + course.days + ')'}}</label>
        </div>

        <input type="radio" v-model="dateFlag" v-on:click="toggleDate()" />Use Current Date & Time
        <div class="input-group" v-if="dateFlag === true">
          <span class="input-group-addon input-group-addon2">Date</span>
          <input type="date" class="form-control" v-model="date">
          <span class="input-group-addon input-group-addon2">Time</span>
          <input type="time" class="form-control" v-model="time">
        </div>
      </div>
      <div class="table-result">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr class="table-header">
              <td>Name</td>
              <td>Course</td>
              <td>Remarks</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody v-if="course !== null">
            <tr class="table-entries" v-for="item, index in course.enrollees">
              <td>{{item.account_details.information.first_name + ' ' + item.account_details.information.middle_name + ' ' + item.account_details.information.last_name}}</td>
              <td>
                {{item.account_details.degree[0].course}}
              </td>
              <td>
              <input v-model="item.remarks" placeholder="Remarks" class="form-control"/>
              </td>
              <td>
                <button class="btn" v-bind:class="{'btn-primary': item.status === 'PRESENT'}" v-on:click="update(item.account_details, item.attendance, item, 'PRESENT')">Present</button> 
                <button class="btn" v-bind:class="{'btn-warning': item.status === 'LATE'}"  v-on:click="update(item.account_details, item.attendance, item, 'LATE')">Late</button> 
                <button class="btn" v-bind:class="{'btn-danger': item.status === 'ABSENT'}"  v-on:click="update(item.account_details, item.attendance, item, 'ABSENT')">Absent</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <show-qr :url="qrCodeUrl" v-if="qrCodeUrl !== null"></show-qr>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  components: {
    'show-qr': require('modules/attendance/DisplayQrCode.vue')
  },
  mounted(){
    this.retrieveCourse(true)
    this.code = this.$route.params.enrolment_code
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      course: null,
      date: null,
      time: null,
      code: this.$route.params.enrolment_code,
      dateFlag: null,
      qrCodeUrl: null,
      attendance: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveCourse(flag){
      let parameter = {
        'condition': [{
          'value': this.code,
          'clause': '=',
          'column': 'enrolment_code'
        }],
        'date': this.date
      }
      this.APIRequest('courses/retrieve_by_attendance', parameter).then(response => {
        if(response.data.length > 0){
          this.course = response.data[0]
          if(this.course.attendance !== null){
            this.qrCodeUrl = this.course.attendance.qr_code_url
            this.attendance = this.course.attendance
          }
        }else{
          this.qrCodeUrl = null
          this.course = null
        }
      })
    },
    update(account, attendance, data, status){
      let flag = this.validateAttendance()
      if(attendance === null && flag === true){
        // create attendance
        let attendanceParameter = {
          'date': this.date,
          'time': this.time,
          'course_id': this.course.id
        }
        let attendanceAccountParameter = {
          'attendance_id': null,
          'account_id': account.id,
          'remarks': data.remarks,
          'status': status
        }
        this.createAttendance(attendanceParameter, attendanceAccountParameter)
      }else if(data.attendance_account === null && flag === true && attendance !== null){
        let parameter = {
          'account_id': account.id,
          'attendance_id': data.attendance.id,
          'remarks': data.remarks,
          'status': status
        }
        this.createAttendanceAccount(parameter)
      }else if(data.attendance_account !== null && flag === true && attendance !== null){
        let parameter = {
          'id': data.attendance_account.id,
          'account_id': account.id,
          'attendance_id': data.attendance.id,
          'remarks': data.remarks,
          'status': status
        }
        this.updateAttendanceAccount(parameter)
      }
    },
    validateAttendance(){
      if(this.dateFlag === true){
        if(this.date === null || this.date === ''){
          return false
        }else{
          return true
        }
      }else{
        return true
      }
    },
    createAttendance(attendanceParameter, attendanceAccountParameter){
      this.APIRequest('attendances/create', attendanceParameter).then(response => {
        if(response.data > 0){
          attendanceAccountParameter.attendance_id = response.data
          this.APIRequest('attendance_accounts/create', attendanceAccountParameter).then(response => {
            if(response.data > 0){
              this.retrieveCourse(true)
            }
          })
        }
      })
    },
    updateAttendance(parameter){
      this.APIRequest('attendances/update', parameter).then(response => {
        if(response.data === true){
          this.retrieveCourse(true)
        }
      })
    },
    createAttendanceAccount(parameter){
      this.APIRequest('attendance_accounts/create', parameter).then(response => {
        if(response.data > 0){
          this.retrieveCourse(true)
        }
      })
    },
    updateAttendanceAccount(parameter){
      this.APIRequest('attendance_accounts/update', parameter).then(response => {
        if(response.data === true){
          this.retrieveCourse(true)
        }
      })
    },
    toggleDate(){
      if(this.dateFlag === null){
        this.dateFlag = true
      }else{
        this.dateFlag = !this.dateFlag
      }
    },
    generateQRCode(){
      if(this.course.attendance !== null){
        let parameter = {
          id: this.course.attendance.id,
          code: this.course.attendance.code
        }
        this.APIRequest('attendances/generate_qr_code', parameter).then(response => {
          if(response.data === true){
            this.retrieveCourse(true)
          }
        })
      }
    },
    showQR(){
      if(this.course.attendance.qr_code_url !== null){
        $('#qrCodeModal').modal('show')
      }
    }
  }
}
</script>
<style>

mark{
  background: none;
}
form{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: -250px;
  width: 500px;
  height: 120px;
  border: 4px dashed;
  border-radius: 3px;
}
form p{
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 120px;
}
form input{
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
}

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

td button i{
  padding-right: 10px;
}
thead{
  font-weight: 700;
}
.input-group{
  margin-top: 5px;
  font-size: 13px !important;
}
.input-group-addon{
  width: 125px;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
}
.input-group-addon2{
  width: 150px;
}
.table .table-header{
  font-size: 12px;
}
.set-date{
  width: 40;
  float: left;
  position: relative;
  margin-left: 50px;
}

</style>
