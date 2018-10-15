<template>
  <div>
    <div class="row">
      <div class="col-sm-12 text-right">
        <button @click="createEntry" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
      </div>
    </div>
    <api-table ref="apiTable" v-on:row_clicked="rowClicked" :api="api" :filter_setting="table_setting.filterSetting" :column_setting="table_setting.columnSetting" :retrieve_parameter="table_setting.retrieveParameter"></api-table>
    <modal :modal_size="modalSize" ref="modal" >
      <div slot="header">
        {{modalTitle}}
      </div>
      <div slot="body">
        <common-form ref="commonForm" :api="api" :inputs="form_setting.inputs" v-on:form_close="formClose" v-on:form_deleted="formDeleted" v-on:form_updated="formUpdated" :retrieve_parameter="form_setting.retrieveParameter"></common-form>
      </div>
    </modal>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'api-table': require('components/table/TableComponent.vue'),
      'modal': require('components/modal/Modal.vue'),
      'common-form': require('components/form/CommonForm.vue')
    },
    create(){

    },
    mounted(){
      this.initializeSettings()
    },
    data(){
      return {
        modalTitle: '',
        modalSize: '',
        currentIndex: -1
      }
    },
    props: {
      api: String,
      table_setting: Object,
      form_setting: Object
    },
    methods: {
      initializeSettings(){
        if(typeof this.form_setting.form_title === 'undefined'){
          this.modalTitle = this.StringUnderscoreToPhrase(this.api) + ' Details'
        }else{
          this.modalTitle = this.form_setting.form_title
        }
        this.modalSize = typeof this.form_setting.modal_size !== 'undefined' ? this.form_setting.modal_size : ''

      },
      createEntry(){
        this.currentIndex = -1
        this.$refs.modal.showModal()
        this.$refs.commonForm.viewForm(0)
      },
      formClose(){
        this.$refs.modal.closeModal()
      },
      formUpdated(id){
        this.$refs.apiTable.updateRow(this.currentIndex, id)
      },
      formDeleted(){
        this.$refs.apiTable.deleteRow(this.currentIndex)
      },
      rowClicked(index, id){
        this.currentIndex = index
        this.$refs.commonForm.viewForm(id)
        this.$refs.modal.showModal()
      }
    }

  }
</script>
<style scoped>

</style>
