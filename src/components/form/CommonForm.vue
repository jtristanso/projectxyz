<template>
  <div>
    <div v-if="messageList.length" class="alert alert-warning">
      <div v-for="message in messageList">
        <strong>{{message['label']}}</strong>
        {{message['message']}}
      </div>
    </div>
    <form ref="form" slot="body" enctype="multipart/form-data" role="form" method="POST">
      <input type='hidden' name="id" v-bind:value='entryID' >
      <input-group
        :inputs="inputs"
        :form_data="formData"
        :form_data_updated="formDataUpdated"
        :form_status="formStatus"
        :error_list="errorList"
        v-on:form_data_changed="valueChanged"
      >
      </input-group>
    </form>
    <div class="row">
      <div class="col-sm-12 text-right">
        <template v-if="formStatus === 'delete_confirmation'">
          <label class="float-left form-label"><strong>Are you sure?</strong></label>
          <button @click="deleteForm" class="btn btn-danger">Yes, delete</button>
          <button @click="formStatus = 'editing'" class="btn btn-default">No</button>
        </template>
        <label v-if="formRequestStatus === 'success'" class="text-success">Success!</label>
        <label v-else-if="formRequestStatus === 'failed'" class="text-danger">Failed!</label>
        <label v-else-if="formRequestStatus === 'loading'" class="text-primary">Please wait...</label>
        <template v-if="formRequestStatus !== 'loading'">
          <template v-if="formStatus === 'view'">
            <button v-if="formData['id'] !== 0" @click="formStatus = 'editing'" v-bind:disabled="formStatus === 'loading'? true : false" type="button" class="btn btn-outline-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>
            <button @click="$emit('form_close')" type="button"  v-bind:disabled="formStatus === 'loading'? true : false" class="btn btn-secondary">Close</button>
          </template>
          <template v-else-if="formStatus === 'editing'">
            <button @click="formStatus = 'delete_confirmation'" v-bind:disabled="formStatus === 'loading'? true : false" type="button" class="btn btn-outline-danger pull-left"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
            <button @click="submitForm" v-bind:disabled="formStatus === 'loading'? true : false" type="button" class="btn btn-outline-success"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
            <button @click="viewForm(formData['id'])" type="button"  v-bind:disabled="formStatus === 'loading'? true : false" class="btn btn-secondary">Cancel</button>
          </template>
          <template v-else-if="formStatus === 'create'">
            <button v-if="formData['id'] === 0" @click="submitForm" v-bind:disabled="formStatus === 'loading'? true : false" type="button" class="btn btn-outline-success"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
            <button v-if="formData['id'] === 0" @click="$emit('form_close')" type="button"  v-bind:disabled="formStatus === 'loading'? true : false" class="btn btn-secondary">Close</button>
          </template>
        </template>
      </div>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  export default{
    name: '',
    components: {
      'input-group': require('../input_field/InputGroup.vue')
    },
    create(){
    },
    mounted(){
      this.initializeLink()
      this.initializeInput()
    },
    data(){
      return {
        /*
          messageList (Array of Object) list of messages to be displayed
          [{
            label: String // label for the message
            message: String // the actual message
          }]
        */
        messageList: [],
        errorList: {},
        /*
          formStatus (String) the status of the form
          view
          loading
          success
          failed
          closed
        */
        formStatus: 'view',
        formRequestStatus: '',
        links: {
          create: '',
          retrieve: '',
          update: '',
          delete: ''
        },
        inputList: {
        },
        formData: {},
        formDataUpdated: false,
        entryID: 0
      }
    },
    props: {
      api: String,
      no_create: Boolean,
      no_delete: Boolean,
      read_only: Boolean,
      inputs: Object,
      entry_id: Number,
      retrieve_parameter: {
        type: Object,
        default: function(){
          return {}
        }
      }
    },
    methods: {
      submitForm(){
        this.formRequestStatus = 'loading'
        this.messageList = []
        let link = (this.formData['id'] * 1) ? this.links.update : this.links.create
        this.APIFormRequest(link, this.$refs.form, (response) => {

          if(response['error']['status'] * 1 === 100){
            this.errorList = response['error']['message']
            for(let field in response['error']['message']){
              let label = field
              if(typeof this.inputList[field] !== 'undefined'){
              }else{
                this.messageList.push({
                  label: label,
                  message: response['error']['message'][field][0]
                })
              }
            }
            this.formRequestStatus = 'failed'
          }else{
            this.formRequestStatus = 'success'
            setTimeout(() => {
              this.formStatus = 'view'
              this.formRequestStatus = ''
              let id = typeof response['data'] === 'object' ? response['data']['id'] : response['data']
              this.viewForm(typeof id === 'boolean' ? this.entryID : id)
            }, 1500)
            this.$emit('form_updated', response['data'])
          }

        }, (response, status) => {
          this.formRequestStatus = 'failed'
        })
      },
      viewForm(id){
        this.entryID = id
        if(id === 0){
          $(this.$refs.form).trigger('reset')
          this.formData = {
            id: 0
          }
          this.formStatus = 'create'
          this.formDataUpdated = !this.formDataUpdated
        }else{
          this.formRequestStatus = 'loading'
          let requestOption = this.retrieve_parameter
          this.retrieve_parameter['id'] = id
          this.APIRequest(this.links.retrieve, requestOption, (response) => {
            if(response['data']){
              this.formData = response['data']
              for(let x in this.valueFunctionList){
                this.valueFunctionList[x]['value_function'](this.formData)
              }
              this.formDataUpdated = !this.formDataUpdated
            }
            this.formStatus = 'view'
            this.formRequestStatus = ''
          })
        }
      },
      deleteForm(){
        let requestOption = {
          id: this.entryID
        }
        this.APIRequest(this.links.delete, requestOption, (response) => {
          if(response['data']){
            this.$emit('form_deleted', this.formData['id'])
            this.$emit('form_close')
          }else{
          }
        })
      },
      initializeLink(){
        if(!this.no_create){
          this.links.create = this.api + '/create'
        }
        this.links.retrieve = this.api + '/retrieve'
        if(!this.read_only){
          this.links.update = this.api + '/update'
        }
        if(!this.no_delete){
          this.links.delete = this.api + '/delete'
        }
      },
      initializeInput(){
        this.inputList = this.inputs
        Vue.set(this.inputList, 'id', {
          db_name: 'id',
          input_type: 'hidden',
          data_format: 'number'
        })
      },
      valueChanged(fieldName, value){
        if(typeof this.formData[fieldName] === 'undefined'){
          Vue.set(this.formData, fieldName, null)
        }
        Vue.set(this.formData, fieldName, value)

      }
    }
  }
</script>
<style scoped>
</style>
