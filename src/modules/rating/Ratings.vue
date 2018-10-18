<template>
  <span>
    <span class="holder">
      <span class="text">
        Average Ratings
        <i class="fas fa-star text-warning" v-for="i in 5"></i>
      </span>
      <button class="btn btn-primary pull-right" style="margin-top: 5px;" data-toggle="modal" data-target="#submitRatingModal">Submit</button>
    </span>
    <rating-create :payload="payload" :payloadValue="payloadValue"></rating-create>
  </span>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import axios from 'axios'
export default {
  components: {
    'rating-create': require('modules/rating/Create.vue')
  },
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG
    }
  },
  props: ['payload', 'payloadValue'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieve(){
      let parameter = {
        condition: [{
          value: this.payload,
          column: 'payload',
          clause: '='
        }, {
          value: this.payloadValue,
          column: 'payload_value',
          clause: '='
        }]
      }
      this.APIRequest('ratings/retrieve', parameter).then(response => {
        // retrieve
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
.text{
  float: left;
}
.fa-star{
  padding-right: 5px;
  font-size: 16px;
}
.fa-star:hover{
  cursor: pointer;
}

</style>
