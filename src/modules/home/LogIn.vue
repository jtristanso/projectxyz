<template>
  <div class="col-sm-12 col-md-6 col-lg-4 mx-auto">
    <div class="login-wrapper">
      <div class="login-header" style="margin-top: 75px;">
        <img src="../../assets/img/classworx.png" v-on:click="redirect('/')">
      </div>
      <span style="width:100%;float:left;text-align:center;font-size:20px;margin-bottom:20px;">
        Login to <b class="text-primary">CLASS</b><b class="text-green">WORX</b>
      </span>
      <div class="login-message-holder login-spacer" v-if="errorMessage != ''">
        <span class="text-danger"><b>Oops!</b> {{errorMessage}}</span>
      </div>
      <div>
        <div class="input-group login-spacer">
          <span class="input-group-addon" id="addon-1"><i class="fa fa-user"></i></span>
          <input type="text" class="form-control form-control-login" placeholder="Username or Email" aria-describedby="addon-1" v-model="username">
        </div>
        <div class="input-group login-spacer">
          <span class="input-group-addon" id="addon-2"><i class="fa fa-key"></i></span>
          <input type="password" class="form-control form-control-login" placeholder="********" aria-describedby="addon-2" v-model="password" @keyup.enter="logIn()">
        </div>
        <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="logIn()">Login</button>
        <!-- <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input">
            Keep me logged in
          </label>
        </div> -->
        <button class="btn btn-login-warning btn-block btn-login login-spacer" v-on:click="redirect('/request_reset_password')">Forget your Password?</button>
        <br>
        <div class="container-fluid separator">
            or
        </div>
        <br>
        <button class="btn btn-blue btn-block btn-login login-spacer" v-on:click="redirect('/signup')">Create Account Now!</button>
        <!-- <button class="btn btn-blue btn-block btn-login login-spacer" v-on:click="redirect('/login_verification/kennette/A-7FYU9RAIZSDGP2WL05H1XJVN3EKCBOMQT864')">Recover Now!</button> -->
      </div>
    </div>
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
      username: null,
      password: null,
      errorMessage: '',
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      branchesEmployees: [],
      branches: []
    }
  },
  methods: {
    logIn(){
      if(this.username !== null && this.username !== '' && this.password !== null && this.password !== ''){
        $('#loading').css({'display': 'block'})
        AUTH.authenticate(this.username, this.password, (response) => {
          this.errorMessage = null
          $('#loading').css({'display': 'none'})
          ROUTER.push('/account_settings')
        }, (response, status) => {
          $('#loading').css({'display': 'none'})
          this.errorMessage = (status === 401) ? 'Username and Password did not matched.' : 'Cannot log in? Contact us through email: support@classworx.co'
        })
      }else{
        this.errorMessage = 'Please fill up all the required fields.'
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    request(parameter){
      this.APIRequest(parameter, {}).then(response => {
      })
    }
  }
}
</script>
<style>

.login-wrapper{
  width: 80%;
  margin: 0 10% 50px 10%;
}


.login-header{
  height: 100px;
  color: #006600;
  width: 100%;
  float: left;
  text-align: center;
}

/*-- login-header --*/


.login-header img{
  width: 70px;
  height: 70px;
}

.login-header img:hover{
  cursor: pointer;
}

.login-message-holder{
  min-height: 30px;
  font-size: 12px;
  float: left;
  overflow: hidden;
}

.login-spacer{
  margin-bottom: 10px;
}/*-- login-spacer --*/

.forgot-password a{
  color: #006600 !important;
}
.forgot-password a:hover{
  cursor: pointer !important;
  text-decoration: underline !important;
  color: #009900 !important;
}

.input-group-addon{
  width: 50px;
}
/*----------------------------------------

            Forms

------------------------------------------*/
.form-control-login{
  height: 45px !important;
}


/*    Line with text on top  */
.separator>*{
  display: inline-block;
  vertical-align: middle;
}
.separator {
    text-align: center;
    border: 0;
    white-space: nowrap;
    display: block;
    overflow: hidden;
    padding: 0;
    margin: 0;
}
.separator:before, .separator:after {
    content: "";
    height: 1px;
    width: 50%;
    background-color: #ccc;
    margin: 0 5px 0 5px;
    display: inline-block;
    vertical-align: middle;
}
.separator:before {
    margin-left: -100%;
}
.separator:after {
    margin-right: -100%;
}

.btn-login-primary{
  background: #6a0090;
  color: #fff;
  height: 45px !important;
}
.btn-login-primary:hover{
  border: solid 1px #3f0050;
}

.btn-login-warning{
  color: #fff;
  background: #FCCD04;
  height: 45px !important;
}
.btn-login-warning:hover{
  color: #fff;
  border: solid 1px #bb9800;
}
.btn-blue{
  background: #028170;
  color: #fff;
  height: 45px !important;
}
.btn-blue:hover{
  border: solid 1px #026759;
}

.banner{
  width: 90%;
  float: left;
  margin-left: 10%;
}
.banner h2{
  text-transform: uppercase;
  font-weight: 600;
  color: #3f0050;
  float: left;
  width: 100%;
}
.banner span{
  width: 100%;
  float: left;
  font-size: 24px;
  color: #888;
}
.banner ul{
  list-style: none;
  width: 100%;
  margin-top: 100px;
}
.banner ul li{
  font-size: 20px;
  color: #888;
  margin-top: 10px;
}
.banner ul li i{
  padding-right: 10px;
  color: #FCCD04;
  font-weight: 700;
}

@media (max-width: 992px){
  .login-wrapper{
    width: 96%;
    margin: 0 2% 0 2%;
  }
}
</style>
