<template>
  <div>
    <div class="discussion-item-new">
      <ul class="discussion-item-new-menu">
        <li v-bind:class="{'new-menu-active': newFlag === 0}" style="" v-on:click="setNewFlag(0)">Ask something</li>
        <li v-bind:class="{'new-menu-active': newFlag === 1}" style="border-left: solid 1px #ddd;" v-on:click="setNewFlag(1)">Search topics</li>
      </ul>
      <div class="discussion-item-header discussion-item-new-header" v-if="newFlag === 0">
<!--                   <img v-bind:src="config.BACKEND_URL + user.profile.profile_url" v-if="user.profile !== null">
        <i class="fa fa-user-circle" v-else></i> -->
        <div class="error-message text-danger" v-if="errorMessage !== null">
          Opps! {{errorMessage}}
        </div>
        <textarea v-bind:placeholder="'Hi ' + user.username + ', you can write or post something related to your course here. Always use a hashtag at the end of the post. eq. Welcome ' + user.username + '. #welcome'" v-model="newPost">
        </textarea>
        <button class="btn btn-primary pull-right" style="margin-top: 5px; margin-bottom: 5px;" v-on:click="submitPost()">Post</button>
      </div>
      <div class="discussion-item-header discussion-item-new-header" v-if="newFlag === 1">
<!--                   <img v-bind:src="config.BACKEND_URL + user.profile.profile_url" v-if="user.profile !== null">
        <i class="fa fa-user-circle" v-else></i> -->
        <textarea v-bind:placeholder="'Hi ' + user.username + ', you can search discussions here. To search for tag just input #tagToSearch.'" v-model="newSearch">
          
        </textarea>
        <button class="btn btn-primary pull-right" style="margin-top: 5px; margin-bottom: 5px;" v-on:click="search()">Search</button>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      test: false,
      newFlag: 0,
      newPost: null,
      newSearch: null,
      errorMessage: null
    }
  },
  props: ['payload', 'payloadValue'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    setNewFlag(parameter){
      this.newFlag = parameter
    },
    submitPost(){
      if(this.newPost !== null && this.newPost !== ''){
        let strings = this.newPost.split('#')
        if(strings.length > 1){
          let text = strings[0]
          let tags = ''
          for (var i = 1; i < strings.length; i++) {
            tags += strings[i] + '#'
          }
          if(text === null || text === ''){
            this.errorMessage = 'Please write something before the hashtag.'
          }else{
            this.errorMessage = null
            this.post(text, tags)
          }
        }else{
          this.errorMessage = 'Please insert hashtag for topic in the end of the question.'
        }
      }else{
        this.errorMessage = 'Post field is Empty.'
      }
    },
    post(text, tags){
      let parameter = {
        account_id: this.user.userID,
        payload: this.payload,
        payload_value: this.payloadValue,
        text: text,
        tags: tags,
        status: 0
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('discussion_posts/create', parameter).then(response => {
        this.$parent.retrieveDiscussion()
        this.newPost = null
        $('#loading').css({'display': 'none'})
      })
    },
    search(){
      if(this.newSearch !== null || this.newSearch !== ''){
        let parameter = null
        if(this.newSearch.includes('#')){
          let searchText = this.newSearch
          searchText = searchText.replace('#', '')
          parameter = {
            'condition': [{
              value: '%' + searchText + '%',
              column: 'tags',
              clause: 'like'
            }, {
              value: this.payload,
              column: 'payload',
              clause: '='
            }, {
              value: this.payloadValue,
              column: 'payload_value',
              clause: '='
            }],
            account_id: this.user.userID
          }
        }else{
          parameter = {
            'condition': [{
              value: '%' + this.newSearch + '%',
              column: 'text',
              clause: 'like'
            }, {
              value: this.payload,
              column: 'payload',
              clause: '='
            }, {
              value: this.payloadValue,
              column: 'payload_value',
              clause: '='
            }],
            account_id: this.user.userID
          }
        }
        $('#loading').css({'display': 'block'})
        this.APIRequest('discussion_posts/search', parameter).then(response => {
          $('#loading').css({'display': 'none'})
          if(response.data.length > 0){
            this.$parent.data = response.data
          }else{
            // display empty search
            this.$parent.data = null
          }
        })
      }
    }
  }
}
</script>
<style scoped>

.discussion-item-new{
  width: 100%;
  float: left;
  border: solid 1px #ddd;
}
.discussion-item-new-menu{
  height: 40px;
  width: 100%;
  float: left;
  border-bottom: solid 1px #ddd;
  list-style: none;
  padding: 0px !important;
}
.discussion-item-new-menu li{
  float: left;
  width: 50%;
  line-height: 40px;
  padding-left: 20px;
}

.discussion-item-new-menu li:hover{
  cursor: pointer;
}

.new-menu-active{
  background: #ddd;
}

.discussion-item-header, .discussion-item-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}



.discussion-item-header img{
  height: 30px;
  width: 30px;
  border-radius: 50%;
  margin-right: 10px;
}

.discussion-item-header i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
}

.discussion-item-header label{
  padding-top: 5px;
  font-weight: 550;
  color: #3f0050;
}

.discussion-item-header .more-options:hover{
  cursor: pointer;
}

.discussion-item-new-header{
  height: auto;
  overflow-y: hidden;
}
.discussion-item-new-header textarea{
  float: left;
  width: 100%;
  border: solid 1px #ddd;
  min-height: 70px;
}

</style>
