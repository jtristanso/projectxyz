<template>
  <div class="row">
    <div class="col-lg-4 col-md-6 mx-auto custom-holder">
      <div class="site-title">
          <img src="../../assets/img/classworx.png">
          <span class="app-name">
            <label class="text-primary">
              <b>Class<label style="color:#028170;">Worx</label></b>
            </label> 
          </span>
        </div>
      <span class="text-center login-spacer"><h6 class="text-center text-primary">Request to Reset</h6></span>
      <div class="input-holder">
        <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
            <span class="text-danger text-center" v-if="successMessage === null && errorMessage !== null"><b>Oops!</b> {{errorMessage}}</span>
            <span class="text-primary text-center" v-else>{{successMessage}}</span>
        </div>
        <div class="login-spacer" style="margin-bottom: 25px !important;text-align: justify;" v-if="hide === true">
          We send recovery email to your email address at <b>{{email}}</b>.
          Please give us a moment, it may take few minutes. Please check your email address to continue.
        </div>
        <input type="text" name="username" placeholder="Type your Email Address" class="form-control" v-model="email" v-if="hide === false">
        <br>
        <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="request()" v-if="hide === false">Send Request</button><button class="btn btn-login-danger btn-block btn-login login-spacer" v-on:click="redirect('/')">Back to Login</button>
        <br>
        <br>
     </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
export default {
  name: '',
  components: {
    'input-group': require('components/input_field/InputGroup.vue')
  },
  mounted(){
  },
  data(){
    return{
      email: null,
      flag: false,
      errorMessage: null,
      successMessage: null,
      hide: false
    }
  },
  methods: {
    request(){
      this.validate()
      let parameter = {
        email: this.email
      }
      if(this.flag === true){
        this.APIRequest('accounts/request_reset', parameter).then(response => {
          this.hide = true
        })
      }
    },
    validate(){
      if(this.email === null || this.email === ''){
        this.flag = false
        this.errorMessage = 'Please enter your Email Address'
      }else{
        this.flag = true
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style>
.custom-holder{
  margin-top: 100px;
}

.header-title{
  width: 90%;
  margin:  25px 5% 0 5%;
  font-size: 24px;
  font-weight: 700px;
}
.input-holder{
  width: 90%;
  margin:  0 5% 0 5%;
}

.form-control{
  height: 45px !important;
}
.btn-login-primary{
  background: #6a0090;
  color: #fff;
  height: 45px !important;
}
.btn-login-primary:hover{
  border: solid 1px #3f0050;
}
.btn-login-danger{
  background: #ff0000;
  color: #fff;
  height: 45px !important;
}
.btn-login-danger:hover{
  border: solid 1px #a90201;
}
.site-title{
  margin-top: 25px;
  width: 100%;
  float: left;
}
.site-title img{
  width: 50px;
  width: 50px;
  float: left;
  margin-right: 10px;
  margin-left: 5%;
}
.site-title .app-name{
  float: left;
}
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .custom-holder{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 50px !important;
  }
}
</style>
