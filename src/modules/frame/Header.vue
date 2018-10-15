 <template>
  <div>
    <div class="system-header">
      <a class="navbar-brand" v-on:click="redirect('dashboard')">
        <img src="../../assets/img/classworx_white.png" class="logo-brand">
        <label class="navbar-brand hide-on-mobile"><b>Class</b>Worx</label>
      </a>
    </div>
    <nav class="header-navbar">
      <span class="navbar-menu-toggler-md" v-bind:class="{'active-menu': menuFlag === true}" data-toggle="collapse" data-target="#ClassWorx" aria-controls="ClassWorx" aria-expanded="false" aria-label="Toggle navigation" v-on:click="makeActive('menu')">
          <i class="fa fa-bars" aria-hidden="true"></i>
      </span>
      <span class="left-menu-icons">
        <label class="account-type  hide-on-mobile" v-bind:class="{'bg-warning': user.type === 'TEACHER', 'bg-green': user.type === 'STUDENT', 'bg-gray': user.type === 'ADMIN'}" v-if="user !== null">{{user.type}}</label>
        <label class="account-type bg-green  hide-on-mobile" v-if="user.schools !== null">
          {{user.schools.school_abbreviation}}
        </label>
        <div class="dropdown semester-selector">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-if="user.selectedSemester !== null && user.selectedSemester.semester_details.description.length > 15">
            {{user.selectedSemester.semester_details.description.substr(0, 14) + ' ...'}}
          </button>
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-if="user.selectedSemester !== null && user.selectedSemester.semester_details.description.length < 15">
            {{user.selectedSemester.semester_details.description}}
          </button>
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-if="user.selectedSemester === null  && user.type === 'TEACHER'">
            Select Semester
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="user.selectedSemester === null && user.type === 'TEACHER'">
            <h6 class="dropdown-header">Select Semester</h6>
            <span class="dropdown-item" v-for="item, index in user.semesters" v-if="user.semesters !== null" v-on:click="setSemester(index)">
              <label style="padding-left: 27px;">{{item.semester_details.description.substr(0, 25)}}</label>
            </span>
          </div>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <h6 class="dropdown-header">Switch Semester</h6>
            <span class="dropdown-item" v-for="item, index in user.semesters" v-if="user.semesters !== null && user.selectedSemester !== null" v-on:click="setSemester(index)">
              <label v-if="user.selectedSemester.semester_details.id === item.semester_details.id"><i class="fa fa-check text-green" ></i>{{item.semester_details.description.substr(0, 25)}}</label>
              <label style="padding-left: 27px;" v-else>{{item.semester_details.description.substr(0, 25)}}</label>
            </span>
          </div>
        </div>
      </span>
      <span class="right-menu-icons">
          <div class="dropdown"> 
            <span class="nav-item" v-bind:class="{'active-menu': settingFlag === true}" data-toggle="dropdown" id="settings" aria-haspopup="true" aria-expanded="false" v-on:click="makeActive('dropdown')" v-bind:onkeypress="makeActive('')">
              <span>
                <i class="fa fa-cog"></i>
              </span>
              <span class="dropdown-menu dropdown-menu-right" aria-labelledby="settings">
                <span class="dropdown-item-profile">
                  <span class="account-picture text-center">
                    <span class="profile-photo-header">
                      <span class="profile-image-holder"  v-if="user.profile !== null">
                        <img v-bind:src="config.BACKEND_URL + user.profile.profile_url">
                      </span>
                      <i class="fa fa-user-circle-o" v-else></i>
                    </span>
                  </span>
                  <span class="account-info text-center">{{user.username}}</span>
                </span>
                <span class="dropdown-item dropdown-item-menu-title">
                  <label>Profile</label>
                </span>
                <span class="dropdown-item" v-on:click="redirect('/account_settings')">
                  <i class="fa fa-cog"></i>
                  <label>Account Settings</label>
                </span>
                <span class="dropdown-item dropdown-item-menu-title">
                  <label>Documents</label>
                </span>
                <span class="dropdown-item" v-on:click="redirectGuide()">
                  <i class="far fa-question-circle"></i>
                  <label>Guide</label>
                </span>
                <span class="dropdown-item" v-on:click="redirect('/privacy_policy')">
                  <i class="fas fa-shield-alt"></i>
                  <label>Privacy Policy</label>
                </span>            
                <span class="dropdown-item" v-on:click="redirect('/terms_and_conditions')">
                  <i class="fa fa-handshake-o"></i>
                  <label>Terms and Conditions</label>
                </span>
                <span class="dropdown-item dropdown-item-menu-title">
                  <label>Organizations</label>
                </span>
                 <span class="dropdown-item" v-on:click="redirect('/create_organization')">
                  <i class="fa fa-users"></i>
                  <label>Create Organization</label>
                </span>
                <span class="dropdown-item" v-on:click="logOut()">
                  <i class="fas fa-sign-out-alt"></i>
                    <label>Logout</label>
                  </span>
              </span>
            </span>
        </div>

        <div class="dropdown"> 
            <span class="nav-item" v-bind:class="{'active-menu': notifFlag === true}" data-toggle="dropdown" id="notifications" aria-haspopup="true" aria-expanded="false" v-on:click="makeActive('notif'), updateNotif(user.notifications.data[0])" v-bind:onkeypress="makeActive('')" v-if="user.notifications.data !== null">
              <span>
                <i class="fa fa-bell"></i>
                <label class="notifications" v-if="parseInt(user.notifications.current) > 0">{{user.notifications.current}}</label>
              </span>
              <span class="dropdown-menu dropdown-menu-right dropdown-menu-notification" aria-labelledby="notifications">
                <span class="notification-header">
                  Notifications
                </span>
                <span class="notification-item" v-for="item, index in user.notifications.data" v-if="user.notifications.data !== null && item.status !== 'ac_viewed'" v-on:click="executeNotifItem(item)">
                  <span class="notification-title">{{item.title}}</span>
                  <span class="notification-description">{{item.description}}</span>
                  <span class="notification-date">Posted on {{item.created_at}}</span>
                </span>
              </span>
            </span>
        </div>

        <div class="dropdown"> 
          <span class="nav-item" v-bind:class="{'active-menu': notifFlag === true}" data-toggle="dropdown" id="eventTickets" aria-haspopup="true" aria-expanded="false" v-on:click="makeActive('event')" v-bind:onkeypress="makeActive('')" v-if="user.events.data !== null">
            <span>
              <i class="fa fa-ticket"></i>
              <label class="notifications" v-if="parseInt(user.events.current) > 0">{{user.events.current}}</label>
            </span>
            <span class="dropdown-menu dropdown-menu-right dropdown-menu-notification" aria-labelledby="eventTickets">
              <span class="notification-header">
                Event Tickets
              </span>
              <span class="notification-item" v-for="item, index in user.events.data" v-if="user.events.data !== null" v-on:click="redirect('/tickets/' + item.code)">
                <span class="notification-title">{{item.event.title + ' on ' + item.event.view_date}}</span>
                <span class="notification-description">Start at {{item.event.view_start_time}} to {{item.event.view_end_time}} in {{item.event.venue}}</span>
                <span class="notification-date">Posted on {{item.created_at}}</span>
              </span>
            </span>
          </span>
        </div>

      </span>


    </nav>

         <!-- Confirmation Modal -->
    <div class="modal" id="timerHeaderModal" v-if="confirmation.message !== null">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Confirmation!</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            {{confirmation.message}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
          </div>
        </div>
      </div>
    </div>

    <div>
   </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
  },
  data(){
    return{
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      settingFlag: false,
      menuFlag: false,
      notifFlag: false,
      config: CONFIG,
      confirmation: {
        message: null,
        action: null
      },
      accountNotif: null
    }
  },
  methods: {
    makeActive(icon){
      if(icon === 'dropdown'){
        this.settingFlag = true
        this.menuFlag = false
        this.notifFlag = false
      }else if(icon === 'sidebar'){
        this.settingFlag = false
        this.menuFlag = true
        this.notifFlag = false
      }else if(icon === 'notif'){
        this.settingFlag = false
        this.menuFlag = false
        this.notifFlag = true
      }else{
        this.settingFlag = false
        this.menuFlag = false
        this.notifFlag = false
      }
    },
    logOut(){
      AUTH.deaunthenticate()
    },
    redirect(parameter){
      if(AUTH.timer.interval === null){
        this.confirmation.message = null
        ROUTER.push(parameter)
      }else{
        this.confirmation.message = 'You have an ongoing examination. You are not allowed to cancel the examination.'
        $('#timerHeaderModal').modal('show')
      }
    },
    display(){
    },
    setSemester(index){
      let semesters = this.user.semesters[index]
      let parameter = {
        'id': this.user.userID,
        'active_semester': semesters.id
      }
      this.APIRequest('accounts/update_active_semester', parameter).then(response => {
        if(response.data === true){
          ROUTER.go('/')
        }
      })
    },
    executeNotifItem(item){
      if(item.payload === 'redirect'){
        this.redirect('/' + item.url)
      }else if(item.payload === 'api_call'){
        let parameter = {
          'condition': [{
            'clause': '=',
            'column': 'id',
            'value': this.user.userID
          }]
        }
        this.APIRequest(item.url, parameter).then(response => {
          // alert here
        })
      }
    },
    updateNotif(item){
      if(parseInt(this.user.notifications.current) > 0){
        if(item.course_id !== null && item.account_id === null){
          let parameter = {
            'account_id': this.user.userID,
            'status': 'ac_viewed'
          }
          this.APIRequest('notifications/create', parameter).then(response => {
            if(response.data > 0){
              AUTH.retrieveNotifications(this.user.userID)
            }
          })
        }else{
          let parameter = {
            'id': item.id,
            'status': 'viewed'
          }
          this.APIRequest('notifications/update', parameter).then(response => {
            if(response.data === true){
              AUTH.retrieveNotifications(this.user.userID)
            }
          })
        }
      }
    },
    redirectGuide(){
      if(this.user.type === 'STUDENT'){
        this.redirect('/guide/fs')
      }else if(this.user.type === 'TEACHER'){
        this.redirect('/guide/ft')
      }
    }
  }
}
</script>
<style>

