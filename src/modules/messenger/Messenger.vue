<template>
  <div class="holder">
    <div class="conversation">
      <conversation></conversation>
    </div>
    <div class="users"></div>
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
  components: {
    'conversation': require('modules/conversation/Conversation.vue')
  },
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
  width: 100%;
  float: left;
}

.conversation{
  width: 70%;
  float: left;
  min-height: 500px;
  overflow-y:hidden;
}
.users{
  width: 30%;
  float: left;
  height: 500px;
  background: #555;
}

</style>
