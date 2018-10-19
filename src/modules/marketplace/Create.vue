<template>
    <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
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
<!--             <div class="featured-image">
              <span class="options" v-on:click="addFeaturedImage()" v-if="featuredFile === null" title="Click to add">
                <i class="fa fa-plus-circle"></i>
                <label>Add Featured Image</label>
                <input type="file" id="addFeaturedImage" name="file" accept="image/*"  @change="setUpFileUploadFeaturedImage($event)">
              </span>
              <span class="options" v-on:click="addFeaturedImage()" v-else title="Click to change">
                <img :src="featuredFileUrl">
                <input type="file" id="addFeaturedImage" name="file" accept="image/*"  @change="setUpFileUploadFeaturedImage($event)">
              </span>
            </div> -->
            <div class="form-group">
              <label for="exampleInputEmail1">Title</label>
              <input type="text" class="form-control" v-model="newEvent.title" placeholder="Type title here...">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Additional Details</label>
              <textarea class="form-control" v-model="newEvent.more_info" placeholder="Type additional details here..." rows="5">
              </textarea> 
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Date & Time</label>
              <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="date" class="form-control" v-model="newEvent.date">

                <span class="input-group-addon">Start Time</span>
                <input type="time" class="form-control" v-model="newEvent.start_time">

                <span class="input-group-addon">End Time</span>
                <input type="time" class="form-control" v-model="newEvent.end_time">
              </div>
            </div>


            <div class="form-group">
              <label for="exampleInputEmail1">Venue</label>
              <input type="text" class="form-control" v-model="newEvent.venue" placeholder="Type venue here...">
            </div>

            <div class="row">
              <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Ticket Price</label>
                <input type="text" class="form-control" v-model="newEvent.ticket_price" placeholder="Type ticket price here...">
              </div>

              <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Maximum Attendees</label>
                <input type="text" class="form-control" v-model="newEvent.max_attendees" placeholder="Type maximum attendees here...">
              </div>

            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#createEventModal">Cancel</button>
              <button type="button" class="btn btn-primary" @click="addEvent()">Add</button>
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
      newEvent: {
        status: 'PENDING',
        account_id: null,
        organization_id: null,
        title: null,
        more_info: null,
        date: null,
        start_time: null,
        end_time: null,
        venue: null,
        ticket_price: null,
        max_attendees: null
      },
      errorMessage: null
    }
  },
  props: ['params'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    addEvent(){
      if(this.validate()){
        this.newEvent.account_id = this.user.userID
        this.newEvent.organization_id = this.params
        this.APIRequest('events/create', this.newEvent).then(response => {
          if(response.data > 0){
            ROUTER.go('/affiliates')
          }
        })
      }
    },
    validate(){
      let i = this.newEvent
      if(i.title !== '' || i.title !== null || i.date !== null || i.start_time !== null || i.venue !== null || i.ticket_price !== null || i.max_attendees !== null){
        this.errorMessage = null
        return true
      }else{
        this.errorMessage = 'Please fill in all required fields.'
        return false
      }
    }
  }
}
</script>
<style scoped>
.featured-image{
  width: 100%;
  float: left;
  height: 200px;
  margin-bottom: 10px;
}

.featured-image .options{
  width: 100%;
  float: left;
  text-align: center;
  height: 200px;
  border: solid 1px #ddd;
  overflow-y: hidden;
}
.options input{
  display: none;
}
.options:hover{
  cursor: pointer;
}
.options i{
  font-size: 40px;
  width: 100%;
  float: left;
  margin-top: 75px;
}

.options label{
  width: 100%;
  float: left;
}
.options img{
  width: 100%;
  float: left;
  height: auto;
}
</style>
