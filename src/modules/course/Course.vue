<template>
  <div>
      <div class="module-header module-header-course">
        <div class="items-display pull-right">
          <label>My Courses</label>
          <select v-model="selectedId" v-on:change="updateActiveCourse()" v-if="data.length > 0">
            <option v-for="item, index in data" v-bind:value="item.id">{{item.code + ' - ' + item.d_time_start + '(' + item.days + ')'}}</option>
          </select>
          <label v-else class="text-danger">EMPTY</label>
        </div>
        <div class="add" v-if="user.selectedSemester !== null">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-plus"></i> New Course
          </button>
        </div>
        <div class="title" v-if="selected !== null">
          <i class="fa fa-code-fork" style="padding-right: 10px;"></i><label class="text-warning">{{selected.code}}</label>
          <br>
        </div>
        <div class="course-info"  v-if="selected !== null">
          <span>
            <i class="fa fa-info" style="padding-right: 10px;"></i><label>{{selected.description + ' (' + selected.units + ')'}}</label>
          </span>
          <span>
            <i class="fa fa-clock" style="padding-right: 10px;padding-left: 50px;"></i><label>{{selected.d_time_start + ' - ' + selected.d_time_end + ' (' + selected.days + ')'}}</label>
          </span>
          <span>
            <label style="padding-left: 50px;">Join Code: <label class="text-danger">{{selected.enrolment_code}}</label></label>
          </span>
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
      
      
      <div class="empty-courses" v-if="selected === null && selectedId === null && user.selectedSemester !== null">
        <i class="fas fa-hourglass-start text-danger"></i>
        <span class="description text-danger"><b>Looks like you have no courses yet!</b></span>
        <span style="font-size: 15px;">Click the button 'Add New Course' to get started.</span>
      </div>

      <div class="empty-courses" v-if="selected === null && selectedId === null && user.selectedSemester === null">
        <i class="fas fa-hourglass-start text-danger"></i>
        <span class="description text-danger"><b>To add Courses you must add Semester first.</b></span>
        <span style="font-size: 15px;">Go to Semesters then click New Semester to implement new semester.</span>
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
        <course-announcements :announcement="selected.announcement"></course-announcements>
      </div>

      <!--
      Enrolled
      -->

      <div class="menu-item-content text-center" v-if="menus[2].flag === true && selected !== null">
        <course-enrolles :enrollees="selected.enrollees"></course-enrolles>
      </div>

      <!--
      Tests
      -->

      <div class="menu-item-content" v-if="menus[3].flag === true && selected !== null">
        <test-list :id="selected.id"></test-list>
      </div>
      
      <!--
      Resources
      -->
      <div class="menu-item-content" v-if="menus[4].flag === true  && selected !== null">
        <course-resources :id="selected.id"></course-resources>
      </div>

      <!--
      Attendance
      -->
      <div class="menu-item-content" v-if="menus[5].flag === true  && selected !== null">
        <course-attendance :attendance="selected.attendance" :enrolmentCode="selected.enrolment_code"></course-attendance>
      </div>

      <!--
      Course Settings
      -->

      <div class="menu-item-content" v-if="menus[6].flag === true  && selected !== null">
        <course-settings :selected="selected"></course-settings>
      </div>
      <course-create></course-create>


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

  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'course-create': require('modules/course/Create.vue'),
    'test-list': require('modules/test/List.vue'),
    'course-discussions': require('modules/discussion/Discussion.vue'),
    'course-announcements': require('modules/announcement/List.vue'),
    'course-attendance': require('modules/attendance/ListTable.vue'),
    'course-enrolles': require('modules/course/Enrolled.vue'),
    'course-settings': require('modules/course/CourseSettings.vue'),
    'course-resources': require('modules/resource/List.vue')
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
      selected: null,
      selectedId: null,
      method: 'courses',
      methodId: 'account_id',
      errorMessage: null,
      closeFag: false,
      enrolmentCode: null,
      enrollees: null,
      confirmation: null,
      prevMenuIndex: 0,
      menus: [
        {'icon': 'fa-comments', 'title': 'Discussions', flag: true},
        {'icon': 'fa-paper-plane-o', 'title': 'Announcements', flag: false},
        {'icon': 'fa-users', 'title': 'Enrolled', flag: false},
        {'icon': 'fa-newspaper-o', 'title': 'Tests', flag: false},
        {'icon': 'fa-files-o', 'title': 'Resources', flag: false},
        {'icon': 'fa-clock-o', 'title': 'Attendance', flag: false},
        {'icon': 'fa-cog', 'title': 'Course Settings', flag: false}
      ],
      attendance: {
        date: null,
        time: null
      }
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
        }, {
          'value': this.user.selectedSemester.id,
          'clause': '=',
          'column': 'account_semester_id'
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length <= 0){
          this.data = []
          this.selectedId = null
          this.selected = null
        }else{
          this.data = response.data
          if(this.user.course !== null){
            this.completeSelected()
          }else{
            this.updateActiveCourse(this.data[0].id)
          }
        }
      })
    },
    completeSelected(){
      for(let i = 0; i < this.data.length; i++){
        if(this.data[i].id === this.user.course){
          this.selected = this.data[i]
          this.selectedId = this.selected.id
          break
        }
      }
    },
    updateActiveCourse(){
      let parameter = {
        id: this.user.userID,
        active_course: this.selectedId
      }
      this.APIRequest('courses/update_active_course', parameter).then(response => {
        ROUTER.go('/')
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
    testConfirmation(id){
      this.confirmation = {
        'message': 'Are you sure you want to delete this test?',
        'method': 'tests/delete',
        'parameter': {
          'id': id
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
    attendanceSummary(){
      AUTH.setReports('attendance', this.selected.id)
      let parameter = {
        'account_id': this.user.userID,
        'config': CONFIG,
        'description': 'attendance',
        'course_id': this.selected.id
      }
      this.APIRequest('gsheets/auth', parameter).then(response => {
        if(response.data !== null){
          // set token
        }else{
          window.location.href = response.redirect
        }
      })
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
    },
    editTopic(index){
      this.selected.topic[index].edit_flag = true
    },
    cancelEditTopic(index){
      this.selected.topic[index].edit_flag = false
    },
    deleteTopic(id){
      let parameter = {
        id: id
      }
      this.APIRequest('topics/delete', parameter).then(response => {
        this.retrieveRequest()
      })
    },
    updateTopic(item){
      let parameter = {
        id: item.id,
        text: item.text
      }
      this.APIRequest('topics/update', parameter).then(response => {
        if(response.data === true){
          this.retrieveRequest()
        }
      })
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
  width: 175px !important;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
  text-align: right !important;
}
.input-group-addon2{
  width: 150px;
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
  min-height: 350px;
  overflow-y: hidden;
}

.menu-item-content .item-content{
  float: left;
  width: 100%;
  min-height: 200px;
  overflow-y: hidden;
}

.item-content-half .item-header, .item-content .item-header{
  float:left;
  width: 100%;
  font-weight: 600;
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
  width: 49%;
  margin-right: 1%;
}
.profile-holder .information .information-header{
  width: 100%;
  float: left;
  background: #ddd;
  margin-top: 10px;
  margin-bottom: 10px;
}
.information .information-header h6{
  padding: 10px 10px 5px 10px;
}
.profile-holder .information .item{
  width: 100%;
  float: left;
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
  }

  

}
</style>