/*
        BODY
*/
body{
  font-size: 13px;
  font-weight: 400;
}

.btn{ 
  font-size: 12px;
}
.btn:hover{
  cursor: pointer;
}


.account-picture i{
  font-size: 100px !important;
}

.arrow::before{
  border-right-color: #ed2a2a !important;
  position: relative;
  border-top-color: #ed2a2a !important;
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

  .system-header{
    float: left;
    height: 50px;
    font-size: 24px;
    width: 18%;
    background: #3f0050;
    text-align: center;
    position: fixed;
    z-index: 6000;
  }
  
  .header-navbar{
    background: #6a0090;
    height: 50px;
    float: left;
    width: 82%;
    position: fixed;
    margin-left: 18%;
    z-index: 5000;
  }

  /*-- navbar --*/
  .system-header .navbar-brand{
    color: #fff;
    text-transform: uppercase;
    line-height: 30px;
  }
  
/*---------------------------------------------
 

        HEADER ACCOUNT PROFILE


-----------------------------------------------*/
  .header-navbar-nav{
      height: 50px;
      float: left;
      color: #fff;
      width: 15%;
  }
  .header-navbar-nav label{
    font-size: 13px;
    font-weight: 500;
    vertical-align: middle;
    padding-left: 10px;
  }
  .header-navbar-nav i{
    font-size: 24px;
     padding: 10px 0 0 5%;
  }

  .header-navbar-nav:hover{
    cursor: pointer;
    background: #3f0050 ;
  }


/*---------------------------------------------
       


                  ICONS


-----------------------------------------------*/

#messagesHeader{
  margin-left: 70%;
}
.nav-item{
  width: 5%;
  height: 50px;
  text-align: center;
  float: right;
  color: #fff;
  display: inline;
}
.left-menu-icons, right-menu-icons{
  height: 50px;
  float: left;
  width: 50%;
  position: fixed;
  z-index: 100;
}

