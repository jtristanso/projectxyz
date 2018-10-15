<template>
  <div>
    <div class="dashboard-item">
      <span class="header">
        <label>
          ANNOUNCEMENTS
          <i class="fa fa-globe pull-right"></i>
        </label>
      </span>
      <div class="content">
        <span class="content-title" >
          <b>{{accountNotif.title}}</b>
        </span>
        <span class="content-description">
          {{accountNotif.description}}
        </span>
      </div>
    </div>
    <button class="btn btn-primary" v-on:click="testAudio()">Test</button>

    <button class="btn btn-primary" v-on:click="newGoogleSheet()">Google Sheeet</button>

<!--     <div class="chart-container">
      <pie-chart
      :data="['13.2', '54.8', '20.0'
      ]"
      :colors="chart.colors"
      :labels="chart.labels"
      class="chart"
      >
      </pie-chart>
    </div> -->
  </div>
</template>
<script>
  import ROUTER from '../../router'
  import Vue from 'vue'
  import AUTH from '../../services/auth'
  import Chart from 'chart.js'
  export default{
    components: {
      'pie-chart': require('../../components/chart/Pie.vue')
    },
    data(){
      return {
        // only sample data. data should be from database
        accountNotif: {
          'title': 'Update Personal Informations',
          'description': 'You need to update your personal informations before you can use the functions of Classworx.',
          'url': 'account_settings'
        },
        chart: {
          colors: ['#028170', '#ffc107', '#dc3545'],
          labels: ['Per A', 'Per B', 'Per C']
        },
        notifsSelectedIndex: 0
      }
    },
    props: {
    },
    methods: {
      redirect(parameter){
        ROUTER.push(parameter)
      },
      testAudio(){
        AUTH.playNotificationSound()
      },
      newGoogleSheet(){
        this.APIRequest('gsheets/auth', {}).then(url => {
          window.location.href = url
        })
      }
    }

  }
</script>
<style scoped>

.dashboard-item{
  width: 46%;
  float: left;
  border-top: solid 1px #ddd;
  border-left: solid 1px #ddd;
  border-right: solid 1px #ddd;
  margin-right: 2%;
  margin-left: 2%;
  min-height: 50px;
  overflow-y: hidden;
  margin-top: 50px;
}

.dashboard-item .header{
  width: 100%;
  float: left;
  padding-top: 10px;
  padding-bottom: 5px;
  border-bottom: solid 1px #ddd;
}

.dashboard-item .header label{
  width: 96%;
  margin-right: 2%; 
  margin-left: 2%;
  float: left;
}

.dashboard-item .content{
  width: 100%;
  float: left;
  border-bottom: solid 1px #ddd;
}

.content .content-title{
  width: 96%;
  margin-right: 2%;
  margin-left: 2%;
  float: left;
  font-size: 12px;
  margin-top: 5px;
}


.content .content-description{
  width: 96%;
  margin-right: 2%;
  margin-left: 2%;
  float: left;
  color: #999;
  font-size: 12px;
  text-align: justify;
  margin-top: 5px;
  margin-bottom: 5px;
}

.dashboard-item .content:hover{
  cursor: pointer;
  background: #efefef;
}





.chart-container{
  border-style: ridge;
  border-color: #c7c9cc;
  border-width: thin;
  border-top: none;
  position: relative;
  float: left;
  margin-left: 2%;
  padding-left: 80px;
  height: 350px;
  width: 60%;
}

.chart{
  float: left;
  padding-left: 80px;
  width: 80%;
  height: 330px;
  float: left;
}

</style>
