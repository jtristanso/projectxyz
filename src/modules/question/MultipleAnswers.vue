<template>
  <div class="question-holder">
<!--     <span class="message-holder text-center" v-if="message !== null || errorMessage !== null">
      <label class="text-primary" v-if="message !== null">{{message}}</label>
      <label class="text-danger" v-if="errorMessage !== null"><b>Opps!</b> &nbsp;{{errorMessage}}</label>
    </span> -->
    <span v-if="data.length > 0" class="option-holder">
      <span class="holder" v-for="item, index in data">

        <!-- <i class="far fa-square" v-bind:id="'option-' + index" v-on:click="selected(index)" ></i> -->
        <i class="far fa-square" v-bind:id="'option-' + index"></i>
        <!-- <input type="text" class="form-control form-control-lg order" v-bind:value="index + 1" disabled> -->
        <input type="text" class="form-control form-control-lg value" v-model="item.description">
        <i class="fa fa-trash text-danger action-link" v-on:click="remove(index)"></i>
      </span>
    </span>
    <span class="add-option">
      <button class="btn btn-primary" v-on:click="add()"><i class="fa fa-plus"></i>Add Answer</button>
      <!-- <button class="btn btn-primary pull-right" v-on:click="submit()"><i class="fa fa-sign-in"></i>Save</button> -->
    </span>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  mounted(){
    this.init()
  },
  data(){
    return {
      username: '',
      password: '',
      message: null,
      errorMessage: null,
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      data: [],
      flag: false,
      questionId: 1,
      prev: null,
      answer: null,
      dataTemp: []
    }
  },
  props: [
    'questionIdParam'
  ],
  watch: {
    questionIdParam(value){
      this.questionId = this.questionIdParam
    }
  },
  methods: {
    init(){
      this.questionId = this.questionIdParam
    },
    add(){
      this.data.push({'description': null})
      this.dataTemp.push({'flag': false})
    },
    remove(index){
      this.data.splice(index, 1)
    },
    submit(){
      if(this.questionId === null){
        this.errorMessage = 'Question ID is empty.'
        this.message = null
      }else{
        this.message = 'Successfully Submitted!'
        this.errorMessage = null
        this.createRequest()
      }
    },
    selected(index){
      if(this.dataTemp[index].flag === false){
        document.getElementById('option-' + index).classList.add('fas')
        document.getElementById('option-' + index).classList.remove('far')
        this.dataTemp[index].flag = true
      }else{
        document.getElementById('option-' + index).classList.add('far')
        document.getElementById('option-' + index).classList.remove('fas')
        this.dataTemp[index].flag = false
      }
    },
    createRequest(questionId){
      let parameter = {
        'question_id': questionId,
        'options': this.data
      }
      this.APIRequest('question_options/create', parameter).then(response => {
        if(response.data.data !== null){
          this.message = 'Successfully Added!'
          this.errorMessage = null
        }else{
          this.message = null
          this.errorMessage = response.data.message
        }
      })
    },
    getAnswer(){
      this.answer = ''
      // for (var i = 0; i < this.dataTemp.length; i++) {
      //   if(this.dataTemp[i].flag === true){
      //     if(this.answer === null){
      //       this.answer = ',' + (i + 1)
      //     }else{
      //       this.answer += ',' + (i + 1)
      //     }
      //   }
      // }
      // if(this.answer === null){
      //   this.errorMessage = 'Please SELECT answers.'
      // }else{
      //   this.answer += ','
      // }
    },
    validation(){
      for (var i = 0; i < this.data.length; i++) {
        if(this.data[i].description === null){
          this.errorMessage = 'Please fill in all required fields.'
          return false
        }
      }
      this.answer = ''
      return true
    },
    redirect(parameter){
      // ROUTER.push(parameter)
    }
  }
}
</script>
<style>
  .question-holder{
    width: 100%;
    min-height: 50;
    overflow-y: hidden;
    float: left;
  }
  .message-holder{
    width: 100%;
    height: 50px;
    float: left;
  }
  .option-holder{
    width: 100%;
    float: left;
    min-height: 50px;
    overflow-y: hidden;
  }
  .holder{
    width: 100%;
    float: left;
    min-height: 50px;
    overflow-y: hidden;
    margin-top: 5px;
    margin-bottom: 5px;
  }
  .holder .value{
    width: 80%;
    float: left;
    margin-left: 2%;
  }
  .holder .order{
    width: 10%;
    float: left;
    text-align: center;
  }
  .holder i{
    width: 5%;
    float: left;
    font-size: 40px;
    padding-top: 5px;
    margin-left: 2%;
    text-align:right;
  }
  
  .add-option{
    float: left;
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
  }

  .add-option button i{
    padding-right: 5px;
  }
  .form-control-lg{
    height: 50px !important;
  }


  /*-------------- Extra Small Screen for Mobile Phones --------------*/
  @media (max-width: 500px){
    .holder .value{
      width: 70%;
      margin-left: 1%;
    }
    .holder .order{
      width: 13%;
    }
    .holder i{
      width: 14%;
      margin-left: 1%;
      margin-right: 1%;
    }
  }
</style>
