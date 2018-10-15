<template>
  <div class="member-holder" v-if="data !== null">
    <div class="org-member-item" v-for="item, index in data">
      <div class="item-holder">
          <span class="profile-picture">
            <img :src="config.BACKEND_URL + item.profile_url" v-if="item.profile_url !== null">
            <i class="fa fa-user-circle-o" v-else></i>
          </span>
          <span class="member-details">
            <label class="name text-primary"><b>{{item.first_name + ' ' + item.last_name}}</b></label>
            <label>{{item.degree.course}}</label>
            <label>{{item.degree.school.school_abbreviation}}</label>
            <label :class="{'text-danger': item.status === 'PENDING'}" class="" v-if="item.status === 'PENDING'">{{item.status}}</label>
            <label class="text-green" v-if="item.status === 'APPROVED'">{{item.type}}</label>
          </span>
          <span class="admin-menu" v-if="memberType === 'ADMIN' && item.status === 'PENDING'">
            <button class="btn text-green" style="margin-left: 10px;" v-on:click="updateStatus(item.organization_member_id, 'APPROVED')">Approve</button>
            <button class="btn text-danger pull-right" style="margin-right: 10px;" v-on:click="updateStatus(item.organization_member_id, 'DECLINED')">Decline</button>
          </span>
          <span class="admin-menu" v-if="memberType === 'ADMIN' && item.status !== 'PENDING'">
            <button class="btn text-green" style="margin-left: 10px;" v-if="item.type !== 'ADMIN'" v-on:click="updateType(item.organization_member_id, 'ADMIN')">Make as Admin</button>
            <button class="btn text-green" style="margin-left: 10px;" v-if="item.type === 'ADMIN'" v-on:click="updateType(item.organization_member_id, 'MEMBER')">Make as Member</button>
            <button class="btn pull-right text-danger" style="margin-right: 10px;" data-toggle="modal" data-target="#confirmationDeleteMemberModal">Delete</button>
          </span>
      </div>

          <!-- Confirmation Modal -->
      <div class="modal" id="confirmationDeleteMemberModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Confirmation!</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this member?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal" data-target="#confirmationDeleteMemberModal">No</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal" data-target="#confirmationDeleteMemberModal" v-on:click="deleteMember(1)">Yes</button>
            </div>
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
import axios from 'axios'
export default {
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      data: null
    }
  },
  props: ['id', 'memberType'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieve(){
      let parameter = {
        organization_id: this.id
      }
      console.log(parameter)
      this.APIRequest('organization_members/retrieve', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    updateStatus(id, status){
      let parameter = {
        id: id,
        status: status
      }
      this.APIRequest('organization_members/update', parameter).then(response => {
        if(response.data === true){
          this.retrieve()
        }
      })
    },
    updateType(id, type){
      let parameter = {
        id: id,
        type: type
      }
      this.APIRequest('organization_members/update', parameter).then(response => {
        if(response.data === true){
          this.retrieve()
        }
      })
    },
    deleteMember(id){
      let parameter = {
        id: id
      }
      this.APIRequest('organization_members/delete', parameter).then(response => {
        this.retrieve()
      })
    }
  }
}
</script>
<style scoped>

.member-holder{
  width: 100%;
  float: left;
}
.org-member-item{
  width: 49%;
  margin-right: 1%;
  float: left;
  margin-bottom: 20px;
}

.item-holder{
  width: 100%;
  float: left;
  min-height: 50px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border: solid 1px #ddd;
  overflow-y: hidden;
}
.profile-picture{
  width: 40%;
  float: left;
  text-align: center;
}
.profile-picture img{
  width: 80px;
  border-radius: 50%;
  margin-top: 10px;
  margin-bottom: 10px;
}
.profile-picture i{
  font-size: 80px;
  padding-top: 10px;
  padding-bottom: 10px;
}
.item-holder .member-details{
  width: 60%;
  float: left;
}

.member-details label{
  width: 100%;
  float: left;
  padding-bottom: 0px;
  margin-bottom: 0px;
  color: #555;
}
.member-details .name{
  padding-top: 10px;
}
.member-details .btn-status{
  height: 25px !important;
  padding-top: 5px !important;
}
.member-details .btn-status:hover{
  cursor: dafault;
}
.item-holder .admin-menu{
  width: 100%;
  float: left;
  margin-bottom: 10px;
}

@media (max-width: 991px){
  .org-member-item{
    width: 100%;
    float: left;
    margin-right: 0%;
  }
}
</style>
