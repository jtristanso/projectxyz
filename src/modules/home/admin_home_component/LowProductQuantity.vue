<template>
  <div>
  <table class="table table-striped table-hover table-large">
    <thead>
      <tr>
        <th>
          <h5>Low Product Quantity</h5>
        </th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="lowProductQuantity in lowProductQuantityList"
          v-bind:class="(lowProductQuantity['quantity'] <= lowProductQuantity['alert_level']) ? 'danger' :''">
        <td>{{lowProductQuantity['short_name']}}</td>
        <td class="text-right">{{lowProductQuantity['quantity']}}</td>
        <td>{{lowProductQuantity['unit']}}</td>
      </tr>
    </tbody>
  </table>
  <a href="#" class="btn btn-info btn-sm">
    Inventory Adjustment <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
  </a>
</div>
</template>
<script>
export default{
  mounted(){
    this.initLowProductQuantity()
  },
  data(){
    return {
      lowProductQuantityList: []
    }
  },
  methods: {
    initLowProductQuantity(){
      this.lowProductQuantityList = [{
        id: 1,
        short_name: 'milo everyday',
        quantity: 100,
        alert_level: 20,
        unit: 'pcs'
      }, {
        id: 1,
        short_name: 'The Webster Dictionary of the English Language',
        quantity: 20,
        alert_level: 20,
        unit: 'pcs'
      }, {
        id: 4,
        short_name: 'milo everyday',
        quantity: 10,
        alert_level: 30,
        unit: 'pcs'
      }, {
        id: 4,
        short_name: 'milo everyday',
        quantity: 5,
        alert_level: 3,
        unit: 'pcs'
      }]
    }
  }

}
</script>
