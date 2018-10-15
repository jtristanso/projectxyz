<template>
  <div class="row">
    <div class="col-md-6 col-lg-4 mx-auto signup-container">
      <div class="signup-wrapper">
        <div class="signup-header" style="margin-top: 50px;">
          <img src="../../assets/img/classworx.png" v-on:click="redirect('/')">
        </div>
        <span style="width:100%;float:left;text-align:center;font-size:20px;margin-bottom:20px;">
          Register to <b class="text-primary">CLASS</b><b class="text-green">WORX</b>
        </span>
        <div class="signup-holder">
          <div class="login-message-holder login-spacer text-center" v-if="errorMessage != ''">
            <span class="text-danger text-center"><b>Oops!</b> {{errorMessage}}</span>
          </div>
          <div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Username" aria-describedby="addon-1" v-model="username">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1"><i class="fa fa-envelope"></i></span>
              <input type="text" class="form-control form-control-login" placeholder="Email" aria-describedby="addon-1" v-model="email">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="Password" aria-describedby="addon-2" v-model="password">
            </div>
            <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
              <input type="password" class="form-control form-control-login" placeholder="Confirm Password" aria-describedby="addon-2" v-model="cpassword">
            </div>
<!--             <div class="input-group login-spacer">
              <span class="input-group-addon" id="addon-1" style="width: 70px;">School</span>
              <select class="form-control form-control-login" v-model="schoolIndex">
                <option v-bind:value="index" v-for="item, index in schools">{{item.name + ' - ' + item.address}}</option>
              </select>
            </div> -->
            <div class="input-group login-spacer">
              <span class="input-group-addon account-type" id="addon-1" style="width: 70px;">Account Type</span>
              <select class="form-control form-control-login" v-model="type">
                <option value="STUDENT">Student</option>
                <option value="TEACHER">Teacher</option>
              </select>
            </div>
<!--              <div class="form-check">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                I agree the terms and conditions
              </label>
            </div> -->
            <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="signUp()">Signup</button>
            <button class="btn btn-login-danger btn-block btn-login login-spacer" v-on:click="redirect('/login')">Back to Login</button>  
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
    // this.getSchools()
  },
  data(){
    return {
      username: '',
      email: '',
      password: '',
      cpassword: '',
      type: null,
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      flag: false,
      schools: null,
      schoolIndex: null
    }
  },
  methods: {
    signUp(){
      this.validate()
      let parameter = {
        username: this.username,
        email: this.email,
        password: this.password,
        config: CONFIG,
        account_type: this.type
      }
      if(this.flag === true){
        $('#loading').css({'display': 'block'})
        this.APIRequest('accounts/create', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.error !== null){
            if(response.error.status === 100){
              let message = response.error.message
              if(typeof message.username !== undefined && typeof message.username !== 'undefined'){
                this.errorMessage = message.username[0]
              }else if(typeof message.email !== undefined && typeof message.email !== 'undefined'){
                this.errorMessage = message.email[0]
              }
            }else if(response.data !== null){
              if(response.data > 0){
                this.login()
              }
            }
          }
          // this.redirect('/verification/' + this.email)
          // this.login()
        })
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    validate(){
      if(this.username.length >= 6 && this.email !== null && this.password !== null && this.password.localeCompare(this.cpassword) === 0 && this.type !== null){
        this.flag = true
      }else if(this.username.length < 6){
        this.errorMessage = 'Username must be atleast 6 characters.'
      }else if(this.password.length < 6){
        this.errorMessage = 'Password must be atleast 6 characters'
      }else if(this.password.localeCompare(this.cpassword) !== 0){
        this.errorMessage = 'Password did not matched'
      }else{
        this.errorMessage = 'Please fill in all required fields.'
        this.flag = false
      }
    },
    login(){
      AUTH.authenticate(this.username, this.password, (response) => {
        ROUTER.push('dashboard')
      }, (response, status) => {
        this.errorMessage = (status === 401) ? 'Your username and password did not matched.' : 'Cannot log in? Contact us through email: support@classworx.co'
      })
    },
    getSchools(){
      let parameter = {
        'sort': {
          'name': 'ASC'
        }
      }
      this.APIRequest('schools/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.schools = response.data
        }else{
          this.schools = null
        }
      })
    }
  }
}
</script>
<style scoped>


.signup-container{
  margin-top: 25px;
}

.signup-header{
  height: 100px;
  color: #006600;
  width: 100%;
  float: left;
  text-align: center;
}
.signup-header img{
  width: 70px;
  height: 70px;
}

.signup-header img:hover{
  cursor: pointer;
}

.header-title{
  width: 90%;
  margin:  25px 5% 0 5%;
  font-size: 24px;
  font-weight: 700px;
}

.signup-holder{
  width: 80%;
  margin: 0 10% 0 10%;
  float: left;
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
.input-group{
  margin-top: 5px;
  margin-bottom: 5px;
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
}
.site-title .app-name{
  float: left;
}

.account-type{
  width: 120px !important;
}
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .custom-holder{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 50px !important;
  }
}
</style>
