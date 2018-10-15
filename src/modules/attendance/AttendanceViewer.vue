<template>
  <div>
      <div class="module-header">
        <div class="title" v-if="data !== null">
          <i class="fa fa-chevron-left text-warning action-link" style="padding-right: 10px;" v-on:click="redirect('/courses')"></i><label class="text-warning" v-if="data.course_details !== null"><b>{{data.course_details.code}}</b>/Attendance</label>
        </div>
        <div class="course-info"  v-if="data !== null">
          <i class="fa fa-calendar" style="padding-right: 10px;"></i><label>{{data.date}}</label>
          <i class="fa fa-clock" style="padding-right: 10px;padding-left: 50px;"></i><label>{{data.d_time}}</label>
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
          <tbody v-if="data !== null">
            <tr class="table-entries" v-for="item, index in data.enrolled_accounts" v-if="data.enrolled_accounts !== null">
              <td>
                <label v-if="item.attendance_account.account_details.information !== null">
                  {{item.attendance_account.account_details.information.first_name + ' ' + item.attendance_account.account_details.information.middle_name + ' ' + item.attendance_account.account_details.information.last_name}}
                </label>
                <label v-else>{{item.attendance_account.account_details.username}}</label>
              </td>
              <td>
                <label v-if="item.attendance_account.account_details.degree !== null">
                  {{item.attendance_account.account_details.degree[0].course}}
                </label>
                <label v-else>Null</label>
              </td>
              <td>
              <input v-model="item.attendance_account.remarks" placeholder="Remarks" class="form-control"/>
              </td>
              <td>
                <button class="btn" v-bind:class="{'btn-primary': item.attendance_account.status === 'PRESENT'}" v-on:click="submit(item.attendance_account, 'PRESENT')">
                  Present
                </button> 
                <button class="btn" v-bind:class="{'btn-warning': item.attendance_account.status === 'LATE'}"  v-on:click="submit(item.attendance_account, 'LATE')">
                  Late
                </button> 
                <button class="btn" v-bind:class="{'btn-danger': item.attendance_account.status === 'ABSENT'}"  v-on:click="submit(item.attendance_account, 'ABSENT')">
                  Absent
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="customModal" id="loadingModalAttendanceViewer">
        <span class="loading">
          <i class="fas fa-spinner fa-spin"></i>
        </span>
      </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieve()
    this.code = this.$route.params.code
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      data: null,
      code: this.$route.params.code
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieve(){
      let parameter = {
        'condition': [{
          'value': this.code,
          'clause': '=',
          'column': 'code'
        }]
      }
      $('#loadingModalAttendanceViewer').css({'display': 'block'})
      this.APIRequest('attendances/retrieve', parameter).then(response => {
        $('#loadingModalAttendanceViewer').css({'display': 'none'})
        if(response.data.length > 0){
          this.data = response.data[0]
        }else{
          this.data = null
        }
      })
    },
    submit(data, status){
      if(data.status === null){
        let parameter = {
          'remarks': data.remarks,
          'status': status,
          'account_id': data.account_id,
          'attendance_id': data.attendance_id
        }
        this.create(parameter)
      }else{
        let parameter = {
          'id': data.id,
          'remarks': data.remarks,
          'status': status
        }
        this.update(parameter)
      }
    },
    create(parameter){
      this.APIRequest('attendance_accounts/create', parameter).then(response => {
        if(response.data > 0){
          this.retrieve()
        }
      })
    },
    update(parameter){
      this.APIRequest('attendance_accounts/update', parameter).then(response => {
        if(response.data === true){
          this.retrieve()
        }
      })
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
