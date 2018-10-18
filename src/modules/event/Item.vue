<template>
  <div>
    <div class="event-header" v-if="item.featured !== null && memberType === 'ADMIN'" v-on:click="addFeaturedImage(item.id)">
      <img :src="config.BACKEND_URL + item.featured.url"/>
      <input type="file" id="addFeaturedImage" name="file" accept="image/*"  @change="setUpFileUploadFeaturedImage($event)">
    </div>
    <span class="event-header" v-on:click="addFeaturedImage(item.id)" v-else-if="item.featured === null && memberType === 'ADMIN'" title="Click to add">
      <i class="fa fa-plus-circle"></i>
      <label>Add Featured Image</label>
      <input type="file" id="addFeaturedImage" name="file" accept="image/*"  @change="setUpFileUploadFeaturedImage($event)">
    </span>
    <div class="event-header event-header-user" v-else>
      <img :src="config.BACKEND_URL + item.featured.url" v-if="item.featured !== null"/>
    </div>
    <div class="event-about">
      <div class="event-date">
        <label class="month">{{item.view_date}}</label>
      </div>
      <div class="event-title">
        {{item.title}}
      </div>
      <div v-if="memberType === 'ADMIN'" class="pull-right event-admin-menu">
        <i class="fa fa-pencil text-primary" style="padding-right: 10px;" title="Edit" v-on:click="editEvent(item)"></i>
        <i class="fa fa-trash text-danger" title="Delete" v-on:click="deleteEvent(item.id)"></i>
      </div>
    </div>
    <div class="event-status">
      <label v-bind:class="{'text-green': item.status === 'PRIVATE' || item.status === 'PUBLIC', 'text-danger': item.status === 'PENDING'}" class=""><b>{{item.status}}</b></label>
      <label v-if="item.my_ticket !== null" class="text-green" style="padding-left: 5px;"> &bull; 
        <b style="padding-left: 5px;">I'm Going</b>
      </label>
      <label v-if="memberType === 'ADMIN' && item.date_flag === true" class="text-magento" style="padding-left: 5px;"> &bull; 
        <b style="padding-left: 5px;" class="text-link" data-toggle="modal" data-target="#viewAttendeesModal">View Attendees</b>
      </label>
      <label v-if="memberType === 'ADMIN' && item.date_flag === false" class="text-magento" style="padding-left: 5px;"> &bull; 
        <b style="padding-left: 5px;" class="text-link" data-toggle="modal" data-target="#viewAttendeesModal">View Attendance</b>
      </label>
    </div>
    <div class="event-info">
      <div class="more-info" v-if="item.more_info !== null">{{item.more_info}}</div>
      <div class="time">
        <i class="fa fa-clock"></i> {{item.view_start_time + ' - ' + item.view_end_time}}
      </div>
      <div class="address">
        <i class="fas fa-map-marker-alt"></i> {{item.venue}}
      </div>
      <div class="tickets">
        <i class="fa fa-ticket"></i>
        <label v-if="parseInt(item.ticket_price) > 0">PHP {{item.ticket_price}}.00</label>
        <label v-else>Free</label>
        <label v-if="parseInt(item.max_attendees) > 0">and {{item.total_tickets}} tickets left</label>
        <label v-if="parseInt(item.max_attendees) === 0 || item.max_attendees === null || item.total_tickets === 0">(Not Available)</label>
      </div>
      <div class="tickets">
        <rating :payload="event" :payloadValue="item.id"></rating>
      </div>
    </div>
    <ul class="event-menu">
      <li>
        <i class="fas fa-comments"  style="padding-right: 20px;"></i> Comments
      </li>
