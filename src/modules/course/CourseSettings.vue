<template>
  <div v-if="selected !== null">
    <ul class="course-settings-menu">
      <li v-for="item, index in courseSettingsMenu" v-bind:class="{'bg-selected-menu': item.flag === true}"  v-on:click="makeActive(index)">{{item.title}}
        <i class="fa fa-pencil pull-right action-link" style="padding-right: 10px;" v-if="(item.flag === true && selected.editable === true && index === 2)" v-on:click="editSettings(item)"></i>
        <i class="fa fa-pencil pull-right action-link" style="padding-right: 10px;" v-if="(item.flag === true && selected.grade_settings[0].editable === true && (index === 1 || index === 0))" v-on:click="editSettings(item)"></i>

      </li>
    </ul>
    <div class="course-settings-content">
      <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 0">
        <pie-chart 
          :data="[
          selected.grade_settings[0].exams_rate,
          selected.grade_settings[0].quizzes_rate,
          selected.grade_settings[0].attendance_rate,
          selected.grade_settings[0].projects_rate
          ]"
          :colors="chart.colors"
          :labels="chart.labels"
          v-if="(selected.grade_settings[0].percentage_edit_flag === false && selected.grade_settings[0].editable === true) || selected.grade_settings[0].editable === false"
          class="chart">
        </pie-chart>
        <span v-else>
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
           <div class="input-group">
            <span class="input-group-addon">Exams Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].exams_rate">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Quizzes Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].quizzes_rate">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Attendance Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].attendance_rate">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Projects Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].projects_rate">
          </div>

          <button class="btn btn-primary pull-right" style="margin-top: 5px;" v-on:click="updatePercentage(selected.grade_settings[0])">
            Update
          </button>
          <button class="btn btn-danger pull-right" style="margin-top: 5px;margin-right: 10px;" v-on:click="editSettings({title: 'Grade Details'})">
            Cancel
          </button>
        </span>
      </span>
      <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 1">


        <span class="passing-percentage-details" v-if="(selected.grade_settings[0].passing_edit_flag === false && selected.grade_settings[0].editable === true) || selected.grade_settings[0].editable === false">
          <label>Quizzes ({{selected.grade_settings[0].passing_percentage_quizzes}}%)</label>
          <div class="progress">
            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(selected.grade_settings[0].passing_percentage_quizzes) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <label>Exams ({{selected.grade_settings[0].passing_percentage_exams}}%)</label>
          <div class="progress">
            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" v-bind:style="'width:' + parseInt(selected.grade_settings[0].passing_percentage_exams) + '%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </span>


        <span v-else>
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
          <div class="input-group">
            <span class="input-group-addon">Quizzes Passing Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].passing_percentage_quizzes">
          </div>

          <div class="input-group">
            <span class="input-group-addon">Exam Passing Rate</span>
            <input type="text" class="form-control" v-model="selected.grade_settings[0].passing_percentage_exams">
          </div>
          <button class="btn btn-primary pull-right" style="margin-top: 5px;" v-on:click="updatePassing(selected.grade_settings[0])">
            Update
          </button>
          <button class="btn btn-danger pull-right" style="margin-top: 5px;margin-right: 10px;" v-on:click="editSettings({title: 'Tests Details'})">
            Cancel
          </button>
        </span>


      </span>
      <span class="item-content" v-if="courseSettingsMenuSelectedIndex === 2">


        <div v-if="selected.course_details_edit_flag === false">
          <span class="item-details">
            <label class="details-title">Course Code</label>
            <label class="details-content">{{selected.code}}</label>
          </span>
           <span class="item-details">
            <label class="details-title">Course Description</label>
            <label class="details-content">{{selected.description}}</label>
          </span>
          <span class="item-details">
            <label class="details-title">Course Units</label>
            <label class="details-content">{{selected.units}}</label>
          </span>
          <span class="item-details">
            <label class="details-title">Schedule Time</label>
            <label class="details-content">{{selected.d_time_start + ' - ' + selected.d_time_end}}</label>
          </span>
           <span class="item-details">
            <label class="details-title">Schedule Days</label>
            <label class="details-content">{{selected.days}}</label>
          </span>
        </div>



        <div v-else>
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
          <div class="input-group">
            <span class="input-group-addon">Course Code</span>
            <input type="text" class="form-control" v-model="selected.code" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Course Description</span>
            <input type="text" class="form-control" v-model="selected.description" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Course Units</span>
            <input type="text" class="form-control" v-model="selected.units" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time Start</span>
            <input type="time" class="form-control" v-model="selected.time_start" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Time End</span>
            <input type="time" class="form-control" v-model="selected.time_end" placeholder="Type course code here...">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Days</span>
            <input type="text" class="form-control" v-model="selected.days" placeholder="Type course code here...">
          </div>

          <button class="btn btn-primary pull-right" style="margin-top: 5px;" v-on:click="updateCourse(selected)">
            Update
          </button>
          <button class="btn btn-danger pull-right" style="margin-top: 5px;margin-right: 10px;" v-on:click="editSettings({title: 'Course Details'})">
            Cancel
          </button>  
        </div>
      </span>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
import Chart from 'chart.js'

