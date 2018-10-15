<template>
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
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
              <input type="text" class="form-control" v-model="params.title" placeholder="Type title here...">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Additional Details</label>
              <textarea class="form-control" v-model="params.more_info" placeholder="Type additional details here..." rows="5">
              </textarea> 
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Date & Time</label>
              <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="date" class="form-control" v-model="params.date">

                <span class="input-group-addon">Start Time</span>
                <input type="time" class="form-control" v-model="params.start_time">

                <span class="input-group-addon">End Time</span>
                <input type="time" class="form-control" v-model="params.end_time">
              </div>
            </div>


            <div class="form-group">
              <label for="exampleInputEmail1">Venue</label>
              <input type="text" class="form-control" v-model="params.venue" placeholder="Type venue here...">
            </div>

            <div class="row">
              <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Ticket Price</label>
                <input type="text" class="form-control" v-model="params.ticket_price" placeholder="Type ticket price here...">
              </div>

              <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Maximum Attendees</label>
                <input type="text" class="form-control" v-model="params.max_attendees" placeholder="Type maximum attendees here...">
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">Status</label>
              <select class="form-control" v-model="params.status">
                <option value="PENDING">PENDING</option>
                <option value="PUBLIC">PUBLISH AS PUBLIC</option>
                <option value="PRIVATE">PUBLISH AS PRIVATE</option>
              </select>
            </div>
           
            
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editEventModal">Cancel</button>
              <button type="button" class="btn btn-primary" @click="updateEvent()">Update</button>
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
      errorMessage: null
    }
  },
  props: {
    params: Object
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    updateEvent(){
      if(this.validate()){
        let parameter = {
          id: this.params.id,
          status: this.params.status,
          title: this.params.title,
          more_info: this.params.more_info,
          date: this.params.date,
          start_time: this.params.start_time,
          end_time: this.params.end_time,
          venue: this.params.venue,
          ticket_price: this.params.ticket_price,
          max_attendees: this.params.max_attendees
        }
        this.APIRequest('events/update', parameter).then(response => {
          if(response.data === true){
            ROUTER.go('/affiliates')
          }
        })
      }
    },
    validate(){
      let i = this.params
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
