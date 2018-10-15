<template>
  <div>
    <span class="post-container">
      <span class="post-content-holder">
        <button class="btn btn-primary pull-right" style="margin-bottom: 20px;" data-toggle="modal" data-target="#postAnnouncementsModal">
          <i class="fa fa-plus"></i> New Announcement
        </button>
        <span class="post-item" v-for="item, index in announcement"v-if="announcement !== null">
          <span class="post-item-header">
            <img v-bind:src="config.BACKEND_URL + item.account.profile.profile_url" v-if="item.account.profile !== null">
            <i class="fa fa-user-circle" v-else></i>
            <label>{{item.account.username}}</label>
            <div class="dropdown pull-right" v-if="parseInt(item.account_id) === user.userID">
              <i class="fas fa-ellipsis-h text-gray more-options" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              </i>
              <div class="dropdown-menu dropdown-more-options" aria-labelledby="dropdownMenuButton">
                <span class="dropdown-item" v-on:click="editAnnouncements(index)" v-if="parseInt(item.account_id) === user.userID">Edit</span>
                <span class="dropdown-item text-danger" v-on:click="deleteAnnouncements(item.id)"  v-if="parseInt(item.account_id) === user.userID">Delete</span>
                <!-- <span class="dropdown-item more-option-title">Report this as</span>
                <span class="dropdown-item" v-on:click="report(item.id, 'not_relevant')">Not Relevant</span> -->
              </div>
            </div>
          </span>
          <span class="post-item-time">
            Posted on: {{item.created_at}}
          </span>
          <span class="post-item-content" v-if="item.edit_flag === false">
            {{item.message}}
          </span>
          <span class="post-item-content" v-else>
            <textarea v-model="item.message"></textarea>
            <button class="btn btn-primary pull-right" v-on:click="updateAnnouncements(item)">Update</button>
            <button class="btn btn-danger pull-right" v-on:click="cancelEditAnnouncements(index)" style="margin-right: 10px;">Cancel</button>
          </span>
        </span>
      </span>
  </span>
  <span class="post-sidebar">
      
  </span>
  <create></create>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'create': require('modules/announcement/Create.vue')
  },
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      editTestData: null
    }
  },
  props: ['announcement'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    editAnnouncements(index){
      this.announcement[index].edit_flag = true
    },
    cancelEditAnnouncements(index){
      this.announcement[index].edit_flag = false
    },
    deleteAnnouncements(id){
      let parameter = {
        id: id
      }
      this.APIRequest('announcements/delete', parameter).then(response => {
        this.$parent.retrieveRequest()
      })
    },
    updateAnnouncements(item){
      let parameter = {
        id: item.id,
        message: item.message
      }
      this.APIRequest('announcements/update', parameter).then(response => {
        if(response.data === true){
          this.$parent.retrieveRequest()
        }
      })
    }
  }
}
</script>
<style scoped>

.post-container{
  width: 100%;
  float: left;
}
.post-content-holder{
  width: 60%;
  float: left;
  margin-right: 5%;
}
.post-item{
  width: 100%;
  float: left;
  margin-top: 20px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border: solid 1px #ddd;
}
.post-item-header, .post-item-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}
.post-item-header{
  margin-top: 10px;
}

.post-item-header .more-options:hover{
  cursor: pointer;
}
.post-item-header img, .comment-header img, .reply-header img{
  height: 30px;
  width: 30px;
  border-radius: 50%;
  margin-right: 10px;
}
.post-item-header i, .comment-header i, .reply-header i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
}
.post-item-header label, .comment-header .username, .reply-header .username{
  padding-top: 5px;
  font-weight: 550;
  color: #3f0050;
}

.dropdown-more-options{
  min-height: 50px !important;
  padding-top: 0px !important;
  padding-bottom: 0px !important;
  overflow-y: hidden;
}

.dropdown-more-options .dropdown-item{
  font-size: 13px !important;
  padding-top: 5px !important;
  padding-bottom: 5px !important;
  height: 30px !important;
}
.dropdown-more-options .dropdown-item:hover{
  cursor: pointer;
}

.more-option-title{
  font-weight: 700;
}
.dropdown-more-options .more-option-title:hover{
  cursor: default;
}


.post-item-time{
  width: 96%;
  float: left;
  font-size: 12px;
  color: #999;
  margin: 0 2% 0 2%;
}
.post-item-content{
  width: 96%;
  float: left;
  min-height: 40px;
  overflow-y: hidden;
  margin: 0 2% 15px 2%;
}

.post-item-content textarea{
  float: left;
  width: 100%;
  border: none;
  min-height: 70px;
}

.post-item-footer .footer-menu{
  padding: 0px !important;
  list-style: none;
  width: 100%;
  float: left;
  border-top: solid 1px #ddd;
}
.post-item-footer .footer-menu li{
  float: left;
  width: 50%;
  padding-top: 10px;
  padding-bottom: 10px;
}
.post-item-footer .footer-menu li:hover{
  cursor: pointer;
}
.post-item-footer .footer-menu li i{
  font-size: 16px;
  padding-right: 10px;
}
</style>
