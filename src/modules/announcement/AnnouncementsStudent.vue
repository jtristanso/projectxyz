<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Announcements</b></label>
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
<!--         <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div> -->
      </div>
      <div class="table-result">
          <span v-if="data.length > 0">
            <span v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)" >
              <span v-for="itemAnnouncements, indexAnnouncements in item.announcements" class="announcements-holder">
                <span class="header">
                  <span class="profile-picture">
                     <img v-bind:src="config.BACKEND_URL + itemAnnouncements.account_profile.profile_url" v-if="itemAnnouncements.account_profile !== null">
                     <i v-else class="fa fa-user-circle"></i>
                  </span>
                  <span class="profile-information" v-if="itemAnnouncements.account_information !== null">
                    <label>
                      {{itemAnnouncements.account_information.first_name + ' ' + itemAnnouncements.account_information.last_name}}
                    </label>
                  </span>
                </span>
                <span class="message">
                  {{itemAnnouncements.message}}
                </span>
                <span class="tag-courses">
                  <span class="item-course btn btn-warning">{{itemAnnouncements.course_details.code}}</span>
                </span>             
                <span class="date" >
                  <b>Posted on: </b>{{itemAnnouncements.created_at}} <!-- &nbsp;&nbsp;<i class="fa fa-eye action-link text-primary"></i> -->
                </span>
              </span>
            </span>
          </span>
          <span v-else>
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

      EDIT
    -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalView !== null">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Semester</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
            </span>
            <br v-if="errorMessage !== null">
            <div class="input-group">
              <span class="input-group-addon input-group-addon2">Message</span>
              <input type="text" class="form-control-textarea" v-model="modalView.message">
            </div>
            <div class="input-group">
              <span class="input-group-addon input-group-addon2">Courses</span>
              <input type="text" class="form-control" v-model="modalView.message">
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="updateRequest()" v-if="closeFag == false">update</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
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
            <div class="input-group">
              <span class="input-group-addon input-group-addon2">Message</span>
              <input type="text" class="form-control" v-model="message" placeholder="Type your messege here">
            </div>
            <div class="input-group">
              <span class="input-group-addon input-group-addon2">Tag Courses</span>
              <span class="form-control tag-courses">
                <button class="btn btn-default btn-inside" style="margin-right: 5px;" v-for="item, index in tagCourses" v-if="tagCourses.length > 0">{{item.description}} <i class="fa fa-trash text-danger action-link" style="padding: 0 5px 0 5px;" v-on:click="deleteTagCourse(index)"></i></button>
              </span>
            </div>
            <div class="input-group">
              <span class="input-group-addon input-group-addon2">Courses</span>
              <select class="form-control" v-model="selectedSemesterId" v-on:change="retrieveCourses()">
                <option v-for="item, index in semesters" v-bind:value="item.id"  v-if="semesters !== null">{{item.description}}</option>
              </select>
              <select class="form-control" v-model="selectedCourseId">
                <option v-for="item, index in courses" v-bind:value="item.id" v-if="courses !== null">{{item.description}}</option>
              </select>
              <i class="fa fa-plus btn btn-primary" style="height: 45px !important; padding-top: 15px;" v-on:click="addTagCourse()"></i>
            </div> 
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
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
  mounted(){
    this.retrieveRequest(true)
    this.retrieveSemesters()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      modalTitle: 'Add Announcements',
      data: [],
      semesters: null,
      selectedSemesterId: null,
      method: 'announcements',
      methodId: 'account_id',
      errorMessage: null,
      closeFag: false,
      message: null,
      courses: null,
      tagCourses: [],
      selectedCourseId: null,
      announcements: [],
      modalView: null,
      modalInput: {
        id: null,
        description: null,
        startDate: null,
        endDate: null,
        gradeSetting: null
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
      prevGradeSettingIndex: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveSemesters(){
      this.selectedCourseId = null
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.APIRequest('semesters/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.semesters = response.data
        }else{
          this.semesters = null
        }
      })
    },
    retrieveCourses(){
      this.selectedCourseId = null
      let parameter = {
        'condition': [{
          'value': this.selectedSemesterId,
          'column': 'semester_id',
          'clause': '='
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.courses = response.data
        }else{
          this.courses = null
        }
      })
    },
    retrieveRequest(flag){
      let parameter = {
        'account_id': this.user.userID
      }
      this.APIRequest(this.method + '/retrieve_student', parameter).then(response => {
        if(response.data.length === 0){
          this.announcements = []
        }else{
          this.announcements = response.data
        }
        this.data = this.announcements
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
      let parameter = {
        'account_id': this.user.userID,
        'message': this.message,
        'courses': this.tagCourses
      }
      this.APIRequest(this.method + '/create', parameter).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          this.retrieveRequest(false)
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest(this.method + '/delete', parameter).then(response => {
        if(response.data === null){
          // Error Message
        }else{
          this.retrieveRequest(true)
        }
      })
    },
    validation(){
      if(this.message === null || this.message === '' || this.tagCourses.length === 0){
        return false
      }else{
        return true
      }
    },
    editModalView(index){
      this.modalView = this.data[index]
    },
    updateRequest(){
      if(this.validationUpdate() === true){
        axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', this.modalView).then(response => {
          if(response.data.data === true){
            $('#editModal').modal('hide')
            this.retrieveRequest(false)
          }
        })
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    validationUpdate(){
      if(this.modalView.description === '' || this.modalView.start_date === null || this.modalView.end_date === null || this.modalView.grade_setting === null){
        return false
      }else{
        return true
      }
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
    addTagCourse(){
      let parameter = {
        'condition': [{
          'value': this.selectedCourseId,
          'column': 'id',
          'clause': '='
        }]
      }
      let course = null
      this.APIRequest('courses/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          course = response.data[0]
          if(this.tagCourses.length > 0){
            let flag = false
            for(let i = 0; i < this.tagCourses.length; i++){
              if(this.tagCourses[i].id === course.id){
                flag = true
              }
            }
            if(flag === false){
              this.tagCourses.push(course)
              this.errorMessage = null
            }else{
              this.errorMessage = 'This is course was added already!'
            }
          }else{
            this.tagCourses.push(course)
          }
        }
      })

    },
    deleteTagCourse(index){
      this.tagCourses.splice(index, 1)
    }
  }
}
</script>
<style>
.modal-title i{
  padding-right: 10px;
}

