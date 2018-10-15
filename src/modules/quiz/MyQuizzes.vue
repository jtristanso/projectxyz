<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Quizzes</b></label>
        </div>
        <div class="items-display pull-right">
          <label v-if="enrolledCourses.length > 0">Enrolled Courses</label>
          <select v-if="enrolledCourses.length > 0" v-on:change="filterCourse()" v-model="courseId">
            <option v-for="item, index in enrolledCourses"  v-bind:value="item.course_details.id" v-if="parseInt(item.status) === 1">{{item.course_details.code}}</option>
          </select>
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>
      </div>
      <div class="table-result">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr class="text-center">
              <td>Description</td>
              <td>Available On</td>
              <td>Settings</td>
              <td>My Score</td>
              <td>Status</td>
            </tr>
          </thead>
          <tbody v-if="data.length > 0">
            <tr v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
              <td>{{item.description}}</td>
              <td>
                {{item.available_date + ' @ ' + item.available_time}}
                <br>
                End after: <b class="text-danger">{{item.time_limit + ' Hour(s)'}}</b>
              </td>
              <td>
                Time Per Question: <b class="text-danger">{{item.time_per_question + ' Minute(s)'}}</b>
                <br>
                Questions Per Page: <b class="text-danger">{{item.questions_per_page}}</b>
              </td>
              <td class="text-center">
                <b>{{item.total_score + '/' + item.total_questions}}</b>
              </td>
              <td class="text-center">
                <button class="btn btn-primary" v-on:click="redirect('/take_quiz/' + item.code)" v-if="item.status_flag === 0">Take Now!</button>
                <label v-else-if="item.status_flag === 1" class="text-danger">Not Available</label>
                <label v-else-if="item.status_flag === 2" class="text-danger">Finished</label>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>   
              <td class="text-danger text-center" colspan="5">No Exams Available!</td>
            </tr>
          </tbody>
        </table>
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
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.checkParameter()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      parameter: this.$route.params.code,
      filterIndex: null,
      data: [],
      gradeSetting: 0,
      enrolledCourses: [],
      courseId: this.$route.params.code,
      courses: [],
      method: 'quizzes',
      methodId: 'course_id',
      errorMessage: null,
      closeFag: false,
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
    checkParameter(){
      this.retrieveRequestCourse()
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequestCourse(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.APIRequest('enrolled_courses/retrieve', parameter).then(response => {
        this.enrolledCourses = response.data
        if(this.parameter !== 'default'){
          for (var i = 0; i < this.enrolledCourses.length; i++) {
            if(this.parameter === this.enrolledCourses[i].course_details.enrolment_code){
              this.parameter = this.enrolledCourses[i].course_details.id
              this.createParameter(this.parameter)
              break
            }
          }
        }else{
          this.createParameter(this.enrolledCourses[0].course_details.id)
        }
      })
    },
    filterCourse(){
      this.createParameter(this.courseId)
      this.parameter = this.courseId
    },
    createParameter(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': this.methodId,
          'clause': '='
        }]
      }
      this.retrieveRequest(true, parameter)
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        if(response.data === null){
          this.courses = []
        }else{
          this.courses = response.data
        }
        this.data = this.courses
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
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest(this.metod + '/delete', parameter).then(response => {
        if(response.data === null){
          // Error Message
        }else{
          this.createParameter(this.parameter)
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
  width: 150px;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
}
.input-group-addon2{
  width: 150px;
}


</style>
