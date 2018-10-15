<template>
  <div>
    <div class="row">
      <template v-for="input in inputList">
        <div v-if="input['input_type'] === 'group'" v-bind:class="'col-sm-' + input['col']" >
          <input-group-recursive
            :inputs="input['inputs']"
            :form_data="form_data"
            :form_data_updated="form_data_updated"
            :form_status="form_status"
            :error_list="error_list"
            v-on:form_data_changed="formGroupDataChanged"
          >
          </input-group-recursive>
        </div>
        <div v-else v-bind:class="[input['input_type'] === 'hidden' ? 'hidden' : '', 'col-sm-' + input['col']]" >

          <input-cell
            :input_name="input['input_name']"
            :field_name="input['field_name']"
            :db_name="input['db_name']"
            :input_setting="input['input_setting']"
            :input_type="input['input_type']"
            :input_style="input['input_style']"
            :colspan="input['colspan']"
            :label_colspan="input['label_colspan']"
            :placeholder="input['placeholder']"
            :default_value="input['default_value']"
            :feedback_message="input['feedback_message']"
            :feedback_status="input['feedback_status']"
            :muted_text="input['muted_text']"
            :form_data="form_data"
            :form_data_updated="form_data_updated"
            :form_status="form_status"
            :error_list="error_list"
            v-on:value_changed="valueChanged"
          >
          </input-cell>
        </div>
      </template>
    </div>
  </div>
</template>
<script>
  import Vue from 'vue'
  export default{
    name: '',
    components: {
      'input-cell': require('../input_field/InputCell.vue')
    },
    beforeCreate: function () {
      this.$options.components.inputGroupRecursive = require('./InputGroup.vue')
    },
    create(){

    },
    mounted(){
      this.initializeInput()
    },
    data(){
      return {
        inputList: {},
        valueFunctionList: {},
        inputInitialized: false,
        fieldNameList: {}
      }
    },
    props: {
      inputs: Object,
      form_data: Object,
      form_data_updated: Boolean,
      form_status: String,
      error_list: Object
    },
    watch: {
      form_data_updated(value){
        for(let key in this.inputList){
          let fieldName = this.inputList[key]['field_name']
          let dbName = this.inputList[key]['field_name']

          if(typeof this.valueFunctionList[fieldName] !== 'undefined'){
            let newFormData = this.valueFunctionList[dbName](this.form_data)
            for(let formKey in newFormData){
              this.formDataChanged(this.fieldNameList[formKey], newFormData[formKey])
            }
          }else{
            this.formDataChanged(fieldName, this.form_data[dbName])
          }
        }
      }
    },
    methods: {
      formGroupDataChanged(fieldname, value){
        this.$emit('form_data_changed', fieldname, value)
      },
      valueChanged(e, customName){
        let dbName = typeof customName !== 'undefined' ? customName : $(e.target).attr('name')
        if(typeof this.valueFunctionList[dbName] !== 'undefined'){
          let newFormData = this.valueFunctionList[dbName](this.form_data)
          for(let formKey in newFormData){
            this.formDataChanged(this.fieldNameList[formKey], newFormData[formKey])
          }
        }else{
          this.formDataChanged(this.fieldNameList[dbName], $(e.target).val())
        }

      },
      formDataChanged(fieldName, value){
        this.$emit('form_data_changed', fieldName, this.dataFormat(fieldName, value))
      },
      initializeInput(){
        this.inputList = {}

        for(let key in this.inputs){
          Vue.set(this.inputList, key, this.inputs[key])
          let dbNameTemp = key.split('.')
          let dbName = key
          if(dbNameTemp.length > 1){
            let dbName = dbNameTemp[0]
            for(let x = 1; x < dbNameTemp.length; x++){
              dbName += (dbNameTemp[x] === '*' ? '[]' : '[' + dbNameTemp[x] + ']')
            }
          }
          Vue.set(this.inputList[key], 'field_name', key)
          typeof this.inputList[key]['db_name'] === 'undefined' ? Vue.set(this.inputList[key], 'db_name', dbName) : ''
          typeof this.inputList[key]['input_name'] === 'undefined' ? Vue.set(this.inputList[key], 'input_name', this.StringUnderscoreToPhrase(key)) : ''
          typeof this.inputList[key]['input_type'] === 'undefined' ? Vue.set(this.inputList[key], 'input_type', 'text') : ''
          typeof this.inputList[key]['col'] === 'undefined' ? Vue.set(this.inputList[key], 'col', '12') : ''

          Vue.set(this.inputList[key], 'feedback_status', 0)
          Vue.set(this.inputList[key], 'feedback_message', '')
          if(typeof this.inputList[key]['default_value'] === 'undefined'){
            Vue.set(this.inputList[key], 'default_value', null)
          }
          if(typeof this.inputList[key]['data_format'] === 'undefined'){
            let defaultDataFormat
            switch(this.inputList[key]['input_type']){
              default:
                defaultDataFormat = 'text'
            }
            Vue.set(this.inputList[key], 'data_format', defaultDataFormat)
          }
          this.formDataChanged(this.inputList[key]['field_name'], this.inputList[key]['default_value'])
          if(typeof this.inputList[key]['value_function'] !== 'undefined'){
            this.valueFunctionList[key] = this.inputList[key]['value_function']
          }
          Vue.set(this.fieldNameList, this.inputList[key]['db_name'], this.inputList[key]['field_name'])
        }
        this.inputInitialized = true
      },
      dataFormat(fieldName, value){
        if(typeof fieldName === 'undefined'){
          return null
        }
        let finalValue = value || this.inputList[fieldName]['default_value']
        switch(this.inputList[fieldName]['data_format']){
          case 'decimal':
            return (finalValue * 1).toFixed(2)
          case 'number':
            return finalValue * 1
          default:
            return finalValue
        }
      }
    }

  }
</script>
<style scoped>
.hidden{
  display: none
}
</style>
