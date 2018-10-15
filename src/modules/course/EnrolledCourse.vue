<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Enrolled Courses</b></label>
        </div>
        <div class="items-display pull-right">
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>
  <!--       <div class="3">
          <input type="text" name="search" class="table-search">
        </div> -->
        <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Enroll New Course</button>
        </div>
      </div>
      <div class="table-result">
          <span v-if="data.length > 0">
            <span v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)" class="enrolled-courses-holder">
              <span class="course-description" v-bind:class="{'bg-danger': (item.status_description !== null && item.status_description === 'DECLINED'), 'bg-warning': item.status_description !== null && item.status_description === 'PENDING'}">
                <h5>
                  {{item.course_details.code}}
                <label v-if="item.status_description !== null">{{' - ' + item.status_description}}
                    </label>
                <div class="dropdown pull-right">
                  <i class="fa fa-cog course-settings-link" style="padding-right: 10px;" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                  <div class="dropdown-menu dropdown-menu-enrolled-courses" aria-labelledby="dropdownMenuButton">
                    <span class="header">Options</span>
                    <span class="dropdown-item" v-on:click="redirect('/tests/fs/' + item.course_details.enrolment_code)" v-if="item.status_description === null"><i class="fa fa-clipboard"></i>View Tests</span>
                    <span class="dropdown-item" v-on:click="saveIdToBeDeleted(item.id)" data-toggle="modal" data-target="#confirmationModal"><i class="fa fa-ban" ></i>Delete</span>
                  </div>
                </div>
                </h5>
              </span>
              <span class="details-holder">
                <span class="item-header"><label>Course Details</label></span>
                <span class="course-details">
                  <i class="fas fa-info text-warning" style="padding-left: 5px;"></i>{{item.course_details.description}} - 
                  {{'(' + item.course_details.units + ')'}}
                </span>
                <span class="course-details">
                  <i class="fas fa-calendar-alt text-warning"></i>{{item.course_details.time_start + ' - ' + item.course_details.time_end + ' ' + item.course_details.days}}
                </span>
                <span class="item-header"><label>Teacher's Details</label></span>
                <span class="teacher-profile">
                  <span class="profile-picture">
                    <span v-if="item.teacher_account_profile !== null" class="profile-image">
                      <img v-bind:src="config.BACKEND_URL + item.teacher_account_profile[0].profile_url" width="100%" height="100%">
                    </span>
                    <span class="profile-image"  v-else>
                      <i class="fa fa-user-circle-o"></i>  
                    </span>
                    <span class="account-id text-center" v-if="item.teacher_account_information !== null">
                      <label>{{item.teacher_account_information[0].first_name + ' ' + item.teacher_account_information[0].last_name}}</label>
                    </span>
                  </span>
                  <span class="personal-information"  v-if="item.teacher_account_degree !== null">
                    <span class="account-item">
                      <b>{{item.teacher_account_degree[0].course}}</b>
                    </span>
                    <span class="account-item">
                      <i class="fas fa-university text-warning"></i> 
                      {{item.teacher_account_degree[0].school}}
                    </span>
                    <span class="account-item">
                      <i class="fas fa-map-marker-alt text-warning"></i> 
                      {{item.teacher_account_degree[0].address}}
                    </span>
                  </span>
                </span>
              </span>
              <span class="details-holder">
                <span class="item-header"><label>Passing Percentage</label></span>
                <span class="passing-percentage-details">
                  <label>Quizzes ({{item.grade_settings[0].passing_percentage_quizzes}}%)</label>
                  <div class="progress">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(item.grade_settings[0].passing_percentage_quizzes) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <label>Exams ({{item.grade_settings[0].passing_percentage_exams}}%)</label>
                  <div class="progress">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(item.grade_settings[0].passing_percentage_exams) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </span>
                <span class="item-header"><label>Grade Settings</label></span>
                <pie-chart 
                  :data="[
                  item.grade_settings[0].exams_rate, 
                  item.grade_settings[0].quizzes_rate, 
                  item.grade_settings[0].attendance_rate, 
                  item.grade_settings[0].projects_rate
                  ]"
                  :colors="chart.colors"
                  :labels="chart.labels" 
                  class="chart"></pie-chart>
              </span>
            </span>
          </span>
      </div>
      <div class="table-footer">
        <div class="items-total pull-left">
          <label>Showing <b>{{display.current}}</b> out of <b>{{display.total}}</b> entries</label>
        </div>
        <div class="items-pager">
          <ul class="pagination pull-right">
            <li class="page-item" v-bind:class="{'disabled': display.prevFlag === true}" v-on:click="pager(1, null)"><span class="page-link">Previous</span></li>
            <li class="page-item" v-for="i, index in display.pager" v-on:click="pager(2, index)"><span class="page-link" v-bind:class="'pager-active-' + index">{{index + 1}}</span></li>
            <li class="page-item" v-bind:class="{'disabled': display.nextFlag === true}" v-on:click="pager(3, null)"><span class="page-link">Next</span></li>
          </ul>
       </div>
      </div>


    <!-- Modal 

      ADD
    -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>{{modalTitle}}</h5>
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
              <span class="input-group-addon">Course Enrolment Code</span>
              <input type="text" class="form-control" v-model="enrolmentCode" placeholder="Enter 12 Digit Enrolment Code">
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Send Request</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Confirmation!</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="cancelDelete()">No</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="deleteRequest()">Yes</button>
          </div>
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
import Chart from 'chart.js'
export default {
  components: {
    'pie-chart': require('../../components/chart/Pie.vue')
  },
  mounted(){
    this.retrieveRequest(true)
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add New Course',
      config: CONFIG,
      data: [],
      method: 'enrolled_courses',
      methodId: 'account_id',
      errorMessage: null,
      closeFag: false,
      enrolmentCode: null,
      modalView: null,
      modalInput: {
        id: null,
        status: null
      },
      selectedTotalItems: null,
      totalDisplay: 5,
      currentTotalIndex: 0,
      displayIndexAdder: 0,
      display: {
        current: 0,
        total: 0,
        pager: null,
        prevFlag: true,
        nextFlag: true,
        currentPager: 1,
        pagerActive: null
      },
      prevGradeSettingIndex: null,
      chart: {
        colors: ['#8cd3ff', '#f99494', '#fac364', '#a1dbb1'],
        labels: ['Exams', 'Quizzes', 'Attendance', 'Projects']
      },
      toBeDeletedId: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequest(flag){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'clause': '=',
          'column': 'account_id'
        },
        {
          'value': 1,
          'clause': '=',
          'column': 'status'
        }]
      }
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        if(response.data === null){
          this.data = []
        }else{
          this.data = response.data
        }
      }).done(() => {
        if(flag === true){
          this.initDisplayer()
          setTimeout(() => {
            this.makeActive(0)
          }, 100)
        }
      })
    },
    initDisplayer(){
      this.display.total = this.data.length
      let pagerSize = 0
      this.display.currentPager = 0
      this.displayIndexAdder = 0
      if(this.display.total > this.totalDisplay){
        pagerSize = ((this.display.total % this.totalDisplay) !== 0) ? (parseInt(this.display.total / this.totalDisplay) + 1) : parseInt(this.display.total / this.totalDisplay)
        this.display.current = this.totalDisplay
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
        }
      }else{
        pagerSize = 1
        this.display.currentPager = 1
        this.display.current = this.display.total
        this.display.nextFlag = true
      }
      this.currentTotalIndex = 0
      this.display.pager = new Array(pagerSize)
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.createRequest()
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append(this.methodId, this.user.userID)
      formData.append('enrolment_code', this.enrolmentCode)
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
        if(response.data.data !== null || response.data.data > 0){
          $('#myModal').modal('hide')
          this.retrieveRequest(false)
        }else{
          this.errorMessage = response.data.message
        }
      })
    },
    saveIdToBeDeleted(id){
      this.toBeDeletedId = id
    },
    cancelDelete(){
      this.toBeDeletedId = null
    },
    deleteRequest(){
      let parameter = {
        id: this.toBeDeletedId
      }
      this.APIRequest(this.method + '/delete', parameter).then(response => {
        this.retrieveRequest(false)
      })
    },
    validation(){
      if(this.description === null || this.startDate === null || this.endDate === null){
        return false
      }else{
        return true
      }
    },
    editModalView(index){
      this.modalView = this.data[index]
      this.modalInput.id = this.modalView.id
    },
    updateRequest(){
      let formData = new FormData()
      formData.append('id', this.modalInput.id)
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.startDate !== null){
        formData.append('start_date', this.modalInput.startDate)
      }
      if(this.modalInput.endDate !== null){
        formData.append('end_date', this.modalInput.endDate)
      }
      if(this.modalInput.gradeSetting !== null){
        formData.append('grade_setting', this.modalInput.gradeSetting)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', formData).then(response => {
        if(response.data.data === true){
          $('#editModal').modal('hide')
          this.retrieveRequest(false)
        }
      })
    },
    filter(){
      this.currentTotalIndex = 0
      this.displayIndexAdder = 0
      this.data = this.data
      this.totalDisplay = this.selectedTotalItems
      this.display.pagerActive = null
      this.display.currentPager = 0
      if(this.display.total < this.selectedTotalItems){
        this.display.current = this.display.total
        this.display.pager = new Array(1)
        this.display.nextFlag = true
        this.display.prevFlag = true
      }else{
        this.display.current = this.selectedTotalItems
        this.display.nextFlag = false
        let pagerSize = 0
        pagerSize = ((this.display.total % this.selectedTotalItems) !== 0) ? (parseInt(this.display.total / this.selectedTotalItems) + 1) : parseInt(this.display.total / this.selectedTotalItems)
        this.display.current = this.selectedTotalItems
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
          this.display.prevFlag = true
        }
        this.display.pager = new Array(pagerSize)
      }
      this.makeActive(0)
    },
    pager(section, index){
      if(section === 1 && this.display.prevFlag === false){
        this.display.currentPager -= 1
        this.makeActive(this.display.currentPager)
      }else if(section === 3 && this.display.nextFlag === false){
        this.display.currentPager += 1
        this.makeActive(this.display.currentPager)
      }else if(section === 2){
        this.display.currentPager = index
        this.makeActive(index)
      }else{
        //
      }
      this.displayIndexAdder = this.display.currentPager
      if((this.display.pager.length - 1) === this.displayIndexAdder){
        this.display.current = this.display.total
      }else if(this.display.pager.length > this.displayIndexAdder && this.displayIndexAdder > 0){
        this.display.current = (this.totalDisplay * (this.displayIndexAdder + 1))
      }else{
        this.display.current = this.totalDisplay
      }
      this.setPrevFlag()
      this.setNextFlag()
    },
    setPrevFlag(){
      if(this.display.currentPager !== 0){
        this.display.prevFlag = false
      }else{
        this.display.prevFlag = true
      }
    },
    setNextFlag(){
      if(this.display.pager.length > (this.display.currentPager + 1)){
        this.display.nextFlag = false
      }else{
        this.display.nextFlag = true
      }
    },
    makeActive(index){
      $('.pager-active-' + index).css({'background': '#3f0050', 'color': 'white', 'border': 'solid 1px #3f0050'})
      if(this.display.pagerActive !== index && this.display.pagerActive !== null){
        $('.pager-active-' + this.display.pagerActive).css({'background': 'inherit', 'color': '#3f0050', 'border': 'solid 1px #ddd'})
        this.display.pagerActive = index
      }else if(this.display.pagerActive === null){
        this.display.pagerActive = index
      }
    },
    editGradeSettings(index){
      if(this.prevGradeSettingIndex === null){
        this.data[index].grade_flag = true
        this.prevGradeSettingIndex = index
      }else{
        if(this.prevGradeSettingIndex === index){
          this.data[index].grade_flag = false
          this.prevGradeSettingIndex = null
        }else{
          this.data[index].grade_flag = true
          this.data[this.prevGradeSettingIndex].grade_flag = false
          this.prevGradeSettingIndex = index
        }
      }
    },
    save(index){
      if(this.gradeValidation(index) === true){
        this.data[index].error_message = null
        let parameter = {
          'data': this.data[index].grade_settings_content[0],
          'semester_id': this.data[index].id
        }
        this.APIRequest('grade_settings/update', parameter).then(response => {
          if(response.data === true || response.data !== null){
            this.retrieveRequest(false)
          }else{
            //
          }
        })
      }else{
        this.data[index].error_message = 'General Settings must be equal to 100'
      }
    },
    gradeValidation(index){
      let quizzesRate = parseInt(this.data[index].grade_settings_content[0].quizzes_rate)
      let attendanceRate = parseInt(this.data[index].grade_settings_content[0].attendance_rate)
      let examsRate = parseInt(this.data[index].grade_settings_content[0].exams_rate)
      let projectsRate = parseInt(this.data[index].grade_settings_content[0].projects_rate)
      let totalGrade = quizzesRate + attendanceRate + projectsRate + examsRate
      if(totalGrade === 100){
        return true
      }else{
        return false
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
  background: #3f0050;
  color: #fff;
}
.input-group-addon2{
  width: 150px;
}

.enrolled-courses-holder{
  width: 100%;
  float: left;
  min-height: 200px;
  overflow-y: hidden;
  overflow-x: hidden;
  margin-bottom: 5px;
}
.course-description{
  width: 100%;
  float: left;
  height: 50px;
  background: #028170;
  color: #fff;
}
.course-description h5{
  padding-top: 15px;
  padding-left: 10px;
}
.details-holder{
  width: 50%;
  float: left;
  height: 100%;
}
.course-details, .action-buttons, .teacher-profile{
  width: 96%;
  float: left;
  height: 100%;
  margin-left: 4%;
}
.course-details i{
  padding-right: 10px;
  font-size: 14px;
}
.action-buttons{
  margin-top: 20px;
}
.passing-percentage-details {
  margin-left: 5%;
  margin-right: 5%;
  width: 90%;
  float: left;
}
.details-holder .chart{
  float: left;
  width: 100%;
  height: 200px;
}
.item-header{
  width: 96%;
  float: left;
  margin: 0 2% 0 2%;
}
.item-header label{
  padding-top: 15px;
  padding-bottom: 2px;
  text-transform: uppercase;
  font-weight: 550;
}

.course-settings-link:hover{
  cursor: pointer;
}


/* 

  DROPDOWN
  
*/
.course-description .dropdown-menu{
  min-height: 60px !important;
  overflow-y: hidden !important;
  width: 100px !important;
}
.course-description .dropdown-menu .dropdown-item{
  height: 30px !important;
  font-size: 12px !important;
  padding-top: 8px !important;
}

/*

  TEACHER PROFILE

*/
.profile-picture{
  width: 40%;
  float: left;
  height: 100%;
  text-align: center;
}
.profile-picture .profile-image{
  width: 100%;
  height:80%;
  float: left;
  padding-top: 10px;
}
.profile-image i{
  font-size: 100px;
  color: #3f0050;
}
.profile-image img{
  height: 100px;
  width: 100px;
  border-radius: 50%;
}
.personal-information{
  width: 60%;
  float: left;
  min-height: 125px;
  overflow-y: hidden;
  margin-bottom: 10px;
}

.account-id{
  min-height: 20px;
  width: 100%;
  float: left;
  margin-top: -15px;
  overflow-y: hidden;
  font-weight: 600;
}
.account-id label{
  background: #028170;
  color: #fff;
  padding: 5px 7px 5px 7px;
}
.account-item{
  width: 100%;
  float: left;
}

.dropdown-menu-enrolled-courses .dropdown-item:hover{
  cursor: pointer;
}
.dropdown-menu-enrolled-courses .header{
  font-size: 13px !important;
  width: 100% !important;
  float: left;
  text-align: center !important;
  padding-top: 5px;
  padding-bottom: 10px;
  border-bottom: solid 1px #ddd;
}
@media (max-width: 991px){
  .details-holder{
    width: 96%;
    float: left;
    margin: 0 2% 0 2%;
  }
}


.input-group{
  margin-top: 5px;
  font-size: 13px !important;
}
.input-group-addon{
  width: 175px;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
  text-align: right !important;
}
.input-group-addon2{
  width: 150px;
}


</style>
