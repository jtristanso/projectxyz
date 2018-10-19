<template>
  <div>
    
    <div class="module-header">
      <div class="items-display pull-right">
        <label>Messenger</label>
        <select v-model="selectedId" v-on:change="selectAffiliates()" v-if="myAffiliates !== null">
          <option v-for="item, index in myAffiliates" v-bind:value="item.id">{{item.name}}</option>
        </select>
        <label class="text-danger" v-else>EMPTY</label>
      </div>
      <div class="add">
        <button class="btn btn-primary pull-right" v-on:click="retrieveAllSchoolOrgs()" v-if="schoolOrg === null">
          Show List of Organizations
        </button>
        <button class="btn btn-warning pull-right" v-on:click="hideList()" v-if="schoolOrg !== null">
          Hide list of Organizations
        </button>
      </div>

      <div class="org-logo" v-if="selected !== null && schoolOrg === null && selected.member_type === 'ADMIN'"> 
        <span v-on:click="addLogo()" v-if="selected.logo === null">
          <i class="fa fa-users" style="padding-right: 10px;"  title="Click to add logo"></i>
          <input type="file" id="addLogo" name="file" accept="image/*"  @change="setUpFileUpload($event)">
        </span>
        <span v-else v-on:click="addLogo()">
          <img :src="config.BACKEND_URL + selected.logo.url">
          <input type="file" id="addLogo" name="file" accept="image/*"  @change="setUpFileUpload($event)">
        </span>
      </div>

      <div class="org-logo org-logo-user" v-if="selected !== null && schoolOrg === null && selected.member_type !== 'ADMIN'"> 
        <span v-if="selected.logo === null">
          <i class="fa fa-users" style="padding-right: 10px;"  ></i>
        </span>
        <span v-else>
          <img :src="config.BACKEND_URL + selected.logo.url">
        </span>
      </div>
      
      <div class="org-info"  v-if="selected !== null && schoolOrg === null">
        <span class="title">
          <label class="text-warning">{{selected.name}}</label>
        </span>
        <span>
          <i class="fas fa-tags" style="padding-right: 10px;"></i>
          <label v-if="selected.type === 'curricular'">Curricular</label>
          <label v-if="selected.type === 'extra_curricular'">Extra Curricular</label>
        </span>
        <span>
          <i class="fa fa-university" style="padding-right: 10px;"></i><label>{{selected.school.name}}</label>
        </span>
        <span v-if="selected.address !== null">
          <i class="fas fa-map-marker-alt" style="padding-right: 10px;"></i><label>{{selected.address}}</label>
        </span>
      </div>
    </div>

    <ul class="org-menu hide-on-mobile" v-if="selected !== null && schoolOrg === null">
      <li class="menu-item" v-for="item, index in menus" v-bind:class="{'bg-selected-org-menu': item.flag === true}" v-on:click="setMenu(index)">
        <i class="fa text-primary" v-bind:class="item.icon"></i>{{item.title}}
      </li>
    </ul>

    <span class="hide-on-large org-mobile-menu" v-if="schoolOrg === null">
      <ul class="pagination">
        <li class="page-item prev" v-on:click="mobileMenuPrev()">
          <span class="page-link"><i class="fa fa-chevron-left"></i> Previous</span>
        </li>
        <li class="page-item menu" v-for="item, index in 1">
          <span class="page-link">
            <i class="fa" v-bind:class="menus[index + (prevMenuIndex * 1)].icon"></i>
            {{menus[index  + (prevMenuIndex * 1)].title}}
          </span>
        </li>
        <li class="page-item next" v-on:click="mobileMenuNext()">
          <span class="page-link"><i class="fa fa-chevron-right"></i> Next</span>
        </li>
      </ul>
    </span>

    <div class="organizations-holder" v-if="schoolOrg === null && menus[0].flag === true && selected !== null">
      <organization-discussions :payload="'organization'" :payloadValue="selected.id"></organization-discussions>
    </div>

    <div class="organizations-holder" v-if="schoolOrg === null && menus[1].flag === true && selected !== null">
      <div class="organizations-list">
        <button class="btn btn-primary pull-right" v-if="selected.member_type === 'ADMIN'" data-toggle="modal" data-target="#createEventModal"><i class="fa fa-plus"></i> New Event</button>
        <event-item class="event-item" v-for="item, index in selected.events" :item="item" :key="item.id" :memberType="selected.member_type"></event-item>
      </div>
      <div class="organizations-sidebar">
        
      </div>
    </div>

    <div class="organizations-holder" v-if="schoolOrg === null && menus[2].flag === true && selected !== null">
      <div class="organizations-list">
        <organization-member :id="selected.id" :memberType="selected.member_type"></organization-member>
      </div>
      <div class="organizations-sidebar">
        
      </div>
    </div>


    <div class="organizations-holder" v-if="schoolOrg === null && menus[3].flag === true && selected !== null">
      <span class="about-title">About</span>
      <span class="about-content">
        {{selected.about}}
      </span>
      <span class="about-title">Our Vision</span>
      <span class="about-content">
        {{selected.vision}}
      </span>
      <span class="about-title">Our Mission</span>
      <span class="about-content">
        {{selected.mission}}
      </span>
    </div>




    <div class="organizations-holder">
      <div class="organizations-list">
        <organization-item class="organizations-item" v-for="item, index in schoolOrg" v-if="schoolOrg !== null" :item="item" :key="item.id"></organization-item>
      </div>
      <div class="organizations-sidebar"></div>
    </div>

    <div class="customModal" id="loadingModalCourse">
      <span class="loading">
        <i class="fas fa-spinner fa-spin"></i>
      </span>
    </div>

    <create-event :params="selected.id" v-if="selected !== null"></create-event>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import axios from 'axios'
