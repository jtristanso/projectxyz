<template>
  <div>
      <div class="module-header module-header-course">
        <div class="items-display pull-right">
          <label>My Courses</label>
          <select v-model="selectedIndex" v-on:change="selectCourse()" v-if="data.length > 0">
            <option v-for="item, index in data" v-bind:value="index">{{item.course_details.code}}</option>
          </select>
          <label v-else class="text-danger">NONE</label>
        </div>
        <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-plus"></i> Join Course
          </button>
        </div>
        <div class="title" v-if="selected !== null">
          <i class="fa fa-code-fork" style="padding-right: 10px;"></i><label class="text-warning">{{selected.course_details.code}}</label>
          <br>
        </div>
        <div class="course-info"  v-if="selected !== null">
          <i class="fa fa-info" style="padding-right: 10px;"></i><label>{{selected.course_details.description + ' (' + selected.course_details.units + ')'}}</label>
          <i class="fa fa-clock" style="padding-right: 10px;padding-left: 50px;"></i><label>{{selected.course_details.time_start + ' - ' + selected.course_details.time_end + ' (' + selected.course_details.days + ')'}}</label>
        </div>
      </div>

      <ul class="course-menu hide-on-mobile" v-if="selected !== null">
        <li class="menu-item" v-for="item, index in menus" v-bind:class="{'bg-selected-course-menu': item.flag === true}" v-on:click="setMenu(index)">
          <i class="fa text-primary" v-bind:class="item.icon"></i>{{item.title}}
        </li>
      </ul>

      <span class="hide-on-large course-mobile-menu">
        <ul class="pagination">
          <li class="page-item prev" v-on:click="mobileMenuPrev()">
            <span class="page-link"><i class="fa fa-chevron-left"></i> Previous</span>
          </li>
          <li class="page-item menu" v-for="item, index in 1">
            <span class="page-link">
              <i class="fa" v-bind:class="menus[index + (prevMenuIndex * 1)].icon"></i>
              {{menus[index  + (prevMenuIndex * 1)].title}}
            </span>
          </li>
          <li class="page-item next" v-on:click="mobileMenuNext()">
            <span class="page-link"><i class="fa fa-chevron-right"></i> Next</span>
          </li>
        </ul>
      </span>
      <div class="empty-courses" v-if="selected === null && selectedIndex === null">
        <i class="fas fa-hourglass-start text-danger"></i>
        <span class="description text-danger"><b>Looks like you have no courses yet!</b></span>
        <span style="font-size: 15px;">Click the button 'Enroll Course' to get started.</span>
      </div>

      <!--


      Discussions


      -->
      <div class="menu-item-content" v-if="menus[0].flag === true  && selected !== null">
        <course-discussions :payload="'course'" :payloadValue="selected.id"></course-discussions>
      </div>

      <!--

       
      Announcements


      -->
      <div class="menu-item-content" v-if="menus[1].flag === true  && selected !== null">
        <span class="post-container">
          <span class="post-content-holder" >
            <span class="post-item" v-for="item, index in selected.announcement"v-if="selected.announcement !== null">
              <span class="post-item-header">
                <img v-bind:src="config.BACKEND_URL + item.account.profile.profile_url" v-if="item.account.profile !== null">
                <i class="fa fa-user-circle" v-else></i>
                <label>{{item.account.username}}</label>
              </span>
              <span class="post-item-time">
                Posted on: {{item.created_at}}
              </span>
              <span class="post-item-content">
                {{item.message}}
              </span>
            </span>
          </span>
        </span>
        <span class="post-sidebar">
            
        </span>
      </div>

      <!--

       
      Tests


      -->

      <div class="menu-item-content" v-if="menus[2].flag === true && selected !== null">
        <table class="table table-bordered text-center">
          <thead>
            <td>Title</td>
            <td>Description</td>
            <td>Available On</td>
            <td>Score</td>
            <td>Action</td>
          </thead>
          <tbody>
            <tr v-for="item, index in selected.test" v-if="selected.test !== null">
              <td>{{item.type}}</td>
              <td v-if="item.description.length > 15">{{item.description.substr(0,15) + '...'}}</td>
              <td v-else>{{item.description}}</td>
              <td>
                {{item.available_date + '@' + item.available_time}}
                <br>
                End After: {{item.time_limit}} Hour(s)
              </td>
              <td>{{item.total_scores + '/' + item.total_questions}}</td>
              <td>
                <button class="btn btn-primary" v-on:click="redirect('/tests/take/fs/' + item.code)" v-if="item.take_flag === false">Take Now!</button>
                <label v-else class="text-success">Finished</label>
              </td>
            </tr>
            <tr v-if="selected.test === null">
              <td colspan="5" class="text-center text-danger">No Tests Posted!</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!--

       
      Resources


      -->
      <div class="menu-item-content" v-if="menus[3].flag === true  && selected !== null">
        <span class="resource-item" @click="this.window.open(item.url,'_blank')" target="_blank" v-for="item, index in selected.resource" v-if="selected.resource !== null">
          <span class="viewer" v-if="parseInt(item.resource_total_viewers) > 0">
            <i class="fa fa-eye text-primary"></i>{{item.resource_total_viewers}}
          </span>
          <span class="icon">
            <i v-bind:class="item.type"></i>
          </span>
          <span class="details">{{item.title}}</span>
        </span>
      </div>

      <!--

       
      Attendance


      -->
      <div class="menu-item-content" v-if="menus[4].flag === true  && selected !== null">
        <table class="table table-bordered">
          <thead class="text-center">
            <td>Date</td>
            <td>Time</td>
            <td>Remarks</td>
            <td>Status</td>
          </thead>
          <tbody>
            <tr v-for="item, index in selected.attendance" v-if="selected.attendance !== null">
              <td>{{item.date}}</td>
              <td>
                {{item.time}}
              </td>
              <td v-if="item.details !== null">
                {{item.details.remarks}}
              </td>
              <td v-else>
              </td>
              <td>
                <button v-if="item.details !==null"  v-bind:class="{'btn-primary': item.details.status === 'PRESENT', 'btn-warning': item.details.status === 'LATE', 'btn-danger': item.details.status === 'ABSENT'}" class="btn attendance-btn">{{item.details.status}}</button>
                <button v-else class="btn attendance-btn">
                      Not yet encoded.
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>


      <!--

       
      Grade Settings


      -->

      <div class="menu-item-content" v-if="menus[5].flag === true  && selected !== null">
        <ul class="course-settings-menu">
          <li v-for="item, index in courseSettingsMenu" v-bind:class="{'bg-selected-menu': item.flag === true}"  v-on:click="makeActive(index)">{{item.title}}
          </li>
        </ul>

        <div class="course-settings-content">



          <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 0">
            <pie-chart 
              :data="[
              selected.grade_settings[0].exams_rate, 
              selected.grade_settings[0].quizzes_rate, 
              selected.grade_settings[0].attendance_rate, 
              selected.grade_settings[0].projects_rate
              ]"
              :colors="chart.colors"
              :labels="chart.labels" 
              class="chart">
            </pie-chart>
          </span>


          <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 1">
            <span class="passing-percentage-details">
              <label>Quizzes ({{selected.grade_settings[0].passing_percentage_quizzes}}%)</label>
              <div class="progress">
                <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(selected.grade_settings[0].passing_percentage_quizzes) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <label>Exams ({{selected.grade_settings[0].passing_percentage_exams}}%)</label>
              <div class="progress">
                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(selected.grade_settings[0].passing_percentage_exams) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                </div>
              </div>
            </span>
          </span>



          <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 2">
            <span class="profile-holder" v-if="selected !== null && selected.teacher_account !== null">
              <span class="picture bg-primary">
                <img v-bind:src="config.BACKEND_URL + selected.teacher_account.profile.profile_url" v-if="selected.teacher_account.profile !== null">
                <i class="fa fa-user-circle" v-else style="font-size: 90px;padding-top: 10px;"></i>
                <label class="username">
                  {{selected.teacher_account.username}}
                </label>
              </span>
              <span class="information">
                <span class="information-header">
                  <h6>
                    Personal Information
                  </h6>
                </span>
                <label class="item">
                  <i class="fa fa-user"></i>
                  {{selected.teacher_account.username}}
                </label>
                <label class="item">
                  <i class="fa fa-info"></i>
                  {{selected.teacher_account.information.first_name + ' ' + selected.teacher_account.information.middle_name + ' ' + selected.teacher_account.information.last_name}}
                </label>
                <!-- <label class="item"><i class="fa fa-phone"></i>
                  {{selected.teacher_account.information.cellular_number}}
                </label> -->
                <label class="item"><i class="fa fa-envelope"></i>
                  {{selected.teacher_account.email}}
                </label>
              </span>
