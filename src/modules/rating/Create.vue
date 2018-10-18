<template>
    <div class="modal" id="submitRatingModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Submit Rating</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body" style="height: 250px;">
            <span class="error text-center text-danger">
              <label v-if="errorMessage !== null"><b>Opps!</b> {{errorMessage}}</label>
            </span>
            <span class="star-holder">
              <i v-bind:class="{'far': active === 0 || i > active, 'fas text-warning': i <= active}" v-for="i in 5" v-on:click="makeActive(i)" class="fa-star"></i>
              <br>
              Click the stars
            </span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="cancel()">Cancel</button>
            <button type="button" class="btn btn-primary" v-on:click="create()">Submit</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
  import ROUTER from '../../router'
  import Vue from 'vue'
  import AUTH from '../../services/auth'
  export default{
    data(){
      return {
        user: AUTH.user,
        active: 0,
        errorMessage: null
      }
    },
    props: ['payload', 'payloadValue'],
    methods: {
      redirect(parameter){
        ROUTER.push(parameter)
      },
      makeActive(index){
        this.active = index
      },
      cancel(){
        this.active = 0
      },
      create(){
        if(this.active > 0){
          let parameter = {
            payload: this.payload,
            payload_value: this.payloadValue,
            account_id: this.user.userID,
            value: this.active
          }
          this.APIRequest('ratings/create', parameter).then(response => {
            if(response.error !== null){
              this.errorMessage = response.error.message
            }else{
              this.errorMessage = null
              $('#submitRatingModal').modal('hide')
            }
          })
        }else{
          this.errorMessage = 'No stars are selected.'
        }
      }
    }
  }
</script>
<style scoped>
.error{
  width: 100%;
  float: left;
  height: 25px;
}
.star-holder{
  width: 100%;
  float: left;
  text-align: center;
  margin-top: 50px;
}
.fa-star{
  font-size: 50px;
}
.fa-star:hover{
  cursor: pointer;
}
@media (max-width: 991px){
}

</style>
