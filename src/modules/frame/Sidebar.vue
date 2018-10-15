<template>
  <div class="system-body"> 
     <div class="main-sidebar sidebar-collapse navbar-collapse collapse" v-bind:class="hide + ' ' + toggleOnClick" id="ClassWorx" >
      <div class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">
                <span v-if="toggleSidebarFlag === true" class="profile-photo">
                  <span class="profile-image-holder"  v-if="user.profile !== null">
                    <img v-bind:src="config.BACKEND_URL + user.profile.profile_url">
                  </span>
                  <i class="fa fa-user-circle-o" v-else></i>
                  Hi {{user.username}}!
                </span>
                <i v-bind:class="toggleSidebar + ' pull-right'" aria-hidden="true" v-on:click="changeToggleSidebarIcon()" id="toggleIcon"></i>
            </li>
              <li v-for="(item,index) in menu" v-bind:class="{ appActive: isActive(item.id) }" v-on:click="setActive(item.id)" v-if="((item.users === user.type || item.users === 'ALL') && user.type !== 'ADMIN') || user.type === 'ADMIN'" data-toggle="collapse" data-target="#ClassWorx" aria-expanded="false" aria-label="Toggle navigation">
                <a v-on:click="navigateTo(item.path, true)"  v-bind:class="hide">
                  <i></i> 
                  <span v-bind:class="'sm-title'" >{{item.description}}
                  </span>
                  <span v-bind:class="'pull-right-container'">  
                    <i v-bind:class="item.icon + ' pull-right'"></i>
                  </span>
                </a>
              </li>
          </ul>
        </div>
      </div>

           <!-- Confirmation Modal -->
      <div class="modal" id="timerModal" v-if="confirmation.message !== null">
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



      <div class="content-holder" v-bind:class="hide">
        <transition >
          <router-view ></router-view>
        </transition>
      </div>
    </div>
  </div>  
</template>
<script>
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import ROUTER from '../../router'
export default {
  mounted(){
  },
  data(){
    return{
      user: AUTH.user,
      config: CONFIG,
      activeItem: '',
      activeSubItem: '',
      menu: [
      // {id: 1, users: 'ALL', parent_id: 0, description: 'Dashboard', icon: 'fa fa-tachometer', path: 'dashboard'},
      // {id: 2, users: 'TEACHER', parent_id: 0, description: 'Guide', icon: 'fa fa-info', path: 'guide/ft'},
      // {id: 3, users: 'STUDENT', parent_id: 0, description: 'Guide', icon: 'fa fa-info', path: 'guide/fs'},
      {id: 4, users: 'ALL', parent_id: 0, description: 'Discussions', icon: 'fas fa-comments', path: 'discussions'},
      {id: 5, users: 'ADMIN', parent_id: 0, description: 'Reported Users', icon: 'fas fa-eye', path: 'discussion_reports'},
      {id: 20, users: 'TEACHER', parent_id: 0, description: 'Semesters', icon: 'fa fa-sitemap', path: 'semesters'},
      {id: 21, users: 'TEACHER', parent_id: 0, description: 'Courses', icon: 'fa fa-code-fork', path: 'courses'},
      // {id: 23, users: 'TEACHER', parent_id: 0, description: 'Announcements', icon: 'far fa-paper-plane', path: 'announcements/ft'},
      // {id: 24, users: 'TEACHER', parent_id: 0, description: 'Attendance', icon: 'far fa-clock-o', path: 'attendance/ft'},
      // {id: 25, users: 'TEACHER', parent_id: 0, description: 'Calendar', icon: 'fa fa-calendar', path: 'calendar/default'},
      // {id: 25, users: 'TEACHER', parent_id: 0, description: 'Discussions', icon: 'fa fa-bullhorn', path: 'discussions/default'},
      // {id: 26, users: 'TEACHER', parent_id: 0, description: 'Tests', icon: 'fa fa-file-text-o', path: 'tests/ft/default'},
      // {id: 27, users: 'TEACHER', parent_id: 0, description: 'Resources', icon: 'fa fa-files-o', path: 'resources/ft/default'},
      // {id: 30, users: 'STUDENT', parent_id: 0, description: ' My Announcements', icon: 'far fa-paper-plane', path: 'announcements/fs'},
      {id: 31, users: 'STUDENT', parent_id: 0, description: 'My Courses', icon: 'fa fa-clipboard', path: 'courses/en'},
      // {id: 32, users: 'STUDENT', parent_id: 0, description: 'My Tests', icon: 'fa fa-file-text-o', path: 'tests/fs/default'},
      // {id: 33, users: 'STUDENT', parent_id: 0, description: 'My Resources', icon: 'fa fa-file-text-o', path: 'resources/fs'},
      // {id: 90, users: 'STUDENT', parent_id: 0, description: 'Reports', icon: 'fa fa-line-chart', path: 'reports/fs'},
      // {id: 91, users: 'TEACHER', parent_id: 0, description: 'Reports', icon: 'fa fa-line-chart', path: 'reports/ft'},
      // {id: 90, users: 'ALL', parent_id: 0, description: 'Planner', icon: 'fas fa-tasks', path: 'planner'},
      // {id: 91, users: 'ALL', parent_id: 0, description: 'Calendar', icon: 'fa fa-calendar', path: 'calendar'},
      {id: 100, users: 'ALL', parent_id: 0, description: 'Organizations', icon: 'fas fa-users', path: 'organizations'},
      {id: 150, users: 'ALL', parent_id: 0, description: 'Account Settings', icon: 'fa fa-cog', path: 'account_settings'},
      {id: 200, users: 'ADMIN', parent_id: 0, description: 'Accounts', icon: 'fa fa-users', path: 'accounts'}
      ],
      toggleSidebar: 'fa fa-toggle-on',
      toggleSidebarFlag: true,
      hide: '',
      toggleOnClick: '',
      alignAtHide: 'pull-right',
      search: '',
      flag: false,
      confirmation: {
        message: null,
        action: null
      }
    }
  },
  methods: {
    getMenu(){
      let parameter = {
        'sort': {
          'id': 'asc'
        }
      }
      this.APIRequest('modules/retrieve', parameter).then(response => {
        this.menu = response.data
      })
    },
    isActive(menuItem){
      return this.activeItem === menuItem
    },
    setActive(menuItem){
      this.activeItem = menuItem
      var intMenu = parseInt(menuItem)
      var intSubMenu = parseInt(this.activeSubItem)
      this.activeSubItem = (intSubMenu < (intMenu + 10) && intSubMenu > intMenu) ? this.activeSubItem : ''
    },
    isSubActive(menuItem){
      return this.activeSubItem === menuItem
    },
    setSubActive(menuItem){
      this.activeSubItem = menuItem
      this.activeItem = ''
    },
    navigateTo(method, toggleCondition){
      if(AUTH.timer.interval === null){
        this.confirmation.message = null
        this.toggleOnClick = (toggleCondition === true) ? 'collapse' : ''
        ROUTER.push('/' + method)
      }else{
        this.confirmation.message = 'You have an ongoing examination. You are not allowed to cancel the examination.'
        $('#timerModal').modal('show')
      }
    },
    changeToggleSidebarIcon(){
      this.toggleSidebarFlag = !this.toggleSidebarFlag
      this.hide = (this.toggleSidebarFlag === true) ? '' : 'hidden'
      this.alignAtHide = (this.toggleSidebarFlag === false) ? 'text-center' : 'pull-right'
      var icon = (this.toggleSidebarFlag === true) ? 'on' : 'off'
      this.toggleSidebar = 'fa fa-toggle-' + icon
    }
  },
  computed: {
    filteredModules: function(){
      let regex = new RegExp(this.search.toLowerCase())
      return this.menu.filter((menu) => {
        return menu.description.toLowerCase().match(regex)
      })
    }
  }
}
</script>
<style>