<!--               <span class="information" v-if="selected.teacher_account.degree !== null">
                <span class="information-header">
                  <h6>
                    Educational Background
                  </h6>
                </span>
                <span class="item" v-for="item, index in selected.teacher_account.degree">
                  <label class="item">
                    <i class="fa fa-trophy"></i>{{item.course}}
                  </label>
                  <label class="item">
                    <i class="fa fa-university"></i>{{item.school.name}}
                  </label>
                  <label class="item">
                    <i class="fa fa-calendar"></i>{{item.month_started + ' ' + item.year_started}} - {{item.month_end + ' ' + item.year_end}}
                  </label>
                </span>
              </span> -->
<!--               <span class="information" v-if="selected.teacher_account.work !== null">
                <span class="information-header">
                  <h6>
                    Work Experiences
                  </h6>
                </span>
                <span class="item" v-for="item, index in selected.teacher_account.work">
                  <label class="item">
                    <i class="fa fa-trophy"></i>{{item.position}}
                  </label>
                  <label class="item">
                    <i class="fa fa-university"></i>{{item.company}}
                  </label>
                   <label class="item">
                    <i class="fas fa-map-marker-alt"></i>{{item.company_address}}
                  </label>
                  <label class="item">
                    <i class="fa fa-calendar"></i>{{item.month_started + ' ' + item.year_started}} - {{item.month_ended + ' ' + item.year_ended}}
                  </label>
                </span>
              </span> -->
            </span>
          </span>
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
              <span class="input-group-addon">Course Join Code</span>
              <input type="text" class="form-control" v-model="enrolmentCode" placeholder="Enter 12 Digit Join Code">
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Request to Join</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal" v-if="confirmation !== null">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Confirmation!</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            {{confirmation.message}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="cancel()">No</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="confirm()">Yes</button>
          </div>
        </div>
      </div>
    </div>


        <!-- New Topic Modal -->
    <div class="modal fade" id="postTopicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Post New Topic</h5>
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
            <textarea class="form-control" id="postTopicTextArea" v-model="postTopicInput"></textarea>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="addTopic()" v-if="closeFag == false">Post</button>
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
    'pie-chart': require('../../components/chart/Pie.vue'),
    'course-discussions': require('modules/discussion/Discussion.vue')
  },
  mounted(){
    this.retrieveRequest(true)
    this.setMenu(0)
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Join Course',
      config: CONFIG,
      data: [],
      selected: null,
      selectedIndex: null,
      method: 'enrolled_courses',
      methodId: 'account_id',
      errorMessage: null,
      closeFag: false,
      enrolmentCode: null,
      chart: {
        colors: ['#8cd3ff', '#f99494', '#fac364', '#a1dbb1'],
        labels: ['Exams', 'Quizzes', 'Attendance', 'Projects']
      },
      confirmation: null,
      prevMenuIndex: 0,
      menus: [
        {'icon': 'fa-comments', 'title': 'Discussions', flag: true},
        {'icon': 'fa-paper-plane-o', 'title': 'Announcements', flag: false},
        {'icon': 'fa-newspaper-o', 'title': 'Tests', flag: false},
        {'icon': 'fa-files-o', 'title': 'Resources', flag: false},
        {'icon': 'fa-clock-o', 'title': 'Attendance', flag: false},
        {'icon': 'fa-cog', 'title': 'Course Settings', flag: false}
      ],
      courseSettingsMenu: [
        {'title': 'Grade Details', 'flag': true},
        {'title': 'Tests Details', 'flag': false},
        {'title': 'Course Details', 'flag': false}
      ],
      courseSettingsMenuSelectedIndex: 0
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
      $('#loading').css({'display': 'block'})
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length < null){
          this.data = []
          this.selectedIndex = null
          this.selected = null
        }else{
          this.data = response.data
          this.selected = response.data[0]
          this.selectedIndex = 0
        }
      })
    },
    selectCourse(){
      this.selected = this.data[this.selectedIndex]
    },
    submit(){
      if(this.enrolmentCode !== null || this.enrolmentCode !== ''){
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
    statusConfirmation(id, status){
      this.confirmation = {
        'message': (status === 'OPEN') ? 'Are you sure you want to re-open the topic status?' : 'Are you sure you want to close the topic status?',
        'method': 'topics/update',
        'parameter': {
          'id': id,
          'status': status
        }
      }
    },
    cancel(){
      this.confirmation = null
    },
    confirm(){
      this.APIRequest(this.confirmation.method, this.confirmation.parameter).then(response => {
        this.retrieveRequest(false)
      })
    },
    setMenu(index){
      if(this.prevMenuIndex === index){
        //
      }else{
        this.menus[this.prevMenuIndex].flag = false
        this.menus[index].flag = true
        this.prevMenuIndex = index
      }
    },
    makeActive(index){
      if(this.courseSettingsMenuSelectedIndex === null){
        this.courseSettingsMenu[index].flag = true
        this.courseSettingsMenuSelectedIndex = index
      }else{
        if(this.courseSettingsMenuSelectedIndex !== index){
          this.courseSettingsMenu[index].flag = true
          this.courseSettingsMenu[this.courseSettingsMenuSelectedIndex].flag = false
          this.courseSettingsMenuSelectedIndex = index
        }
      }
    },
    mobileMenuNext(){
      if(this.prevMenuIndex < (this.menus.length - 1)){
        this.menus[this.prevMenuIndex].flag = false
        this.prevMenuIndex++
        this.menus[this.prevMenuIndex].flag = true
      }
    },
    mobileMenuPrev(){
      if(this.prevMenuIndex > 0){
        this.menus[this.prevMenuIndex].flag = false
        this.prevMenuIndex--
        this.menus[this.prevMenuIndex].flag = true
      }
    }
  }
}
</script>
<style scoped>
.module-header-course .course-info{
  width: 100%;
  float: left;
}
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
  width: 175px;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
  text-align: right !important;
}
.input-group-addon2{
  width: 150px;
}

