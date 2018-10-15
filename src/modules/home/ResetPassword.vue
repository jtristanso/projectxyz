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
      <span class="text-center form-spacer"><h6 class="text-center text-primary">Reset Password</h6></span>
      <div class="input-holder">
        <div class="login-message-holder login-spacer" v-if="errorMessage != '' && updateFlag === false">
            <span class="text-danger text-center" v-if="errorMessage !== null"><b>Oops!</b> {{errorMessage}}</span>
        </div>
        <div class="login-message-holder login-spacer" v-if="updateFlag === true">
            <span class="text-center">Your password was successully updated! To login click the button below.</span>
        </div>
        <div class="input-group form-spacer" v-if="updateFlag === false">
          <span class="input-group-addon recover-addon" id="addon-2">New Password</span>
          <input type="password" class="form-control form-control-login" placeholder="New Password" aria-describedby="addon-2" v-model="password">
        </div>
        <div class="input-group form-spacer" v-if="updateFlag === false">
          <span class="input-group-addon recover-addon" id="addon-2">Confirm Password</span>
          <input type="password" class="form-control form-control-login" placeholder="Confirm New Password" aria-describedby="addon-2" v-model="cPassword">
        </div>
        <br>
        <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="reset()" v-if="updateFlag === false">Continue</button>
        <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="redirect('/login')" v-else>Continue to Login</button>
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
    this.code = this.$route.params.code
    this.username = this.$route.params.username
  },
  data(){
    return{
      email: null,
      flag: false,
      errorMessage: null,
      password: null,
      cPassword: null,
      code: this.$route.params.code,
      username: this.$route.params.username,
      updateFlag: false
    }
  },
  methods: {
    reset(){
      this.validate()
      if(this.flag === true){
        let parameter = {
          'username': this.username,
          'code': this.code,
          'password': this.password
        }
        this.APIRequest('accounts/update', parameter).then(response => {
          if(response.data === true){
            this.updateFlag = true
          }else{
            this.updateFlag = false
          }
        })
      }
    },
    validate(){
      if(this.password === null || this.password === '' || this.cPassword === null || this.cPassword === ''){
        this.flag = false
        this.errorMessage = 'Please fill in all required fields.'
      }else if(this.password !== this.cPassword){
        this.flag = false
        this.errorMessage = 'Please confirm your new password.'
      }else if(this.password.length < 8){
        this.flag = false
        this.errorMessage = 'Password length must be greater than 8 digit characters.'
      }else{
        this.flag = true
        this.errorMessage = null
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
.recover-addon{
  width: 170px;
}
.form-spacer{
  margin-top: 10px;
}
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .custom-holder{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 50px !important;
  }
}
</style>