export default {
  mounted(){
    this.retrieveMyAffiliates()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      selectedId: null,
      selected: null,
      data: null,
      schoolOrg: null,
      myAffiliates: null,
      prevMenuIndex: 0,
      menus: [
        {'icon': 'fa-comments', 'title': 'Discussions', flag: true},
        {'icon': 'fa-calendar', 'title': 'Events', flag: false},
        {'icon': 'fa-users', 'title': 'Members', flag: false},
        {'icon': 'fa-info', 'title': 'About', flag: false}
      ],
      file: null
    }
  },
  components: {
    'create-event': require('modules/event/Create.vue'),
    'event-item': require('modules/event/Item.vue'),
    'organization-item': require('modules/organization/ListItem.vue'),
    'organization-member': require('modules/organization/Member.vue'),
    'organization-discussions': require('modules/discussion/Discussion.vue')
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveMyAffiliates(){
      let parameter = {
        account_id: this.user.userID
      }
      this.APIRequest('organizations/retrieve_my_affiliates', parameter).then(response => {
        if(response.data.length > 0){
          this.myAffiliates = response.data
          if(this.user.organization !== null){
            this.completeSelected()
          }else{
            this.selected = response.data[0]
            this.selectedId = this.selected.id
          }
        }else{
          this.myAffiliates = null
          this.retrieveAllSchoolOrgs()
        }
      })
    },
    completeSelected(){
      for (let i = 0; i < this.myAffiliates.length; i++) {
        if(this.user.organization === this.myAffiliates[i].id){
          this.selected = this.myAffiliates[i]
          this.selectedId = this.selected.id
          break
        }
      }
    },
    selectAffiliates(){
      this.schoolOrg = null
      let parameter = {
        id: this.user.userID,
        active_organization: this.selectedId
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('organizations/update_active_org', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data === true){
          ROUTER.go('/')
        }
      })
    },
    hideList(){
      this.schoolOrg = null
    },
    retrieveAllSchoolOrgs(){
      let parameter = {
        condition: [
          {
            value: this.user.schools.id,
            clause: '=',
            column: 'school_id'
          },
          {
            value: 'APPROVED',
            clause: '=',
            column: 'status'
          }
        ],
        account_id: this.user.userID
      }
      this.APIRequest('organizations/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.schoolOrg = response.data
        }else{
          this.schoolOrg = null
        }
      })
    },
    setMenu(index){
      if(this.prevMenuIndex === index){
        //
      }else{
        this.menus[this.prevMenuIndex].flag = false
        this.menus[index].flag = true
        this.prevMenuIndex = index
      }
    },
    mobileMenuNext(){
      if(this.prevMenuIndex < (this.menus.length - 1)){
        this.menus[this.prevMenuIndex].flag = false
        this.prevMenuIndex++
        this.menus[this.prevMenuIndex].flag = true
      }
    },
    mobileMenuPrev(){
      if(this.prevMenuIndex > 0){
        this.menus[this.prevMenuIndex].flag = false
        this.prevMenuIndex--
        this.menus[this.prevMenuIndex].flag = true
      }
    },
    addLogo(){
      $('#addLogo')[0].click()
    },
    setUpFileUpload(event){
      let files = event.target.files || event.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.file = files[0]
        this.createFile(files[0])
      }
    },
    createFile(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      this.updateLogo()
    },
    updateLogo(){
      if(this.file !== null){
        let formData = new FormData()
        formData.append('organization_id', this.selected.organization_id)
        formData.append('file', this.file)
        formData.append('logo_url', this.file.name)
        axios.post(this.config.BACKEND_URL + '/organization_logos/create', formData).then(response => {
          ROUTER.go('/affiliates')
        })
      }
    }
  }
}
</script>
<style scoped>

.org-logo{
  width: 20%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

.org-logo span{
  width: 100%;
  float: left;
}

.org-logo i{
  font-size: 150px;
}

.org-logo img{
  width: 80%;
  border-radius: 50%;
}

.org-logo span input{
  display: none;
}

.org-logo span:hover{
  cursor: pointer;
}

.org-logo-user span:hover{
  cursor: default;
}

.org-info{
  width: 80%;
  float: left;
}

.org-info span{
  width: 100%;
  float: left;
}

.org-info .title{
  font-size: 24px;
  font-weight: 550;
}
.org-menu{
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  border-bottom: solid 1px #ddd;
  list-style: none;
  padding: 0px !important;
}

.org-menu .bg-selected-org-menu{
  border-bottom: solid 2px #3f0050;
}
.org-menu .menu-item-first{
  float: left;
  padding: 15px 20px 15px 0px;
}
.org-menu .menu-item{
  float: left;
  padding: 15px 40px 15px 0px;
}
.org-menu li i{
  padding-right: 5px;
}
.org-menu li:hover{
  cursor: pointer;
}


.organizations-holder{
  width: 90%;
  margin-left: 5%;
  margin-right: 5%;
  float: left;
}

.organizations-list{
  width: 65%;
  float: left;
  min-height: 100px;
  overflow-y: hidden;
}

.organizations-sidebar{
  width: 30%;
  margin-left: 5%;
  float: left;
  min-height: 100px;
  overflow-y: hidden;
}

.organizations-item{
  width: 100%;
  float: left;
  min-height: 200px;
  overflow-y: hidden;
  margin-top: 20px;
}

.organizations-holder .about-title{
  width: 100%;
  float: left;
  text-align: justify;
  margin-top: 10px;
  margin-bottom: 10px;
  font-weight: 600;
}

.organizations-holder .about-content{
  width: 100%;
  float: left;
  text-align: justify;
}

.event-item{
  width: 100%;
  float: left;
  margin-top: 20px;
}

</style>