.course-menu{
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  border-bottom: solid 1px #ddd;
  list-style: none;
  padding: 0px !important;
}

.course-menu .bg-selected-course-menu{
  border-bottom: solid 2px #3f0050;
}

.course-menu .menu-item-first{
  float: left;
  padding: 15px 20px 15px 0px;
}

.course-menu .menu-item{
  float: left;
  padding: 15px 40px 15px 0px;
}

.course-menu li i{
  padding-right: 5px;
}

.course-menu li:hover{
  cursor: pointer;
}

.menu-item-content{
  width: 90%;
  float: left;
  min-height: 200px;
  overflow-y: hidden;
  margin: 0 5% 0 5%;
}
.item-content-half{
  float: left;
  width: 49%;
  margin-right: 1%;
  min-height: 200px;
  overflow-y: hidden;
}

.menu-item-content .item-content{
  float: left;
  width: 100%;
  min-height: 200px;
  overflow-y: hidden;
  padding-top: 10px;
  padding-right: 10px;
}

.item-content-half .item-header{
  float:left;
  width: 91.8%;
  font-weight: bold;
  padding-top: 9px;
  padding-bottom: 10px;
  padding-right: 10px;
  padding-left: 10px;
  border-style: ridge;
  border-width: thin;
  border-color: #c7c9cc;
  background: #ffffff;
  height:40px;
  color: #494949;
  font-family: sans-serif;
  font-size: 15px;
  margin-bottom: 10px;
}
.item-content .item-header{
  float:left;
  width: 95.5%;
  font-weight: bold;
  padding-top: 9px;
  padding-bottom: 10px;
  padding-right: 10px;
  padding-left: 10px;
  border-style: ridge;
  border-width: thin;
  border-color: #c7c9cc;
  background: #ffffff;
  height:40px;
  color: #494949;
  font-family: sans-serif;
  font-size: 15px;
  margin-bottom: 10px;
}
.item-content .profile-holder{
  float: left;
  width: 100%;
}

