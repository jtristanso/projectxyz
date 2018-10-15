<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Tests</b></label>
        </div>
        <div class="items-display">
          <label v-if="semesters.length > 0">Semesters</label>
          <select v-if="semesters.length > 0" v-on:change="filterSemester()" v-model="semesterId">
            <option v-for="item, index in semesters"  v-bind:value="item.id">{{item.description}}</option>
          </select>
          <label v-if="courses.length > 0">Courses</label>
          <select v-if="courses.length > 0" v-on:change="filterCourses()" v-model="parameter">
            <option v-for="item, index in courses"  v-bind:value="item.id">{{item.description}}</option>
          </select>
          <label v-if="testTypes.length > 0">Display Only</label>
          <select v-if="testTypes.length > 0" v-on:change="filterTestTypes()" v-model="testTypeSelected">
            <option value="All">All</option>
            <option v-for="item, index in testTypes"  v-bind:value="item">{{item}}</option>
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
  <!--       <div class="3">
          <input type="text" name="search" class="table-search">
        </div> -->
        <div class="add" v-if="parameter !== 'default'">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div>
      </div>
      <div class="table-result">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr>
              <td>Description</td>
              <td>Available On</td>
              <td>Timer Settings</td>
              <td>Question Settings</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody v-if="data.length > 0">
            <tr v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
              <td>
                {{item.description}}
                <br>
                Type of Test: <b>{{item.type}}</b>
              </td>
              <td>
                {{item.available_date + ' at ' + item.available_time}}
                <br>
                End after: <b class="text-danger">{{item.time_limit}} Hour(s)</b>
              </td>
              <td>
                <span>
                    Timer Flag: 
                    <b v-if="parseInt(item.timer_flag) === 1" class="text-danger">OFF</b>
                    <b v-else class="text-danger">ON</b>
                  <br v-if="parseInt(item.timer_flag) === 2">
                    <label v-if="parseInt(item.timer_flag) === 2">
                    Time Per Question: <b class="text-danger">{{item.time_per_question}} Minute(s)</b>
                    </label>
                </span>
              </td>
              <td>
                <span>
                  Questions Per Page: <b class="text-danger">{{item.questions_per_page}}</b></label>
                  <br>
                  Orders Setting: <b class="text-danger">{{item.orders_setting}}</b></label>
                  <br>
                  Choices Setting: <b class="text-danger">{{item.choices_setting}}</b></label>
                </span>
              </td>
              <td class="text-center">
                <b class="text-primary action-link" v-on:click="redirect('/questions/ft/' + item.code)" data-hover="tooltip" data-placement="top" title="View Questions">{{item.total_questions}}</b> &nbsp;&nbsp;
                <i class="fa fa-pencil text-warning action-link" v-on:click="editModalView(index)" data-toggle="modal" data-target="#editModal" data-hover="tooltip" data-placement="top" title="Edit Quiz">
                </i>
                <i class="fa fa-trash text-danger action-link" v-on:click="deleteRequest(item.id)" data-hover="tooltip" data-placement="top" title="Delete Quiz"></i>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-danger text-center empty-table" colspan="5" data-toggle="modal" data-target="#myModal" v-if="parameter !== 'default'">Click to Add Quiz Now!</td>     
              <td class="text-danger text-center" colspan="5" v-else>Empty! Please Select the options above.</td>
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
     

    <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="modalView !== null">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Update Test</h5>
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
              <span class="input-group-addon">Type of Test</span>
              <select class="form-control" v-model="modalView.type">
                <option  v-for="item in testTypes" v-bind:value="item">{{item}}</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Description</span>
              <input type="text" class="form-control" v-model="modalView.description">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Available Date</span>
              <input type="date" class="form-control" v-model="modalView.available_date">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Available Time</span>
              <input type="time" class="form-control" v-model="modalView.available_time">
            </div><div class="input-group">
              <span class="input-group-addon">Time Limit</span>
              <select class="form-control" v-model="modalView.time_limit">
                <option v-for="i in 48" v-bind:value="i / 2">{{(i / 2)}}<label v-if="i % 2 === 0">.00</label><label v-else>0</label>
                  &nbsp;Hour(s)
                </option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Questions Per Page</span>
              <select class="form-control" v-model="modalView.questions_per_page">
                <option v-for="i in 5" v-bind:value="i">{{i}}</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Question Order Setting</span>
              <select class="form-control" v-model="modalView.orders_setting">
                <option value="SHUFFLE">SHUFFLE</option>
                <option value="IN ORDER">IN ORDER</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Answer Choices Setting</span>
              <select class="form-control" v-model="modalView.choices_setting">
                <option value="SHUFFLE">SHUFFLE</option>
                <option value="IN ORDER">IN ORDER</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Timer Flag</span>
              <select class="form-control" v-model="modalView.timer_flag">
                <option value="1">OFF</option>
                <option value="2">ON</option>
              </select>
            </div>
            <div class="input-group" v-if="modalView.timer_flag === 2 || modalView.timer_flag === '2'">
              <span class="input-group-addon">Time Per Question</span>
              <select class="form-control" v-model="modalView.time_per_question">
                <option v-for="i in 15" v-bind:value="i">{{i}} Minute(s)</option>
              </select>
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
              <span class="input-group-addon">Type of Test</span>
              <select class="form-control" v-model="newInput.type">
                <option  v-for="item in testTypes" v-bind:value="item">{{item}}</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Description</span>
              <input type="text" class="form-control" v-model="newInput.description" placeholder="Exam Title or Description">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Available Date</span>
              <input type="date" class="form-control" v-model="newInput.available_date">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Available Time</span>
              <input type="time" class="form-control" v-model="newInput.available_time">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Time Limit</span>
              <select class="form-control" v-model="newInput.time_limit">
                <option v-for="i in 48" v-bind:value="i / 2">{{(i / 2)}}<label v-if="i % 2 === 0">.00</label><label v-else>0</label>
                  &nbsp;Hour(s)
                </option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Questions Per Page</span>
              <select class="form-control" v-model="newInput.questions_per_page">
                <option v-for="i in 5" v-bind:value="i">{{i}}</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Question Order Setting</span>
              <select class="form-control" v-model="newInput.orders_setting">
                <option value="SHUFFLE">SHUFFLE</option>
                <option value="IN ORDER">IN ORDER</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Answer Choices Setting</span>
              <select class="form-control" v-model="newInput.choices_setting">
                <option value="SHUFFLE">SHUFFLE</option>
                <option value="IN ORDER">IN ORDER</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Timer Flag</span>
              <select class="form-control" v-model="newInput.timer_flag">
                <option value="1">OFF</option>
                <option value="2">ON</option>
              </select>
            </div>
            <div class="input-group" v-if="newInput.timer_flag === 2 || newInput.timer_flag === '2'">
              <span class="input-group-addon">Time Per Question</span>
              <select class="form-control" v-model="newInput.time_per_question">
                <option v-for="i in 15" v-bind:value="i">{{i}} Minute(s)</option>
              </select>
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
    this.checkParameter()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Test',
      parameter: this.$route.params.code,
      data: [],
      semesters: [],
      semesterId: null,
      courses: [],
      courseId: this.$route.params.code,
      method: 'tests',
      methodId: 'course_id',
      quizzes: [],
      errorMessage: null,
      closeFag: false,
      newInput: {
        course_id: null,
        type: null,
        description: null,
        available_date: null,
        available_time: null,
        time_limit: null,
        timer_flag: null,
        questions_per_page: 1,
        time_per_question: null,
        orders_setting: 'SHUFFLE',
        choices_setting: 'SHUFFLE'
      },
      modalView: null,
      modalInput: {
        id: null,
        type: null,
        description: null,
        available_date: null,
        available_time: null,
        time_limit: null,
        timer_flag: null,
        time_per_question: null,
        questions_per_page: 1,
        orders_setting: 'SHUFFLE',
        choices_setting: 'SHUFFLE'
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
      testTypes: ['Major', 'Minor', 'HomeWork'],
      testTypeSelected: null
    }
  },
  methods: {
    checkParameter(){
      if(this.parameter !== 'default'){
        this.retrieveCourseByCode()
        this.retrieveRequestSemester()
      }else{
        this.retrieveRequestSemester()
        this.defaultParameter()
      }
    },
    retrieveCourseByCode(){
      let parameter = {
        'condition': [{
          'value': this.$route.params.code,
          'column': 'enrolment_code',
          'clause': '='
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        this.createParameter(response.data[0].id)
        this.parameter = response.data[0].id
      })
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequestSemester(){
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
          this.retrieveCourse(this.semesters[0].id)
        }
      })
    },
    filterSemester(){
      this.retrieveCourse(this.semesterId)
    },
    retrieveCourse(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': 'semester_id',
          'clause': '='
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.courses = response.data
          this.createParameter(this.courses[0].id)
        }
      })
    },
    filterCourses(){
      this.createParameter(this.parameter)
    },
    filterTestTypes(){
      let selectedParameter = (this.testTypeSelected === 'All') ? '' : this.testTypeSelected
      let parameter = {
        'condition': [{
          'value': this.parameter,
          'column': this.methodId,
          'clause': '='
        }, {
          'value': selectedParameter + '%',
          'column': 'type',
          'clause': 'like'
        }]
      }
      this.retrieveRequest(true, parameter)
    },
    createParameter(value){
      let parameter = null
      if(this.testTypeSelected === null){
        parameter = {
          'condition': [{
            'value': value,
            'column': this.methodId,
            'clause': '='
          }]
        }
      }else{
        let selectedParameter = (this.testTypeSelected === 'ALL') ? '' : this.testTypeSelected
        parameter = {
          'condition': [{
            'value': value,
            'column': this.methodId,
            'clause': '='
          }, {
            'value': selectedParameter + '%',
            'column': 'type',
            'clause': 'like'
          }]
        }
      }
      this.retrieveRequest(true, parameter)
    },
    defaultParameter(){
      let param = null
      if(this.parameter !== 'default'){
        param = {
          'condition': [{
            'value': this.parameter,
            'clause': '=',
            'column': this.methodId
          }]
        }
        this.retrieveRequest(true, param)
      }else if(parseInt(this.parameter) > 0){
        alert('Incorrect Parameter Supplied')
      }
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/retrieve', parameter).then(response => {
        if(response.data === null){
          this.quizzes = []
        }else{
          this.quizzes = response.data
        }
        this.data = this.quizzes
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
      this.newInput.course_id = this.parameter
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', this.newInput).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          this.createParameter(this.parameter)
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
          this.createParameter(this.parameter)
        }
      })
    },
    validation(){
      this.newInput.course_id = this.parameter
      if(this.newInput.description === '' || this.newInput.description === null || this.newInput.available_date === null || this.newInput.available_time === null || this.newInput.time_limit === null || this.newInput.timer_flag === null || this.newInput.orders_setting === null || this.newInput.choices_setting === null || this.newInput.questions_per_page === null || this.newInput.questions_per_page === ''){
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
            this.createParameter(this.parameter)
          }
        })
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    validationUpdate(){
      if(this.modalView.description === '' || this.modalView.available_date === null || this.modalView.available_time === null || this.modalView.time_limit === null || this.modalView.timer_flag === null || this.modalView.orders_setting === null || this.modalView.choices_setting === null || this.modalView.questions_per_page === null || this.modalView.questions_per_page === ''){
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