.form-control-textarea{
  min-height: 250px !important;
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
  height: 45px;
}
.btn-inside{
  float: left !important;
}
.tag-courses{
  min-height: 45px !important;
  overflow-y: hidden;
  float: left;
}
.announcements-holder{
  width: 49%;
  float: left;
  min-height: 10px;
  margin-right: 1%;
  border: solid 1px #ddd;
  margin-top: 10px;
  overflow-y: hidden;
}

.announcements-holder .header{
  height: 50px;
  float: left;
  width: 100%;
  background: #028170;
  color: #fff;
}
.header .profile-picture{
  width: 8%;
  float: left;
  height: 50px;
  margin-left: 5%;
}
.header .profile-picture img{
  width: 30px;
  height: 30px;
  border-radius: 15px;
  margin-top: 10px;
}
.header .profile-picture i{
  padding-top: 12px;
  color: #fff;
}
.header .profile-information{
  width: 87%;
  float: left;
  height: 40px;
}
.header .profile-information label{
  padding-top: 15px;
  font-weight: 600;
}
.announcements-holder .message{
  min-height: 10px;
  width: 90%;
  margin: 10px 5% 10px 5%;
  float: left;
  overflow-y: hidden;
  text-align: justify;
}

.announcements-holder .date{
  width: 90%;
  float: left;
  font-size: 12px;
  margin: 5px 5% 0 5%;
  color: #999;
  padding-bottom: 10px;
}
.announcements-holder .tag-courses{
  min-height: 10px;
  width: 90%;
  margin: 5px 5% 0 5%;
  float: left;
  overflow-y: hidden;
}
.item-course{
  margin-right: 10px;
}

.item-course:hover{
  cursor: default;
}
@media screen and (max-width: 991px){
  .announcements-holder{
    width: 98%;
    margin-left: 1%;
  }
  .header .profile-picture{
    width: 12%;
  }
  .header .profile-information{
    width: 83%;
  }
}
</style>