.main-sidebar, .content-holder{  
  min-height: 84.5vh;
  overflow: hidden;
  transition: all 1s ease 0s;
  z-index: 1;
  margin-top: 50px;
}
.main-sidebar{
  position: fixed;
  overflow-y: auto;
}

.main-sidebar i{
  padding:0 10px 0 10px;
}
.sidebar-menu .header{
  font-weight: 700; 
  padding: 15px 2% 15px 2%;
  color: #000;
  text-align: center;
}
.sidebar-menu, .sidebar-menu  ul {
  list-style: none;
  margin: 0;
  padding: 0;
  color: #000;
}
.header .input-group{
  width: 80%;
  float: left;
}
.header .input-group div{
  width: 15%;
  float: left;
  font-size: 10px;
  padding: 0;
  background: #fff !important;
}

.header .input-group div i{
  padding: 0;
  font-size: 12px;
}
.header .switch{
  float: left;
  width: 20%;
}
.header i{
  font-size: 24px;
  color: #3f0050;
  }/*-- toggle-sidebar i --*/
.header i:hover{
  cursor: pointer;
  color: #6a0090;
}

.profile-photo{
  float: left;
  width: 100%;
  height: 100px;
  margin-top: 20px;
}

.profile-image-holder{
  width: 100%;
  float: left;
  height: 80px;
  margin-bottom: 10px;
  text-align: center;
}
.profile-image-holder img{
  width: 80px;
  height: 80px;
  border-radius: 50%;
}

.profile-photo i{
  float: left;
  font-size: 80px;
  width: 100%;
  height: 80px;
  margin-bottom: 10px;
}


/*-- .toggle-sidebar i:hover --*/
.sidebar-menu li{
  min-height: 40px;
  overflow-x: hidden;
  vertical-align: middle;
  border-bottom: solid #eee 1px;
}
.sidebar-menu ul li{
  min-height: 30px;
  overflow-x: hidden;
}
.sidebar-menu li, .sidebar-menu ul > li {
  position: relative;
  margin: 0;
  padding: 0;
}

