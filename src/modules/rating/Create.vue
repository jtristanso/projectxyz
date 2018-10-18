<template>
    <div class="modal" id="submitRatingModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Submit Rating</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body" style="height: 250px;">
            <span class="star-holder">
              <i v-bind:class="{'far': active === 0 || i > active, 'fas text-warning': i <= active}" v-for="i in 5" v-on:click="makeActive(i)" class="fa-star"></i>
              <br>
              Click the stars
            </span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" v-on:click="create()">Submit</button>
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
        value: null,
        active: 0
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
      create(){
        let parameter = {
          payload: this.payload,
          payload_value: this.payloadValue,
          account_id: this.user.userID,
          value: this.value
        }
        this.APIRequest('ratings/create', parameter).then(response => {
          //
        })
      }
    }
  }
</script>
<style scoped>
.star-holder{
  width: 100%;
  float: left;
  text-align: center;
  margin-top: 75px;
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
