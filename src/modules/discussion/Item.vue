<template>
  <div v-if="item !== null || item !== ''">
      <div class="discussion-item-header">
          <img v-bind:src="config.BACKEND_URL + item.account.profile.profile_url" v-if="item.account.profile !== null" style="margin-top: 5px;">
          <i class="fa fa-user-circle" v-else></i>
          <label>{{item.account.username}}</label>

          <div class="dropdown pull-right" id="dropdownMenuButtonDropdown">
            <i class="fas fa-ellipsis-h text-gray more-options" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="dropdownMenuButtonDropdown">
            </i>
            <div class="dropdown-menu dropdown-more-options" aria-labelledby="dropdownMenuButton" >
              <span class="dropdown-item" v-on:click="editTopic(index)" v-if="parseInt(item.account_id) === user.userID">Edit</span>
              <span class="dropdown-item text-danger" v-on:click="deleteTopic(item.id)"  v-if="parseInt(item.account_id) === user.userID">Delete</span>
              <span class="dropdown-item more-option-title">Report this as</span>
              <span class="dropdown-item" v-on:click="report(item.id, 'not_relevant')">Not Relevant</span>
            </div>
          </div>
      </div>
      <div class="discussion-item-datetime">
          <button class="btn btn-discusions bg-green" style="margin-top: 5px;" v-if="parseInt(item.status) === 0 && parseInt(item.account_id) === user.userID" v-on:click="updateMarkAs(item.id, 1)">OPEN</button>
          <button class="btn btn-discusions" style="margin-top: 5px;" v-if="parseInt(item.status) === 1 && parseInt(item.account_id) === user.userID" v-on:click="updateMarkAs(item.id, 0)">SOLVED</button>
          <label v-if="parseInt(item.account_id) !== user.userID && parseInt(item.status) === 1" class="text-danger">SOLVED</label>
          <label v-if="parseInt(item.account_id) !== user.userID && parseInt(item.status) === 0" class="text-green">OPEN</label>
          <label>Posted on {{item.created_at}}</label>
      </div>
      <div class="discussion-item-content" v-if="item.edit_flag === false">
        {{item.text}}
      </div>
      <div class="discussion-item-content" v-if="item.edit_flag === true">
        <textarea v-model="item.edit_text"></textarea>
        <button class="btn btn-primary pull-right" v-on:click="updatePost(item)">Update</button>
        <button class="btn btn-danger pull-right" style="margin-right: 10px;" v-on:click="cancelEditPost(index)">Cancel</button>
      </div>
      <div class="discussion-item-tags" v-if="item.edit_flag === false">
        <label class="tag-item" v-for="tagItem, tagIndex in item.tags_array" v-if="item.tags_array !== null">
          {{tagItem.tag}}
        </label>
      </div>
      <div class="discussion-item-footer">
        <ul class="footer-menu">
          <li v-on:click="reactions(item.id)">
            <label v-if="parseInt(item.reactions) > 0" style="font-size: 12px;">{{item.reactions}}</label>
            <i v-bind:class="{'fa-star text-warning': item.account_reaction_flag === true, 'fa-star-o': item.account_reaction_flag === false}" class="fa"></i>
            Excellent
         </li>
          <li><i class="fa fa-comment-o"></i> Answer</li>
        </ul>
      </div>
      <span class="discussion-item-answer">
        <span class="answer-item" v-if="item.answer !== null" v-for="answerItem, answerIndex in item.answer">
          <span class="answer-header">
            <img v-bind:src="config.BACKEND_URL + answerItem.account.profile.profile_url" v-if="answerItem.account.profile !== null">
            <i class="fa fa-user-circle" v-else></i>
            <label class="username">{{answerItem.account.username}}</label>
            <label class="answer-date pull-right">{{answerItem.created_at}}</label>
          </span>
          
          <span v-bind:class="{'answer-content': parseInt(answerItem.account_id) === user.userID, 'answer-content-not': parseInt(answerItem.account_id) !== user.userID}" class="" v-if="answerItem.edit_flag === false">{{answerItem.text}}</span>
          <span v-if="answerItem.edit_flag === true" class="answer-content-whole">
            <textarea v-model="answerItem.text"></textarea>
            <button class="btn btn-primary pull-right" style="margin-top: 5px" v-on:click="updateAnswer(answerItem)">Update</button>
            <button class="btn btn-danger pull-right" style="margin-right: 10px; margin-top: 5px;" v-on:click="cancelEditAnswer(answerIndex, index)">Cancel</button>
          </span>
          <div class="dropdown pull-right" v-if="parseInt(answerItem.account_id) === user.userID && answerItem.edit_flag === false" id="dropdownMenuButtonAnswersDropdown" style="margin-right: 10px;">
            <i class="fas fa-ellipsis-h text-gray more-options-answers" id="dropdownMenuButtonAnswers" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="dropdownMenuButtonAnswersDropdown">
            </i>
            <div class="dropdown-menu dropdown-more-options-answers" aria-labelledby="dropdownMenuButtonAnswers" >
              <span class="dropdown-item" v-on:click="editAnswer(answerIndex, index)">
                <i class="fa fa-pencil"></i> Edit
              </span>
              <span class="dropdown-item text-danger" v-on:click="deleteAnswer(answerItem.id)">
                <i class="fa fa-trash"></i> Delete
              </span>
            </div>
          </div>
        </span>
        <span class="new-answer">
          <img v-bind:src="config.BACKEND_URL + user.profile.profile_url" v-if="user.profile !== null">
          <i class="fa fa-user-circle" v-else></i>
          <textarea placeholder="Write an answer..." v-model="item.new_answer" class="pull-right">
            
          </textarea>
          <button class="btn btn-discusions bg-green pull-right" style="margin: 5px;" v-on:click="submitAnswer(item.id, item.new_answer)">Submit</button>
        </span>
      </span>
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
      config: CONFIG
    }
  },
  props: ['item', 'index'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    report(id, text){
      let parameter = {
        account_id: this.user.userID,
        discussion_post_id: id,
        text: text
      }
      this.APIRequest('discussion_reports/create', parameter).then(response => {
        this.$parent.retrieveDiscussion()
      })
    },
    updateMarkAs(id, status){
      let parameter = {
        id: id,
        status: status
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('discussion_posts/update', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data === true){
          this.$parent.retrieveDiscussion()
        }
      })
    },
    reactions(id){
      let parameter = {
        account_id: this.user.userID,
        discussion_post_id: id
      }
      this.APIRequest('discussion_reactions/create', parameter).then(response => {
        if(response.data === true || response.data > 0){
          this.$parent.retrieveDiscussion()
        }
      })
    },
    editTopic(index){
      this.item.edit_flag = true
    },
    cancelEditPost(index){
      this.item.edit_flag = false
    },
    deleteTopic(id){
      let parameter = {
        id: id
      }
      this.APIRequest('discussion_posts/delete', parameter).then(response => {
        this.$parent.retrieveDiscussion()
      })
    },
    updatePost(item){
      let editText = item.edit_text
      if(editText !== null || editText !== ''){
        let strings = editText.split('#')
        if(strings.length > 0){
          let text = strings[0]
          let tags = ''
          for (var i = 1; i < strings.length; i++) {
            tags += strings[i] + '#'
          }
          let parameter = {
            id: item.id,
            text: text,
            tags: tags
          }
          this.APIRequest('discussion_posts/update', parameter).then(response => {
            if(response.data === true){
              this.$parent.retrieveDiscussion()
            }
          })
        }
      }
    },
    submitAnswer(id, text){
      if(text !== null || text !== ''){
        this.answer(id, text)
      }
    },
    answer(id, text){
      let parameter = {
        discussion_post_id: id,
        account_id: this.user.userID,
        text: text,
        best_solution: 0
      }
      this.APIRequest('discussion_answers/create', parameter).then(response => {
        this.$parent.retrieveDiscussion()
      })
    },
    editAnswer(answerIndex, index){
      this.item.answer[answerIndex].edit_flag = true
    },
    cancelEditAnswer(answerIndex, index){
      this.item.answer[answerIndex].edit_flag = false
    },
    updateAnswer(answerItem){
      let parameter = {
        id: answerItem.id,
        text: answerItem.text
      }
      this.APIRequest('discussion_answers/update', parameter).then(response => {
        if(response.data === true){
          this.$parent.retrieveDiscussion()
        }
      })
    },
    deleteAnswer(id){
      let parameter = {
        id: id
      }
      this.APIRequest('discussion_answers/delete', parameter).then(response => {
        this.$parent.retrieveDiscussion()
      })
    }
  }
}
</script>
<style scoped>
.discussion-item-header, .discussion-item-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}