<!--       <li>
        <i class="fa fa-clock"  style="padding-right: 20px;"></i> Check Later
      </li> -->
      <li v-on:click="getTicket(item.id)" v-if="item.my_ticket === null">
        <i class="fa fa-ticket" style="padding-right: 20px;"></i> Get Ticket Now!
      </li>
      <li v-on:click="redirect('tickets/' + item.my_ticket.code)" v-if="item.my_ticket !== null">
        <i class="fa fa-ticket" style="padding-right: 20px;"></i> View My Ticket
      </li>
    </ul>
    <event-comments :id="item.id"></event-comments>

    <edit-event :params="selectedEvent" v-if="selectedEvent !== null"></edit-event>


    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationDeleteEventModal" v-if="toBeDeletedEventId !== null">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-warning text-danger"></i> Confirmation!</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this event?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="cancelDelete()">No</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="deleteRequest()">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <view-attendees :id="item.id"></view-attendees>

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
      selectedEvent: null,
      fileFeaturedImage: null,
      selectedEventId: null,
      toBeDeletedEventId: null
    }
  },
  components: {
    'edit-event': require('modules/event/Edit.vue'),
    'view-attendees': require('modules/event/Attendees.vue'),
    'event-comments': require('modules/event/Comments.vue'),
    'rating': require('modules/rating/Ratings.vue')
  },
  props: {
    item: Object,
    memberType: String
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    editEvent(item){
      this.selectedEvent = item
      setTimeout(() => {
        $('#editEventModal').modal('show')
      }, 100)
    },
    deleteEvent(id){
      this.toBeDeletedEventId = id
      setTimeout(() => {
        $('#confirmationDeleteEventModal').modal('show')
      }, 100)
    },
    cancelDelete(){
      this.toBeDeletedEventId = null
    },
    deleteRequest(){
      let parameter = {
        id: this.toBeDeletedEventId
      }
      this.APIRequest('events/delete', parameter).then(response => {
        ROUTER.go('/affiliates')
      })
    },
    updateFeaturedImage(){
      if(this.fileFeaturedImage !== null){
        let formData = new FormData()
        formData.append('event_id', this.selectedEventId)
        formData.append('file', this.fileFeaturedImage)
        formData.append('file_url', this.fileFeaturedImage.name)
        axios.post(this.config.BACKEND_URL + '/event_featured_images/create', formData).then(response => {
          ROUTER.go('/affiliates')
        })
      }
    },
    createFileFeaturedImage(file){
      let fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      this.updateFeaturedImage()
    },
    setUpFileUploadFeaturedImage(event){
      let files = event.target.files || event.dataTransfer.files
      if(!files.length){
        return false
      }else{
        this.fileFeaturedImage = files[0]
        this.createFileFeaturedImage(files[0])
      }
    },
    addFeaturedImage(id){
      this.selectedEventId = id
      $('#addFeaturedImage')[0].click()
    },
    getTicket(id){
      let parameter = {
        event_id: id,
        account_id: this.user.userID,
        payment_status: 'NOT_PAID',
        payment_method: 'CASH'
      }
      this.APIRequest('event_tickets/create', parameter).then(response => {
        if(response.data > 0){
          ROUTER.go('/affiliates')
        }
      })
    }
  }
}
</script>
<style scoped>
.event-item .event-header{
  height: 250px;
  float: left;
  width: 100%;
  text-align: center;
  overflow-y: hidden;
  border: solid 1px #ddd;
}
.event-header img{
  width: 100%;
  float: left;
  height: auto;
}
.event-header input{
  display: none;
}
.event-header:hover{
  cursor: pointer;
}
.event-header-user:hover{
  cursor: default;
}
.event-header i{
  font-size: 40px;
  width: 100%;
  float: left;
  margin-top: 75px;
}

.event-header label{
  width: 100%;
  float: left;
}

.event-item .event-about{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

.event-about .event-admin-menu{
  font-size: 20px;
  line-height: 50px;
}

.event-about .event-admin-menu i:hover{
  cursor: pointer;
}

.event-about .event-date{
  float: left;
  text-align: center;
  color: #ff0000;
  width: 70px;
}

.event-date .month, .event-date .date{
  line-height: 25px;
  font-size: 20px;
  line-height: 50px;
  float: left;
}


.event-about .event-title{
  float: left;
  line-height: 50px;
  font-weight: 550;
  padding-left: 25px;
}

.event-item .event-status{
  width: 100%;
  float: left;
}
.event-item .event-info{
  width: 100%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

.event-info .more-info{
  width: 100%;
  float: left;
  color: #555;
  text-align: justify;
}

.event-info .time, .event-info .address, .event-info .tickets{
  width: 100%;
  float: left;
  line-height: 50px;
}

.event-info div i{
  padding-right: 20px;
}

.event-item .event-menu{
  padding: 0px;
  list-style: none;
  margin: 0px;
  width: 100%;
  float: left;
  border-top: solid 1px #ddd;
  border-left: solid 1px #ddd;
  border-right: solid 1px #ddd;
}

.event-menu li{
  width: 25%;
  float: left;
  line-height: 50px;
}

.event-menu li i{
  padding-left: 10px;
}
.event-menu li:hover{
  cursor: pointer;
}
.text-link:hover{
  cursor: pointer;
  text-decoration: underline;
}
</style>
