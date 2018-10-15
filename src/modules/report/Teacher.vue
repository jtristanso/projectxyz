<template>
  <div>
    <div class="module-header">
      <div class="items-display pull-right">
        <label>My Courses</label>
        <select v-model="selectedIndex" v-on:change="selectCourse()" v-if="data !== null">
          <option v-for="item, index in data" v-bind:value="index">{{item.code}}</option>
        </select>
        <label v-else class="text-danger">EMPTY</label>
      </div>
    </div>
    <div class="report-holder">
      <span v-if="selected !== null && selected.reports !== null">
        <span class="report-item" v-for="item, index in selected.reports">
          <span class="item-description">
            <b>{{item.description}}</b>
          </span>
          <i class="fa fa-file-excel-o text-green"></i>
          <span class="item-details">
            {{item.spreadsheet_title}}
          </span>
          <span class="action bg-green" v-on:click="newWindow(item.spreadsheet)">Open</span>
        </span>
      </span>
    </div>
<!--     <div class="customModal" id="generatingReports">
      <span class="loading">
        <h2>Generating Reports...</h2>
        <i class="fas fa-spinner fa-spin"></i>
      </span>
    </div> -->
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.retrieveRequest()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      selectedIndex: null,
      selected: null,
      data: null
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequest(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'clause': '=',
          'column': 'account_id'
        }, {
          'value': this.user.selectedSemester.id,
          'clause': '=',
          'column': 'account_semester_id'
        }]
      }
      this.APIRequest('courses/retrieve', parameter).then(response => {
        if(response.data.length <= 0){
          this.data = null
          this.selectedIndex = null
          this.selected = null
        }else{
          this.data = response.data
          if(this.selectedIndex === null){
            this.selected = response.data[0]
            this.selectedIndex = 0
          }else{
            this.selectCourse()
          }
        }
      })
    },
    selectCourse(){
      this.selected = this.data[this.selectedIndex]
    },
    newWindow(id){
      window.open('https://docs.google.com/spreadsheets/d/' + id)
    }
  }
}
</script>
<style>
.module-header{
  width: 100%;
  float: left;
}

.report-holder{
  width: 90%;
  float: left;
  margin-left: 5%;
  margin-right: 5%;
  min-height: 100px;
  overflow-y: hidden;
}

.report-item{
  width: 19%;
  float: left;
  height: 250px;
  border: solid 1px #ddd;
  margin-right: 1%;
  margin-bottom: 10px;
}

.item-whole{
  margin-right: 80%;
  margin-bottom: 10px;
}
.report-item .item-description{
  width: 100%;
  float: left;
  height: 50px;
  text-align: center;
  line-height: 50px;
  border-bottom: solid 1px #ddd;
}

.report-item i{
  width: 100%;
  float: left;
  line-height: 100px;
  font-size: 50px;
  text-align: center;
}
.report-item .item-details{
  width: 90%;
  float: left;
  margin-left: 5%;
  margin-right: 5%;
  text-align: justify;
  height: 50px;
  padding-top: 10px;
}
.report-item .generate{
  height: 150px;
}
.report-item .action{
  width: 100%;
  float: left;
  height: 50px;
  line-height: 50px;
  text-align: center;
  color: #fff;
}

.report-item .action:hover{
  cursor: pointer;
}


.customModal{
  position: fixed;
  background-color: rgba(0, 0, 0, 1);
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100000 !important;
  opacity: 0.5;
  pointer-events: none;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  transition: all 0.3s;
  text-align: center;
  display: none;
}
.customModal .loading{
  font-size: 50px;
  margin-top: 200px;
  height: 50px;
  float: left;
  width: 100%;
  text-align: center;
  color: #fff;
}
</style>