.item-content .profile-holder .picture{
  width: 100%;
  float: left;
  text-align: center;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  height: 150px;
}
.profile-holder .picture img{
  height: 100px;
  width: 100px;
  border-radius: 50%;
  margin-top: 10px;
}
.profile-holder .picture .username{
  height: 40px;
  width: 100%;
  float: left;
  margin-top: 10px;
}

.profile-holder .information{
  float: left;
  width: 100%;
}
.profile-holder .information .information-header{
  width: 100%;
  float: left;
  margin-top: 10px;
}
.information .information-header{
    height: 40px;
    width: 100%;
    float: left;
    color: #000;
}
.profile-holder .information .item{
  width: 100%;
  float: left;

  /*height: 30px;*/
}
.profile-holder .information .item .fas{
  color: #FCCD04;
  height: 30px;
  padding-right: 10px;
}
.profile-holder .information .item .fa{
  color: #FCCD04;
  height: 30px;
  padding-right: 10px;
}

.item-content-half .chart{
  float: left;
  width: 100%;
  height: 300px;
}
.passing-percentage-details {
  margin-left: 5%;
  margin-right: 5%;
  width: 90%;
  float: left;
}


/*

    COMMENT

*/
#postTopicTextArea{
  max-height: 200px;
  height: 200px !important;
}

