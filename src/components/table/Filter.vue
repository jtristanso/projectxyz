<template>
  <div>
    <form v-if="filterInitialized" ref="form" enctype="multipart/form-data" role="form" method="POST">
      <div class="row">
        <div class="col-sm-10 float-right">
          <input-group
            :inputs="filterList"
          >
          </input-group>
        </div>
        <div class="col-sm-2">
          <button @click="filterForm" type="button" class="btn btn-outline-success" ><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
  import Vue from 'vue'
  export default{
    name: '',
    components: {
      'input-cell': require('components/input_field/InputCell.vue'),
      'input-group': require('components/input_field/InputGroup.vue')
    },
    create(){

    },
    mounted(){
      this.initializeFilter()
    },
    data(){
      return {
        filterList: {},
        filterInitialized: false
      }
    },
    props: {
      filter_setting: Object
    },
    methods: {
      initializeFilter(){
        this.filterList = {}
        for(let key in this.filter_setting){
          Vue.set(this.filterList, key, this.filter_setting[key])
          typeof this.filterList[key]['name'] === 'undefined' ? Vue.set(this.filterList[key], 'name', this.StringUnderscoreToPhrase(key)) : ''
          Vue.set(this.filterList[key], 'db_name', key)
          typeof this.filterList[key]['col'] === 'undefined' ? Vue.set(this.filterList[key], 'col', 4) : ''
        }
        this.filterInitialized = true
      },
      filterForm(){
        this.$emit('filter', this.$refs.form)
      }
    }

  }
</script>
<style scoped>

</style>
