<template>
  <div class="panel panel-default">
    <div class="panel-heading">Sales Summary</div>
    <div class="panel-body">
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-6 control-label">Previous Total </label>
          <div class="col-sm-6 text-info text-right">
            <p class="form-control-static"><strong>{{previousGrandTotal}}</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-6 control-label">Grand Total </label>
          <div class="col-sm-6 text-success text-right">
            <p class="form-control-static"><strong>{{grandTotal}}</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-6 control-label">Sales per Hour </label>
          <div class="col-sm-6 text-right">
            <p class="form-control-static"><strong>{{hourlySales}}</strong></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
export default{
  data(){
    return {
      previousGrandTotal: '2,000,000.00',
      grandTotal: '3,000,000.00',
      hourlySales: '400.00'
    }
  }
}
</script>
<style scoped>

</style>
