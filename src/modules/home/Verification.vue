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
          <h6 class="text-center">
            Email Confirmation
          </h6>
        </span>
        <div class="signup-holder">
          <br>  
          <div>
            <div class="login-spacer" style="margin-bottom: 25px !important;text-align: justify;">
              We send confirmation email to your email address at <b>{{email}}</b> to verify if it exist.
              Please give us a moment, it may take few minutes. Please check your email address to continue.
            </div>
            <button class="btn btn-login-danger btn-block btn-login login-spacer" v-on:click="redirect('/')">Back to Login</button>  
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
    this.email = this.$route.params.email
  },
  data(){
    return {
      email: this.$route.params.email
    }
  },
  methods: {
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