export default {
  components: {
    'pie-chart': require('../../components/chart/Pie.vue')
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      errorMessage: null,
      courseSettingsMenu: [
        {'title': 'Grade Details', 'flag': true},
        {'title': 'Tests Details', 'flag': false},
        {'title': 'Course Details', 'flag': false}
      ],
      courseSettingsMenuSelectedIndex: 0,
      chart: {
        colors: ['#8cd3ff', '#f99494', '#fac364', '#a1dbb1'],
        labels: ['Exams', 'Quizzes', 'Attendance', 'Projects']
      }
    }
  },
  props: {
    selected: Object
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    makeActive(index){
      if(this.courseSettingsMenuSelectedIndex === null){
        this.courseSettingsMenu[index].flag = true
        this.courseSettingsMenuSelectedIndex = index
      }else{
        if(this.courseSettingsMenuSelectedIndex !== index){
          this.courseSettingsMenu[index].flag = true
          this.courseSettingsMenu[this.courseSettingsMenuSelectedIndex].flag = false
          this.courseSettingsMenuSelectedIndex = index
        }
      }
    },
    editSettings(item){
      if(item.title === 'Course Details'){
        this.selected.course_details_edit_flag = !this.selected.course_details_edit_flag
      }else if(item.title === 'Grade Details'){
        this.selected.grade_settings[0].percentage_edit_flag = !this.selected.grade_settings[0].percentage_edit_flag
      }else if(item.title === 'Tests Details'){
        this.selected.grade_settings[0].passing_edit_flag = !this.selected.grade_settings[0].passing_edit_flag
      }
    },
    updatePassing(item){
      if(item.id === null){
        // Create
        if(this.validatePassingRate(item) === true){
          let parameter = {
            'passing_percentage_quizzes': item.passing_percentage_quizzes,
            'passing_percentage_exams': item.passing_percentage_exams,
            'exams_rate': 0,
            'quizzes_rate': 0,
            'attendance_rate': 0,
            'projects_rate': 0,
            'course_id': item.course_id,
            'account_semester_id': item.account_semester_id
          }
          this.APIRequest('grade_settings/create', parameter).then(response => {
            if(response.data > 0){
              this.$parent.retrieveRequest(true)
            }
          })
        }
      }else{
        // Update
        if(this.validatePassingRate(item) === true){
          this.APIRequest('grade_settings/update_by_course', item).then(response => {
            if(response.data === true){
              this.$parent.retrieveRequest(true)
            }
          })
        }
      }
    },
    updateCourse(){
      let t = this.selected
      if(t.code !== null || t.description !== null || t.units !== null || t.time_start !== null || t.time_end !== null || t.days !== null){
        let parameter = {
          'id': this.selected.id,
          'code': this.selected.code,
          'description': this.selected.description,
          'units': this.selected.units,
          'time_start': this.selected.time_start,
          'time_end': this.selected.time_end,
          'days': this.selected.days
        }
        this.APIRequest('courses/update', parameter).then(response => {
          if(response.data === true){
            this.$parent.retrieveRequest(true)
          }
        })
      }else{
        //
      }
    },
    updatePercentage(item){
      if(item.id === null){
        // Create
        if(this.validatePercentageRate(item) === true){
          let parameter = {
            'passing_percentage_quizzes': 0,
            'passing_percentage_exams': 0,
            'exams_rate': 0,
            'quizzes_rate': 0,
            'attendance_rate': 0,
            'projects_rate': 0,
            'course_id': item.course_id,
            'account_semester_id': item.account_semester_id
          }
          this.APIRequest('grade_settings/create', parameter).then(response => {
            if(response.data > 0){
              this.$parent.retrieveRequest(true)
            }
          })
        }
      }else{
        // Update
        if(this.validatePercentageRate(item) === true){
          this.APIRequest('grade_settings/update_by_course', item).then(response => {
            if(response.data === true){
              this.$parent.retrieveRequest(true)
            }
          })
        }
      }
    },
    validatePercentageRate(item){
      let total = parseInt(item.exams_rate) + parseInt(item.quizzes_rate) + parseInt(item.attendance_rate) + parseInt(item.projects_rate)
      if(total > 100){
        this.errorMessage = 'Percentage value must not greater than 100.'
        return false
      }else if(isNaN(item.exams_rate) === true || isNaN(item.quizzes_rate) === true || isNaN(item.attendance_rate) === true || isNaN(item.projects_rate) === true){
        this.errorMessage = 'Input must be a number.'
      }else{
        this.errorMessage = null
        return true
      }
    },
    validatePassingRate(item){
      if(parseInt(item.passing_percentage_exams) > 100 || parseInt(item.passing_percentage_quizzes) > 100){
        this.errorMessage = 'Passing value must not greater than 100.'
        return false
      }else if(isNaN(item.passing_percentage_quizzes) === true || isNaN(item.passing_percentage_exams) === true){
        this.errorMessage = 'Input must be a number.'
        return false
      }else{
        this.errorMessage = null
        return true
      }
    }
  }
}
</script>
<style>
.form-control{
  height: 45px !important;
}

.item-content{
  float: left;
  width: 100%;
  min-height: 200px;
  overflow-y: hidden;
}

.item-content .item-details{
  float: left;
  width: 100%;
  height: 35px;
}

.item-details .details-title{
  width: 25%;
  float: left;
  font-weight: 600;
}

.item-details .details-content{
  float: left;
  width: 75%;
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

.course-settings-menu{
  width: 25%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
  list-style: none;
  padding: 0px;
}

.course-settings-menu li{
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 10px;
}

.course-settings-menu li:hover{
  cursor: pointer;
  background: #eaeaea;
}

.course-settings-content{
  width: 74%;
  margin-left: 1%;
  float: left;
  min-height: 50px;
  overflow-y: hidden;
}

</style>
