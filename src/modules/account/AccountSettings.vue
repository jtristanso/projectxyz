<template>
  <div class="account-settings-holder" v-if="data !== null">
    <div class="account-header text-center">
      <span class="profile-image-settings" v-if="data.account_profile !== null" v-on:click="addProfile()">
        <img v-bind:src="config.BACKEND_URL + data.account_profile.profile_url" class="profile-image-content">
        <input type="file" name="file" accept="image/*"  @change="setUpFileUpload($event)">
      </span>
      <span class="profile-image-settings" v-else  v-on:click="addProfile()">
        <i class="fa fa-user-circle-o profile-image-content"></i>
        <input type="file" name="file" accept="image/*"  @change="setUpFileUpload($event)">
      </span>
      <span class="account-name">
        <label v-if="data.account_information.first_name !== null && data.account_information.last_name !== null">
          {{data.account_information.first_name + ' ' + data.account_information.last_name}}
        </label>
        <label v-else>{{user.username}}</label>
      </span>
    </div>
    <div class="account-settings-pager">
      <ul class="pager-menu"> 
        <li class="header"><b>Settings</b></li>
        <li v-for="item, index in pagerMenu" v-bind:class="{'bg-selected-menu': item.flag === true}" v-on:click="makeActive(index)">{{item.title}}
<!--           <i v-if="index === 1 && data.account_information === null" class="fas fa-exclamation-triangle pull-right" id = "personalInfo">
          </i>
          <i v-if="index === 2 && data.account_degree === null" class="fas fa-exclamation-triangle pull-right" id="edBackground">
          </i>
          <i v-if="index === 3 && data.account_work === null" class="fas fa-exclamation-triangle pull-right" id="workExp">
          </i> -->
          <!-- Tooltips -->
 <!--          <b-tooltip v-if="index ===1" show target="personalInfo" v-bind:title="item.tooltip_title" placement="right" class="tooltips" boundary="window" triggers="none" show="true"></b-tooltip>
          <b-tooltip v-if="index ===2" show target="edBackground" v-bind:title="item.tooltip_title" placement="right" class="tooltips" boundary="window" triggers="none" show="true"></b-tooltip>
          <b-tooltip v-if="index ===3" show target="workExp" v-bind:title="item.tooltip_title" placement="right" boundary="window" triggers="none" show="true"></b-tooltip> -->
          <!-- end of Tooltips -->
        </li>
      </ul>
      <div class="pager-content">




        <div class="information-holder" v-if="pagerMenuSelectedIndex === 0 && data.notifications !== null">
          <span class="header-account-settings">
            <b>
                Login Notification Settings
            </b>
          </span>
          <span class="item">
            <span class="content">
              <label style="padding-top: 15px;">
                Email
                <label v-if="user.status === 'NOT_VERIFIED'" class="text-danger"> - Please verify your email to activate you email notification</label>
              </label>
              <label class="pull-right" style="padding-top: 25px;" v-if="user.status === 'NOT_VERIFIED'">
                <button class="btn btn-primary" v-on:click="sendEmailVerification()">Send Email Verification</button>
              </label>
              <label class="pull-right"  v-if="user.status === 'VERIFIED'">
                <i v-bind:class="{'text-green': data.notifications[0].email === 'ON', 'fa-toggle-on': data.notifications[0].email === 'ON', 'text-danger': data.notifications[0].email === 'OFF', 'fa-toggle-off': data.notifications[0].email === 'OFF'}" class="fa on-icon" v-on:click="updateNotification('email', data.notifications[0].email, data.notifications[0])"></i>
              </label>
            </span>
          </span>
