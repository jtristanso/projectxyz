<template>
	<div class="body">
		<landing-banner></landing-banner>
    <landing-snetwork></landing-snetwork>
    <landing-classroom></landing-classroom>
    <landing-organization></landing-organization>
		<landing-features></landing-features>
	</div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
export default {
  components: {
    'landing-banner': require('modules/home/Landing/Banner.vue'),
    'landing-snetwork': require('modules/home/Landing/SocialNetwork.vue'),
    'landing-classroom': require('modules/home/Landing/Classroom.vue'),
    'landing-organization': require('modules/home/Landing/Organization.vue'),
    'landing-features': require('modules/home/Landing/Features.vue')
  },
  mounted(){
  },
  data(){
    return {
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
.body{
	margin: 0px;
}
</style>
