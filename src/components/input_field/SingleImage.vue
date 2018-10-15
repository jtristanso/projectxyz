<template>
  <div>
    <img @click="triggerFileInput" v-bind:src="imageSrc" v-bind:style="imgStyle">
    <input ref="fileInput" type="file" style="display:none"
      @change="inputFileChanged"
      v-bind:name="db_name"
      >
    <small v-if="form_status !== 'view'">Click the image to select an image file to upload</small>
  </div>
</template>
<script>
  import CONFIG from '../../config.js'
  export default{
    name: '',
    create(){

    },
    mounted(){
      this.initInputSetting()
    },
    data(){
      return {
        imgStyle: {},
        defaultImageSrc: require('../../assets/img/no_image.png'),
        imageSrc: null
      }
    },
    props: {
      input_setting: Object,
      default_value: String,
      db_name: String,
      field_name: String,
      form_data: Object,
      form_status: String,
      form_data_updated: Boolean
    },
    watch: {
      form_data_updated(value){
        let fileName = (this.db_name).replace('_file', '')
        if(this.form_data[fileName]){
          this.imageSrc = CONFIG.IMAGE_URL + 'department_logo/' + this.form_data[fileName]
        }else{
          this.imageSrc = this.defaultImageSrc
        }
      }
    },
    methods: {
      initInputSetting(){
        if(typeof this.input_setting !== 'undefined'){
          this.imgStyle = (typeof this.input_setting['image_style'] !== 'undefined') ? this.input_setting['image_style'] : {width: '100%'}
          this.defaultValue = this.default_value ? this.default_value : null
        }else{
          this.imgStyle = {width: '100%'}
        }
        this.imageSrc = this.defaultImageSrc
      },
      triggerFileInput(){
        if(this.form_status === 'view'){
          return false
        }
        $(this.$refs.fileInput).trigger('click')
      },
      inputFileChanged(e){
        let input = e.target
        if (input.files && input.files[0]) {
          var reader = new FileReader()
          reader.onload = (e) => {
            this.imageSrc = e.target.result
          }
          reader.readAsDataURL(input.files[0])
        }
      },
      valueChanged(e){
        this.$emit('value_changed', e)
      }
    }

  }
</script>
<style scoped>

</style>
