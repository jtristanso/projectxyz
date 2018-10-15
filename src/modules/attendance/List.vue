<template>
  <div>
    <span class="resource-item bg-warning" style="margin-top: 5px;">
      <span class="icon" style="font-size: 30px; margin-top: 100px;">
        <i class="fa fa-line-chart"></i>
        <br>
        <label style="font-size: 12px;">Generate Summary</label>
      </span>
    </span>
    <span class="resource-item" style="margin-top: 5px;" v-on:click="redirect('/attendances/ft/' + enrolmentCode)">
      <span class="icon" style="font-size: 30px; margin-top: 100px;">
        <i class="fa fa-plus"></i>
        <br>
        <label style="font-size: 12px;">New Attendance</label>
      </span>
    </span>
    <span class="resource-item" style="margin-top: 5px;" v-for="item, index in attendance" v-if="attendance !== null" v-on:click="redirect('attendance/ft/' + item.code)">
      <span v-if="item.total  > 0">
      <doughnut-chart 
          :data="[item.present, item.late, item.absent]" 
          :colors="chart2.colors"
          :labels="chart2.labels"
          class="chart2">
        </doughnut-chart>
      </span>
    </span>
    <span v-else>
      <doughnut-chart
        :data="['100']"
        :colors="emptychart.colors"
        :labels="emptychart.labels"
        class="emptychart">
      </doughnut-chart>
    </span>
      
<!--     <span class="day">{{item.date.split(',')[0]}}</span>
    <span class="details">{{item.date.split(',')[1] + ' ' + item.date.split(',')[2] + ',' + item.date.split(',')[3] + ' ' + item.d_time}}</span> -->
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'doughnut-chart': require('../../components/chart/Circle.vue')
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      chart2: {
        colors: ['#028170', '#ffc107', '#dc3545'],
        labels: ['Present', 'Late', 'Absent']
      },
      emptychart: {
        colors: ['#949596'],
        labels: ['No attendance recorded yet']
      }
    }
  },
  props: ['enrolmentCode', 'attendance'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
.new-resource{
  width: 49%;
  float: left;
  margin-right: 1%;
  height: 250px;
}

.resource-item{
  width: 19%;
  float: left;
  margin-right: 1%;
  border-radius: 2px;
  border: solid 1px #ddd;
  height: 250px;
  margin-top: 10px;
}

.resource-item:hover{
  border: solid 1px #3f0050;
}

.resource-item .viewer{
  float: left;
  height: 20px;
  width: 90%;
  text-align: right;
  margin: 0 5% 0 5%;
  font-size: 10px;
}
.resource-item .viewer i{
  padding-right: 5px;
  font-size: 13px;
  padding-top: 2px;
}
.resource-item .icon{
  text-align: center;
  font-size: 100px;
  float: left;
  height: 200px;
  width: 90%;
  margin: 0 5% 0 5%;
}
.resource-item .details{
  text-align: center;
  float: left;
  height: 30px;
  width: 90%;
  margin: 0 5% 0 5%;
  font-size: 12px;
}

.resource-item .item-menu{
  margin-top: -15px;
  float: left;
  width: 100%;
  display: none;
}
.resource-item .item-menu i{
  width: 33%;
  float: left;
  padding-top: 10px;
  padding-bottom: 10px;
  color: #fff;
  text-align: center;
}
.resource-item .item-menu i:hover{
  cursor: pointer;
}
.resource-item .item-menu .item-right{
  width: 34%;
}
.resource-item .chart2{
  position: relative;
  padding-left: 8px;
  display: inline-block;
  height: 170px;
  width: 170px;
}
.resource-item .emptychart{
  position: relative;
  padding-left: 8px;
  display: inline-block;
  height: 170px;
  width: 170px;
}
.resource-item .day{
  position: relative;
  float: left;
  font-size: 30px;
  font-style: bold;
  bottom: 110px;
  left: 62px;
  z-index: -1;
}
</style>
