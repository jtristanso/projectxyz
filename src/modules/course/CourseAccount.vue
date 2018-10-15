<template>
  <div>
      <div class="module-header">
        <div class="title text-warning" style="width: 100%">
          <label v-on:click="redirect('/' + headerMethod + '/default')" class="text-underline"><b>{{headerTitle}}</b></label><label v-if="header !== null">/{{header.description}}</label>
        </div>
        <div class="items-display pull-right" style="width: 90%">
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="75">75</option>
            <option value="100">100</option>
          </select>
        </div>
  <!--       <div class="3">
          <input type="text" name="search" class="table-search">
        </div> -->
<!--         <div class="add" style="width: 10%">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div> -->
      </div>
      <div class="table-result" v-if="data.length > 0">
        <span class="account-info-holder" v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)">
          <span class="profile-picture">
            <span v-if="item.account_profile !== null" class="profile-image">
              <img v-bind:src="config.BACKEND_URL + item.account_profile.profile_url" width="100%" height="100%">
            </span>
            <span class="profile-image"  v-else>
              <i class="fa fa-user-circle-o"></i>  
            </span>
            <span class="account-id text-center" v-if="item.account_degree !== null">
              <label>ID: {{item.account_degree.school_student_code}}</label>
            </span>
          </span>
          <span class="personal-information">
            <span class="account-name" v-if="item.account_information !== null">
              {{item.account_information.first_name + ' ' + item.account_information.last_name}}
            </span>
            <span class="account-item" v-if="item.account_degree !== null">{{item.account_degree.course}}</span>
            <span class="account-item" v-if="item.account_degree !== null"><i class="fas fa-university text-warning"></i> {{item.account_degree.school}}</span>
            <span class="account-item" v-if="item.account_degree !== null"><i class="fas fa-map-marker-alt text-warning"></i> {{item.account_degree.address}}</span>
            <span class="account-item" style="margin-top:5px;" v-if="parseInt(item.status) === 0 || data[index].edit_status_flag === true">
              <button class="btn btn-primary" v-on:click="accept(index)"><i class="fa fa-check"></i> Confirm</button>
              <button class="btn btn-danger" v-on:click="decline(index)"><i class="fa fa-ban"></i> Decline</button>
            </span>
            <span class="account-item" v-else>
              <i class="fa fa-cog text-primary action-link" data-hover="tooltip" data-placement="top" title="Edit Status" v-on:click="editStatus(index)"></i>
              <label v-if="parseInt(item.status) === 1"> Enrolled</label>
              <label v-if="parseInt(item.status) === 2" v-bind:class="{'text-danger': parseInt(item.status) === 2}"> Declined</label>
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
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveHeader()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      modalTitle: 'Add Question',
      parameter: this.$route.params.code,
      code: this.$route.params.code,
      data: [],
      header: null,
      headerTitle: null,
      headerMethod: null,
      method: 'courses',
      methodId: 'id',
      errorMessage: null,
      closeFag: false,
      type: null,
      question: null,
      prevEditIndex: null,
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
      editStatusPrevIndex: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveHeader(value){
      let method = window.location.href.split('/')[4]
      this.headerMethod = 'courses'
      this.headerTitle = 'Enrolled Accounts'
      this.methodId = 'quiz_id'
      let parameter = {
        'condition': [{
          'value': this.parameter,
          'column': 'enrolment_code',
          'clause': '='
        }]
      }
      this.APIRequest(this.headerMethod + '/retrieve', parameter).then(response => {
        this.header = response.data[0]
        this.parameter = response.data[0].id
        this.defaultParameter()
      })
    },
    filterCourses(){
      this.createParameter(this.parameter)
    },
    createParameter(value){
      let parameter = {
        'course_id': this.parameter
      }
      this.retrieveRequest(true, parameter)
    },
    defaultParameter(){
      let param = null
      if(this.parameter !== 'default'){
        param = {
          'course_id': this.parameter
        }
        this.retrieveRequest(true, param)
      }else if(parseInt(this.parameter) > 0){
        alert('Incorrect Parameter Supplied')
      }
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/accounts', parameter).then(response => {
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
      if(this.$children.length > 0){
        this.$children[0].getAnswer()
        if(this.validation() === true){
          this.errorMessage = null
          this.createRequest()
        }else{
          if(this.$children[0].answer === null){
            this.errorMessage = this.$children[0].errorMessage
          }else if(this.$children[0].validation() === false){
            this.errorMessage = this.$children[0].errorMessage
          }else{
            this.errorMessage = 'Please fill in all required fields.'
          }
        }
      }else{
        this.errorMessage = 'Please SELECT the type of the  question'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append(this.methodId, this.parameter)
      formData.append('question', this.question)
      formData.append('type', this.type)
      formData.append('answer', this.$children[0].answer)
      let newId = null
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          newId = response.data.data
          if(this.type === 'multiple_choice' || this.type === 'multiple_answers'){
            this.createRequestQuestionOptions(newId)
          }else{
            this.createParameter(this.parameter)
          }
        }else{
          this.errorMessage = response.error.message
        }
      })
    },
    createRequestQuestionOptions(newId){
      let parameter = {
        'question_id': newId,
        'options': this.$children[0].data
      }
      this.APIRequest('question_options/create', parameter).then(response => {
        if(response.data.data !== null){
          this.$children[0].message = 'Successfully Added!'
          this.$children[0].errorMessage = null
        }else{
          this.$children[0].message = null
          this.$children[0].errorMessage = response.data.message
        }
      }).done(() => {
        this.createParameter(this.parameter)
      })
    },
    deleteRequest(index){
      let parameter = {
        id: index,
        'value': this.parameter,
        'column': this.methodId
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
      if(this.question === null || this.type === null || this.$children[0].answer === null || this.$children[0].validation() === false){
        return false
      }else{
        return true
      }
    },
    updateRequest(){
      let formData = new FormData()
      formData.append('id', this.modalInput.id)
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.type !== null){
        formData.append('type', this.modalInput.type)
      }
      if(this.modalInput.start !== null){
        formData.append('start', this.modalInput.start)
      }
      if(this.modalInput.end !== null){
        formData.append('end', this.modalInput.end)
      }
      if(this.modalInput.timer !== null){
        formData.append('timer', this.modalInput.timer)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', formData).then(response => {
        if(response.data.data === true){
          $('#editModal').modal('hide')
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
    },
    accept(index){
      let parameter = {
        'id': this.data[index].id,
        'status': 1
      }
      this.updateRequestEnrolledAccount(parameter)
    },
    decline(index){
      let parameter = {
        'id': this.data[index].id,
        'status': 2
      }
      this.updateRequestEnrolledAccount(parameter)
    },
    updateRequestEnrolledAccount(parameter){
      this.APIRequest('enrolled_courses/update', parameter).then(response => {
        if(response.data === true){
          this.createParameter(this.parameter)
          this.data[this.editStatusPrevIndex].edit_status_flag = false
          this.editStatusPrevIndex = null
        }
      })
    },
    editStatus(index){
      if(this.editStatusPrevIndex === null){
        this.editStatusPrevIndex = index
        this.data[index].edit_status_flag = true
      }else{
        if(this.editStatusPrevIndex === index){
          this.data[index].edit_status_flag = false
          this.editStatusPrevIndex = null
        }else{
          this.data[this.editStatusPrevIndex].edit_status_flag = false
          this.data[index].edit_status_flag = true
          this.editStatusPrevIndex = index
        }
      }
    }
  }
}
</script>
<style>
  .account-info-holder{
    min-height: 125px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    width: 48%;
    float: left;
    margin: 10px 0% 0 2%;
    overflow-y: hidden;
    border: solid 1px #eee;
  }
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

  .account-name{
    height: 35px;
    width: 100%;
    float: left;
    font-weight: 600;
    padding-top: 10px;
    font-size: 18px;
    color: #028170;
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
    min-height: 20px;
    width: 100%;
    float: left;
    overflow-y: hidden;
  }
   @media screen (min-width: 200px), screen and (max-width: 991px){
    .account-info-holder{
      width: 100%;
      margin: 10px 0% 0 0%;
    }
   }
</style>
