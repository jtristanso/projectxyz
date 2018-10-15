<template>
  <footer>
    <div class="footer">
      <ul class="footer-widget contact-us">
        <li class="title"><b>ClassWorx</b></li>
        <li class="link"><a v-on:click="redirect('/')">Home</a></li>
       <!--  <li class="link"><a v-on:click="redirect('/')">About Us</a></li>
        <li class="link"><a v-on:click="redirect('/')">Contact Us</a></li> -->
      </ul>
      <ul class="footer-widget">
        <li class="title"><b>Privacy and Terms</b></li>
        <li class="link"><a v-on:click="redirect('/terms_and_conditions')">Terms & Conditions</a></li>
        <li class="link"><a v-on:click="redirect('/privacy_policy')">Privacy Policy</a></li>
      </ul>
       <ul class="footer-widget">
        <li class="title"><b>Resources</b></li>
        <li class="link"><a v-on:click="redirect('/guide/ft')">Teacher's Guide</a></li>
        <li class="link"><a v-on:click="redirect('/guide/fs')">Student's Guide</a></li>
      </ul>
      <span class="footer-widget community">
        <span class="title"><b>Community</b></span>
        <span class="link">
          <a target="_BLANK" class="text-gray" href="https://www.facebook.com/classworx"><i class="fab fa-facebook"></i></a>
        </span>
      </span>
      
      <span class="copyright">
        <label>Copyright @ClassWorx 2018. All rights reserved.</label>
      </span>
    </div>
  </footer>
</template>
<script>
import ROUTER from '../../../router'
import AUTH from '../../../services/auth'
import CONFIG from '../../../config.js'
export default {
  mounted(){
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      account: [],
      config: CONFIG,
      host: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
footer {
  width:100%;
  min-height: 200px;
  float: left;
  overflow-y: hidden;
  background: #e9e9e9;
  margin-top: 50px;
}
.footer{
  width: 80%;
  float: left;
  min-height: 50px;
  margin-left: 10%; 
  margin-right: 10%;
  overflow-y: hidden;
}

.footer-widget{
  width: 25%;
  float: left;
  margin-top: 50px;
  list-style: none;
  padding: 0px;
  margin-bottom: 0px;
}


.footer-widget li{
  list-style: none;
  padding: 5px 10px 5px 10px;
  float: left;
  width: 100%;
  color: #6f6f6f !important;
}

.footer-widget, .footer-widget li a{
  color: #6f6f6f !important;
}
.footer-widget .title{
  color: #4c4c4c;
}

.footer-widget li a:hover{
  cursor: pointer;
  text-decoration: underline;
}
footer-widget .title:hover{
  cursor: default;
}

.community .title{
  width: 100%;
  float: left;
  height: 40px;
  text-align: left;
  padding-top: 5px;
} 
.community .links{
  width: 100%;
  float: left;
}
.community .link i{
  font-size: 24px;
  padding: 0 10px 0 10px;
  float: left;
  width: 20%;
}
.community .link i:hover{
  color: #1caceb;
  cursor: pointer;
}
.text-gray{
  color: #212529;
}

.contact-us li i{
  padding-right: 10px;
}

.contact-us li .fa-phone{
  padding-right: 9px;
}

.contact-us li .fa-mobile-alt{
  padding-right: 12px;
}

.copyright{
  width: 100%;
  float: left;
  padding-top: 5px;
  padding-bottom: 5px;
  font-size: 12px;
  text-align: center;
  margin-top: 45px;
  color: #6f6f6f !important;
}
@media screen and (max-width: 992px){
  .footer-widget{
    width: 100%;
    text-align: center;
    margin-right: 0%;
    padding: 0px;
  }

  .community .title{
    text-align: center;
  }

  .community i{
    width: 20%;
  }
}
</style>
