<template>
  <div class="modal fade" id="postAnnouncementsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>New Announcement</h5>
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
          <textarea class="form-control" id="postTopicTextArea" v-model="newAnnouncementInput" placeholder="Type new announcements here..."></textarea>
          <span style="width: 94%;margin-left: 3%;float: left;margin-top: 10px;">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="announcementCourses">
              <label class="form-check-label" for="defaultCheck1">
                Post to all courses of the selected semester
              </label>
            </div>
          </span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="create()">Post</button>
        </div>
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
      errorMessage: null,
      newAnnouncementInput: null,
      announcementCourses: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    create(){
      if(this.newAnnouncementInput !== null || this.newAnnouncementInput !== ''){
        let parameter = {
          'course_id': (this.announcementCourses === true) ? null : this.user.course,
          'account_id': this.user.userID,
          'message': this.newAnnouncementInput,
          'account_semester_id': (this.announcementCourses === true) ? this.user.selectedSemester.id : null
        }
        this.APIRequest('announcements/create', parameter).then(response => {
          if(response.data > 0){
            this.newAnnouncementInput = null
            this.errorMessage = null
            this.$parent.retrieve(true)
            $('#postAnnouncementsModal').modal('hide')
          }
        })
      }else{
        this.errorMessage = 'Message is empty.'
      }
    }
  }
}
</script>
<style scoped>

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

.input-group{
  margin-top: 5px;
  font-size: 13px !important;
}
.input-group-addon{
  width: 175px !important;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
  text-align: right !important;
}
</style>
