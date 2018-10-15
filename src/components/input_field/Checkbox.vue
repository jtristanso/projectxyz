<template>
  <div>
    <div class="form-check">
      <label class="form-check-label">
        <input type="hidden"
        v-bind:name="db_name"
        v-bind:value="form_data[db_name] ? form_data[db_name] : defaultValue"
        >
        <input class="form-check-input" type="checkbox"
        v-bind:value="form_data[db_name] ? form_data[db_name] : defaultValue"
        @change="valueChanged"
        v-bind:readonly="form_status === 'view'"
        >
      </label>
    </div>
  </div>
</template>
<script>
  export default{
    name: '',
    create(){

    },
    mounted(){
      this.defaultValue = this.default_value ? this.default_value : false
    },
    data(){
      return {
        defaultValue: false
      }
    },
    props: {
      input_setting: Object,
      default_value: String,
      db_name: String,
      form_data: Object,
      form_status: String
    },
    methods: {
      valueChanged(e){
        $(e.target).val($(e.target).is(':checked'))
        this.$emit('change', e, this.db_name)
      }
    }

  }
</script>
<style scoped>

</style>
