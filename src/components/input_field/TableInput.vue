/v  <template>
  <div>
    <table class="table">
      <thead>
        <tr>
          <th v-for="(column, index) in columnSetting[0]"
            v-bind:rowspan="(column['sub_columns']) ? 1:2"
            v-bind:colspan="column['sub_columns'] ? column['sub_column_count']  : 1"
            v-on:click="changeSort(0, index)"
          >
            {{column['name']}}
            <!--<span class="pull-right">
              <i v-if="column['sort'] === 0" class="fa fa-sort" aria-hidden="true"></i>
              <i v-else-if="column['sort'] === 1" class="fa fa-sort-asc" aria-hidden="true"></i>
              <i v-else-if="column['sort'] === 2" class="fa fa-sort-desc" aria-hidden="true"></i>
            </span>-->
          </th>
          <th v-if="action['remove_entry']"></th>
        </tr>
        <tr>
          <th v-for="(column, index) in columnSetting[1]"
            v-bind:rowspan="(column['sub_columns']) ? 1:2"
            v-bind:colspan="column['sub_columns'] ? column['sub_column_count']  : 1"
            v-on:click="changeSort(1, index)"
          >
            {{column['name']}}
            <!---<span class="pull-right">
              <i v-if="column['sort'] === 0" class="fa fa-sort" aria-hidden="true"></i>
              <i v-else-if="column['sort'] === 1" class="fa fa-sort-asc" aria-hidden="true"></i>
              <i v-else-if="column['sort'] === 2" class="fa fa-sort-desc" aria-hidden="true"></i>
            </span>-->
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(tableEntry, index) in tableEntries"
          @click="$emit('row_clicked', index, tableEntry['id'])"
        >
          <input type="hidden" v-bind:name="db_name + '[' + index + '][id]'" v-bind:value="tableEntry['id']">
          <td v-for="columnSetting in linearColumnSetting">
            <select v-if="columnSetting['input_type'] === 'select'"
              v-bind:value="(tableEntry[columnSetting['db_name']] || columnSetting['default_value'])"
              class="form-control"
              v-bind:name="db_name + '[' + index + '][' + columnSetting['db_name'] + ']'"
            >
              <option v-for="option in columnSetting['select_option']" v-bind:value="option['value']" v-bind:selected="(tableEntry[columnSetting['db_name']] || columnSetting['default_value'])  === option['value'] ? 'selected' : false">{{option['label']}}</option>
            </select>
            <input v-else
              class="form-control"
              v-model="tableEntry[columnSetting['db_name']]"
              v-bind:name="db_name + '[' + index + '][' + columnSetting['db_name'] + ']'"
            >
          </td>
          <td v-if="action['remove_entry']"><button @click="removeEntry(index, tableEntry['id'])" class="btn btn-sm btn-danger" type="button"><i class="fa fa-remove" aria-hidden="true"></i></button></td>
        </tr>
      </tbody>
      <tfoot>
        <tr v-if="action['add_entry']">
          <td v-bind:colspan="columnCount +( action['add_entry'] ? 1 : 0)">
            <button @click="addEntry" type="button" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
          </td>
        </tr>
      </tfoot>
    </table>
    <input v-for="(id, index) in deletedForeignTable"
      v-bind:value="id"
      v-bind:name="'deleted_foreign_table['+db_name+']['+index+']'"
    >
  </div>
</template>
<script>
  import Vue from 'vue'
  export default{
    name: '',
    create(){

    },
    mounted(){
      this.initConfig()
      this.initColumnSetting()
    },
    data(){
      return {
        columnSetting: [
          [],
          []
        ],
        linearColumnSetting: [],
        tableEntries: [],
        currentSort: null,
        action: {
          add_entry: true,
          remove_entry: true
        },
        columnCount: 0,
        deletedForeignTable: []
      }
    },
    props: {
      form_data_updated: Boolean,
      input_setting: Object,
      default_value: String,
      db_name: String,
      form_data: Object,
      form_status: String,
      placeholder: String
    },
    watch: {
      form_data_updated(value){
        this.tableEntries = this.form_data[this.db_name] ? this.form_data[this.db_name] : []
        this.deletedForeignTable = []
      }
    },
    methods: {
      addEntry(){
        let newEntry = {
          id: 0
        }
        for(let x = 0; x < this.linearColumnSetting.length; x++){
          newEntry[this.linearColumnSetting[x]['db_name']] = null
        }
        this.tableEntries.push(newEntry)
      },
      initColumnSetting(){
        let columnSetting = this.input_setting['column_setting']
        for(let dbName in columnSetting){
          this.columnCount++
          let column = columnSetting[dbName]
          Vue.set(column, 'db_name', typeof column['db_name'] !== 'undefined' ? column['db_name'] : dbName)
          this.initColumn(column)
          this.columnSetting[0].push(column)
          if(!column['sub_columns']){
            this.linearColumnSetting.push(column)
          }else{
            let subCount = 0
            for(let subColDBName in column['sub_columns']){
              subCount++
              let subColumn = column['sub_columns'][subColDBName]
              Vue.set(subColumn, 'db_name', subColDBName)
              this.initColumn(subColumn)
              this.columnSetting[1].push(subColumn)
              this.linearColumnSetting.push(subColumn)
            }
            Vue.set(column, 'sub_column_count', subCount)
          }
        }
      },
      initConfig(){
        if(typeof this.input_setting['action'] !== 'undefined'){
          for(let x = 0; x < this.input_setting['action'].length; x++){
            this.action[this.input_setting['action'][x]] = true
          }
        }
      },
      initColumn(column){
        typeof column['name'] === 'undefined' ? Vue.set(column, 'name', this.StringUnderscoreToPhrase(column['db_name'])) : null
        typeof column['type'] === 'undefined' ? Vue.set(column, 'type', 'text') : null
        typeof column['sort'] === 'undefined' ? Vue.set(column, 'sort', 0) : null
        typeof column['value_function'] === 'undefined' ? Vue.set(column, 'value_function', (row, dbName) => {
          let dbNameSegment = dbName.split('.')
          let value
          if(dbNameSegment.length > 1){
            value = row
            for(let x = 0; x < dbNameSegment.length; x++){
              if(typeof value[dbNameSegment[x]] !== 'undefined'){
                value = value[dbNameSegment[x]]
              }else{
                break
              }
            }
          }else{
            value = row[dbName]
          }
          return value
        }) : null
        typeof column['sub_columns'] === 'undefined' ? Vue.set(column, 'sub_columns', null) : null
      },
      removeEntry(rowIndex, entryID){
        console.log(this.tableEntries.splice(rowIndex, 1))
        if(entryID){
          this.deletedForeignTable.push(entryID)
        }
      }
    }

  }
</script>
<style scoped>

</style>
