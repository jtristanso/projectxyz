<template>
  <div>
    <div class="module-header">
      <div class="item-display">
        <label>View By</label>
        <select v-model="viewBy">
          <option value="month">Month</option>
          <option value="week">Week</option>
        </select>
      </div>
    </div>
    <div class="calendar-holder" v-if="viewBy === 'month'">
      <div class="calendar-selected-date" v-if="selectedDate !== null">
        <div class="calendar-day"><h1>{{selectedDate.day}}</h1></div>
        <div class="calendar-complete-dates">{{selectedDate.month_text + ' ' + selectedDate.date}}</div>
        <div class="calendar-list"></div>
      </div>
      <div class="calendar-selected-date" v-else>
        <div class="calendar-day"><h3>No Date Selected</h3></div>
        <div class="calendar-complete-dates">Click the date of the  calendar</div>
        <div class="calendar-list"></div>
      </div>
      <div class="calendar-dates">
        <div class="dates-header">
          <label class="prev">
            <i class="fa fa-chevron-left" v-on:click="prev()"></i>
          </label>
          <label class="dates" v-if="data !== null">
            {{data[3].dates[0].month_text + ' ' + data[3].dates[0].year}}
          </label>
          <label class="next">
            <i class="fa fa-chevron-right" v-on:click="next()"></i>
          </label>
        </div>
        <ul class="days">
          <li style="margin"></li>
          <li v-for="item, index in days" class="days-holder">{{item.day}}</li>
          <li style="margin"></li>
        </ul>
        <div class="dates-holder" v-for="item, index in data" v-if="data !== null">
          <ul class="dates">
            <li class="margin"></li>
            <li v-for="datesItem, datesIndex in item.dates" class="date-holder" v-bind:class="{'bg-current-date': datesItem.current_date === true, 'bg-selected': datesItem.selected === true}" v-if="item.dates !== null" v-on:click="selectDate(datesItem, index, datesIndex)">{{datesItem.date}}</li>
            <li class="margin"></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="calendar-holder" v-else>
      <div class="week-header">
        <label class="prev">
          <i class="fa fa-chevron-left"></i>
        </label>
        <label class="dates" v-if="data !== null">
          {{data[3].dates[0].month_text + ' ' + data[3].dates[0].year}}
        </label>
        <label class="next">
          <i class="fa fa-chevron-right"></i>
        </label>
      </div>
      <ul class="week-days">
        <li class="day-time">
          <label>Time</label>
