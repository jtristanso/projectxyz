<template>
  <div class="question-holder">
<!--     <span class="message-holder text-center" v-if="message !== null || errorMessage !== null">
      <label class="text-primary" v-if="message !== null">{{message}}</label>
      <label class="text-danger" v-if="errorMessage !== null"><b>Opps!</b> &nbsp;{{errorMessage}}</label>
    </span> -->
   <!--  <span class="option-holder">
       <span class="holder">
        <input type="text" class="form-control form-control-lg value" v-model="answer">
      </span> 
    </span> -->
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  mounted(){
  },
  data(){
    return {
      message: null,
      errorMessage: null,
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      answer: null
    }
  },
  methods: {
    getAnswer(){
      // this.validation()
    },
    validation(){
      if(this.answer === null){
        // this.errorMessage = 'Please INPUT your answer.'
        return true
      }else{
        return true
      }
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
