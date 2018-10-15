<template>
  <div class="row">
    <div class="col-md-6 col-lg-4 mx-auto signup-container">
      <div class="login-wrapper">
        <div class="site-title">
          <img src="../../assets/img/classworx.png">
          <span class="app-name">
            <label class="text-primary">
              <b>Class<label style="color:#028170;">Worx</label></b>
            </label> 
          </span>
        </div>
        <br>
        <br>
<!--         <span class="login-spacer">
          <h1 class="text-primary text-center">
            <i class="fa fa-check-circle"></i>
          </h1>
        </span> -->
        <span class="login-spacer">
          <h6 class="text-center text-primary">
            Verification
          </h6>
        </span>
        <div class="signup-holder">
          <br>  
          <div>
            <div  style="margin-bottom: 25px !important;text-align: justify;" v-bind:class="{'text-danger': flag === false}" class="login-spacer">
              {{message}}
            </div>
            <button class="btn btn-login-primary btn-block btn-login login-spacer" v-on:click="update()" v-if="verified === false">Continue</button>  
            <button class="btn btn-login-danger btn-block btn-login login-spacer" v-on:click="redirect('/')" v-if="verified === true && user.userID <= 0">Back to Login</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  mounted(){
    this.username = this.$route.params.username
    this.code = this.$route.params.code
    this.retrieveAccount()
    this.setMessage()
    if(this.account !== null){
      this.validate()
    }
  },
  data(){
    return {
      username: this.$route.params.username,
      code: this.$route.params.code,
      account: null,
      flag: null,
      message: null,
      user: AUTH.user,
      verified: false
    }
  },
  methods: {
    setMessage(){
      this.message = 'Hi ' + this.username + '! Please click the button to verify your email address here in ClassWorx'
    },
    retrieveAccount(){
      let parameter = {
        'condition': [
          {
            'column': 'username',
            'value': this.username,
            'clause': '='
          },
          {
            'column': 'code',
            'value': this.code,
            'clause': '='
          }
        ]
      }
      this.APIRequest('accounts/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.account = response.data[0]
        }else{
          this.account = null
        }
      })
    },
    update(){
      this.retrieveAccount()
      console.log(this.account)
      if(this.validate() === true && this.account !== null){
        let parameter = {
          'id': this.account.id,
          'status': 'VERIFIED'
        }
        this.APIRequest('accounts/update_verification', parameter).then(response => {
          if(response.data === true){
            this.message = 'Congratulations! You\'ve have successfully verified your account. Kindly click Continue Button to login.'
            this.flag = true
            this.verified = true
          }else{
            this.flag = false
            this.verified = false
            this.message = 'Sorry! Internal Error. Kindly verify again or contact the system support.'
          }
        })
      }else{
        this.flag = false
        this.message = 'Sorry! Internal Error. Kindly verify again or contact the system support.'
      }
    },
    validate(){
      if(this.username !== null || this.code !== null){
        this.flag = true
        return true
      }else{
        this.flag = false
        return false
      }
    },
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style>
.signup-container{
  margin-top: 50px;
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
/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .custom-holder{
    box-shadow: 0 0 0 0 #fff !important;
    margin-top: 50px !important;
  }
}
</style>