.nav-item span i{
  padding: 12px 0 15px 0;
  font-size: 24px;
}

.nav-item span .notifications{
  background: #ff0000;
  color: #ffffff;
  border-radius: 5px;
  height: 20px;
  width: 20px;
  margin-left: -10px;
  font-size: 10px;
  margin-top: 15px;
  text-align: center;
  padding-top: 2px;
}

.nav-item span .notifications:hover{
  cursor: pointer;
}

.nav-item .label{
  background: #ff0000;
  padding: 5px;
  margin: -10px 0 0 -10px;
  border-radius: 2px;
  border-color: solid 1px #ff0000;
  position: fixed;
  z-index: 1;
}

.nav-item:hover{
  background: #3f0050 ;
  cursor: pointer;
}

.dropdown-menu{
  width: 250px;
  min-height: 250px;
  border-radius: 0px !important;
  padding-bottom: 0px !important;
}

.dropdown-menu-notification{
  padding-top: 0px !important;
  width: 350px !important;
  height: 400px !important;
  overflow-y: auto;

}
.dropdown-item{
  width: 100%;
  height: 40px;
  float: left;
  background: #fff;
  padding-top: 0px;
}
.dropdown-item:hover{
  background: #ddd !important;
}

.dropdown-item i{
  font-size: 14px !important;
  padding-right: 10px !important;
  color: #FCCD04 !important;
}
.dropdown-item label{
  font-size: 14px !important;
}
.dropdown-item label:hover, .dropdown-item i:hover{
  cursor: pointer;
}
.dropdown-header{
  padding: 5px 0 10px 0;
  width: 100%;
  text-align: center;
  border-bottom: solid 1px #ccc;
}
.dropdown-item-profile{
  height: auto;
  width: 100%;
  float: left;
  top: 0;
}
.dropdown-item-menu-title label{
  line-height: 40px;
  font-weight: 550;
}