.post-item-header{
  margin-top: 10px;
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

.discussion-item-datetime{
  width: 96%;
  float: left;
  font-size: 12px;
  color: #999;
  margin: 0 2% 0 2%;
}

.discussion-item-content{
  width: 96%;
  float: left;
  min-height: 40px;
  overflow-y: hidden;
  margin: 0 2% 15px 2%;
}

.discussion-item-content textarea{
  float: left;
  width: 100%;
  border: solid 1px #ddd;
  min-height: 70px;
}

.discussion-item-tags{
  width: 96%;
  float: left;
  min-height: 40px;
  overflow-y: hidden;
  margin: 0 2% 0 2%;
}

.discussion-item-tags .tag-item{
  padding-left: 10px;
  padding-right: 10px;
  background: #6a0090;
  border-color: #6a0090;
  line-height: 30px;
  border-radius: 5px;
  color: #fff;
  margin-right: 10px;
}

.discussion-item-footer .footer-menu{
  padding: 0px !important;
  list-style: none;
  width: 100%;
  float: left;
  border-top: solid 1px #ddd;
}

.discussion-item-footer .footer-menu li{
  float: left;
  width: 50%;
  padding-top: 10px;
  padding-bottom: 10px;
}

.discussion-item-footer .footer-menu li:hover{
  cursor: pointer;
}

.discussion-item-footer .footer-menu li i{
  font-size: 16px;
  padding-right: 10px;
}

.btn-discusions{
  height: 30px !important;
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

/*
      ANSWER
*/

.post-item-answer{
  width: 100%;
  float: left;
  min-height: 1px;
  overflow-y: hidden;
}

.post-item-answer .answer-item{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

.answer-item .answer-content{
  width: 80%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-left: 7%;
  margin-right: 7%;
}

.answer-item .answer-content-not{
  width: 86%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-left: 7%;
  margin-right: 7%;
}

.answer-item .answer-content-whole{
  width: 94%;
  float: left;
  min-height: 10px;
  overflow-y: hidden;
  margin-left: 3%;
  margin-right: 3%;
}

.answer-item .answer-content-whole textarea{
  float: left;
  width: 100%;
  border: solid 1px #ddd;
  min-height: 70px;
}

.answer-item .more-options-answers:hover{
  cursor: pointer;
}

.dropdown-more-options-answers{
  min-height: 50px !important;
  padding-top: 0px !important;
  padding-bottom: 0px !important;
  overflow-y: hidden;
  width: 150px !important;
}

.dropdown-more-options-answers .dropdown-item{
  font-size: 13px !important;
  padding-top: 5px !important;
  padding-bottom: 5px !important;
  height: 30px !important;
}

.dropdown-more-options-answers .dropdown-item:hover{
  cursor: pointer;
}

.more-option-title{
  font-weight: 700;
}

.dropdown-more-options-answers .more-option-title:hover{
  cursor: default;
}

.new-answer{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  margin-top: 5px;
}

.new-answer img{
  height: 25px;
  width: 25px;
  border-radius: 50%;
  float: left;
  margin-top: 5px;
  margin-left: 10px;
}

.new-answer i, .answer-header i{
  font-size: 24px;
  padding-top: 5px;
  padding-right: 10px;
  float: left;
  padding-left: 5px;
  width: 5%;
}

.answer-header img{
  width: 30px;
  height: 30px;
  float: left;
  border-radius: 50%;
}

.new-answer textarea{
  width: 92%;
  float: right;
  border: solid 1px #ddd;
  min-height: 40px;
  padding-top: 5px;
  margin-right: 1%;
}

.answer-date, .reply-date{
  font-size: 12px !important;
  color: #999;
}

.answer-header, .answer-footer{
  width: 96%;
  float: left;
  height: 40px;
  margin: 0 2% 0 2%;
  font-weight: 525;
}

.answer-header label{
  line-height: 30px;
  padding-left: 10px;
}

.answer-footer .footer-menu{
  padding: 0px !important;
  list-style: none;
  width: 90%;
  float: left;
  margin-left: 5%;
}

.answer-footer .footer-menu li{
  float: left;
  width: 50%;
  padding-top: 10px;
  padding-bottom: 10px;
}

.answer-footer .footer-menu li:hover{
  cursor: pointer;
}

.answer .footer-menu li i{
  font-size: 16px;
  padding-right: 10px;
}

.error-message{
  width: 100%;
  float: left;
  padding-top: 10px;
  padding-bottom: 10px;
  txt-align: center;
}
</style>
