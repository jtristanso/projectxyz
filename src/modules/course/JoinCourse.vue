<template>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i> Join new Course</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
          <br v-if="errorMessage !== null">
          <br>
          <div class="input-group">
            <span class="input-group-addon">Course Join Code</span>
            <input type="text" class="form-control" v-model="enrolmentCode" placeholder="Enter 12 Digit Join Code">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Request to Join</button>
            <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
        </div>
      </div>
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
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      enrolmentCode: null,
      errorMessage: null
    }
  },
  props: ['params'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    submit(){
      if(this.enrolmentCode !== null || this.enrolmentCode !== ''){
        this.errorMessage = null
        this.createRequest()
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append('account_id', this.user.userID)
      formData.append('enrolment_code', this.enrolmentCode)
      axios.post(CONFIG.BACKEND_URL + '/enrolled_courses/create', formData).then(response => {
        if(response.data.data !== null || response.data.data > 0){
          $('#myModal').modal('hide')
          this.$parent.retrieveRequest(false)
        }else{
          this.errorMessage = response.data.message
        }
      })
    }
  }
}
</script>
<style scoped>
</style>
