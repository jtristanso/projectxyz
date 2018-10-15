<template>
  <div class="form-group row" v-bind:class="[feedbackStatus ? 'has-feedback' :'', feedbackStatusClass]">
    <input v-if="inputType === 'hidden'" type="text"
    v-bind:name="dbName"
    v-on:change="valueChanged"
    v-bind:value="form_data[db_name] ? form_data[db_name] : default_value"
    >
    <template v-else>
      <label v-if="labelColspan" class="col-form-label" v-bind:class="'col-sm-' + labelColspan">{{labelText}} :</label>
      <div v-bind:class="'col-sm-' + (12 - (labelColspan !== 12 ? labelColspan : 0))">
        <radio-button
          v-if="inputType === 'radio'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :default_value="default_value"
          >
        </radio-button>
        <check-list
          v-else-if="inputType === 'checklist'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"

          >
        </check-list>
        <select-input
          v-else-if="inputType === 'select'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :form_data="form_data"
          :form_status="form_status"
          :default_value="default_value"
          v-on:change="valueChanged"

          >
        </select-input>
        <single-image
          v-else-if="inputType === 'single_image'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :form_data="form_data"
          :form_status="form_status"
          :form_data_updated="form_data_updated"
          v-on:change="valueChanged"

          >
        </single-image>
        <textarea-input
          v-else-if="inputType === 'textarea'"
          :input_setting="input_setting"
          >
        </textarea-input>
        <check-box
          v-else-if="inputType === 'checkbox'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :form_data="form_data"
          :form_status="form_status"
          :default_value="default_value"
          v-on:change="valueChanged"
          >
        </check-box>
        <select2
          v-else-if="inputType === 'select2'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :form_data="form_data"
          :form_status="form_status"
          :default_value="default_value"
          :placeholder="placeholder"
          v-on:change="valueChanged"
          >
        </select2>
        <table-input
          v-else-if="inputType === 'table-input'"
          :input_setting="input_setting"
          :db_name="dbName"
          :field_name="field_name"
          :form_data="form_data"
          :form_status="form_status"
          :default_value="default_value"
          :placeholder="placeholder"
          :form_data_updated="form_data_updated"
          v-on:change="valueChanged"
          >
        </table-input>
        <template
          v-else-if="inputType === 'static'"
        >
          {{form_data[db_name]}}
        </template>
        <template v-else>
          <input
            v-if="form_status !== 'view'"
            v-bind:name="db_name"
            v-bind:placeholder="inputPlaceholder"
            v-bind:type="inputType"
            class="form-control"
            v-on:change="valueChanged"
            v-bind:value="form_data[db_name] ? form_data[db_name] : default_value"
            >
          <span v-else class="form-control">{{form_data[db_name]}}&nbsp;</span>
        </template>
        <div v-if="feedbackMessage" class="form-control-feedback">{{feedbackMessage}}</div>
        <small v-if="muted_text" class="form-text text-muted">{{muted_text}}</small>
      </div>
    </template>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'radio-button': require('./RadioButton.vue'),
      'check-box': require('./Checkbox.vue'),
      'check-list': require('./CheckList.vue'),
      'select-input': require('./Select.vue'),
      'select2': require('./Select2.vue'),
      'textarea-input': require('./Textarea.vue'),
      'single-image': require('./SingleImage.vue'),
      'table-input': require('./TableInput.vue')
    },
    create(){

    },
    mounted(){
      this.initSetting()
    },
    props: {
      input_name: String,
      db_name: String,
      field_name: String,
      label: String,
      label_style: Object,
      label_colspan: Number,
      form_data: {
        type: Object,
        default: () => {
          return {}
        }
      },
      input_type: String,
      input_setting: Object,
      input_style: Object,
      placeholder: String,
      muted_text: String,
      form_data_updated: Boolean,
      form_status: String,
      default_value: [String, Number],
      error_list: Object
    },
    data(){
      return {
        dbName: null,
        labelText: null,
        labelStyle: {},
        labelColspan: 3,
        inputType: 'text',
        inputSetting: {},
        inputStyle: {},
        inputPlaceholder: null,
        feedbackStatusClass: '',
        feedbackStatus: 0, // 0 - none, 1 - success, 2 - danger, 3 - warning
        feedbackMessage: '',
        value: null

      }
    },
    watch: {
      form_data_updated(value){
        this.feedbackStatus = 0
        this.feedbackMessage = ''
      },
      error_list(value){
        if(typeof this.error_list[this.db_name] !== 'undefined'){
          this.feedbackStatus = 2
          this.feedbackMessage = this.error_list[this.db_name][0]
        }
      },
      feedbackStatus(value){
        this.feedbackMessage = this.feedbackMessage
        switch(value * 1){
          case 1:
            this.feedbackStatusClass = 'has-success'
            break
          case 2:
            this.feedbackStatusClass = 'has-danger'
            break
          case 3:
            this.feedbackStatusClass = 'has-warning'
            break
          default:
            this.feedbackStatusClass = ''
            break
        }
      }
    },
    methods: {
      initSetting(){
        this.dbName = this.db_name
        this.labelText = this.label ? this.label : this.input_name
        this.labelStyle = this.label_style
        this.labelColspan = typeof this.label_colspan !== 'undefined' ? this.label_colspan : 4
        this.inputType = this.input_type ? this.input_type : 'text'
        this.inputStyle = this.input_style
        this.inputPlaceholder = this.placeholder ? this.placeholder : this.input_name
      },
      formDataUpdated(){
      },
      valueChanged(e, customName){
        this.feedbackStatus = 0
        this.feedbackMessage = ''
        this.$emit('value_changed', e, customName)
      }
    }

  }
</script>
<style scoped>

</style>
