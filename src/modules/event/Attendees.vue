<template>
    <div class="modal fade" id="viewAttendeesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="data !== null">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Attendees</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td scope="col"><b>School</b></td>
                  <td scope="col"><b>Name</b></td>
                  <td scope="col"><b>Email</b></td>
                  <td scope="col"><b>Contact Number</b></td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item, index in data">
                  <td>{{item.account.degree[0].school.school_abbreviation}}</td>
                  <td>{{item.account.information.first_name + ' ' + item.account.information.last_name}}</td>
                  <td>{{item.account.email}}</td>
                  <td>{{item.account.information.cellular_number}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewAttendeesModal">
                Export
              </button>
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#viewAttendeesModal">
                Close
              </button>
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
    this.retrieve()
  },
  data(){
    return {
      user: AUTH.user,
      config: CONFIG,
      errorMessage: null,
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
          value: this.id,
          column: 'event_id',
          clause: '='
        }]
      }
      this.APIRequest('event_tickets/retrieve_by_attendees', parameter).then(response => {
        if(response.data.length > 0){
          this.data = response.data
        }else{
          this.data = null
        }
      })
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
