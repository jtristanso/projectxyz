<template>
  <div class="holder">
    <c-header></c-header>
    <c-body></c-body>
    <c-footer></c-footer>
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
  props: ['params'],
  components: {
    'c-header': require('modules/conversation/Header.vue'),
    'c-body': require('modules/conversation/Body.vue'),
    'c-footer': require('modules/conversation/Footer.vue')
  },
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
}

</style>
