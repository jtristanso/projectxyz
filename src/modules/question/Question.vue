<template>
  <div>
      <div class="module-header">
        <div class="title text-warning" style="width: 100%">
          <label v-on:click="redirect('/courses')" class="text-underline"><b>Courses</b></label><label v-if="header !== null && header.description.length > 57">/{{header.type}}/{{header.description.substr(0,57) + '...'}}</label>
          <label v-else-if="header !==null && header.description.length < 58">/{{header.type}}/{{header.description}}</label>
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
        <div class="add" style="width: 10%">
          <button class="btn btn-primary pull-right" id="newquestion" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> New Question</button>
        </div>
      </div>
      <div class="table-result">
        <span class="test-details" v-if="info !== null">
          <b class="text-danger"><i class="fas fa-cog" style="padding-right: 10px;"></i>Test Settings:</b>
          <br>
          The Test will end after <b>{{data.length * parseInt(info.time_per_question)}} minute(s)</b> and contains of <b>{{info.total_questions}} question(s)</b>. There are <b>{{info.questions_per_page}} question(s)</b> per page with <b>{{info.time_per_question}} minute(s)</b>  as the time  limit per question.
        </span>
        <span v-if="data.length > 0" class="test-page-holder">
          <span v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)"
            class="test-item" v-on:dblclick="edit(index)" v-bind:data-hover="{'tooltip': data[index].edit === false}" v-bind:data-placement="{'top': data[index].edit === false}" title="Double Click to edit">
            <span class="test-question text-danger" v-if="item.error_message !== null"><b>Opps! {{item.error_message}}</b></span>
            <span class="test-question">
              <span class="number-holder">
                <label class="number" v-bind:class="{'bg-danger': item.type !== 'long_answer' && (item.answer === null || item.answer === '')}" v-if="item.edit === false">{{(item.order)}}</label>
                <input type="text" class="form-control" v-model="item.order" v-else>
              </span>
              <span class="question-holder">
                <label v-if="item.edit === false"><b v-if="parseInt(item.points) > 1">{{'(' + item.points + ' Points) '}}</b><b v-else>{{'(' + item.points + ' Point) '}}</b>{{item.question}}
                  <label class="text-danger" v-bind:id="'quesholder-' + index" v-if="item.type !== 'long_answer' && (item.answer === null || item.answer === '')"><b>Please update the ANSWER(S).</b></label>
                </label>
                <input type="text" class="form-control" v-model="item.question" v-if="item.edit === true">
                <br v-if="item.edit === true">
                <label v-if="item.edit === true">
                  Points
                </label>
                <input type="text" class="form-control" v-model="item.points" v-if="item.edit === true">
              </span>
            </span>
            <span class="test-question-choices">
                <span class="options" v-if="item.type === 'multiple_answers'">
                  <span class="option-item" v-for="itemOption, indexOption in data[index].question_options" v-if="data[index].question_options.length > 0">
                    <span class="icon"><i class="fa-square action-link" v-bind:class="{'far': item.answer === '' || item.answer.includes(',' + itemOption.id + ',') === false, 'fas': item.answer.includes(',' + itemOption.id + ',') === true, 'text-danger': item.answer === null, 'text-primary-test': item.answer !== null}" v-on:click="setActiveOnMultipleAnswers(index, itemOption)"></i></span>
                    <span class="text">
                      <label v-if="item.edit === false">{{itemOption.description}}</label>
                      <input type="text" class="form-control" v-model="itemOption.description" v-if="item.edit === true">
                      <i class="fa fa-trash text-danger delete action-link" v-if="item.edit === true" v-on:click="deleteOption(itemOption, indexOption, index)"></i>
                    </span>
                  </span>
                  <span class="add-option-item" v-if="item.edit === true">
                    <button class="btn btn-primary" v-on:click="addOption(index)"><i class="fa fa-plus"></i> Add Option</button>
                  </span>
                </span>

                <span class="options" v-if="item.type === 'multiple_choice'">
                  <span class="option-item" v-for="itemOption, indexOption in data[index].question_options" v-if="data[index].question_options.length > 0">
                    <span class="icon"><i class="fa-dot-circle action-link" v-bind:class="{'far': item.answer === '' || itemOption.id !== parseInt(item.answer), 'fas': parseInt(item.answer) === itemOption.id, 'text-danger-test': item.answer === null, 'text-primary-test': item.answer !== null, 'edit': item.edit === true}" v-on:click="setActiveOnMultipleChoice(index, itemOption)"></i></span>
                    <span class="text">
                      <label v-if="item.edit === false">{{itemOption.description}}</label>
                      <input type="text" class="form-control" v-model="itemOption.description" v-if="item.edit === true">
                      <i class="fa fa-trash text-danger delete action-link" v-if="item.edit === true" v-on:click="deleteOption(itemOption, indexOption, index)"></i>
                    </span>
                  </span>
                  <span class="add-option-item" v-if="item.edit === true">
                    <button class="btn btn-primary" v-on:click="addOption(index)"><i class="fa fa-plus"></i> Add Option</button>
                  </span>
                </span>

                <span class="options" v-if="item.type === 'short_answer'">
                  <span class="option-item" v-if="item.edit === true">
                    Answer:
                  </span>
                  <span class="option-item">
                    <input type="text" class="form-control short" v-model="item.answer" v-if="item.edit === true">
                    <label v-if="item.answer !== '' && item.answer !== null && item.edit === false">Answer: <b>{{item.answer}}</b></label>
                  </span>
                </span>


                <span class="options" v-if="item.type === 'long_answer'">
                  <span class="option-item">
                    <textarea class="form-control short" v-model="item.answer" v-if="item.edit === true"></textarea>
                  </span>
                </span>

                <span class="options" v-if="item.edit === true">
                  <button class="btn btn-primary pull-right" v-on:click="update(data[index], index)"><i class="fa fa-sync"></i> Update</button>
                  <button class="btn btn-danger pull-right" style="margin-right: 10px;" v-on:click="edit(index)"><i class="fa fa-ban"></i> cancel</button>
                  <button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                </span>

            </span>
          </span>
        </span>
        <span v-else class="text-center text-danger">
          <label>No Question Available!</label>
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
            <label>Question</label>
            <br>
            <input type="text" id="questionnew" class="form-control" v-model="question">
            <br>
            <label>Points of this Question</label>
            <br>
            <input type="text" id="questionpoints" class="form-control" v-model="points" placeholder="Default is 1">
            <br>
            <label>Answer Type</label>
            <br>
            <select v-model="type" id="questiontype" class="form-control">
              <option value="short_answer">Short Answer</option>
              <option value="long_answer">Long Answer</option>
              <option value="multiple_choice">Multiple Choice</option>
              <option value="multiple_answers">Multiple Answers</option>
            </select>
            <span v-if="type === 'short_answer'">
              <!-- <br>
              <label>Answer <label class="text-danger">(<b>PLEASE ENTER YOUR ANSWER.</b>)</label></label>
              <br> -->
              <short-answer></short-answer>
            </span>
            <span v-if="type === 'long_answer'">
              <br>
              <label>Answer <label class="text-primary">(<b>NO ANSWER: MANUAL CHECKING.</b>)</label></label>
              <br>
              <long-answer></long-answer>
            </span>
            <span v-if="type === 'multiple_choice'">
              <br>
              <label>Choices <label class="text-danger">(<b>PLEASE ADD CHOICES.</b>)</label></label>
              <br>
              <multiple-choice></multiple-choice>
            </span>
            <span v-if="type == 'multiple_answers'">
              <br>
              <label>Choices <label class="text-danger">(<b>PLEASE ADD OPTIONS.</b>)</label></label>
              <multiple-answers></multiple-answers>
            </span>
            <br>
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
    this.retrieveHeader()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Question',
      parameter: this.$route.params.code,
      info: null,
      data: [],
      header: null,
      headerTitle: 'Tests',
      headerMethod: 'tests',
      method: 'questions',
      methodId: 'test_id',
      errorMessage: null,
      closeFag: false,
      type: null,
      question: null,
      points: null,
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
      }
    }
  },
  components: {
    'multiple-choice': require('modules/question/MultipleChoice.vue'),
    'multiple-answers': require('modules/question/MultipleAnswers.vue'),
    'short-answer': require('modules/question/ShortAnswer.vue'),
    'long-answer': require('modules/question/LongAnswer.vue')
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveHeader(value){
      let parameter = {
        'condition': [{
          'value': this.parameter,
          'column': 'code',
          'clause': '='
        }]
      }
      this.APIRequest(this.headerMethod + '/retrieve', parameter).then(response => {
        this.header = response.data[0]
        this.info = this.header
        this.parameter = response.data[0].id
        this.defaultParameter()
      })
    },
    filterCourses(){
      this.createParameter(this.parameter)
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
      formData.append('points', (this.points !== null) ? this.points : 1)
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
      if(this.question === null || this.type === null || this.$children[0].validation() === false){
        return false
      }else{
        return true
      }
    },
    edit(index){
      if(this.prevEditIndex === null){
        this.data[index].edit = true
        this.prevEditIndex = index
      }else{
        if(index === this.prevEditIndex){
          this.data[index].edit = false
          this.prevEditIndex = null
        }else{
          this.data[index].edit = true
          this.data[this.prevEditIndex].edit = false
          this.prevEditIndex = index
        }
      }
    },
    setActiveOnMultipleChoice(index, option){
      if(option.id !== null){
        this.data[index].answer = option.id
      }else{
        this.data[index].error_message = 'You added new option(s). Please update first before selecting an answer.'
      }
    },
    setActiveOnMultipleAnswers(index, option){
      if(option.id === null){
        this.data[index].error_message = 'You added new option(s). Please update first before selecting an answer.'
      }else{
        if(this.data[index].answer === ''){
          this.data[index].answer = ',' + option.id + ','
        }else{
          if(this.data[index].answer.includes(',' + option.id + ',') === true){
            let text = ',' + option.id + ','
            this.data[index].answer = this.data[index].answer.replace(text, ',')
          }else{
            this.data[index].answer += option.id + ','
          }
        }
      }
    },
    update(data, index){
      let parameter = {
        'data': data
      }
      this.APIRequest(this.method + '/update', parameter).then(response => {
        if(data.type === 'short_answer' || data.type === 'long_answer'){
          this.data[index].edit = false
          this.createParameter(this.parameter)
        }
      }).done(() => {
        if(data.type === 'multiple_answers' || data.type === 'multiple_choice'){
          this.updateOptions(data, index)
        }
      })
    },
    addOption(index){
      let object = {
        'id': null,
        'question_id': this.data[index].id,
        'description': null,
        'order': this.data[index].question_options.length + 1
      }
      this.data[index].question_options.push(object)
    },
    updateOptions(data, index){
      if(this.validateOptions(data.question_options) === true){
        this.APIRequest('question_options/update', data).then(response => {
          this.data[index].edit = false
          this.createParameter(this.parameter)
        })
      }else{
        this.data[index].error_message = 'Fields should not be Empty!'
      }
    },
    deleteOption(option, indexOption, index){
      if(option.id === null){
        this.data[index].question_options.splice(indexOption, 1)
      }else{
        let parameter = {
          'id': option.id
        }
        this.APIRequest('question_options/delete', parameter).then(response => {
          this.createParameter(this.parameter)
        })
      }
    },
    validateOptions(option){
      for(let i = 0; i < option.length; i++){
        if(option[i].description === '' || option[i].description === null){
          return false
        }
      }
      return true
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
td .question, td .answer, td .options, .options .option-item{
  float: left;
  width: 100%;
}
.editable-tr{
  cursor: pointer;
}
.question{
  font-size: 14px !important;
  margin-bottom: 10px;
}
.tooltip-inner{
  z-index: -1 !important;
}
.options .add-option-item{
  width: 100%;
  float: left;
  margin: 10px 0 10px 0;
}
.option-item{
  margin-bottom: 10px;
}
.option-item i{
  font-size: 24px !important;
}
.edit-option i{
  width: 5%;
  text-align: center;
  float: left;
  padding-top: 10px;
}
.edit-option input{
  width: 86%;
  margin: 0 2% 0 2%;
  float: left;
}
.test-details{
  width: 100%;
  float: left;
  margin-bottom: 25px;
}
.test-page-holder{
  float: left;
  width: 100%;
  min-height: 300px;
  overflow-y: hidden;
}
.test-page-holder .test-item{
  float: left;
  width: 100%;
  min-height: 50px;
  overflow-y: hidden;
  margin-bottom: 10px;
}




.test-question{
  float: left;
  margin-bottom: 10px;
  width: 100%;
  min-height: 10px;
  overflow-y: hidden;
}
.test-question .question-holder{
  width: 85%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
}
.question-holder input{
  margin-left: 5%;
  width: 95%;
}
.test-question .number-holder{
  width: 5%;
  float: left;
}
.number-holder .number{
  background: #028170;
  height: 30px;
  width: 30px;
  text-align: center;
  color: #fff;
  border-radius: 15px;
  padding-top: 5px;
}

.test-item .test-question-choices{
  float: left;
  width: 100%;
  min-height: 50px;
  overflow-y:hidden;
}

.test-question-choices .options{
  float: left;
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
}

.options .option-item{
  float: left;
  width: 100%;
  margin-top: 10px;
}
.option-item .short{
  float: left;
  width: 90%;
  margin-left: 5%;
}

.option-item .icon{
  width: 5%;
  float: left;
}
.option-item .icon i{
  font-size: 24px !important;
}
.option-item .text{
  float: left;
  width: 93%;
  margin-left: 1%;
}
.option-item .text input{
  width: 90%;
  float: left;
}
.option-item .icon .edit{
  margin-top: 10px;
}
.option-item .text .delete{
  width: 10%;
  float: left;
  text-align: center;
  margin-top: 10px;
}
.text-primary-test{
  color: #028170 !important ;
}

.text-danger-test{
  color: #dc3545 !important ;
}

.btn-warning-test{
  border: solid 1px #FCCD04 !important;
  background: #FCCD04 !important;
}
.btn-danger-test{
  border: solid 1px #dc3545 !important;
  background: #dc3545 !important;
}

@media screen (min-width: 401px), screen and (max-width: 700px){
  .option-item .icon{
    width: 10%;
  }
  .option-item .text{
    width: 89%;
  }
  .test-question .question-holder{
    width: 90%;
  }
  .test-question .number-holder{
    width: 10%;
  }
}
@media screen (min-width: 200px), screen and (max-width: 400px){
  .option-item .icon{
    width: 15%;
  }
  .option-item .text{
    width: 84%;
  }
  .test-question .question-holder{
    width: 85%;
  }
  .test-question .number-holder{
    width: 15%;
  }
}

</style>
