<template>
  <div v-if="item !== null">
    <div class="event-header event-header-user" v-if="item.event.featured !== null">
      <img :src="config.BACKEND_URL + item.event.featured.url" class="featured-image" />
      <img :src="config.BACKEND_URL + item.qr_code_url" class="qr-code">
    </div>
    <div class="event-about">
      <div class="event-date">
        <label class="month">{{item.event.view_date_without_year}}</label>
      </div>
      <div class="event-title">
        {{item.event.title}}
      </div>
    </div>
    <div class="event-status">
      <label class="text-green"><b>{{item.payment_method}} &bull; {{item.payment_status}}</b></label>
    </div>
    <div class="event-info">
      <div class="more-info" v-if="item.event.more_info !== null">{{item.event.more_info}}</div>
      <div class="time">
        <i class="fa fa-clock"></i> {{item.event.view_start_time + ' - ' + item.event.view_end_time}}
      </div>
      <div class="address">
        <i class="fas fa-map-marker-alt"></i> {{item.event.venue}}
      </div>
      <div class="tickets">
        <i class="fa fa-ticket"></i>
        <label v-if="parseInt(item.event.ticket_price) > 0">PHP {{item.event.ticket_price}}.00</label>
        <label v-else>Free</label>
      </div>
    </div>
<!--     <ul class="event-menu">
      <li>
        <i class="fas fa-comments"  style="padding-right: 20px;"></i> Discussions
      </li>
    </ul> -->

  </div>
</template>

<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import CONFIG from '../../config.js'
import axios from 'axios'
export default {
  mounted(){
    this.retrieveTicket()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      code: this.$route.params.code,
      item: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveTicket(){
      let parameter = {
        condition: [{
          value: this.code,
          clause: '=',
          column: 'code'
        }]
      }
      this.APIRequest('event_tickets/retrieve_by_code', parameter).then(response => {
        if(response.data.length > 0){
          this.item = response.data[0]
        }else{
          this.item = null
        }
      })
    }
  }
}
</script>
<style scoped>
.event-header{
  height: 250px;
  float: left;
  width: 100%;
  text-align: center;
  overflow-y: hidden;
  border: solid 1px #ddd;
  position: relative;
}
.event-header .featured-image{
  width: 100%;
  height: auto;
}
.event-header .qr-code{
  width: 150px;
  height: auto;
  position: absolute;
  top: 50px;
  right: 50px;
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

.event-status{
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
  border-bottom: solid 1px #ddd;
  border-top: solid 1px #ddd;
}

.event-menu li{
  width: 25%;
  float: left;
  line-height: 50px;
}

.event-menu li:hover{
  cursor: pointer;
}
</style>
