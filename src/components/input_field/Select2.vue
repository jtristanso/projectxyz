go  <template>
  <div>
    <div v-bind:class="form_status === 'view' ? 'hide' :''">
      <select ref="select" style="width:100%"

        v-bind:name="db_name"
      >
      </select>
    </div>
    <span v-if="form_status === 'view'" class="form-control">hey</span>
  </div>
</template>
<script>
  export default{
    name: '',
    create(){

    },
    mounted(){
      this.initInput()
    },
    data(){
      return {
        value: 1,
        options: {
          results: []
        }
      }
    },
    props: {
      input_setting: Object,
      default_value: String,
      db_name: String,
      form_data: Object,
      form_status: String,
      placeholder: String
    },
    methods: {
      initInput(){
        let vm = this
        let placeholder = (this.placeholder) ? this.placeholder : 'Select ' +
        $(this.$refs.select).select2({
          data: this.value,
          minimumInputLength: 3,
          placeholder: 'Select',
          query: (query) => {
            let requestParam = {
              condition: [
                {
                  column: 'first_name',
                  clause: 'like',
                  value: '%' + query.term + '%'
                }
              ]
            }
            if(typeof query.term !== 'undefined'){
              this.options.results = []
            }
            this.APIRequest('account_information/retrieve', requestParam, (response) => {
              if(response['data']){
                for(let x = 0; x < response['data'].length; x++){
                  this.options.results.push({
                    id: response['data'][x]['id'],
                    text: response['data'][x]['first_name'] + ' ' + response['data'][x]['middle_name'] + ' ' + response['data'][x]['last_name']
                  })
                }
              }
              query.callback(this.options)
            })
          }
        })
        .on('change', function(event){
          vm.value = this.value
          vm.$emit('change', event)
        })
      }
    }

  }
</script>
<style scoped>
  .hide{
    display: none
  }
</style>
