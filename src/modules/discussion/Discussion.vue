<template>
  <div>
      <div class="module-header-discussions">
        <div class="discussion-posts">
          <div class="discussion-search">

            <div class="discussion-list">
              <create-search-item :payload="payload" :payloadValue="payloadValue"></create-search-item>
              <div class="discussion-item" v-if="data !== null" v-for="item, index in data">
                <discussion-item :item="item" :index="index"></discussion-item>
              </div>
            </div>
          </div>
        </div>
        <div class="discussion-sidebar"></div>
      </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'create-search-item': require('modules/discussion/CreateSearch.vue'),
    'discussion-item': require('modules/discussion/Item.vue')
  },
  mounted(){
    this.retrieveDiscussion()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      config: CONFIG,
      data: null
    }
  },
  props: ['payload', 'payloadValue'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveDiscussion(){
      let parameter = {
        account_id: this.user.userID,
        condition: [{
          value: this.payload,
          clause: '=',
          column: 'payload'
        }, {
          value: this.payloadValue,
          clause: '=',
          column: 'payload_value'
        }]
      }
      $('#loading').css({'display': 'block'})
      this.APIRequest('discussion_posts/retrieve', parameter).then(response => {
        $('#loading').css({'display': 'none'})
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    }
  }
}
</script>
<style scoped>

.module-header-discussions{
  width: 100%;
  float: left;
}
.discussion-posts{
  width: 60%;
  float: left;
  min-height: 100px;
  overflow-y: hidden;
}
.discussion-sidebar{
  width: 35%;
  margin-left: 5%;
  float: left;
  min-height: 100px;
  overflow-y: hidden;
}

.discussion-posts .discussion-header{
  width: 100%;
  float: left;
}
.discussion-posts .discussion-search{
  width: 100%;
  float: left;
}

.discussion-posts .discussion-list{
  width: 100%;
  float: left;
}
.discussion-item{
  width: 100%;
  float: left;
  margin-top: 20px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border: solid 1px #ddd;
}

@media (max-width: 991px){
  .discussion-posts{
    width: 100%;
  }
  .discussion-sidebar{
    display: none;
  }
}
</style>
