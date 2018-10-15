<template>
  <div>
    <transition name="modal">
      <div  v-bind:class="modalShown ? '' : 'closedModal'" >
        <div class="modal-mask" @click="modalShown = false">
          <div v-bind:class="modal_size" class="modal-dialog">
            <div class="modal-content" @click.stop>
              <div class="modal-header bg-primary">
                <slot name="header">

                </slot>
              </div>
              <div class="modal-body">
                <slot name="body">
                  default body
                </slot>
              </div>
              <!-- <div class="modal-footer">
                <slot name="footer">
                </slot>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
  import global from '../../helpers/global'
  export default{
    name: '',
    create(){

    },
    mounted(){
    },
    data(){
      return {
        modalShown: false
      }
    },
    props: {
      modal_size: String
    },
    watch: {
      modalShown: function(value){
        if(value){
          this.modal('show')
        }else{
          this.modal('close')
        }
        this.$emit('change-state', value)
        global.hasShownModal = value
      },
      show_modal: function(value){
        this.modalShown = value
      }
    },
    methods: {
      showModal(){
        this.modalShown = true
      },
      closeModal(){
        this.modalShown = false
      }
    }

  }
</script>
<style scoped>
.modal-mask {
  position: fixed;
  z-index: 100;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  transition: opacity .3s ease;
  overflow-y: scroll;
  padding-top:100px;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  /*margin: 20px 0;*/
}

.modal-default-button {
  float: right;
}


/*
* The following styles are auto-applied to elements with
* transition="modal" when their visibility is toggled
* by Vue.js.
*
* You can easily play with the modal transition by editing
* these styles.
*/

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
.modal-xl{
  max-width: 80%;
  width:100%
}
.closedModal{
  display:none
}
</style>