.dropdown-item-menu-title:hover, .dropdown-item-menu-title label:hover{
  cursor: default;
  background: #fff !important;
}

#account{
  background: #fff;
}
.account-picture{
  height: auto;
  width: 100%;
  float: left;
  padding-bottom: 12px;
}
.account-info{
  width: 100%;
  display: inline-block;
  float: left;
  font-weight: 550;
  color: #3f0050;
  margin-top: 20px;
}
.dropdown-item-button{
  height: 50px;
  width: 100%;
  float: left;
  background: #fff;
  border-top: solid 1px #ddd;
}
.dropdown-item-button button{
  height: 40px;
  border-radius: 0;
  width: 100px;
  margin-top: 5px;  
}
.dropdown-item-button button:hover{
  background: #ff0000;
  color: #fff;
}

/*---------------------------------------------
 
 
        HEADER TOGGLER MENU


-----------------------------------------------*/
.navbar-menu-toggler-md{
  height: 50px;
  float: left;
  text-align: center;
  font-size: 16px;
  color: #fff;
  line-height: 50px;
  display: none;
}
.navbar-menu-toggler-md:hover{
  cursor: pointer;
  background: #3f0050;
}

.active-menu{
  background: #3f0050;
}


/*--------------------------------------

          PROFILE PICTURE
  
---------------------------------------*/


.profile-photo-header{
  float: left;
  width: 100%;
  height: 80px;
  color: #3f0050;
}
.profile-image-holder{
  width: 100%;
  float: left;
  height: 80px;
  text-align: center;
}
.profile-image-holder img{
  width: 80px;
  height: 80px;
  border-radius: 50%;
}

.profile-photo-header i{
  float: left;
  font-size: 80px;
  width: 100%;
}


.logo-brand{
  width: 25px;
  height: 25px;
}
.left-menu-icons .account-type{
  padding: 10px 10px 10px 10px;
  margin-top: 6px;
  margin-left: 5px;
  border-radius: 5px;
  font-size: 11px;
  color: #fff;
  float: left;
}
.semester-selector{
  float: left;
  margin-top: 6px;
}
.semester-selector button{
  height: 36px !important;
  margin-left: 5px;
}