<!--           <span class="item">
            <span class="content">
              <label style="padding-top: 25px;">
                SMS (Coming Soon!)
              </label>
              <label class="pull-right">
                <i v-bind:class="{'text-green': data.notifications[0].sms === 'ON', 'fa-toggle-on': data.notifications[0].sms === 'ON', 'text-danger': data.notifications[0].sms === 'OFF', 'fa-toggle-off': data.notifications[0].sms === 'OFF'}" class="fa on-icon"></i>
                v-on:click="updateNotification('sms', data.notifications[0].sms, data.notifications[0])"
              </label>
            </span>
          </span> -->
          <span class="item">
            <span class="content">
              <label style="padding-top: 25px;">
                Facebook Messenger
              </label>
              <label class="pull-right">
                <i v-bind:class="{'text-green': data.notifications[0].fb_messenger === 'ON', 'fa-toggle-on': data.notifications[0].fb_messenger === 'ON', 'text-danger': data.notifications[0].fb_messenger === 'OFF', 'fa-toggle-off': data.notifications[0].fb_messenger === 'OFF'}" v-on:click="updateNotification('fb_messenger', data.notifications[0].fb_messenger, data.notifications[0])" class="fa on-icon"></i>
              </label>
            </span>
          </span>
<!--           <span class="item">
            <span class="content">
              <label style="padding-top: 25px;">
                OTP (Coming Soon!)
              </label>
              <label class="pull-right">
                <i v-bind:class="{'text-green': data.notifications[0].otp === 'ON', 'fa-toggle-on': data.notifications[0].otp === 'ON', 'text-danger': data.notifications[0].otp === 'OFF', 'fa-toggle-off': data.notifications[0].otp === 'OFF'}" class="fa  on-icon"></i>
                v-on:click="updateNotification('otp', data.notifications[0].otp, data.notifications[0])"
              </label>
            </span>
          </span> -->
        </div>





        <div class="information-holder" v-if="pagerMenuSelectedIndex === 1">
          <span class="header-account-settings">
            <b>
              Personal Information <i class="fa fa-pencil pull-right action-link" v-on:click="editPersonalInformation()"></i>
            </b>
          </span>
          <span v-if="personalInfoFlag === false">
            <span class="item">
              <span class="content">
                <label>
                  <i class="fa fa-user"></i>
                  {{data.account_information.first_name + ' ' + data.account_information.middle_name + ' ' + data.account_information.last_name}}
                </label>
              </span>
            </span>
            <span class="item">
              <span class="content">
                <label>
                  <i class="fa fa-calendar"></i>
                  {{data.account_information.birth_date}}
                </label>
              </span>
            </span>
            <span class="item">
              <span class="content">
                <label>
                  <i class="fas fa-transgender"></i>
                  {{data.account_information.sex}}
                </label>
              </span>
            </span>
            <span class="item">
              <span class="content">
                <label>
                  <i class="fa fa-phone"></i>
                  {{data.account_information.cellular_number}}
                </label>
              </span>
            </span>
            <span class="item">
              <span class="content">
                <label>
                  <i class="fas fa-map-marker-alt"></i>
                  {{data.account_information.address}}
                </label>
              </span>
            </span>
          </span>
          <span v-else>
            <div class="input-group">
              <span class="input-group-addon">First Name</span>
              <input type="text" v-model="data.account_information.first_name" class="form-control">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Middle Name</span>
              <input type="text" v-model="data.account_information.middle_name" class="form-control">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Last Name</span>
              <input type="text" v-model="data.account_information.last_name" class="form-control">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Birthdate</span>
              <input type="date" v-model="data.account_information.birth_date" class="form-control">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Sex</span>
              <select class="form-control" v-model="data.account_information.sex">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Cellular Number</span>
              <input type="text" v-model="data.account_information.cellular_number" class="form-control">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Address</span>
              <input type="text" v-model="data.account_information.address" class="form-control">
            </div>
            <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="updatePersonalInformation()"><i class="fa fa-sync"></i> Update</button>
              <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="editPersonalInformation()"><i class="fa fa-ban"></i> Cancel</button>
          </span>
        </div>


        <div class="information-holder"  v-if="pagerMenuSelectedIndex === 2">
          <span class="header-account-settings">
            <b>
             Educational Background 
             <i class="fa fa-plus pull-right action-link" v-on:click="addBackgroundFlag()"></i>
            </b>
          </span>
          <span class="degree-holder" v-if="data.account_degree !== null && data.account_degree.length > 0" v-for="item, index in data.account_degree">
            <span v-if="item.edit_flag === false">
              <span class="item-half course">
                <label>{{item.course}}</label>
                <i class="fa fa-pencil text-primary pull-right action-link" v-on:click="edit(index)"></i>
              </span>
              <span class="item-half details">
                <label><i class="fa fa-university"></i>{{item.school.name}}</label>
              </span>
              <span class="item-half details">
              <label><i class="fas fa-id-card-alt"></i>{{item.school_student_code}}</label></span>
              <span class="item-half details">
                <label><i class="fa fa-calendar"></i>{{item.month_started + ' ' + item.year_started + ' - '}}</label>
                <label v-if="item.current_flag === true">{{'Current'}}</label>
                <label v-if="item.current_flag === false">{{item.month_end + ' ' + item.year_end}}</label>
              </span>
            </span>
            <span v-else>
              <div class="input-group">
                <span class="input-group-addon">Course</span>
                <input type="text" v-model="data.account_degree[index].course" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">School</span>
                <select v-model="data.account_degree[index].school_id" v-if="schools !== null" class="form-control">
                  <option v-bind:value="itemSchool.id" v-for="itemSchool, indexSchool in schools">{{itemSchool.name}}</option>
                </select>
                <!-- <input type="text" v-model="data.account_degree[index].school" class="form-control"> -->
              </div>
              <div class="input-group">
                <span class="input-group-addon">School ID</span>
                <input type="text" v-model="data.account_degree[index].school_student_code" class="form-control">
              </div>

              <div class="input-group">
                <span class="input-group-addon">Started</span>
                <select class="form-control" v-model="data.account_degree[index].year_started">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="data.account_degree[index].month_started">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <div class="input-group">
                <input type="checkbox" id="formCheck" v-model="data.account_degree[index].current_flag">
                <label class="form-check-label" for="formCheck">Still studying here?</label>
              </div>
              <div class="input-group" v-if="data.account_degree[index].current_flag === false">
                <span class="input-group-addon">Ended</span>
                <select class="form-control" v-model="data.account_degree[index].year_end">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="data.account_degree[index].month_end">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="updateDegree(index)"><i class="fa fa-sync"></i> Update</button>
              <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="edit(index)"><i class="fa fa-ban"></i> Cancel</button>
            </span>
          </span>
          <span v-if="addFlag === true">
              <label><b>Add New</b></label>
              <div class="input-group">
                <span class="input-group-addon">Course</span>
                <input type="text" v-model="newBackground.course" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">School</span>
                <select v-model="newBackground.school_id" v-if="schools !== null" class="form-control">
                  <option v-bind:value="itemSchool.id" v-for="itemSchool, indexSchool in schools">{{itemSchool.name}}</option>
                </select>
                <!-- <input type="text" v-model="data.account_degree[index].school" class="form-control"> -->
              </div>
              <div class="input-group">
                <span class="input-group-addon">School ID</span>
                <input type="text" v-model="newBackground.school_student_code" class="form-control">
              </div>

              <div class="input-group">
                <span class="input-group-addon">Started</span>
                <select class="form-control" v-model="newBackground.year_started">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="newBackground.month_started">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <div class="input-group">
                <input type="checkbox" id="formCheck" v-model="newBackground.current_flag">
                <label class="form-check-label" for="formCheck">Still studying here?</label>
              </div>
              <div class="input-group" v-if="newBackground.current_flag === false">
                <span class="input-group-addon">Ended</span>
                <select class="form-control" v-model="newBackground.year_end">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="newBackground.month_end">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="addDegree()"><i class="fa fa-plus"></i> Add</button>
              <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="addBackgroundFlag()"><i class="fa fa-ban"></i> Cancel</button>
            </span>
        </div>


        <div class="information-holder"  v-if="pagerMenuSelectedIndex === 3">
          <span class="header-account-settings">
            <b>
              Work Experiences 
              <i class="fa fa-plus pull-right action-link" v-on:click="addWorkExperienceFlag()"></i>
            </b>
          </span>
          <span class="degree-holder" v-if="data.account_work !== null && data.account_work.length > 0" v-for="item, index in data.account_work">
            <span v-if="item.edit_flag === false">
              <span class="item-half course">
                <label>{{item.position}}</label>
                <i class="fa fa-pencil text-primary pull-right action-link" v-on:click="editWork(index)"></i>
              </span>
              <span class="item-half details">
                <label><i class="fa fa-university"></i>{{item.company}}</label>
              </span>
              <span class="item-half details">
                <label><i class="fas fa-map-marker-alt"></i>{{item.company_address}}</label>
              </span>
              <span class="item-half details">
                <label><i class="fa fa-calendar"></i>{{item.month_started + ' ' + item.year_started + ' - '}}</label>
                <label v-if="item.current_flag === true">{{'Current'}}</label>
                <label v-if="item.current_flag === false">{{item.month_ended + ' ' + item.year_ended}}</label>
              </span>
            </span>
            <span v-else>
              <div class="input-group">
                <span class="input-group-addon">Position</span>
                <input type="text" v-model="data.account_work[index].position" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Company</span>
                <input type="text" v-model="data.account_work[index].company" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Company Address</span>
                <input type="text" v-model="data.account_work[index].company_address" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Started</span>
                <select class="form-control" v-model="data.account_work[index].year_started">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="data.account_work[index].month_started">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <div class="input-group">
                <input type="checkbox" id="formCheck" v-model="data.account_work[index].current_flag">
                <label class="form-check-label" for="formCheck">Still working here?</label>
              </div>
              <div class="input-group" v-if="data.account_work[index].current_flag === false">
                <span class="input-group-addon">Ended</span>
                <select class="form-control" v-model="data.account_work[index].year_ended">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="data.account_work[index].month_ended">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="updateWork(index)"><i class="fa fa-sync"></i> Update</button>
              <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="editWork(index)"><i class="fa fa-ban"></i> Cancel</button>
            </span>
          </span>
          <span v-if="addWorkFlag === true">
              <label><b>Add New</b></label>
              <div class="input-group">
                <span class="input-group-addon">Position</span>
                <input type="text" v-model="newWork.position" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Company</span>
                <input type="text" v-model="newWork.company" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Company Address</span>
                <input type="text" v-model="newWork.company_address" class="form-control">
              </div>
              <div class="input-group">
                <span class="input-group-addon">Started</span>
                <select class="form-control" v-model="newWork.year_started">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="newWork.month_started">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <div class="input-group">
                <input type="checkbox" id="formCheck" v-model="newWork.current_flag">
                <label class="form-check-label" for="formCheck">Still working here?</label>
              </div>
              <div class="input-group" v-if="newWork.current_flag === false">
                <span class="input-group-addon">Ended</span>
                <select class="form-control" v-model="newWork.year_ended">
                  <option v-for="i in 50" v-bind:value="currentYear - i">{{currentYear - i}}</option>
                </select>
                <select class="form-control" v-model="newWork.month_ended">
                  <option v-for="i in months" v-bind:value="i">{{i}}</option>
                </select>
              </div>
              <button class="btn btn-primary pull-right" style="margin-top:5px; margin-bottom:5px;" v-on:click="addWork()"><i class="fa fa-plus"></i> Add</button>
              <button class="btn btn-danger pull-right" style="margin-top:5px; margin-bottom:5px; margin-right: 5px;" v-on:click="addWorkExperienceFlag()"><i class="fa fa-ban"></i> Cancel</button>
            </span>
        </div>


        <div class="information-holder" v-if="pagerMenuSelectedIndex === 4">
          <span class="header-account-settings">
            <b>
                Account Information
            </b>
          </span>
          <span class="item">
            <span class="content">
              <label>
                <i class="fa fa-user"></i>
                @{{data.username}}
              </label>
            </span>
          </span>
          <span class="item">
            <span class="content">
              <label>
                <i class="fa fa-envelope"></i>
                {{data.email}}
              </label>
            </span>
          </span>
          <span class="item">
            <span class="content">
              <label>
                <i class="fa fa-calendar"></i>
                {{data.created_at}}
              </label>
            </span>
          </span>
        </div>


      </div>
    </div>

               <!-- Confirmation Modal -->
      <div class="modal" id="emailSendVerificationSuccessModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Email Verification Sent!</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              We've sent an verification email to your email address. Thank You!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
          </div>
        </div>
      </div>
  </div>
