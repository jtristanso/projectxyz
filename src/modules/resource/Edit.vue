<template>
  <div class="modal fade" id="editResourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="params != null">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Edit Resources</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span v-if="errorMessage !== null" class="text-danger text-center">
              <label><b>Opps! </b>{{errorMessage}}</label>
          </span>
          <div class="input-group">
            <span class="input-group-addon">Type</span>
            <select class="form-control" v-model="params.type">
              <option v-bind:value="item.font" v-for="item, index in resourcesFileTypes">{{item.type}}</option>
            </select>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Title</span>
            <input type="text" class="form-control" v-model="params.title" placeholder="Resource Name">
          </div>
          <div class="input-group">
            <span class="input-group-addon">URL</span>
            <input type="text" class="form-control" v-model="params.url" placeholder="Resource URL">
          </div>
          <div class="input-group">
            <span class="input-group-addon">Status</span>
            <select class="form-control" v-model="params.status">
              <option value="PUBLIC">PUBLIC</option>
              <option value="PRIVATE">PRIVATE</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="updateResource()">Update</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'

export default {
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      errorMessage: null,
      resourcesFileTypes: [
        {'type': 'PDF', 'font': 'fa fa-file-pdf-o'},
        {'type': 'MS Powerpoint', 'font': 'fa fa-file-powerpoint-o'},
        {'type': 'MS Excel', 'font': 'fa fa-file-excel-o'},
        {'type': 'MS Word', 'font': 'fa fa-file-word-o'},
        {'type': 'Image', 'font': 'fa fa-file-image-o'},
        {'type': 'Video', 'font': 'fa fa-file-movie-o'},
        {'type': 'Others', 'font': 'fa fa-file'}
      ]
    }
  },
  props: {
    params: Object
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    },
    updateResource(){
      this.APIRequest('resources/update', this.params).then(response => {
        if(response.data === true){
          this.$parent.retrieve()
          $('#editResourceModal').modal('hide')
        }
      })
    }
  }
}
</script>
<style scoped>

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
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
</style>
