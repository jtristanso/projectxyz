<template>
	<div>
    <div class="details">
    <div class="logo">
      <img :src="config.BACKEND_URL + item.logo.url" v-if="item.logo !== null">
      <i v-else class="fa fa-users"></i>
    </div>
    <div class="content">
      <span class="name"><b>{{item.name}}</b></span>
      <span class="type"><i class="fas fa-tags"></i>{{item.type}}</span>
      <span class="school"><i class="fa fa-university"></i>{{item.school.name}}</span>
      <span class="address" v-if="item.address !== null && item.address !== ''">
        <i class="fas fa-map-marker-alt"></i>
        {{item.address}}
      </span>
    </div>
  </div>
  <span class="about">
    <b>About</b>
    <br>
    <label>
      {{item.about}}
    </label>
  </span>
  <ul class="menu">
    <li v-if="item.join_status === null" v-on:click="createRequestToJoin(item.id)">Request to Join</li>
    <li v-if="item.join_status === 'PENDING'">Pending</li>
    <li v-if="item.join_status === 'APPROVED'">Joined</li>
    <li>
      <label v-if="parseInt(item.members) > 1">
        {{item.members}} Members
      </label>
      <label v-else>
        {{item.members}} Member
      </label>
    </li>
<!--             <li>Events</li>
    <li>Vision & Mission</li> -->
  </ul>
	</div>
</template>

<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import axios from 'axios'
export default {
  mounted(){
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG
    }
  },
  props: {
    item: Object
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    createRequestToJoin(id){
      let parameter = {
        account_id: this.user.userID,
        organization_id: id,
        status: 'PENDING',
        type: 'MEMBER'
      }
      this.APIRequest('organization_members/create', parameter).then(response => {
        if(response.data > 0){
          ROUTER.go('/affiliates')
        }
      })
    }
  }
}
</script>
<style scoped>
.organizations-item .about{
  width: 100%;
  float: left;
  text-align: justify;
  margin-top: 20px;
}
.organizations-item .details{
  width: 100%;
  float: left;
  margin-left: 0%;
}


.details .logo{
  width: 25%;
  float: left;
  margin-right: 2%;
  min-height: 50px;
  overflow-y: hidden;
  text-align: center;
}

.details .logo i{
  font-size: 100px;
}

.details .logo img{
  width: 100%;
  float: left;
  border-radius: 50%;
}

.details .content{
  width: 73%;
  float: left;
  margin-top: 20px;
}

.organizations-item .content span{
  width: 100%;
  float: left;
  text-align: justify;
  margin-bottom: 10px;
}

.organizations-item .content span i{
  padding-right: 20px;
}

.organizations-item .menu{
  width: 100%;
  height: 50px;
  float: left; 
  list-style: none;
  border-bottom: solid 1px #ddd;
  border-top: solid 1px #ddd;
  margin: 10px 0px 0px 0px;
  padding: 0px;
}

.menu li{
  width: 50%;
  float: left;
  line-height: 50px;
}

.menu li:hover{
  cursor: pointer;
}
</style>