.semester-selector .dropdown-menu{
  min-height: 100px !important;
  overflow-y: hidden;
  margin-top: 7px;
  font-size: 14px !important;
  text-align: left;
}
.semester-selector .dropdown-menu .dropdown-item{
  padding: 0px !important;
  line-height: 30px !important;
}
.semester-selector .dropdown-menu .dropdown-item i{
  padding-left: 5px;
  padding-right: 5px;
}
.semester-selector .dropdown-menu .dropdown-item:hover{
  cursor: pointer;
}

/*

      NOTIFICATION DROPDOWN

*/

.notification-header{
  width: 100%;
  float: left;
  border-bottom: solid 1px #ddd;
  padding-top: 10px;
  padding-bottom: 10px;
  text-align: center;
}
.notification-item{
  width: 100%;
  float: left;
  border-bottom: solid 1px #ddd;
}

.notification-item:hover{
  cursor: pointer;
  background: #efefef;
}

.notification-title{
  width: 96%;
  margin-right: 2%;
  margin-left: 2%;
  float: left;
  font-size: 12px;
  padding-top: 5px;
  padding-bottom: 5px;
  font-weight: 600;
}

.notification-description{
  width: 96%;
  margin-right: 2%;
  margin-left: 2%;
  float: left;
  color: #999;
  font-size: 12px;
  text-align: justify;
}

.notification-date{
  width: 96%;
  margin-right: 2%;
  margin-left: 2%;
  float: left;
  color: #999;
  font-size: 11px;
  text-align: justify;
  margin-bottom: 5px; 
}

/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/
/*-------------- Medium and Large Screens for Tablets and Desktop --------------*/

 @media (min-width: 1200px){
    .system-header{
      width: 23%;
    }
    .header-navbar{
      width: 77%;
      margin-left: 23%;
    }
    .header-navbar-nav{
      width: 15% !important;
    }
    .navbar-menu-toggler-md{
      display: none;
    }
    .left-menu-icons{
      width: 40% !important;
    }
    .right-menu-icons{
      width: 60% !important;
    }
    .nav-item{
      width: 5% !important;
    }
  }

 @media (max-width: 1199px){
    .system-header{
      width: 23%;
    }
    .header-navbar{
      width: 77%;
      margin-left: 23%;
    }
    .header-navbar-nav{
      width: 30%;
    }
    .navbar-menu-toggler-md{
      display: none;
    }
    .left-menu-icons{
      width: 60% !important;
    }
    .right-menu-icons{
      width: 40%;
    }
    .nav-item{
      width: 10%;
    }
  }

@media (max-width: 991px){
   .system-header{
      width: 30%;
    }
    .header-navbar{
      width: 70%;
      margin-left: 30%;
    }
   .header-navbar-nav{
      width: 30%;
    }
    .navbar-menu-toggler-md{
      width: 10%;
      text-align: center;
      display: block;
    }
    .left-menu-icons{
      width: 60% !important;
    }
    .right-menu-icons{
      width: 40% !important;
    }
    .nav-item{
      width: 10%;
    }
 }

/*-------------- Small Screen for Mobile Phones --------------*/
 @media (max-width: 767px){
    .header-margin{
     width: 10% !important;
    }
    
    .system-header{
      width: 15%;
    }
    
    .navbar-brand{
      width: 100% !important;
      margin: 0px !important;
    }
    
    .header-navbar{
      width: 85%;
      margin-left: 15%;
    }

    .nav-item{
      width: 20%;
    }

    .logo-brand{
      width: 30px;
      height: 30px;
    }

    .left-menu-icons{
      width: 40% !important;
    }

    .right-menu-icons{
      width: 60% !important;
    }

    .hide-on-mobile{
      display: none !important;
    }
  }

  @media (max-width: 300px){
    .logo-brand{
      width: 30px;
      height: 30px;
    }
  }
</style>
