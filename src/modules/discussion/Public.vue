<template>
	<div class="module-header">
		<public-discussions :payload="'public'" :payloadValue="null"></public-discussions>
	</div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'public-discussions': require('modules/discussion/Discussion.vue')
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
</style>
