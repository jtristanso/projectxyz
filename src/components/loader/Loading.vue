<template>
  <div>
    <div class="customModal" id="loading">
      <span class="loading">
        <i class="fas fa-spinner fa-spin"></i>
      </span>
    </div>
  </div>
</template>
<script>
</script>
<style scoped>
.customModal{
  position: fixed;
  background-color: rgba(254, 254, 254, 1);
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 100000 !important;
  opacity: 0.5;
  pointer-events: none;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  transition: all 0.3s;
  text-align: center;
  display: none;
}
.customModal .loading{
  font-size: 50px;
  margin-top: 200px;
  height: 50px;
  float: left;
  width: 100%;
  text-align: center;
  color: #00bff3;
}
</style>
