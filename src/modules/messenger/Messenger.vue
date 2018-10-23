<template>
  <div class="holder">
    <div class="send">
      <textarea class="form-control" v-model="newTitle" rows="15" placeholder="Type title here">
      </textarea>
      <button class="btn btn-primary" style="margin-top: 5px;" v-on:click="create()">Send</button>
    </div>
    <div class="messages">
      <ul>
        <li v-for="item, index in data">
          <span class="profile">
            <img v-bind:src="config.BACKEND_URL + item.account_details.profile.profile_url" style="height: 50px;" v-if="item.account_details !== null && item.account_details.profile !== null">
            <i class="fa fa-user" v-else></i>
          </span>
          <span class="title">{{item.title}}</span>
        </li>
      </ul>
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
      newTitle: null,
      data: null
    }
  },
  props: ['params'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    create(){
      if(this.newTitle !== null || this.newTitle !== ''){
        let parameter = {
          'account_id': this.user.userID,
          'title': this.newTitle
        }
        this.APIRequest('messenger_groups/create', parameter).then(response => {
          console.log(response)
        })
      }
    },
    retrieve(){
      let parameter = {
        condition: [{
          value: this.user.userID,
          column: 'account_id',
          clause: '='
        }]
      }
      this.APIRequest('messenger_groups/retrieve', parameter).then(response => {
        this.data = response.data
      })
    }
  }
}
</script>
<style scoped>
.holder{
  width: 60%;
  margin-left: 20%;
  float: left;
}

ul{
  padding: 0px;
  margin: 0px;
  list-style: none;
  margin-top: 50px;
}

ul li{
  height: 50px;
  line-height: 50px;
  padding-left: 10px;
}

ul li:hover{
  cursor: pointer;
  background: #eee;
}
</style>
