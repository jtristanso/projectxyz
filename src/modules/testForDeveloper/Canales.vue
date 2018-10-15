<template>
   <div class="result-holder">     
      <div class="container-fluid">
         <h6>API: Test Results</h6>
         <div class="container-fluid row">
            <div class="col-4">
              <input type="text" class="form-control" placeholder="Module" v-model="filter">
            </div>
            <div class="col-4">
              <button class="btn btn-primary" v-on:click="getTestResults()"><i class="fa fa-search"></i></button>
            </div>
             <div class="col-4">
              <button class="btn btn-primary pull-right" id="submit" data-toggle="modal" data-target="#addTestModal"><i class="fa fa-plus"></i> Add</button>
             </div>
         </div>
      </div>
      <div class="container-fluid" style="margin-top:50px;">
        <table class="table table-stripe container-fluid">
          <thead class="table-default">
            <tr>
              <th class="col-xs-2">API Path</th>
              <th class="col-xs-6">Test Data</th>
              <th class="col-xs-2">Results</th>
              <th class="col-xs-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ci in currentResult" v-if="currentResult.length !== 0">
               <td>{{ci.path}}</td>
               <td>{{ci.test_data}}</td>
               <td>{{ci.result}}</td>
               <td>
                <button type="button" class="btn btn-primary" v-on:click="saveTest()"><i class="fa fa-save"></i> Save</button>
               </td> 
            </tr>
            <tr v-for="item in testsResult">
              <td class="col-xs-2">{{item.path}}</td>
              <td class="col-xs-6">{{item.parameter}}</td>
              <td class="col-xs-2">{{item.result}}</td>
              <td class="col-xs-2">
                <button type="button" class="btn btn-danger-hallow" v-on:click="deleteTest(item.id)"><i class="fa fa-ban"></i> Delete</button>
              </td>
            </tr>
          </tbody> 
        </table>
      </div>
      <div class="container-fluid">
        <div class="modal fade" id="addTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Test API</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label v-bind:class="textColor">{{message}}</label>
              <form>
                <div class="form-group row"> 
                  <label class="col-2 col-form-label" for="path">Path:</label>
                  <div class="col-8">
                    <input type="text" class="form-control" id="path" v-model="path" placeholder="Path" />
                  </div>
                  <div class="col-2 pull-right">
                    <button type="button" class="btn btn-primary" v-on:click="addRow()"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-primary" v-on:click="addFile()"><i class="fa fa-file"></i></button>
                  </div>
                </div>
                <table class="table" v-if="tempPostData.length !== 0">
                  <thead class="table-default">
                    <td class="col-xs-2">Type</td>
                    <td class="col-xs-3">Array Name</td>
                    <td class="col-xs-3">Column Name</td>
                    <td class="col-xs-3">Value</td>
                    <td class="col-xs-1">Action</td>
                  </thead>
                  <tbody>
                    <tr v-for="(j, index) in tempPostData">
                      <td>
                        <select v-model="j.dataType" class="form-control">
                          <option>Array</option>
                          <option>Single</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="j.arrayName" placeholder="Array Name"  v-if="j.dataType === 'Array'"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="j.columnName" placeholder="Column Name" v-if="j.dataType === 'Array' ||j.dataType === 'Single' "/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="j.value" placeholder="Value" v-if="j.dataType === 'Array' ||j.dataType === 'Single' "/>
                      </td>
                      <td>
                        <button type="button" class="btn btn-default" v-on:click="remove(index)"><i class="fa fa-times"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="row container-fluid form-group" v-if="fileFlag === true">
                     <input type="file" class="form-control-file col-xs-11" v-on:change="file" >
                     <button type="button" class="btn btn-default col-xs-1" v-on:click="removeFile(index)"><i class="fa fa-times"></i></button>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" v-on:click="validate()"><i class="fa fa-cog"></i> Validate</button>
              <button type="button" class="btn btn-primary" v-on:click="send()"><i class="fa fa-sign-in"></i> Send</button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
</template>
<script>
  export default {
    mounted(){
      this.getTestResults()
    },
    data(){
      return{
        testsResult: [],
        currentResult: [],
        path: '',
        result: '',
        message: '',
        flag: false,
        textColor: 'text-danger',
        tempPostData: [],
        postData: {},
        dataType: '',
        arrayName: '',
        columnName: '',
        value: '',
        file: [],
        fileFlag: false,
        filter: 'company'
      }
    },
    methods: {
      getTestResults (){
        let parameter = [{
          'condition': {
            'column': 'path',
            'clause': 'like',
            'value': this.filter + '%'
          }
        }]
        this.APIRequest('api_test_results/retrieve', parameter).then(response => {
          this.testsResult = response.data
        })
      },
      requestHandler(path, parameter){
        this.APIRequest(path, parameter).then(response => {
          this.getTestResults()
        })
      },
      validate(){
        let temp = {}
        let j
        for(j = 0; j < this.tempPostData.length; j++){
          if(this.tempPostData[j].dataType === 'Single'){
            if(JSON.stringify(temp) !== '{}'){
              this.postData[this.tempPostData[j - 1].arrayName] = temp
              temp = {}
            }
            this.postData[this.tempPostData[j].columnName] = this.tempPostData[j].value
          }else{
            if(j > 0 && this.tempPostData[j - 1].arrayName !== this.tempPostData[j].arrayName){
              if(JSON.stringify(temp) !== '{}'){
                this.postData[this.tempPostData[j - 1].arrayName] = temp
                temp = {}
              }
            }
            temp[this.tempPostData[j].columnName] = this.tempPostData[j].value
          }
        }
        if(j === this.tempPostData.length && JSON.stringify(temp) !== '{}'){
          this.postData[this.tempPostData[j - 1].arrayName] = temp
        }
        console.log(JSON.stringify(this.postData))
      },
      send(){
        this.validate()
        this.APIRequest(this.path, this.postData).then(response => {
          this.result = (response.data !== '[]') ? 'Success' : 'Failed'
          let currentResult = {
            'path': this.path,
            'test_data': this.postData,
            'result': this.result
          }
          this.currentResult.push(currentResult)
        })
      },
      saveTest(){
        let parameter = {
          'path': this.path,
          'parameter': JSON.stringify(this.postData),
          'result': this.result
        }
        this.requestHandler('api_test_results/create', parameter)
        this.clearFields()
      },
      deleteTest(id){
        let parameter = {
          'id': id
        }
        this.requestHandler('api_test_results/delete', parameter)
      },
      addRow(){
        let data = {
          'dataType': this.type,
          'arrayName': this.arrayName,
          'columnName': this.columnName,
          'value': this.value
        }
        this.tempPostData.push(data)
      },
      addFile(){
        this.fileFlag = true
      },
      remove(index){
        this.tempPostData.splice(index, 1)
      },
      removeFile(){
        this.fileFlag = false
        this.file = null
      },
      getArrayIndex(array, columnName){
        let i = 0
        while(i < array.length){
          if(array[i].columnName){}
          i++
        }
      },
      clearFields(){
        this.path = ''
        this.currentResult = ''
      }
    }
  }
</script>
<style type="text/css">
  body{
    font-size: 12px;
  }
  .result-holder{
    margin-top: 20px;
  }
  .table .table-default{
    background: #eaeaea;
  }/*-- table .table-inverse --*/
  .btn-primary{
    background: #006600;
    border:0;
  }
  .btn-primary:hover{
    background: #009900;
  }
  .btn:hover{
    cursor: pointer;
  }
</style>
