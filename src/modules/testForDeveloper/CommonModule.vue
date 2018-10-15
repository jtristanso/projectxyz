<template>
  <div>
    <module :api="api" :table_setting="table_setting" :form_setting="form_setting"></module>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'module': require('components/common_module/CommonModule.vue')
    },
    create(){

    },
    mounted(){

    },
    data(){
      let filterSetting = {
        parent_id: {
          label_colspan: 5
        },
        description: {
          label_colspan: 5,
          clause: 'like'
        }
      }
      let columnSetting = {
        parent_id: {
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
      let tableSetting = {
        filterSetting: filterSetting,
        columnSetting: columnSetting
      }
      let formSetting = {
        inputs: {
          parent_id: {},
          description: {},
          icon: {},
          path: {},
          rank: {
            input_type: 'select',
            input_setting: {
              options: [
                {
                  value: null,
                  label: 'Select Rank'
                },
                {
                  value: 1,
                  label: 'One'
                },
                {
                  value: 2,
                  label: 'Two'
                },
                {
                  value: 3,
                  label: 'Three'
                },
                {
                  value: 4,
                  label: 'Four'
                },
                {
                  value: 5,
                  label: 'Five'
                }
              ]
            }
          }
        }
      }
      return {
        api: 'modules',
        table_setting: tableSetting,
        form_setting: formSetting
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
