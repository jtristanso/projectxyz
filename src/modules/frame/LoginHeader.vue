 <template>
  <div v-if="!tokenData.verifyingToken && !tokenData.token">
    <div class="login-content-holder">
      <transition>
          <router-view ></router-view>
      </transition> 
    </div> 
  </div> 
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style type="text/css">


/*
        BODY
*/
body{
  font-size: 13px;
  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
  font-weight: 400;
}

/*
        BUTTONS
*/

.btn{
  font-size: 12px;
}
.btn:hover{
  cursor: pointer;
}


/*------------------------------------

          TABLES

--------------------------------------*/

.table{
  font-size: 12px;
}


/*----------------------------------------

            Forms

------------------------------------------*/
.form-control{
  height: 35px;
  font-size: 12px;
}

/*----------------------------------------

            Contents

------------------------------------------*/


  .login-system-header{
    float: left;
    height: 50px;
    font-size: 24px;
    width: 18%;
    background: #fff;
    text-align: center;
    border-bottom: solid 1px #eee;
  }
  .login-header-navbar{
    background: #fff;
    height: 50px;
    float: left;
    width: 82%;
    border-bottom: solid 1px #eee;
  }/*-- navbar --*/
  .login-system-header .navbar-brand{
    color: #009900;
  }

  .navbar-brand:hover{
    cursor: pointer;
  }
  

  .login-content-holder{
    width: 100%;
    float: left;
    min-height: 87.5vh;
    overflow: hidden;
  }
/*---------------------------------------------
 

        HEADER ACCOUNT PROFILE


-----------------------------------------------*/
  .login-header-navbar-nav{
      height: 50px;
      float: right;
      color: #fff;
      width: 20%;
  }
  .login-header-navbar-nav label{
    font-size: 13px;
    font-weight: 500;
    vertical-align: middle;
    padding-left: 10px;
  }
  .login-header-navbar-nav i{
    font-size: 24px;
     padding: 10px 0 0 5%;
  }

  .login-header-navbar-nav:hover{
    cursor: pointer;
    background: #009900;
  }


/*---------------------------------------------
 
 
        ICONS


-----------------------------------------------*/

#messagesHeader{
  margin-left: 70%;
}
.login-nav-item{
  width: 15%;
  height: 50px;
  text-align: center;
  float: left;
  color: #009900;
  display: inline;
}
.login-header-margin{
  height: 50px;
  float: left;
  width: 70%;
}

.login-nav-item span i{
  padding: 12px 0 15px 0;
  font-size: 16px;
}

.login-nav-item .label{
  z-index: 1000;
  background: #ff0000;
  padding: 5px;
  font-size: 8px;
  margin: -10px 0 0 -10px;
  border-radius: 2px;
  border-color: solid 1px #009900;
}

.login-nav-item:hover{
  background: #009900;
  cursor: pointer;
  color: #fff;
}

#book-now{
  border-right: solid 1px #eee;
  border-left: solid 1px #eee;
}


/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/
/*-------------- Medium and Large Screens for Tablets and Desktop --------------*/

 @media screen (min-width: 1200px){
    .login-system-header{
      width: 23%;
    }
    .login-header-navbar{
      width: 77%;
    }
    .login-header-margin{
      width: 70%;
    }
    .login-nav-item{
      width: 15%;
    }
  }

 @media screen (min-width: 992px), screen and (max-width: 1199px){
    .login-system-header{
      width: 23%;
    }
    .login-header-navbar{
      width: 77%;
    }
    .login-header-margin{
      width: 60%;
    }
    .login-nav-item{
      width: 20%;
    }
  }

@media screen (min-width: 768px), screen and (max-width: 991px){
   .login-system-header{
      width: 100%;
    }
    .login-header-navbar{
      width: 100%;
    }
    .login-header-margin{
      width: 40%;
    }
    .login-nav-item{
      width: 30%;
    }
 }

/*-------------- Small Screen for Mobile Phones --------------*/
 @media (max-width: 767px){
    .login-header-margin{
     width: 0%;
    }
    .login-system-header{
      width: 100%;
    }
    .login-header-navbar{
      width: 100%;
    }
    .login-nav-item{
      width: 50%;
    }
  } 

</style>