.post-container{
  width: 100%;
  float: left;
}
.post-content-holder{
  width: 60%;
  float: left;
  margin-right: 5%;
}
.post-item{
  width: 100%;
  float: left;
  margin-top: 20px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border: solid 1px #ddd;
}

.post-item-header, .post-item-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}

.post-item-header{
  margin-top: 10px;
}

.post-item-header .more-options:hover{
  cursor: pointer;
}

.dropdown-more-options{
  min-height: 50px !important;
  padding-top: 0px !important;
  padding-bottom: 0px !important;
  overflow-y: hidden;
}

.dropdown-more-options .dropdown-item{
  font-size: 13px !important;
  padding-top: 5px !important;
  padding-bottom: 5px !important;
  height: 30px !important;
}

.dropdown-more-options .dropdown-item:hover{
  cursor: pointer;
}

.more-option-title{
  font-weight: 700;
}

.dropdown-more-options .more-option-title:hover{
  cursor: default;
}

.post-item-header img, .comment-header img, .reply-header img{
  height: 30px;
  width: 30px;
  border-radius: 50%;
  margin-right: 10px;
}
.post-item-header i, .comment-header i, .reply-header i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
}
.post-item-header label, .comment-header .username, .reply-header .username{
  padding-top: 5px;
  font-weight: 550;
  color: #3f0050;
}
.post-item-time{
  width: 96%;
  float: left;
  font-size: 12px;
  color: #999;
  margin: 0 2% 0 2%;
}
.post-item-content{
  width: 96%;
  float: left;
  min-height: 40px;
  overflow-y: hidden;
  margin: 0 2% 15px 2%;
}
.post-item-content textarea{
  float: left;
  width: 100%;
  border: none;
  min-height: 70px;
}

.post-item-footer .footer-menu{
  padding: 0px !important;
  list-style: none;
  width: 100%;
  float: left;
  border-top: solid 1px #ddd;
}
.post-item-footer .footer-menu li{
  float: left;
  width: 50%;
  padding-top: 10px;
  padding-bottom: 10px;
}
.post-item-footer .footer-menu li:hover{
  cursor: pointer;
}
.post-item-footer .footer-menu li i{
  font-size: 16px;
  padding-right: 10px;
}

.post-item-comment{
  width: 100%;
  float: left;
  min-height: 1px;
  overflow-y: hidden;
}
.post-item-comment .comment-item{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.comment-item .comment-content{
  width: 86%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-left: 7%;
  margin-right: 7%;
}
.post-item-comment .new-comment{
  width: 100%;
  float: right;
  height: 50px;
}
.new-comment img{
  height: 30px;
  width: 30px;
  border-radius: 50%;
  margin-right: 10px;
  float: left;
  margin-top: 5px;
  margin-left: 10px;
}
.new-comment i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
  float: left;
}
.new-comment input{
  width: 90%;
  float: left;
}
.comment-date, .reply-date{
  font-size: 12px !important;
  color: #999;
}
.comment-header, .comment-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}
.comment-footer .footer-menu{
  padding: 0px !important;
  list-style: none;
  width: 90%;
  float: left;
  margin-left: 5%;
}
.comment-footer .footer-menu li{
  float: left;
  width: 50%;
  padding-top: 10px;
  padding-bottom: 10px;
}
.comment-footer .footer-menu li:hover{
  cursor: pointer;
}
.comment .footer-menu li i{
  font-size: 16px;
  padding-right: 10px;
}

