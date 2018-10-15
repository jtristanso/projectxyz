<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning" v-if="info !== null"><label v-on:click="redirect('/courses/en/')" class="text-underline"><b>{{info.course_details.code}}</b></label>/Take Test/{{info.type}}/{{info.description}}</label>
        </div>
      </div>
      <div class="table-result" v-if="finishFlag === false">
        <span class="test-details" v-if="info !== null">
          <b class="text-danger"><i class="fas fa-info-circle" style="padding-right: 10px;"></i>Instructions:</b>
          <br>
          The Test will end after <b>{{info.time_per_question * data.length}} Minute(s)</b> and contains of <b>{{info.total_questions}} question(s)</b>. There are <b>{{info.questions_per_page}} question(s)</b> per page with <b>{{info.time_per_question}} minute(s)</b>  as the time  limit per question.
        </span>
        <span class="test-details text-danger" v-if="errorMessage !== null && timerFlag === true"><b>Opps! {{errorMessage}}</b></span>
        <span  v-if="info !== null && timerFlag === true" v-bind:class="{'text-danger': minutes < 1 && seconds > 0, 'text-warning': minutes >= 1 && minutes <= 3, 'text-primary-test': minutes > 3}" class="test-details">
          <b><i class="fa fa-clock" style="padding-right: 10px;"></i></b>
          <b v-if="hours > 0">{{hours + ' Hrs:'}}</b>
          <b v-if="minutes > 0">{{minutes + ' Min:'}}</b>
          <b v-if="seconds > 0">{{seconds + ' Sec'}}</b>
        </span>
        <span class="test-details text-danger" v-if="timerFlag === false"><b>TIME IS UP! Sorry, You did not select or submit any answer(s). Kindly click the next button to continue.</b></span>
        <span v-if="data.length > 0" class="test-page-holder">
          <span v-for="item, index in data" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)"
            class="test-item">
            <span class="test-question">
              <span class="number-holder"><label  v-bind:class="{'bg-danger': minutes < 1 && seconds > 0, 'bg-warning': minutes >= 1 && minutes <= 3}" class="number">{{(index + 1)}}</label></span>
              <span class="question-holder">{{item.question}}</span>
            </span>
            <span class="test-question-choices">
                <span class="options" v-if="item.type === 'multiple_answers'">
                  <span class="option-item" v-for="itemOption, indexOption in data[index].question_options" v-if="data[index].question_options.length > 0">
                    <span class="icon"><i class="fa-square action-link" v-bind:class="{'far': item.answer === '' || item.answer.includes(',' + itemOption.id + ',') === false, 'fas': item.answer.includes(',' + itemOption.id + ',') === true, 'text-danger': minutes < 1 && seconds > 0, 'text-warning': minutes >= 1 && minutes <= 3, 'text-primary-test': minutes > 3}" v-on:click="setActiveOnMultipleAnswers(index, itemOption.id)"></i></span>
                    <span class="text">{{itemOption.description}}</span>
                  </span>
                </span>

                <span class="options" v-if="item.type === 'multiple_choice'">
                  <span class="option-item" v-for="itemOption, indexOption in data[index].question_options" v-if="data[index].question_options.length > 0">
                    <span class="icon"><i class="fa-dot-circle action-link" v-bind:class="{'far': item.answer === '' || itemOption.id !== parseInt(item.answer), 'fas': parseInt(item.answer) === itemOption.id, 'text-danger': minutes < 1 && seconds > 0, 'text-warning': minutes >= 1 && minutes <= 3, 'text-primary-test': minutes > 3}" v-on:click="setActiveOnMultipleChoice(index, itemOption.id)"></i></span>
                    <span class="text">{{itemOption.description}}</span>
                  </span>
                </span>

                <span class="options" v-if="item.type === 'short_answer'">
                  <span class="option-item">
                    <input type="text" class="form-control" v-model="item.answer">
                  </span>
                </span>


                <span class="options" v-if="item.type === 'long_answer'">
                  <span class="option-item">
                    <textarea class="form-control" v-model="item.answer"></textarea>
                  </span>
                </span>

            </span>
          </span>
        </span>
        <span v-else class="text-center text-danger">
          <label>No Question Available!</label>
        </span>

      </div>
      <div class="finished-test" v-else>
          <i class="fas fa-thumbs-up text-success"></i>
          <span class="description text-success"><b>Congratulation!</b></span>
          <span style="font-size: 15px;">You've finish already the your Test!</span>
        </div>
      <div class="table-footer" v-if="finishFlag === false">
        <div class="items-total pull-left">
          <label>Showing <b>{{display.current}}</b> out of <b>{{display.total}}</b> Questions</label>
        </div>
        <div class="items-pager">
          <button class="btn btn-primary pull-right" v-on:click="submitAnswer()" v-bind:class="{'btn-danger-test': minutes < 1 && seconds > 0, 'btn-warning-test': minutes >= 1 && minutes <= 3}" v-if="timerFlag === true">Submit</button>
          <button class="btn btn-primary pull-right btn-danger-test" v-on:click="nextQuestion()" v-else>Next</button>
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
  created(){
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      parameter: this.$route.params.code,
      info: null,
      data: [],
      selectedIndex: null,
      courses: [],
      method: 'questions',
      methodId: 'test_id',
      errorMessage: null,
      closeFag: false,
      selectedTotalItems: null,
      totalDisplay: 5,
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
      hours: 0,
      minutes: 5,
      seconds: 60,
      timerFlag: true,
      finishFlag: false
    }
  },
  methods: {
    checkParameter(){
      this.retrieveRequestQuizzes()
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    timer(){
      AUTH.timer.interval = window.setInterval(() => {
        if(this.flagTimer() === true){
          this.subtractTime()
          this.timerFlag = true
        }else{
          this.timerFlag = false
        }
      }, AUTH.timer.speed)
    },
    flagTimer(){
      if(this.hours === 0 && this.minutes === 0 && this.seconds === 1){
        this.createParameterAnswerOnTimeEnd()
        return false
      }else{
        return true
      }
    },
    subtractTime(){
      this.hours = (this.minutes === 0 && this.seconds === 0 && this.hours > 0) ? this.hours - 1 : this.hours
      this.minutes = (this.seconds === 1 && this.minutes > 0) ? this.minutes - 1 : this.minutes
      this.seconds = (this.seconds === 1 && this.minutes >= 0) ? 60 : this.seconds - 1
    },
    retrieveRequestQuizzes(){
      let parameter = {
        'condition': [{
          'value': this.parameter,
          'column': 'code',
          'clause': '='
        }]
      }
      this.APIRequest('tests/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.parameter = response.data[0].id
          this.createParameter(response.data[0].id)
          this.info = response.data[0]
          this.totalDisplay = this.info.questions_per_page
        }else{
          alert('Not Found!')
          this.info = null
        }
      })
    },
    setTimer(){
      let numberOfQuestions = null
      let qpp = parseInt(this.info.questions_per_page)
      if(this.displayIndexAdder === 0){
        numberOfQuestions = (this.data.length >= qpp) ? qpp : this.data.length
      }else{
        let current = (this.displayIndexAdder * qpp) + qpp
        let lessThan = this.data.length - (this.displayIndexAdder * qpp)
        numberOfQuestions = (current > this.data.length) ? lessThan : qpp
      }
      this.minutes = parseInt(this.info.time_per_question) * numberOfQuestions - 1
      this.hours = parseInt(this.minutes / 60)
      this.minutes = this.minutes % 60
      this.seconds = 60
      this.timer()
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
      this.APIRequest(this.method + '/retrieve_on_take', parameter).then(response => {
        if(response.data === null){
          this.courses = []
        }else{
          this.courses = response.data
        }
        this.selectedIndex = 0
        this.data = this.courses
      }).done(() => {
        if(flag === true){
          this.initDisplayer()
          this.setTimer()
          setTimeout(() => {
            this.makeActive(0)
          }, AUTH.timer.speed)
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
      this.display.pager = new Array(pagerSize)
    },
    filter(){
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
    setActiveOnMultipleChoice(index, optionId){
      this.data[index].answer = optionId
    },
    setActiveOnMultipleAnswers(index, optionId){
      if(this.data[index].answer === ''){
        this.data[index].answer = ',' + optionId + ','
      }else{
        if(this.data[index].answer.includes(',' + optionId + ',') === true){
          let text = ',' + optionId + ','
          this.data[index].answer = this.data[index].answer.replace(text, ',')
        }else{
          this.data[index].answer += optionId + ','
        }
      }
    },
    submitAnswer(){
      this.selectedIndex++
      window.clearInterval(AUTH.timer.interval)
      AUTH.timer.interval = null
      if(this.validateAnswer() === false){
        this.errorMessage = 'Please Select or Type your Answer.'
      }else{
        this.errorMessage = null
        let qpp = parseInt(this.info.questions_per_page)
        let answer = []
        let i = 0
        let flag = false
        while(i < this.totalDisplay){
          if((i + (this.displayIndexAdder * qpp)) < this.data.length){
            flag = ((i + (this.displayIndexAdder * qpp) + 1) === this.data.length)
            let tempData = this.data[i + (this.displayIndexAdder * qpp)]
            tempData.account_id = this.user.userID
            answer.push(tempData)
          }else{
            flag = true
            break
          }
          i++
        }
        this.createRequestAnswer(answer, flag)
      }
    },
    createParameterAnswerOnTimeEnd(){
      this.errorMessage = 'Time is up and no answer was selected.'
      let qpp = parseInt(this.info.questions_per_page)
      window.clearInterval(AUTH.timer.interval)
      AUTH.timer.interval = null
      let answer = []
      let flag = false
      let i = 0
      while(i < this.totalDisplay){
        if((i + (this.displayIndexAdder * qpp)) < this.data.length){
          flag = flag = ((i + (this.displayIndexAdder * qpp) + 1) === this.data.length)
          let tempData = this.data[i + (this.displayIndexAdder * qpp)]
          tempData.account_id = this.user.userID
          answer.push(tempData)
        }else{
          flag = true
          break
        }
        i++
      }
      this.createRequestAnswer(answer, flag)
    },
    nextQuestion(){
      this.errorMessage = null
      this.displayIndexAdder++
      window.clearInterval(AUTH.timer.interval)
      AUTH.timer.interval = null
      this.setTimer()
    },
    createRequestAnswer(answer, flag){
      let parameter = {
        'data': answer
      }
      this.APIRequest('answers/create', parameter).then(response => {
      }).done(() => {
        this.errorMessage = null
        if(flag === false){
          this.finishFlag = false
          this.nextQuestion()
        }else{
          // Notify Finish
          this.finishFlag = true
        }
      })
    },
    validateAnswer(){
      let i = 0
      while(i < this.totalDisplay){
        if(this.data[i + this.displayIndexAdder].answer === ''){
          return false
        }else{
          //
        }
        i++
      }
      return true
    }
  }
}
</script>
<style>
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
  width: 80%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
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

.option-item .icon{
  width: 5%;
  float: left;
}
.option-item .icon i{
  font-size: 24px !important;
}
.text-primary-test{
  color: #028170 !important ;
}
.option-item .text{
  float: left;
  width: 93%;
  margin-left: 1%;
}
.btn-warning-test{
  border: solid 1px #FCCD04 !important;
  background: #FCCD04 !important;
}
.btn-danger-test{
  border: solid 1px #dc3545 !important;
  background: #dc3545 !important;
}


.finished-test{
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
  float: left;
  min-height: 450px;
  overflow-y: hidden;
  text-align: center;
  border: solid 1px #ddd;
}

.finished-test i{
  font-size: 100px;
  padding-top: 150px;
}
.finished-test span{
  width: 100%;
  float: left;
}

.finished-test .description{
  font-size: 24px;
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
