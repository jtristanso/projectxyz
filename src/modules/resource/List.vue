<template>
  <div>
    <span class="new-resource" v-if="newResourceFlag === true">
      <div style="width: 100%; float: left; text-align: center;" v-if="resourceErrorMessage !== null">
        {{resourceErrorMessage}}
      </div>
      <div class="input-group">
          <span class="input-group-addon">Type</span>
          <select class="form-control" v-model="newResource.type">
            <option v-bind:value="item.font" v-for="item, index in resourcesFileTypes">{{item.type}}</option>
          </select>
      </div>
      <div class="input-group">
          <span class="input-group-addon">Title</span>
          <input type="text" class="form-control" v-model="newResource.title" placeholder="Resource Name">
      </div>
      <div class="input-group">
          <span class="input-group-addon">URL</span>
          <input type="text" class="form-control" v-model="newResource.url" placeholder="Resource URL">
      </div>
      <div class="input-group">
          <span class="input-group-addon">Status</span>
          <select class="form-control" v-model="newResource.status">
            <option value="PUBLIC">PUBLIC</option>
            <option value="PRIVATE">PRIVATE</option>
          </select>
      </div>
      <button class="btn btn-primary pull-right" style="margin-top: 10px;" v-on:click="createRequest()">Add</button>
      <button class="btn btn-danger pull-right" style="margin-top: 10px;margin-right: 10px;" v-on:click="newResourceFlag = false">Cancel</button>
    </span>
    <span class="resource-item" v-if="newResourceFlag === false" v-on:click="newResourceFlag = true">
      <span class="icon" style="font-size: 30px; margin-top: 100px;">
        <i class="fa fa-plus"></i>
        <br>
        <label style="font-size: 12px;">New Resource</label>
      </span>
    </span>
    <span class="resource-item" v-for="item, index in data" v-if="data !== null" v-on:mouseover="showResourceMenu('resource-menu-' + index)">
      <span class="viewer" v-if="parseInt(item.resource_total_viewers) > 0">
        <i class="fa fa-eye text-primary"></i>{{item.resource_total_viewers}}
      </span>
      <span class="icon">
        <i v-bind:class="item.type"></i>
      </span>
      <span class="details">{{item.title}}</span>
      <span class="item-menu" v-bind:id="'resource-menu-' + index">
        <i class="fa fa-eye bg-primary" v-on:click="openResource(item.url)"></i>
        <i class="fa fa-pencil bg-warning" v-on:click="showEditResource(item)"></i>
        <i class="fa fa-trash bg-danger item-right" v-on:click="deleteResource(item.id)"></i>
      </span>
    </span>
    <edit-resource :params="editResource" v-if="editResource !== null"></edit-resource>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  components: {
    'edit-resource': require('modules/resource/Edit.vue')
  },
  mounted(){
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      resourcesFileTypes: [
        {'type': 'PDF', 'font': 'fa fa-file-pdf-o'},
        {'type': 'MS Powerpoint', 'font': 'fa fa-file-powerpoint-o'},
        {'type': 'MS Excel', 'font': 'fa fa-file-excel-o'},
        {'type': 'MS Word', 'font': 'fa fa-file-word-o'},
        {'type': 'Image', 'font': 'fa fa-file-image-o'},
        {'type': 'Video', 'font': 'fa fa-file-movie-o'},
        {'type': 'Others', 'font': 'fa fa-file'}
      ],
      newResource: {
        account_id: null,
        course_id: null,
        type: null,
        title: null,
        url: null,
        status: null
      },
      newResourceFlag: false,
      resourceErrorMessage: null,
      prevResourceMenuId: null,
      editResource: null,
      data: null
    }
  },
  props: ['id'],
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieve(){
      let parameter = {
        condition: [{
          column: 'course_id',
          clause: '=',
          value: this.id
        }]
      }
      this.APIRequest('resources/retrieve_by_course', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
    },
    createRequest(){
      if(this.newResource.type === null || this.newResource.title === null || this.newResource.url === null || this.newResource.status === null){
        this.resourceErrorMessage = 'Please fill in all required fields.'
      }else{
        this.newResource.course_id = this.id
        this.newResource.account_id = this.user.userID
        this.APIRequest('resources/create', this.newResource).then(response => {
          if(response.data > 0){
            this.resourceErrorMessage = null
            this.newResourceFlag = false
            this.retrieve()
          }
        })
      }
    },
    showResourceMenu(id){
      if(this.prevResourceMenuId === null){
        $('#' + id).show(500)
        this.prevResourceMenuId = id
      }else{
        if(this.prevResourceMenuId === id){
          //
        }else{
          $('#' + this.prevResourceMenuId).hide(500)
          this.prevResourceMenuId = id
          $('#' + id).show(500)
        }
      }
    },
    openResource(url){
      window.open(url)
    },
    deleteResource(id){
      let parameter = {
        'id': id
      }
      this.APIRequest('resources/delete', parameter).then(response => {
        this.retrieve()
      })
    },
    showEditResource(item){
      this.editResource = item
      setTimeout(() => {
        $('#editResourceModal').modal('show')
      }, 100)
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
