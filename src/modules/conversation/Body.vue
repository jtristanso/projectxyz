<template>
  <div class="holder">
    <div class="left-template" v-for="i in 5">
       <img :src="config.BACKEND_URL + user.profile.profile_url" class="profile" v-if="user.profile !== null">
       <i class="fa fa-user-circle-o" v-else></i>
       <label class="content">
         Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
         tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
         quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
         consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
         cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
         proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
       </label>
    </div>
    <div class="right-template" v-for="i in 10">
       <label class="content">
         Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
         tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
         quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
         consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
         cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
         proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
       </label>
       <img :src="config.BACKEND_URL + user.profile.profile_url" class="profile pull-right" v-if="user.profile !== null">
       <i class="fa fa-user-circle-o pull-right" v-else></i>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
export default {
  mounted(){
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG
    }
  },
  props: ['params'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
.holder{
  width: 100%;
  float: left;
  height: 75vh;
  overflow-y: scroll;
}

.left-template, .right-template{
  width: 100%;
  float: left;
  min-height: 100px;
  overflow-y: hidden;
  background: #aaa;
  margin-top: 10px;
}

.content{
  float: left;
  width: 60%;

}

</style>