</template><script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveRequest()
    this.getSchools()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      pagerMenu: [
        {'title': 'Notifications', 'flag': true, 'tooltip_title': null},
        {'title': 'Personal Information', 'flag': false, 'tooltip_title': 'Provide your Personal Info'},
        {'title': 'Educational Background', 'flag': false, 'tooltip_title': 'Provide your Educational Background'},
        {'title': 'Work Experiences', 'flag': false, 'tooltip_title': 'Provide your Work Experiences'},
        {'title': 'Account Information', 'flag': false, 'tooltip_title': null}
      ],
      pagerMenuSelectedIndex: 0,
      data: null,
      prevDegreeEditIndex: null,
      prevWorkEditIndex: null,
      personalInfoFlag: false,
      addFlag: false,
      addWorkFlag: false,
      newBackground: {
        account_id: null,
        school_student_code: null,
        course: null,
        school_id: null,
        year_started: null,
        current_flag: false,
        month_started: null,
        year_end: null,
        month_end: null
      },
      newWork: {
        account_id: null,
        position: null,
        company: null,
        company_address: null,
        year_started: null,
        month_started: null,
        current_flag: false,
        year_ended: null,
        month_ended: null
      },
      months: [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
      ],
      file: null,
      schools: null,
      currentYear: 2020
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequest(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'id',
          'clause': '='
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        if(response.data !== null || response.data.length > 0){
          $('#loading').css({'display': 'none'})
          this.personalInfoFlag = (response.data[0].account_information === null)
          this.addFlag = (response.data[0].account_degree === null)
          this.addWorkFlag = (response.data[0].account_work === null)
          if(response.data[0].account_information === null){
            response.data[0].account_information = {
              account_id: response.data[0].id,
              first_name: null,
              last_name: null,
              middle_name: null,
              sex: null,
              birth_date: null,
              cellular_number: null,
              address: null
            }
          }else{
            $('#loading').css({'display': 'none'})
          }
          this.data = response.data[0]
        }else{
          this.data = null
        }
      }).done(() => {
        this.makeActive(0)
      })
    },
    makeActive(index){
      if(this.pagerMenuSelectedIndex === null){
        this.pagerMenu[index].flag = true
        this.pagerMenuSelectedIndex = index
      }else{
        if(this.pagerMenuSelectedIndex !== index){
          this.pagerMenu[index].flag = true
          this.pagerMenu[this.pagerMenuSelectedIndex].flag = false
          this.pagerMenuSelectedIndex = index
        }
      }
    },
    updateNotification(column, value, data){
      if(column === 'email'){
        if(this.user.status === 'NOT_VERIFIED'){
          // prompt to verify email address
        }else{
          let nValue = (value === 'ON') ? 'OFF' : 'ON'
          let parameter = this.getNotifParameter(column, nValue, data)
          this.updateNotificationRequest(parameter)
        }
      }else{
        let nValue = (value === 'ON') ? 'OFF' : 'ON'
        let parameter = this.getNotifParameter(column, nValue, data)
        this.updateNotificationRequest(parameter)
      }
    },
    updateNotificationRequest(parameter){
      this.APIRequest('notification_settings/update', parameter).then(response => {
        if(response.data === true){
          this.makeActive(0)
          this.retrieveRequest()
          this.makeActive(0)
        }
      })
    },
    getNotifParameter(column, value, data){
      switch(column){
        case 'email': data.email = value
          return data
        case 'sms': data.sms = value
          return data
        case 'fb_messenger': data.fb_messenger = value
          return data
        case 'otp': data.otp = value
          return data
      }
    },
    edit(index){
      this.addFlag = false
      if(this.prevDegreeEditIndex === null){
        this.data.account_degree[index].edit_flag = true
        this.prevDegreeEditIndex = index
      }else{
        if(this.prevDegreeEditIndex === index){
          this.data.account_degree[index].edit_flag = false
          this.prevDegreeEditIndex = null
        }else{
          this.data.account_degree[this.prevDegreeEditIndex].edit_flag = false
          this.data.account_degree[index].edit_flag = true
          this.prevDegreeEditIndex = index
        }
      }
    },
    editWork(index){
      this.addWorkFlag = false
      if(this.prevWorkEditIndex === null){
        this.data.account_work[index].edit_flag = true
        this.prevWorkEditIndex = index
      }else{
        if(this.prevWorkEditIndex === index){
          this.data.account_work[index].edit_flag = false
          this.prevWorkEditIndex = null
        }else{
          this.data.account_work[this.prevWorkEditIndex].edit_flag = false
          this.data.account_work[index].edit_flag = true
          this.prevWorkEditIndex = index
        }
      }
    },
    updateDegree(index){
      this.data.account_degree[index].current_flag = (this.data.account_degree[index].current_flag === true) ? 1 : 0
      this.APIRequest('account_degrees/update', this.data.account_degree[index]).then(response => {
        if(response.data === true){
          this.data.account_degree[index].edit_flag = false
          this.prevDegreeEditIndex = null
          this.retrieveRequest()
          this.makeActive(2)
        }
      })
    },
    updateWork(index){
      this.data.account_work[index].current_flag = (this.data.account_work[index].current_flag === true) ? 1 : 0
      this.APIRequest('account_work_experiences/update', this.data.account_work[index]).then(response => {
        if(response.data === true){
          this.data.account_work[index].edit_flag = false
          this.prevWorkEditIndex = null
          AUTH.checkAuthentication(null)
          this.retrieveRequest()
          this.makeActive(3)
        }
      })
    },
    addBackgroundFlag(){
      this.addFlag = !this.addFlag
    },
    addWorkExperienceFlag(){
      this.addWorkFlag = !this.addWorkFlag
    },
    addDegree(){
      this.newBackground.account_id = this.user.userID
      this.newBackground.current_flag = (this.newBackground.current_flag === true) ? 1 : 0
      this.APIRequest('account_degrees/create', this.newBackground).then(response => {
        AUTH.checkAuthentication(null)
        this.addBackgroundFlag()
        this.retrieveRequest()
        this.makeActive(2)
      })
    },
    addWork(){
      this.newWork.account_id = this.user.userID
      this.newWork.current_flag = (this.newWork.current_flag === true) ? 1 : 0
      this.APIRequest('account_work_experiences/create', this.newWork).then(response => {
        this.addWorkExperienceFlag()
        this.retrieveRequest()
        this.makeActive(3)
      })
    },
    editPersonalInformation(){
      this.personalInfoFlag = !this.personalInfoFlag
    },
    updatePersonalInformation(){
      this.APIRequest('account_informations/update', this.data.account_information).then(response => {
        if(response.data === true || response.data > 0){
          this.retrieveRequest()
          this.editPersonalInformation()
          this.makeActive(1)
        }
      })
    },
    addProfile(){
      $('input:file')[0].click()
    },
    setUpFileUpload(event){
      let files = event.target.files || event.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.file = files[0]
        this.createFile(files[0])
      }
    },
    createFile(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      this.updateProfile()
    },
    updateProfile(){
      if(this.file !== null){
        let formData = new FormData()
        formData.append('account_id', this.user.userID)
        formData.append('file', this.file)
        formData.append('profile_url', this.file.name)
        axios.post(this.config.BACKEND_URL + '/account_profiles/create', formData).then(response => {
          ROUTER.go('/account_settings')
        })
      }
    },
    getSchools(){
      let parameter = {
        'sort': {
          'name': 'ASC'
        }
      }
      this.APIRequest('schools/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.schools = response.data
        }else{
          this.schools = null
        }
      })
    },
    sendEmailVerification(){
      let parameter = {
        'condition': [
          {
            'column': 'id',
            'clause': '=',
            'value': this.user.userID
          }
        ]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('accounts/verify', parameter).then(response => {
        if(response.data.length > 0){
          $('#loading').css({'display': 'none'})
          $('#emailSendVerificationSuccessModal').modal('show')
        }
      })
    }
  }
}
</script>
<style>
  .acccount-settings-holder{
    width: 100%;
    min-height: 500px;
    overflow-y: hidden;
  }
  .account-header{
    width: 100%;
    height: 250px;
    background: #eee;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }
  .profile-image-settings{
    position: relative;
    padding-bottom: 50px;
  }
  .profile-image-settings i{
    font-size: 150px;
    color: #3f0050;
    padding-top: 50px;
  }
  .profile-image-settings img{
    height: 150px;
    width: 150px;
    border-radius: 50%;
    margin-top: 25px;
  }
  .profile-image-settings input{
    display: none;
  }
  .profile-image-settings:hover{
    cursor: pointer;
  }
 
  .account-name{
    height: 35px;
    width: 100%;
    float: left;
    font-weight: 600;
    padding-top: 10px;
    font-size: 18px;
    color: #028170;
  }

  .account-settings-pager{
    width: 100%;
    float: left;
    min-height: 10px;
    overflow-y: hidden;
  }

  .account-settings-pager .pager-menu{
    width: 25%;
    float: left;
    min-height: 50px;
    overflow-y: hidden;
    list-style: none;
    padding: 0px;
  }

  .account-settings-pager .pager-menu li{
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 10px;
  }
  .account-settings-pager .pager-menu .header{
    margin-top: 25px;
  }

  .account-settings-pager .pager-content{
    width: 74%;
    margin-left: 1%;
    float: left;
    min-height: 50px;
    overflow-y: hidden;
  }

  .account-settings-pager .pager-menu li:hover{
    cursor: pointer;
    background: #eaeaea;
  }
  .account-settings-pager .pager-menu i{
    color: red;
    visibility: true;
  }
  .bg-selected-menu{
    background: #eaeaea;
  }
  .account-settings-pager .pager-menu .header:hover{
    cursor: default;
    background: #fff;
  }
  .information-holder{
    width: 100%;
    float: left;
    min-height: 100px;
    margin-top: 35px;
    overflow-y: hidden;
    color: #565656;
  }

  .information-holder .header-account-settings{
    width: 100%;
    float: left;
  }

  .information-holder .header-account-settings b{
    padding-bottom: 10px;
    border-bottom: solid 1px #eaeaea;
    width: 100%;
    float: left;
  }

  .information-holder .item{
    width: 100%;
    float: left;
  }
  .item .content{
    height: 50px;
    float: left;
    width: 100%;
  }
  .content label{
    padding-top: 15px;
  }
  .content .on-icon{
    font-size: 40px;
  }

  .content .on-icon:hover{
    cursor: pointer;
  }

  .content label i, .item-half label i{
    padding-right: 10px;
    padding-left: 10px;
    color: #FCCD04;
  }
  .degree-holder{
    width: 100%;
    float: left;
    min-height: 100px;
    overflow-y: hidden;
    margin-top: 5px;
  }
  .degree-holder:hover{
    cursor: pointer;
    border-bottom: solid 1px #028170;
  }
  .degree-icon{
    width: 30%;
    float: left;
    min-height: 100px;
    text-align: center;
    overflow-y: hidden;
  }
  .degree-icon i{
    font-size: 16px;
  }
  .item-half{
    width: 100%;
    float: left;
    min-height: 20px;
    overflow-y: hidden;
  }
  .course{
    font-size: 14px;
    font-weight: 600;
  }
  .details{
    width: 98%;
    margin-left: 2%;
  }
  .date{
    width: 20%;
    margin-right: 1%;
    float: left;
  }
  .input-group{
    margin-top: 5px;
    font-size: 13px !important;
  }
  .input-group-addon{
    width: 125px;
    font-size: 13px !important;
    background: #3f0050;
    color: #fff;
  }
  .input-group-addon2{
    width: 150px;
  }
  @media screen (min-width: 200px), screen and (max-width: 991px){
    .information-holder{
      width: 100%;
    }
    .information-holder .header{
      font-size: 15px;
    }
   }
</style>
