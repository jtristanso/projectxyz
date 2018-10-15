<template>
<div class="section ">
  <div class="row">
    <div class="col-sm-8">
      <div class="row">
        <div class="col-sm-6 text-uppercase">
          <sales-summary></sales-summary>
        </div>
        <div class="col-sm-6">
          <low-product-quantity></low-product-quantity>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="row">
        <div class="col-sm-12">
          <notification></notification>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <user-activity></user-activity>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  components: {
    'sales-summary': require('./admin_home_component/SalesSummary.vue'),
    'low-product-quantity': require('./admin_home_component/LowProductQuantity'),
    'notification': require('./admin_home_component/Notification'),
    'user-activity': require('./admin_home_component/UserActivity')
  },
  mounted() {
    this.initDummy()
  },
  data() {
    return {

    }
  },
  methods: {
    initDummy() {

    }
  }
}
</script>
<style scoped>
.table-large td {
  padding: 5px
}
</style>