.reply-header{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}
.comment-item-reply{
  width: 90%;
  margin-left: 10%;
  float: left;
}
.comment-item-reply .reply-item{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}
.comment-item-reply .reply-content{
  width: 86%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-left: 7%;
  margin-right: 7%;
  margin-bottom: 10px;
}
.comment-item-reply .new-reply{
  width: 100%;
  float: left;
  height: 50px;
}

.new-reply img{
  height: 30px;
  width: 30px;
  border-radius: 50%;
  margin-right: 10px;
  float: left;
  margin-top: 5px;
  margin-left: 10px;
}
.new-reply i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
  float: left;
}
.new-reply input{
  width: 90%;
  float: left;
}



.post-sidebar{
  width: 35%;
  float: left;
}

.resource-item{
  width: 19%;
  float: left;
  margin-right: 1%;
  border-radius: 2px;
  border: solid 1px #ddd;
  height: 250px;
}
.resource-item:hover{
  cursor: pointer;
}
.resource-item .viewer{
  float: left;
  height: 20px;
  width: 90%;
  text-align: right;
  margin: 0 5% 0 5%;
  font-size: 10px;
}
.resource-item .viewer i{
  padding-right: 5px;
  font-size: 13px;
  padding-top: 2px;
}
.resource-item .icon{
  text-align: center;
  font-size: 100px;
  float: left;
  height: 200px;
  width: 90%;
  margin: 0 5% 0 5%;
}
.resource-item .details{
  text-align: center;
  float: left;
  height: 30px;
  width: 90%;
  margin: 0 5% 0 5%;
}
.attendance-btn{
  pointer-events: none;
}
.empty-courses{
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
  float: left;
  min-height: 450px;
  overflow-y: hidden;
  text-align: center;
  border: solid 1px #ddd;
}

.empty-courses i{
  font-size: 100px;
  padding-top: 150px;
}
.empty-courses span{
  width: 100%;
  float: left;
}

.empty-courses .description{
  font-size: 24px;
}

.course-settings-menu{
  width: 25%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  list-style: none;
  padding: 0px;
}

.course-settings-menu li{
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 10px;
}
.course-settings-menu li:hover{
  cursor: pointer;
  background: #eaeaea;
}

.course-settings-content{
  width: 74%;
  margin-left: 1%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

.bg-selected-menu{
  background: #eaeaea;
}

.hide-on-large{
  display: none;
}

.course-mobile-menu{
  width: 100%;
  float: left;
  text-align: center;
}

.course-mobile-menu .pagination .page-item:hover{
  cursor:pointer;
}

.course-mobile-menu .pagination .next, .course-mobile-menu .pagination .prev{
  width: 30%;
}

.course-mobile-menu .pagination .menu{
  width: 40%;
}


@media (max-width: 991px){
  .module-header-course .items-display{
    width: 100% !important;
  }

  .module-header-course .add{
    width: 100% !important;
  }

  .course-info span{
    width: 100%;
    float: left;
  }

  .course-info span i, .course-info span label{
    padding-left: 0px !important;
  }

  .item-content-half{
    width: 96%;
    float: left;
    margin: 0 2% 0 2%;
  }

  .post-content-holder{
    width: 100%;
  }

  .post-sidebar{
    display: none;
  }

  .hide-on-mobile{
    display: none;
  }

  .hide-on-large{
    display: block;
  }

  .resource-item{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    height: 300px;
    margin-bottom: 10px;
  }

  .post-item-comment .new-comment input{
    width: 80%;
  }

  .comment-item-reply .new-reply input{
    width: 80%;
  }
}

</style>
