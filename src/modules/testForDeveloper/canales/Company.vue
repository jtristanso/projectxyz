<template>
      <div class="row">
        <h6>API: Test Results</h6>
        <button class="btn btn-primary btn-large pull-right" ><i class="fa fa-plus" v-on:click="addTest()"></i> Add</button>
        <div class="col-xs-12 col-sm-12">
          <table class="table table-stripe table-bordered">
            <thead class="table-default">
              <tr>
                <td>Method</td>
                <td>Inputs</td>
                <td>Results</td>
                <td>Remarks</td>
              </tr>
            </thead> 
            <tbody>
                <tr>
                  <td>
                    <select class="form-control">
                      <option>POST</option>
                      <option>GET</option>
                    </select>
                  </td>
                  <td>
                    <textarea class="form-control">
                    </textarea>
                  </td>
                  <td></td>
                  <td></td>
                </tr>
            </tbody>     
          </table>
        </div>
      </div>
</template>
<script>
  export default {
    mounted(){
    },
    data(){
      return{
        tests: []
      }
    },
    methods: {
      navigateTo(method){
        this.$router.push('/' + method)
      },
      addTest(){
        alert('hello')
      }
    }
  }
</script>
<style type="text/css">
  body{
    font-size: 12px;
  }
  .table .table-default{
    background: #eaeaea;
  }/*-- table .table-inverse --*/
  .row{
    margin: 5px 1px 0 1px;
  }
</style>