<!--           <label class="pull-right">
             <i class="fa fa-chevron-up"></i>
             <i class="fa fa-chevron-down"></i>
          </label> -->
        </li>
        <li v-for="item, index in daysWeek" class="days-holder">{{item.day}}</li>
      </ul>
      <ul class="time-holder">
        <li class="time-item" v-for="item, index in time">
          <span class="time-details"><b>{{item.time}}</b></span>
          <span class="schedule-holder" v-for="scheduleItem, scheduleIndex in scheduleList">
            <span class="schedule-maybe-empty" v-for="innerItem, innerIndex in scheduleItem.day" v-if="scheduleItem.day !== null">
              <span class="schedule-not-empty" v-bind:class="innerItem.category" v-if="innerItem.start == item.hour || innerItem.end == item.hour || (item.hour >= innerItem.start && item.hour <= innerItem.end)">
                <label v-if="innerItem.start === item.hour">{{innerItem.details}}</label>
                <label v-else></label>
              </span>
            </span>
            <span class="schedule-empty" v-else></span>
          </span>
        </li>
      </ul>

    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveCalendar(0, null)
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      days: [
        {day: 'S'},
        {day: 'M'},
        {day: 'T'},
        {day: 'W'},
        {day: 'T'},
        {day: 'F'},
        {day: 'S'}
      ],
      daysWeek: [
        {day: 'Mon'},
        {day: 'Tue'},
        {day: 'Wed'},
        {day: 'Thu'},
        {day: 'Fri'},
        {day: 'Sat'},
        {day: 'Sun'}
      ],
      data: null,
      monthCounter: 0,
      selectedDate: null,
      prevIndex: null,
      prevDatesIndex: null,
      viewBy: 'month',
      time: [
        {time: '12:00 AM', hour: 0},
        {time: '12:30 AM', hour: 0.5},
        {time: '1:00 AM', hour: 1},
        {time: '1:30 AM', hour: 1.5},
        {time: '2:00 AM', hour: 2},
        {time: '2:30 AM', hour: 2.5},
        {time: '3:00 AM', hour: 3},
        {time: '3:30 AM', hour: 3.5},
        {time: '4:00 AM', hour: 4},
        {time: '4:30 AM', hour: 4.5},
        {time: '5:00 AM', hour: 5},
        {time: '5:30 AM', hour: 5.5},
        {time: '6:00 AM', hour: 6},
        {time: '6:30 AM', hour: 6.5},
        {time: '7:00 AM', hour: 7},
        {time: '7:30 AM', hour: 7.5},
        {time: '8:00 AM', hour: 8},
        {time: '8:30 AM', hour: 8.5},
        {time: '9:00 AM', hour: 9},
        {time: '9:30 AM', hour: 9.5},
        {time: '10:00 AM', hour: 10},
        {time: '10:30 AM', hour: 10.5},
        {time: '11:00 AM', hour: 11},
        {time: '11:30 AM', hour: 11.5},
        {time: '12:00 AM', hour: 12},
        {time: '12:30 AM', hour: 12.5},
        {time: '1:00 PM', hour: 13},
        {time: '1:30 PM', hour: 13.5},
        {time: '2:00 PM', hour: 14},
        {time: '2:30 PM', hour: 14.5},
        {time: '3:00 PM', hour: 15},
        {time: '3:30 PM', hour: 15.5},
        {time: '4:00 PM', hour: 16},
        {time: '4:30 PM', hour: 16.5},
        {time: '5:00 PM', hour: 17},
        {time: '5:30 PM', hour: 17.5},
        {time: '6:00 PM', hour: 18},
        {time: '6:30 PM', hour: 18.5},
        {time: '7:00 PM', hour: 19},
        {time: '7:30 PM', hour: 19.5},
        {time: '8:00 PM', hour: 20},
        {time: '8:30 PM', hour: 20.5},
        {time: '9:00 PM', hour: 21},
        {time: '9:30 PM', hour: 21.5},
        {time: '10:00 PM', hour: 22},
        {time: '10:30 PM', hour: 22.5},
        {time: '11:00 PM', hour: 23},
        {time: '11:30 PM', hour: 23.5}
      ],
      scheduleList: [
        {
          day: [
            {day: 'Mon', start: 9.5, end: 10.5, details: 'General Assembly', category: 'events'},
            {day: 'Mon', start: 11.5, end: 15.5, details: 'General Assembly', category: 'events'}
          ]
        },
        {
          day: [
            {day: 'Tue', start: 7.5, end: 9, details: 'General Assembly', category: 'course-class'},
            {day: 'Tue', start: 12, end: 13.5, details: 'General Assembly', category: 'notes'}
          ]
        },
        {day: null},
        {day: null},
        {day: null},
        {day: null},
        {day: null}
      ],
      calendarWeek: null
    }
  },
  methods: {
    retrieveCalendar(plusMonth, lessMonth){
      let parameter = {
        plus_month: plusMonth,
        less_month: lessMonth
      }
      this.APIRequest('calendars/retrieve', parameter).then(response => {
        this.data = response.data
        this.selectedDate = response.current
      })
    },
    prev(){
      this.monthCounter--
      this.call()
    },
    next(){
      this.monthCounter++
      this.call()
    },
    call(){
      if(this.monthCounter < 0){
        this.retrieveCalendar(null, (this.monthCounter * (-1)))
      }else{
        this.retrieveCalendar(this.monthCounter, null)
      }
    },
    selectDate(datesItem, index, datesIndex){
      this.selectedDate = datesItem
      if(this.prevIndex === null && this.prevDatesIndex === null){
        this.data[index].dates[datesIndex].selected = true
        this.prevIndex = index
        this.prevDatesIndex = datesIndex
      }else{
        if(this.prevDatesIndex !== datesIndex || this.prevIndex !== index){
          this.data[this.prevIndex].dates[this.prevDatesIndex].selected = false
          this.data[index].dates[datesIndex].selected = true
          this.prevIndex = index
          this.prevDatesIndex = datesIndex
        }
      }
    },
    retrieveByWeek(plusWeek, lessWeek){
      let parameter = {
        plus_week: plusWeek,
        less_week: lessWeek
      }
      this.APIRequest('calendars/retrieve_week', parameter).then(response => {
        if(response.data !== null){
          this.calendarWeek = response.data
        }
      })
    }
  }
}
</script>
<style>
.calendar-holder{
  width: 90%;
  float: left;
  margin-left: 5%;
  margin-right: 5%;
  min-height: 100px;
  overflow-y: hidden;
}
.calendar-selected-date{
  width: 40%;
  float: left;
  min-height: 400px;
  overflow-y: hidden;
  background: #028170;
  color: #fff;
}

