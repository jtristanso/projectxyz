<template>
  <div>
    <api-table :api="api" :filter_setting="filterSetting" :column_setting="columnSetting"></api-table>
  </div>
</template>
<script>
  export default{
    components: {
      'api-table': require('components/table/TableComponent.vue')
    },
    create(){

    },
    mounted(){

    },
    data(){
      let filterSetting = {

      }
      let columnSetting = {
        parent_id: {
          type: 'check'
        },
        description: {},
        icon: {
          type: 'html',
          value_function: (entry) => {
            return '<i class="' + entry['icon'] + '" aria-hidden="true"></i>'
          }
        },
        path: {},
        rank: {
          type: 'number'
        }
      }
      return {
        api: 'modules',
        filterSetting: filterSetting,
        columnSetting: columnSetting
      }
    },
    props: {
    },
    methods: {
    }

  }
</script>
<style scoped>

</style>