/*padding: 13px 2% 13px 5%;*/
.sidebar-menu  li > a{
  display: block;
  padding: 13px 2% 13px 2%;
}

/*padding: 10px 20px 10px 50px;*/
.sidebar-menu  ul li > a{
  padding: 10px 5% 10px 3%;
  display: block;
}
.sidebar-menu li > a:hover,.sidebar-menu ul li > a :hover{
    cursor: pointer;
    background: #eaeaea;
}
.appActive, .appSubActive{
  background: #eaeaea;
}/*-- app-active --*/

.appActive ul{
  background: #f4f4f4;
}

/*---------------------------------------------------------          

                  RESPONSIVE HANDLER

-----------------------------------------------------------*/
/*-------------- Large Screens for Desktop --------------*/
@media (min-width: 1200px){
  .main-sidebar{
    width:18%;
    float: left;
  }
  .sidebar-collapse{
    display: block;
  }
  .sidebar-menu .header span{
    display: block;
  }
  .content-holder{
    width: 79%;
    margin: 60px 1% 0 19%;
    float: left;
  }
  /*  Change with Menu Toggled */
  .main-sidebar.hidden{
    margin-left: -14%;
  }
  .content-holder.hidden{
    width: 94%;
    margin: 60px 1% 0 1%;
    float: left;
  }
}

/*-------------- Medium Screen for Tablets  --------------*/
@media screen (min-width: 992px), screen and (max-width: 1199px){
  .main-sidebar{
    width: 23%;
    float: left;
  }
  .content-holder{
    width: 71%;
    margin: 60px 2% 0 25%;
    float: left;
  }
  .main-sidebar.active{
    padding-left:15%;
  }
  .sidebar-collapse{
    display: block;
  }
  .sidebar-menu .header span{
    display: block;
  }

  /*  Change with Menu Toggled */
  .main-sidebar.hidden{
    margin-left: -18%;
  }
  .content-holder.hidden{
    width: 92%;
    margin: 60px 2% 0 2%;
    float: left;
  }
}
/*-------------- Small Screen for Mobile Phones  --------------*/
/*@media screen (min-width: 768px), screen and (max-width: 991px){
  .main-sidebar{
    width: 90%;
    position: absolute;
    top:0;
    left: 0;
    z-index: 10;
    background: #fff;
  }
  .content-holder{
    width: 96%;
    margin: 60px 2% 0 2%;
  }
  .sm-title{
    text-align: center;
  }
   .sidebar-collapse{
    display: none;
  }
  .sidebar-menu .header span{
    display: none;
  }
  
  .main-sidebar.hidden{
     margin-left: 0;
  }
  #toggleIcon{
    display: none;
  }
  .force-collapse{
    display: none;
  }
}*/

/*-------------- Extra Small Screen for Mobile Phones --------------*/
@media (max-width: 991px){
  .main-sidebar{
    width: 100%;
    position: absolute;
    top:0;
    left: 0;
    z-index: 10;
    background-color: rgba(0,0,0,0.5);
    margin-top: 50px;
  }
  .content-holder{
    width: 10px;
    min-width: 96%;
    overflow-y: hidden;
    margin: 60px 2% 0 2%;
    float: left;
  }
  .main-sidebar ul{
    background: #fff;
    width: 90%;
    min-height: 84.5vh;
  }
   .sm-title{
    text-align: center;
  }

  .sidebar-menu .header span{
    display: none;
  }
  .main-sidebar.hidden{
    margin-left: 0;
  }
  .header .input-group{
    width: 90%;
    margin: 0 5% 0 5%;
  }
  #toggleIcon{
    display: none;
  }
  .force-collapse{
    display: none;
  }

  .collapse{
    display: none !important;
  }
  .collapse.show{
    display: block !important;
  }
}
@media (max-width: 239px){

  .collapse{
    display: none !important;
  }

  .collapse.show{
    display: block !important;
  }
  .main-sidebar{
    width: 90%;
    position: absolute;
    top:0;
    left: 0;
    z-index: 10;
    background: #fff;
    margin-top: 100px;
  }

  .content-holder{
    width: 96%;
    margin: 60px 2% 0 2%;
    float: left;
  }
  .sm-title,.header{
    display: none;
  }
  .sidebar-menu  li > a{
  padding: 15px 20px 15px 15px;
  display: block;
  }
  .sidebar-menu  ul li > a{
  padding: 10px 15px 10px 25px;
  display: block;
  }
  .sidebar-menu .header span{
    display: none;
  }
  .main-sidebar.hidden{
    margin-left: 0;
  }
  .header .input-group{
    width: 90%;
    margin: 0 5% 0 5%;
  }
  #toggleIcon{
    display: none;
  }

  .force-collapse{
    display: none;
  }
}
</style>