.calendar-day{
  width: 100%;
  float: left;
  margin-top: 50px;
  padding-left: 50px;
}

.calendar-complete-dates{
  width: 100%;
  float: left;
  font-size: 16px;
  padding-left: 50px;
}

.calendar-dates{
  width: 60%;
  float: left;
  min-height: 400px;
  overflow-y: hidden;
  border: solid 1px #ddd;
}

.dates-header{
  width: 100%;
  float: left;
  height: 50px;
  border-bottom: solid 1px #ddd;
}

.dates-header .prev, .dates-header .next{
  width: 5%;
  float: left;
  text-align: center;
  font-size: 24px;
  color: #028170;
}
.dates-header .prev i, .dates-header .next i{
  padding-top: 12px;
}

.dates-header .prev i:hover, .dates-header .next i:hover{
  cursor: pointer;
  color: #555;
}
.dates-header .dates{
  width: 90%;
  float: left;
  text-align: center;
  color: #028170;
  padding-top: 5px;
  font-size: 24px;
}

.calendar-dates .days{
  width: 100%;
  float: left;
  list-style: none;
  padding: 0px;
}

.days .days-holder{
  width: 14%;
  float: left;
  text-align: center;
  color: #028170;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 20px;
}

.days .margin, .dates .margin{
  width: 1%;
  float: left;
}

.dates-holder{
  width: 100%;
  float: left;
  height: 50px;
}
.dates-holder .dates{
  width: 100%;
  float: left;
  list-style: none;
  padding: 0px;
}

.dates-holder .date-holder{
  width: 14%;
  float: left;
  text-align: center;
  color: #aaa;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 20px;
}

.dates-holder .date-holder:hover{
  cursor: pointer;
  background: #FCCD04;
  color: #fff;
}

.bg-current-date{
  background: #028170;
  color: #fff !important;
}

.bg-selected{
  background: #FCCD04;
  color: #fff !important;
}

.calendar-holder .week-header{
  width: 40%;
  float: right;
}

.week-header .prev, .week-header .next{
  width: 5%;
  float: left;
  text-align: center;
  font-size: 24px;
  color: #028170;
}

.week-header .prev i, .week-header .next i{
  padding-top: 12px;
}

.week-header .prev i:hover, .week-header .next i:hover{
  cursor: pointer;
  color: #555;
}
.week-header .dates{
  width: 90%;
  float: left;
  text-align: center;
  color: #028170;
  padding-top: 5px;
  font-size: 24px;
}

.calendar-holder .week-days{
  width: 100%;
  float: left;
  list-style: none;
  padding: 0px;
  border-bottom: solid 2px #028170;
  margin-bottom: 0px;
}

.week-days .days-holder{
  width: 12%;
  float: left;
  text-align: center;
  color: #028170;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 20px;
}

.week-days .day-time{
  width: 16%;
  float: left;
}
.day-time label{
  text-align: center;
  color: #028170;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 20px;
}

.time-holder{
  float: left;
  width: 100%;
  min-height: 50px;
  list-style: none;
  overflow-y: hidden;
  padding: 0px;
}
.time-holder .time-item{
  width: 100%;
  float: left;
  height: 50px;
}

.time-item .time-details{
  width: 16%;
  float: left;
  line-height: 50px;
  color: #028170;
  border-bottom: solid 1px #ddd;
  border-right: solid 1px #ddd;
  border-left: solid 1px #ddd;
  padding-left: 10px;
}
.time-item .schedule-holder{
  width: 12%;
  float: left;
  text-align: center;
  height: 50px;
  color: #fff;
  line-height: 50px;
  font-size: 11px;
}
.schedule-empty{
  width: 100%;
  float: left;
  border-bottom: solid 1px #ddd;
}

.schedule-not-empty{
  width: 100%;
  float: left;
  border-bottom: 0px;
  z-index: 10;
}

.schedule-maybe-empty{
  width: 100%;
  float: left;
  border-bottom: 0px;
}

.course-class{
  background: #028170;
}

.events{
  background: #3f0050;
}

.notes{
  background: #FCCD04;
}
</style>
